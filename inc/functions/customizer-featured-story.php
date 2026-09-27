<?php
/**
 * Featured Story Customizer controls.
 *
 * @package Paper_Hue
 */

/**
 * Sanitize Featured Story source.
 *
 * @param string $value Raw source.
 * @return string
 */
function paper_hue_sanitize_featured_story_source( $value ) {
	$value = sanitize_key( $value );
	return in_array( $value, array( 'sticky', 'manual', 'latest' ), true ) ? $value : 'sticky';
}

/**
 * Add first-class Featured Story controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 * @return void
 */
function paper_hue_customize_featured_story( $wp_customize ) {
	$wp_customize->add_setting(
		'paper_hue_featured_story_source',
		array(
			'default'           => 'sticky',
			'sanitize_callback' => 'paper_hue_sanitize_featured_story_source',
		)
	);
	$wp_customize->add_control(
		'paper_hue_featured_story_source',
		array(
			'label'           => esc_html__( 'Story Source', 'paper-hue' ),
			'description'     => esc_html__( 'Use WordPress sticky posts, pick one post manually, or feature the latest post.', 'paper-hue' ),
			'section'         => 'feat_post',
			'type'            => 'select',
			'choices'         => array(
				'sticky' => esc_html__( 'Latest Sticky Post', 'paper-hue' ),
				'manual' => esc_html__( 'Choose a Post', 'paper-hue' ),
				'latest' => esc_html__( 'Latest Post', 'paper-hue' ),
			),
			'active_callback' => function() {
				return (bool) get_theme_mod( 'show_feat_sticky', true );
			},
		)
	);

	$posts = get_posts(
		array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => 50,
			'orderby'        => 'date',
			'order'          => 'DESC',
		)
	);
	$choices = array( 0 => esc_html__( 'Select a post', 'paper-hue' ) );
	foreach ( $posts as $post ) {
		$choices[ $post->ID ] = get_the_title( $post );
	}

	$wp_customize->add_setting(
		'paper_hue_featured_story_post_id',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'paper_hue_featured_story_post_id',
		array(
			'label'           => esc_html__( 'Featured Post', 'paper-hue' ),
			'section'         => 'feat_post',
			'type'            => 'select',
			'choices'         => $choices,
			'active_callback' => function() {
				return (bool) get_theme_mod( 'show_feat_sticky', true ) && 'manual' === get_theme_mod( 'paper_hue_featured_story_source', 'sticky' );
			},
		)
	);

	$checkboxes = array(
		'paper_hue_featured_story_excerpt'        => array( esc_html__( 'Show Excerpt', 'paper-hue' ), true ),
		'paper_hue_featured_story_meta'           => array( esc_html__( 'Show Author and Date', 'paper-hue' ), false ),
		'paper_hue_featured_story_exclude_recent' => array( esc_html__( 'Exclude Featured Story from Recent Articles', 'paper-hue' ), true ),
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
				'label'           => $definition[0],
				'section'         => 'feat_post',
				'type'            => 'checkbox',
				'active_callback' => function() {
					return (bool) get_theme_mod( 'show_feat_sticky', true );
				},
			)
		);
	}

	$wp_customize->add_setting(
		'paper_hue_featured_story_cta',
		array(
			'default'           => esc_html__( 'Read More', 'paper-hue' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'paper_hue_featured_story_cta',
		array(
			'label'           => esc_html__( 'Button Label', 'paper-hue' ),
			'section'         => 'feat_post',
			'type'            => 'text',
			'active_callback' => function() {
				return (bool) get_theme_mod( 'show_feat_sticky', true );
			},
		)
	);
}
add_action( 'customize_register', 'paper_hue_customize_featured_story', 35 );
