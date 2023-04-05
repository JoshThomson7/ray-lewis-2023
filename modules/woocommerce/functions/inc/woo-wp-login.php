<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Woo WP Login
 *
 * WP Login related messages and functions
 *
 * @author  Various
 * @package WooCommerce
 *
*/

/**
 *  wp_login_wordpress_errors()
 *
 *  Change standard login errors
*/
function wp_login_wordpress_errors() {
    if(isset($_GET['error']) && $_GET['error'] === 'invalidkey') {
        return 'Oops! The link to create your password has expired. Enter your email address to receive a new link.';
    } else {
        return 'Oops! Wrong email or password.';
    }
}
add_filter( 'login_errors', 'wp_login_wordpress_errors' );

/**
 *  wp_login_wordpress_errors()
 *
 *  Change standard login errors
*/
function wp_login_text( $translated, $original, $domain ) {

    // Use the text string exactly as it is in the translation file
    if ( $GLOBALS['pagenow'] === 'wp-login.php' ) {
        if($translated == 'Username or Email Address' ) {
            $translated = 'Email address';
        }

        if(isset($_GET['action']) && $_GET['action'] === 'lostpassword') {
            $translated = str_ireplace(  'Please enter your username or',  'Please enter your',  $translated );
        }
    }
    
    return $translated;
}
add_filter( 'gettext', 'wp_login_text', 10, 3 );

/**
 *  wp_login_wordpress_errors()
 *
 *  Change standard login errors
*/
function change_email_error( $message ) {
    if ($message == 'Invalid username or email.' ) {
        $message = 'Oops! Something went wrong. Please try again.';
    }
    return $message;
}
add_filter('woocommerce_add_error', 'change_email_error');