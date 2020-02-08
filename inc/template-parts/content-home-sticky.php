
<div class="hue-sticky-row" style="background: url(<?php get_hue_image("url") ?>)"><!-- Add class and background image to sticky posts -->
    <div class="hue-sticky-wrapper">
        <article id="post-<?php the_ID(); ?>" <?php post_class( "hue-sticky-article" ); ?>>
            <?php  the_title( '<header class="home-sticky-header"><h1 class="entry-title">', '</h1></header>' ); ?>
            <p><?php the_excerpt(); ?></p>
            	<input onclick="window.location.href = '<?php echo get_permalink() ?>'" type="button" class="hue-sticky-btn" value="Read More">
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
    </div>
        </div><!-- .hue-sticky-row -->
