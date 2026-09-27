<?php
/**
 * Paper Hue functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Paper_Hue
 */

if ( ! defined( 'PAPER_HUE_VERSION' ) ) {
	define( 'PAPER_HUE_VERSION', '1.1.0' );
}

if ( ! function_exists( 'paper_hue_setup' ) ) :
	/**
	 * Set up theme defaults and register support for WordPress features.
	 *
	 * @return void
	 */
	function paper_hue_setup() {
		load_theme_textdomain( 'paper-hue', get_template_directory() . '/languages' );

		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'responsive-embeds' );

		add_theme_support(
			'custom-logo',
			array(
				'height'      => 80,
				'width'       => 350,
				'flex-height' => true,
				'flex-width'  => true,
			)
		);

		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'script',
				'style',
			)
		);

		register_nav_menus(
			array(
				'main-menu' => esc_html__( 'Primary', 'paper-hue' ),
			)
		);

		set_post_thumbnail_size( 800, 999999, false );
		add_image_size( 'featured-post-image', 800, 999999, false );
		add_image_size( 'thumbnail-large', 640, 440, true );
		add_image_size( 'thumbnail-small', 320, 200, true );
		add_image_size( 'medium-width', 640, 9999, false );
		add_image_size( 'medium-height', 9999, 640, false );
		add_image_size( 'medium-crop', 640, 640, true );
	}
endif;
add_action( 'after_setup_theme', 'paper_hue_setup' );

/**
 * Set the content width in pixels.
 *
 * @return void
 */
function paper_hue_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'paper_hue_content_width', 640 );
}
add_action( 'after_setup_theme', 'paper_hue_content_width', 0 );

/**
 * Remove WordPress archive-title prefixes while preserving translated labels.
 *
 * @param string $title Archive title.
 * @return string
 */
function paper_hue_archive_title( $title ) {
	if ( is_category() ) {
		return single_cat_title( '', false );
	}

	if ( is_tag() ) {
		return single_tag_title( '', false );
	}

	if ( is_author() ) {
		return '<span class="vcard">' . esc_html( get_the_author() ) . '</span>';
	}

	if ( is_year() ) {
		return get_the_date( _x( 'Y', 'yearly archives date format', 'paper-hue' ) );
	}

	if ( is_month() ) {
		return get_the_date( _x( 'F Y', 'monthly archives date format', 'paper-hue' ) );
	}

	if ( is_day() ) {
		return get_the_date( _x( 'F j, Y', 'daily archives date format', 'paper-hue' ) );
	}

	if ( is_tax( 'post_format' ) ) {
		$format_titles = array(
			'post-format-aside'   => _x( 'Asides', 'post format archive title', 'paper-hue' ),
			'post-format-gallery' => _x( 'Galleries', 'post format archive title', 'paper-hue' ),
			'post-format-image'   => _x( 'Images', 'post format archive title', 'paper-hue' ),
			'post-format-video'   => _x( 'Videos', 'post format archive title', 'paper-hue' ),
			'post-format-quote'   => _x( 'Quotes', 'post format archive title', 'paper-hue' ),
			'post-format-link'    => _x( 'Links', 'post format archive title', 'paper-hue' ),
			'post-format-status'  => _x( 'Statuses', 'post format archive title', 'paper-hue' ),
			'post-format-audio'   => _x( 'Audio', 'post format archive title', 'paper-hue' ),
			'post-format-chat'    => _x( 'Chats', 'post format archive title', 'paper-hue' ),
		);

		foreach ( $format_titles as $format => $format_title ) {
			if ( is_tax( 'post_format', $format ) ) {
				return $format_title;
			}
		}
	}

	if ( is_post_type_archive() ) {
		return post_type_archive_title( '', false );
	}

	if ( is_tax() ) {
		return single_term_title( '', false );
	}

	return $title ?: esc_html__( 'Archives', 'paper-hue' );
}
add_filter( 'get_the_archive_title', 'paper_hue_archive_title' );

/**
 * Register widget areas.
 *
 * @return void
 */
function paper_hue_widgets_init() {
	$widgets = array(
		'posts-widget'    => array( 'Posts Widget', 'Aside widget for posts only' ),
		'widget-bottom-1' => array( 'Widget Bottom 1', 'First row below main content, on all pages' ),
		'widget-bottom-2' => array( 'Widget Bottom 2', 'The second row below main content, on all pages.' ),
		'widget-bottom-3' => array( 'Widget Bottom 3', 'The third row below main content, on all pages.' ),
		'widget-bottom-4' => array( 'Widget Bottom 4', 'The fourth row below main content, on all pages.' ),
	);

	foreach ( $widgets as $id => $widget ) {
		register_sidebar(
			array(
				'name'          => esc_html__( $widget[0], 'paper-hue' ),
				'id'            => $id,
				'description'   => esc_html__( $widget[1], 'paper-hue' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			)
		);
	}
}
add_action( 'widgets_init', 'paper_hue_widgets_init' );

/**
 * Return a cache-busting version for a theme asset.
 *
 * @param string $relative_path Relative path from the theme directory.
 * @return string
 */
function paper_hue_asset_version( $relative_path ) {
	$file = get_template_directory() . '/' . ltrim( $relative_path, '/' );

	if ( file_exists( $file ) ) {
		return (string) filemtime( $file );
	}

	return PAPER_HUE_VERSION;
}

/**
 * Enqueue scripts and styles.
 *
 * @return void
 */
function paper_hue_scripts() {
	wp_enqueue_style(
		'paper-hue-style',
		get_stylesheet_uri(),
		array(),
		PAPER_HUE_VERSION
	);

	wp_enqueue_style(
		'paper-hue-paper-style',
		get_template_directory_uri() . '/client-side/css/hue-paper-style.css',
		array( 'paper-hue-style' ),
		paper_hue_asset_version( 'client-side/css/hue-paper-style.css' )
	);

	wp_enqueue_script(
		'paper-hue-navigation-bar',
		get_template_directory_uri() . '/client-side/js/hue-navigation-bar.js',
		array(),
		paper_hue_asset_version( 'client-side/js/hue-navigation-bar.js' ),
		true
	);

	if ( is_front_page() && ! is_paged() && get_theme_mod( 'paper_hue_slider', true ) ) {
		wp_enqueue_script(
			'paper-hue-slider',
			get_template_directory_uri() . '/client-side/js/hue-slider.js',
			array(),
			paper_hue_asset_version( 'client-side/js/hue-slider.js' ),
			true
		);
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'paper_hue_scripts' );

require get_template_directory() . '/inc/functions/featured-image.php';
require get_template_directory() . '/inc/functions/template-tags.php';
require get_template_directory() . '/inc/functions/template-functions.php';
require get_template_directory() . '/inc/functions/customizer.php';
require get_template_directory() . '/inc/functions/customizer-experience.php';
require get_template_directory() . '/inc/functions/customizer-slider.php';
require get_template_directory() . '/inc/functions/customizer-featured-story.php';
require get_template_directory() . '/inc/classes/class-paper-hue-config.php';
require get_template_directory() . '/inc/classes/class-paper-hue-admin.php';
require get_template_directory() . '/inc/classes/class-paper-hue-slider.php';
require get_template_directory() . '/inc/classes/class-paper-hue-featured-story.php';

if ( is_admin() ) {
	$paper_hue_admin = new Paper_Hue_Admin( Paper_Hue_Config::instance() );
	$paper_hue_admin->register();
}

if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/functions/jetpack.php';
}

if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/functions/woocommerce.php';
}

require get_template_directory() . '/inc/functions/breadcrumbs-functions.php';
