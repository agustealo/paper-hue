<?php
/**
 * Recent Articles Customizer controls.
 *
 * @package Paper_Hue
 */

/**
 * Sanitize Recent Articles source.
 *
 * @param string $value Raw value.
 * @return string
 */
function paper_hue_sanitize_recent_source( $value ) {
	$value = sanitize_key( $value );
	return in_array( $value, array( 'latest', 'category' ), true ) ? $value : 'latest';
}

/**
 * Sanitize Recent Articles layout.
 *
 * @param string $value Raw value.
 * @return string
 */
function paper_hue_sanitize_recent_layout( $value ) {
	$value = sanitize_key( $value );
	return in_array( $value, array( 'classic', 'compact', 'list' ), true ) ? $value : 'classic';
}

/**
 * Sanitize posts-per-page control.
 *
 * @param mixed $value Raw value.
 * @return int
 */
function paper_hue_sanitize_recent_per_page( $value ) {
	return min( 24, max( 3, absint( $value ) ) );
}

/**
 * Register Recent Articles controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 * @return void
 */
function paper_hue_customize_recent_articles( $wp_customize ) {
	$wp_customize->add_setting(
		'paper_hue_recent_heading',
		array(
			'default'           => esc_html__( 'Recent Articles', 'paper-hue' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'paper_hue_recent_heading',
		array(
			'label'   => esc_html__( 'Section Heading', 'paper-hue' ),
			'section' => 'hue_front_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'paper_hue_recent_source',
		array(
			'default'           => 'latest',
			'sanitize_callback' => 'paper_hue_sanitize_recent_source',
		)
	);
	$wp_customize->add_control(
		'paper_hue_recent_source',
		array(
			'label'   => esc_html__( 'Article Source', 'paper-hue' ),
			'section' => 'hue_front_page',
			'type'    => 'select',
			'choices' => array(
				'latest'   => esc_html__( 'Latest Posts', 'paper-hue' ),
				'category' => esc_html__( 'Selected Category', 'paper-hue' ),
			),
		)
	);

	$categories = array( 0 => esc_html__( 'Select a category', 'paper-hue' ) );
	foreach ( get_categories( array( 'hide_empty' => false ) ) as $category ) {
		$categories[ $category->term_id ] = $category->name;
	}
	$wp_customize->add_setting(
		'paper_hue_recent_category',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'paper_hue_recent_category',
		array(
			'label'           => esc_html__( 'Category', 'paper-hue' ),
			'section'         => 'hue_front_page',
			'type'            => 'select',
			'choices'         => $categories,
			'active_callback' => function() {
				return 'category' === get_theme_mod( 'paper_hue_recent_source', 'latest' );
			},
		)
	);

	$wp_customize->add_setting(
		'paper_hue_recent_per_page',
		array(
			'default'           => (int) get_option( 'posts_per_page', 10 ),
			'sanitize_callback' => 'paper_hue_sanitize_recent_per_page',
		)
	);
	$wp_customize->add_control(
		'paper_hue_recent_per_page',
		array(
			'label'       => esc_html__( 'Articles Per Page', 'paper-hue' ),
			'description' => esc_html__( 'Choose between 3 and 24 articles per page.', 'paper-hue' ),
			'section'     => 'hue_front_page',
			'type'        => 'number',
			'input_attrs' => array( 'min' => 3, 'max' => 24, 'step' => 1 ),
		)
	);

	$wp_customize->add_setting(
		'paper_hue_recent_layout',
		array(
			'default'           => 'classic',
			'sanitize_callback' => 'paper_hue_sanitize_recent_layout',
		)
	);
	$wp_customize->add_control(
		'paper_hue_recent_layout',
		array(
			'label'       => esc_html__( 'Layout', 'paper-hue' ),
			'description' => esc_html__( 'Classic preserves the original Paper Hue card grid.', 'paper-hue' ),
			'section'     => 'hue_front_page',
			'type'        => 'select',
			'choices'     => array(
				'classic' => esc_html__( 'Classic Paper Hue', 'paper-hue' ),
				'compact' => esc_html__( 'Compact Cards', 'paper-hue' ),
				'list'    => esc_html__( 'Article List', 'paper-hue' ),
			),
		)
	);

	$checkboxes = array(
		'paper_hue_recent_show_image'   => array( esc_html__( 'Show Featured Images', 'paper-hue' ), true ),
		'paper_hue_recent_show_excerpt' => array( esc_html__( 'Show Excerpts', 'paper-hue' ), true ),
		'paper_hue_recent_show_meta'    => array( esc_html__( 'Show Author and Date', 'paper-hue' ), false ),
	);
	foreach ( $checkboxes as $setting_id => $definition ) {
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => $definition[1],
				'sanitize_callback' => 'paper_hue_sanitize_checkbox',
			)
		);
		$wp_customize->add_control(
			$setting_id,
			array(
				'label'   => $definition[0],
				'section' => 'hue_front_page',
				'type'    => 'checkbox',
			)
		);
	}

	$wp_customize->add_setting(
		'paper_hue_recent_cta',
		array(
			'default'           => esc_html__( 'Read More', 'paper-hue' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'paper_hue_recent_cta',
		array(
			'label'   => esc_html__( 'Button Label', 'paper-hue' ),
			'section' => 'hue_front_page',
			'type'    => 'text',
		)
	);
}
add_action( 'customize_register', 'paper_hue_customize_recent_articles', 40 );
