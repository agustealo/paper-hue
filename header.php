<?php
/**
 * The header for Paper Hue.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Paper_Hue
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'paper-hue' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="hue-slider">
			<?php
			if ( is_front_page() && ! is_paged() && get_theme_mod( 'paper_hue_slider', true ) ) {
				get_template_part( 'inc/modules/hue-slider' );
			} else {
				get_template_part( 'inc/template-parts/content', 'header-entry' );
			}
			?>
		</div><!-- .hue-slider -->

		<div class="hue-nav-wraper">
			<div class="site-branding">
				<?php
				if ( has_custom_logo() ) {
					the_custom_logo();
				} else {
					$legacy_logo_id = absint( get_theme_mod( 'hue_them_logo', 0 ) );

					if ( $legacy_logo_id ) {
						printf(
							'<a class="custom-logo-link paper-hue-legacy-logo" href="%1$s" rel="home">%2$s</a>',
							esc_url( home_url( '/' ) ),
							wp_kses_post(
								wp_get_attachment_image(
									$legacy_logo_id,
									'full',
									false,
									array(
										'class' => 'custom-logo',
										'alt'   => get_bloginfo( 'name' ),
									)
								)
							)
						);
					} else {
						?>
						<h1 class="site-title">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
						</h1>
						<?php
						$paper_hue_description = get_bloginfo( 'description', 'display' );
						if ( $paper_hue_description || is_customize_preview() ) {
							?>
							<p class="site-description"><?php echo esc_html( $paper_hue_description ); ?></p>
							<?php
						}
					}
				}
				?>
			</div><!-- .site-branding -->

			<nav id="main-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Primary navigation', 'paper-hue' ); ?>">
				<button class="paper-hue-menu-toggle" type="button" aria-controls="primary-menu" aria-expanded="false">
					<span class="paper-hue-menu-icon" aria-hidden="true">&#9776;</span>
					<span class="screen-reader-text"><?php esc_html_e( 'Toggle primary navigation', 'paper-hue' ); ?></span>
				</button>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'main-menu',
						'menu_id'        => 'primary-menu',
						'fallback_cb'    => false,
					)
				);
				?>
			</nav><!-- #main-navigation -->
		</div><!-- .hue-nav-wraper -->
	</header><!-- #masthead -->

	<div id="content" class="site-content">
