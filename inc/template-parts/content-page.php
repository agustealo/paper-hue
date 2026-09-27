<?php
/**
 * Template part for displaying page content.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Paper_Hue
 */

$parent_id = wp_get_post_parent_id( get_the_ID() );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<?php if ( $parent_id ) : ?>
			<p class="page-parent-link">
				<?php
				printf(
					/* translators: %s: Parent page title. */
					wp_kses_post( __( 'Sub page of: %s', 'paper-hue' ) ),
					sprintf(
						'<a href="%1$s">%2$s</a>',
						esc_url( get_permalink( $parent_id ) ),
						esc_html( get_the_title( $parent_id ) )
					)
				);
				?>
			</p>
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php paper_hue_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'paper-hue' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer">
			<?php
			edit_post_link(
				sprintf(
					wp_kses(
						/* translators: %s: Page title. */
						__( 'Edit <span class="screen-reader-text">%s</span>', 'paper-hue' ),
						array( 'span' => array( 'class' => array() ) )
					),
					get_the_title()
				),
				'<span class="edit-link">',
				'</span>'
			);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
