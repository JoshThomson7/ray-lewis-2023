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

class ATM_REST_Feed_Controller extends WP_REST_Controller {

    /**
	 * Declare REST API route.
	 */
    protected $route = 'feed';

	/**
	 * Register REST route.
	 */
	public function register_rest_route() {

        register_rest_route( ATM_REST_API_NAMESPACE, '/'.$this->route.'/', array(
            'methods' => WP_REST_Server::READABLE, // GET
            'callback' => array( $this, 'process' ),
            'permission_callback' => array($this, 'check_for_errors')
        ));

    }

    /**
     * Returns team members
	 *
	 * @since 2.0
	 */
	public function process(WP_REST_Request $request) {

        $data = array(
            array(
                'file' => ATM_Helpers::get_feed_buy_url(),
                'transaction_type' => 'buy',
            ),
            array(
                'file' => ATM_Helpers::get_feed_sell_url(),
                'transaction_type' => 'sell',
            )
        );

        $import = new ATM_Import();
        $response = $import->import($data);
        
        return $response;

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
            $user_agent = $request->get_header('user_agent');
        
            if (!$user_agent) {
                return false;
            }
            
            // User agent must contain Wget.
            if (strpos($user_agent, 'Wget') === false) {
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