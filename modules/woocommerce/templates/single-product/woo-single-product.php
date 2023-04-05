<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce Single Product (Custom)
 *
 * @package modules/woocommerce
 * @version 1.0
*/

global $product, $post;

// main image
$prod_main_image = null;
if(get_post_thumbnail_id(get_the_ID())) {
    $prod_main_image_id = get_post_thumbnail_id();
    $prod_main_image = vt_resize($prod_main_image_id, '' ,1000, 800, true);
    $prod_main_image = $prod_main_image['url'];

} else {
    $prod_main_image = get_stylesheet_directory_uri().'/img/product-holding.png';
}

// gallery
$gallery_ids = $product->get_gallery_image_ids();

// type
$product_type = $product->get_type();

// price
$price = $product->get_regular_price();

// Product nav is in functions/woo-single-product-nav.php via hook
?>

<div class="wc-single-product--content-wrap has-deps" data-deps='{"js":["woo-add-to-cart"]}' data-deps-path="wc_ajax_object">

    <div class="max__width">

        <?php require_once wc_path().'templates/single-product/woo-single-product-gallery.php'; ?>

        <div class="wc-single-product--content" data-title="<?php the_title(); ?>">

            <div class="wc-single-product--content-info">
                <header>
                    <h1><?php the_title(); ?></h1>
                </header>
                <?php the_content(); ?>
            </div><!-- wc-single-product--content-info -->

            <?php require_once wc_path().'templates/single-product/woo-single-product-sidebar.php'; ?>

            <div class="wc__single__float">
                <div class="wc__single__float__price"><?php echo '&pound;'.$price; ?></div>
                <div class="wc__single__float__button">
                    <a href="#wc_sidebar" class="scroll">Buying options</a>
                </div>
            </div><!-- wc__single__add__to__cart__mobile -->

        </div><!-- wc-single-product--content -->
    </div>

</div><!-- wc-single-product--content-wrap -->
