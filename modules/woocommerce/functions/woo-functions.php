<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce Functions
 *
 * @package modules/woocommerce
 * @version 1.0
*/

function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'woocommerce_support' );

// includes
require_once('inc/woo-pages.php');
require_once('inc/woo-templates.php');
require_once('inc/woo-performance.php');
require_once('inc/woo-redirects.php');
require_once('inc/woo-ajax-add-to-cart.php');
require_once('inc/woo-styles.php');
require_once('inc/woo-login-register.php');
require_once('inc/woo-enqueue.php');
require_once('inc/woo-single-product.php');
require_once('inc/woo-my-account.php');
require_once('inc/woo-cart.php');
require_once('inc/woo-checkout.php');

/**
 * wc_path()
 * Returns the full WooComerce module path
 * 
 * @param bool $wc_path
 * @return bool $wc_path
 */
function wc_path($wc_path = false) {
    if($wc_path) {
        $wc_path = get_stylesheet_directory_uri()  . '/modules/woocommerce/';
    } else {
        $wc_path = get_stylesheet_directory()  . '/modules/woocommerce/';
    }
    return $wc_path;
}

/**
 * wc_class()
 * Returns the class of the current woo page
 * 
 * @param bool $echo
 * @return string $wc_class
 */
function wc_class($echo = false) {
    if(is_shop()) {
        $wc_class = 'wc__shop';

    } elseif(is_singular('product')) {
        $wc_class = 'wc-single-product';

    }

    if($echo === true) {
        echo $wc_class;
    } else {
        return $wc_class;
    }
}

/**
 * wc_extend_cookie_logout()
 * 
 * @param int $expiration
 * @return int
 */
// function wc_extend_cookie_logout( $expiration ){
//     return 15780000; // 6 months in seconds
// }
// add_filter('auth_cookie_expiration', 'wc_extend_cookie_logout', 10, 3);

/*--------------------------------------------------------------------------*/
/*    function wc_hide()
/*    Hides WooCommerce pages. Useful if working on a live site
/*--------------------------------------------------------------------------*/
// function wc_hide() {
//     if ( ( is_woocommerce() || is_shop() || is_cart() || is_checkout() ) && !current_user_can('administrator')) {
//         wp_redirect(esc_url(home_url()));
//     }
// }
// add_action( 'wp_head', 'wc_hide', 0);


// add_action( 'woocommerce_cart_is_empty', 'bbloomer_print_cart_array' );
// add_action( 'woocommerce_before_cart', 'bbloomer_print_cart_array' );
// function bbloomer_print_cart_array() {
//     $cart = WC()->cart->get_cart();
//     echo '<pre>';
//     print_r($cart);
//     echo '</pre>';
// }


/* ------------------------------------------*/
/* 	SITE SEARCH
/* 	Include post types in search
* -------------------------------------------*/
function wc_search_cpts($query) {
    if ($query->is_search && !is_admin()) {
        $query->set('post_type', array('product'));
        $query->set('post_status', array('publish'));
    };
    return $query;
};
//add_filter('pre_get_posts', 'wc_search_cpts');