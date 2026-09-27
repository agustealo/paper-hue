<?php
/**
 * Template part for displaying the posts-style front page.
 *
 * @package Paper_Hue
 */

$featured_sticky_id = 0;
$sticky_ids         = array_values( array_filter( array_map( 'absint', (array) get_option( 'sticky_posts', array() ) ) ) );

if ( get_theme_mod( 'show_feat_sticky', true ) && ! is_paged() && $sticky_ids ) {
	$sticky_query = new WP_Query(
		array(
			'post__in'            => $sticky_ids,
			'posts_per_page'      => 1,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);

	if ( $sticky_query->have_posts() ) {
		$sticky_query->the_post();
		$featured_sticky_id = get_the_ID();
		get_template_part( 'inc/template-parts/content', 'home-sticky' );
	}

	wp_reset_postdata();
}
?>

<div class="container">
	<div class="not-sticky">
		<div class="fp-heading">
			<h1><?php esc_html_e( 'Recent Articles', 'paper-hue' ); ?></h1>
		</div>

		<div class="article-container">
			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : ?>
					<?php
					the_post();
					if ( $featured_sticky_id && get_the_ID() === $featured_sticky_id ) {
						continue;
					}
					?>
					<div class="posts-card">
						<figure class="header-container post-figure">
							<?php get_hue_image( 'wrapped', 'thumbnail-large' ); ?>
							<figcaption class="entry-header">
								<h2 class="entry-title">
									<a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a>
								</h2>
							</figcaption>
						</figure>

						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<?php the_excerpt(); ?>

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

						<a class="hue-btn-01" href="<?php echo esc_url( get_permalink() ); ?>">
							<?php esc_html_e( 'Read More', 'paper-hue' ); ?>
						</a>
					</div>
				<?php endwhile; ?>
			<?php else : ?>
				<?php get_template_part( 'inc/template-parts/content', 'none' ); ?>
			<?php endif; ?>
		</div>
	</div>

	<?php if ( get_theme_mod( 'hue_post_nav', false ) ) : ?>
		<?php paper_hue_posts_nav(); ?>
	<?php endif; ?>
</div>
