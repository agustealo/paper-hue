<?php
/**
 * Functions that enhance Paper Hue by hooking into WordPress.
 *
 * @package Paper_Hue
 */

require get_template_directory() . '/inc/functions/posts-navigation.php';

/**
 * Add a pingback URL auto-discovery header when needed.
 *
 * @return void
 */
function paper_hue_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'paper_hue_pingback_header' );

/**
 * Set the default excerpt length for Paper Hue layouts.
 *
 * @param int $length WordPress default excerpt length.
 * @return int
 */
function paper_hue_excerpt_length( $length ) {
	unset( $length );
	return 30;
}
add_filter( 'excerpt_length', 'paper_hue_excerpt_length', 999 );
