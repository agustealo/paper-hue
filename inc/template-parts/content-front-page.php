<?php
/**
 * Template part for displaying the posts-style front page.
 *
 * @package Paper_Hue
 */

$featured_story_id = Paper_Hue_Featured_Story::post_id();

if ( $featured_story_id ) {
	$featured_query = new WP_Query(
		array(
			'p'                   => $featured_story_id,
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => 1,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);

	if ( $featured_query->have_posts() ) {
		$featured_query->the_post();
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
					if ( $featured_story_id && Paper_Hue_Featured_Story::exclude_from_recent() && get_the_ID() === $featured_story_id ) {
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
