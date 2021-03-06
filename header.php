<?php
/**
 * The header for our theme
 *
 * This template displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Paper_Hue
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <div id="page" class="site">
        <header id="masthead" class="site-header">
            <div class="hue-slider">
              <?php
              if ( is_front_page() && !is_paged() ) :
                if( get_theme_mod( 'paper_hue_slider' ) ):
                  /* get slider */
                  require get_template_directory() . '/inc/modules/hue-slider.php';
                endif;
                  else :
                  /* get static header content */
                  require get_template_directory() . '/inc/template-parts/content-header-entry.php';
              endif;
              ?>
            </div><!-- .hue-paper-slider -->
            <div class="hue-nav-wraper">
                <div class="site-branding">
                <?php
                $custom_logo_id = get_theme_mod( 'hue_them_logo' );
                $logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
                if ( get_theme_mod( 'hue_them_logo' ) ) :
                ?>
                  <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <img src="<?php  echo $logo[0] ?>" alt=" <?php get_bloginfo( 'name' ) ?> ">
                  </a>
                <?php else: ?>
                <h1 class="site-title">
                  <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
                     rel="home"><?php bloginfo( 'name' ); ?>
                 </a>
                </h1>
                <?php
                $paper_hue_description = get_bloginfo( 'description', 'display' );
                if ( $paper_hue_description || is_customize_preview() ) :
                ?>
                <p class="site-description"><?php echo $paper_hue_description; /* WPCS: xss ok. */ ?></p>
                <?php
                  endif;
                  endif; ?>
              </div><!-- .site-branding -->
              <nav id="main-navigation" class="main-navigation">
                <span class="toggle-icon"></span>
                <?php
          				wp_nav_menu( array(
          					'theme_location' => 'main-menu',
          					'menu_id'        => 'primary-menu',
          				) );
                ?>
                </nav><!-- #site-navigation -->
            </div><!-- .ui-bar -->
        </header><!-- #masthead -->

        <div id="content" class="site-content">
