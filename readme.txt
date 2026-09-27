=== Paper Hue ===
Contributors: agustealo
Tags: blog, custom-logo, custom-menu, featured-images, threaded-comments, translation-ready
Requires at least: 6.4
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.1.0
License: GNU General Public License v3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

A lightweight paper-inspired classic WordPress theme with familiar Customizer controls and a built-in front-page slider.

== Description ==

Paper Hue is a responsive classic WordPress theme designed to work with minimal setup. It keeps the original paper-inspired visual language while providing a first-class Hero Slider, Featured Story, configurable Recent Articles, featured-image fallback support, widget areas, custom footer information, and WooCommerce/Jetpack compatibility hooks.

Paper Hue does not collect analytics, transmit theme settings, or add tracking code.

Existing Paper Hue Customizer setting IDs are retained in 1.1.0 so upgrading does not reset saved slider, header, image, front-page, or footer choices. The original Paper Hue logo setting remains readable for existing sites; new sites can use WordPress's standard Site Identity logo control.

== Installation ==

1. In WordPress, go to Appearance > Themes > Add New.
2. Choose Upload Theme, select the Paper Hue ZIP, and install it.
3. Activate Paper Hue.
4. Open Appearance > Paper Hue for the configuration overview.
5. Open Appearance > Customize to configure homepage and presentation options.
6. If upgrading from an older Paper Hue release, regenerate thumbnails only if you want previously uploaded images recreated for the registered Paper Hue image sizes.

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

= 1.1.0 - September 27, 2026 =
* Modernize compatibility for WordPress 7.1 and PHP 7.4 through 8.5.
* Add an Appearance > Paper Hue control center without duplicating Customizer settings.
* Promote the homepage slider into a configurable Hero Slider with modern accessibility and reduced-motion behavior.
* Promote sticky content into a configurable Featured Story while preserving the original sticky-post default.
* Add first-class Recent Articles source, count, layout, image, excerpt, metadata, CTA, and pagination controls.
* Fix featured-image registration that could fail on modern PHP.
* Replace the legacy bundled JPEG fallback with a repository-authored Paper Hue SVG fallback.
* Preserve existing Customizer setting IDs while adding sanitization.
* Add standard WordPress custom-logo support with legacy-logo fallback.
* Add wp_body_open() and a skip-to-content link.
* Honor WordPress's static front-page Reading setting.
* Harden queries, output escaping, and interactive controls.
* Stop the theme from mutating submitted comment content.
* Replace fixed 2015 asset versions with cache-aware theme asset versions.
* Add governed real-browser presentation proof for desktop, mobile, admin, and Customizer states.
* Exclude legacy demo JPEG banners with undocumented upstream provenance from the release package.

= 1.0.0 - February 2020 =
* Original public release line.

== Credits ==

* Paper Hue is licensed under GNU GPLv3 or later. See LICENSE.
* Paper Hue is based on Underscores, (C) 2012-2017 Automattic, Inc., GPLv2 or later: https://underscores.me/
* normalize.css, (C) Nicolas Gallagher and Jonathan Neal, MIT: https://necolas.github.io/normalize.css/
* client-side/img/paper-hue-fallback.svg is original Paper Hue project artwork created for the 1.1.0 release and distributed under GNU GPLv3 or later with the theme.
* screenshot.jpg is the Paper Hue project theme screenshot first committed by the project author on January 5, 2020 (repository commit 97b14ca13a60c9c90febe3cf28f20d395260c160) and distributed under GNU GPLv3 or later with the theme.
* Legacy banner1.jpeg through banner4.jpeg and paper_hue_fallback.jpeg remain only in repository history/source and are intentionally excluded from the 1.1.0 distribution package because their upstream image provenance is not documented.
