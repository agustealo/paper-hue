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
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)-in-3.0.0
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

/**
 * Disable the default WooCommerce stylesheet.
 */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Add 'woocommerce-active' class to the body tag.
 *
 * @param  array $classes CSS classes applied to the body tag.
 * @return array $classes modified to include 'woocommerce-active' class.
 */
function paper_hue_woocommerce_active_body_class( $classes ) {
	$classes[] = 'woocommerce-active';

	return $classes;
}
add_filter( 'body_class', 'paper_hue_woocommerce_active_body_class' );

/**
 * Products per page.
 *
 * @return integer number of products.
 */
function paper_hue_woocommerce_products_per_page() {
	return 12;
}
add_filter( 'loop_shop_per_page', 'paper_hue_woocommerce_products_per_page' );

/**
 * Product gallery thumbnail columns.
 *
 * @return integer number of columns.
 */
function paper_hue_woocommerce_thumbnail_columns() {
	return 4;
}
add_filter( 'woocommerce_product_thumbnails_columns', 'paper_hue_woocommerce_thumbnail_columns' );

/**
 * Default loop columns on product archives.
 *
 * @return integer products per row.
 */
function paper_hue_woocommerce_loop_columns() {
	return 3;
}
add_filter( 'loop_shop_columns', 'paper_hue_woocommerce_loop_columns' );

/**
 * Related Products Args.
 *
 * @param array $args related products args.
 * @return array $args related products args.
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

if ( ! function_exists( 'paper_hue_woocommerce_product_columns_wrapper' ) ) {
	function paper_hue_woocommerce_product_columns_wrapper() {
		$columns = paper_hue_woocommerce_loop_columns();
		echo '<div class="columns-' . absint( $columns ) . '">';
	}
}
add_action( 'woocommerce_before_shop_loop', 'paper_hue_woocommerce_product_columns_wrapper', 40 );

if ( ! function_exists( 'paper_hue_woocommerce_product_columns_wrapper_close' ) ) {
	function paper_hue_woocommerce_product_columns_wrapper_close() {
		echo '</div>';
	}
}
add_action( 'woocommerce_after_shop_loop', 'paper_hue_woocommerce_product_columns_wrapper_close', 40 );

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

if ( ! function_exists( 'paper_hue_woocommerce_wrapper_before' ) ) {
	function paper_hue_woocommerce_wrapper_before() {
		?>
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">
			<?php
	}
}
add_action( 'woocommerce_before_main_content', 'paper_hue_woocommerce_wrapper_before' );

if ( ! function_exists( 'paper_hue_woocommerce_wrapper_after' ) ) {
	function paper_hue_woocommerce_wrapper_after() {
		?>
			</main><!-- #main -->
		</div><!-- #primary -->
		<?php
	}
}
add_action( 'woocommerce_after_main_content', 'paper_hue_woocommerce_wrapper_after' );

if ( ! function_exists( 'paper_hue_woocommerce_cart_link_fragment' ) ) {
	function paper_hue_woocommerce_cart_link_fragment( $fragments ) {
		ob_start();
		paper_hue_woocommerce_cart_link();
		$fragments['a.cart-contents'] = ob_get_clean();

		return $fragments;
	}
}
add_filter( 'woocommerce_add_to_cart_fragments', 'paper_hue_woocommerce_cart_link_fragment' );

if ( ! function_exists( 'paper_hue_woocommerce_cart_link' ) ) {
	function paper_hue_woocommerce_cart_link() {
		?>
		<a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'paper-hue' ); ?>">
			<?php
			$item_count_text = sprintf(
				/* translators: number of items in the mini cart. */
				_n( '%d item', '%d items', WC()->cart->get_cart_contents_count(), 'paper-hue' ),
				WC()->cart->get_cart_contents_count()
			);
			?>
			<span class="amount"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span> <span class="count"><?php echo esc_html( $item_count_text ); ?></span>
		</a>
		<?php
	}
}

if ( ! function_exists( 'paper_hue_woocommerce_header_cart' ) ) {
	function paper_hue_woocommerce_header_cart() {
		$class = is_cart() ? 'current-menu-item' : '';
		?>
		<ul id="site-header-cart" class="site-header-cart">
			<li class="<?php echo esc_attr( $class ); ?>">
				<?php paper_hue_woocommerce_cart_link(); ?>
			</li>
			<li>
				<?php
				$instance = array(
					'title' => '',
				);
				the_widget( 'WC_Widget_Cart', $instance );
				?>
			</li>
		</ul>
		<?php
	}
}
