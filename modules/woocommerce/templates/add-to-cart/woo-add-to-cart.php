<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce Add to Cart (Quantity + Button)
 *
 * @package modules/woocommerce
 * @version 1.0
*/

global $woocommerce, $product;
?>

<div class="wc__qty__add__to__cart">
    <button type="submit" class="wc__add__to__cart__button button">Add to basket</button>
</div><!-- wc__qty__add__to__cart -->

<div class="wc__deal__continue">
    <a href="<?php echo wc_get_cart_url(); ?>" class="view__cart"><i class="fa fa-shopping-cart"></i> View cart</a>
    <a href="<?php echo wc_get_checkout_url(); ?>" class="go__to__checkout"><i class="fa fa-credit-card"></i> Checkout</a>
</div><!-- wc__deal__continue -->

<div class="wc__add__to__cart__notice"></div>
