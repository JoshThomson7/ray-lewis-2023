<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce Cart Functions
 *
 * @package modules/woocommerce
 * @version 1.0
*/

// Disable persistent cart.
add_filter('woocommerce_persistent_cart_enabled', function () {
    return false;
});

/**
 * Card logos
 * @hook 
 */
function checkout_card_logos() {
    echo '<div class="wc__checkout__cards"><img src="'.esc_url(get_stylesheet_directory_uri()).'/img/cards.png" alt="All major cards accepted"></div>';
}
add_action('woocommerce_review_order_after_submit', 'checkout_card_logos');
add_action('woocommerce_after_cart_totals', 'checkout_card_logos' );

/*--------------------------------------------------------------------------*/
/*    function wc_continue_shopping()
/*    display continue shopping button
/*--------------------------------------------------------------------------*/
function wc_continue_shopping() {
	echo '<div class="wc__continue__shopping"><a href="'.esc_url(home_url()).'">Continue shopping</a></div>';
}
add_action('woocommerce_proceed_to_checkout', 'wc_continue_shopping' );

/**
 * Custom empty cart message
 */
function wc_custom_empty_cart_message() {
    $html = '<div class="wc__empty__basket">';
    $html .= '<div class="message"><figure><i class="fal fa-shopping-cart"></i></figure>';
    $html .= '<p>'.wp_kses_post( apply_filters( 'wc_empty_cart_message', __( 'Your basket is currently empty. Why not treat yourself to one our packages?', 'woocommerce' ) ) ).'</p></div>';
    $html .= '<div class="wc__empty__basket--buttons"><a href="'.esc_url(get_permalink(get_page_by_path('rib-charter'))).'" class="button secondary">Browse packages</a></div>';
    $html .= '</div>';

    echo $html;
}
add_action( 'woocommerce_cart_is_empty', 'wc_custom_empty_cart_message', 10 );

/**
 * Changes the redirect URL for the Return To Shop button in the cart.
 *
 * @return string
 */
function wc_empty_cart_redirect_url() {
	return esc_url(home_url());
}
add_filter( 'woocommerce_return_to_shop_redirect', 'wc_empty_cart_redirect_url' );

//add_action('woocommerce_after_cart', 'but_whats_in_the_cart'); // Dump cart data
function but_whats_in_the_cart() {

    if(get_current_user_id() == 1) {
        pretty_print(WC()->cart->get_cart());
        //pretty_print(WC()->session);
    }

}

// define the woocommerce_applied_coupon callback 
function action_woocommerce_applied_coupon($number, $discounting_amount, $cart_item, $single, $obj ) { 
    if($cart_item['product_id'] == 685) {
        return 0;
    }
}; 
         
// add the action 
//add_action( 'woocommerce_coupon_get_discount_amount', 'action_woocommerce_applied_coupon', 10, 5); 


function custom_cart_totals_coupon_label($text, $coupon_obj) { 
    if(in_array(685, $coupon_obj->get_product_ids())) {

        switch ($coupon_obj->get_discount_type()) {
            case 'percent':
                $amount = $coupon_obj->get_amount().'%';
                break;
            
            default:
                $amount = '&pound;'.$coupon_obj->get_amount();
                break;
        }
        $text .= '<br><br><small style="display: block; font-size: 14px; background: #171b27; padding: 10px; border-radius: 5px; color: #00efe8;">'.$amount.' discount will be applied to remainder of payment.</small>';
    }

    return $text;
}; 
//add_action( 'woocommerce_cart_totals_coupon_label', 'custom_cart_totals_coupon_label', 10, 2); 

