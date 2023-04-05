<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce Add to Cart (Standard)
 *
 * @package modules/woocommerce
 * @version 1.0
*/

global $woocommerce, $product;

$product_type = $product->get_type();

if($product_type === 'auction'):
    require_once("woo-auction.php");
else: ?>
    <form class="wc__add__to__cart__form" data-wc-product-type="<?php echo $product_type; ?>">
        <?php
            require_once("woo-{$product_type}.php");

            // extras
            //require_once('woo-extras.php');

            // quantity + add to cart button
            require_once('woo-add-to-cart.php');
        ?>
    </form>
<?php endif; ?>