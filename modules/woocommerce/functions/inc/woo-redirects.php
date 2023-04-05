<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce Custom Taxonomies
 *
 * @package modules/woocommerce
 * @version 1.0
*/
function wc_term_redirect() {
    if( is_tax( 'product_cat' ) ) {
        $prod_term = get_queried_object();

        if(get_field('product_cat_redirect', 'product_cat_'.$prod_term->term_id)) {
            wp_redirect(get_field('product_cat_redirect', 'product_cat_'.$prod_term->term_id), 301);
            exit;
        }
    } elseif(is_page('shop')) { 
        wp_redirect(esc_url(home_url()), 301);
        exit;
    }
}
add_action( 'template_redirect', 'wc_term_redirect' );
