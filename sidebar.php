<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Paper_Hue
 */

if (
	! is_active_sidebar('widget-bottom-1') ||
	! is_active_sidebar( 'widget-bottom-2') ||
	! is_active_sidebar( 'widget-bottom-3') ||
	! is_active_sidebar( 'widget-bottom-4' )
	) {
	return;
}
?>

<div class="widget-wrapper">
<div class="widget-container">
<aside id="secondary" class="widget-area">
	<?php dynamic_sidebar( 'widget-bottom-1' ); ?>
</aside><!-- #secondary -->
<aside id="secondary" class="widget-area">
	<?php dynamic_sidebar( 'widget-bottom-2' ); ?>
</aside><!-- #secondary -->
<aside id="secondary" class="widget-area">
	<?php dynamic_sidebar( 'widget-bottom-3' ); ?>
</aside><!-- #secondary -->
<aside id="secondary" class="widget-area">
	<?php dynamic_sidebar( 'widget-bottom-4' ); ?>
</aside><!-- #secondary -->
</div><!-- #widget-container -->
</div><!-- #widget-wrapper -->
