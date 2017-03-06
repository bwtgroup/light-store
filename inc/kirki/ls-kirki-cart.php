<?php
/**
 * Add Cart Customizer
 */

Kirki::add_section( 'cart', array(
	'title'       => __( 'Cart', 'light-store' ),
	'priority'    => 20,
	'panel'          => '',
	'capability'     => 'edit_theme_options',
	'theme_supports' => '',
) );

Kirki::add_field( 'ls_theme', array(
	'type'        => 'radio-image',
	'settings'    => 'basket-image',
	'label'       => esc_html__( 'Cart Icon', 'light-store' ),
	'section'     => 'cart',
	'default'     => 'basket-1',
	'priority'    => 10,
	'choices'     => array(
		'basket-1'  => trailingslashit( get_template_directory_uri() ) . 'assets/img/carts/basket-1.svg',
		'basket-2' => trailingslashit( get_template_directory_uri() ) . 'assets/img/carts/basket-2.svg',
		'basket-3' => trailingslashit( get_template_directory_uri() ) . 'assets/img/carts/basket-3.svg'
	),
) );

Kirki::add_field( 'ls_theme', array(
	'type'        => 'radio',
	'settings'    => 'mini-cart-layout',
	'label'       => esc_html__( 'Mini Cart Layout', 'light-store' ),
	'section'     => 'cart',
	'default'     => '1',
	'priority'    => 10,
	'choices'     => array(
		'1'   => array(
			esc_attr__( 'Layout 1', 'light-store' ),
			esc_attr__( 'Popup layout', 'light-store' ),
		),
		'2' => array(
			esc_attr__( 'Layout 2', 'light-store' ),
			esc_attr__( 'Side bar layout', 'light-store' ),
		)
	),
) );