<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce Single Product (Gallery)
 *
 * @package modules/woocommerce
 * @version 1.0
*/

if(get_post_thumbnail_id(get_the_ID()) || !empty($gallery_ids)): ?>
    <div class="wc-single-product--gallery">
        <?php if($prod_main_image): ?>
            <div class="wc-single-product--gallery-main">
                <figure><img src="<?php echo $prod_main_image; ?>"></figure>
            </div>
        <?php endif; ?>
        
        <div class="wc-single-product--gallery-rest">
            <?php
                $gallery_id_count = 1;
                foreach($gallery_ids as $gallery_id):
                    if($gallery_id_count == 9) break;
                    $gallery_img = vt_resize($gallery_id, '', 400, 252, false);
            ?>
                <figure><img src="<?php echo $gallery_img['url']; ?>"></figure>
            <?php $gallery_id_count++; endforeach; ?>
        </div>
    </div><!-- wc-single-product--gallery -->
<?php endif; ?>