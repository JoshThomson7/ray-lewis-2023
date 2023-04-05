<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce Sidebars
 *
 * @package modules/woocommerce
 * @version 1.0
*/

if ( function_exists('register_sidebar') ) {

	// Shop Sidebar
	register_sidebar(array(
		'name' => 'Shop',
		'before_widget' => '<div class="wc__widget">',
		'after_widget' => '</div><!-- wc__widget -->',
	));

}
