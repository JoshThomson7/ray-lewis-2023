<?php
/**
 * ATM Form
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if(get_field('avb_hide_form')) {
    return;
}

$default_amount = ATM_Helpers::get_form_default_amount();
$default_delivery = ATM_Helpers::get_form_default_delivery();

$mode = ATM_Helpers::get_mode();
$amount = FL1_Helpers::param('amount') ?? $default_amount;
$delivery = FL1_Helpers::param('delivery') ?? $default_delivery;
?>
<form id="atm_form" action="">
    <div class="atm-form">
        <div class="atm-form-switch">
            <input type="radio" name="mode" id="atm-buy" value="buy" <?php echo !$mode || $mode === 'buy' ? 'checked' : ''; ?>/>
            <label for="atm-buy">Buying</label>

            <input type="radio" name="mode" id="atm-sell" value="sell" <?php echo $mode === 'sell' ? 'checked' : ''; ?>/>
            <label for="atm-sell">Selling</label>
        </div>

        <h4>Details</h4>

        <div class="atm-form-input--join">
            <div class="atm-form-input amount has-icon">
                <label class="float label-text">Amount to buy</label>
                <div class="currency-icon">£</div>
                <input type="number" value="<?php echo $amount ? $amount : $default_amount; ?>" name="amount" min="10" max="10000" placeholder="<?php echo $default_amount; ?>">
            </div>

            <div class="atm-form-input">
                <label class="float">Choose your currency</label>
                <select name="currency" id="atm_currency_sel" class="chosen" name="atm_currency">
                    <option value="">Select a currency</option>
                </select>
            </div>
        </div>

        <h4>Delivery Options</h4>
        <div class="atm-form-input radio">
            <input type="radio" name="delivery" id="atm-collect" value="collect" <?php echo $delivery === 'collect' ? 'checked' : ''; ?>/>
            <label for="atm-collect">Click &amp; collect</label>

            <input type="radio" name="delivery" id="atm-deliver" value="deliver" <?php echo !$delivery || $delivery === 'deliver' ? 'checked' : ''; ?>/>
            <label for="atm-deliver">Home delivery</label>
        </div>

        <div class="atm-form-input">
            <button type="submit" class="button primary"><span>Compare rates</span></button>
        </div>
    </div>
</form>