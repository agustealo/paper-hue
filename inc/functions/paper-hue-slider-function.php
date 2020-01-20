<?php

/**
 * Add Paper Hue Slider control and information for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */

function paper_hue_slider_customize_register( $wp_customize ) {
  
  
}
add_action( 'customize_register', 'paper_hue_slider_customize_register' );

////////////////////
    $args = array(
        'post_type'           => 'post',
        'post_status'         => 'publish',            
        'ignore_sticky_posts' => true
    );

    if( $slider_type === 'cat' && $slider_cat ){
        $args['cat']            = $slider_cat; 
        $args['posts_per_page'] = -1;  
    }else{
        $args['posts_per_page'] = $posts_per_page;
    }

/**
 * Template part for displaying slider in header
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package paper_hue_slider
 */


   // Settings : Query
   // Setup what kind of posts and how many
   $slider_posts_query = new WP_Query( array(
    'orderby'          => 'comment_count',
    //'order'            => 'ASC',
     //'category_name' => 'my_category',
    'post_status'      => array( 'publish' ),
    //'post_type'        => array( 'post', 'page' ),
    'posts_per_page'   => 3,
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