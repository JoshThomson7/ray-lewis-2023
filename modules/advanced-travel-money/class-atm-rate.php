<?php
/**
 * ATM Rate
 *
 * @author FL1 Digital
 * @version 1.0
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class ATM_Rate {

    /**
	 * The tier ID.
	 *
	 * @since 1.0
	 * @access   private
	 * @var      int
	 */
    protected $data;
    
    /**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0
	 * @access public
	 * @param mixed int|obj
	 */
    public function __construct($data = null) {

        $this->data = $data;

    }

    /**
     * Gets data from table.
     * @return object
     */
    private function get_data($pluck = '') {

        global $wpdb;
        $rate = '';

        if(is_numeric($this->data)) {

            $rate = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM $wpdb->prefix.'atm_rates' WHERE ID = %d",
                $this->id()
            ));

        } else {
            $rate = $this->data;
        }

        if($rate != null) {
            if($pluck != '') {
                return $rate->$pluck;
            } else {
                return $rate;
            }
        }

        return null;

    }

    /**
     * Gets the rate ID.
     * @return int
     */
    public function id() {

        return $this->id;

    }

    /**
     * Gets merchant ID
     * @return int
     */
    public function post_id() {

        return $this->get_data('post_id');

    }
    
    /**
     * Gets transaction_type
     * @return string
     */
    public function transaction_type() {

        return $this->get_data('transaction_type');

    }

    /**
     * Gets item_number
     * @return int
     */
    public function item_number() {

        return $this->get_data('item_number');

    }
    
    /**
     * Gets item_currency
     * @return string
     */
    public function currency() {

        return $this->get_data('item_currency');

    }
    
    /**
     * Gets item_currencyISO
     * @return string
     */
    public function currency_iso() {

        return $this->get_data('item_currencyISO');

    }

    /**
     * Gets item_currency_tier_available
     * @return string
     */
    public function currency_tier_available() {

        return $this->get_data('item_currency_tier_available');

    }

    /**
     * Gets item_currency_tier_threshold
     * @return string
     */
    public function currency_tier_threshold() {

        return $this->get_data('item_currency_tier_threshold');

    }
    
    /**
     * Gets item_currency_tier_from
     * @return string
     */
    public function currency_tier_from() {

        return $this->get_data('item_currency_tier_from');

    }

    /**
     * Gets item_currency_tier_to
     * @return string
     */
    public function currency_tier_to() {

        return $this->get_data('item_currency_tier_to');

    }

    /**
     * Gets item_currency_value
     * @return string
     */
    public function currency_value() {

        return $this->get_data('item_currency_value');

    }

    /**
     * Gets rate_status
     * @return string
     */
    public function status() {

        return $this->get_data('rate_status');

    }

    /**
     * Rest API Data output
     * 
     * @return object $data
     */
    public function rest_api_data() {

        $this->get_data();

    }

}

