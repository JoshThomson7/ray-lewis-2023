<?php
/**
 * WooCommerce Styles
 *
 * @package modules/woocommerce
 * @version 1.0
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
* Remove all styles
*/
add_filter( 'woocommerce_enqueue_styles', '__return_false' );

/*
* Remove shop title
*/
function woo_hide_page_title() {
	return false;
}
add_filter( 'woocommerce_show_page_title' , 'woo_hide_page_title' );

/*
* Show trailing zeros on prices - default is to hide it.
*/
// Show trailing zeros on prices.
function wc_hide_trailing_zeros( $trim ) {
    return false;
}
add_filter( 'woocommerce_price_trim_zeros', 'wc_hide_trailing_zeros', 10, 1 );

/*
* Remove breadcrumbs
*/
remove_action( 'woocommerce_before_main_content','woocommerce_breadcrumb', 20, 0);

/**
 * wc_variations_price()
 * 
 * Gets rid of 0.00 price variations and adds FROM
 * 
 * @param  string $price
 * @param  object $product
 * @return string
 */
function wc_variations_price( $price, $product ) {
    
    if(!is_admin() || wp_doing_ajax()) {
        $prefix = sprintf('%s ', __('From', 'iconic'));

        $product_type = $product->get_type();
    
        $min_price_regular = $product->get_variation_regular_price( 'min', true );
        $min_price_sale    = $product->get_variation_sale_price( 'min', true );
        $max_price = $product->get_variation_price( 'max', true );
        $min_price = $product->get_variation_price( 'min', true );

        // Skip free variations
        if($min_price === '0.00' || $min_price === '0.00') { 
            $variations = $product->get_children();

            foreach($variations as $variation) { 
                $variation_price = get_post_meta($variation, '_regular_price', true);

                if($variation_price == 0) { continue; }
                
                $variation_prices[] = $variation_price;
            }
            
            $min_price_regular = min($variation_prices);
            $min_price_sale = min($variation_prices);

        }

        $per_month = '';
        if($product_type === 'variable-subscription') {
            $per_month = ' <small class="wc__price__frequency">per month</small>';
        }
    
        $price = ( $min_price_sale == $min_price_regular ) ? 
            wc_price( $min_price_regular ) . $per_month :
            '<del>' . wc_price( $min_price_regular ) . '</del>' . '<ins>' . wc_price( $min_price_sale ) . $per_month.'</ins>';

        $price = ( $min_price == $max_price ) ? $price : sprintf('%s%s', $prefix, $price);

    }
 
    return $price;
 
}
 
add_filter( 'woocommerce_variable_sale_price_html', 'wc_variations_price', 10, 2 );
add_filter( 'woocommerce_variable_price_html', 'wc_variations_price', 10, 2 );
add_filter( 'woocommerce_variable_subscription_price_html', 'wc_variations_price', 10, 2 );

/**
 * Display price as per month
 * 
 * @param int $wc_price
 */
function wc_cart_totals($wc_price){   
    return $wc_price.' <small style="display: block;">per month</small>';
}
//add_filter('woocommerce_cart_total', 'wc_cart_totals', 10, 1);


/*
* Allow HTML in term (category, tag) descriptions
*
* By default WordPress strips HTML from category descriptions.
* You can get around this by adding a small snippet to your theme functions.php file:
*/

// foreach ( array( 'pre_term_description' ) as $filter ) {
//     remove_filter( $filter, 'wp_filter_kses' );
// }
//
// foreach ( array( 'term_description' ) as $filter ) {
//     remove_filter( $filter, 'wp_kses_data' );
// }
