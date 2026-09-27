<?php
/**
 * Slider template for the site header.
 *
 * @package Paper_Hue
 */

$slider_category = absint( get_theme_mod( 'slider_category', 1 ) );
$slider_total    = paper_hue_sanitize_slider_count( get_theme_mod( 's_total', 3 ) );
$slider_order    = paper_hue_sanitize_slider_order( get_theme_mod( 's_order', 'ASC' ) );
$slider_orderby  = paper_hue_sanitize_slider_orderby( get_theme_mod( 's_order_by', 'date' ) );
$slider_orderby  = 'id' === $slider_orderby ? 'ID' : $slider_orderby;

$slider_posts_query = new WP_Query(
	array(
		'orderby'             => $slider_orderby,
		'order'               => $slider_order,
		'cat'                 => $slider_category,
		'post_status'         => 'publish',
		'posts_per_page'      => $slider_total,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);
?>
<div class="hue-slider-container">
	<?php if ( $slider_posts_query->have_posts() ) : ?>
		<?php while ( $slider_posts_query->have_posts() ) : ?>
			<?php $slider_posts_query->the_post(); ?>
			<div class="hue-slide-item" style="background-image: url('<?php echo esc_url( paper_hue_get_image_url( 'featured-post-image' ) ); ?>');">
				<div class="hue-slide">
					<div class="text">
						<?php the_title( '<header class="entry-header"><h2 class="entry-title">', '</h2></header>' ); ?>
						<?php the_excerpt(); ?>
						<a class="btn-line hue-sticky-btn" href="<?php echo esc_url( get_permalink() ); ?>">
							<?php esc_html_e( 'Read More', 'paper-hue' ); ?>
						</a>
					</div>
				</div>
			</div>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
	<?php else : ?>
		<p class="hue-slider-empty">
			<?php esc_html_e( 'No posts are available in the selected slider category yet.', 'paper-hue' ); ?>
		</p>
	<?php endif; ?>

	<?php if ( $slider_posts_query->post_count > 0 ) : ?>
		<div class="control">
			<div>
				<button class="prev" type="button" onclick="plusSlides(-1)" aria-label="<?php esc_attr_e( 'Previous slide', 'paper-hue' ); ?>"><i aria-hidden="true">&#9665;</i></button>
				<button class="next" type="button" onclick="plusSlides(1)" aria-label="<?php esc_attr_e( 'Next slide', 'paper-hue' ); ?>"><i aria-hidden="true">&#9655;</i></button>
				<span class="hue-counter" aria-live="polite"><i class="counter"></i></span>
				<a class="hue-nextsection" href="#content" aria-label="<?php esc_attr_e( 'Skip slider and continue to content', 'paper-hue' ); ?>"><i aria-hidden="true">&#9660;</i></a>
			</div>

			<div class="dots-wrapper">
				<?php for ( $slide_number = 1; $slide_number <= $slider_posts_query->post_count; $slide_number++ ) : ?>
					<button class="dot" type="button" onclick="currentSlide(<?php echo esc_attr( $slide_number ); ?>)" aria-label="<?php echo esc_attr( sprintf( __( 'Go to slide %d', 'paper-hue' ), $slide_number ) ); ?>">&#9635;</button>
				<?php endfor; ?>
			</div>

			<a class="to-content-bttn" href="#content" aria-label="<?php esc_attr_e( 'Continue to content', 'paper-hue' ); ?>">
				<i class="font-icon-arrow-simple" aria-hidden="true"></i>
			</a>
		</div>
	<?php endif; ?>
</div><!-- .hue-slider-container -->
