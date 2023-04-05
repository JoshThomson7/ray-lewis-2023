<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce Variations
 *
 * @package modules/woocommerce
 * @version 1.0
*/

global $post, $woocommerce, $product;

$variations = $product->get_available_variations();
$product_type = $product->get_type();

// echo '<pre>';
// print_r($variations);
// echo '</pre>';

// get product attributes
$attributes = get_post_meta($product->get_id() , '_product_attributes');
foreach($attributes[0] as $key => $value) {
    $the_attribute = $attributes[0][$key]['name'];
}
?>

<div class="wc__variable">
    <h4><?php echo $the_attribute; ?></h4>
    <p>Please select at least one variation.</p>

    <?php
        $variation_count = 1;
        foreach($variations as $key => $value):

        // Resets
        $child_tickets_qty_text = '';
        $child_tickets_only_text = '';
        $child_tickets_only_data = '';
        $payment_plan_deposit_text = '';
        $payment_plan_data = '';

        // Handle price
        if(!empty($value['price_html'])) {
            $price_html = $value['price_html'];
        } else { 
            $price_html = '<span class="price"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">&pound;</span>'.number_format((float)$value['display_price'], 2, '.', '').'</span></span>';
        }

        // Max quantity
        $max_qty = $value['max_qty'];

        // custom or pre-defined attributes?
        if(!empty($value['attributes'])) {
            foreach ($value['attributes'] as $attr_key => $attr_value) {
                $attribute_slug = $attr_key;
                
                $attribute_name = $attr_value;
                if (strpos($attr_value, ' -') !== false) {
                    $attribute_name = substr(ucfirst($attr_value), 0, strpos(ucfirst($attr_value), " -"));
                }
            }

            $variation_price = $value['display_regular_price'];

            if($product_type !== 'variable-subscription') {

                if($variation_price == 0) {

                    $variation_price_html = '
                        <span class="price">
                            <span class="woocommerce-Price-amount amount">Free</span>
                            <span class="subscription-details">'.$child_tickets_only_text.$child_tickets_qty_text.'</span>
                        </span>';
                } else {

                    $variation_price_html = $price_html;
                    
                }

            }

        } else {
            $attribute_name = implode('/', $value['attributes']);
            $variation_price_html = $price_html;
        }

        // Stock
        $out_of_stock = '';
        $out_of_stock_tooltip = '';
        if($value['is_in_stock'] != 1) { 
            $out_of_stock = ' wc__out__of__stock tooltip';
            $out_of_stock_tooltip = ' data-tooltipster=\'{"side":"top"}\' title="Sold out"';
        }

        // Description
        $variation_description = '';
        if(!empty($value['variation_description'])) {
            $variation_description = '<span class="variation-description">'.strip_tags($value['variation_description']).'</span>';
        }
    ?>
        <div class="wc__variation<?php echo $out_of_stock; ?>"<?php echo $out_of_stock_tooltip; ?>>
            <input id="<?php echo $value['variation_id']; ?>" type="checkbox" name="wc_variation" value=""
            data-product-id="<?php echo $post->ID; ?>"
            data-variation-id="<?php echo $value['variation_id']; ?>"
            data-variation-qty="1"
            data-variation-name="<?php echo $attribute_name; ?>"
            data-variation-slug="<?php echo $attribute_slug; ?>"
            data-variation-price="<?php echo $variation_price; ?>"
            <?php echo $child_tickets_only_data.$payment_plan_data; ?>>

            <label for="<?php echo $value['variation_id']; ?>">
                <div class="wc__variation__meta">
                    <h5><?php echo $attribute_name;?><?php echo $variation_price_html.$variation_description;?></h5>
                </div><!-- wc__variation__meta -->

                <div class="wc__variation__quantity">
                    <input type="number" name="wc_variation_quantity" value="1" min="1" max="<?php echo $max_qty; ?>">
                </div><!-- wc__variation__quantity -->
            </label>
        </div><!-- wc__variation -->
    <?php $variation_count++; endforeach; ?>

</div><!-- wc__variable -->
