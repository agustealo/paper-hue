<?php
/**
 * Recent Articles configuration and main-query ownership.
 *
 * @package Paper_Hue
 */

/**
 * First-class Recent Articles service.
 */
final class Paper_Hue_Recent_Articles {
	/** @return string */
	public static function source() {
		$source = sanitize_key( get_theme_mod( 'paper_hue_recent_source', 'latest' ) );
		return in_array( $source, array( 'latest', 'category' ), true ) ? $source : 'latest';
	}

	/** @return int */
	public static function category_id() {
		return absint( get_theme_mod( 'paper_hue_recent_category', 0 ) );
	}

	/** @return int */
	public static function per_page() {
		$value = absint( get_theme_mod( 'paper_hue_recent_per_page', (int) get_option( 'posts_per_page', 10 ) ) );
		return min( 24, max( 3, $value ) );
	}

	/** @return string */
	public static function layout() {
		$layout = sanitize_key( get_theme_mod( 'paper_hue_recent_layout', 'classic' ) );
		return in_array( $layout, array( 'classic', 'compact', 'list' ), true ) ? $layout : 'classic';
	}

	/** @return bool */
	public static function show_image() {
		return (bool) get_theme_mod( 'paper_hue_recent_show_image', true );
	}

	/** @return bool */
	public static function show_excerpt() {
		return (bool) get_theme_mod( 'paper_hue_recent_show_excerpt', true );
	}

	/** @return bool */
	public static function show_meta() {
		return (bool) get_theme_mod( 'paper_hue_recent_show_meta', false );
	}

	/** @return string */
	public static function heading() {
		$heading = sanitize_text_field( get_theme_mod( 'paper_hue_recent_heading', __( 'Recent Articles', 'paper-hue' ) ) );
		return '' !== $heading ? $heading : __( 'Recent Articles', 'paper-hue' );
	}

	/** @return string */
	public static function cta_label() {
		$label = sanitize_text_field( get_theme_mod( 'paper_hue_recent_cta', __( 'Read More', 'paper-hue' ) ) );
		return '' !== $label ? $label : __( 'Read More', 'paper-hue' );
	}

	/**
	 * Apply Recent Articles settings to the canonical posts-style front-page query.
	 *
	 * @param WP_Query $query Query instance.
	 * @return void
	 */
	public static function filter_main_query( $query ) {
		if ( is_admin() || ! $query->is_main_query() || ! $query->is_front_page() || ! $query->is_home() ) {
			return;
		}

		$query->set( 'posts_per_page', self::per_page() );

		if ( 'category' === self::source() && self::category_id() ) {
			$query->set( 'cat', self::category_id() );
		}

		if ( class_exists( 'Paper_Hue_Featured_Story' ) && Paper_Hue_Featured_Story::exclude_from_recent() ) {
			$featured_id = Paper_Hue_Featured_Story::post_id();
			if ( $featured_id ) {
				$query->set( 'post__not_in', array( $featured_id ) );
			}
		}
	}
}
add_action( 'pre_get_posts', array( 'Paper_Hue_Recent_Articles', 'filter_main_query' ) );
