<?php
/**
 * Paper Hue Customizer experience refinements.
 *
 * Keeps legacy setting IDs intact while presenting a clearer first-class UI.
 *
 * @package Paper_Hue
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether slider-detail controls should be visible.
 *
 * @return bool
 */
function paper_hue_customize_slider_details_active() {
	return (bool) get_theme_mod( 'paper_hue_slider', true );
}

/**
 * Whether footer custom text should be editable.
 *
 * @return bool
 */
function paper_hue_customize_footer_text_active() {
	return (bool) get_theme_mod( 'show_copyright', true );
}

/**
 * Refine the existing Customizer structure without changing stored option IDs.
 *
 * Runs after the primary Paper Hue Customizer registration.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function paper_hue_customize_experience( $wp_customize ) {
	$panel = $wp_customize->get_panel( 'paper_hue_theme_settings' );
	if ( $panel ) {
		$panel->title       = esc_html__( 'Paper Hue', 'paper-hue' );
		$panel->description = esc_html__( 'Shape the Paper Hue homepage and presentation while keeping the classic paper-material design language.', 'paper-hue' );
	}

	$sections = array(
		'slider_options' => array(
			'title'       => __( 'Hero Slider', 'paper-hue' ),
			'description' => __( 'Control the Paper Hue hero without changing its familiar visual treatment.', 'paper-hue' ),
			'priority'    => 10,
		),
		'feat_post' => array(
			'title'       => __( 'Featured Story', 'paper-hue' ),
			'description' => __( 'Promote a WordPress sticky post as the featured story on the homepage.', 'paper-hue' ),
			'priority'    => 20,
		),
		'hue_front_page' => array(
			'title'       => __( 'Recent Articles', 'paper-hue' ),
			'description' => __( 'Control the article stream that follows the featured content.', 'paper-hue' ),
			'priority'    => 30,
		),
		'hue_banner' => array(
			'title'       => __( 'Page Header', 'paper-hue' ),
			'description' => __( 'Control title presentation in the Paper Hue header treatment.', 'paper-hue' ),
			'priority'    => 40,
		),
		'default_feat_image' => array(
			'title'       => __( 'Fallback Images', 'paper-hue' ),
			'description' => __( 'Choose the image Paper Hue uses when an article has no featured image.', 'paper-hue' ),
			'priority'    => 50,
		),
		'footer_settings' => array(
			'title'       => __( 'Footer', 'paper-hue' ),
			'description' => __( 'Manage the informational content shown in the Paper Hue footer.', 'paper-hue' ),
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
		$slider_toggle->description = esc_html__( 'Show the classic Paper Hue slider at the top of the posts homepage.', 'paper-hue' );
	}

	$slider_category = $wp_customize->get_control( 'slider_category' );
	if ( $slider_category ) {
		$slider_category->label           = esc_html__( 'Slider Category', 'paper-hue' );
		$slider_category->description     = esc_html__( 'Published posts from this category become slider candidates.', 'paper-hue' );
		$slider_category->active_callback = 'paper_hue_customize_slider_details_active';
	}

	$slider_total = $wp_customize->get_control( 's_total' );
	if ( $slider_total ) {
		$slider_total->label           = esc_html__( 'Number of Slides', 'paper-hue' );
		$slider_total->active_callback = 'paper_hue_customize_slider_details_active';
	}

	$slider_order = $wp_customize->get_control( 's_order' );
	if ( $slider_order ) {
		$slider_order->description     = esc_html__( 'Choose which direction Paper Hue traverses the selected source.', 'paper-hue' );
		$slider_order->active_callback = 'paper_hue_customize_slider_details_active';
	}

	$slider_orderby = $wp_customize->get_control( 's_order_by' );
	if ( $slider_orderby ) {
		$slider_orderby->description     = esc_html__( 'Choose the post field used to determine slide order.', 'paper-hue' );
		$slider_orderby->active_callback = 'paper_hue_customize_slider_details_active';
	}

	$featured_toggle = $wp_customize->get_control( 'show_feat_sticky' );
	if ( $featured_toggle ) {
		$featured_toggle->label       = esc_html__( 'Enable Featured Story', 'paper-hue' );
		$featured_toggle->description = esc_html__( 'Uses the newest published WordPress sticky post as Paper Hue’s featured story.', 'paper-hue' );
	}

	$pagination = $wp_customize->get_control( 'hue_post_nav' );
	if ( $pagination ) {
		$pagination->label       = esc_html__( 'Show Article Pagination', 'paper-hue' );
		$pagination->description = esc_html__( 'Display navigation for additional pages of recent articles.', 'paper-hue' );
	}

	$header_title = $wp_customize->get_control( 'hue_header_title' );
	if ( $header_title ) {
		$header_title->label       = esc_html__( 'Show Context Title', 'paper-hue' );
		$header_title->description = esc_html__( 'Display the current archive, page, post, or error title inside the Paper Hue header.', 'paper-hue' );
	}

	$fallback = $wp_customize->get_control( 'theme_feat_image' );
	if ( $fallback ) {
		$fallback->label = esc_html__( 'Article Fallback Image', 'paper-hue' );
	}

	$footer_text = $wp_customize->get_control( 'hue_copyright' );
	if ( $footer_text ) {
		$footer_text->label           = esc_html__( 'Custom Footer Text', 'paper-hue' );
		$footer_text->active_callback = 'paper_hue_customize_footer_text_active';
	}
}
add_action( 'customize_register', 'paper_hue_customize_experience', 20 );
