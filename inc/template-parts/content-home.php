<?php

/**
 * Template part for displaying homepage content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Paper_Hue
 */
 /* Start the Loop */

/**
* Add function for if(is_sticky()) contidtional arguments for sticky posts
 */

function hue_if_is_sticky($arg){
	if(is_sticky()){
		return $arg;
	}
  }
$count=0;
while ( have_posts() ) : the_post();
if(!is_sticky()) : $count++; endif;
$i++;

?>
<?php if($i==1): echo '<div class="container" >'; endif ?>
<?php if(!is_sticky() && $count==1): echo '<div class="not-sticky" >'; endif; // Separate the sticky posts from other posts ?>
<?php if(!is_sticky() && $count==1): echo '<div class="fp-heading"> <h1>Recent Articles</h1></div>'; endif; // Add intro title to page after sticky ?>
<?php if(!is_sticky() && $count==1): echo '<div class="article-container" >'; endif; // Add container for all none sticky posts ?>
<?php if(is_sticky()): echo('<div class="hue-sticky-row" style="background: url('); get_hue_image("url"); echo(')">'); endif // Add class and background image to sticky posts ?>
    <div class="<?php if(!is_sticky()) : echo 'posts-card'; else: echo 'hue-sticky-wrapper'; endif ?>">
    <?php if(!is_sticky()) : // Display featured images except for sticky posts ?>
    <figure class="header-container post-figure">
        <?php get_hue_image( 'wrapped','thumbnail-large'); ?>
        <?php the_title( '<figcaption class="entry-header"><h1 class="entry-title">', '</h1></figcaption>' ); ?>
        <!-- .entry-header -->
    </figure><!-- .hue-sticky-image-wrapper -->
    <?php endif ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class( hue_if_is_sticky('hue-sticky-article')); ?>>
            <?php  if(is_sticky()): the_title( '<header class="home-sticky-header"><h1 class="entry-title">', '</h1></header>' ); endif ?>
            <p><?php the_excerpt(); ?></p>
						<?php  if(is_sticky()): ?>
            	<input onclick="window.location.href = '<?php echo get_permalink() ?>'" type="button" class="hue-sticky-btn" value="Read More">
						<?php endif; ?>
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
			<?php  if(!is_sticky()): ?>
				<input onclick="window.location.href = '<?php echo get_permalink() ?>'" type="button" class="hue-btn-01" value="Read More">
			<?php endif; ?>
    </div><?php if(!is_sticky()) : echo "<!-- posts-card -->"; else: echo "<!-- hue-sticky-wrapper -->"; endif ?>
    <?php if(is_sticky()) : echo file_get_contents( get_stylesheet_directory_uri() . '/client-side/img/waving-waves.svg' );?>
        </div><!-- .hue-sticky-row -->
    <?php endif ?>
<?php
    endwhile;
    if(!is_sticky()) : echo "</div><!-- .article-container -->"; endif;
    if(!is_sticky()) : echo "</div><!-- .not-sticky -->"; endif;
 ?>
 <!-- .container -->
