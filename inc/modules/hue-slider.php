<?php
/**
 * Template part for displaying slider in header
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Paper_Hue
 */


   // Settings : Query
   // Setup what kind of posts and how many
   $cats               = get_theme_mod( 'slider_category' );
   $s_total            = get_theme_mod( 's_total' );
   $s_order            = get_theme_mod( 's_order' );
   $s_order_by         = get_theme_mod( 's_order_by' );
   $slider_posts_query = new WP_Query( array(
    'orderby'          => $s_order_by,
    'order'            => $s_order,
    'cat'              => $cats,
    'post_status'      => array( 'publish' ),
    'posts_per_page'   => $s_total,
    'ignore_sticky_posts' => true // Exclude sticky posts from loop, if set to false a plus one will be added to loop's final count, ie. total=(('posts_per_page')+1)
   )
  );
  ?>
  <div class="hue-slider-container" >
  <?php

    if ( $slider_posts_query->have_posts() ) :
      while ( $slider_posts_query->have_posts() ) :
        $slider_posts_query->the_post();

  ?>

<div class="hue-slide-item" style="background: url(<?php get_hue_image("url") ?>)">
  <div class="hue-slide">
        <div class="text">
            <?php the_title( '<header class="entry-header"><h1 class="entry-title">', '</h1></header>' ); ?>
        <p><?php the_excerpt(); ?></p>
        <input class="btn-line" onclick="window.location.href = '<?php echo get_permalink() ?>'" type="button" class="hue-sticky-btn" value="Read More">
        </div>
  </div>
</div>

  <?php endwhile; ?>
  <?php wp_reset_postdata(); ?>

<?php else : ?>
  <p><?php echo 'Nothing to show here, empty category. Select a category with at least one post, or start adding posts with images to the selected category.'; ?></p>
<?php endif; ?>
 <!-- control bar -->
 <div class="control">
        <div>
            <!-- Next and previous buttons -->
            <a class="prev" onclick="plusSlides(-1)"><i>&#9665;</i></a>
            <a class="next" onclick="plusSlides(1)"><i>&#9655;</i></a>
            <!-- counter -->
            <a class="hue-counter"><i class="counter"></i></a>
            <a class="hue-nextsection" href="#content"><i>&#9660;</i></a>
        </div>
        <!-- The dots/circles -->
        <div class="dots-wrapper">
        <?php
            $total_slides = $slider_posts_query->post_count;
            $i = 1;
            for ($x = 1; $x <= $total_slides; $x++) {
                echo "<span class=\"dot\" onclick=\"currentSlide(" . $i++ . ")\">&#9635;</span>";
            }
        ?>
        </div>
        <!-- The Link to content -->
        <a class="to-content-bttn" href="#content"><i
                class="<?php esc_html_e( 'font-icon-arrow-simple', 'paper-hue' ); ?>"></i></a>
    </div>

</div><!-- .hue-slider-container -->
