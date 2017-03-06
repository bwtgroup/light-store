<?php
require_once 'inc/class-ls.php';
require_once 'inc/ls-functions.php';
require_once 'inc/ls-template-hooks.php';
require_once 'inc/ls-template-functions.php';
require_once 'inc/ls-widgets.php';
require_once 'inc/ls-ajax.php';
require_once 'inc/kirki/ls-kirki-config.php';

/**
 * Instantiate the Light Store Class
 */
new LS();


if ( is_woocommerce_activated() ) {
    require_once 'inc/woocommerce/ls-woocommerce-template-hooks.php';
    require_once 'inc/woocommerce/ls-woocommerce-template-functions.php';
	require_once 'inc/woocommerce/class-ls-woocommerce.php';

	/**
	 * Instantiate the WooCommerce Light Store Class.
	 */
	new LS_Woocommerce();
}