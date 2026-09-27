<?php
/**
 * Slider template for the site header.
 *
 * @package Paper_Hue
 */

$paper_hue_slider = new Paper_Hue_Slider();
$slider_query     = $paper_hue_slider->query();
$slider_attrs     = $paper_hue_slider->data_attributes();
?>
<div
	class="hue-slider-container"
	<?php foreach ( $slider_attrs as $attribute => $value ) : ?>
		<?php echo esc_attr( $attribute ); ?>="<?php echo esc_attr( $value ); ?>"
	<?php endforeach; ?>
	aria-roledescription="<?php esc_attr_e( 'carousel', 'paper-hue' ); ?>"
	aria-label="<?php esc_attr_e( 'Featured stories', 'paper-hue' ); ?>"
>
	<?php if ( $slider_query->have_posts() ) : ?>
		<?php while ( $slider_query->have_posts() ) : ?>
			<?php $slider_query->the_post(); ?>
			<article class="hue-slide-item" style="background-image: url('<?php echo esc_url( paper_hue_get_image_url( 'featured-post-image' ) ); ?>');">
				<div class="hue-slide">
					<div class="text">
						<?php the_title( '<header class="entry-header"><h2 class="entry-title">', '</h2></header>' ); ?>
						<?php if ( $paper_hue_slider->show_excerpt() ) : ?>
							<?php the_excerpt(); ?>
						<?php endif; ?>
						<a class="btn-line hue-sticky-btn" href="<?php echo esc_url( get_permalink() ); ?>">
							<?php echo esc_html( $paper_hue_slider->cta_label() ); ?>
						</a>
					</div>
				</div>
			</article>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
	<?php else : ?>
		<p class="hue-slider-empty"><?php esc_html_e( 'No posts are available for the current slider source yet.', 'paper-hue' ); ?></p>
	<?php endif; ?>

	<?php if ( $slider_query->post_count > 0 ) : ?>
		<nav class="control" aria-label="<?php esc_attr_e( 'Hero Slider controls', 'paper-hue' ); ?>">
			<div class="slider-control-group">
				<?php if ( $paper_hue_slider->show_arrows() && $slider_query->post_count > 1 ) : ?>
					<button class="prev" type="button" aria-label="<?php esc_attr_e( 'Previous slide', 'paper-hue' ); ?>"><i aria-hidden="true">&#9665;</i></button>
					<button class="next" type="button" aria-label="<?php esc_attr_e( 'Next slide', 'paper-hue' ); ?>"><i aria-hidden="true">&#9655;</i></button>
				<?php endif; ?>
				<span class="hue-counter" aria-live="polite"><i class="counter"></i></span>
			</div>

			<?php if ( $paper_hue_slider->show_dots() && $slider_query->post_count > 1 ) : ?>
				<div class="dots-wrapper" role="group" aria-label="<?php esc_attr_e( 'Choose slide', 'paper-hue' ); ?>">
					<?php for ( $slide_number = 1; $slide_number <= $slider_query->post_count; $slide_number++ ) : ?>
						<?php /* translators: %d: slide number. */ ?>
						<button class="dot" type="button" aria-label="<?php echo esc_attr( sprintf( __( 'Go to slide %d', 'paper-hue' ), $slide_number ) ); ?>">&#9635;</button>
					<?php endfor; ?>
				</div>
			<?php endif; ?>

			<a class="to-content-bttn" href="#content" aria-label="<?php esc_attr_e( 'Continue to content', 'paper-hue' ); ?>">
				<i aria-hidden="true">&#9660;</i>
			</a>
		</nav>
	<?php endif; ?>
</div><!-- .hue-slider-container -->
