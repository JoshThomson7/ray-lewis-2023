<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce Custom Query
 *
 * @package modules/woocommerce
 * @version 1.0
*/

global $woocommerce, $products;

$prod_cat = get_queried_object();
$prod_cat_tax = $prod_cat->taxonomy;

if($prod_cat_tax) {
    $prod_cat_name = (is_shop() ? 'Shop' : $prod_cat->name);
    $prod_cat_slug = $prod_cat->slug;
} else {
    $prod_cat_tax = 'product_mode';
    $prod_cat_slug = 'shop';
}

// Default Woocommerce ordering args
$ordering_args = WC()->query->get_catalog_ordering_args();

// $tax_query = array();
// if(!isset($_GET['s'])) {
//     $tax_query[] = array(
//         'relation' => 'AND',
//         array(
//             'taxonomy'  => $prod_cat_tax,
//             'field'     => 'slug',
//             'terms'     => $prod_cat_slug,
//             'operator'  => 'IN'
//         ),
//         array ( // exclude for hidden products
//             'taxonomy' => 'product_visibility',
//             'field'    => 'name',
//             'terms'    => 'exclude-from-catalog',
//             'operator' => 'NOT IN',

//         )
//     );

//     $not_found_message = '<p>There doesn\'t seem to be any products under <span>'.$prod_cat->name.'</span>.</p>';
// } else {
//     $not_found_message = '<p>No products match your search for <span>'.$_GET['s'].'</span>.</p>';
// }

$pages = $wp_query->max_num_pages;
$args = array(
    'post_type'             => 'product',
    'post_status'           => 'publish',
    //'tax_query'             => $tax_query,
    $prod_cat_tax           => $prod_cat_slug, // only way for it to work with plugin Advanced Post Type Order
    // 's'                     => $_GET['s'],
    'orderby'               => 'title',
    'order'                 => 'ASC',
    'posts_per_page'        => 50
);

// if ( isset( $ordering_args['meta_key'] ) ) {
//     $args['meta_key'] = $ordering_args['meta_key'];
// }
