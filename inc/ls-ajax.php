<?php
/**
 * Register LS ajax hooks
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// update quantity products in the cart
add_action( 'wp_ajax_ls_product_update_qty', 'ls_product_update_qty' );
add_action( 'wp_ajax_nopriv_ls_product_update_qty', 'ls_product_update_qty' );
if ( !function_exists( 'ls_product_update_qty' ) ) {

	function ls_product_update_qty() {
		$resp               = [ ];
		$resp['status']     = 0;
		$resp['price']      = 0;
		$resp['subtotal']   = 0;
		$resp['totalCount'] = 0;

		// calculate the item price
		if ( ! empty( $_POST['cart_item_key'] ) ) {

			$cart_item_key = filter_var( $_POST['cart_item_key'], FILTER_SANITIZE_STRING );
			$cart_item_qty = filter_var( $_POST['cart_item_qty'], FILTER_SANITIZE_STRING );

			WC()->cart->set_quantity( $cart_item_key, $cart_item_qty );

			$items = WC()->cart->get_cart();

			if ( array_key_exists( $cart_item_key, $items ) ) {
				$cart_item     = $items[ $cart_item_key ];
				$_product      = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$price         = apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
				$resp['price'] = $price;
			}

			$resp['subtotal']   = WC()->cart->get_cart_total();
			$resp['totalCount'] = WC()->cart->get_cart_contents_count();
			$resp['status']     = 1;
		}

		echo json_encode( $resp );
		exit;
	}

}


// remove product from cart
add_action( 'wp_ajax_ls_product_remove', 'ls_product_remove' );
add_action( 'wp_ajax_nopriv_ls_product_remove', 'ls_product_remove' );
if ( !function_exists( 'ls_product_remove' ) ) {
	function ls_product_remove() {
		$resp               = [ ];
		$resp['status']     = 0;
		$resp['subtotal']   = 0;
		$resp['totalCount'] = 0;

		if ( ! empty( $_POST['cart_item_key'] ) ) {
			$cart_item_key = filter_var( $_POST['cart_item_key'], FILTER_SANITIZE_STRING );

			WC()->cart->remove_cart_item( $cart_item_key );

			$resp['subtotal']   = WC()->cart->get_cart_total();
			$resp['totalCount'] = WC()->cart->get_cart_contents_count();
			$resp['status']     = 1;
		}

		echo json_encode( $resp );
		exit();
	}

}

// add product to cart
add_action( 'wp_ajax_ls_add_product', 'ls_add_product' );
add_action( 'wp_ajax_nopriv_ls_add_product', 'ls_add_product' );
if ( !function_exists( 'ls_add_product' ) ) {
	function ls_add_product() {
		$resp = [ ];

		$product_id   = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
		$quantity     = empty( $_POST['quantity'] ) ? 1 : apply_filters( 'woocommerce_stock_amount', $_POST['quantity'] );
		$variation_id = $_POST['variation_id'];
		$variation    = $_POST['variation'];

		$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );

		if ( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation ) ) {

			ob_start();
			woocommerce_mini_cart();
			$resp['cart']       = ob_get_clean();
			$resp['totalCount'] = WC()->cart->get_cart_contents_count();
			$resp['status']     = 1;
			$resp['message']    = 'Adding to the cart';
		} else {
			// If there was an error adding to the cart,
			$resp['status']  = 0;
			$resp['message'] = 'Error adding to the cart';
		}

		echo json_encode( $resp );
		exit();
	}
}


// get wishlist count product
add_action( 'wp_ajax_ls_wishlist_number', 'ls_wishlist_number' );
add_action( 'wp_ajax_nopriv_ls_wishlist_number', 'ls_wishlist_number' );
if ( !function_exists( 'ls_wishlist_number' ) ) {
	function ls_wishlist_number() {
		$resp           = [ ];
		$resp['status'] = 0;
		$resp['count']  = 0;

		if ( class_exists( 'YITH_WCWL' ) ) {
			$resp['status'] = 1;
			$resp['count']  = YITH_WCWL()->count_products();
		}
		echo json_encode( $resp );
		exit();
	}
}


// ajax user login
add_action( 'wp_ajax_ls_ajax_login', 'ls_ajax_login' );
add_action( 'wp_ajax_nopriv_ls_ajax_login', 'ls_ajax_login' );
if ( !function_exists( 'ls_ajax_login' ) ) {
	function ls_ajax_login() {
		$response  = [ ];
		$nonce_value = isset( $_POST['_wpnonce'] ) ? $_POST['_wpnonce'] : '';
		$nonce_value = isset( $_POST['woocommerce-login-nonce'] ) ? $_POST['woocommerce-login-nonce'] : $nonce_value;

		if ( wp_verify_nonce( $nonce_value, 'woocommerce-login' ) ) {


			$creds    = array();
			$username = trim( $_POST['username'] );

			$validation_error = new WP_Error();
			$validation_error = apply_filters( 'woocommerce_process_login_errors', $validation_error, $_POST['username'], $_POST['password'] );

			if ( $validation_error->get_error_code() ) {
				$response['success'] = 0;
				$response['message'] = '<strong>' . __( 'Error', 'light-store' ) . ':</strong> ' . $validation_error->get_error_message();
				echo json_encode( $response );
				exit();
			}

			if ( empty( $username ) ) {
				$response['success'] = 0;
				$response['message'] = '<strong>' . __( 'Error', 'light-store' ) . ':</strong> ' . __( 'Username is required.', 'light-store' );
				echo json_encode( $response );
				exit();
			}

			if ( empty( $_POST['password'] ) ) {
				$response['success'] = 0;
				$response['message'] = '<strong>' . __( 'Error', 'light-store' ) . ':</strong> ' . __( 'Password is required.', 'light-store' );
				echo json_encode( $response );
				exit();
			}

			if ( is_email( $username ) && apply_filters( 'woocommerce_get_username_from_email', true ) ) {
				$user = get_user_by( 'email', $username );

				if ( isset( $user->user_login ) ) {
					$creds['user_login'] = $user->user_login;
				} else {
					$response['success'] = 0;
					$response['message'] = '<strong>' . __( 'Error', 'light-store' ) . ':</strong> ' . __( 'A user could not be found with this email address.', 'light-store' );
					echo json_encode( $response );
					exit();
				}

			} else {
				$creds['user_login'] = $username;
			}

			$creds['user_password'] = $_POST['password'];
			$creds['remember']      = isset( $_POST['rememberme'] );
			$secure_cookie          = is_ssl() ? true : false;
			$user                   = wp_signon( apply_filters( 'woocommerce_login_credentials', $creds ), $secure_cookie );

			if ( is_wp_error( $user ) ) {
				$message = $user->get_error_message();
				$message = str_replace( '<strong>' . esc_html( $creds['user_login'] ) . '</strong>', '<strong>' . esc_html( $username ) . '</strong>', $message );
				$response['success'] = 0;
				$response['message'] = $message;
				echo json_encode( $response );
				exit();
			} else {

				$response['success'] = 1;
				$response['message'] = __( 'Login successful, redirecting...', 'light-store' );;
				echo json_encode( $response );
				exit();
			}

		}
	}
}


// ajax user register
add_action( 'wp_ajax_ls_ajax_register', 'ls_ajax_register' );
add_action( 'wp_ajax_nopriv_ls_ajax_register', 'ls_ajax_register' );

if ( !function_exists( 'ls_ajax_register' ) ) {
	function ls_ajax_register() {

		$response  = [ ];
		$nonce_value = isset( $_POST['_wpnonce'] ) ? $_POST['_wpnonce'] : '';
		$nonce_value = isset( $_POST['woocommerce-register-nonce'] ) ? $_POST['woocommerce-register-nonce'] : $nonce_value;

		if ( wp_verify_nonce( $nonce_value, 'woocommerce-register' ) ) {
			$username = 'no' === get_option( 'woocommerce_registration_generate_username' ) ? $_POST['username'] : '';
			$password = 'no' === get_option( 'woocommerce_registration_generate_password' ) ? $_POST['password'] : '';
			$email    = $_POST['email'];


			$validation_error = new WP_Error();
			$validation_error = apply_filters( 'woocommerce_process_registration_errors', $validation_error, $username, $password, $email );

			if ( $validation_error->get_error_code() ) {
				$response['success'] = 0;
				$response['message'] = '<strong>' . __( 'Error', 'light-store' ) . ':</strong> ' . $validation_error->get_error_message();
				echo json_encode( $response );
				exit();
			}

			// Anti-spam trap
			if ( ! empty( $_POST['email_2'] ) ) {
				$response['success'] = 0;
				$response['message'] = __( 'Anti-spam field was filled in.', 'light-store' );
				echo json_encode( $response );
				exit();
			}

			$new_customer = wc_create_new_customer( sanitize_email( $email ), wc_clean( $username ), $password );

			if ( is_wp_error( $new_customer ) ) {
				$response['success'] = 0;
				$response['message'] = '<strong>' . __( 'Error', 'light-store' ) . ':</strong> ' . $new_customer->get_error_message();
				echo json_encode( $response );
				exit();
			}

			if ( apply_filters( 'woocommerce_registration_auth_new_customer', true, $new_customer ) ) {
				wc_set_customer_auth_cookie( $new_customer );
			}

			$response['success'] = 1;
			$response['message'] = __( 'User registered', 'light-store' );
			echo json_encode( $response );
			exit();

		}

	}
}

