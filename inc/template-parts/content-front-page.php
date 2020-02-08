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
?>
<div class="container" >
	<?php
	$args = array('post__in' => get_option('sticky_posts'));
	$stickyposts = new WP_Query($args);
	while($stickyposts->have_posts()) : $stickyposts->the_post();
		if(get_theme_mod( 'show_feat_sticky' ) && is_sticky() && !is_paged()):// Show only on front-page first page
			$i++;
			if($i==1): $exclude_sticky = $post->ID; endif;// Get only the first sticky post ID
			require_once get_template_directory() . '/inc/template-parts/content-home-sticky.php';
		endif;
	endwhile;
	 ?>

	<div class="not-sticky" ><!-- Separate the sticky posts from other posts -->
		<div class="fp-heading">
			<h1>Recent Articles</h1>
		</div><!-- Add intro title to page after sticky -->
		<div class="article-container" ><!-- Add container for all none sticky posts -->

		<?php
		if ( have_posts() ) : while ( have_posts() ) : the_post();
			if ( $post->ID == $exclude_sticky ) continue;// Exclude sticky from loop
	  ?>
  <div class="posts-card">
			<!-- Display featured images except for sticky posts -->
    <figure class="header-container post-figure">
        <?php get_hue_image( 'wrapped','thumbnail-large'); ?>
        <?php the_title( '<figcaption class="entry-header">'. '<a href="' . get_permalink() . '" >' . '<h1 class="entry-title">', '</h1></a></figcaption>' ); ?>
        <!-- .entry-header -->
    </figure><!-- .hue-sticky-image-wrapper -->
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <p><?php the_excerpt(); ?></p>
        <?php if ( get_edit_post_link() ) : ?>
        <footer class="entry-footer">
        <?php
					edit_post_link( sprintf( wp_kses(
								/* translators: %s: Name of current post. Only visible to screen readers */
								__( 'Edit <span class="screen-reader-text">%s</span>', 'paper-hue' ),
									array(
												'span'  => array(
													'class' => array(),
										), ) ),
									get_the_title() ),
						'<span class="edit-link">',
						'</span>'
					);
				?>
        </footer><!-- .entry-footer -->
        <?php endif; ?>
  	</article><!-- #post-<?php the_ID(); ?> -->
				<input onclick="window.location.href = '<?php echo get_permalink() ?>'" type="button" class="hue-btn-01" value="Read More">
  </div><!-- posts-card -->
<?php endwhile; ?>
</div><!-- .article-container -->
</div><!-- .not-sticky -->
<?php
	if(get_theme_mod('hue_post_nav')): paper_hue_posts_nav(); endif;
	endif;
	?>
</div><!-- .container -->
