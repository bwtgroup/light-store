<?php
/**
 * Light Store Advanced Customizer
 *
 */

// if Kirki is not installed - exit
if ( !class_exists( 'Kirki' ) ) {
	return;
}

Kirki::add_config( 'ls_theme', array(
	'capability'    => 'edit_theme_options',
	'option_type'   => 'theme_mod',
) );

require_once 'ls-kirki-layout.php';
require_once 'ls-kirki-typography.php';

// if woocommerce  activated add kirki config for product and cart
if ( is_woocommerce_activated() ) {
	require_once 'ls-kirki-product.php';
	require_once 'ls-kirki-cart.php';
}
