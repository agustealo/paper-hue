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
		/**
		 * Implement feature image support.
		 */
		require get_template_directory() . '/inc/functions/featured-image.php';


		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'main-menu' => esc_html__( 'Primary', 'paper-hue' ),
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
		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

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
* Modify Category title by removing prefix "Category"
*/
// Return an alternate title, without prefix, for every type used in the get_the_archive_title().
add_filter('get_the_archive_title', function ($title) {
    if ( is_category() ) {
        $title = single_cat_title( '', false );
    } elseif ( is_tag() ) {
        $title = single_tag_title( '', false );
    } elseif ( is_author() ) {
        $title = '<span class="vcard">' . get_the_author() . '</span>';
    } elseif ( is_year() ) {
        $title = get_the_date( _x( 'Y', 'yearly archives date format' ) );
    } elseif ( is_month() ) {
        $title = get_the_date( _x( 'F Y', 'monthly archives date format' ) );
    } elseif ( is_day() ) {
        $title = get_the_date( _x( 'F j, Y', 'daily archives date format' ) );
    } elseif ( is_tax( 'post_format' ) ) {
        if ( is_tax( 'post_format', 'post-format-aside' ) ) {
            $title = _x( 'Asides', 'post format archive title' );
        } elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
            $title = _x( 'Galleries', 'post format archive title' );
        } elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
            $title = _x( 'Images', 'post format archive title' );
        } elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
            $title = _x( 'Videos', 'post format archive title' );
        } elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
            $title = _x( 'Quotes', 'post format archive title' );
        } elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
            $title = _x( 'Links', 'post format archive title' );
        } elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
            $title = _x( 'Statuses', 'post format archive title' );
        } elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
            $title = _x( 'Audio', 'post format archive title' );
        } elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
            $title = _x( 'Chats', 'post format archive title' );
        }
    } elseif ( is_post_type_archive() ) {
        $title = post_type_archive_title( '', false );
    } elseif ( is_tax() ) {
        $title = single_term_title( '', false );
    } else {
        $title = __( 'Archives' );
    }
    return $title;
});
/**
 * Register widget areas.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */

function paper_hue_widgets_init() {

	$widgets = [
		// To add a new widget...
		// Name of widgets and description ie. [widget_name, widget_description]
		[ 'Posts Widget',    'Aside widget for posts only' ],
		[ 'Widget Bottom 1', 'First row below main content, on all pages' ],
		[ 'Widget Bottom 2', 'The second row below main content, on all pages.' ],
		[ 'Widget Bottom 3', 'The third row below main content, on all pages.' ],
		[ 'Widget Bottom 4', 'The fourth row below main content, on all pages.' ]
	];

	$before_widget = '<section id="%1$s" class="widget %2$s">';
	$after_widget  = '</section>';
	$before_title  = '<h2 class="widget-title">';
	$after_title   = '</h2>';

	foreach ($widgets as list($name, $description)) {
		$widget = array(
			'name'          => esc_html__( $name, 'paper-hue' ),
			'id'            => strtolower(str_replace(' ', '-', $name)), // Format widget name to id string ie. 'Widget Bottom 1' to 'widget-bottom-1'
			'description'   => esc_html__( $description, 'paper-hue' ),
			'before_widget' => $before_widget,
			'after_widget'  => $after_widget,
			'before_title'  => $before_title,
			'after_title'   => $after_title,
		);
		register_sidebar($widget);
	}
}
add_action( 'widgets_init', 'paper_hue_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function paper_hue_scripts() {
	wp_enqueue_style( 'paper-hue-style', get_stylesheet_uri() );

	//wp_enqueue_script( 'paper-hue-code-highlighter', get_template_directory_uri() . '/client-side/js/hue-code-highlighter.js', array(), '20151215', true );

	wp_enqueue_script( 'paper-hue-navigation-bar', get_template_directory_uri() . '/client-side/js/hue-navigation-bar.js', array(), '20151215', true );

	if ( is_front_page() && !is_paged() ) {
		wp_enqueue_script( 'paper-hue-slider', get_template_directory_uri() . '/client-side/js/hue-slider.js', array(), '20151215', true );
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'paper_hue_scripts' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/functions/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/functions/template-functions.php';

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
