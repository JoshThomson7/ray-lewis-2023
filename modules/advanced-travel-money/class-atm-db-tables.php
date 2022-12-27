<?php
/**
 * ATM_DB_Tables
 *
 * Class in charge of initialising everything ATM_DB_Tables
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class ATM_DB_Tables {

    public function __construct() {
        add_action('after_switch_theme', array($this, 'create_atm_tables'));
    }

    public function create_atm_tables() {

        // Include Upgrade Script
        require_once ABSPATH.'/wp-admin/includes/upgrade.php';

        // WP Globals
        global $wpdb;
        
        // Log table
        $log_table = $wpdb->prefix.'atm_log';
        if( $wpdb->get_var( "show tables like '$log_table'" ) != $log_table ) { 
    
            $sql = "CREATE TABLE `$log_table` (";
            $sql .= " `ID` bigint(20) NOT NULL AUTO_INCREMENT, ";
            $sql .= " `message` longtext COLLATE utf8mb4_unicode_520_ci, ";
            $sql .= " `line` varchar(255) COLLATE utf8mb4_unicode_520_ci, ";
            $sql .= " `file` varchar(20) COLLATE utf8mb4_unicode_520_ci, ";
            
            $sql .= " PRIMARY KEY (`ID`), ";
            $sql .= " KEY `ID` (`ID`) ";
            $sql .= " ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;";
    
            dbDelta($sql);

        }

        // Rates table
        $rates_table = $wpdb->prefix.'atm_rates';
        if( $wpdb->get_var( "show tables like '$rates_table'" ) != $rates_table ) { 
    
            $sql = "CREATE TABLE `$rates_table` (";
            $sql .= " `ID` bigint(20) NOT NULL AUTO_INCREMENT, ";
            $sql .= " `post_id` bigint(20) unsigned NOT NULL DEFAULT '0', ";
            $sql .= " `merchant_id` varchar(255) NOT NULL, ";
            $sql .= " `merchant_min_order` int(11) NOT NULL, ";
            $sql .= " `merchant_max_order` int(11) NOT NULL, ";
            $sql .= " `merchant_delivery` varchar(20) COLLATE utf8mb4_unicode_520_ci, ";
            $sql .= " `merchant_collection` varchar(20) COLLATE utf8mb4_unicode_520_ci, ";
            $sql .= " `merchant_london_only` varchar(20) COLLATE utf8mb4_unicode_520_ci, ";
            $sql .= " `transaction_type` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL, ";
            $sql .= " `item_number` int(11) NOT NULL, ";
            $sql .= " `item_currency` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL, ";
            $sql .= " `item_currencyISO` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL, ";
            $sql .= " `item_currency_tier_available` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL, ";
            $sql .= " `item_currency_tier_threshold` varchar(20) COLLATE utf8mb4_unicode_520_ci, ";
            $sql .= " `item_currency_tier_from` int(11) NOT NULL, ";
            $sql .= " `item_currency_tier_to` int(11) NOT NULL, ";
            $sql .= " `item_currency_value` decimal(10,6) , ";
            $sql .= " `rate_status` varchar(20) COLLATE utf8mb4_unicode_520_ci DEFAULT 'publish', ";
            
            $sql .= " PRIMARY KEY (`ID`), ";
            $sql .= " KEY `ID` (`ID`), ";
            $sql .= " KEY `post_id` (`post_id`), ";
            $sql .= " KEY `merchant_id` (`merchant_id`), ";
            $sql .= " KEY `merchant_min_order` (`merchant_id`), ";
            $sql .= " KEY `merchant_max_order` (`merchant_id`), ";
            $sql .= " KEY `merchant_delivery` (`merchant_delivery`), ";
            $sql .= " KEY `merchant_collection` (`merchant_collection`), ";
            $sql .= " KEY `merchant_london_only` (`merchant_london_only`), ";
            $sql .= " KEY `transaction_type` (`transaction_type`), ";
            $sql .= " KEY `item_number` (`item_number`), ";
            $sql .= " KEY `item_currency` (`item_currency`), ";
            $sql .= " KEY `item_currencyISO` (`item_currencyISO`), ";
            $sql .= " KEY `item_currency_tier_available` (`item_currency_tier_available`), ";
            $sql .= " KEY `item_currency_value` (`item_currency_value`) ";
            $sql .= " ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;";

            dbDelta($sql);

        }
    
    }

    
}

