<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce Enqueue
 *
 * @package modules/woocommerce
 * @version 1.0
*/

/*
* Load scripts to wp_head()
*/
function wc_og_tags() {
    if(is_singular('product')) {
        global $post;

        $product_img = get_the_post_thumbnail_url($post->ID, 'full');

        echo '<meta property="og:title" content="'.get_the_title($post->ID).'"/>';
        echo $product_img ? '<meta property="og:image" content="'.esc_url($product_img).'"/>' : '';
        echo '<meta property="og:site_name" content="'.get_bloginfo('name').'"/>';
        echo '<meta property="og:description" content="'.get_the_title($post->ID).'"/>';
    }
}
add_action( 'wp_head', 'wc_og_tags');

/**
 * Custom Woo enqueue
 */
function wc_enqueue_scripts_and_style() {

    // scripts
    wp_localize_script('custom-js', 'wc_ajax_object', array(
    	'ajax_url' => admin_url( 'admin-ajax.php' ),
        'site_url' => esc_url(home_url()),
        'jsPath' => wc_path(true).'assets/js/'
    ));

}

add_action( 'wp_enqueue_scripts', 'wc_enqueue_scripts_and_style', 20);