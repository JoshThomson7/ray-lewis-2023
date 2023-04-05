<?php
/**
 * WooCommerce Login/Register functions
 *
 * @package modules/woocommerce
 * @version 1.0
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Links previous orders to a new customer upon registration.
 *
 * @param int $user_id the ID for the new user
 */
function wc_link_orders_at_registration( $user_id ) {
    wc_update_new_customer_past_orders( $user_id );
}
add_action( 'woocommerce_created_customer', 'wc_link_orders_at_registration' );