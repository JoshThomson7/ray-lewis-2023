<?php
/**
 * Travel Money Template
 */

get_header();

$mode = ATM_Helpers::get_mode();
$currency_iso = ATM_Helpers::get_currency('id') ?? 'eur';
$currency = ATM_Helpers::get_currency('symbol') ?? '€';
$currency_name = ATM_Helpers::get_currency('') ?? 'Euro';
$amount = FL1_Helpers::param('amount') ?? ATM_Helpers::get_form_default_amount();
$delivery = FL1_Helpers::param('delivery') ?? ATM_Helpers::get_form_default_delivery();

global $post;
?>

<section class="avb">
    <div class="avb-banners avb-50vh avb-dots-left avb-home avb-has-form">
        <div class="avb-banner avb-50vh" data-type="avb_image">
            <div class="avb-banner__caption">
                <div class="max__width">
                    <div class="avb-banner__caption-wrap">
                        <h1><?php echo ATM_Helpers::landing_page_title(); ?></h1>
                        <p><?php echo ATM_Helpers::landing_page_caption(); ?></p>
                    </div>
                </div>
            </div>
                        
            <figure class="avb-banner__overlay opacity-80"></figure>

            <div class="avb-banner__media">
                <div class="avb-banner__medium image show" style="background-image:url(<?php echo ATM_URL; ?>assets/img/flags/<?php echo $currency_iso; ?>.svg);">
                    <img class="" src="<?php echo ATM_URL; ?>assets/img/flags/<?php echo $currency_iso ?>.svg" alt="<?php echo $currency_name; ?>">
                </div>
            </div>
        </div>
    </div><!-- avb-banners -->

    <?php include ATM_PATH.'templates/atm-form.php'; ?>
</section>

<section class="atm-venue">
    <div class="max__width">
        <div class="atm-stage">
            <?php 
                $url = home_url('wp-json/atm/rates');

                $args = array(
                    'method' => 'GET',
                    'body' => array(
                        'transaction_type' => $mode,
                        'currency_iso' => $currency_iso,
                        'amount' => $amount,
                        'delivery' => $delivery,
                    )
                );
                $response = wp_remote_request($url, $args);

                if(!is_wp_error($response) && ($response['response']['code'] == 200 || $response['response']['code'] == 201)) {
                    $body = wp_remote_retrieve_body($response);
                    $body = $body ? json_decode($body) : array();
                    $rates = !empty($body->rates) ? $body->rates : array();

                    if(!empty($rates)):

                        // Restructure the rates array
                        foreach($rates as $idx => $rate) {
                            $merchant_id = $rate->post_id;
                            $merchant = new ATM_Merchant($merchant_id);

                            if($merchant->exclude()) {
                                unset($rates[$idx]);
                            }

                            $_rate = new ATM_Rate($rate);

                            $postage = $merchant->postage($amount);
                            $amount_cal = $amount;

                            if($postage && $delivery === 'deliver') {
                                if($mode === 'buy') {
                                    $amount_cal = $amount_cal - (float)$postage;
                                    $you_get = $_rate->currency_value() * $amount_cal;
                                } else {
                                    $you_get = ($amount_cal / $_rate->currency_value()) - (float)$postage;
                                }
                            } else { 
                                if($mode === 'buy') {
                                    $you_get = $_rate->currency_value() * $amount_cal;
                                } else {
                                    $you_get = $amount_cal / $_rate->currency_value();
                                }
                            }
                            
                            $rate->you_get = $you_get;
                        }

                        /**
                         * Sort by you_get
                         * @see https://stackoverflow.com/questions/15941137/sort-multi-dimensional-array-by-decimal-values
                         * @see https://www.php.net/usort
                         */
                        usort($rates, function($a, $b) {
                            return $b->you_get <=> $a->you_get;
                        });

                        // Loop through rates
                        foreach($rates as $rate):
                            $merchant_id = $rate->post_id;
                            $merchant_name = $rate->merchant_id;

                            $merchant = new ATM_Merchant($merchant_id);
                            $merchant_logo = $merchant->image();
                            $merchant_url = $merchant->redirect_url($mode, true);

                            $postage = $merchant->postage($amount);

                            $_rate = new ATM_Rate($rate);

                            // Tooltip
                            $tooltip = '';
                            if($postage) {
                                if($mode === 'buy') {
                                    $tooltip = 'We deduct the postage cost from the amount of currency you want to convert and then time the remaining amount by the conversion rate';
                                } else {
                                    $tooltip = 'We divide the amount you want to convert by the conversion rate and then deduct the postage cost from the result';
                                }
                            }                            
                        ?>

                            <article class="atm-merchant">
                                <div class="atm-merchant-logo">
                                    <?php if(!empty($merchant_logo)): ?>
                                        <figure><img src="<?php echo $merchant_logo['url']; ?>" alt="<?php echo $merchant_name; ?>"></figure>
                                    <?php endif; ?>
                                </div>

                                <div class="atm-merchant-info">
                                    <h3><?php echo $merchant_name; ?></h3>
                                    <?php echo $merchant->description() ?? ''; ?>
                                </div>

                                <div class="atm-merchant-value rate">
                                    <div class="value">
                                        <small><?php echo strtoupper($currency_iso); ?> Rate</small>
                                        <?php echo number_format($_rate->currency_value(), 4, '.', ''); ?>
                                    </div>
                                </div>

                                <div class="atm-merchant-value you-get">
                                    <div class="value">
                                        <small>You get</small>
                                        <span class="currency-symbol"><?php echo $mode === 'buy' ? $currency : '&pound;'; ?></span><?php echo number_format($rate->you_get, 2, '.', ','); ?>
                                    </div>

                                    <?php if($delivery === 'deliver'): ?>
                                        <div class="postage <?php echo !$postage ? 'free' : 'tooltip'; ?>" title="<?php echo $tooltip; ?>">
                                            <?php echo !$postage ? 'Free' : 'Inc. &pound;'.number_format((float)$merchant->postage($amount), 2, '.', ','); ?> Delivery
                                        </div>
                                    <?php endif; ?>

                                    <div class="rate-mobile">
                                        <?php echo strtoupper($currency_iso); ?> Rate = <?php echo number_format($_rate->currency_value(), 4, '.', ''); ?>
                                    </div>

                                    <a href="<?php echo $merchant_url ? $merchant_url : '#'; ?>" class="button primary" target="_blank"
                                    onclick="gtag('event', 'outbound_click', {
                                        'supplier': '<?php echo $merchant_name; ?>',
                                        'screen_name': 'Travel Money Search Results',
                                        'currency_iso': '<?php echo $currency_iso; ?>',
                                        'currency_name': '<?php echo $currency_name; ?>',
                                        'amount': '<?php echo $amount; ?>',
                                        'type': '<?php echo $mode; ?>',
                                    });">
                                        <span><?php echo ucwords($mode); ?> now</span>
                                    </a>
                                </div>
                            </article>

                        <?php
                        endforeach;

                    else: ?>
                        <div class="not__found">
                            <figure><i class="fa-solid fa-magnifying-glass-dollar"></i></figure>
                            <h3>No rates found</h3>
                            <p>We couldn't find any rates matching your criteria.<br/>Try changing some options like entering a lower amount.</p>
                        </div>
                    <?php endif;
                }
            ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
