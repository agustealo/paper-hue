#!/usr/bin/env bash
set -euo pipefail

wp() {
  npx wp-env run cli wp "$@"
}

wp theme activate paper-hue
wp option update blogname 'Paper Hue Journal'
wp option update blogdescription 'Stories with texture, color, and character.'
wp option update show_on_front posts
wp option update posts_per_page 6
wp rewrite structure '/%postname%/' --hard

CATEGORY_ID="$(wp term create category Stories --slug=stories --porcelain || wp term get category stories --field=term_id --by=slug)"

import_image() {
  local image="$1"
  local title="$2"
  wp media import "wp-content/themes/paper-hue/client-side/img/${image}" --title="${title}" --porcelain
}

IMG1="$(import_image banner1.jpeg 'Paper Hue texture one')"
IMG2="$(import_image banner2.jpeg 'Paper Hue texture two')"
IMG3="$(import_image banner3.jpeg 'Paper Hue texture three')"
IMG4="$(import_image banner4.jpeg 'Paper Hue texture four')"

create_post() {
  local title="$1"
  local slug="$2"
  local excerpt="$3"
  local body="$4"
  local image_id="$5"
  local post_id
  post_id="$(wp post create --post_type=post --post_status=publish --post_title="${title}" --post_name="${slug}" --post_excerpt="${excerpt}" --post_content="${body}" --post_category="${CATEGORY_ID}" --porcelain)"
  wp post meta update "${post_id}" _thumbnail_id "${image_id}" >/dev/null
  echo "${post_id}"
}

POST1="$(create_post 'Stories With Texture' 'stories-with-texture' 'A closer look at tactile visual storytelling and the quiet details that make an article memorable.' '<p>Paper Hue was built around readable stories, strong imagery, and a calm editorial rhythm. This demo content exercises the real theme templates rather than a mock presentation.</p><p>The result should feel familiar to long-time Paper Hue users while the machinery underneath is thoroughly modern.</p>' "${IMG1}")"
POST2="$(create_post 'Color, Type, and Breathing Room' 'color-type-breathing-room' 'How color, typography, and whitespace work together in a lightweight publishing theme.' '<p>Good publishing interfaces create hierarchy without shouting. Paper Hue keeps its recognizable cards and paper-inspired presentation while gaining stronger controls.</p>' "${IMG2}")"
POST3="$(create_post 'The Featured Story' 'the-featured-story' 'A dedicated homepage spotlight that still uses normal WordPress content and familiar publishing workflows.' '<p>The Featured Story is powered by ordinary posts. It can follow sticky posts, the latest post, or a manually selected article.</p>' "${IMG3}")"
POST4="$(create_post 'A Modern Classic Theme' 'a-modern-classic-theme' 'Classic WordPress architecture, updated for current PHP, accessibility, performance, and administration expectations.' '<p>Modern does not have to mean unfamiliar. Paper Hue remains a classic theme with a more capable control plane.</p>' "${IMG4}")"
POST5="$(create_post 'Recent Articles, Refined' 'recent-articles-refined' 'The familiar article grid now has first-class source, layout, metadata, and pagination controls.' '<p>The original layout remains the default. Compact cards and list mode are optional variations, not forced redesigns.</p>' "${IMG2}")"
POST6="$(create_post 'Paper Hue on Mobile' 'paper-hue-on-mobile' 'The same editorial personality, presented cleanly on smaller screens.' '<p>Responsive proof belongs in the release evidence, not in assumptions. The screenshot harness captures both desktop and mobile states.</p>' "${IMG1}")"
POST7="$(create_post 'Publishing Without Friction' 'publishing-without-friction' 'A simple WordPress workflow with richer theme-level presentation options.' '<p>The best admin experience keeps ordinary WordPress concepts recognizable while making theme features easier to discover and configure.</p>' "${IMG4}")"

wp option update sticky_posts "[${POST3}]" --format=json >/dev/null

wp theme mod set paper_hue_slider 1 >/dev/null
wp theme mod set paper_hue_slider_source category >/dev/null
wp theme mod set slider_category "${CATEGORY_ID}" >/dev/null
wp theme mod set s_total 4 >/dev/null
wp theme mod set s_order DESC >/dev/null
wp theme mod set s_order_by date >/dev/null
wp theme mod set paper_hue_slider_autoplay 1 >/dev/null
wp theme mod set paper_hue_slider_delay 5000 >/dev/null
wp theme mod set paper_hue_slider_pause 1 >/dev/null
wp theme mod set paper_hue_slider_excerpt 1 >/dev/null
wp theme mod set paper_hue_slider_arrows 1 >/dev/null
wp theme mod set paper_hue_slider_dots 1 >/dev/null
wp theme mod set paper_hue_slider_cta 'Read Story' >/dev/null

wp theme mod set show_feat_sticky 1 >/dev/null
wp theme mod set paper_hue_featured_story_source manual >/dev/null
wp theme mod set paper_hue_featured_story_post_id "${POST3}" >/dev/null
wp theme mod set paper_hue_featured_story_excerpt 1 >/dev/null
wp theme mod set paper_hue_featured_story_meta 1 >/dev/null
wp theme mod set paper_hue_featured_story_exclude_recent 1 >/dev/null
wp theme mod set paper_hue_featured_story_cta 'Explore Story' >/dev/null

wp theme mod set paper_hue_recent_heading 'Recent Articles' >/dev/null
wp theme mod set paper_hue_recent_source category >/dev/null
wp theme mod set paper_hue_recent_category "${CATEGORY_ID}" >/dev/null
wp theme mod set paper_hue_recent_per_page 6 >/dev/null
wp theme mod set paper_hue_recent_layout classic >/dev/null
wp theme mod set paper_hue_recent_show_image 1 >/dev/null
wp theme mod set paper_hue_recent_show_excerpt 1 >/dev/null
wp theme mod set paper_hue_recent_show_meta 1 >/dev/null
wp theme mod set paper_hue_recent_cta 'Read More' >/dev/null
wp theme mod set hue_post_nav 1 >/dev/null
wp theme mod set hue_header_title 1 >/dev/null
wp theme mod set theme_feat_image "${IMG4}" >/dev/null

MENU_ID="$(wp menu create 'Primary Navigation' --porcelain || wp menu list --fields=term_id,name --format=csv | awk -F, '$2=="Primary Navigation" {print $1; exit}')"
wp menu item add-custom "${MENU_ID}" 'Home' 'http://localhost:8888/' >/dev/null
wp menu item add-custom "${MENU_ID}" 'Stories' 'http://localhost:8888/category/stories/' >/dev/null
wp menu location assign "${MENU_ID}" main-menu >/dev/null

wp rewrite flush --hard >/dev/null

mkdir -p artifacts
cat > artifacts/showcase-state.json <<JSON
{
  "categoryId": ${CATEGORY_ID},
  "featuredPostId": ${POST3},
  "singlePostSlug": "stories-with-texture",
  "categorySlug": "stories"
}
JSON
