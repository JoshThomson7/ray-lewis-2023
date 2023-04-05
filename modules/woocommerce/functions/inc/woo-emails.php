<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce Emails
 *
 * @package modules/woocommerce
 * @version 1.0
*/

function wc_custom_email_styles( $css ) {
	$css .= "p a { color: #d93f97 !important; }";
	return $css;
}
add_filter( 'woocommerce_email_styles', 'wc_custom_email_styles' );