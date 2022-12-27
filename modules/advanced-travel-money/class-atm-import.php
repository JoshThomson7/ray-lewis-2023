<?php
/**
 * ATM Import
 * @author FL1 Digital
 * @version 1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

final class ATM_Import {

    private $files;

    public function setup_theme() {
        
        add_action('trashed_post', array($this, 'on_trash_merchant'));
        add_action('untrash_post', array($this, 'on_untrash_merchant'));

        add_action('delete_post', array($this, 'on_delete_merchant'));

    }

    public function import($files = array()) {

        $this->files = $files;

        if(empty($this->files)) { return; }

        $insert = false;
        $response = array(
            'message' => 'Import done.',
            'errors' => array(),
        );

        foreach($this->files as $file) {

            $transaction_type = $file['transaction_type'];
            $xml = simplexml_load_file($file['file']);
            $xml_decoded = json_decode(json_encode($xml), TRUE);

            if(isset($xml_decoded['merchant']) && !empty($xml_decoded['merchant'])) {

                foreach($xml_decoded['merchant'] as $merchant) {

                    // Merchant ID
                    $merchant_id = $this->pluck_merchant_id($merchant);

                    if(!$merchant_id) { continue; }

                    // Merchant Data
                    $merchant_info = $merchant['merchantInfo'];
                    $merchant_data = array();

                    if($transaction_type === 'buy') {
                        $merchant_data['min_order'] = isset($merchant_info['minimumOrder']) ? $merchant_info['minimumOrder'] : 0;
                        $merchant_data['max_order'] = isset($merchant_info['maximumOrder']) ? $merchant_info['maximumOrder'] : 0;
                        $merchant_data['postage'] = $this->merchant_postage($merchant_info);
                    }
                    
                    $post_id = $this->import_merchant($merchant_id, $merchant_data);
                    //echo $post_id.' - '.$merchant_id.'<br>';

                    if(!$post_id) { continue; }

                    if(isset($merchant['item']) && !empty($merchant['item'])) {

                        $this->delete_rates($merchant_id, $transaction_type);

                        foreach($merchant['item'] as $rate) {

                            $insert = 0;

                            $rate['post_id'] = intval($post_id);
                            $rate['transaction_type'] = $transaction_type;
                            $rate['merchant_id'] = $merchant_id;
                            $rate['merchant_min_order'] = isset($merchant_info['minimumOrder']) ? $merchant_info['minimumOrder'] : 0;
                            $rate['merchant_max_order'] = isset($merchant_info['maximumOrder']) ? $merchant_info['maximumOrder'] : 0;
                            $rate['merchant_delivery'] = isset($merchant_info['delivery']) ? $merchant_info['delivery'] : 0;
                            $rate['merchant_collection'] = isset($merchant_info['collection']) ? $merchant_info['collection'] : 0;
                            $rate['merchant_london_only'] = isset($merchant_info['londonOnly']) ? $merchant_info['londonOnly'] : 0;

                            // Have we got tiers?
                            if(isset($rate['item_currency_tier_available']) && $rate['item_currency_tier_available'] === 'yes') {
                                
                                $tiers = isset($rate['item_currency_tier']) && !empty($rate['item_currency_tier']) ? $rate['item_currency_tier'] : array();

                                // Go through tiers and reformat data ready to be imported correctly
                                $new_tiers = array();
                                foreach($tiers as $tier_key => $tier) {
                                    
                                    if(!empty($new_tiers)) {
                                        $new_tiers[$tier_key-1]['CurrencyTierTo'] = $tier['CurrencyTierFrom'] - 1;
                                    }

                                    if(count($tiers) == $tier_key + 1) {
                                        $tier['CurrencyTierTo'] = 1000000;
                                    }

                                    array_push($new_tiers, $tier);
                                }

                                if(!empty($new_tiers)) {
                                    foreach($new_tiers as $new_tier) {
                                        $rate['item_currency_tier_from'] = $new_tier['CurrencyTierFrom'];
                                        $rate['item_currency_tier_to'] = $new_tier['CurrencyTierTo'];
                                        $rate['item_currency_value'] = $new_tier['item_currency_value'];
                                        unset($rate['item_currency_tier']);
                                        
                                        $insert = $this->import_rate($rate) ?? 0;
                                    }
                                }

                            } else {
                                
                                $rate['item_currency_tier_available'] = 'no';
                                $rate['item_currency_tier_threshold'] = '';
                                $rate['item_currency_tier_from'] = 0;
                                $rate['item_currency_tier_to'] = 1000000;
                                
                                $insert = $this->import_rate($rate) ?? 0;

                            }
                            
                            if(!$insert) { 
                                $response['errors'][] = 'Error: rate item '.$rate['item_number'].', transaction type '.$transaction_type.' for merchant '.$merchant_id.' not inserted<br/>';
                            }

                            //pretty_print($rate);

                        }

                    }

                }

            }

        }

        return $response;

    }

    /**
     * Get merchant ID from XML
     * 
     * @param string $merchant
     * @return string
     */
    private function pluck_merchant_id($merchant) {

        return isset($merchant['@attributes']['id']) ? $merchant['@attributes']['id'] : '';

    }

    /**
     * Generates array of postage data
     * 
     * @param array $merchant_info
     * @return array
     */
    private function merchant_postage($merchant_info) {

        $postage = array();

        if(is_array($merchant_info) && !empty($merchant_info) && isset($merchant_info['delivery']) && $merchant_info['delivery'] === 'yes') {
            $postage_fields = array(
                'threshold5' => 'threshold5Fee',
                'threshold4' => 'threshold4Fee',
                'threshold3' => 'threshold3Fee',
                'threshold2' => 'threshold2Fee',
                'threshold1' => 'threshold1Fee'
            );

            foreach($postage_fields as $postage_field => $postage_field_fee) {
                $threshold = isset($merchant_info[$postage_field]) ? $merchant_info[$postage_field] : '';
                if(!$threshold) { continue; }

                $threshold_num = preg_replace('/[^0-9]/', '', $postage_field);
                $threshold_next = 'threshold'.($threshold_num - 1);

                $postage[] = array(
                    'min' => $threshold,
                    'max' => $threshold_num == 1 ? $merchant_info['maximumOrder'] : $merchant_info[$threshold_next] - 1,
                    'cost' => $merchant_info[$postage_field_fee]
                );
            }

            //pretty_print($postage);

        }

        return $postage;

    }

    /**
     * Import merchant as WP post
     * 
     * @param string $merchant_id
     * @param array $merchant_data
     * @return int $post_id
     */
    private function import_merchant($merchant_id, $merchant_data) {

        $post = get_page_by_title($merchant_id, OBJECT, 'merchant');

        // Only insert if merchant doesn't exist
        if(!$post->ID) {

            $args = array(
                'post_type'     => 'merchant',
                'post_title'    => $merchant_id,
                'post_content'  => '',
                'post_status'   => 'publish',
                'post_author'   => 1
            );

            $post_id = wp_insert_post($args);

        } else {
            $post_id = $post->ID;
        }

        // Update meta
        if($post_id && !empty($merchant_data)) {
            update_field('atm_merchant_minimum_order', $merchant_data['min_order'], $post_id);
            update_field('atm_merchant_maximum_order', $merchant_data['max_order'], $post_id);
            update_field('atm_merchant_postage', $merchant_data['postage'], $post_id);
        }

        return $post_id;

    }

    /**
     * Import merchant as WP post
     * 
     * @param string $merchant_id
     * @param string $transaction_type
     * @return int $post_id
     */
    private function delete_rates($merchant_id, $transaction_type) {

        global $wpdb;

        $wpdb->delete(
            $wpdb->prefix.'atm_rates',
            array(
                'merchant_id' => $merchant_id,
                'transaction_type' => $transaction_type
            ),
            array(
                '%s',
                '%s'
            )
        );

    }
    
    /**
     * Import merchant rates
     * 
     * @param array $rate
     * @return int|null
     */
    public function import_rate($rate) {
        
        global $wpdb;

        return $wpdb->insert(
            $wpdb->prefix.'atm_rates', // table name
            $rate, // data
        );

    }

    /**
     * On trash merchant
     * 
     * @param int $post_id
     * @return int|false
     */
    public function on_trash_merchant($post_id) {

        global $wpdb;
 
        return $wpdb->update(
            $wpdb->prefix.'atm_rates',
            array( // data to update
                'rate_status' => 'trash'
            ),
            array( // where clause
                'post_id' => $post_id,
                'rate_status' => 'publish'
            ),
            array('%s'), // type for data
            array('%d', '%s'), // type for where
        );

    }

    /**
     * On untrash merchant
     * 
     * @param int $post_id
     * @return int|false
     */
    public function on_untrash_merchant($post_id) {

        global $wpdb;
 
        return $wpdb->update(
            $wpdb->prefix.'atm_rates',
            array( // data to update
                'rate_status' => 'publish'
            ),
            array( // where clause
                'post_id' => $post_id,
                'rate_status' => 'trash'
            ),
            array('%s'), // type for data
            array('%d', '%s'), // type for where
        );

    }

    /**
     * On merchant deletion
     * 
     * @param int $post_id
     * @param object $post
     * @return int|false
     */
    public function on_delete_merchant($post_id) {

        global $wpdb;
 
        return $wpdb->delete(
            $wpdb->prefix.'atm_rates',
            array(
                'post_id' => $post_id,
                'rate_status' => 'trash',
            ),
            array('%d', '%s'),
        );

    }
    
}