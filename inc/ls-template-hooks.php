<?php
/**
 * ls hooks
 *
 */
function lsHooks() {
	/**
	 * General
	 *
	 * @see  ls_header_shim()
	 * @see  ls_header_widget_region()
	 * @see  ls_get_sidebar()
	 */
	add_action( 'ls_before_content', 'ls_header_shim', 0 );
	add_action( 'ls_before_content', 'ls_header_widget_region', 50 );
	add_action( 'ls_sidebar', 'ls_get_sidebar', 10 );

	/**
	 * Header
	 *
	 * @see  ls_site_branding()
	 * @see  ls_primary_navigation()
	 * @see  ls_right_navigation()
	 */

	add_action( 'ls_header', 'ls_site_branding', 10 );
	add_action( 'ls_header', 'ls_primary_navigation', 20 );
	add_action( 'ls_header', 'ls_right_navigation', 40 );

	/**
	 * Footer
	 *
	 * @see  ls_footer_widgets()
	 */
	add_action( 'ls_footer', 'ls_footer_widgets', 10 );

	/**
	 * Homepage
	 *
	 * @see  ls_homepage_masonry_categories()
	 * @see  ls_homepage_content()
	 */
	add_action( 'homepage', 'ls_homepage_masonry_categories', 10 );
	add_action( 'homepage', 'ls_homepage_content', 20 );

	/**
	 * Posts
	 *
	 * @see  ls_post_header()
	 * @see  ls_post_meta()
	 * @see  ls_post_content()
	 * @see  ls_init_structured_data()
	 * @see  ls_paging_nav()
	 * @see  ls_single_post_header()
	 * @see  ls_post_nav()
	 * @see  ls_display_comments()
	 */
	add_action( 'ls_loop_post', 'ls_post_header', 10 );
	add_action( 'ls_loop_post', 'ls_post_meta', 20 );
	add_action( 'ls_loop_post', 'ls_post_content', 30 );
	add_action( 'ls_loop_post', 'ls_init_structured_data', 40 );
	add_action( 'ls_loop_after', 'ls_paging_nav', 10 );
	add_action( 'ls_single_post', 'ls_post_header', 10 );
	add_action( 'ls_single_post', 'ls_post_meta', 20 );
	add_action( 'ls_single_post', 'ls_post_content', 30 );
	add_action( 'ls_single_post', 'ls_init_structured_data', 40 );
	add_action( 'ls_single_post_bottom', 'ls_post_nav', 10 );
	add_action( 'ls_single_post_bottom', 'ls_display_comments', 20 );
	add_action( 'ls_post_content_before', 'ls_post_thumbnail', 10 );

	/**
	 * Pages
	 *
	 * @see  ls_page_header()
	 * @see  ls_page_content()
	 * @see  ls_init_structured_data()
	 * @see  ls_display_comments()
	 */
	add_action( 'ls_page', 'ls_page_header', 10 );
	add_action( 'ls_page', 'ls_page_content', 20 );
	add_action( 'ls_page', 'ls_init_structured_data', 30 );
	add_action( 'ls_page_after', 'ls_display_comments', 10 );
}

