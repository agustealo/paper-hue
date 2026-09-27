<?php
/**
 * First-class Hero Slider Customizer controls.
 *
 * @package Paper_Hue
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sanitize slider source.
 *
 * @param mixed $value Raw value.
 * @return string
 */
function paper_hue_sanitize_slider_source( $value ) {
	$value = sanitize_key( $value );
	return in_array( $value, array( 'category', 'latest', 'sticky' ), true ) ? $value : 'category';
}

/**
 * Sanitize slider interval.
 *
 * @param mixed $value Raw value.
 * @return int
 */
function paper_hue_sanitize_slider_interval( $value ) {
	$value = absint( $value );
	return min( 12000, max( 2000, $value ) );
}

/**
 * Whether category-only controls should be visible.
 *
 * @return bool
 */
function paper_hue_customize_slider_category_active() {
	return paper_hue_customize_slider_details_active() && 'category' === get_theme_mod( 'paper_hue_slider_source', 'category' );
}

/**
 * Whether autoplay controls should be visible.
 *
 * @return bool
 */
function paper_hue_customize_slider_autoplay_active() {
	return paper_hue_customize_slider_details_active() && (bool) get_theme_mod( 'paper_hue_slider_autoplay', true );
}

/**
 * Register advanced slider settings while keeping existing IDs intact.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function paper_hue_customize_slider_controls( $wp_customize ) {
	$wp_customize->add_setting(
		'paper_hue_slider_source',
		array(
			'default'           => 'category',
			'transport'         => 'refresh',
			'sanitize_callback' => 'paper_hue_sanitize_slider_source',
		)
	);
	$wp_customize->add_control(
		'paper_hue_slider_source',
		array(
			'label'           => esc_html__( 'Content Source', 'paper-hue' ),
			'description'     => esc_html__( 'Choose where Paper Hue gets slider stories.', 'paper-hue' ),
			'section'         => 'slider_options',
			'type'            => 'select',
			'priority'        => 15,
			'active_callback' => 'paper_hue_customize_slider_details_active',
			'choices'         => array(
				'category' => esc_html__( 'Selected Category', 'paper-hue' ),
				'latest'   => esc_html__( 'Latest Posts', 'paper-hue' ),
				'sticky'   => esc_html__( 'Sticky Posts', 'paper-hue' ),
			),
		)
	);

	$category_control = $wp_customize->get_control( 'slider_category' );
	if ( $category_control ) {
		$category_control->priority        = 20;
		$category_control->active_callback = 'paper_hue_customize_slider_category_active';
	}

	$wp_customize->add_setting(
		'paper_hue_slider_autoplay',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'paper_hue_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'paper_hue_slider_autoplay',
		array(
			'label'           => esc_html__( 'Autoplay', 'paper-hue' ),
			'description'     => esc_html__( 'Advance through stories automatically. Reduced-motion visitors are respected automatically.', 'paper-hue' ),
			'section'         => 'slider_options',
			'type'            => 'checkbox',
			'priority'        => 60,
			'active_callback' => 'paper_hue_customize_slider_details_active',
		)
	);

	$wp_customize->add_setting(
		'paper_hue_slider_interval',
		array(
			'default'           => 4000,
			'transport'         => 'refresh',
			'sanitize_callback' => 'paper_hue_sanitize_slider_interval',
		)
	);
	$wp_customize->add_control(
		'paper_hue_slider_interval',
		array(
			'label'           => esc_html__( 'Autoplay Delay', 'paper-hue' ),
			'description'     => esc_html__( 'Milliseconds between slides. Allowed range: 2000–12000.', 'paper-hue' ),
			'section'         => 'slider_options',
			'type'            => 'number',
			'priority'        => 70,
			'active_callback' => 'paper_hue_customize_slider_autoplay_active',
			'input_attrs'     => array(
				'min'  => 2000,
				'max'  => 12000,
				'step' => 500,
			),
		)
	);

	$wp_customize->add_setting(
		'paper_hue_slider_pause_on_interaction',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'paper_hue_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'paper_hue_slider_pause_on_interaction',
		array(
			'label'           => esc_html__( 'Pause on Hover or Focus', 'paper-hue' ),
			'section'         => 'slider_options',
			'type'            => 'checkbox',
			'priority'        => 80,
			'active_callback' => 'paper_hue_customize_slider_autoplay_active',
		)
	);

	$wp_customize->add_setting(
		'paper_hue_slider_show_excerpt',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'paper_hue_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'paper_hue_slider_show_excerpt',
		array(
			'label'           => esc_html__( 'Show Excerpt', 'paper-hue' ),
			'section'         => 'slider_options',
			'type'            => 'checkbox',
			'priority'        => 90,
			'active_callback' => 'paper_hue_customize_slider_details_active',
		)
	);

	$wp_customize->add_setting(
		'paper_hue_slider_show_arrows',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'paper_hue_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'paper_hue_slider_show_arrows',
		array(
			'label'           => esc_html__( 'Show Previous/Next Controls', 'paper-hue' ),
			'section'         => 'slider_options',
			'type'            => 'checkbox',
			'priority'        => 100,
			'active_callback' => 'paper_hue_customize_slider_details_active',
		)
	);

	$wp_customize->add_setting(
		'paper_hue_slider_show_dots',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'paper_hue_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'paper_hue_slider_show_dots',
		array(
			'label'           => esc_html__( 'Show Slide Dots', 'paper-hue' ),
			'section'         => 'slider_options',
			'type'            => 'checkbox',
			'priority'        => 110,
			'active_callback' => 'paper_hue_customize_slider_details_active',
		)
	);

	$wp_customize->add_setting(
		'paper_hue_slider_cta_label',
		array(
			'default'           => esc_html__( 'Read More', 'paper-hue' ),
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'paper_hue_slider_cta_label',
		array(
			'label'           => esc_html__( 'Button Label', 'paper-hue' ),
			'section'         => 'slider_options',
			'type'            => 'text',
			'priority'        => 120,
			'active_callback' => 'paper_hue_customize_slider_details_active',
		)
	);
}
add_action( 'customize_register', 'paper_hue_customize_slider_controls', 30 );

/**
 * Load the modern-browser compatibility layer for the legacy Paper Hue slider.
 *
 * @return void
 */
function paper_hue_enqueue_slider_compatibility_styles() {
	if ( ! is_front_page() || is_paged() || ! get_theme_mod( 'paper_hue_slider', true ) ) {
		return;
	}

	wp_enqueue_style(
		'paper-hue-slider-modern',
		get_template_directory_uri() . '/client-side/css/paper-hue-slider-modern.css',
		array( 'paper-hue-paper-style' ),
		paper_hue_asset_version( 'client-side/css/paper-hue-slider-modern.css' )
	);
}
add_action( 'wp_enqueue_scripts', 'paper_hue_enqueue_slider_compatibility_styles', 20 );
