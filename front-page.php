<?php
/**
 * The main template file
 *
 * This is used to display a page when nothing more specific matches a query.
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
				/*
				 * Include the Post-Type-specific template for the homepage.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
				?>
					<div class="home-posts-wrapper" >
				<?php
        			if ( is_home() || is_front_page() ) :
                  get_template_part( 'inc/template-parts/content', 'front-page' );
              endif;
        ?>
					</div><!-- home-posts-wrapper -->

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
