<?php
/**
 * Template part for the static image header shown away from the slider.
 *
 * @package Paper_Hue
 */

$header_image_url = paper_hue_get_image_url( 'featured-post-image' );
?>

<div class="posts-header-container">
	<div class="header-content" style="background-image: url('<?php echo esc_url( $header_image_url ); ?>');">
		<header class="hue-page-header">
			<?php if ( get_theme_mod( 'hue_header_title', false ) ) : ?>
				<?php if ( is_category() || is_archive() ) : ?>
					<div class="entry-categories">
						<span class="screen-reader-text"><?php esc_html_e( 'Archive:', 'paper-hue' ); ?></span>
						<?php the_archive_title( '<h1 class="page-title archive-header category-header">', '</h1>' ); ?>
					</div>
				<?php elseif ( is_page() ) : ?>
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
					<?php $parent_id = wp_get_post_parent_id( get_the_ID() ); ?>
					<?php if ( $parent_id ) : ?>
						<p class="page-parent-link">
							<?php
							printf(
								/* translators: %s: Parent page title. */
								wp_kses_post( __( 'Sub page of: %s', 'paper-hue' ) ),
								sprintf(
									'<a href="%1$s">%2$s</a>',
									esc_url( get_permalink( $parent_id ) ),
									esc_html( get_the_title( $parent_id ) )
								)
							);
							?>
						</p>
					<?php endif; ?>
				<?php elseif ( is_singular() ) : ?>
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				<?php elseif ( is_404() ) : ?>
					<h1><?php esc_html_e( '404', 'paper-hue' ); ?></h1>
					<p><?php esc_html_e( 'The page you seek does not exist.', 'paper-hue' ); ?></p>
				<?php endif; ?>
			<?php endif; ?>
		</header>
	</div>
</div>
