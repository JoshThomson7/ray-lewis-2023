<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce Custom Checkout Fields
 *
 * @package modules/woocommerce
 * @version 1.0
*/

/**
 * Change labels, fields etc of checkout form
*/
function wc_custom_checkout_fields( $fields ) {
	unset($fields['billing']['billing_company']);
	unset($fields['order']['order_comments']);
	
	$fields['billing']['billing_address_1']['required'] = true;
	$fields['billing']['billing_city']['required'] = true;
	$fields['billing']['billing_postcode']['required'] = true;
	$fields['billing']['billing_email']['required'] = true;

    $fields['account']['account_username']['label'] = 'Account email';
    return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'wc_custom_checkout_fields' );

// Remove the "Additional Info" order notes
add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );

add_filter( 'wc_stripe_elements_styling', 'marce_add_stripe_elements_styles' );
function marce_add_stripe_elements_styles($array) {
	$array = array(
		'base' => array(
			'color' 	=> '#404041',
			'fontSize' 	=> '18px'
		),
		'invalid' => array(
			'color'		=> 'tomato'
		)
	);
	return $array;
}
