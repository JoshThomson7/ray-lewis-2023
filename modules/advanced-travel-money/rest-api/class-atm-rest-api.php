<?php
/**
 * Extends the WordPress REST API.
 *
 * Adds custom endpoints to the WordPress REST API.
 *
 * @package    APM
 * @author     FL1 Digital
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class ATM_REST_API {

    public function register_routes() {

        // Rates.
        $rates_controller = new ATM_REST_Rates_Controller();
        add_action('rest_api_init',	array($rates_controller, 'register_rest_route'));

        $rates_feed = new ATM_REST_Feed_Controller();
        add_action('rest_api_init',	array($rates_feed, 'register_rest_route'));

    }

}
