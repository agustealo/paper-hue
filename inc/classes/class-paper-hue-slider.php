<?php
/**
 * Paper Hue slider service.
 *
 * @package Paper_Hue
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Owns slider configuration and post selection.
 */
final class Paper_Hue_Slider {
	/**
	 * Get the configured source.
	 *
	 * @return string
	 */
	public function source() {
		$source = sanitize_key( get_theme_mod( 'paper_hue_slider_source', 'category' ) );
		return in_array( $source, array( 'category', 'latest', 'sticky' ), true ) ? $source : 'category';
	}

	/**
	 * Whether autoplay is enabled.
	 *
	 * @return bool
	 */
	public function autoplay() {
		return (bool) get_theme_mod( 'paper_hue_slider_autoplay', true );
	}

	/**
	 * Autoplay delay in milliseconds.
	 *
	 * @return int
	 */
	public function interval() {
		$value = absint( get_theme_mod( 'paper_hue_slider_interval', 4000 ) );
		return min( 12000, max( 2000, $value ) );
	}

	/**
	 * Whether slider excerpts should be displayed.
	 *
	 * @return bool
	 */
	public function show_excerpt() {
		return (bool) get_theme_mod( 'paper_hue_slider_show_excerpt', true );
	}

	/**
	 * Whether arrow controls should be displayed.
	 *
	 * @return bool
	 */
	public function show_arrows() {
		return (bool) get_theme_mod( 'paper_hue_slider_show_arrows', true );
	}

	/**
	 * Whether dot controls should be displayed.
	 *
	 * @return bool
	 */
	public function show_dots() {
		return (bool) get_theme_mod( 'paper_hue_slider_show_dots', true );
	}

	/**
	 * Whether autoplay pauses while the slider is hovered or focused.
	 *
	 * @return bool
	 */
	public function pause_on_interaction() {
		return (bool) get_theme_mod( 'paper_hue_slider_pause_on_interaction', true );
	}

	/**
	 * Get CTA label.
	 *
	 * @return string
	 */
	public function cta_label() {
		$label = trim( (string) get_theme_mod( 'paper_hue_slider_cta_label', __( 'Read More', 'paper-hue' ) ) );
		return '' !== $label ? $label : __( 'Read More', 'paper-hue' );
	}

	/**
	 * Build slider query arguments while preserving old defaults.
	 *
	 * @return array<string,mixed>
	 */
	public function query_args() {
		$total   = paper_hue_sanitize_slider_count( get_theme_mod( 's_total', 3 ) );
		$order   = paper_hue_sanitize_slider_order( get_theme_mod( 's_order', 'ASC' ) );
		$orderby = paper_hue_sanitize_slider_orderby( get_theme_mod( 's_order_by', 'date' ) );
		$orderby = 'id' === $orderby ? 'ID' : $orderby;

		$args = array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => $total,
			'orderby'             => $orderby,
			'order'               => $order,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		);

		switch ( $this->source() ) {
			case 'latest':
				$args['orderby'] = 'date';
				$args['order']   = 'DESC';
				break;

			case 'sticky':
				$sticky_ids = array_values( array_filter( array_map( 'absint', (array) get_option( 'sticky_posts', array() ) ) ) );
				if ( empty( $sticky_ids ) ) {
					$args['post__in'] = array( 0 );
				} else {
					$args['post__in'] = $sticky_ids;
					$args['orderby']  = 'date';
					$args['order']    = 'DESC';
				}
				break;

			case 'category':
			default:
				$args['cat'] = absint( get_theme_mod( 'slider_category', 1 ) );
				break;
		}

		/**
		 * Filter the slider query arguments after Paper Hue has validated them.
		 *
		 * @param array<string,mixed> $args Slider query arguments.
		 * @param Paper_Hue_Slider    $this Slider service.
		 */
		return apply_filters( 'paper_hue_slider_query_args', $args, $this );
	}

	/**
	 * Create a slider query.
	 *
	 * @return WP_Query
	 */
	public function query() {
		return new WP_Query( $this->query_args() );
	}

	/**
	 * Build data attributes consumed by the slider JavaScript.
	 *
	 * @return array<string,string>
	 */
	public function data_attributes() {
		return array(
			'data-paper-hue-slider' => '1',
			'data-autoplay'         => $this->autoplay() ? '1' : '0',
			'data-interval'         => (string) $this->interval(),
			'data-pause'            => $this->pause_on_interaction() ? '1' : '0',
		);
	}
}
