<?php
/**
 * Breadcrumb helpers.
 *
 * @package Paper_Hue
 */

/**
 * Render simple Paper Hue breadcrumbs.
 *
 * @return void
 */
function paper_hue_breadcrumb() {
	$separator = '<span class="breadcrumb-separator" aria-hidden="true"> &raquo; </span>';

	echo '<nav class="paper-hue-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumbs', 'paper-hue' ) . '">';

	if ( is_home() ) {
		esc_html_e( 'Home', 'paper-hue' );
		echo '</nav>';
		return;
	}

	printf(
		'<a href="%1$s">%2$s</a>',
		esc_url( home_url( '/' ) ),
		esc_html__( 'Home', 'paper-hue' )
	);

	if ( is_category() || is_single() ) {
		echo wp_kses_post( $separator );
		the_category( ' &raquo; ' );

		if ( is_single() ) {
			echo wp_kses_post( $separator );
			echo esc_html( get_the_title() );
		}
	} elseif ( is_page() ) {
		echo wp_kses_post( $separator );
		echo esc_html( get_the_title() );
	} elseif ( is_search() ) {
		echo wp_kses_post( $separator );
		printf(
			/* translators: %s: Search query. */
			esc_html__( 'Search results for “%s”', 'paper-hue' ),
			esc_html( get_search_query() )
		);
	} elseif ( is_archive() ) {
		echo wp_kses_post( $separator );
		echo wp_kses_post( get_the_archive_title() );
	} elseif ( is_404() ) {
		echo wp_kses_post( $separator );
		esc_html_e( 'Page not found', 'paper-hue' );
	}

	echo '</nav>';
}

/**
 * Backward-compatible alias retained for child themes and existing templates.
 *
 * @return void
 */
function get_breadcrumb() {
	paper_hue_breadcrumb();
}
