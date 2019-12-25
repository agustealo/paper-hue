<?php
/**
 * Template part for displaying the featured image in header
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Paper_Hue
 */
?>

<div class="posts-header-container"> 

<div class="header-content" style="background: url( <?php echo get_hue_image("url") ?> )" >

<header class="page-header">
    <?php
        the_title( '<h1 class="page-title">', '</h1>' );
    ?>
</header><!-- .page-header -->

</div>
</div>