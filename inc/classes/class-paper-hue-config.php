<?php
/**
 * Centralized Paper Hue configuration access.
 *
 * @package Paper_Hue
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Provides a single read-only access layer for theme configuration.
 */
final class Paper_Hue_Config {
	/**
	 * Singleton instance.
	 *
	 * @var Paper_Hue_Config|null
	 */
	private static $instance = null;

	/**
	 * Get singleton instance.
	 *
	 * @return Paper_Hue_Config
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Prevent direct construction.
	 */
	private function __construct() {}

	/**
	 * Whether the homepage slider is enabled.
	 *
	 * @return bool
	 */
	public function slider_enabled() {
		return (bool) get_theme_mod( 'paper_hue_slider', true );
	}

	/**
	 * Get the selected slider category ID.
	 *
	 * @return int
	 */
	public function slider_category_id() {
		return absint( get_theme_mod( 'slider_category', 1 ) );
	}

	/**
	 * Get the selected slider category object.
	 *
	 * @return WP_Term|null
	 */
	public function slider_category() {
		$term = get_term( $this->slider_category_id(), 'category' );

		return $term instanceof WP_Term ? $term : null;
	}

	/**
	 * Get the slider total.
	 *
	 * @return int
	 */
	public function slider_total() {
		return paper_hue_sanitize_slider_count( get_theme_mod( 's_total', 3 ) );
	}

	/**
	 * Whether the featured sticky story is enabled.
	 *
	 * @return bool
	 */
	public function featured_story_enabled() {
		return (bool) get_theme_mod( 'show_feat_sticky', true );
	}

	/**
	 * Get the first sticky post used by the existing Paper Hue experience.
	 *
	 * @return WP_Post|null
	 */
	public function featured_story() {
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

	/**
	 * Whether front-page pagination is enabled.
	 *
	 * @return bool
	 */
	public function homepage_pagination_enabled() {
		return (bool) get_theme_mod( 'hue_post_nav', false );
	}

	/**
	 * Whether header titles are enabled.
	 *
	 * @return bool
	 */
	public function header_title_enabled() {
		return (bool) get_theme_mod( 'hue_header_title', false );
	}

	/**
	 * Get fallback-image attachment ID.
	 *
	 * @return int
	 */
	public function fallback_image_id() {
		return absint( get_theme_mod( 'theme_feat_image', 0 ) );
	}

	/**
	 * Whether a custom fallback image is configured.
	 *
	 * @return bool
	 */
	public function has_custom_fallback_image() {
		return $this->fallback_image_id() > 0 && (bool) wp_get_attachment_image_url( $this->fallback_image_id(), 'full' );
	}

	/**
	 * Get the effective logo attachment ID while preserving legacy Paper Hue data.
	 *
	 * @return int
	 */
	public function logo_id() {
		$native_logo = absint( get_theme_mod( 'custom_logo', 0 ) );

		if ( $native_logo ) {
			return $native_logo;
		}

		return absint( get_theme_mod( 'hue_them_logo', 0 ) );
	}

	/**
	 * Whether a navigation menu is assigned to the primary location.
	 *
	 * @return bool
	 */
	public function has_primary_menu() {
		$locations = get_nav_menu_locations();

		return ! empty( $locations['main-menu'] );
	}

	/**
	 * Get active Paper Hue widget areas.
	 *
	 * @return string[]
	 */
	public function active_widget_areas() {
		$ids = array( 'posts-widget', 'widget-bottom-1', 'widget-bottom-2', 'widget-bottom-3', 'widget-bottom-4' );

		return array_values(
			array_filter(
				$ids,
				static function ( $id ) {
					return is_active_sidebar( $id );
				}
			)
		);
	}

	/**
	 * Count published posts in the configured slider category.
	 *
	 * @return int
	 */
	public function slider_source_count() {
		$category = $this->slider_category();

		return $category ? absint( $category->count ) : 0;
	}

	/**
	 * Build the complete dashboard status payload.
	 *
	 * @return array<string,array<string,mixed>>
	 */
	public function dashboard_status() {
		$slider_category = $this->slider_category();
		$featured_story  = $this->featured_story();
		$widget_areas    = $this->active_widget_areas();

		return array(
			'identity' => array(
				'label'       => __( 'Site Identity', 'paper-hue' ),
				'healthy'     => $this->logo_id() > 0 || get_bloginfo( 'name' ),
				'summary'     => $this->logo_id() > 0 ? __( 'Logo configured', 'paper-hue' ) : __( 'Using site title', 'paper-hue' ),
				'customizer'  => 'title_tagline',
			),
			'navigation' => array(
				'label'       => __( 'Primary Navigation', 'paper-hue' ),
				'healthy'     => $this->has_primary_menu(),
				'summary'     => $this->has_primary_menu() ? __( 'Menu assigned', 'paper-hue' ) : __( 'No menu assigned', 'paper-hue' ),
				'customizer'  => 'nav_menus',
			),
			'slider' => array(
				'label'       => __( 'Hero Slider', 'paper-hue' ),
				'healthy'     => ! $this->slider_enabled() || ( $slider_category && $this->slider_source_count() > 0 ),
				'summary'     => $this->slider_enabled()
					? sprintf(
						/* translators: 1: category name, 2: slide count. */
						__( '%1$s · %2$d slides', 'paper-hue' ),
						$slider_category ? $slider_category->name : __( 'Missing category', 'paper-hue' ),
						$this->slider_total()
					)
					: __( 'Disabled', 'paper-hue' ),
				'customizer'  => 'slider_options',
			),
			'featured_story' => array(
				'label'       => __( 'Featured Story', 'paper-hue' ),
				'healthy'     => ! $this->featured_story_enabled() || ( $featured_story instanceof WP_Post ),
				'summary'     => $this->featured_story_enabled()
					? ( $featured_story ? get_the_title( $featured_story ) : __( 'No sticky post available', 'paper-hue' ) )
					: __( 'Disabled', 'paper-hue' ),
				'customizer'  => 'feat_post',
			),
			'recent_articles' => array(
				'label'       => __( 'Recent Articles', 'paper-hue' ),
				'healthy'     => true,
				'summary'     => $this->homepage_pagination_enabled() ? __( 'Pagination enabled', 'paper-hue' ) : __( 'Classic article grid', 'paper-hue' ),
				'customizer'  => 'hue_front_page',
			),
			'images' => array(
				'label'       => __( 'Fallback Image', 'paper-hue' ),
				'healthy'     => true,
				'summary'     => $this->has_custom_fallback_image() ? __( 'Custom fallback configured', 'paper-hue' ) : __( 'Using bundled Paper Hue fallback', 'paper-hue' ),
				'customizer'  => 'default_feat_image',
			),
			'widgets' => array(
				'label'       => __( 'Widget Areas', 'paper-hue' ),
				'healthy'     => true,
				'summary'     => sprintf(
					/* translators: %d: number of active widget areas. */
					_n( '%d active area', '%d active areas', count( $widget_areas ), 'paper-hue' ),
					count( $widget_areas )
				),
				'customizer'  => 'widgets',
			),
		);
	}
}
