<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce My Account
 *
 * @package modules/woocommerce
 * @version 1.0
*/

/*--------------------------------------------------------------------------*/
/*    function is_wc_dashboard()
/*    returns true if my account dashboard
/*--------------------------------------------------------------------------*/
function is_wc_dashboard() {

    global $wp;

    if( is_account_page() &&
        !is_wc_endpoint_url() &&
        $wp->request != 'my-account/subscriptions' &&
        !$wp->query_vars['view-subscription'] &&
        !isset($_GET['resubscribe']) ) {
        
        return true;
    }

    return false;
    
}

/*
 *  Custom account menu
*/
function wc_custom_my_account_menu_items( $items ) {

    $items = array(
        'dashboard'         => __( 'Dashboard', 'woocommerce' ),
        'orders'            => __( 'Bookings &amp; Orders', 'woocommerce' ),
        'edit-address'      => __( 'Addresses', 'woocommerce' ),
        'edit-account'      => __( 'Account', 'woocommerce' ),
        'customer-logout'   => __( 'Log out', 'woocommerce' ),
    );

	return $items;
}
//add_filter( 'woocommerce_account_menu_items', 'wc_custom_my_account_menu_items' );

/*
 *  Get customer subs count
*/
function wc_get_customer_subscriptions() {

    // Get all customer subs
    $customer_subs = new WP_Query(array(
        'post_type'           => 'shop_subscription',
    	'posts_per_page'      => -1,
    	'post_status'         => 'all',
        'meta_query'          => array(
    		array(
    			'key'   => '_customer_user',
    			'value' => get_current_user_id(),
    		),
    	)
    ));

    $total_subs = $customer_subs->post_count;

    return $total_subs;
}