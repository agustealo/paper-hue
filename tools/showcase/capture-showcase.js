'use strict';

const fs = require('fs');
const path = require('path');
const { chromium } = require('playwright');

const baseUrl = process.env.PAPER_HUE_SHOWCASE_URL || 'http://localhost:8888';
const outputDir = path.resolve('artifacts/screenshots');
fs.mkdirSync(outputDir, { recursive: true });

async function settle(page) {
  await page.waitForLoadState('domcontentloaded');
  await page.waitForTimeout(700);
}

async function capturePage(page, name, url, viewport, fullPage = true) {
  await page.setViewportSize(viewport);
  await page.goto(url, { waitUntil: 'domcontentloaded' });
  await settle(page);
  await page.screenshot({ path: path.join(outputDir, `${name}.png`), fullPage });
}

async function captureElement(page, name, selector) {
  const element = page.locator(selector).first();
  await element.waitFor({ state: 'visible' });
  await element.screenshot({ path: path.join(outputDir, `${name}.png`) });
}

async function assertSliderLayout(page) {
  const compatibilityStyleCount = await page.locator('link[href*="paper-hue-slider-modern.css"]').count();
  if (compatibilityStyleCount < 1) {
    throw new Error('Hero Slider compatibility stylesheet is not loaded.');
  }

  const card = page.locator('.hue-slider-container .hue-slide:visible').first();
  await card.waitFor({ state: 'visible' });
  const box = await card.boundingBox();

  if (!box || box.width < 320) {
    throw new Error(`Hero Slider reading card collapsed to ${box ? Math.round(box.width) : 0}px.`);
  }
}

async function authenticateAdmin(page) {
  const authFile = process.env.PAPER_HUE_ADMIN_AUTH_FILE;
  if (!authFile || !fs.existsSync(authFile)) {
    return false;
  }

  const auth = JSON.parse(fs.readFileSync(authFile, 'utf8'));
  if (!auth.username || !auth.password) {
    throw new Error('Admin capture auth file is incomplete.');
  }

  await page.goto(`${baseUrl}/wp-login.php`, { waitUntil: 'domcontentloaded' });
  await page.locator('#user_login').fill(auth.username);
  await page.locator('#user_pass').fill(auth.password);
  await Promise.all([
    page.waitForURL(/wp-admin/),
    page.locator('#wp-submit').click(),
  ]);

  return true;
}

(async () => {
  const browser = await chromium.launch({ headless: true });
  const context = await browser.newContext({
    reducedMotion: 'no-preference',
    colorScheme: 'light',
  });
  const page = await context.newPage();

  await capturePage(page, '01-front-page-desktop', `${baseUrl}/`, { width: 1440, height: 1000 });
  await assertSliderLayout(page);
  await captureElement(page, '02-hero-slider', '.hue-slider-container');
  await captureElement(page, '03-featured-story', '.hue-sticky-row');
  await captureElement(page, '04-recent-articles', '.not-sticky');
  await capturePage(page, '05-single-post', `${baseUrl}/stories-with-texture/`, { width: 1440, height: 1000 });
  await capturePage(page, '06-category-archive', `${baseUrl}/category/stories/`, { width: 1440, height: 1000 });
  await capturePage(page, '07-front-page-mobile', `${baseUrl}/`, { width: 390, height: 844 });

  const authenticated = await authenticateAdmin(page);
  if (authenticated) {
    await capturePage(
      page,
      '08-paper-hue-admin',
      `${baseUrl}/wp-admin/themes.php?page=paper-hue`,
      { width: 1440, height: 1000 }
    );

    await capturePage(
      page,
      '09-customizer-homepage',
      `${baseUrl}/wp-admin/customize.php?url=${encodeURIComponent(`${baseUrl}/`)}&autofocus%5Bsection%5D=slider_options`,
      { width: 1440, height: 1000 },
      false
    );
  }

  const candidateSha =
    process.env.PAPER_HUE_ACTUAL_SHA ||
    process.env.PAPER_HUE_CANDIDATE_SHA ||
    process.env.GITHUB_SHA ||
    null;

  const manifest = {
    candidateSha,
    wordpress: '7.1',
    php: '8.3',
    baseUrl,
    authenticatedAdminProof: authenticated,
    capturedAt: new Date().toISOString(),
    screenshots: fs.readdirSync(outputDir).filter((name) => name.endsWith('.png')).sort(),
  };

  fs.writeFileSync(
    path.resolve('artifacts/showcase-manifest.json'),
    `${JSON.stringify(manifest, null, 2)}\n`,
    'utf8'
  );

  await browser.close();
})().catch((error) => {
  console.error(error);
  process.exit(1);
});
