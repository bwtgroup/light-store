<?php
/**
 * LS WooCommerce hooks
 *
 */
function lsWooCommerceHooks() {

	/**
	 * Secondary navigation
	 *
	 * @see  ls_navigation_account()
	 * @see  ls_navigation_cart()
	 * @see  ls_navigation_search()
	 *
	 */
	add_action( 'ls_right_navigation', 'ls_navigation_account', 1 );
	add_action( 'ls_right_navigation', 'ls_navigation_cart', 5 );
	add_action( 'ls_right_navigation', 'ls_navigation_search', 10 );
	/**
	 * Layout
	 *
	 */
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

	/**
	 * Product
	 *
	 */

	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
	remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
	remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );

	/**
	 * Before Single Products Summary Div.
	 *
	 * @see  ls_show_product_images_swipe_slick()
	 * @see  woocommerce_show_product_sale_flash()
	 */
	add_action( 'woocommerce_before_single_product_summary', 'ls_show_product_images_swipe_slick', 20 );
	add_action( 'swipe_slick_gallery_before_main', 'woocommerce_show_product_sale_flash', 15 );
	/**
	 * Get Type Layout Product page
	 */
	$product_template_type = ls_get_theme_mod( 'product_page_layout_type');

	switch ( $product_template_type ) {
		case 1:

			/**
			 * Product Summary Box
			 *
			 * @see  ls_template_single_meta()
			 */
			add_action( 'woocommerce_single_product_summary', 'ls_template_single_meta', 7 );
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

			break;

		case 2:

			add_filter( 'ls_show_product_thumbnail_size', create_function( '', 'return "large";' ), 25, 1 );

			/**
			 * Product Summary Box
			 *
			 * @see  ls_template_single_meta()
			 */
			add_action( 'woocommerce_single_product_summary', 'ls_template_single_meta', 7 );
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

			break;

		case 3:

			/**
			 * Product Summary Box
			 *
			 * @see  woocommerce_template_single_price()
			 * @see  ls_template_single_meta()
			 */
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
			add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 9 );
			add_action( 'woocommerce_single_product_summary', 'ls_template_single_meta', 7 );
			/**
			 * After Single Products Summary Div
			 *
			 * @see  ls_output_product_data()
			 * @see  ls_before_comment_details()
			 * @see  woocommerce_review_display_rating()
			 * @see  woocommerce_review_display_meta()
			 * @see  ls_after_comment_details()
			 */
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
			add_action( 'woocommerce_after_single_product_summary', 'ls_output_product_data', 10 );

			remove_action( 'woocommerce_review_meta', 'woocommerce_review_display_meta', 10 );
			remove_action( 'woocommerce_review_before_comment_meta', 'woocommerce_review_display_rating', 10 );
			add_action( 'woocommerce_review_before', 'ls_before_comment_details', 1 );
			add_action( 'woocommerce_review_before', 'woocommerce_review_display_rating', 2 );
			add_action( 'woocommerce_review_before', 'woocommerce_review_display_meta', 11 );
			add_action( 'woocommerce_review_before', 'ls_after_comment_details', 50 );

			break;
	}

	/**
	 * Products
	 *
	 * @see  ls_before_content()
	 * @see  ls_after_content()
	 * @see  ls_shop_messages()
	 * @see  ls_sorting_wrapper()
	 * @see  ls_sorting_wrapper_close()
	 * @see  ls_template_loop_product_thumbnails()
	 * @see  ls_template_loop_product_title()
	 * @see  woocommerce_show_product_loop_sale_flash()
	 * @see  ls_product_bottom_open()
	 * @see  ls_product_swap_open()
	 * @see  woocommerce_template_loop_price()
	 * @see  ls_product_swap_close()
	 * @see  ls_product_swatch_color()
	 * @see  ls_product_bottom_close()
	 * @see  ls_add_to_cart_fragments()
	 */

	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
	remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );

	add_action( 'woocommerce_before_main_content', 'ls_before_content', 10 );
	add_action( 'woocommerce_after_main_content', 'ls_after_content', 10 );
	add_action( 'ls_content_top', 'ls_shop_messages', 15 );
	add_action( 'woocommerce_after_shop_loop', 'ls_sorting_wrapper', 9 );
	add_action( 'woocommerce_after_shop_loop', 'ls_sorting_wrapper_close', 31 );
	add_action( 'woocommerce_before_shop_loop', 'ls_sorting_wrapper', 9 );
	add_action( 'woocommerce_before_shop_loop', 'ls_sorting_wrapper_close', 31 );

	add_action( 'woocommerce_before_shop_loop_item_title', 'ls_template_loop_product_thumbnails', 10 );
	add_action( 'woocommerce_shop_loop_item_title', 'ls_template_loop_product_title', 10 );
	add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 6 );

	add_action( 'woocommerce_after_shop_loop_item', 'ls_product_bottom_open', 6 );
	add_action( 'woocommerce_after_shop_loop_item', 'ls_product_swap_open', 7 );
	add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 8 );
	add_action( 'woocommerce_after_shop_loop_item', 'ls_product_swap_close', 15 );

	add_action( 'woocommerce_after_shop_loop_item', 'ls_product_swatch_color', 20 );
	add_action( 'woocommerce_after_shop_loop_item', 'ls_product_bottom_close', 25 );

	add_filter( 'woocommerce_add_to_cart_fragments', 'ls_add_to_cart_fragments' );

	/**
	 * If init YITH Quick View
	 *
	 * @see  ls_add_quick_view_button()
	 * @see  ls_template_loop_product_title()
	 * @see  woocommerce_template_single_price()
	 * @see  ls_template_single_meta()
	 * @see  ls_show_product_images_swipe_slick()
	 */
	if ( class_exists( 'YITH_WCQV' ) ) {

		remove_action( 'yith_wcwl_table_after_product_name', [
			YITH_WCQV_Frontend::get_instance(),
			'yith_add_quick_view_button'
		], 15 );
		remove_action( 'woocommerce_after_shop_loop_item', [
			YITH_WCQV_Frontend::get_instance(),
			'yith_add_quick_view_button'
		], 15 );

		remove_action( 'yith_wcqv_product_summary', 'woocommerce_template_single_title', 5 );
		remove_action( 'yith_wcqv_product_summary', 'woocommerce_template_single_price', 15 );
		remove_action( 'yith_wcqv_product_summary', 'woocommerce_template_single_meta', 30 );
		remove_action( 'yith_wcqv_product_image', 'woocommerce_show_product_images', 20 );
		remove_action( 'yith_wcqv_product_image', 'woocommerce_show_product_sale_flash', 10 );

		add_action( 'ls_product_thumbnail_buttons', 'ls_add_quick_view_button', 5 );

		add_action( 'yith_wcqv_product_summary', 'ls_template_loop_product_title', 5 );
		add_action( 'yith_wcqv_product_summary', 'woocommerce_template_single_price', 9 );
		add_action( 'yith_wcqv_product_summary', 'ls_template_single_meta', 7 );
		add_action( 'yith_wcqv_product_image', 'ls_show_product_images_swipe_slick', 20 );
	}
	/**
	 * If init YITH Wishlist
	 *
	 * @see ls_add_wishlist_button()
	 * @see ls_add_wishlist_button()
	 */
	if ( class_exists( 'YITH_WCWL' ) ) {

		add_action( 'ls_product_thumbnail_buttons', 'ls_add_wishlist_button', 10 );
		add_action( 'woocommerce_single_product_summary', 'ls_add_wishlist_button', 36 );

		add_filter( 'yith_wcwl_add_to_wishlist_params', 'ls_add_to_wishlist_params' );
		add_filter( 'yith_wcwl_button_label', 'ls_add_to_wishlist_button_label' );
		add_filter( 'yith-wcwl-browse-wishlist-label', 'ls_add_to_wishlist_button_label' );
	}

	/**
	 * If init YITH Compare
	 *
	 * @see ls_add_compare_button()
	 */
	if ( class_exists( 'YITH_Woocompare' ) ) {

		add_action( 'ls_product_thumbnail_buttons', 'ls_add_compare_button', 8 );
	}

	/**
	 * Structured Data
	 *
	 * @see ls_woocommerce_init_structured_data()
	 */
	if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '2.7', '<' ) ) {
		add_action( 'woocommerce_before_shop_loop_item', 'ls_woocommerce_init_structured_data' );
	}

	if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '2.3', '>=' ) ) {
		add_filter( 'woocommerce_add_to_cart_fragments', 'ls_cart_link_fragment' );
	} else {
		add_filter( 'add_to_cart_fragments', 'ls_cart_link_fragment' );
	}
}
