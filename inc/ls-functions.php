<?php
/**
 * Light Store functions.
 *
 */

if ( ! function_exists( 'ls_get_theme_mod' ) ) {
	/**
	 * Get theme mod.
	 *
	 * @param string $key mod key.
	 *
	 * @return mixed mod value.
	 */
	function ls_get_theme_mod( $key ) {

		if ( empty( $key ) ) {
			return;
		}

		$value = '';

		$default_theme_mods = ls_get_default_theme_mods();
		$default_value      = null;
		if ( is_array( $default_theme_mods ) && isset( $default_theme_mods[ $key ] ) ) {
			$default_value = $default_theme_mods[ $key ];
		}

		if ( null !== $default_value ) {
			$value = get_theme_mod( $key, $default_value );
		} else {
			$value = get_theme_mod( $key );
		}

		return $value;
	}
}

if ( ! function_exists( 'ls_get_default_theme_mods' ) ) {
	/**
	 * Get default theme mods.
	 *
	 * @return array Default theme options.
	 */
	function ls_get_default_theme_mods() {

		$ls_default_theme_mods = [
			'product_page_layout_type' => '1',
			'mini-cart-layout'         => '1',
			'content-layout'           => 'container',
			'basket-image'             => 'basket-1',
		];

		return apply_filters( 'ls_default_theme_mods', $ls_default_theme_mods );
	}
}

/**
 * Display the classes for the content element.
 *
 * @return string
 */
function ls_get_content_classes() {
	echo 'class="' . ls_get_theme_mod( 'content-layout' ) . '"';
}

/**
 * Display the classes for the cart element.
 *
 * @return string
 */
function ls_get_basket_icon_classes() {

	$basket_image = get_theme_mod( 'basket-image', 'basket-1' );
	echo 'class="icon icon-cart icon-basket icon-' . $basket_image . '"';
}

if ( ! function_exists( 'is_woocommerce_activated' ) ) {
	/**
	 * Query WooCommerce activation
	 */
	function is_woocommerce_activated() {
		return class_exists( 'woocommerce' ) ? true : false;
	}
}

/**
 * Checks if the current page is a product archive
 * @return boolean
 */
if ( ! function_exists( 'is_product_archive' ) ) {
	function is_product_archive() {
		if ( is_woocommerce_activated() ) {
			if ( is_shop() || is_product_taxonomy() || is_product_category() || is_product_tag() ) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}

/**
 * Call a shortcode function by tag name.
 *
 *
 * @param string $tag     The shortcode whose function to call.
 * @param array  $atts    The attributes to pass to the shortcode function. Optional.
 * @param array  $content The shortcode's content. Default is null (none).
 *
 * @return string|bool False on failure, the result of the shortcode on success.
 */
function ls_do_shortcode( $tag, array $atts = [ ], $content = null ) {
	global $shortcode_tags;

	if ( ! isset( $shortcode_tags[ $tag ] ) ) {
		return false;
	}

	return call_user_func( $shortcode_tags[ $tag ], $atts, $content, $tag );
}

/**
 * Adjust a hex color brightness
 * Allows us to create hover styles for custom link colors
 *
 * @param  strong  $hex   hex color e.g. #111111.
 * @param  integer $steps factor by which to brighten/darken ranging from -255 (darken) to 255 (brighten).
 *
 * @return string        brightened/darkened hex color
 */
function adjust_color_brightness( $hex, $steps ) {
	// Steps should be between -255 and 255. Negative = darker, positive = lighter.
	$steps = max( - 255, min( 255, $steps ) );

	// Format the hex color string.
	$hex = str_replace( '#', '', $hex );

	if ( 3 == strlen( $hex ) ) {
		$hex = str_repeat( substr( $hex, 0, 1 ), 2 ) . str_repeat( substr( $hex, 1, 1 ), 2 ) . str_repeat( substr( $hex, 2, 1 ), 2 );
	}

	// Get decimal values.
	$r = hexdec( substr( $hex, 0, 2 ) );
	$g = hexdec( substr( $hex, 2, 2 ) );
	$b = hexdec( substr( $hex, 4, 2 ) );

	// Adjust number of steps and keep it inside 0 to 255.
	$r = max( 0, min( 255, $r + $steps ) );
	$g = max( 0, min( 255, $g + $steps ) );
	$b = max( 0, min( 255, $b + $steps ) );

	$r_hex = str_pad( dechex( $r ), 2, '0', STR_PAD_LEFT );
	$g_hex = str_pad( dechex( $g ), 2, '0', STR_PAD_LEFT );
	$b_hex = str_pad( dechex( $b ), 2, '0', STR_PAD_LEFT );

	return '#' . $r_hex . $g_hex . $b_hex;
}

/**
 * Sanitizes choices (selects / radios)
 * Checks that the input matches one of the available choices
 *
 * @param array $input   the available choices.
 * @param array $setting the setting object.
 */
function sanitize_choices( $input, $setting ) {
	// Ensure input is a slug.
	$input = sanitize_key( $input );

	// Get list of choices from the control associated with the setting.
	$choices = $setting->manager->get_control( $setting->id )->choices;

	// If the input is a valid key, return it; otherwise, return the default.
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Checkbox sanitization callback.
 *
 * Sanitization callback for 'checkbox' type controls. This callback sanitizes `$checked`
 * as a boolean value, either TRUE or FALSE.
 *
 * @param bool $checked Whether the checkbox is checked.
 *
 * @return bool Whether the checkbox is checked.
 * @since  1.5.0
 */
function ls_sanitize_checkbox( $checked ) {
	return ( ( isset( $checked ) && true == $checked ) ? true : false );
}

/**
 * Schema type
 *
 * @return void
 */
function ls_html_tag_schema() {

	$schema = 'http://schema.org/';
	$type   = 'WebPage';

	if ( is_singular( 'post' ) ) {
		$type = 'Article';
	} elseif ( is_author() ) {
		$type = 'ProfilePage';
	} elseif ( is_search() ) {
		$type = 'SearchResultsPage';
	}

	echo 'itemscope="itemscope" itemtype="' . esc_attr( $schema ) . esc_attr( $type ) . '"';
}

/**
 * Sanitizes the layout setting
 *
 * Ensures only array keys matching the original settings specified in add_control() are valid
 *
 * @param array $input the layout options.
 */
function sanitize_layout( $input ) {
	_deprecated_function( 'sanitize_layout', '2.0', 'sanitize_choices' );

	$valid = [
		'right' => 'Right',
		'left'  => 'Left',
	];

	if ( array_key_exists( $input, $valid ) ) {
		return $input;
	} else {
		return '';
	}
}
