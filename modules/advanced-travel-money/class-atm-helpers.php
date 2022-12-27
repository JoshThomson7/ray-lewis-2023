<?php
/**
 * ATM Helpers
 *
 * Helper static methods for the Advanced Travel Money module.
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class ATM_Helpers {

    public static function landing_page_title() {

        $currency = ATM_Helpers::get_currency('text', true);

        if($currency) {
            return 'Best '.$currency.' Exchange Rates';

        } else {
            $mode = ATM_Helpers::get_mode(true, true);
            return 'Best '.$mode.' Exchange Rates';
        }

    }

    public static function landing_page_caption() {

        $mode = ATM_Helpers::get_mode();
        $currency = ATM_Helpers::get_currency('text');

        if($currency && $mode) {
            return 'Compare and '.$mode.' the best rates for '.$currency.' online';

        } else {

            if($currency && !$mode) {
                return 'Compare the best rates for '.$currency.' online';
            }
            
            if(!$currency && $mode) {
                return 'Compare and '.$mode.' the best rates online';
            }
        }

    }   

    public static function get_mode($caps = false, $gerund = false) {

        $mode = FL1_Helpers::param('atm_mode', 'query_vars') ?? 'buy';

        if(!$mode) {
            $mode = FL1_Helpers::param('mode');
        }

        if($mode && $gerund) {
            $mode .= 'ing';
        }

        return $caps ? ucwords($mode) : $mode;

    }

    public static function get_currency($return = 'text', $singular = false) {

        $currency = FL1_Helpers::param('atm_currency', 'query_vars') ?? '';

        if(!$currency) {
            $currency = FL1_Helpers::param('currency') ?? ATM_Helpers::get_form_default_currency();
            $currency = FL1_Helpers::search_array(self::currencies(), 'id', $currency);
            $currency = !empty($currency) ? $currency[0][$return] : ATM_Helpers::get_form_default_currency();

        } else {

            $currency = str_replace('-', ' ', $currency);
            $currency = ucwords($currency);
            $currency = str_replace('Us ', 'US ', $currency);
            $currency = FL1_Helpers::search_array(self::currencies(), 'text', $currency);
            $currency = !empty($currency) ? $currency[0][$return] : '';
            

        }

        if($currency && $return === 'text') {
            if($singular && substr($currency, -1) == 's') {
                $currency = substr($currency, 0, -1);
            }
        }

        return $currency;

    }

    public static function currencies($lower = false) {

        $currencies = array(
            array('id' => 'eur', 'text' => 'Euros', 'symbol' => '€'),
            array('id' => 'usd', 'text' => 'US Dollars', 'symbol' => '$'),
            array('id' => 'ars', 'text' => 'Argentine Pesos', 'symbol' => '$'),
            array('id' => 'aud', 'text' => 'Australian Dollars', 'symbol' => '$'),
            array('id' => 'bsd', 'text' => 'Bahamian Dollars', 'symbol' => '$'),
            array('id' => 'bhd', 'text' => 'Bahraini Dinars', 'symbol' => '$'),
            array('id' => 'bdt', 'text' => 'Bangladeshi Taka', 'symbol' => '$'),
            array('id' => 'bbd', 'text' => 'Barbados Dollars', 'symbol' => '$'),
            array('id' => 'bmd', 'text' => 'Bermudian Dollars', 'symbol' => '$'),
            array('id' => 'brl', 'text' => 'Brazilian Reals', 'symbol' => 'R$'),
            array('id' => 'bnd', 'text' => 'Brunei Dollars', 'symbol' => '$'),
            array('id' => 'bgn', 'text' => 'Bulgarian Lev', 'symbol' => 'лв'),
            array('id' => 'cad', 'text' => 'Canadian Dollars', 'symbol' => '$'),
            array('id' => 'kyd', 'text' => 'Cayman Islands Dollars', 'symbol' => '$'),
            array('id' => 'xpf', 'text' => 'Central Pacific francs', 'symbol' => '$'),
            array('id' => 'clp', 'text' => 'Chilean Pesos', 'symbol' => '$'),
            array('id' => 'cny', 'text' => 'Chinese Yuan', 'symbol' => '¥'),
            array('id' => 'cop', 'text' => 'Colombian Pesos', 'symbol' => '$'),
            array('id' => 'crc', 'text' => 'Costa Rica Colons', 'symbol' => '$'),
            array('id' => 'hrk', 'text' => 'Croatian Kuna', 'symbol' => 'kn'),
            array('id' => 'czk', 'text' => 'Czech Koruna', 'symbol' => 'Kč'),
            array('id' => 'dkk', 'text' => 'Danish Krone', 'symbol' => 'kr'),
            array('id' => 'dop', 'text' => 'Dominican Pesos', 'symbol' => '$'),
            array('id' => 'xcd', 'text' => 'East Caribbean Dollars', 'symbol' => '$'),
            array('id' => 'egp', 'text' => 'Egyptian Pounds', 'symbol' => '£'),
            array('id' => 'fjd', 'text' => 'Fiji Dollars', 'symbol' => '$'),
            array('id' => 'gmd', 'text' => 'Gambian Dalasi', 'symbol' => '$'),
            array('id' => 'gtq', 'text' => 'Guatemalan Quetzales', 'symbol' => 'Q'),
            array('id' => 'hnl', 'text' => 'Honduran Lempiras', 'symbol' => 'L'),
            array('id' => 'hkd', 'text' => 'Hong Kong Dollars', 'symbol' => '$'),
            array('id' => 'huf', 'text' => 'Hungarian Forints', 'symbol' => 'Ft'),
            array('id' => 'isk', 'text' => 'Icelandic Krona', 'symbol' => 'kr'),
            array('id' => 'inr', 'text' => 'Indian Rupees', 'symbol' => '₹'),
            array('id' => 'idr', 'text' => 'Indonesian Rupiahs', 'symbol' => 'Rp'),
            array('id' => 'ils', 'text' => 'Israeli Shekels', 'symbol' => '₪'),
            array('id' => 'jmd', 'text' => 'Jamaican Dollars', 'symbol' => '$'),
            array('id' => 'jpy', 'text' => 'Japanese Yen', 'symbol' => '¥'),
            array('id' => 'jod', 'text' => 'Jordanian Dinars', 'symbol' => '$'),
            array('id' => 'kes', 'text' => 'Kenyan Shillings', 'symbol' => '$'),
            array('id' => 'kwd', 'text' => 'Kuwaiti Dinars', 'symbol' => '$'),
            array('id' => 'lbp', 'text' => 'Lebanese Pounds', 'symbol' => '£'),
            array('id' => 'myr', 'text' => 'Malaysian Ringgits', 'symbol' => 'RM'),
            array('id' => 'mur', 'text' => 'Mauritian Rupees', 'symbol' => 'Rs'),
            array('id' => 'mxn', 'text' => 'Mexican Pesos', 'symbol' => '$'),
            array('id' => 'mad', 'text' => 'Moroccan Dirham', 'symbol' => 'DH'),
            array('id' => 'nad', 'text' => 'Namibian Dollars', 'symbol' => '$'),
            array('id' => 'nzd', 'text' => 'New Zealand Dollars', 'symbol' => '$'),
            array('id' => 'nok', 'text' => 'Norwegian Krone', 'symbol' => 'kr'),
            array('id' => 'omr', 'text' => 'Omani Rials', 'symbol' => 'ر.ع.'),
            array('id' => 'pkr', 'text' => 'Pakistani Rupees', 'symbol' => 'Rs'),
            array('id' => 'pgk', 'text' => 'Papua New Guinean Kina', 'symbol' => '$'),
            array('id' => 'pen', 'text' => 'Peruvian Nuevo Sol', 'symbol' => 'S/'),
            array('id' => 'php', 'text' => 'Philippine Pesos', 'symbol' => '₱'),
            array('id' => 'pln', 'text' => 'Polish Zloty', 'symbol' => 'zł'),
            array('id' => 'qar', 'text' => 'Qatar Riyals', 'symbol' => 'ر.ق'),
            array('id' => 'ron', 'text' => 'Romanian Leu', 'symbol' => 'lei'),
            array('id' => 'rub', 'text' => 'Russian Rubles', 'symbol' => '₽'),
            array('id' => 'sar', 'text' => 'Saudi Riyals', 'symbol' => 'ر.س'),
            array('id' => 'sgd', 'text' => 'Singapore Dollars', 'symbol' => '$'),
            array('id' => 'zar', 'text' => 'South African Rand', 'symbol' => 'R'),
            array('id' => 'krw', 'text' => 'South Korean Won', 'symbol' => '₩'),
            array('id' => 'lkr', 'text' => 'Sri Lankan Rupees', 'symbol' => 'Rs'),
            array('id' => 'sek', 'text' => 'Swedish Krona', 'symbol' => 'kr'),
            array('id' => 'chf', 'text' => 'Swiss Francs', 'symbol' => 'Fr'),
            array('id' => 'twd', 'text' => 'Taiwan Dollars', 'symbol' => '$'),
            array('id' => 'thb', 'text' => 'Thai Baht', 'symbol' => '฿'),
            array('id' => 'ttd', 'text' => 'Trinidad Dollars', 'symbol' => '$'),
            array('id' => 'try', 'text' => 'Turkish Lira', 'symbol' => '₺'),
            array('id' => 'uah', 'text' => 'Ukrainian Hryvnia', 'symbol' => '₴'),
            array('id' => 'aed', 'text' => 'United Arab Emirates Dirham', 'symbol' => 'د.إ'),
            array('id' => 'uyu', 'text' => 'Uruguayan Pesos', 'symbol' => '$'),
            array('id' => 'vnd', 'text' => 'Vietnamese Dongs', 'symbol' => '₫'),
        );

        if($lower) {
            $currencies = array_map(function($currency) {
                $currency['text'] = strtolower($currency['text']);
                return $currency;
            }, $currencies);
        }

        return $currencies;

    }

    /**
     * Returns default form amount from settings
     * @return int
     */
    public static function get_form_default_amount() {

        return get_field('atm_settings_form_default_amount', 'option') ?? 500;

    }

    /**
     * Returns default form currency from settings
     * @return int
     */
    public static function get_form_default_currency() {
        
        $avb_currency = get_field('avb_default_currency');

        return $avb_currency ? $avb_currency : get_field('atm_settings_form_default_currency', 'option');

    }

    /**
     * Returns default delivery type from settings
     * @return string
     */
    public static function get_form_default_delivery() {

        return get_field('atm_settings_form_default_delivery', 'option') ?? 'collect';

    }

    /**
     * Returns whether the feed is in dev mode
     * @return string
     */
    public static function is_feed_in_dev_mode() {

        return get_field('atm_settings_feed_dev_mode', 'option');

    }

    /**
     * Returns feed buy URL
     * @return string
     */
    public static function get_feed_buy_url() {

        return get_field('atm_settings_feed_buy_url', 'option');

    }

    /**
     * Returns feed sell URL
     * @return string
     */
    public static function get_feed_sell_url() {

        return get_field('atm_settings_feed_sell_url', 'option');

    }

}

