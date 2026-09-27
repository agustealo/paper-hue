<?php
/**
 * Front page template.
 *
 * Honors WordPress's Reading setting: a static front page displays that page's
 * content, while "Your latest posts" keeps Paper Hue's original article layout.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Paper_Hue
 */

get_header();
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main">
		<?php if ( 'page' === get_option( 'show_on_front' ) ) : ?>
			<?php while ( have_posts() ) : ?>
				<?php the_post(); ?>
				<?php get_template_part( 'inc/template-parts/content', 'page' ); ?>
			<?php endwhile; ?>
		<?php else : ?>
			<div class="home-posts-wrapper">
				<?php get_template_part( 'inc/template-parts/content', 'front-page' ); ?>
			</div>
		<?php endif; ?>
	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
