<?php
/**
 * Template part for displaying slider in header
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Paper_Hue
 */

?>
<div class="slideshow-container">
<div class="overlay"></div>
<?php
   // the query
   $slider_posts_query = new WP_Query( array(
    'orderby'          => 'comment_count',
    //'order'            => 'ASC',
     //'category_name' => 'my_category',
    'post_status'      => array( 'publish' ),
    //'post_type'        => array( 'post', 'page' ),
    'posts_per_page'   => 3,
    'ignore_sticky_posts' => true
   )
  );
?>
<?php if ( $slider_posts_query->have_posts() ) : ?>
  <?php while ( $slider_posts_query->have_posts() ) : $slider_posts_query->the_post(); ?>  
  <div class="hueDisplay fade">
        <?php echo get_hue_image("featured") ?>
        <div class="text">
        <h1><?php the_title(); ?></h1>
        <p><?php the_excerpt(); ?></p>        
        </div>
				<a class="hue-button"><input type="button" value="Read More"></a>
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

</div>