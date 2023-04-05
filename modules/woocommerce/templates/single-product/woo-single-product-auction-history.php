<?php
/**
 * Auction history tab
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $post, $product;

$auction_start_date = new DateTime($product->get_auction_start_time(), wp_timezone());
?>

<?php if ( ( $product->is_closed() === true ) && ( $product->is_started() === true ) ) : ?>

	<p><?php esc_html_e( 'Auction has finished', 'auctions-for-woocommerce' ); ?></p>
	<?php
	if ( $product->get_auction_fail_reason() === 1 ) {
		esc_html_e( 'Auction failed because there were no bids', 'auctions-for-woocommerce' );
	} elseif ( $product->get_auction_fail_reason() === 2 ) {
		esc_html_e( 'Auction failed because item did not make it to reserve price', 'auctions-for-woocommerce' );
	}

	if ( $product->get_auction_closed() === 3 ) {
		?>
		<p><?php esc_html_e( 'Product sold for buy now price', 'auctions-for-woocommerce' ); ?>: <span><?php echo wp_kses_post( wc_price( $product->get_regular_price() ) ); ?></span></p>
	<?php } elseif ( $product->get_auction_current_bider() ) { ?>
		<p><?php esc_html_e( 'Highest bidder was', 'auctions-for-woocommerce' ); ?>: <span><?php echo esc_html( apply_filters( 'auctions_for_woocommerce_displayname', $product->get_auction_current_bider_displayname() ) ); ?></span></p>
	<?php } ?>
<?php endif; ?>	

<div class="auction-history">
    <?php
        $auction_history = apply_filters( 'woocommerce__auction_history_data', $product->auction_history() );
        if(!empty( $auction_history)):
            if($product->is_sealed()):
                echo 'This auction is sealed. When the auction has finished, the bid history and winner will be available to the public.';
            else:
                foreach($auction_history as $history_value):
                    $bid_date = new DateTime($history_value->date, wp_timezone());
    ?>
                    <article>
                        <strong class="price"><?php echo wp_kses_post(wc_price($history_value->bid )); ?></strong>
                        <span class="user"><?php echo esc_html( apply_filters( 'auctions_for_woocommerce_displayname', get_userdata( $history_value->userid )->display_name ) ); ?></span>
                        <small class="date"><?php echo time_ago($bid_date->format('Y-m-d H:i')); ?></small>
                        <?php if('1' === $history_value->proxy): ?>
                            <small class="date">Auto</small>
                        <?php endif; ?>
                    </article>
            <?php endforeach;
            endif;
        endif;
        
        if($product->is_started() === true) {
            $auction_status_text = 'Auction started';
        } else {
            $auction_status_text = 'Auction starting';
            
        }
    ?>
        <article>
            <strong class="price"><?php echo $auction_status_text; ?></strong>
            <small class="date"><?php echo time_ago($auction_start_date->format('j M Y H:i')); ?></small>
        </article>
</div>