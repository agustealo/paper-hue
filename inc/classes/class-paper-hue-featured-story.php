<?php
/**
 * Featured Story configuration and query ownership.
 *
 * @package Paper_Hue
 */

/**
 * First-class Featured Story service.
 */
final class Paper_Hue_Featured_Story {
	/**
	 * Whether the feature is enabled.
	 *
	 * @return bool
	 */
	public static function is_enabled() {
		return (bool) get_theme_mod( 'show_feat_sticky', true );
	}

	/**
	 * Return the selected source mode.
	 *
	 * @return string
	 */
	public static function source() {
		$source = sanitize_key( get_theme_mod( 'paper_hue_featured_story_source', 'sticky' ) );
		return in_array( $source, array( 'sticky', 'manual', 'latest' ), true ) ? $source : 'sticky';
	}

	/**
	 * Resolve the featured post ID.
	 *
	 * @return int
	 */
	public static function post_id() {
		if ( ! self::is_enabled() || is_paged() ) {
			return 0;
		}

		$source = self::source();

		if ( 'manual' === $source ) {
			$post_id = absint( get_theme_mod( 'paper_hue_featured_story_post_id', 0 ) );
			return 'publish' === get_post_status( $post_id ) ? $post_id : 0;
		}

		$args = array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => 1,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'fields'              => 'ids',
		);

		if ( 'sticky' === $source ) {
			$sticky_ids = array_values( array_filter( array_map( 'absint', (array) get_option( 'sticky_posts', array() ) ) ) );
			if ( ! $sticky_ids ) {
				return 0;
			}
			$args['post__in'] = $sticky_ids;
			$args['orderby']  = 'date';
			$args['order']    = 'DESC';
		}

		$query = new WP_Query( $args );
		return $query->posts ? absint( $query->posts[0] ) : 0;
	}

	/**
	 * Whether to show the excerpt.
	 *
	 * @return bool
	 */
	public static function show_excerpt() {
		return (bool) get_theme_mod( 'paper_hue_featured_story_excerpt', true );
	}

	/**
	 * Whether to show metadata.
	 *
	 * @return bool
	 */
	public static function show_meta() {
		return (bool) get_theme_mod( 'paper_hue_featured_story_meta', false );
	}

	/**
	 * Whether the selected story should be excluded from Recent Articles.
	 *
	 * @return bool
	 */
	public static function exclude_from_recent() {
		return (bool) get_theme_mod( 'paper_hue_featured_story_exclude_recent', true );
	}

	/**
	 * CTA label.
	 *
	 * @return string
	 */
	public static function cta_label() {
		$label = sanitize_text_field( get_theme_mod( 'paper_hue_featured_story_cta', __( 'Read More', 'paper-hue' ) ) );
		return $label ?: __( 'Read More', 'paper-hue' );
	}
}
