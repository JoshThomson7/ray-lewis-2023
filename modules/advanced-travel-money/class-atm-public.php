<?php
/**
 * ATM Public
 *
 * Class in charge of ATM Public facing side
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class ATM_Public {

    public function __construct() {

        add_action('wp_enqueue_scripts', array($this, 'enqueue'));
        add_action('body_class', array($this, 'body_classes'), 20);

        $this->atm_rewrite_rules();
        add_filter('query_vars', array($this, 'add_query_vars'));
        add_filter('page_template', array($this, 'pages'));
        add_filter('single_template', array($this, 'singles'));

        add_action('template_redirect', array($this, 'template_redirect'));

        // Rankmath
        add_filter( 'rank_math/sitemap/enable_caching', '__return_false');
        add_filter('rank_math/frontend/canonical', array($this, 'atm_rank_math_canonical'));
        add_filter('rank_math/sitemap/providers', function( $external_providers ) {
            $external_providers['travel-money'] = new \RankMath\Sitemap\Providers\ATM_Sitemap();
            return $external_providers;
        });

    }

    public function enqueue() {

        wp_enqueue_script(ATM_SLUG.'-js', ATM_URL.'assets/js/atm.min.js');

        // JS vars
        wp_localize_script(ATM_SLUG.'-js', ATM_SLUG.'_ajax_object', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'ajax_nonce' => wp_create_nonce('$C.cGLu/1zxq%.KH}PjIKK|2_7WDN`x[vdhtF5GS4|+6%$wvG)2xZgJcWv3H2K_M'),
            'siteUrl' => site_url('travel-money'),
            'jsPath' => ATM_URL.'assets/js/',
            'cssPath' => ATM_URL.'assets/css/',
            'imgPath' => ATM_URL.'assets/img/',
            'currencies' => ATM_Helpers::currencies(),
            'mode' => ATM_Helpers::get_mode(),
            'currency' => ATM_Helpers::get_currency('text'),
            'currency_iso' => ATM_Helpers::get_currency('id') ? ATM_Helpers::get_currency('id') : 'eur'
        ));

        // Styles
        wp_enqueue_style(ATM_SLUG, ATM_URL.'assets/css/atm.min.css');

        // Enqueue React app
        //$this->embed_react_app('app/static', array('book'));

    }

    /**
	 * Returns body CSS class names.
	 *
	 * @since 1.0
     * @param array $classes
	 */
    public function body_classes($classes) {
        global $post;

        if(is_page()) {

            if($post->post_parent) {
                   
                $ancestors = get_post_ancestors( $post->ID );
                $ancestors = array_reverse($ancestors);
                   
                if ( !isset( $parents ) ) $parents = null;

                foreach($ancestors as $ancestor_id) {

                    $post_name = get_post_field('post_name', $ancestor_id);
                    $classes[] = ATM_SLUG.'-'.$post_name;

                }

            }

            $current_post_name = get_post_field('post_name', $post->ID);
            $classes[] = ATM_SLUG.'-'.$current_post_name;

        } elseif(is_singular(FL1_Helpers::registered_post_types(ATM_SLUG))) {

            $post_type = $post->post_type;
            $classes[] = ATM_SLUG.'-single-'.$post_type;

        }

        return $classes;
    }

    /**
     * Embeds necessary production build files
     * 
     * @param string $path | Required - Files must be uploaded to a folder inside the active theme, ie: app/static
     * @param array $pages | Required - The ages in which to load the files
     */
    private function embed_react_app($path, $pages = array()) {

        if(empty($pages)) { return null; }

        foreach($pages as $page) {

            /**
             * Enqueue React app in "book" page
             */
            if(is_page($page)) {

                $CSSfiles = glob(TEMPLATEPATH.'/'.$path.'/css/*.css');

                $css_file_count = 0;
                foreach($CSSfiles as $CSSfilename) {
                    if(strpos($CSSfilename, '.css') && !strpos($CSSfilename, '.css.map')) {
                        $CSSfilename = basename($CSSfilename);
                        wp_enqueue_style('bodyset-book-'.$css_file_count, esc_url(get_stylesheet_directory_uri()).'/'.$path.'/css/' . $CSSfilename); // Header
                    }

                    $css_file_count++;
                }

                $JSfiles = glob(TEMPLATEPATH.'/'.$path.'/js/*.js');
                $react_js_to_load = '';

                $js_file_count = 0;
                foreach($JSfiles as $filename) {
                    if(strpos($filename,'.js')&&!strpos($filename,'.js.map')) {
                        $filename = basename($filename);
                        wp_enqueue_script('bodyset-book-react-'.$js_file_count, esc_url(get_stylesheet_directory_uri()).'/'.$path.'/js/' . $filename, '', '', true); // Footer
                    }

                    $js_file_count++;
                }

            }

        }

    }

    /**
     * page_template filter function
     * 
     * @param string $template
     */
    public function pages($template) {
    
        if(is_page(array('travel-money'))) {
            $template = ATM_PATH . 'templates/atm-travel-money.php';
        }
    
        return $template;
    
    }

    /**
     * page_template filter function
     * 
     * @param string $template
     */
    public function singles($template) {
    
        global $post;

        if($post->post_type === 'travel-guide') {
            $template = ATM_PATH . 'templates/travel-guides/single-travel-guide.php';
        }

        return $template;
    
    }

    /**
     * Add objective rewrite rule
     */
    public function atm_rewrite_rules() {
        add_rewrite_rule('^travel-money/([^/]+)/([^/]+)/?','index.php?pagename=travel-money&atm_mode=$matches[1]&atm_currency=$matches[2]', 'top');
        add_rewrite_rule('^travel-money/([^/]+)/?','index.php?pagename=travel-money&atm_mode=$matches[1]', 'top');
    }

    /**
     * Add query vars
     */
    public function add_query_vars($vars) {
        $vars[] = 'atm_mode';
        $vars[] = 'atm_currency';
        return $vars;
    }

    /**
     * Template redirect
     */
    public function template_redirect() {

        if(is_page('travel-money')) {
            
            $do_redirect = false;
            $redirect = '';

            $url = get_permalink(get_page_by_path('travel-money'));

            $mode = ATM_Helpers::get_mode();

            if(!$mode) {
                $do_redirect = true;
                $redirect = $url.'/buy/euros/';
            } else {
                if($mode !== 'buy' && $mode !== 'sell') {
                    $do_redirect = true;
                    $redirect = $url.'/buy/euros/';
                }
            }

            if($do_redirect) {
                wp_redirect($redirect);
                exit();
            }

        }

    }

    public function atm_rank_math_canonical( $canonical ) {
        
        if(is_page('travel-money')) {

            $base_url = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 'https' : 'http' ) . '://' .  $_SERVER['HTTP_HOST'];
            $canonical = $base_url . $_SERVER["REQUEST_URI"];

        }

        return $canonical;

    }



}

