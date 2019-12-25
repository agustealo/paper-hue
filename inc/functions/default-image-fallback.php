<?php
/**
 * Functions which enhance the theme featured image
 * Display featured image on all pages.
 * Create fallback images for posts without featured images.
 * Create <div> wrapper for images
 * Set permalink back to post.
 *
 * @package Paper_Hue
 */

function get_hue_image($image_type="noWrap"){
  $hue_image =  get_template_directory_uri() . "/client-side/img/fallback-postcard-img03.jpg"; // The fallback image location
  $hue_fallback_image = "<img src=\"" . $hue_image . "\"". "class=\"attachment-post-thumbnail size-post-thumbnail wp-post-image fallback-image\" alt=\"Default fallback image\" />";


  if($image_type == "featured"){
    if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) {
      echo "<div class=\"hue_img_wrapper posts_image featured_image\" ><a href=\"" . get_permalink( $post->ID ) . "\">" . get_the_post_thumbnail() . "</a></div>";
    }
    else {
      echo "<div class=\"hue_img_wrapper posts_image featured_image fallback-img\"><a href=\"" . get_permalink( $post->ID ) . "\">"  . $hue_fallback_image . "</a></div>";
    }
  }
  elseif($image_type == "noWrap"){
    if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) {
      echo get_the_post_thumbnail();
    }
    else {
      echo $hue_fallback_image;
    }
  }
  elseif($image_type == "url"){
    if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) {
      echo get_the_post_thumbnail_url();
        }
        else {
          echo $hue_image;
        }
  }}

/**
 * Output a post's first image.
 *
 * @param int $post_id Post ID.
 */
function wpdocs_echo_first_image( $post_id ) {
  $args = array(
      'posts_per_page' => 1,
      'order'          => 'ASC',
      'post_mime_type' => 'image',
      'post_parent'    => $post_id,
      'post_status'    => null,
      'post_type'      => 'attachment',
  );

  $attachments = get_children( $args );

  if ( $attachments ) {
      echo '<img src="' . wp_get_attachment_thumb_url( $attachments[0]->ID ) . '" class="current">';
  }
}