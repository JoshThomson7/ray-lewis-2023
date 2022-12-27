<?php
/**
 * ATM Init
 *
 * Class in charge of initialising everything ATM
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class ATM {

    public function __construct() {

        $this->define_constants();

        add_filter(FL1_SLUG.'_load_dependencies', array($this, 'load_dependencies'));
        add_action(FL1_SLUG.'_init', array($this, 'init'));
        add_action(FL1_SLUG.'_setup_theme',	array($this, 'setup_theme'));

    }

    /**
     * Setup constants.
     *
     * @access private
     * @since 1.0
     * @return void
     */
    private function define_constants() {

        define('ATM_VERSION', '1.0');
        define('ATM_PLUGIN_FOLDER', 'advanced-travel-money');
        define('ATM_SLUG', 'atm');
        define('ATM_PATH', FL1_PATH.'/modules/'.ATM_PLUGIN_FOLDER.'/');
        define('ATM_URL', FL1_URL.'/modules/'.ATM_PLUGIN_FOLDER.'/');
        define('ATM_REST_API_NAMESPACE', ATM_SLUG);
        define('ATM_REST_API_URL', esc_url(home_url()).'/wp-json/'.ATM_REST_API_NAMESPACE.'/');

    }
    
    /**
     * Loads all dependencies.
     *
     * @access public
     * @since 1.0
     * @return void
     */
    public function load_dependencies($deps) {

        // Core
        $deps[] = ATM_PATH. 'class-atm-cpt.php';
        $deps[] = ATM_PATH. 'class-atm-helpers.php';
        $deps[] = ATM_PATH. 'class-atm-db-tables.php';
        $deps[] = ATM_PATH. 'class-atm-public.php';
        $deps[] = ATM_PATH. 'class-atm-import.php';
        $deps[] = ATM_PATH. 'class-atm-merchant.php';
        $deps[] = ATM_PATH. 'class-atm-rate.php';
        $deps[] = ATM_PATH. 'rest-api/class-atm-rest-api.php';
        $deps[] = ATM_PATH. 'rest-api/class-atm-rest-feed-controller.php';
        $deps[] = ATM_PATH. 'rest-api/class-atm-rest-rates-controller.php';
        $deps[] = ATM_PATH. 'class-atm-rankmath-sitemap.php';

        return $deps;

    }

    public function init() {

        $atm_public = new ATM_Public();
        
        // Call our custom WPAI Add-on like a boss
        //ATM_WPAI::get_instance();
        
    }

    public function setup_theme() {

        $atm_db_tables = new ATM_DB_Tables();
        $atm_cpt = new ATM_CPT();

        $atm_import = new ATM_Import();
        $atm_import->setup_theme();

        $atm_rest_api = new ATM_REST_API();
        $atm_rest_api->register_routes();

    }

}

// Release the Kraken!
$atm = new ATM();