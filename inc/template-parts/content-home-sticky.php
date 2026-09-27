<?php
/**
 * Featured Story shown on the posts-style front page.
 *
 * @package Paper_Hue
 */
?>

<div class="hue-sticky-row" style="background-image: url('<?php echo esc_url( paper_hue_get_image_url( 'featured-post-image' ) ); ?>');">
	<div class="hue-sticky-wrapper">
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'hue-sticky-article' ); ?>>
			<?php the_title( '<header class="home-sticky-header"><h2 class="entry-title">', '</h2></header>' ); ?>

			<?php if ( Paper_Hue_Featured_Story::show_meta() ) : ?>
				<p class="hue-sticky-meta">
					<?php
					printf(
						/* translators: 1: author name, 2: publication date. */
						esc_html__( 'By %1$s · %2$s', 'paper-hue' ),
						esc_html( get_the_author() ),
						esc_html( get_the_date() )
					);
					?>
				</p>
			<?php endif; ?>

			<?php if ( Paper_Hue_Featured_Story::show_excerpt() ) : ?>
				<?php the_excerpt(); ?>
			<?php endif; ?>

			<a class="hue-sticky-btn" href="<?php echo esc_url( get_permalink() ); ?>">
				<?php echo esc_html( Paper_Hue_Featured_Story::cta_label() ); ?>
			</a>

			<?php if ( get_edit_post_link() ) : ?>
				<footer class="entry-footer">
					<?php
					edit_post_link(
						sprintf(
							wp_kses(
								/* translators: %s: Post title. */
								__( 'Edit <span class="screen-reader-text">%s</span>', 'paper-hue' ),
								array( 'span' => array( 'class' => array() ) )
							),
							get_the_title()
						),
						'<span class="edit-link">',
						'</span>'
					);
					?>
				</footer>
			<?php endif; ?>
		</article>
	</div>
</div>
