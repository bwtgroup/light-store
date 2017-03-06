<?php
/**
 * Custom Widgets
 *
 * Widget related functions and widget registration.
 *
 * @author 		Vanokk
 * @category 	Core
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Include widget classes.
include_once( 'widgets/ls_widget_site_info.php' );

/**
 * Register Widgets.
 *
 */
function ls_register_widgets() {
	register_widget( 'LS_Widget_Site_Info' );
}
add_action( 'widgets_init', 'ls_register_widgets' );