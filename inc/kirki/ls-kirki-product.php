<?php
/**
 * Add Product Customizer
 */

Kirki::add_section( 'product_page', array(
	'title'       => __( 'Product Page', 'light-store' ),
	'priority'    => 20,
	'panel'          => '', // Not typically needed.
	'capability'     => 'edit_theme_options',
	'theme_supports' => '', // Rarely needed.
) );

Kirki::add_field( 'ls_theme', array(
	'type'        => 'radio',
	'settings'    => 'product_page_layout_type',
	'label'       => esc_html__( 'Product Page Layout', 'light-store' ),
	'section'     => 'product_page',
	'default'     => '1',
	'priority'    => 10,
	'choices'     => array(
		'1'   => array(
			esc_attr__( 'Layout 1', 'light-store' ),
			esc_attr__( 'One column layout', 'light-store' ),
		),
		'2' => array(
			esc_attr__( 'Layout 2', 'light-store' ),
			esc_attr__( 'Two column with sticky summary', 'light-store' ),
		),
		'3' => array(
			esc_attr__( 'Layout 3', 'light-store' ),
			esc_attr__( 'Two column layout', 'light-store' ),
		)
	),
) );