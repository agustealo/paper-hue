# Paper Hue

![Paper Hue theme screenshot](screenshot.jpg)

Paper Hue is a lightweight, paper-inspired **classic WordPress theme**. The 1.1 line modernizes the original 2020 theme for current WordPress and PHP without replacing its familiar templates, Customizer workflow, or visual identity.

## Compatibility

- WordPress: **6.4+**, tested through **7.1**
- PHP: **7.4+**, with the compatibility target extending through **PHP 8.5**
- Theme model: **Classic theme**
- License: **GPL-3.0-or-later**

Paper Hue intentionally remains a classic theme. This maintenance line is not a block-theme rewrite and does not require existing users to relearn the product.

## Features

- **Appearance → Paper Hue** control center for homepage status, branding, navigation, fallback imagery, widgets, and deep links to the real WordPress settings surfaces
- First-class **Hero Slider** with category/latest/sticky sources, autoplay controls, pause-on-interaction, accessible navigation, reduced-motion behavior, CTA label, excerpt toggle, and retained legacy count/order settings
- First-class **Featured Story** built on native WordPress sticky-post behavior, with optional manual/latest sources, metadata, excerpt, CTA, and Recent Articles exclusion
- Configurable **Recent Articles** source, count, section heading, classic/compact/list layouts, image/excerpt/metadata toggles, CTA label, and canonical pagination
- Configurable featured-image fallback
- Native WordPress custom-logo support while retaining the original Paper Hue logo setting for upgraded sites
- Header-title, pagination, and footer controls in the Customizer
- Widget areas for post and lower-page layouts
- Responsive front-end styles
- Jetpack and WooCommerce compatibility hooks when those plugins are active
- Translation-ready strings and standard WordPress template hooks

## Real browser proof

Paper Hue's 1.1 release line is exercised against a real WordPress 7.1 runtime with Playwright. The governed presentation rail captures the exact candidate SHA and includes desktop, mobile, Hero Slider, Featured Story, Recent Articles, single-post, archive, **Appearance → Paper Hue**, and live **Customizer** states.

[View the full real screenshot gallery](docs/SCREENSHOT-GALLERY.md). These captures come from production `master@76c2aa2d0e5f8a2d7bb281d09e6a9a416fb37a7c`, with the exact checked-out SHA asserted before browser capture.

![Paper Hue real desktop homepage](docs/screenshots/front-page-desktop.png)

<table>
<tr>
<td><img src="docs/screenshots/hero-slider.png" alt="Paper Hue real Hero Slider"></td>
<td><img src="docs/screenshots/paper-hue-admin.png" alt="Paper Hue real admin control center"></td>
</tr>
<tr>
<td><img src="docs/screenshots/front-page-mobile.png" alt="Paper Hue real mobile homepage"></td>
<td><img src="docs/screenshots/customizer-homepage.png" alt="Paper Hue real WordPress Customizer"></td>
</tr>
</table>

The screenshot workflow deliberately rejects mock UI as release evidence. It seeds a disposable WordPress site, imports Paper Hue's bundled imagery, configures the actual theme, captures the rendered product, records runtime versions and provenance, then destroys the runtime.

## Upgrade compatibility

Paper Hue 1.1 keeps the original theme-mod keys for slider, header, featured-image, front-page, logo, and footer options. Existing saved choices therefore remain available after upgrade.

The original `get_hue_image()`, `static_fallback_img()`, and `get_breadcrumb()` helpers are also retained as compatibility wrappers for child themes or custom templates that may already call them.

The theme no longer modifies submitted comment content. Older releases stripped an author's URL and lowercased comments written entirely in uppercase. Content mutation does not belong in a presentation theme, so that behavior is intentionally retired.

## Installation

1. Download or build a ZIP containing the `paper-hue` theme directory.
2. In WordPress, open **Appearance > Themes > Add New > Upload Theme**.
3. Upload the ZIP and activate Paper Hue.
4. Open **Appearance > Paper Hue** for a quick configuration overview.
5. Open **Appearance > Customize** for Hero Slider, Featured Story, Recent Articles, branding, image, header, and footer controls.

If you are upgrading an existing site, regenerating thumbnails is optional. Do it only when you want older uploads recreated for Paper Hue's registered image sizes.

## Development

Paper Hue keeps source Sass under `client-side/sass/` and the compiled production stylesheet at `client-side/css/hue-paper-style.css`. WordPress loads the compiled stylesheet directly; `style.css` remains the required theme metadata file.

The repository quality workflows syntax-check supported PHP versions, validate JavaScript, run WordPress coding/PHP compatibility standards for release candidates, build the exact distribution ZIP, and boot a real WordPress runtime for presentation proof. Changes should preserve the classic-theme UX and existing setting IDs unless a migration is included.

## 1.1 modernization highlights

The 1.1 maintenance line fixes several issues that became significant on modern WordPress/PHP installations and promotes existing Paper Hue ideas into first-class systems:

- corrects invalid post-thumbnail registration that could hard-fail under modern PHP
- fixes the bundled fallback-image filename mismatch
- adds `wp_body_open()` and a skip-to-content link
- adds sanitization to user-controlled Customizer settings
- hardens query values and output escaping
- honors the WordPress static-front-page setting
- uses WordPress's native custom-logo API while retaining the legacy logo choice
- removes the CSS `@import` waterfall by enqueueing the compiled stylesheet directly
- replaces frozen 2015 script versions with cache-aware asset versions
- removes content-mutating comment behavior from the theme runtime
- adds a real Paper Hue admin control center without creating a parallel settings database
- modernizes Hero Slider interaction and accessibility without replacing its established visual classes
- makes Featured Story and Recent Articles configurable while keeping original defaults familiar
- adds governed real-browser screenshot proof for public and authenticated admin states

## Contributing

Pull requests are welcome. Keep changes narrowly scoped, preserve backward compatibility where practical, and avoid moving plugin-like functionality into the theme.

For large behavioral or visual changes, open an issue first so the compatibility impact can be discussed.

## Credits and license

Paper Hue is **GPL-3.0-or-later**. It is based on [Underscores](https://underscores.me/) and includes normalization work derived from [normalize.css](https://necolas.github.io/normalize.css/). See `readme.txt` and `LICENSE` for distribution details.
