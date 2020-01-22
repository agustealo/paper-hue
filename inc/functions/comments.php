<?php
function preprocess_comment_remove_url( $commentdata ) {
  // Always remove the URL from the comment author's comment
  unset( $commentdata['comment_author_url'] );

  // If the user is speaking in all caps, lowercase the comment
  if( $commentdata['comment_content'] == strtoupper( $commentdata['comment_content'] )) {
    $commentdata['comment_content'] = strtolower( $commentdata['comment_content'] );
  }

  return $commentdata;
}
add_filter( 'preprocess_comment' , 'preprocess_comment_remove_url' );
