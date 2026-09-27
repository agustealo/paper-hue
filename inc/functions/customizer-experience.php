<?php
/**
 * Paper Hue Customizer experience refinements.
 *
 * Keeps legacy setting IDs intact while making the Customizer the complete,
 * canonical write surface for Paper Hue presentation settings.
 *
 * @package Paper_Hue
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function paper_hue_customize_slider_details_active() {
	return (bool) get_theme_mod( 'paper_hue_slider', true );
}

function paper_hue_customize_footer_text_active() {
	return (bool) get_theme_mod( 'show_copyright', true );
}

function paper_hue_customize_legacy_logo_active() {
	return absint( get_theme_mod( 'hue_them_logo', 0 ) ) > 0 && 0 === absint( get_theme_mod( 'custom_logo', 0 ) );
}

/**
 * Refine the complete Paper Hue Customizer structure without changing stored
 * option IDs or creating a second settings authority in TCC.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function paper_hue_customize_experience( $wp_customize ) {
	$panel = $wp_customize->get_panel( 'paper_hue_theme_settings' );
	if ( $panel ) {
		$panel->title       = esc_html__( 'Paper Hue', 'paper-hue' );
		$panel->description = esc_html__( 'All Paper Hue presentation settings live here. Theme Control Center summarizes your setup and links into these controls, but never duplicates them.', 'paper-hue' );
		$panel->priority    = 25;
	}

	$sections = array(
		'slider_options' => array(
			'title'       => __( 'Hero Slider', 'paper-hue' ),
			'description' => __( 'Choose slider content, ordering, motion, visibility, and call-to-action behavior. Changes use the existing Paper Hue slider settings.', 'paper-hue' ),
			'priority'    => 10,
		),
		'feat_post' => array(
			'title'       => __( 'Featured Story', 'paper-hue' ),
			'description' => __( 'Choose the homepage spotlight source and the story details Paper Hue shows beneath the hero.', 'paper-hue' ),
			'priority'    => 20,
		),
		'hue_front_page' => array(
			'title'       => __( 'Recent Articles', 'paper-hue' ),
			'description' => __( 'Control the article stream, source, layout, images, metadata, excerpts, button text, and pagination.', 'paper-hue' ),
			'priority'    => 30,
		),
		'hue_banner' => array(
			'title'       => __( 'Page Header', 'paper-hue' ),
			'description' => __( 'Control Paper Hue context titles across pages, posts, archives, search, and error routes.', 'paper-hue' ),
			'priority'    => 40,
		),
		'default_feat_image' => array(
			'title'       => __( 'Fallback Images', 'paper-hue' ),
			'description' => __( 'Choose the image Paper Hue uses when an article does not have a featured image. Leave empty to use the bundled Paper Hue fallback.', 'paper-hue' ),
			'priority'    => 50,
		),
		'footer_settings' => array(
			'title'       => __( 'Footer', 'paper-hue' ),
			'description' => __( 'Control the informational footer content while preserving the existing Paper Hue footer layout.', 'paper-hue' ),
			'priority'    => 60,
		),
	);

	foreach ( $sections as $section_id => $properties ) {
		$section = $wp_customize->get_section( $section_id );
		if ( ! $section ) {
			continue;
		}

		$section->title       = $properties['title'];
		$section->description = $properties['description'];
		$section->priority    = $properties['priority'];
	}

	$slider_toggle = $wp_customize->get_control( 'paper_hue_slider' );
	if ( $slider_toggle ) {
		$slider_toggle->label       = esc_html__( 'Enable Hero Slider', 'paper-hue' );
		$slider_toggle->description = esc_html__( 'Show the classic Paper Hue hero on the posts homepage. Disabling it does not delete any slider configuration.', 'paper-hue' );
		$slider_toggle->priority    = 5;
	}

	$slider_category = $wp_customize->get_control( 'slider_category' );
	if ( $slider_category ) {
		$slider_category->label           = esc_html__( 'Slider Category', 'paper-hue' );
		$slider_category->description     = esc_html__( 'Published posts from this category become slider candidates when Content Source is Selected Category.', 'paper-hue' );
		$slider_category->active_callback = 'paper_hue_customize_slider_details_active';
	}

	$slider_total = $wp_customize->get_control( 's_total' );
	if ( $slider_total ) {
		$slider_total->label           = esc_html__( 'Number of Slides', 'paper-hue' );
		$slider_total->description     = esc_html__( 'Paper Hue supports between 2 and 5 hero slides.', 'paper-hue' );
		$slider_total->active_callback = 'paper_hue_customize_slider_details_active';
	}

	$slider_order = $wp_customize->get_control( 's_order' );
	if ( $slider_order ) {
		$slider_order->label           = esc_html__( 'Sort Direction', 'paper-hue' );
		$slider_order->description     = esc_html__( 'Choose which direction Paper Hue traverses the selected category source.', 'paper-hue' );
		$slider_order->active_callback = 'paper_hue_customize_slider_category_active';
	}

	$slider_orderby = $wp_customize->get_control( 's_order_by' );
	if ( $slider_orderby ) {
		$slider_orderby->label           = esc_html__( 'Order Slides By', 'paper-hue' );
		$slider_orderby->description     = esc_html__( 'Choose the post field used to determine slide order for the selected category source.', 'paper-hue' );
		$slider_orderby->active_callback = 'paper_hue_customize_slider_category_active';
	}

	$featured_toggle = $wp_customize->get_control( 'show_feat_sticky' );
	if ( $featured_toggle ) {
		$featured_toggle->label       = esc_html__( 'Enable Featured Story', 'paper-hue' );
		$featured_toggle->description = esc_html__( 'Show the first-class Featured Story module between the hero and Recent Articles.', 'paper-hue' );
		$featured_toggle->priority    = 5;
	}

	$pagination = $wp_customize->get_control( 'hue_post_nav' );
	if ( $pagination ) {
		$pagination->label       = esc_html__( 'Show Article Pagination', 'paper-hue' );
		$pagination->description = esc_html__( 'Display navigation for additional pages of Recent Articles.', 'paper-hue' );
	}

	$header_title = $wp_customize->get_control( 'hue_header_title' );
	if ( $header_title ) {
		$header_title->label       = esc_html__( 'Show Context Title', 'paper-hue' );
		$header_title->description = esc_html__( 'Display the current archive, page, post, search, or error title inside the Paper Hue header.', 'paper-hue' );
	}

	$fallback = $wp_customize->get_control( 'theme_feat_image' );
	if ( $fallback ) {
		$fallback->label       = esc_html__( 'Article Fallback Image', 'paper-hue' );
		$fallback->description = esc_html__( 'Used only when a post does not provide its own featured image.', 'paper-hue' );
	}

	$legacy_logo = $wp_customize->get_control( 'hue_them_logo' );
	if ( $legacy_logo ) {
		$legacy_logo->description     = esc_html__( 'Compatibility control for sites still using the original Paper Hue logo setting. It disappears after moving to the native WordPress logo.', 'paper-hue' );
		$legacy_logo->active_callback = 'paper_hue_customize_legacy_logo_active';
	}

	$copyright_toggle = $wp_customize->get_control( 'show_copyright' );
	if ( $copyright_toggle ) {
		$copyright_toggle->label       = esc_html__( 'Show Footer Credit', 'paper-hue' );
		$copyright_toggle->description = esc_html__( 'Show the Paper Hue footer information row.', 'paper-hue' );
	}

	$footer_text = $wp_customize->get_control( 'hue_copyright' );
	if ( $footer_text ) {
		$footer_text->label           = esc_html__( 'Custom Footer Text', 'paper-hue' );
		$footer_text->description     = esc_html__( 'Optional text shown in the existing Paper Hue footer treatment.', 'paper-hue' );
		$footer_text->active_callback = 'paper_hue_customize_footer_text_active';
	}
}
add_action( 'customize_register', 'paper_hue_customize_experience', 20 );
