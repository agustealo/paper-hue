<?php
/**
 * Functions which enables featured image option to the theme with fallback image support
 * Display featured image on all pages.
 * Create fallback images for posts without featured images.
 * Create <div> wrapper for images
 * Set permalink back to post.
 *
 * @package Paper_Hue
 */

/*
 * Enable support for Post Thumbnails on posts and pages.
 *
 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
 */

    //  Enabled featured images
    add_theme_support( 'post-thumbnails' );

    //  Set image size for default featured images
    set_post_thumbnail_size( 'featured-post-image', 800, 999999 ); // Not cropped with unlimited height

    // Set additional image sizes
    add_image_size( 'thumbnail-large', 640, 440, true ); // Cropped for home and archive display
    add_image_size( 'thumbnail-small', 320, 200, true ); //  Cropped for small display

    // Selectable admin sizes
    add_image_size( 'medium-width', 640, 9999 ); // No cropping means images will be sized based on max height and width
    add_image_size( 'medium-height', 9999, 640 );
    add_image_size( 'medium-crop', 640, 640 );

    // Register admin image sizes
    add_filter( 'image_size_names_choose', 'hue_custom_sizes' );
    function hue_custom_sizes( $sizes ) {
        return array_merge( $sizes, array(
                'medium-width'      => __( 'Medium Width' ),
                'medium-height'     => __( 'Medium Height' ),
                'thumbnail-large'   => __( 'Medium Something' ),
        ) );
    }

/*
 * Enable image fallback support for featured images, or missing thumbnail.
 *
 */

   function static_fallback_img(){
    if(get_theme_mod('theme_feat_image')){
      $static_fallback_img = get_theme_mod('theme_feat_image');
      $static_fallback_img = wp_get_attachment_image_src( $static_fallback_img , 'full' );
      return $static_fallback_img[0];
    } else {
      $static_fallback_img = __(get_template_directory_uri() . '/client-side/img/paper_hue_fallback.jpg');
      return $static_fallback_img;
    }
    echo $static_fallback_img;
  }
function get_hue_image(
  $image_type='noWrap',
  $image_size='featured-post-image'
  ){

  $hue_image =  static_fallback_img(); // The fallback image location
  $hue_fallback_image = "<img src=\"" . static_fallback_img() . "\"". "class=\"attachment-post-thumbnail size-post-thumbnail wp-post-image fallback-image\" alt=\"Default fallback image\" />";

  if( $image_type == 'wrapped' ){
    if (  (function_exists('has_post_thumbnail') ) && ( has_post_thumbnail() )  ) {

      echo '<div class="hue_img_wrapper posts_image featured_image" ><a href="' . get_permalink( $post->ID ) . '">' ?>
      <?php the_post_thumbnail($image_size);
      echo '</a></div>';
    }
    else {

      echo '<div class="hue_img_wrapper posts_image featured_image fallback-img"><a href="' . get_permalink( $post->ID ) . '">'  . $hue_fallback_image . '</a></div>';
    }
  }
  elseif($image_type == 'noWrap'){
    if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) {

      echo the_post_thumbnail($image_size);
    }
    else {
      echo $hue_fallback_image;
    }
  }
  elseif($image_type == 'url'){
    if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) {

      echo the_post_thumbnail_url($image_size);

        }
        else {

          echo $hue_image;

        }
  }}
