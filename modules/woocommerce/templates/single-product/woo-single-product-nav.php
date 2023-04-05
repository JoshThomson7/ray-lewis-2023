<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce Single Product (Nav)
 *
 * @package modules/woocommerce
 * @version 1.0
*/

global $post;
$product_id = $post->ID;
$product = wc_get_product($product_id);
?>
<div class="wc-single-product--nav">
    <div class="max__width">
        <div class="wc-single-product--nav-left">
            <a href="<?php echo esc_url(home_url()); ?>" class="nav-back">
                <i class="fal fa-chevron-left"></i>
            </a>

            <div class="wc-single-product--nav-auction">
                <article>
                    <?php echo wp_kses_post($product->get_price_html()); ?>
                </article>
                
                <article>
                    <?php if ( ( false === $product->is_closed ) && ( true === $product->is_started ) ) : ?>
                        <div class="auction-time "><?php echo wp_kses_post( apply_filters( 'time_text', esc_html__( 'Time left:', 'auctions-for-woocommerce' ), $product_id ) ); ?> 
                            <div class="main-auction auction-time-countdown-nav" data-time="<?php echo esc_attr( $product->get_seconds_remaining() ); ?>" data-auctionid="<?php echo intval( $product_id ); ?>" data-format="<?php echo esc_attr( get_option( 'auctions_for_woocommerce_countdown_format' ) ); ?>"></div>
                        </div>
                    <?php else: ?>
                        <div class="auction-time future"><?php echo wp_kses_post( apply_filters( 'auction_starts_text', esc_html__( 'Auction starts in:', 'auctions-for-woocommerce' ), $product ) ); ?> 
                            <div class="auction-time-countdown future" data-time="<?php echo esc_attr( $product->get_seconds_to_auction() ); ?>" data-format="<?php echo esc_attr( get_option( 'auctions_for_woocommerce_countdown_format' ) ); ?>"></div>
                        </div>
                    <?php endif; ?>
                </article>
            </div>
        </div>

        <div class="wc-single-product--nav-right">
            <?php if(is_user_logged_in()): ?>
                <a href="#wc_sidebar" class="button medium primary scroll"><span>Bid</span></a>
            <?php else: ?>
                <a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" class="button medium primary"><span>Log in to Bid</span></a>
            <?php endif; ?>
        </div>
    </div>
</div>