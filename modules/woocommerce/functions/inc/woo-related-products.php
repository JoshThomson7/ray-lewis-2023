<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Woo Related products
 *
 * @author  FL1 Digital
 * @package WooCommerce
 *
*/
function wc_related_products($post_id) {

	$post_type = get_post_type($post_id);

    $product_cats = wp_get_post_terms($post_id, 'product_cat', array("fields" => "ids"));
    //$product_tags = wp_get_post_terms($post_id, 'product_tag', array("fields" => "ids"));
    
	$args = array (
		'post_type' => array($post_type),
        'tax_query' => array(
            'relation' => 'AND',
            array(
                'taxonomy'  => 'product_cat',
                'field'     => 'id',
                'terms'     => $product_cats,
                'operator'  => 'IN'
            )
        ),
		'post__not_in' => array($post_id),
		'posts_per_page'=> 4,
		'orderby'=> 'rand'
	);

    // Pass the query
	$related_products = new WP_Query($args);

    if($related_products->have_posts()):
    
        // Loop.
        while($related_products->have_posts() ) : $related_products->the_post();

        $_product = wc_get_product(get_the_ID());

        $merchant_obj = get_field('deal_merchant');

        if($merchant_obj) {
            $merchant_id = $merchant_obj->ID;
        }

        $attachment_id = get_post_thumbnail_id();
        $prod_image = vt_resize( $attachment_id,'' , 700, 585, true);

        $product_cats = wp_get_post_terms(get_the_ID(), 'product_cat', array('fields' => 'all'));
        $product_cat = $product_cats[0];
        $product_cat_link = get_term_link( $product_cat, 'product_cat');
    ?>

        <article class="card">

            <div class="card__inner">

                <div class="deal__images">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" data-src="<?php echo $prod_image['url']; ?>" class="deal__image blazy"></a>
                </div><!-- featured__deal images -->

                <div class="deal__details">
                    <h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
                    <?php if($merchant_obj): ?>
                        <h4><i class="fal fa-map-marker-alt"></i><?php echo get_the_title($merchant_id); ?></h4>
                    <?php endif; ?>

                    <div class="deal__meta">
                        <div class="deal__category">
                            <a href="<?php echo $product_cat_link; ?>" title="View more on <?php echo $product_cat->name; ?>"><?php echo $product_cat->name; ?></a>
                        </div><!-- deal__category -->

                        <div class="deal__price">
                            <div class="the__price">
                                <?php echo $_product->get_price_html(); ?>
                            </div><!-- the__price -->
                        </div><!-- deal__price -->
                    </div><!-- deal__meta -->
                </div><!-- deal__details -->

            </div><!-- card__inner -->
        </article><!-- card third -->
    
    <?php endwhile; wp_reset_postdata();

    else: 
        // If something goes wrong, there are no related posts, return false.
	    return false;
    endif;
}

