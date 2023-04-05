<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce Sidebar
 *
 * @package modules/woocommerce
 * @version 1.0
*/

if( is_shop() || is_tax(array('product_cat', 'product_tag'))  || is_search()):
?>
    <div class="wc__sidebar">
        <div class="wc__sidebar__close">
            <a href="#" class="wc__sidebar__hide"><i class="ion-close-circled"></i> Close</a>
        </div><!-- wc__sidebar__close -->

        <div class="wc__widget wc__sidebar__search">
            <h2>Search</h2>

            <form role="search" method="get" id="searchform" class="searchform" action="<?php echo esc_url(home_url()); ?>">
    			<div>
    				<label class="screen-reader-text" for="s">Search for:</label>
    				<input type="text" value="" name="s" id="s">
    				<button type="submit" id="searchsubmit"><i class="ion-ios-search-strong"></i></button>
    			</div>
    		</form>
        </div><!-- wc__widget -->

        <?php
            $args = array(
                'taxonomy'     => 'product_cat',
                'hide_empty'   => false,
                'title_li'     => ''
            );
        ?>
        <div class="wc__widget">
            <h2>Categories</h2>
            <ul>
                <?php wp_list_categories($args); ?>
            </ul>
        </div><!-- wc__widget -->

        <?php
            /*$product_tags = get_terms( array(
                'taxonomy' => 'product_tag', // Taxonomy to return. Valid values are 'category', 'post_tag' or any registered taxonomy.
                'show_option_none' => 'Select product type',
                'show_count' => 1,
                'orderby' => 'name',
                'value_field' => 'slug',
                'echo' => 0
                )
            );

            if($product_tags):
        ?>
            <div class="wc__widget">
                <h2>Tags</h2>
                <select onchange="document.location.href=this.options[this.selectedIndex].value;">
                    <option>Select tag</option>
                    <?php foreach($product_tags as $product_tag): ?>
                        <option value="<?php echo get_term_link($product_tag); ?>"><?php echo $product_tag->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div><!-- wc__widget -->
        <?php endif;*/ ?>
    </div><!-- wc__sidebar -->
<?php endif; ?>
