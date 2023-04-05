<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce Loop (Grid)
 *
 * @package modules/woocommerce
 * @version 1.0
*/

global $woocommerce, $post, $product;

// product_cat
$product_cats = get_the_terms(get_the_ID(), 'product_cat');
$get_product_cats = array();
foreach ( $product_cats as $product_cat ) {
    $get_product_cats[] = $product_cat->name;
}
$all_product_cats = join(', ', $get_product_cats);
?>
    <article>
        <div class="product__inner">
            <a href="<?php the_permalink(); ?><?php echo $page_slug; ?>" title="<?php the_title(); ?>">
                <figure class="wc__product__img">
                    <img src="<?php echo $prod_image['url']; ?>" alt="<?php the_title(); ?>">
                </figure>
            </a>

            <div class="wc__content__content">
                <h2><?php the_title(); ?></h2>
                <?php if($price_html = $product->get_price_html()): ?><?php echo $price_html; ?><?php endif; ?>
                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">More details</a>
            </div><!-- wc__content__content -->
        </div>
    </article>
