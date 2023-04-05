<?php
/**
 * WooCommerce Simple Product
 *
 * @package modules/woocommerce
 * @version 1.0
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $woocommerce;
?>

<div class="wc__simple">
    <h3>How many would you like?</h3>

    <div class="wc__simple__qty">
        <input type="number" name="wc_simple" value="1" data-product-id="<?php echo $post->ID; ?>" min="1">
    </div><!-- wc__simple__qty -->
</div><!-- wc__variations -->
