<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce Checkout Functions
 *
 * @package modules/woocommerce
 * @version 1.0
*/

/**
 * wc_update_user_orders
 *
 * Saves customer orders in user meta
 * for faster processing uner my account
 *
 * @param  [int] $order_id The order ID
 */
function wc_update_user_orders($order_id) {

    if(is_user_logged_in()) {
        $customer_orders = get_user_meta(get_current_user_id(), '_customer_orders', true);

        if(!$customer_orders) {
            $customer_orders = wc_orders_to_user_meta(true);

        } else {

            // only add the order ID if not already in the array
            if(!in_array($order_id, $customer_orders)) {

                // add new order to array
                array_push($customer_orders, $order_id);

                // update user meta orders
                update_user_meta(get_current_user_id(), '_customer_orders', $customer_orders);

            }

        }

    }

}
//add_action('woocommerce_thankyou', 'wc_update_user_orders', 10, 1);

/**
 * wc_custome_order_received_text()
 *
 * Custom thank you text
 *
 * @param  [str] $str
 * @param  [int] $order
 * @return [str]
 */
function wc_custome_order_received_text( $text, $order ) {

    if ( isset ( $order ) ) {
	    $text = sprintf( "Thank you, %s. Your order has been received.", esc_html( $order->get_billing_first_name() ) );
    }

    return $text;
}
add_filter('woocommerce_thankyou_order_received_text', 'wc_custome_order_received_text', 10, 2 );

/**
 * Add Prefix to WooCommerce Order Number
*/
function wc_prefix_order_number( $oldnumber, $order ) {
    return 'EUPBOO-' . $order->get_id();
}
add_filter( 'woocommerce_order_number', 'wc_prefix_order_number', 1, 2 );

/**
 * Disclaimer
 */
function wc_checkout_disclaimer() {
    echo '<div class="wc__checkout__disclamier"><p>All Decked Out Rib Charter Vouchers are valid for one year from the date of purchase, and are available to redeem against any Rib Charter, Commercial Rib Charter, Corporate Rib Charter and Rib Charter Packages.<br/><br/>Please contact us on 01590 452122 if there are any queries before purchasing a Voucher.</p></div>';
}
// /add_action('woocommerce_review_order_before_submit', 'wc_checkout_disclaimer' );