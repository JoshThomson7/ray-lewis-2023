<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce AJAX
 *
 * @package modules/woocommerce
 * @version 1.0
*/

function wc_ajax_add_to_cart() {

    // security check
    wp_verify_nonce('$C.cGLu/1zxq%.KH}PjIKK|2_7WDN`x[vdhtF5GS4|+6%$wvG)2xZgJcWv3H2K_M', 'wc_security');

    $product_type = $_POST['wc_product_type'];
    $cart_data = isset($_POST['wc_cart_data']) && !empty($_POST['wc_cart_data']) ? $_POST['wc_cart_data'] : array();

    if(!empty($cart_data)) {

        foreach ($cart_data as $key => $value) {

            // get product data
            $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($value['product_id']));
            $quantity = empty($value['quantity']) ? 1 : wc_stock_amount($value['quantity']);
            $variation_id = null;
            $variation = array();
            $cart_item_data_variation = array();

            // variable
            if ($product_type === 'variable' || $product_type === 'variable-subscription') {
                $variation_id = absint($value['variation_id']);

                $variation[$value['variation_slug']] = $value['variation_name'];
                $cart_item_data_variation[$value['variation_slug']] = $value['variation_name'] . ' - &pound;' . number_format($value['variation_price'], 2, '.', '');
            }

            $product_status = get_post_status($product_id);

            // filter validation rules
            $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);

            // all good?
            $product_available = !current_user_can('administrator') ? ('publish' === $product_status) : true;

            // Validate
            if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation, $cart_item_data_variation) && $product_available) {

                // add to cart
                do_action('woocommerce_ajax_added_to_cart', $product_id);

                // This is the magic: With this function, the modified object gets saved.
                WC()->cart->set_session();

            } else {

                $data = array(
                    'error' => true,
                    'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id)
                );

                wp_send_json(array(
                    'feedback' => $data,
                    'passed' => $passed_validation,
                    'add' => WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $cart_item_data_variation),
                    'item_data' => $cart_item_data_variation
                ));
            }
        }

        // DEBUG
        //print_r($voucher_item_meta_data);

    }

    wp_die();
}

add_action('wp_ajax_wc_ajax_add_to_cart', 'wc_ajax_add_to_cart');
add_action('wp_ajax_nopriv_wc_ajax_add_to_cart', 'wc_ajax_add_to_cart');