<?php
/**
 * ATM Merchant
 *
 * @author FL1 Digital
 * @version 1.0
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class ATM_Merchant {

    /**
	 * The tier ID.
	 *
	 * @since 1.0
	 * @access   private
	 * @var      int
	 */
    protected $id;
    
    /**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0
	 * @access public
	 * @param int $id
	 */
    public function __construct($id = null) {

        $this->id = $id ?? 0;

    }

    /**
     * Gets the tier ID.
     * @return int
     */
    public function id() {

        return $this->id;

    }

    /**
     * Gets the merchant ID.
     * @return int
     */
    public function title() {

        return get_the_title($this->id());

    }

    /**
     * Returns permalink
     */
    public function url() {

        return get_permalink($this->id());

    }

    /**
     * Returns merchant logo
     *
     * @param int $width
     * @param int $height
     * @param bool $crop
     * @see vt_resize() in core/fl1-helpers.php
     */
    public function image($width = 800, $height = 400, $crop = false) {

        $attachment_id = get_field('atm_merchant_logo', $this->id()) ?? null;

        if($attachment_id) {
            if($width || $height) {
                return vt_resize($attachment_id, '', $width, $height, $crop);
            } else {
                return $attachment_id;
            }
        }

        return $attachment_id;

    }

    /**
     * Returns atm_merchant_description
     * 
     * @return string
     */
    public function description() {

        return get_field('atm_merchant_description', $this->id());

    }

    /**
     * Returns atm_merchant_exclude
     * 
     * @return string
     */
    public function exclude() {

        $exclude = get_field('atm_merchant_exclude', $this->id()) ?? false;

        if($exclude === 'complete') {
            return true;
        }

        if($exclude === 'partial') {

            $delivery = FL1_Helpers::param('delivery') ?? ATM_Helpers::get_form_default_delivery();
            $mode = ATM_Helpers::get_mode();

            if($mode === 'buy') {
                $buy = get_field('atm_merchant_exclude_buy', $this->id());
                if(!empty($buy) && in_array($delivery, $buy)) {
                    return true;
                }
            }

            if($mode === 'sell') {
                $sell = get_field('atm_merchant_exclude_sell', $this->id());
                if(!empty($sell) && in_array($delivery, $sell)) {
                    return true;
                }
            }

        }

        return false;

    }

    /**
     * Returns atm_merchant_redirect_url_buy
     * 
     * @return string
     */
    public function redirect_url($mode = 'buy', $params = false) {

        switch ($mode) {
            case 'sell':
                return $this->redirect_url_sell($params);
                break;
            
            default:
                return $this->redirect_url_buy($params);
                break;
        }

    }
    
    /**
     * Returns atm_merchant_redirect_url_buy
     * 
     * @return string
     */
    public function redirect_url_buy($params = false) {

        $url = get_field('atm_merchant_redirect_url_buy', $this->id());

        if($url && $params && $this->url_params()) {
            $url .= '?' . http_build_query($this->url_params());
        }

        return $url;

    }

    /**
     * Returns atm_merchant_redirect_url_sell
     * 
     * @return string
     */
    public function redirect_url_sell($params = false) {

        $url = get_field('atm_merchant_redirect_url_sell', $this->id());

        if($url && $params && $this->url_params()) {
            $url .= '?' . http_build_query($this->url_params());
        }

        return $url;

    }

    /**
     * Returns atm_merchant_url_params
     * 
     * @return string
     */
    public function url_params() {

        $params = get_field('atm_merchant_url_params', $this->id()) ?? array();

        $query_array = array();
        if(is_array($params) && !empty($params)) {
            
            foreach($params as $param) {

                $key = $param['param'];
                $value = $param['value'];

                if(!$key) { continue; }
                if(!$value) { continue; }
                
                switch ($param['value']) {
                    case '%mode%':
                        $query_array[$key] = ATM_Helpers::get_mode();
                        break;

                    case '%amount%':
                        $query_array[$key] = FL1_Helpers::param('amount') ?? ATM_Helpers::get_form_default_amount();
                        break;

                    case '%currency%':
                        $query_array[$key] = ATM_Helpers::get_currency('id');
                        break;
                    
                    case '%amount%':
                        $query_array[$key] = FL1_Helpers::param('delivery') ?? ATM_Helpers::get_form_default_delivery();
                        break;

                    default:
                        $query_array[$key] = $value;
                        break;
                }

            }

        }

        return $query_array;

    }

    /**
     * Returns atm_merchant_minimum_order
     * 
     * @return string
     */
    public function min_order($format = false) {

        $amount = (float)get_field('atm_merchant_minimum_order', $this->id());

        if(is_numeric($amount) && $format) {
            $amount = number_format($amount, 0, ',', '.');
        }

        return $amount;

    }

    /**
     * Returns atm_merchant_maximum_order
     * 
     * @return string
     */
    public function max_order($format = false) {

        $amount = (float)get_field('atm_merchant_maximum_order', $this->id());

        if(is_numeric($amount) && $format) {
            $amount = number_format($amount, 0, '.', ',');
        }

        return $amount;

    }

    /**
     * Returns atm_merchant_postage or
     * calculated postage based on the amount
     * 
     * @param int $amount
     * @return number
     */
    public function postage($amount = 0) {

        $mode = ATM_Helpers::get_mode();
        $postage_field = $mode === 'buy' ? 'atm_merchant_postage' : 'atm_merchant_postage_sell';
        $postage = get_field($postage_field, $this->id()) ?? array();
        $postage_charge = 0;

        if(!empty($postage)) {

            foreach($postage as $postage_tier) {

                if($amount >= $postage_tier['min'] && $amount <= $postage_tier['max']) {
                    $postage_charge = $postage_tier['cost'];
                    break;
                }

            }

        }

        return $postage_charge;

    }

    /**
     * Rest API Data output
     * 
     * @return object $data
     */
    public function rest_api_data() {

        return array(
            'id' => $this->id(),
            'title' => $this->title(),
            'url' => $this->url(),
            'image' => $this->image(),
            'description' => $this->description(),
            'redirect_url_buy' => $this->redirect_url_buy(true),
            'redirect_url_sell' => $this->redirect_url_sell(true),
            'min_order' => $this->min_order(),
            'max_order' => $this->max_order(),
            'postage' => $this->postage(),
        );

    }

}

