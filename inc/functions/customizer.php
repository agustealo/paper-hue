<?php
/**
 * Paper Hue Theme Customizer.
 *
 * @package Paper_Hue
 */

/**
 * Sanitize a checkbox value.
 *
 * @param mixed $checked Raw value.
 * @return bool
 */
function paper_hue_sanitize_checkbox( $checked ) {
	return (bool) $checked;
}

/**
 * Sanitize slider count.
 *
 * @param mixed $value Raw value.
 * @return int
 */
function paper_hue_sanitize_slider_count( $value ) {
	$value = absint( $value );
	return in_array( $value, array( 2, 3, 4, 5 ), true ) ? $value : 3;
}

/**
 * Sanitize slider order.
 *
 * @param mixed $value Raw value.
 * @return string
 */
function paper_hue_sanitize_slider_order( $value ) {
	$value = strtoupper( sanitize_key( $value ) );
	return in_array( $value, array( 'ASC', 'DESC' ), true ) ? $value : 'ASC';
}

/**
 * Sanitize slider order-by value.
 *
 * @param mixed $value Raw value.
 * @return string
 */
function paper_hue_sanitize_slider_orderby( $value ) {
	$value   = sanitize_key( $value );
	$allowed = array( 'title', 'id', 'date', 'modified', 'author', 'rand', 'comment_count' );

	return in_array( strtolower( $value ), $allowed, true ) ? strtolower( $value ) : 'date';
}

/**
 * Register Paper Hue Customizer controls.
 *
 * Existing setting IDs are intentionally preserved so upgrades keep user choices.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function paper_hue_customize_register( $wp_customize ) {
	$wp_customize->add_panel(
		'paper_hue_theme_settings',
		array(
			'title'    => esc_html__( 'Theme Settings', 'paper-hue' ),
			'priority' => 20,
		)
	);

	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'paper_hue_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'paper_hue_customize_partial_blogdescription',
			)
		);
	}

	$wp_customize->add_section(
		'slider_options',
		array(
			'panel'    => 'paper_hue_theme_settings',
			'title'    => esc_html__( 'Slider Options', 'paper-hue' ),
			'priority' => 20,
		)
	);

	$wp_customize->add_setting(
		'paper_hue_slider',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'paper_hue_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'paper_hue_slider',
		array(
			'label'    => esc_html__( 'Show Slider', 'paper-hue' ),
			'section'  => 'slider_options',
			'settings' => 'paper_hue_slider',
			'type'     => 'checkbox',
		)
	);

	$categories = array();
	foreach ( get_categories( array( 'hide_empty' => false ) ) as $category ) {
		$categories[ $category->term_id ] = $category->name;
	}

	$wp_customize->add_setting(
		'slider_category',
		array(
			'default'           => 1,
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'slider_category',
		array(
			'label'    => esc_html__( 'Select Category', 'paper-hue' ),
			'section'  => 'slider_options',
			'settings' => 'slider_category',
			'type'     => 'select',
			'choices'  => $categories,
		)
	);

	$wp_customize->add_setting(
		's_total',
		array(
			'default'           => 3,
			'transport'         => 'refresh',
			'sanitize_callback' => 'paper_hue_sanitize_slider_count',
		)
	);
	$wp_customize->add_control(
		's_total',
		array(
			'label'    => esc_html__( 'Total Slides', 'paper-hue' ),
			'section'  => 'slider_options',
			'settings' => 's_total',
			'type'     => 'select',
			'choices'  => array(
				2 => esc_html__( 'Two', 'paper-hue' ),
				3 => esc_html__( 'Three', 'paper-hue' ),
				4 => esc_html__( 'Four', 'paper-hue' ),
				5 => esc_html__( 'Five', 'paper-hue' ),
			),
		)
	);

	$wp_customize->add_setting(
		's_order',
		array(
			'default'           => 'ASC',
			'transport'         => 'refresh',
			'sanitize_callback' => 'paper_hue_sanitize_slider_order',
		)
	);
	$wp_customize->add_control(
		's_order',
		array(
			'label'    => esc_html__( 'Display Order', 'paper-hue' ),
			'section'  => 'slider_options',
			'settings' => 's_order',
			'type'     => 'select',
			'choices'  => array(
				'ASC'  => esc_html__( 'Oldest First', 'paper-hue' ),
				'DESC' => esc_html__( 'Newest First', 'paper-hue' ),
			),
		)
	);

	$wp_customize->add_setting(
		's_order_by',
		array(
			'default'           => 'date',
			'transport'         => 'refresh',
			'sanitize_callback' => 'paper_hue_sanitize_slider_orderby',
		)
	);
	$wp_customize->add_control(
		's_order_by',
		array(
			'label'    => esc_html__( 'Order By', 'paper-hue' ),
			'section'  => 'slider_options',
			'settings' => 's_order_by',
			'type'     => 'select',
			'choices'  => array(
				'title'         => esc_html__( 'Title', 'paper-hue' ),
				'id'            => esc_html__( 'ID', 'paper-hue' ),
				'date'          => esc_html__( 'Date', 'paper-hue' ),
				'modified'      => esc_html__( 'Modified', 'paper-hue' ),
				'author'        => esc_html__( 'Author', 'paper-hue' ),
				'rand'          => esc_html__( 'Random Order', 'paper-hue' ),
				'comment_count' => esc_html__( 'Comment Count', 'paper-hue' ),
			),
		)
	);

	$wp_customize->add_section(
		'hue_banner',
		array(
			'panel'    => 'paper_hue_theme_settings',
			'title'    => esc_html__( 'Banner Settings', 'paper-hue' ),
			'priority' => 20,
		)
	);
	$wp_customize->add_setting(
		'hue_header_title',
		array(
			'default'           => false,
			'transport'         => 'refresh',
			'sanitize_callback' => 'paper_hue_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'hue_header_title',
		array(
			'label'    => esc_html__( 'Show Title In Header', 'paper-hue' ),
			'section'  => 'hue_banner',
			'settings' => 'hue_header_title',
			'type'     => 'checkbox',
		)
	);

	$wp_customize->add_section(
		'feat_post',
		array(
			'panel'    => 'paper_hue_theme_settings',
			'title'    => esc_html__( 'Featured Post', 'paper-hue' ),
			'priority' => 20,
		)
	);
	$wp_customize->add_setting(
		'show_feat_sticky',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'paper_hue_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'show_feat_sticky',
		array(
			'label'    => esc_html__( 'Feature Sticky Post', 'paper-hue' ),
			'section'  => 'feat_post',
			'settings' => 'show_feat_sticky',
			'type'     => 'checkbox',
		)
	);

	/* Legacy logo setting retained so existing sites do not lose their saved logo. */
	$wp_customize->add_setting(
		'hue_them_logo',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Cropped_Image_Control(
			$wp_customize,
			'hue_them_logo',
			array(
				'label'       => esc_html__( 'Legacy Paper Hue Logo', 'paper-hue' ),
				'description' => esc_html__( 'Kept for compatibility. New sites should use Site Identity > Logo.', 'paper-hue' ),
				'section'     => 'title_tagline',
				'settings'    => 'hue_them_logo',
				'flex_width'  => true,
				'flex_height' => true,
				'width'       => 350,
				'height'      => 80,
			)
		)
	);

	$wp_customize->add_section(
		'default_feat_image',
		array(
			'panel'    => 'paper_hue_theme_settings',
			'title'    => esc_html__( 'Default Featured Image', 'paper-hue' ),
			'priority' => 20,
		)
	);
	$wp_customize->add_setting(
		'theme_feat_image',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Cropped_Image_Control(
			$wp_customize,
			'theme_feat_image',
			array(
				'label'       => esc_html__( 'Default Featured Image', 'paper-hue' ),
				'description' => esc_html__( 'Fallback for posts without a featured image. Recommended size: 640×440 pixels.', 'paper-hue' ),
				'section'     => 'default_feat_image',
				'settings'    => 'theme_feat_image',
				'flex_width'  => true,
				'flex_height' => true,
				'width'       => 640,
				'height'      => 440,
			)
		)
	);

	$wp_customize->add_section(
		'hue_front_page',
		array(
			'panel'    => 'paper_hue_theme_settings',
			'title'    => esc_html__( 'Front Page Settings', 'paper-hue' ),
			'priority' => 20,
		)
	);
	$wp_customize->add_setting(
		'hue_post_nav',
		array(
			'default'           => false,
			'transport'         => 'refresh',
			'sanitize_callback' => 'paper_hue_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'hue_post_nav',
		array(
			'label'    => esc_html__( 'Show Page Navigation', 'paper-hue' ),
			'section'  => 'hue_front_page',
			'settings' => 'hue_post_nav',
			'type'     => 'checkbox',
		)
	);

	$wp_customize->add_section(
		'footer_settings',
		array(
			'panel' => 'paper_hue_theme_settings',
			'title' => esc_html__( 'Footer Settings', 'paper-hue' ),
		)
	);

	$wp_customize->add_setting(
		'hue_powered_by',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'paper_hue_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'hue_powered_by',
		array(
			'label'    => esc_html__( 'Show “Powered by WordPress”', 'paper-hue' ),
			'section'  => 'footer_settings',
			'settings' => 'hue_powered_by',
			'type'     => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'hue_credit',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'paper_hue_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'hue_credit',
		array(
			'label'       => esc_html__( 'Credit Theme Creator', 'paper-hue' ),
			'description' => esc_html__( 'Show or hide the Paper Hue creator credit.', 'paper-hue' ),
			'section'     => 'footer_settings',
			'settings'    => 'hue_credit',
			'type'        => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'show_copyright',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'paper_hue_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'show_copyright',
		array(
			'label'       => esc_html__( 'Show Footer Information', 'paper-hue' ),
			'description' => esc_html__( 'Display your custom footer information.', 'paper-hue' ),
			'section'     => 'footer_settings',
			'settings'    => 'show_copyright',
			'type'        => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'hue_copyright',
		array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'wp_kses_post',
		)
	);
	$wp_customize->add_control(
		'hue_copyright',
		array(
			'label'       => esc_html__( 'Footer Info', 'paper-hue' ),
			'description' => esc_html__( 'Add copyright or other footer information.', 'paper-hue' ),
			'section'     => 'footer_settings',
			'type'        => 'textarea',
			'input_attrs' => array(
				'class'       => 'footer-info',
				'placeholder' => esc_attr__( 'Add footer information here', 'paper-hue' ),
			),
		)
	);
}
add_action( 'customize_register', 'paper_hue_customize_register' );

/**
 * Render the site title for selective refresh.
 *
 * @return void
 */
function paper_hue_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for selective refresh.
 *
 * @return void
 */
function paper_hue_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Enqueue Customizer preview JavaScript.
 *
 * @return void
 */
function paper_hue_customize_preview_js() {
	wp_enqueue_script(
		'paper-hue-customizer',
		get_template_directory_uri() . '/client-side/js/customizer.js',
		array( 'customize-preview' ),
		paper_hue_asset_version( 'client-side/js/customizer.js' ),
		true
	);
}
add_action( 'customize_preview_init', 'paper_hue_customize_preview_js' );
