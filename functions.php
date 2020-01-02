<?php
/**
 * Paper Hue functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Paper_Hue
 */

if ( ! function_exists( 'paper_hue_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function paper_hue_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Paper Hue, use a find and replace
		 * to change 'paper-hue' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'paper-hue', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 600, 600 ); // default Featured Image dimensions (cropped)
		add_image_size( 'header-image', 9999, 600, true );
		add_image_size( 'full-size', 9999, 9999 );
		add_image_size( 600, 250, true );


		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'paper-hue' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'paper_hue_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'paper_hue_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function paper_hue_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'paper_hue_content_width', 640 );
}
add_action( 'after_setup_theme', 'paper_hue_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function paper_hue_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'paper-hue' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'paper-hue' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'paper_hue_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function paper_hue_scripts() {
	wp_enqueue_style( 'paper-hue-style', get_stylesheet_uri() );

	wp_enqueue_script( 'paper-hue-code-highlighter', get_template_directory_uri() . '/client-side/js/hue-code-highlighter.js', array(), '20151215', true );

	wp_enqueue_script( 'paper-hue-navigation-bar', get_template_directory_uri() . '/client-side/js/hue-navigation-bar.js', array(), '20151215', true );

	//wp_enqueue_script( 'paper-hue-theme', get_template_directory_uri() . '/client-side/js/hue-theme.js', array(), '1', true );

	//wp_enqueue_script( 'paper-hue-navigation', get_template_directory_uri() . '/client-side/js/navigation.js', array(), '20151215', true );

	//wp_enqueue_script( 'paper-hue-skip-link-focus-fix', get_template_directory_uri() . '/client-side/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_home() && is_front_page() ) {
		wp_enqueue_script( 'paper-hue-slider', get_template_directory_uri() . '/client-side/js/hue-slider.js', array(), '20151215', true );
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'paper_hue_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/functions/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/functions/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/functions/template-functions.php';

/**
 * Image fallback functions.
 */
require get_template_directory() . '/inc/functions/default-image-fallback.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/functions/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/functions/jetpack.php';
}

/**
 * Load WooCommerce compatibility file.
 */

if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/functions/woocommerce.php';
}


/**
 * Load Breadcrumb function.
 */
	require get_template_directory() . '/inc/functions/breadcrumbs-functions.php';
