<?php
/**
 * For displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Paper_Hue
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php
				the_archive_title( '<h1 class="page-title archive-header category-header">', '</h1>' );
				echo '<span class="hue-breadcrumb">';
				get_breadcrumb();
				echo '</span>';
				?>
			</header><!-- .page-header -->
			<div class="category-container">

			<?php
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
				get_template_part( 'inc/template-parts/content', get_post_type() );

			endwhile;
			echo '</div><!-- category-container -->';
			paper_hue_posts_nav();

		else :

			get_template_part( 'inc/template-parts/content', 'none' );

		endif;
		?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
