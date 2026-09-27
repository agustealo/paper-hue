<?php
/**
 * WooCommerce Compatibility File
 *
 * @link https://woocommerce.com/
 *
 * @package Paper_Hue
 */

/**
 * WooCommerce setup function.
 *
 * @return void
 */
function paper_hue_woocommerce_setup() {
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'paper_hue_woocommerce_setup' );

/**
 * WooCommerce specific scripts & stylesheets.
 *
 * @return void
 */
function paper_hue_woocommerce_scripts() {
	wp_enqueue_style(
		'paper-hue-woocommerce-style',
		get_template_directory_uri() . '/woocommerce.css',
		array(),
		paper_hue_asset_version( 'woocommerce.css' )
	);

	$font_path   = WC()->plugin_url() . '/assets/fonts/';
	$inline_font = '@font-face {
			font-family: "star";
			src: url("' . $font_path . 'star.eot");
			src: url("' . $font_path . 'star.eot?#iefix") format("embedded-opentype"),
				url("' . $font_path . 'star.woff") format("woff"),
				url("' . $font_path . 'star.ttf") format("truetype"),
				url("' . $font_path . 'star.svg#star") format("svg");
			font-weight: normal;
			font-style: normal;
		}';

	wp_add_inline_style( 'paper-hue-woocommerce-style', $inline_font );
}
add_action( 'wp_enqueue_scripts', 'paper_hue_woocommerce_scripts' );

add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Add 'woocommerce-active' class to the body tag.
 *
 * @param array $classes CSS classes applied to the body tag.
 * @return array
 */
function paper_hue_woocommerce_active_body_class( $classes ) {
	$classes[] = 'woocommerce-active';
	return $classes;
}
add_filter( 'body_class', 'paper_hue_woocommerce_active_body_class' );

/**
 * Products per page.
 *
 * @return int
 */
function paper_hue_woocommerce_products_per_page() {
	return 12;
}
add_filter( 'loop_shop_per_page', 'paper_hue_woocommerce_products_per_page' );

/**
 * Product columns.
 *
 * @return int
 */
function paper_hue_woocommerce_loop_columns() {
	return 3;
}
add_filter( 'loop_shop_columns', 'paper_hue_woocommerce_loop_columns' );

/**
 * Related Products Args.
 *
 * @param array $args related products args.
 * @return array
 */
function paper_hue_woocommerce_related_products_args( $args ) {
	$defaults = array(
		'posts_per_page' => 3,
		'columns'        => 3,
	);

	$args = wp_parse_args( $defaults, $args );
	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'paper_hue_woocommerce_related_products_args' );

/**
 * Wrapper before shop content.
 *
 * @return void
 */
function paper_hue_woocommerce_wrapper_before() {
	?>
	<main id="primary" class="site-main">
	<?php
}
add_action( 'woocommerce_before_main_content', 'paper_hue_woocommerce_wrapper_before' );

/**
 * Wrapper after shop content.
 *
 * @return void
 */
function paper_hue_woocommerce_wrapper_after() {
	?>
	</main>
	<?php
}
add_action( 'woocommerce_after_main_content', 'paper_hue_woocommerce_wrapper_after' );

/**
 * Cart link fragment update.
 *
 * @param array $fragments Fragments to refresh via AJAX.
 * @return array
 */
function paper_hue_woocommerce_cart_link_fragment( $fragments ) {
	ob_start();
	paper_hue_woocommerce_cart_link();
	$fragments['a.cart-contents'] = ob_get_clean();
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'paper_hue_woocommerce_cart_link_fragment' );

/**
 * Cart link.
 *
 * @return void
 */
function paper_hue_woocommerce_cart_link() {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return;
	}
	?>
	<a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'paper-hue' ); ?>">
		<?php
		$item_count_text = sprintf(
			/* translators: %d: number of items in the cart. */
			_n( '%d item', '%d items', WC()->cart->get_cart_contents_count(), 'paper-hue' ),
			WC()->cart->get_cart_contents_count()
		);
		?>
		<span class="amount"><?php echo wp_kses_post( WC()->cart->get_cart_subtotal() ); ?></span>
		<span class="count"><?php echo esc_html( $item_count_text ); ?></span>
	</a>
	<?php
}

/**
 * Display Header Cart.
 *
 * @return void
 */
function paper_hue_woocommerce_header_cart() {
	if ( is_cart() ) {
		$class = 'current-menu-item';
	} else {
		$class = '';
	}
	?>
	<ul id="site-header-cart" class="site-header-cart">
		<li class="<?php echo esc_attr( $class ); ?>">
			<?php paper_hue_woocommerce_cart_link(); ?>
		</li>
		<?php if ( ! is_cart() ) : ?>
			<li>
				<div class="widget woocommerce widget_shopping_cart">
					<div class="widget_shopping_cart_content"></div>
				</div>
			</li>
		<?php endif; ?>
	</ul>
	<?php
}
