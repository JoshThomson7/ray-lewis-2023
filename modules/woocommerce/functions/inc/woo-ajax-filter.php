<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Woo WP Login
 *
 * Custom functions to use our Woo module with
 * BeRocket's AJAX Product filter
 *
 * @author  Various
 * @package WooCommerce
 * @see https://berocket.com/docs/plugin/woocommerce-ajax-products-filter
 *
*/

function wc_custom_filters_on_load ( $args ) {
    global $woocommerce, $products;

    if(isset($_GET['filters']) && !empty($_GET['filters'])) {
        //print_r($_GET);

        // grab current category for AND query
        $prod_cat = get_queried_object();
        $prod_cat_slug = $prod_cat->slug;

        $args['product_cat'] = $prod_cat_slug;
        $products = new WP_Query($args);
    }

    // DEBUG:
    // echo '<pre>';
    // print_r($args);
    // echo '</pre>';

    return $args;
}

add_filter( 'berocket_aapf_filters_on_page_load', 'wc_custom_filters_on_load' );
