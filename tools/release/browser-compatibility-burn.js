'use strict';

const fs = require('fs');
const { spawnSync } = require('child_process');
const { chromium } = require('playwright');

const baseUrl = process.env.PAPER_HUE_BURN_URL || 'http://localhost:8888';
const state = JSON.parse(fs.readFileSync('artifacts/showcase-state.json', 'utf8'));

function fail(message) {
  throw new Error(message);
}

function wp(args) {
  const result = spawnSync('npx', ['wp-env', 'run', 'cli', 'wp', ...args], {
    encoding: 'utf8',
    stdio: ['ignore', 'pipe', 'pipe'],
  });
  if (result.status !== 0) {
    fail(`wp ${args.join(' ')} failed:\n${result.stdout}\n${result.stderr}`);
  }
  return result.stdout.trim();
}

function modSet(name, value) {
  wp(['theme', 'mod', 'set', name, String(value)]);
}

function modRemove(name) {
  wp(['theme', 'mod', 'remove', name]);
}

async function open(page, path = '/') {
  const response = await page.goto(`${baseUrl}${path}`, { waitUntil: 'domcontentloaded' });
  if (!response || response.status() >= 400) {
    fail(`${path} returned ${response ? response.status() : 'no response'}`);
  }
  await page.waitForTimeout(250);
}

async function expectCount(page, selector, expected, label) {
  const count = await page.locator(selector).count();
  if (count !== expected) fail(`${label}: expected ${expected}, got ${count}`);
}

(async () => {
  const browser = await chromium.launch({ headless: true });
  const context = await browser.newContext({ colorScheme: 'light' });
  const page = await context.newPage();

  // Latest-posts front page and slider count choices 2–5.
  wp(['option', 'update', 'show_on_front', 'posts']);
  modSet('paper_hue_slider', 1);
  modSet('paper_hue_slider_source', 'category');
  modSet('slider_category', state.categoryId);
  for (const total of [2, 3, 4, 5]) {
    modSet('s_total', total);
    await open(page, '/');
    await expectCount(page, '.hue-slide-item', total, `slider count ${total}`);
  }

  // Every supported order/order-by choice must survive validated query construction.
  const orderProof = wp(['eval', `
    $orders = array('ASC','DESC');
    $orderbys = array('title','id','date','modified','author','rand','comment_count');
    foreach ($orders as $order) {
      foreach ($orderbys as $orderby) {
        set_theme_mod('paper_hue_slider_source','category');
        set_theme_mod('slider_category', ${Number(state.categoryId)});
        set_theme_mod('s_order',$order);
        set_theme_mod('s_order_by',$orderby);
        $args=(new Paper_Hue_Slider())->query_args();
        $expected = 'id' === $orderby ? 'ID' : $orderby;
        if ($args['order'] !== $order || $args['orderby'] !== $expected) { throw new Exception($order . '/' . $orderby); }
      }
    }
    echo 'ORDER_MATRIX_OK';
  `]);
  if (!orderProof.includes('ORDER_MATRIX_OK')) fail('Slider order matrix did not complete.');

  // Disabled and empty-category slider paths.
  modSet('paper_hue_slider', 0);
  await open(page, '/');
  await expectCount(page, '[data-paper-hue-slider="1"]', 0, 'disabled slider');
  modSet('paper_hue_slider', 1);
  modSet('paper_hue_slider_source', 'category');
  modSet('slider_category', 99999999);
  await open(page, '/');
  await expectCount(page, '.hue-slide-item', 0, 'empty category slider');
  modSet('slider_category', state.categoryId);

  // Sticky Featured Story present/absent and paged front page suppression.
  modSet('show_feat_sticky', 1);
  modSet('paper_hue_featured_story_source', 'sticky');
  wp(['option', 'update', 'sticky_posts', '[]', '--format=json']);
  await open(page, '/');
  await expectCount(page, '.hue-sticky-row', 0, 'featured story without sticky post');
  wp(['option', 'update', 'sticky_posts', `[${state.featuredPostId}]`, '--format=json']);
  await open(page, '/');
  if ((await page.locator('.hue-sticky-row').count()) < 1) fail('Featured sticky story did not render.');
  await open(page, '/page/2/');
  await expectCount(page, '[data-paper-hue-slider="1"]', 0, 'paged front slider');
  await expectCount(page, '.hue-sticky-row', 0, 'paged featured story');

  // Static front page honors Reading settings.
  const frontPageId = wp(['post', 'create', '--post_type=page', '--post_status=publish', '--post_title=Compatibility Front Page', '--post_content=Static front page proof.', '--porcelain']);
  wp(['option', 'update', 'show_on_front', 'page']);
  wp(['option', 'update', 'page_on_front', frontPageId]);
  await open(page, '/');
  if (!(await page.locator('body').innerText()).includes('Compatibility Front Page')) fail('Static front page did not render.');
  wp(['option', 'update', 'show_on_front', 'posts']);

  // Legacy logo and native custom logo both remain valid.
  const logoId = wp(['media', 'import', 'wp-content/themes/paper-hue/screenshot.jpg', '--title=Compatibility Logo', '--porcelain']);
  modRemove('custom_logo');
  modSet('hue_them_logo', logoId);
  await open(page, '/');
  if ((await page.locator('.paper-hue-legacy-logo img').count()) !== 1) fail('Legacy hue_them_logo did not render.');
  modSet('custom_logo', logoId);
  await open(page, '/');
  if ((await page.locator('.custom-logo-link:not(.paper-hue-legacy-logo) img').count()) !== 1) fail('Native custom logo did not render.');
  modRemove('custom_logo');
  modRemove('hue_them_logo');

  // Bundled and user-configured fallback images on the homepage card surface.
  const noImageId = wp(['post', 'create', '--post_type=post', '--post_status=publish', '--post_title=Fallback Proof', '--post_name=fallback-proof', '--post_content=Fallback image proof.', '--post_category=' + state.categoryId, '--porcelain']);
  modRemove('theme_feat_image');
  await open(page, '/');
  const noImageCard = page.locator(`#post-${noImageId}`).locator('xpath=..');
  const bundledSrc = await noImageCard.locator('img.fallback-image').first().getAttribute('src');
  if (!bundledSrc || !bundledSrc.includes('paper-hue-fallback.svg')) fail('Bundled fallback SVG did not render on a recent-article card.');
  modSet('theme_feat_image', logoId);
  await open(page, '/');
  const customSrc = await page.locator(`#post-${noImageId}`).locator('xpath=..').locator('img.fallback-image').first().getAttribute('src');
  if (!customSrc || customSrc.includes('paper-hue-fallback.svg')) fail('Configured fallback image did not override bundled fallback on a recent-article card.');

  // Routes: category, search, page, child page, single, comments, archive, 404.
  const parentId = wp(['post', 'create', '--post_type=page', '--post_status=publish', '--post_title=Parent Proof', '--post_name=parent-proof', '--post_content=Parent page proof.', '--porcelain']);
  wp(['post', 'create', '--post_type=page', '--post_status=publish', '--post_title=Child Proof', '--post_name=child-proof', '--post_parent=' + parentId, '--post_content=Child page proof.', '--porcelain']);
  wp(['comment', 'create', '--comment_post_ID=' + state.featuredPostId, '--comment_author=Compatibility Bot', '--comment_author_email=compat@example.test', '--comment_content=Comment route proof.', '--comment_approved=1']);
  const seededPostDate = wp(['post', 'get', String(state.featuredPostId), '--field=post_date']);
  const dateMatch = seededPostDate.match(/^(\d{4})-(\d{2})-/);
  if (!dateMatch) fail(`Unable to derive archive date from ${seededPostDate}.`);
  const archivePath = `/${dateMatch[1]}/${dateMatch[2]}/`;
  for (const path of [
    '/category/stories/',
    '/?s=Stories',
    '/parent-proof/',
    '/parent-proof/child-proof/',
    '/stories-with-texture/',
    archivePath,
  ]) {
    await open(page, path);
  }
  const notFoundResponse = await page.goto(`${baseUrl}/this-route-must-404/`, { waitUntil: 'domcontentloaded' });
  if (!notFoundResponse || notFoundResponse.status() !== 404) fail('404 route did not return HTTP 404.');
  await open(page, '/the-featured-story/');
  if (!(await page.locator('body').innerText()).includes('Comment route proof.')) fail('Approved comment did not render.');

  // Widget areas are registered.
  const sidebarProof = wp(['eval', `global $wp_registered_sidebars; if (empty($wp_registered_sidebars['widget-bottom-1'])) { throw new Exception('widget-bottom-1 missing'); } echo 'WIDGET_OK';`]);
  if (!sidebarProof.includes('WIDGET_OK')) fail('Widget registration proof failed.');

  // Skip link must be keyboard reachable and visible when focused.
  await open(page, '/');
  await page.keyboard.press('Tab');
  const activeClass = await page.evaluate(() => document.activeElement && document.activeElement.className);
  if (!String(activeClass).includes('skip-link')) fail('Skip link is not the first keyboard focus target.');
  const skipBox = await page.locator('.skip-link').boundingBox();
  if (!skipBox || skipBox.width < 1 || skipBox.height < 1) fail('Focused skip link is not visible.');

  // Mobile menu must be a real keyboard-operable disclosure, not hover-only.
  await page.setViewportSize({ width: 390, height: 844 });
  await open(page, '/');
  const toggle = page.locator('.paper-hue-menu-toggle');
  await toggle.focus();
  if ((await toggle.getAttribute('aria-expanded')) !== 'false') fail('Mobile menu initial aria-expanded is not false.');
  await page.keyboard.press('Enter');
  if ((await toggle.getAttribute('aria-expanded')) !== 'true') fail('Enter did not open mobile menu.');
  if (!(await page.locator('#primary-menu').isVisible())) fail('Mobile menu is not visible after keyboard activation.');
  await page.keyboard.press('Escape');
  if ((await toggle.getAttribute('aria-expanded')) !== 'false') fail('Escape did not close mobile menu.');
  if (await page.locator('#primary-menu').isVisible()) fail('Mobile menu remained visible after Escape closed the disclosure.');
  const overflow = await page.evaluate(() => document.documentElement.scrollWidth - document.documentElement.clientWidth);
  if (overflow > 1) fail(`Mobile reflow has ${overflow}px horizontal overflow.`);

  // Slider controls expose buttons and support keyboard arrows.
  await page.setViewportSize({ width: 1440, height: 1000 });
  modSet('paper_hue_slider', 1);
  modSet('paper_hue_slider_source', 'category');
  modSet('slider_category', state.categoryId);
  modSet('s_total', 3);
  modSet('paper_hue_slider_show_arrows', 1);
  modSet('paper_hue_slider_show_dots', 1);
  await open(page, '/');
  const slider = page.locator('[data-paper-hue-slider="1"]');
  if ((await slider.locator('button').count()) < 3) fail('Slider controls are missing buttons.');
  const initialCounter = await slider.locator('.counter').textContent();
  await slider.locator('button').first().focus();
  await page.keyboard.press('ArrowRight');
  const nextCounter = await slider.locator('.counter').textContent();
  if (initialCounter === nextCounter) fail('Slider keyboard ArrowRight did not advance.');

  // Reduced-motion users must not autoplay.
  const reducedContext = await browser.newContext({ reducedMotion: 'reduce' });
  const reducedPage = await reducedContext.newPage();
  modSet('paper_hue_slider_autoplay', 1);
  modSet('paper_hue_slider_interval', 2000);
  await open(reducedPage, '/');
  const reducedCounter = reducedPage.locator('.counter');
  const before = await reducedCounter.textContent();
  await reducedPage.waitForTimeout(2400);
  const after = await reducedCounter.textContent();
  if (before !== after) fail('Reduced-motion slider autoplay advanced unexpectedly.');
  await reducedContext.close();

  console.log('PAPER_HUE_BROWSER_COMPATIBILITY_OK');
  await browser.close();
})().catch((error) => {
  console.error(error);
  process.exit(1);
});
