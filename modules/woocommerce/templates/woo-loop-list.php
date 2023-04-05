<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce Loop (Standard)
 *
 * @package modules/woocommerce
 * @version 1.0
*/

global $woocommerce;

// product_cat
$product_cats = get_the_terms(get_the_ID(), 'product_cat');
$get_product_cats = array();
foreach ( $product_cats as $product_cat ) {
    $get_product_cats[] = sprintf('<a href="%s">%s</a>',
        esc_url( get_term_link($product_cat)),
        esc_html( sanitize_term_field( 'name', $product_cat->name, $product_cat->term_id, 'product_cat', 'display' ) )
    );
}
$all_product_cats = join(', ', $get_product_cats);

// prod_tag
$prod_tags = get_the_terms(get_the_ID(), 'product_tag');
if($prod_tags) {
    $get_prod_tags = array();
    foreach ( $prod_tags as $prod_tag ) {
        $get_prod_tags[] = sprintf( '<a href="%s">%s</a>',
            esc_url( get_term_link($prod_tag)),
            esc_html( sanitize_term_field( 'name', $prod_tag->name, $prod_tag->term_id, 'product_tag', 'display' ) )
        );
    }
    $all_prod_tags = join(', ', $get_prod_tags);
}
?>
    <article>
        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="wc__product__img"<?php echo $prod_image; ?>></a>

        <div class="wc__product__content">
            <h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
            <h3><?php if($price_html = $product->get_price_html()): ?><?php echo $price_html; ?><?php endif; ?></h3>

            <div class="wc__product__meta">
                <div class="wc__meta__data">
                    <h4>Categories</h4>
                    <p><?php echo $all_product_cats; ?></p>
                </div><!-- wc__meta__data -->

                <div class="wc__meta__data">
                    <h4>Tags</h4>
                    <p><?php echo $all_prod_tags; ?></p>
                </div><!-- wc__meta__data -->

                <div class="wc__meta__data">
                    <h4>SKU</h4>
                    <p><?php echo $product->get_sku(); ?></p>
                </div><!-- wc__meta__data -->

                <div class="wc__meta__data">
                    <h4>Stock</h4>
                    <p><?php echo $product->get_stock_quantity(); ?></p>
                </div><!-- wc__meta__data -->
            </div><!-- wc__product__meta -->

            <a href="<?php the_permalink(); ?>" title="Full details" class="button has__icon">
                <i class="ion-plus"></i>
                <span>Full details</span>
            </a>
        </div><!-- wc__product__content -->
    </article>
