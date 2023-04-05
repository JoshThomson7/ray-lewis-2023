<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce Single Product (Sidebar)
 *
 * @package modules/woocommerce
 * @version 1.0
*/
global $product;
?>
<aside id="wc_sidebar" class="wc-single-product--content-sidebar">
    <article class="wc-single-product__add__to__cart">
        <?php require_once wc_path().'templates/add-to-cart/woo-buy.php'; ?>
    </article><!-- wc-single-product__add__to__cart -->

    <?php if($product->get_type() === 'auction'): ?>

        <article class="wc-single-product--auction-overview">
            <h4>Car overview</h4>
            
            <ul>
                <?php if(get_field('vehicle_mileage')): ?>
                    <li>
                        <i class="fa fa-gauge-high fa-fw"></i>
                        <span><?php echo number_format(get_field('vehicle_mileage'), 0); ?> miles</span>
                    </li>
                <?php endif; ?>
                
                <?php if(get_field('vehicle_transmission')): ?>
                    <li>
                        <i class="fa fa-sliders-up fa-fw"></i>
                        <span><?php the_field('vehicle_transmission'); ?></span>
                    </li>
                <?php endif; ?>

                <?php if(get_field('vehicle_driving_side')): ?>
                    <li>
                        <i class="fa fa-steering-wheel fa-fw"></i>
                        <span><?php the_field('vehicle_driving_side'); ?></span>
                    </li>
                <?php endif; ?>

                <?php if(get_field('vehicle_colour')): ?>
                    <li>
                        <i class="fa fa-brush fa-fw"></i>
                        <span><?php the_field('vehicle_colour'); ?></span>
                    </li>
                <?php endif; ?>

                <?php if(get_field('vehicle_interior_colour')): ?>
                    <li>
                        <i class="fa fa-seat-airline fa-fw"></i>
                        <span><?php the_field('vehicle_interior_colour'); ?></span>
                    </li>
                <?php endif; ?>

                <?php if(get_field('vehicle_engine_size')): ?>
                    <li>
                        <i class="fa fa-engine fa-fw"></i>
                        <span><?php the_field('vehicle_engine_size'); ?> L</span>
                    </li>
                <?php endif; ?>
            </ul>
        </article>

        <?php if($product->get_type() === 'auction'): ?>
            <article class="wc-single-product--auction-history">
                <h4>Bid history</h4>
                <?php require_once wc_path().'templates/single-product/woo-single-product-auction-history.php'; ?>
            </article>
        <?php endif; ?>

    <?php endif; ?>
</aside>