<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce Cart Empty
 *
 * @package modules/woocommerce
 * @version 1.0
*/

$cart_empty_args = array(
    'post_type' => 'product',
    'post_status' => 'publish',
    'posts_per_page' => 6,
    'poruct_mode' => 'shop',
    'meta_query' => array(
        array(
            'key' => '_visibility',
            'compare' => 'NOT EXISTS'
        )
    ),
    'cache_results' => false,
    'orderby' => 'rand',
    'no_found_rows' => true
);

$cart_empty_query = new WP_Query($cart_empty_args);

echo '<section class="deals">';
echo '<div class="deals__wrapper do__flex">';

// Loop.
while($cart_empty_query->have_posts() ) : $cart_empty_query->the_post();

$_product = wc_get_product(get_the_ID());

$merchant_obj = get_field('deal_merchant');
$merchant_id = $merchant_obj->ID;

$attachment_id = get_post_thumbnail_id();
$prod_image = vt_resize( $attachment_id,'' , 700, 585, true);
?>

    <article class="card third">

        <div class="card__inner">

            <div class="deal__images">
                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" style="background-image: url(<?php echo $prod_image['url']; ?>);" class="deal__image"></a>
            </div><!-- featured__deal images -->

            <div class="deal__details">
                <h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
                <h4><i class="fal fa-map-marker-alt"></i><?php echo get_the_title($merchant_id); ?></h4>

                <div class="deal__meta">
                    <div class="deal__category">
                        <a href="#">Food</a>
                    </div><!-- deal__category -->

                    <div class="deal__price">
                        <?php if($_product->is_on_sale()): ?>
                            <div class="old__price">
                                <?php echo $_product->get_regular_price(); ?>
                            </div><!-- old__price -->
                        <?php endif; ?>

                        <div class="the__price">
                            <?php echo $_product->get_price_html(); ?>
                        </div><!-- the__price -->
                    </div><!-- deal__price -->
                </div><!-- deal__meta -->
            </div><!-- deal__details -->

        </div><!-- card__inner -->
    </article><!-- card third -->

<?php endwhile; wp_reset_postdata(); ?>
</div>
</section>