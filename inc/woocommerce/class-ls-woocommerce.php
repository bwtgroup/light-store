<?php
/**
 * ls WooCommerce Class
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'LS_Woocommerce' ) ) {

	/**
	 * The Light Store WooCommerce Integration class
	 */
	class LS_Woocommerce {

		/**
		 * Setup class.
		 *
		 */
		public function __construct() {

			add_filter( 'woocommerce_product_single_add_to_cart_text', [
				$this,
				'add_to_cart_single_product_button_text'
			] );
			add_filter( 'woocommerce_loop_add_to_cart_link', [ $this, 'add_to_cart_link_loop' ] );
			add_filter( 'loop_shop_columns', [ $this, 'loop_columns' ] );
			add_filter( 'loop_shop_per_page', [ $this, 'products_per_page' ] );
			add_filter( 'body_class', [ $this, 'woocommerce_body_class' ] );
			add_filter( 'post_class', [ $this, 'post_classes' ] );
			add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
			add_filter( 'woocommerce_output_related_products_args', [ $this, 'related_products_args' ] );

			// Integrations.
			add_action( 'wp_enqueue_scripts', [ $this, 'woocommerce_scripts' ], 10 );
			add_action( 'wp_enqueue_scripts', [ $this, 'woocommerce_integrations_scripts' ], 99 );
			add_action( 'wp_head', [ $this, 'woocommerce_integrations_scripts' ] );

			lsWooCommerceHooks();
		}

		/**
		 * Change add to cart button text on sigle page
		 *
		 * @return integer products per row
		 */
		public function add_to_cart_single_product_button_text() {

			return __( 'Add to cart', 'light-store' );
		}

		/**
		 * Change add to cart button text on arhive page
		 *
		 * @return integer products per row
		 */
		public function add_to_cart_link_loop( $button ) {

			global $product, $class;

			if ( $product->product_type == 'simple' && $product->is_in_stock() ) {
				$link_text = '<span class="add_to_cart_button_text">' . __( 'Add to cart', 'light-store' ) . '</span><svg x="0px" y="0px" width="32px" height="32px" viewBox="0 0 32 32">
								<path stroke-dasharray="19.79 19.79" stroke-dashoffset="19.79" fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" d="M9,17l3.9,3.9c0.1,0.1,0.2,0.1,0.3,0L23,11"></path>
							</svg>';
			} else {

				$link_text = $product->add_to_cart_text();
			}
			$class = implode( ' ', array_filter( [
				'button',
				'product_type_' . $product->product_type,
				$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
				$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : ''
			] ) );

			$link = sprintf( '<a rel="nofollow" href="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="%s">%s</a>', esc_url( $product->add_to_cart_url() ), esc_attr( isset( $quantity ) ? $quantity : 1 ), esc_attr( $product->id ), esc_attr( $product->get_sku() ), esc_attr( $class ), $link_text );

			return $link;
		}

		/**
		 * Default loop columns on product archives
		 *
		 * @return integer products per row
		 */
		public function loop_columns() {
			return apply_filters( 'ls_loop_columns', 4 ); // 4 products per row
		}

		/**
		 * Add 'woocommerce-active' class to the body tag
		 *
		 * @param  array $classes css classes applied to the body tag.
		 *
		 * @return array $classes modified to include 'woocommerce-active' class
		 */
		public function woocommerce_body_class( $classes ) {
			if ( is_woocommerce_activated() ) {
				$classes[] = 'woocommerce-active';
			}

			return $classes;
		}

		/**
		 * WooCommerce specific scripts & stylesheets
		 *
		 */

		public function woocommerce_scripts() {
			$theme_data    = wp_get_theme();
			$theme_version = $theme_data->Version;

			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			/**
			 * Styles
			 */
			wp_enqueue_style( 'ls-woocommerce-style', get_template_directory_uri() . '/assets/css/extensions/woocommerce/woocommerce.css', [ ], $theme_version );

			/**
			 * Scripts
			 */
			wp_enqueue_script( 'ls-product', get_template_directory_uri() . '/assets/js/frontend/product' . $suffix . '.js', [ 'jquery', 'ls-validation' ], $theme_version, true );

			wp_enqueue_script( 'ls-add-to-cart', get_template_directory_uri() . '/assets/js/frontend/add-to-cart' . $suffix . '.js', [ 'jquery' ], $theme_version, true );

			wp_enqueue_script( 'ls-mini-cart', get_template_directory_uri() . '/assets/js/frontend/mini-cart' . $suffix . '.js', [ 'ls-main-script' ], $theme_version, true );

			wp_enqueue_script( 'ls-product-swatches', get_template_directory_uri() . '/assets/js/frontend/product-swatches' . $suffix . '.js', [
				'jquery',
				'photo-swipe-ui'
			], $theme_version, true );

			if ( is_cart() ) {

				wp_enqueue_script( 'ls-cart-page', get_template_directory_uri() . '/assets/js/frontend/cart' . $suffix . '.js', [ 'jquery' ], $theme_version, true );
			}

			if ( is_product() ) {

				/**
				 * Style and scripts for photo swipe and slick slider
				 */

				wp_enqueue_style( 'photo-swipe-css', get_template_directory_uri() . '/assets/css/extensions/photo-swipe/photoswipe.css', [ ],  '4.1.0' );
				wp_enqueue_style( 'slick-css', get_template_directory_uri() . '/assets/css/extensions/slick/slick.css', [ ], '1.5.7' );

				wp_enqueue_script( 'photo-swipe', get_template_directory_uri() . '/assets/js/photo-swipe/photoswipe' . $suffix . '.js', [ 'jquery',  'prettyPhoto-init' ],  '4.1.0', true );
				wp_enqueue_script( 'photo-swipe-ui', get_template_directory_uri() . '/assets/js/photo-swipe/photoswipe-ui-default' . $suffix . '.js', [ 'photo-swipe' ],  '4.1.0', true );
				wp_enqueue_script( 'slick', get_template_directory_uri() . '/assets/js/slick/slick' . $suffix . '.js', [ 'jquery' ], '1.5.7', true );
				wp_enqueue_script( 'elevatezoom', get_template_directory_uri() . '/assets/js/elevatezoom' . $suffix . '.js', [ 'jquery' ], '3.0.8', true );
			}

			if ( is_checkout() && apply_filters( 'ls_sticky_order_review', true ) ) {

				wp_enqueue_script( 'ls-sticky-payment', get_template_directory_uri() . '/assets/js/frontend/checkout' . $suffix . '.js', [ 'jquery' ], $theme_version, true );
			}
		}

		/**
		 * Related Products Args
		 *
		 * @param  array $args related products args.
		 *
		 * @return  array $args related products args
		 */
		public function related_products_args( $args ) {
			$args = apply_filters( 'ls_related_products_args', [
				'posts_per_page' => 4,
				'columns'        => 4,
			] );

			return $args;
		}

		/**
		 * Adds custom classes to the array of post classes.
		 *
		 * @param array $classes Classes for the body element.
		 *
		 * @return array
		 */
		public function post_classes( $classes ) {
			// Adds a class number of template product page
			if ( is_product() ) {
				$product_template_type = ls_get_theme_mod( 'product_page_layout_type');

				$classes[] = 'product-template-' . $product_template_type;
			}

			return $classes;
		}

		/**
		 * Product gallery thumnail columns
		 *
		 * @return integer number of columns
		 */
		public function thumbnail_columns() {
			return intval( apply_filters( 'ls_product_thumbnail_columns', 4 ) );//4
		}

		/**
		 * Products per page
		 *
		 * @return integer number of products
		 */
		public function products_per_page() {
			add_filter( 'loop_shop_per_page', create_function( '$cols', 'return 12;' ), 20 );
		}

		/**
		 * Query WooCommerce Extension Activation.
		 *
		 * @param string $extension Extension class name.
		 *
		 * @return boolean
		 */
		public function is_woocommerce_extension_activated( $extension = 'WC_Bookings' ) {
			return class_exists( $extension ) ? true : false;
		}

		/**
		 * Integration Styles & Scripts
		 *
		 * @return void
		 */
		public function woocommerce_integrations_scripts() {
			$theme_data    = wp_get_theme();
			$theme_version = $theme_data->Version;
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			/**
			 * WooCommerce YITH Product Slider
			 */
			if ( $this->is_woocommerce_extension_activated( 'YITH_WooCommerce_Product_Slider' ) ) {

				wp_enqueue_style( 'ls-yith-product-slider', get_template_directory_uri() . '/assets/css/extensions/yith-product-slider.css', [ 'ls-woocommerce-style' ], $theme_version );
				wp_enqueue_script( 'ls-yith-product-slider', get_template_directory_uri() . '/assets/js/extensions/yith_product_slider' . $suffix . '.js', [ 'ls-main-script' ], $theme_version, true );
			}

			/**
			 * WooCommerce YITH Wishlist
			 */
			if ( $this->is_woocommerce_extension_activated( 'YITH_WCWL' ) ) {

				wp_enqueue_style( 'ls-yith-wishlist', get_template_directory_uri() . '/assets/css/extensions/yith-wishlist.css', [ 'ls-woocommerce-style' ], $theme_version );
				wp_enqueue_script( 'ls-yith-wishlist', get_template_directory_uri() . '/assets/js/extensions/yith-wishlist' . $suffix . '.js', [ 'ls-main-script' ], $theme_version, true );

				add_action( 'ls_right_navigation', 'ls_wishlist_info', 6 );
			}

			/**
			 * WooCommerce YITH Compare
			 */
			if ( $this->is_woocommerce_extension_activated( 'YITH_Woocompare' ) ) {

				wp_enqueue_style( 'ls-yith-compare', get_template_directory_uri() . '/assets/css/extensions/yith-compare.css', [ ], $theme_version );
				wp_enqueue_script( 'ls-yith-compare', get_template_directory_uri() . '/assets/js/extensions/yith-compare' . $suffix . '.js', [ 'ls-main-script' ], $theme_version, true );
				add_action( 'wp_print_styles', 'add_yith_compare_style', 101 );

				if ( ! function_exists( 'add_yith_compare_style' ) ) {
					function add_yith_compare_style() {
						global $wp_styles;
						global $theme_version;
						if ( empty( $wp_styles->queue ) ) {
							wp_enqueue_style( 'ls-style', get_template_directory_uri() . '/style.css', [ ], $theme_version );
							wp_enqueue_style( 'ls-woocommerce-style', get_template_directory_uri() . '/assets/css/extensions/woocommerce/woocommerce.css', [ ], $theme_version );
							wp_enqueue_style( 'ls-main-style', get_template_directory_uri() . '/assets/css/style.css', [ ], $theme_version );
							wp_enqueue_style( 'ls-yith-compare', get_template_directory_uri() . '/assets/css/yith-compare.css', [ ], $theme_version );
						}
					}
				}
			}
			/**
			 * WooCommerce YITH Quick View
			 */
			if ( $this->is_woocommerce_extension_activated( 'YITH_WCQV' ) ) {
				wp_enqueue_style( 'ls-yith-quick-view', get_template_directory_uri() . '/assets/css/extensions/yith-quick-view.css', [ 'ls-woocommerce-style' ], $theme_version );
				wp_enqueue_script( 'ls-yith-quick-view', get_template_directory_uri() . '/assets/js/extensions/yith-quick-view' . $suffix . '.js', [ 'ls-product' ], $theme_version, true );

				if ( ! is_product() ) {

					/**
					 * Style and scripts for photo swipe and slick slider
					 */
					wp_enqueue_style( 'photo-swipe-css', get_template_directory_uri() . '/assets/css/extensions/photo-swipe/photoswipe.css', [ ],  '4.1.0' );
					wp_enqueue_style( 'slick-css', get_template_directory_uri() . '/assets/css/extensions/slick/slick.css', [ ], '1.5.7' );

					wp_enqueue_script( 'photo-swipe', get_template_directory_uri() . '/assets/js/photo-swipe/photoswipe' . $suffix . '.js', [ 'jquery' ],  '4.1.0', true );
					wp_enqueue_script( 'photo-swipe-ui', get_template_directory_uri() . '/assets/js/photo-swipe/photoswipe-ui-default' . $suffix . '.js', [ 'jquery' ],  '4.1.0', true );
					wp_enqueue_script( 'slick', get_template_directory_uri() . '/assets/js/slick/slick' . $suffix . '.js', [ 'jquery' ], '1.5.7', true );

					wp_enqueue_script( 'elevatezoom', get_template_directory_uri() . '/assets/js/elevatezoom' . $suffix . '.js', [ 'jquery' ], '3.0.8', true );
				}
			}
			/**
			 * Checkout Add Ons
			 */
			if ( $this->is_woocommerce_extension_activated( 'WC_Checkout_Add_Ons' ) ) {
				add_filter( 'ls_sticky_order_review', '__return_false' );
			}
		}

	}
}
