<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Paper_Hue
 */

get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <?php
		if ( have_posts() ) :

			if ( is_home() && ! is_front_page() ) :
				?>
        <header>
            <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
		</header>
        <?php
			endif;

				/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
				if ( is_front_page() && is_home() ) { 
				?>
					<div class="home-posts-wrapper" >
				<?php get_template_part( 'inc/template-parts/content', 'home' ); ?>
					</div><!-- home-posts-wrapper -->
				<?php
				  } else {
					/* Start the Loop */
					while ( have_posts() ) : the_post();
					  if ( is_front_page() ) {
					// static homepage
					get_template_part( 'inc/template-parts/content', 'page' );
				  } elseif ( is_home() ) {
					// blog page
				  } else {
					//everything else
					get_template_part( 'inc/template-parts/content', get_post_type() );
				  }

				endwhile;

				the_posts_navigation();
				}

		else :

			get_template_part( 'inc/template-parts/content', 'none' );

		endif;
		?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();