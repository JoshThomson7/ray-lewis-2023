<?php
/**
 * Extends the WordPress REST API.
 *
 * Adds custom endpoint to the WordPress REST API.
 *
 * @package    ATM
 * @author     FL1 Digital
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class ATM_REST_Rates_Controller extends WP_REST_Controller {

    /**
	 * Declare REST API route.
	 */
    protected $route = 'rates';

	/**
	 * Register REST route.
	 */
	public function register_rest_route() {

        register_rest_route( ATM_REST_API_NAMESPACE, '/'.$this->route.'/', array(
            'methods' => WP_REST_Server::READABLE, // GET
            'callback' => array( $this, 'getData' ),
            'permission_callback' => array($this, 'check_for_errors'),
            'args' => array(
                'select' => array(
                    'description'       => 'Choose what to return.',
                    'type'              => 'string',
                    'validate_callback' => function( $param, $request, $key ) {
                        return $this->validate_select($param);
                    },
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                'id' => array(
                    'description'       => 'Query one rate only from collection by ID.',
                    'type'              => 'integer',
                    'validate_callback' => function( $param, $request, $key ) {
                        return is_numeric( $param );
                    },
                    'sanitize_callback' => 'absint',
                ),
                'amount' => array(
                    'description'       => 'Query rates by amount entered.',
                    'type'              => 'integer',
                    'validate_callback' => function( $param, $request, $key ) {
                        return is_numeric( $param );
                    },
                    'sanitize_callback' => 'absint',
                ),
                'merchant_id' => array(
                    'description'       => 'Query rates by merchant.',
                    'type'              => 'string',
                    'validate_callback' => function( $param, $request, $key ) {
                        return is_string( $param );
                    },
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                'transaction_type' => array(
                    'description'       => 'Query rates by type of transaction.',
                    'type'              => 'string',
                    'validate_callback' => function( $param, $request, $key ) {
                        return is_string( $param );
                    },
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                'currency_iso' => array(
                    'description'       => 'Query rates by currency ISO code.',
                    'type'              => 'string',
                    'validate_callback' => function( $param, $request, $key ) {
                        return is_string( $param );
                    },
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                'currency_value' => array(
                    'description'       => 'Query rates currency value.',
                    'type'              => 'string',
                    'validate_callback' => function( $param, $request, $key ) {
                        return is_string( $param );
                    },
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                'delivery' => array(
                    'description'       => 'Query rates based on delivery or collection.',
                    'type'              => 'string',
                    'validate_callback' => function( $param, $request, $key ) {
                        return is_string( $param );
                    },
                    'sanitize_callback' => 'sanitize_text_field'
                )
            )
        ));

    }

    /**
     * Returns team members
	 *
	 * @since 2.0
	 */
	public function getData(WP_REST_Request $request) {

        global $wpdb;

        $all_params = $request->get_params();

        $select =  $request->get_param('select') ?? '*';
        $id =  $request->get_param('id');
        $merchant_id =  $request->get_param('merchant_id');
        $transaction_type =  $request->get_param('transaction_type');
        $amount =  $request->get_param('amount');
        $currencyISO =  $request->get_param('currency_iso');
        $currency_value =  $request->get_param('currency_value');
        $delivery =  $request->get_param('delivery');

        $where = $this->handle_where($all_params);

        $sql = "SELECT {$select} FROM `{$wpdb->prefix}atm_rates`".$where;
        $params = array();

        if($id) {
            $params[] = "id = {$id}";
        }

        if($merchant_id && $merchant_id !== 'debug') {
            $params[] = "merchant_id = '{$merchant_id}'";
        }

        if($transaction_type) {
            $params[] = "transaction_type = '{$transaction_type}'";
        }
        
        if($amount) {
            $params[] = "'{$amount}' BETWEEN item_currency_tier_from AND item_currency_tier_to";

            if($transaction_type === 'buy') {
                $params[] = "'{$amount}' BETWEEN merchant_min_order AND merchant_max_order";
            }
        }

        if($currencyISO) {
            $params[] = "item_currencyISO = '{$currencyISO}'";
        }

        if($currency_value) {
            if(strpos($currency_value, ',')) {
                $currency_value = explode(',', $currency_value);
                $params[] = "item_currency_value BETWEEN {$currency_value[0]} AND {$currency_value[1]}";
            } else {
                $params[] = "item_currency_value LIKE '{$currency_value}'";
            }
        }

        if($delivery) {
            if($delivery === 'deliver') {
                $params[] = "merchant_delivery = 'yes'";
            }

            if($delivery === 'collect') {
                $params[] = "merchant_collection = 'yes'";
            }
        }


        $sql .= (!empty($params) ? implode(' AND ', $params) : '');
        $sql .= " ORDER BY item_currency_value DESC";
        
        // Query it up
        $rates = $wpdb->get_results($wpdb->prepare(
            $sql,
        ), ARRAY_A);
        
        return array(
            // 'params' => $params,
            // 'sql'=> $sql,
            'total'=> count($rates),
            'rates'=> $rates,
        );

    }

    /**
     * Handles the WHERE clause
     * 
     * @param array $params
     * @return string
     */
    private function handle_where($params) {

        $where = '';

        if(!empty($params)) {
            if(count($params) == 1 && isset($params['select'])) { 
                return '';
            } else {
                $where = ' WHERE ';
            }
        }

        return $where;

    }

    /**
     * Validates select param
     * 
     * @param string $param
     * @return boolean
     */
    private function validate_select($param) {

        $allowed = array(
            'ID',
            'post_id',
            'merchant_id',
            'transaction_type',
            'item_number',
            'item_currency',
            'item_currencyISO',
            'item_currency_tier_available',
            'item_currency_tier_threshold',
            'item_currency_tier_from',
            'item_currency_tier_to',
            'item_currency_value',
            'rate_status',
        );

        if($param !== '*' && !in_array($param, $allowed)) {
            return false;
        }

        return true;

    }

    /**
     * Check for errors.
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
    */
    public function check_for_errors( $request ) {

        $debug = ATM_Helpers::is_feed_in_dev_mode();

        if(!$debug) {
            $referer = $_SERVER['HTTP_REFERER'];
        
            if ( ! $referer ) {
                return false;
            }
            
            // Referer must contain besttravelmoney.com.
            if (strpos($referer, 'besttravelmoney.com') === false) {
                return new WP_Error(
                    'invalid-referer',
                    'Requests must contain a valid referer',
                    compact( 'referer' )
                );
            }
        }

        return $request;

    }

}