<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce Custom Archive Product
 *
 * @package modules/woocommerce
 * @version 1.0
*/

global $woocommerce, $products;

/*
 * Include our custom query by default
 * if we aren't using Woo AJAX filter plugin
*/
if( !function_exists('br_get_woocommerce_version') || !isset($_GET['filters']) ) {
    require_once('woo-query.php');
    $products = new WP_Query($args);
}

if($products->have_posts()):
?>

    <?php if(!is_shop()): ?>
        <div class="wc__page__title">
            <h1><?php echo $prod_cat_name; ?></h1>

            <?php woocommerce_catalog_ordering(); ?>
        </div><!-- page__title -->
    <?php endif; ?>

    <div class="wc__products wc__products__grid">
        <?php
            global $product;
            while($products->have_posts()) : $products->the_post();

            if(get_post_thumbnail_id(get_the_ID())) {
                $attachment_id = get_post_thumbnail_id(get_the_ID());
                $prod_image = vt_resize($attachment_id,'' , 700, 700, true);

            } else {
                $prod_image = ' style="background-image:url('.get_stylesheet_directory_uri().'/img/product-holding.png;);"';
            }

            // loop
            include(wc_path().'templates/woo-loop-grid.php');

            endwhile; woocommerce_reset_loop(); wp_reset_postdata();
        ?>

    </div><!-- wc__products -->

<?php else: ?>

    <div class="wc__products wc__products__not__found">
        <figure>

        </figure>

        <h2>No products found.</h2>
        <?php echo $not_found_message; ?>
        <p>If you are looking for something in particular and can't find it, please do not hesitate to <a href="<?php esc_url(home_url()); ?>/contact/">get in touch</a>.</p>
    </div><!-- wc__products__not__found -->

<?php endif; ?>
