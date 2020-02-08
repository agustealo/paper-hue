<?php
function paper_hue_posts_nav( $page_range = 10, $query = NULL )
{
   global $wp_query;

   if( empty( $query ) ){
      $query = $wp_query;
   }

   $page = $query->query_vars["paged"];

   if( !$page ){
      $page = 1;
   }

   $page_start = floor( ($page - 1) / $page_range) * $page_range + 1;
   $page_end = $page_start + $page_range - 1;

   if( $page_end > $query->max_num_pages ){
      $page_end = $query->max_num_pages;
   }

   echo '<nav class="navigation posts-navigation">';
   
   if ( $page <= 1 && $page_end <=1) {
     echo '<span class="end-archive">End of archive</span>';
   }else{

   if( $page > 1 ){
    echo '<span class="hue-posts-nav-prev"><a href="'.previous_posts(FALSE).'" rel="prev">Previous</a></span>';
   }else{
    echo '<span class="hue-posts-nav-prev hue_disabled">Previous</span>';
   }
   if ( $page_end >=2) {
     echo '<ul class="numb-wrapper">';
   }
   for( $i = $page_start; $i <= $page_end; $i++ ){
      $class = "";


      $url = get_pagenum_link( $i );
      if ( $page_end >=2) {
      if( $page == $i){
         $class = ' class="number current"';

         echo "<li$class>$i</li>";

      }else{
         $class = ' class="number"';

         echo "<li$class><a href=\"$url\">$i</a></li>";
      }
    }
   }
   if ( $page_end >=2) {
  echo '</ul>';
  }
   if( $page < $query->max_num_pages ){
      echo '<span class="hue-posts-nav-next"><a href="'.next_posts($query->max_num_pages, FALSE).'" rel="next">Next</a></span>';
   }else{
      echo '<span class="hue-posts-nav-next hue_disabled">Next</span>';
   }
}
   echo '</nav>';
}
