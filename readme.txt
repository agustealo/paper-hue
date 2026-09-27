=== Paper Hue ===
Contributors: agustealo
Tags: blog, custom-logo, custom-menu, featured-images, threaded-comments, translation-ready
Requires at least: 6.4
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.1.0
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A lightweight paper-inspired classic WordPress theme with familiar Customizer controls and a built-in front-page slider.

== Description ==

Paper Hue is a responsive classic WordPress theme designed to work with minimal setup. It keeps the original paper-inspired visual language while providing a front-page slider, an optional featured sticky post, featured-image fallback support, widget areas, custom footer information, and WooCommerce/Jetpack compatibility hooks.

Paper Hue does not collect analytics, transmit theme settings, or add tracking code.

Existing Paper Hue Customizer setting IDs are retained in 1.1.0 so upgrading does not reset saved slider, header, image, front-page, or footer choices. The original Paper Hue logo setting remains readable for existing sites; new sites can use WordPress's standard Site Identity logo control.

== Installation ==

1. In WordPress, go to Appearance > Themes > Add New.
2. Choose Upload Theme, select the Paper Hue ZIP, and install it.
3. Activate Paper Hue.
4. Open Appearance > Customize to configure Paper Hue options.
5. If upgrading from an older Paper Hue release, regenerate thumbnails only if you want previously uploaded images recreated for the registered Paper Hue image sizes.

== Frequently Asked Questions ==

= Does upgrading to 1.1.0 reset my existing Paper Hue settings? =

No. The existing theme-mod IDs are retained for compatibility.

= Does Paper Hue require a block theme or Full Site Editing? =

No. Paper Hue intentionally remains a classic theme so existing sites keep the familiar template and Customizer workflow.

= Does Paper Hue track visitors or send data to a third party? =

No.

= Does the theme support Jetpack or WooCommerce? =

Paper Hue includes compatibility hooks for Jetpack and WooCommerce when those plugins are active.

== Changelog ==

= 1.1.0 - September 26, 2026 =
* Modernize compatibility for WordPress 7.1 and PHP 7.4 through 8.5.
* Fix featured-image registration that could fail on modern PHP.
* Fix the bundled featured-image fallback path.
* Preserve existing Customizer setting IDs while adding sanitization.
* Add standard WordPress custom-logo support with legacy-logo fallback.
* Add wp_body_open() and a skip-to-content link.
* Honor WordPress's static front-page Reading setting.
* Harden slider queries, output escaping, and interactive controls.
* Stop the theme from mutating submitted comment content.
* Replace fixed 2015 asset versions with cache-aware theme asset versions.

= 1.0.0 - February 2020 =
* Original public release line.

== Credits ==

* Paper Hue is based on Underscores, (C) 2012-2017 Automattic, Inc., GPLv2 or later: https://underscores.me/
* normalize.css, (C) Nicolas Gallagher and Jonathan Neal, MIT: https://necolas.github.io/normalize.css/
