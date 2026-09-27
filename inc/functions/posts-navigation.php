<?php
/**
 * Numbered posts navigation for Paper Hue archives and the posts-style homepage.
 *
 * @package Paper_Hue
 */

/**
 * Render accessible numbered navigation for a query.
 *
 * @param int           $page_range Number of pages to show around the current page.
 * @param WP_Query|null $query      Query instance. Defaults to the main query.
 * @return void
 */
function paper_hue_posts_nav( $page_range = 5, $query = null ) {
	global $wp_query;

	$query = $query instanceof WP_Query ? $query : $wp_query;
	if ( ! $query instanceof WP_Query || $query->max_num_pages <= 1 ) {
		return;
	}

	$current = max( 1, absint( get_query_var( 'paged' ) ) );
	$total   = max( 1, absint( $query->max_num_pages ) );
	$mid_size = max( 1, absint( $page_range ) );

	$links = paginate_links(
		array(
			'current'   => $current,
			'total'     => $total,
			'mid_size'  => $mid_size,
			'prev_text' => esc_html__( 'Previous', 'paper-hue' ),
			'next_text' => esc_html__( 'Next', 'paper-hue' ),
			'type'      => 'list',
		)
	);

	if ( ! $links ) {
		return;
	}
	?>
	<nav class="navigation posts-navigation" aria-label="<?php esc_attr_e( 'Posts navigation', 'paper-hue' ); ?>">
		<?php echo wp_kses_post( $links ); ?>
	</nav>
	<?php
}
