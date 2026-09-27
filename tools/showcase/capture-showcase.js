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

(async () => {
  const browser = await chromium.launch({ headless: true });
  const context = await browser.newContext({
    reducedMotion: 'no-preference',
    colorScheme: 'light',
  });
  const page = await context.newPage();

  await capturePage(page, '01-front-page-desktop', `${baseUrl}/`, { width: 1440, height: 1000 });
  await captureElement(page, '02-hero-slider', '.hue-slider-container');
  await captureElement(page, '03-featured-story', '.hue-sticky-row');
  await captureElement(page, '04-recent-articles', '.not-sticky');
  await capturePage(page, '05-single-post', `${baseUrl}/stories-with-texture/`, { width: 1440, height: 1000 });
  await capturePage(page, '06-category-archive', `${baseUrl}/category/stories/`, { width: 1440, height: 1000 });
  await capturePage(page, '07-front-page-mobile', `${baseUrl}/`, { width: 390, height: 844 });

  const manifest = {
    candidateSha: process.env.GITHUB_SHA || null,
    wordpress: '7.1',
    php: '8.3',
    baseUrl,
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
