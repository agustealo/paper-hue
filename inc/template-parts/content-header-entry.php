<?php
/**
 * Template part for displaying the featured image in header
 *
 * @package Paper_Hue
 *
 */
?>

<div class="posts-header-container"> 

<div class="header-content" style="background: url( <?php echo esc_url( get_hue_image("url") ) ?> )" >

<header class="hue-page-header">

<?php
    /**
     * 
     * @param bool   Whether to show the categories in header, Default true.
     */
$show_categories = apply_filters( 'paper_hue_show_categories_in_entry_header', true );

if ( is_category() || is_archive() ) {
    ?>

    <div class="entry-categories">
        <span class="screen-reader-text"><?php esc_attr( 'Categories', 'paper_hue' ); ?></span>
    <?php
        the_archive_title( '<h1 class="page-title archive-header category-header">', '</h1>' );
                the_archive_description( '<div class="archive-description">', '</div>' );
                ?>
    </div><!-- .entry-categories -->

    <?php
}elseif ( is_page() ) {
    if ( $post->post_parent ) : 
        $page_title = get_the_title($post->ID);
        echo '<h1>';
        _e($page_title);
        echo '</h1>';

        $parent_title = get_the_title($post->post_parent);
        echo '<p>Sub page of: ';
        echo '<a href="' . get_permalink($post->post_parent) . '">' . $parent_title . '</a>';
        echo '</p>';

    else : echo '<h1>' . get_the_title($post->ID) . '</h1>';
endif;
}elseif ( is_singular() || is_single() ) {
    the_title( '<h1 class="entry-title">', '</h1>' );
}elseif ( is_404() ) {
    echo '<h1>404</h1>';
    echo '<p>The page you seek doesn\'t exist.</p>';
}

?>
</header><!-- .page-header -->

</div><!-- .header-content -->
</div><!-- .posts-header-container -->