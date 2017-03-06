<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Displays Site Info in footer.
 *
 * @author   Vanokk
 * @category Widgets
 * @version  1.0.0
 * @extends  WC_Widget
 */
class LS_Widget_Site_Info extends WP_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {

		$widget_options = array(
			'classname' => 'widget_site_info',
			'description' => __( "Displays Site Info", 'light-store' ),
		);

		parent::__construct( 'site_info', 'Site Info Widget', $widget_options );
	}

	/**
	 * Output widget.
	 *
	 * @see WP_Widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {

		echo ls_credit();

	}
}
