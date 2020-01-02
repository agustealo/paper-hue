<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
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
                if ( is_home() && is_front_page() ) :	
		/* get slider */
        require get_template_directory() . '/inc/modules/hue-slider.php';
                    else : 
                        /* get static header content */
        require get_template_directory() . '/inc/template-parts/content-header-entry.php';
                    endif;
	?>
            </div><!-- .hue-paper-slider -->
            <div class="hue-nav-wraper">
                <div class="site-branding">
                    <?php
				the_custom_logo();
					?>
                    <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"
                            rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                    <?php
				$paper_hue_description = get_bloginfo( 'description', 'display' );
				if ( $paper_hue_description || is_customize_preview() ) :
					?>
                    <p class="site-description"><?php echo $paper_hue_description; /* WPCS: xss ok. */ ?></p>
                    <?php endif; ?>
                </div><!-- .site-branding -->

                <nav id="site-navigation" class="main-navigation">
                    <?php
				wp_nav_menu( array(
					'theme_location' => 'menu-1',
					'menu_id'        => 'primary-menu',
				) );
				?>
                </nav><!-- #site-navigation -->
            </div><!-- .ui-bar -->
        </header><!-- #masthead -->

        <div id="content" class="site-content">