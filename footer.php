<?php
/**
 * For displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Paper_Hue
 */

?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
		<div class="site-info">
			<?php if( get_theme_mod( 'hue_powered_by' ) ): ?>
			<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'paper-hue' ) ); ?>">
				<?php
				/* translators: %s: CMS name, i.e. WordPress. */
				printf( esc_html__( 'Proudly powered by %s', 'paper-hue' ), 'WordPress' );
				?>
			</a>
			<?php if(get_theme_mod( 'hue_credit' )||get_theme_mod( 'hue_copyright' )): ?>
				<span class="sep"> | </span>
		<?php endif; endif; ?>
				<?php
				if(get_theme_mod( 'hue_credit' )){
				/* translators: 1: Theme name, 2: Theme author. */
				printf( esc_html__( 'Theme: %1$s by %2$s.', 'paper-hue' ), 'paper-hue', '<a href="http://www.agustealo.com">Agustealo</a>' );
				}
				?>
				<?php if(get_theme_mod( 'show_copyright' ) && get_theme_mod( 'hue_copyright' )): echo get_theme_mod( 'hue_copyright' ); endif ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
