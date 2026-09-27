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

async function captureCustomizerSection(page, name, sectionId, controlId) {
  await page.setViewportSize({ width: 1440, height: 1000 });
  const targetUrl = `${baseUrl}/wp-admin/customize.php?url=${encodeURIComponent(`${baseUrl}/`)}&autofocus%5Bsection%5D=${encodeURIComponent(sectionId)}`;
  await page.goto(targetUrl, { waitUntil: 'domcontentloaded' });
  await settle(page);

  const targetControl = page.locator(`#customize-control-${controlId}`).first();
  if (!(await targetControl.isVisible())) {
    const sectionButton = page.locator(`#accordion-section-${sectionId} .accordion-section-title`).first();
    await sectionButton.waitFor({ state: 'visible' });
    await sectionButton.click();
  }

  await targetControl.waitFor({ state: 'visible' });
  await page.waitForTimeout(300);
  await page.screenshot({ path: path.join(outputDir, `${name}.png`), fullPage: false });
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

  const title = card.locator('.entry-title');
  const titleBox = await title.boundingBox();
  if (!titleBox || titleBox.width < Math.min(280, box.width * 0.65)) {
    throw new Error(`Hero Slider title is squeezed to ${titleBox ? Math.round(titleBox.width) : 0}px.`);
  }
}

async function assertSliderControls(page, mobile = false) {
  const bar = page.locator('.hue-slider-container .control').first();
  await bar.waitFor({ state: 'visible' });
  const barBox = await bar.boundingBox();
  if (!barBox) throw new Error('Hero Slider control bar has no geometry.');

  const controls = page.locator('.hue-slider-container .control .prev, .hue-slider-container .control .next, .hue-slider-container .control .hue-counter, .hue-slider-container .control .to-content-bttn');
  const count = await controls.count();
  for (let index = 0; index < count; index += 1) {
    const controlBox = await controls.nth(index).boundingBox();
    if (!controlBox) throw new Error(`Hero Slider control ${index + 1} is not measurable.`);
    if (controlBox.x < barBox.x - 1 || controlBox.x + controlBox.width > barBox.x + barBox.width + 1) {
      throw new Error(`Hero Slider control ${index + 1} escapes its nav bar horizontally.`);
    }
    if (controlBox.y < barBox.y - 1 || controlBox.y + controlBox.height > barBox.y + barBox.height + 1) {
      throw new Error(`Hero Slider control ${index + 1} escapes its nav bar vertically.`);
    }
  }

  const continueGlyph = (await bar.locator('.to-content-bttn').innerText()).trim();
  if (!continueGlyph) {
    throw new Error('Hero Slider continue control has no visible glyph.');
  }

  const mode = await bar.evaluate((element) => getComputedStyle(element).gridTemplateAreas);
  if (mobile && !String(mode).includes('dots')) {
    throw new Error('Hero Slider did not switch to its mobile control layout.');
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
  const context = await browser.newContext({ reducedMotion: 'no-preference', colorScheme: 'light' });
  const page = await context.newPage();

  await capturePage(page, '01-front-page-desktop', `${baseUrl}/`, { width: 1440, height: 1000 });
  await assertSliderLayout(page);
  await assertSliderControls(page, false);
  await captureElement(page, '02-hero-slider', '.hue-slider-container');
  await captureElement(page, '03-slider-controls-desktop', '.hue-slider-container .control');
  await captureElement(page, '04-featured-story', '.hue-sticky-row');
  await captureElement(page, '05-recent-articles', '.not-sticky');
  await capturePage(page, '06-single-post', `${baseUrl}/stories-with-texture/`, { width: 1440, height: 1000 });
  await capturePage(page, '07-category-archive', `${baseUrl}/category/stories/`, { width: 1440, height: 1000 });
  await capturePage(page, '08-front-page-mobile', `${baseUrl}/`, { width: 390, height: 844 });
  await assertSliderLayout(page);
  await assertSliderControls(page, true);
  await captureElement(page, '09-slider-controls-mobile', '.hue-slider-container .control');

  const authenticated = await authenticateAdmin(page);
  if (authenticated) {
    await capturePage(page, '10-theme-control-center', `${baseUrl}/wp-admin/themes.php?page=paper-hue`, { width: 1440, height: 1000 });
    await captureCustomizerSection(page, '11-customizer-hero-slider', 'slider_options', 'paper_hue_slider');
    await captureCustomizerSection(page, '12-customizer-recent-articles', 'hue_front_page', 'paper_hue_recent_heading');
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

  fs.writeFileSync(path.resolve('artifacts/showcase-manifest.json'), `${JSON.stringify(manifest, null, 2)}\n`, 'utf8');
  await browser.close();
})().catch((error) => {
  console.error(error);
  process.exit(1);
});
