<?php
/**
 * FL1 Init
 *
 * Class in charge of initialising everything FL1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class FL1 {

    public function __construct() {

        $this->define_constants();

        add_action('init', array($this, 'init'));
        add_action('after_setup_theme',	array($this, 'setup_theme'));

    }
    
    /**
     * Setup constants.
     *
     * @access private
     * @since 1.0
     * @return void
     */
    public function define_constants() {

        define('FL1_VERSION', '6.0');
        define('FL1_SLUG', 'fl1');
        define('FL1_PATH', get_stylesheet_directory());
        define('FL1_URL', get_stylesheet_directory_uri());
        define('FL1_REST_API_NAMESPACE', FL1_SLUG);

    }
    
    /**
     * Loads all dependencies.
     *
     * @access private
     * @since 1.0
     * @return void
     */
    public function load_dependencies() {

        $deps = apply_filters(
            FL1_SLUG.'_load_dependencies', 
            array(
                FL1_PATH.'/core/class-fl1-helpers.php',
                FL1_PATH.'/core/class-fl1-security.php',
                FL1_PATH.'/core/class-fl1-cpt.php',
                FL1_PATH.'/core/class-fl1-admin.php',
                FL1_PATH.'/core/class-fl1-public.php',
                FL1_PATH.'/core/class-fl1-acf.php',
            )
        );

        foreach($deps as $dep) {
            include_once $dep;
        }

    }

    public function init() {

        $fl1_public = new FL1_Public();
        $fl1_admin = new FL1_Admin();
        $fl1_security = new FL1_Security();
        
        // After core init
        do_action(FL1_SLUG.'_init');
        
    }

    public function setup_theme() {

        $this->load_dependencies();

        if(class_exists('ACF')) {
            $fl1_acf = new FL1_ACF();
        }

        // After core setup theme
        do_action(FL1_SLUG.'_setup_theme');

    }

}

// Release the Ojeteeeee! xD
$fl1 = new FL1();