<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Woo Templates
 *
 * Programatcially assign pages
 *
 * @author  Various
 * @package WooCommerce
 *
*/

/*
 *  Page templates
*/
function woo_page_templates($page_template) {
    global $post;

    // Login
    if(is_page('login') || is_page('register') || is_wc_endpoint_url( 'lost-password' )) {
        $page_template = wc_path() . 'templates/woo-login-register.php';
    
    } elseif(is_page(array('my-physio', 'my-account'))) {
        if(!is_user_logged_in()) {
            $page_template = wc_path() . 'templates/woo-login-register.php';
        }

    } elseif(is_page('checkout')) {
        $page_template = wc_path() . 'templates/woo-checkout.php';
    }

    return $page_template;


}
add_filter( 'page_template', 'woo_page_templates' );