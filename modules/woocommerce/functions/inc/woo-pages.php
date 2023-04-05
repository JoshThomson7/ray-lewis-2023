<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Woo Pages
 *
 * Programatcially creates custom woo pages
 *
 * @author  Various
 * @package WooCommerce
 * @see https://wordpress.stackexchange.com/questions/31247/how-can-i-programmatically-create-child-pages-on-theme-activation
 *
*/

function wc_custom_pages() {
    $pages = array(
        array(
            'name'  => 'register',
            'title' => 'Register'
        )
    );

    $template = array(
        'post_type'   => 'page',
        'post_status' => 'publish',
        'post_author' => 1
    );

    foreach( $pages as $page ) {
        $exists = get_page_by_title( $page['title'] );

        $my_page = array(
            'post_name'  => $page['name'],
            'post_title' => $page['title']
        );

        $my_page = array_merge( $my_page, $template );

        $id = ( $exists ? $exists->ID : wp_insert_post( $my_page ) );

        if( isset( $page['child'] ) ) {
            foreach( $page['child'] as $key => $value ) {
                $child_id = get_page_by_title( $value );
                $child_page = array(
                    'post_name'   => $key,
                    'post_title'  => $value,
                    'post_parent' => $id
                );
                $child_page = array_merge( $child_page, $template );
                if( !isset( $child_id ) ) wp_insert_post( $child_page );
            }
        }
    }
}
add_filter( 'after_switch_theme', 'wc_custom_pages' );
