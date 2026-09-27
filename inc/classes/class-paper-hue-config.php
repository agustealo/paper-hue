<?php
/**
 * Centralized Paper Hue configuration access.
 *
 * @package Paper_Hue
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Paper_Hue_Config {
	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {}

	public function slider_enabled() {
		return (bool) get_theme_mod( 'paper_hue_slider', true );
	}

	public function slider_category_id() {
		return absint( get_theme_mod( 'slider_category', 1 ) );
	}

	public function slider_category() {
		$term = get_term( $this->slider_category_id(), 'category' );
		return $term instanceof WP_Term ? $term : null;
	}

	public function slider_total() {
		return paper_hue_sanitize_slider_count( get_theme_mod( 's_total', 3 ) );
	}

	public function featured_story_enabled() {
		return class_exists( 'Paper_Hue_Featured_Story' ) ? Paper_Hue_Featured_Story::is_enabled() : (bool) get_theme_mod( 'show_feat_sticky', true );
	}

	public function featured_story() {
		if ( class_exists( 'Paper_Hue_Featured_Story' ) ) {
			$post = get_post( Paper_Hue_Featured_Story::post_id() );
			return $post instanceof WP_Post ? $post : null;
		}

		$sticky_ids = array_values( array_filter( array_map( 'absint', (array) get_option( 'sticky_posts', array() ) ) ) );
		if ( empty( $sticky_ids ) ) {
			return null;
		}

		$query = new WP_Query(
			array(
				'post_type'           => 'post',
				'post_status'         => 'publish',
				'post__in'            => $sticky_ids,
				'posts_per_page'      => 1,
				'orderby'             => 'date',
				'order'               => 'DESC',
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
			)
		);
		return ! empty( $query->posts[0] ) && $query->posts[0] instanceof WP_Post ? $query->posts[0] : null;
	}

	public function homepage_pagination_enabled() { return (bool) get_theme_mod( 'hue_post_nav', false ); }
	public function header_title_enabled() { return (bool) get_theme_mod( 'hue_header_title', false ); }
	public function fallback_image_id() { return absint( get_theme_mod( 'theme_feat_image', 0 ) ); }
	public function has_custom_fallback_image() { return $this->fallback_image_id() > 0 && (bool) wp_get_attachment_image_url( $this->fallback_image_id(), 'full' ); }

	public function logo_id() {
		$native_logo = absint( get_theme_mod( 'custom_logo', 0 ) );
		return $native_logo ? $native_logo : absint( get_theme_mod( 'hue_them_logo', 0 ) );
	}

	public function has_primary_menu() {
		$locations = get_nav_menu_locations();
		return ! empty( $locations['main-menu'] );
	}

	public function active_widget_areas() {
		$ids = array( 'posts-widget', 'widget-bottom-1', 'widget-bottom-2', 'widget-bottom-3', 'widget-bottom-4' );
		return array_values( array_filter( $ids, static function ( $id ) { return is_active_sidebar( $id ); } ) );
	}

	public function slider_source_count() {
		$category = $this->slider_category();
		return $category ? absint( $category->count ) : 0;
	}

	public function dashboard_status() {
		$slider_category = $this->slider_category();
		$featured_story  = $this->featured_story();
		$widget_areas    = $this->active_widget_areas();
		$featured_source = class_exists( 'Paper_Hue_Featured_Story' ) ? Paper_Hue_Featured_Story::source() : 'sticky';
		$source_labels   = array(
			'sticky' => __( 'Sticky post', 'paper-hue' ),
			'manual' => __( 'Selected post', 'paper-hue' ),
			'latest' => __( 'Latest post', 'paper-hue' ),
		);

		$slider_summary = __( 'Disabled', 'paper-hue' );
		if ( $this->slider_enabled() ) {
			/* translators: 1: slider source label, 2: number of slides. */
			$slider_summary = sprintf( __( '%1$s · %2$d slides', 'paper-hue' ), $slider_category ? $slider_category->name : __( 'Configured source', 'paper-hue' ), $this->slider_total() );
		}

		$featured_summary = __( 'Disabled', 'paper-hue' );
		if ( $this->featured_story_enabled() ) {
			if ( $featured_story ) {
				/* translators: 1: featured story source label, 2: post title. */
				$featured_summary = sprintf( __( '%1$s · %2$s', 'paper-hue' ), $source_labels[ $featured_source ], get_the_title( $featured_story ) );
			} else {
				/* translators: %s: featured story source label. */
				$featured_summary = sprintf( __( '%s unavailable', 'paper-hue' ), $source_labels[ $featured_source ] );
			}
		}

		/* translators: %d: number of active widget areas. */
		$widget_summary = sprintf( _n( '%d active area', '%d active areas', count( $widget_areas ), 'paper-hue' ), count( $widget_areas ) );

		return array(
			'identity' => array( 'label' => __( 'Site Identity', 'paper-hue' ), 'healthy' => $this->logo_id() > 0 || get_bloginfo( 'name' ), 'summary' => $this->logo_id() > 0 ? __( 'Logo configured', 'paper-hue' ) : __( 'Using site title', 'paper-hue' ), 'customizer' => 'title_tagline' ),
			'navigation' => array( 'label' => __( 'Primary Navigation', 'paper-hue' ), 'healthy' => $this->has_primary_menu(), 'summary' => $this->has_primary_menu() ? __( 'Menu assigned', 'paper-hue' ) : __( 'No menu assigned', 'paper-hue' ), 'customizer' => 'nav_menus' ),
			'slider' => array( 'label' => __( 'Hero Slider', 'paper-hue' ), 'healthy' => ! $this->slider_enabled() || ( $slider_category && $this->slider_source_count() > 0 ), 'summary' => $slider_summary, 'customizer' => 'slider_options' ),
			'featured_story' => array( 'label' => __( 'Featured Story', 'paper-hue' ), 'healthy' => ! $this->featured_story_enabled() || ( $featured_story instanceof WP_Post ), 'summary' => $featured_summary, 'customizer' => 'feat_post' ),
			'recent_articles' => array( 'label' => __( 'Recent Articles', 'paper-hue' ), 'healthy' => true, 'summary' => $this->homepage_pagination_enabled() ? __( 'Pagination enabled', 'paper-hue' ) : __( 'Classic article grid', 'paper-hue' ), 'customizer' => 'hue_front_page' ),
			'images' => array( 'label' => __( 'Fallback Image', 'paper-hue' ), 'healthy' => true, 'summary' => $this->has_custom_fallback_image() ? __( 'Custom fallback configured', 'paper-hue' ) : __( 'Using bundled Paper Hue fallback', 'paper-hue' ), 'customizer' => 'default_feat_image' ),
			'widgets' => array( 'label' => __( 'Widget Areas', 'paper-hue' ), 'healthy' => true, 'summary' => $widget_summary, 'customizer' => 'widgets' ),
		);
	}
}
