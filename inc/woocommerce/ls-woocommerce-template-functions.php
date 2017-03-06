<?php
/**
 * WooCommerce Template Functions.
 *
 */

if ( ! function_exists( 'ls_before_content' ) ) {
	/**
	 * Before Content
	 * Wraps all WooCommerce content in wrappers which match the theme markup
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	function ls_before_content() {
		?>
		<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php
	}
}

if ( ! function_exists( 'ls_after_content' ) ) {
	/**
	 * After Content
	 * Closes the wrapping divs
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	function ls_after_content() {
		?>
		</main><!-- #main -->
		</div><!-- #primary -->

		<?php do_action( 'ls_sidebar' );
	}
}

if ( ! function_exists( 'ls_product_bottom_open' ) ) {
	/**
	 * Open div product bottom in loop products
	 *
	 */
	function ls_product_bottom_open() {
		?>
		<div class="product-bottom">
		<?php
	}
}

if ( ! function_exists( 'ls_product_bottom_close' ) ) {
	/**
	 * Open div product bottom in loop products
	 *
	 */
	function ls_product_bottom_close() {
		?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'ls_product_swap_open' ) ) {
	/**
	 * Before product swap
	 * Wraps all add to cart and price product on archive
	 *
	 */
	function ls_product_swap_open() {
		?>
		<div class="product-wrap-swap">
		<div class="product-swap">
		<?php
	}
}

if ( ! function_exists( 'ls_product_swap_close' ) ) {
	/**
	 * Close product swap
	 *
	 */
	function ls_product_swap_close() {
		?>
		</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'ls_cart_link_fragment' ) ) {
	/**
	 * Cart Fragments
	 * Ensure cart contents update when products are added to the cart via AJAX
	 *
	 * @param  array $fragments Fragments to refresh via AJAX.
	 *
	 * @return array            Fragments to refresh via AJAX
	 */
	function ls_cart_link_fragment( $fragments ) {
		global $woocommerce;

		ob_start();
		ls_cart_link();
		$fragments['a.cart-contents'] = ob_get_clean();

		ob_start();
		ls_handheld_footer_bar_cart_link();
		$fragments['a.footer-cart-contents'] = ob_get_clean();

		return $fragments;
	}
}

if ( ! function_exists( 'ls_cart_link' ) ) {
	/**
	 * Cart Link
	 * Displayed a link to the cart including the number of items present and the cart total
	 *
	 * @return void
	 * @since  1.0.0
	 */
	function ls_cart_link() {
		?>
		<a class="cart-contents" href="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'light-store' ); ?>">
			<span class="amount"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span>
			<span class="count"><?php echo wp_kses_data( sprintf( _n( '%d item', '%d items', WC()->cart->get_cart_contents_count(), 'light-store' ), WC()->cart->get_cart_contents_count() ) ); ?></span>
		</a>
		<?php
	}
}

if ( ! function_exists( 'ls_product_search' ) ) {
	/**
	 * Display Product Search
	 *
	 * @since  1.0.0
	 * @uses   is_woocommerce_activated() check if WooCommerce is activated
	 * @return void
	 */
	function ls_product_search() {
		if ( is_woocommerce_activated() ) { ?>
			<div class="site-search">
				<?php the_widget( 'WC_Widget_Product_Search', 'title=' ); ?>
			</div>
			<?php
		}
	}
}

if ( ! function_exists( 'ls_add_to_cart_fragments' ) ) {
	/**
	 * Add fragments to response ajax add to cart
	 *
	 */
	function ls_add_to_cart_fragments( $fragments ) {

		$total_count                   = WC()->cart->get_cart_contents_count();
		$fragments['span.total-count'] = '<span class="total-count">' . $total_count . '</span>';

		return $fragments;
	}
}

if ( ! function_exists( 'ls_header_cart' ) ) {
	/**
	 * Display Header Cart
	 *
	 * @since  1.0.0
	 * @uses   is_woocommerce_activated() check if WooCommerce is activated
	 * @return void
	 */
	function ls_header_cart() {
		if ( is_woocommerce_activated() ) {
			if ( is_cart() ) {
				$class = 'current-menu-item';
			} else {
				$class = '';
			}
			?>
			<ul id="site-header-cart" class="site-header-cart menu">
				<li class="<?php echo esc_attr( $class ); ?>">
					<?php ls_cart_link(); ?>
				</li>
				<li>
					<?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
				</li>
			</ul>
			<?php
		}
	}
}

if ( ! function_exists( 'ls_upsell_display' ) ) {
	/**
	 * Upsells
	 * Replace the default upsell function with our own which displays the correct number product columns
	 *
	 * @since   1.0.0
	 * @return  void
	 * @uses    woocommerce_upsell_display()
	 */
	function ls_upsell_display() {
		woocommerce_upsell_display( - 1, 3 );
	}
}

if ( ! function_exists( 'ls_sorting_wrapper' ) ) {
	/**
	 * Sorting wrapper
	 *
	 * @since   1.4.3
	 * @return  void
	 */
	function ls_sorting_wrapper() {
		echo '<div class="ls-sorting">';
	}
}

if ( ! function_exists( 'ls_sorting_wrapper_close' ) ) {
	/**
	 * Sorting wrapper close
	 *
	 * @since   1.4.3
	 * @return  void
	 */
	function ls_sorting_wrapper_close() {
		echo '</div>';
	}
}

if ( ! function_exists( 'ls_template_loop_product_thumbnails' ) ) {

	function ls_template_loop_product_thumbnails() {

		global $product;
		$img = woocommerce_get_product_thumbnail();
		echo '<div class="product_thumbnails">' . $img;

		$attachment_ids = $product->get_gallery_attachment_ids();

		if ( isset( $attachment_ids[0] ) ) {

			$image_size = apply_filters( 'single_product_archive_thumbnail_size', 'shop_catalog' );
			$props      = wc_get_product_attachment_props( $attachment_ids[0], $product );
			$image      = wp_get_attachment_image( $attachment_ids[0], $image_size, false, [
				'title' => $props['title'],
				'alt'   => $props['alt'],
			] );

			echo '<div class="hover-image">' . $image . '</div>';
		}

		if ( $product->is_type( 'variable' ) ) {

			echo '<div class="variation-image">' . $img . '</div>';
		}

		echo '<div class="product-thumbnail-buttons">';

		do_action( 'ls_product_thumbnail_buttons' );

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ls_template_loop_product_title' ) ) {

	/**
	 * Show the product title in the product loop. By default this is an H3.
	 */
	function ls_template_loop_product_title() {
		echo '<h3><a href="' . get_the_permalink() . '" class="product-title">' . get_the_title() . '</a></h3>';
	}
}

if ( ! function_exists( 'ls_product_swatch_color' ) ) {
	function ls_product_swatch_color() {

		global $product;

		if ( $product->is_type( 'variable' ) ) {

			$colors   = [ ];
			$swatches = '';

			foreach ( $product->get_available_variations() as $variation ) {
				if ( $variation['variation_is_active'] && isset( $variation['attributes']['attribute_pa_color'] ) && ! empty( $variation['attributes']['attribute_pa_color'] ) ) {
					$color = $variation['attributes']['attribute_pa_color'];

					if ( ! in_array( $color, $colors ) ) {
						$colors[] = $color;

						$class = 'product-swatch black-title';
						$class .= ' color-' . $color;
						$content = '';

						if ( $variation['image_src'] ) {
							$class .= ' has-image';
						}

						$content .= '<div class="' . $class . '" ';
						$content .= 'data-image-src="' . $variation['image_src'] . '" ';
						$content .= 'data-image-srcset="' . $variation['image_srcset'] . '" ';
						$content .= 'data-image-sizes="' . $variation['image_sizes'] . '" ';
						$content .= '><div class="black-title-lable top">' . $color . '</div>';
						$content .= '</div>';

						$swatches .= apply_filters( 'woocommerce_product_loop_swatches', $content, $product );
					}
				}
			}

			if ( $swatches ) {
				echo '<div class="product-swatches">' . $swatches . '</div>';
			}
		}
	}
}

if ( ! function_exists( 'ls_add_quick_view_button' ) ) {

	/**
	 * Add quick view button in wc product loop
	 *
	 */
	function ls_add_quick_view_button() {

		global $product;

		$label = esc_html( get_option( 'yith-wcqv-button-label' ) );

		echo '<a href="#" class="yith-wcqv-button black-title" data-product_id="' . $product->id . '"><div class="black-title-lable left">' . $label . '</div></a>';
	}
}

if ( ! function_exists( 'ls_add_to_wishlist_params' ) ) {

	/**
	 * Add wishlist_url for wishlist
	 *
	 */
	function ls_add_to_wishlist_params( $additional_params ) {

		$additional_params['wishlist_url'] = esc_url( get_site_url( null, '/wishlist/view', null ) );

		return $additional_params;
	}
}

if ( ! function_exists( 'ls_add_to_wishlist_button_label' ) ) {

	/**
	 * Change label in wishlist
	 *
	 */
	function ls_add_to_wishlist_button_label( $label_option ) {
		return '<div class="black-title">' . $label_option . '<div class="black-title-lable left">' . $label_option . '</div></div>';
	}
}

if ( ! function_exists( 'ls_add_wishlist_button' ) ) {

	/**
	 * Add wishlist button in wc product loop
	 *
	 */
	function ls_add_wishlist_button() {
		echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
	}
}

if ( ! function_exists( 'ls_add_compare_button' ) ) {

	/**
	 * Add compare button in wc product loop
	 *
	 */
	function ls_add_compare_button() {

		global $product;
		$YITH_Woocompare = new YITH_Woocompare_Frontend();
		$product_id      = isset( $product->id ) ? $product->id : 0;

		// return if product doesn't exist
		if ( empty( $product_id ) || apply_filters( 'yith_woocompare_remove_compare_link_by_cat', false, $product_id ) ) {
			return;
		}

		$link = esc_url( $YITH_Woocompare->add_product_url( $product_id ) );

		$label = get_option( 'yith_woocompare_button_text', __( 'Compare', 'light-store' ) );
		do_action( 'wpml_register_single_string', 'Plugins', 'plugin_yit_compare_button_text', $label );
		$label = apply_filters( 'wpml_translate_single_string', $label, 'Plugins', 'plugin_yit_compare_button_text' );

		echo '<a href="' . $link . '" class="yith-compare-button compare black-title" data-product_id="' . $product->id . '" rel="nofollow"><div class="black-title-lable left">' . $label . '</div></a>';
	}
}

if ( ! function_exists( 'ls_woof' ) ) {
	/**
	 * Wrap for woof sorting
	 *
	 * @return  void
	 */
	function ls_woof() {
		echo '<div class="woof-inline">' . do_shortcode( '[woof]' ) . '</div>';
	}
}

if ( ! function_exists( 'ls_shop_messages' ) ) {
	/**
	 * ls shop messages
	 *
	 * @since   1.4.4
	 * @uses    ls_do_shortcode
	 */
	function ls_shop_messages() {
		if ( ! is_checkout() ) {
			echo wp_kses_post( ls_do_shortcode( 'woocommerce_messages' ) );
		}
	}
}

if ( ! function_exists( 'ls_woocommerce_pagination' ) ) {
	/**
	 * LS WooCommerce Pagination
	 * WooCommerce disables the product pagination inside the woocommerce_product_subcategories() function
	 * but since LS adds pagination before that function is excuted we need a separate function to
	 * determine whether or not to display the pagination.
	 *
	 * @since 1.4.4
	 */
	function ls_woocommerce_pagination() {
		if ( woocommerce_products_will_display() ) {
			woocommerce_pagination();
		}
	}
}

if ( ! function_exists( 'ls_promoted_products' ) ) {
	/**
	 * Featured and On-Sale Products
	 * Check for featured products then on-sale products and use the appropiate shortcode.
	 * If neither exist, it can fallback to show recently added products.
	 *
	 * @since  1.5.1
	 *
	 * @param integer $per_page        total products to display.
	 * @param integer $columns         columns to arrange products in to.
	 * @param boolean $recent_fallback Should the function display recent products as a fallback when there are no featured or on-sale products?.
	 *
	 * @uses   is_woocommerce_activated()
	 * @uses   wc_get_featured_product_ids()
	 * @uses   wc_get_product_ids_on_sale()
	 * @uses   ls_do_shortcode()
	 * @return void
	 */
	function ls_promoted_products( $per_page = '2', $columns = '2', $recent_fallback = true ) {
		if ( is_woocommerce_activated() ) {

			if ( wc_get_featured_product_ids() ) {

				echo '<h2>' . esc_html__( 'Featured Products', 'light-store' ) . '</h2>';

				echo ls_do_shortcode( 'featured_products', [
					'per_page' => $per_page,
					'columns'  => $columns,
				] );
			} elseif ( wc_get_product_ids_on_sale() ) {

				echo '<h2>' . esc_html__( 'On Sale Now', 'light-store' ) . '</h2>';

				echo ls_do_shortcode( 'sale_products', [
					'per_page' => $per_page,
					'columns'  => $columns,
				] );
			} elseif ( $recent_fallback ) {

				echo '<h2>' . esc_html__( 'New In Store', 'light-store' ) . '</h2>';

				echo ls_do_shortcode( 'recent_products', [
					'per_page' => $per_page,
					'columns'  => $columns,
				] );
			}
		}
	}
}

if ( ! function_exists( 'ls_handheld_footer_bar' ) ) {
	/**
	 * Display a menu intended for use on handheld devices
	 *
	 * @since 2.0.0
	 */
	function ls_handheld_footer_bar() {
		$links = [
			'my-account' => [
				'priority' => 10,
				'callback' => 'ls_handheld_footer_bar_account_link',
			],
			'search'     => [
				'priority' => 20,
				'callback' => 'ls_handheld_footer_bar_search',
			],
			'cart'       => [
				'priority' => 30,
				'callback' => 'ls_handheld_footer_bar_cart_link',
			],
		];

		if ( wc_get_page_id( 'myaccount' ) === - 1 ) {
			unset( $links['my-account'] );
		}

		if ( wc_get_page_id( 'cart' ) === - 1 ) {
			unset( $links['cart'] );
		}

		$links = apply_filters( 'ls_handheld_footer_bar_links', $links );
		?>
		<div class="ls-handheld-footer-bar">
			<ul class="columns-<?php echo count( $links ); ?>">
				<?php foreach ( $links as $key => $link ) : ?>
					<li class="<?php echo esc_attr( $key ); ?>">
						<?php
						if ( $link['callback'] ) {
							call_user_func( $link['callback'], $key, $link );
						}
						?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php
	}
}

if ( ! function_exists( 'ls_handheld_footer_bar_search' ) ) {
	/**
	 * The search callback function for the handheld footer bar
	 *
	 * @since 2.0.0
	 */
	function ls_handheld_footer_bar_search() {
		echo '<a href="">' . esc_attr__( 'Search', 'light-store' ) . '</a>';
		ls_product_search();
	}
}

if ( ! function_exists( 'ls_handheld_footer_bar_cart_link' ) ) {
	/**
	 * The cart callback function for the handheld footer bar
	 *
	 * @since 2.0.0
	 */
	function ls_handheld_footer_bar_cart_link() {
		?>
		<a class="footer-cart-contents" href="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'light-store' ); ?>">
			<span class="count"><?php echo wp_kses_data( WC()->cart->get_cart_contents_count() ); ?></span>
		</a>
		<?php
	}
}

if ( ! function_exists( 'ls_handheld_footer_bar_account_link' ) ) {
	/**
	 * The account callback function for the handheld footer bar
	 *
	 * @since 2.0.0
	 */
	function ls_handheld_footer_bar_account_link() {
		echo '<a href="' . esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ) . '">' . esc_attr__( 'My Account', 'light-store' ) . '</a>';
	}
}

if ( ! function_exists( 'ls_woocommerce_init_structured_data' ) ) {
	/**
	 *
	 * Generates product category structured data.
	 *
	 * Hooked into `woocommerce_before_shop_loop_item` action hook.
	 */
	function ls_woocommerce_init_structured_data() {
		if ( ! is_product_category() ) {
			return;
		}

		global $product;

		$json['@type']       = 'Product';
		$json['@id']         = 'product-' . get_the_ID();
		$json['name']        = get_the_title();
		$json['image']       = esc_url( wp_get_attachment_url( $product->get_image_id() ) );
		$json['description'] = get_the_excerpt();
		$json['url']         = esc_url( get_the_permalink() );
		$json['sku']         = $product->get_sku();

		if ( $product->get_rating_count() ) {
			$json['aggregateRating'] = [
				'@type'       => 'AggregateRating',
				'ratingValue' => $product->get_average_rating(),
				'ratingCount' => $product->get_rating_count(),
				'reviewCount' => $product->get_review_count(),
			];
		}

		$json['offers'] = [
			'@type'         => 'Offer',
			'priceCurrency' => get_woocommerce_currency(),
			'price'         => $product->get_price(),
			'itemCondition' => 'http://schema.org/NewCondition',
			'availability'  => 'http://schema.org/' . $stock = ( $product->is_in_stock() ? 'InStock' : 'OutOfStock' ),
			'seller'        => [
				'@type' => 'Organization',
				'name'  => get_bloginfo( 'name' ),
			],
		];

		if ( ! isset( $json ) ) {
			return;
		}

		LS::set_structured_data( apply_filters( 'ls_woocommerce_structured_data', $json ) );
	}
}

if ( ! function_exists( 'ls_template_single_meta' ) ) {

	/**
	 * Output the product meta.
	 *
	 */
	function ls_template_single_meta() {

		global $product;
		?>
		<div class="product_meta">

			<?php do_action( 'woocommerce_product_meta_start' ); ?>

			<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>

				<p class="text-grey text-italic"><?php _e( 'Article number: ', 'light-store' );
					echo ( $sku = $product->get_sku() ) ? $sku : __( 'N/A', 'light-store' ); ?></p>

			<?php endif; ?>

			<?php do_action( 'woocommerce_product_meta_end' ); ?>

		</div>
		<?php
	}
}

if ( ! function_exists( 'ls_show_product_images_swipe_slick' ) ) {

	function ls_show_product_images_swipe_slick() {

		global $post, $product;
		?>

		<div id="swipe-slick-gallery" class="images">

			<?php do_action( 'swipe_slick_gallery_before_main' ); ?>

			<?php

			$zoomed_image_size = [ 1920, 1080 ];

			if ( has_post_thumbnail() ) {

				$image_title = esc_attr( get_the_title( get_post_thumbnail_id() ) );
				$image_link  = esc_url( wp_get_attachment_url( get_post_thumbnail_id() ) );
				$hq          = wp_get_attachment_image_src( get_post_thumbnail_id(), apply_filters( 'swipe_slick_gallery_zoomed_image_size', $zoomed_image_size ) );
				$image       = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), [
					'title'   => '',
					'data-hq' => $hq[0],
					'data-w'  => $hq[1],
					'data-h'  => $hq[2],
				] );

				echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="single-product-main-image"><a href="%s"  class="woocommerce-main-image zoom" title="%s" >%s</a></div>', $image_link, $image_title, $image ), $post->ID );
			} else {
				echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), __( 'Placeholder', 'light-store' ) ), $post->ID );
			}

			$attachment_ids = $product->get_gallery_attachment_ids();

			if ( $attachment_ids ) { ?>

				<div class="thumbnails">
					<ul class="thumbnail-nav">
						<?php

						if ( has_post_thumbnail() ) {
							$attachment_id = get_post_thumbnail_id();
							ls_show_product_thumbnail( $attachment_id, $zoomed_image_size );
						}

						foreach ( $attachment_ids as $attachment_id ) {
							$image_link = esc_url( wp_get_attachment_url( $attachment_id ) );
							if ( ! $image_link ) {
								continue;
							}
							ls_show_product_thumbnail( $attachment_id, $zoomed_image_size );
						} ?>
					</ul>

				</div>

			<?php } ?>

			<?php do_action( 'swipe_slick_gallery_after_main' ); ?>

		</div>

		<!-- Photo Swipe -->
		<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="pswp__bg"></div>
			<div class="pswp__scroll-wrap">
				<div class="pswp__container">
					<div class="pswp__item"></div>
					<div class="pswp__item"></div>
					<div class="pswp__item"></div>
				</div>
				<div class="pswp__ui pswp__ui--hidden">
					<div class="pswp__top-bar">
						<div class="pswp__counter"></div>
						<button class="pswp__button pswp__button--close" title="<?php _e( 'Close (Esc)', 'light-store' ); ?>"></button>
						<button class="pswp__button pswp__button--share" title="<?php _e( 'Share', 'light-store' ); ?>"></button>
						<button class="pswp__button pswp__button--fs" title="<?php _e( 'Toggle fullscreen', 'light-store' ); ?>"></button>
						<button class="pswp__button pswp__button--zoom" title="<?php _e( 'Zoom in/out', 'light-store' ); ?>"></button>
						<div class="pswp__preloader">
							<div class="pswp__preloader__icn">
								<div class="pswp__preloader__cut">
									<div class="pswp__preloader__donut"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
						<div class="pswp__share-tooltip"></div>
					</div>
					<button class="pswp__button pswp__button--arrow--left" title="<?php _e( 'Previous', 'light-store' ); ?>">
					</button>
					<button class="pswp__button pswp__button--arrow--right" title="<?php _e( 'Next', 'light-store' ); ?>">
					</button>
					<div class="pswp__caption">
						<div class="pswp__caption__center"></div>
					</div>
				</div>
			</div>
		</div>

	<?php }
}

if ( ! function_exists( 'ls_show_product_thumbnail' ) ) {

	function ls_show_product_thumbnail( $attachment_id, $zoomed_image_size ) {

		global $post;

		$image = wp_get_attachment_image( $attachment_id, apply_filters( 'ls_show_product_thumbnail_size', 'shop_thumbnail' ) );
		$hq    = wp_get_attachment_image_src( $attachment_id, apply_filters( 'swipe_slick_gallery_zoomed_image_size', $zoomed_image_size ) );
		$med   = wp_get_attachment_image_src( $attachment_id, 'shop_single' );

		echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '
							<li>
								<div class="thumb" data-hq="%s" data-w="%s" data-h="%s" data-med="%s" data-medw="%s" data-medh="%s">%s</div>
							</li>', $hq[0], $hq[1], $hq[2], $med[0], $med[1], $med[2], $image ), $attachment_id, $post->ID );
	}
}

if ( ! function_exists( 'ls_output_product_data' ) ) {

	function ls_output_product_data() {

		$data = apply_filters( 'woocommerce_product_tabs', [ ] );

		if ( ! empty( $data ) ) : ?>

			<div class="ls_output_product_data">

				<?php
				foreach ( $data as $key => $tab ) :

					call_user_func( $tab['callback'], $key, $tab );

				endforeach;
				?>
			</div>

		<?php endif;
	}
}

if ( ! function_exists( 'ls_before_comment_details' ) ) {
	/**
	 * Before comment details
	 * Wraps all WooCommerce comments
	 *
	 */
	function ls_before_comment_details() {
		?>
		<div class="comment_details">
		<?php
	}
}

if ( ! function_exists( 'ls_after_comment_details' ) ) {
	/**
	 * After comment details
	 * Closes the comment details divs
	 *
	 */
	function ls_after_comment_details() {
		?>
		</div><!-- .comment_details -->
		<?php
	}
}