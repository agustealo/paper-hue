<?php
/**
 * The footer for Paper Hue.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Paper_Hue
 */
?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
		<div class="site-info">
			<?php if ( get_theme_mod( 'hue_powered_by', true ) ) : ?>
				<a href="<?php echo esc_url( 'https://wordpress.org/' ); ?>">
					<?php
					printf(
						/* translators: %s: CMS name. */
						esc_html__( 'Proudly powered by %s', 'paper-hue' ),
						'WordPress'
					);
					?>
				</a>
			<?php endif; ?>

			<?php if ( get_theme_mod( 'hue_credit', true ) ) : ?>
				<?php if ( get_theme_mod( 'hue_powered_by', true ) ) : ?>
					<span class="sep"> | </span>
				<?php endif; ?>
				<?php
				printf(
					/* translators: 1: Theme name, 2: Theme author link. */
					wp_kses_post( __( 'Theme: %1$s by %2$s.', 'paper-hue' ) ),
					esc_html__( 'Paper Hue', 'paper-hue' ),
					'<a href="https://www.agustealo.com/">Agustealo</a>'
				);
				?>
			<?php endif; ?>

			<?php
			$footer_info = get_theme_mod( 'hue_copyright', '' );
			if ( get_theme_mod( 'show_copyright', true ) && $footer_info ) :
				?>
				<span class="paper-hue-footer-info"><?php echo wp_kses_post( $footer_info ); ?></span>
			<?php endif; ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
