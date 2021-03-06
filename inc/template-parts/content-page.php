<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Paper_Hue
 */

?>

<article id="post-<?php the_ID(); ?>"  <?php post_class() ; ?>>
	<header class="entry-header">
	<?php
	if ( $post->post_parent ):
	        $page_title = get_the_title($post->ID);
	        echo '<h1 class="entry-title">';
	        _e($page_title);
	        echo '</h1>';

	        $parent_title = get_the_title($post->post_parent);
	        echo '<p>Sub page of: ';
	        echo '<a href="' . get_permalink($post->post_parent) . '">' . __($parent_title) . '</a>';
	        echo '</p>';
	else: the_title( '<h1 class="entry-title">', '</h1>' );
	endif;
	?>
	</header><!-- .entry-header -->

	<?php paper_hue_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
		the_content();

		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'paper-hue' ),
			'after'  => '</div>',
		) );
		?>
	</div><!-- .entry-content -->

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer">
			<?php
			edit_post_link(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Edit <span class="screen-reader-text">%s</span>', 'paper-hue' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
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
