<?php
/**
 * ATM CPT
 *
 * Class in charge of registering ATM custom post types
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class ATM_CPT {

    public function __construct() {

        $post_types = array(
            'merchant',
            'travel_guide'
        );

        foreach($post_types as $post_type) {
            $method = 'register_'.$post_type.'_cpt';

            if(method_exists($this, $method)) {
                $this->$method();
            }
        }

        add_action('admin_menu', array($this, 'menu_page'));
        add_action('admin_menu', array($this, 'remove_duplicate_subpage'));
        add_filter('parent_file', array($this, 'highlight_current_menu'));

        add_action('acf/init', array($this, 'acf_init'));

    }

    function menu_page() {
        add_menu_page(
            __('Travel Money', ATM_SLUG),
            'Travel Money',
            'manage_options',
            ATM_SLUG,
            '',
            'dashicons-palmtree',
            30
        );

        $submenu_pages = array(
            array(
                'page_title'  => 'Merchants',
                'menu_title'  => 'Merchants',
                'capability'  => 'manage_options',
                'menu_slug'   => 'edit.php?post_type=merchant',
                'function'    => null,
            ),
            array(
                'page_title'  => 'Travel Guides',
                'menu_title'  => 'Travel Guides',
                'capability'  => 'manage_options',
                'menu_slug'   => 'edit.php?post_type=travel-guide',
                'function'    => null,
            ),
        );

        foreach ( $submenu_pages as $submenu ) {

            add_submenu_page(
                ATM_SLUG,
                $submenu['page_title'],
                $submenu['menu_title'],
                $submenu['capability'],
                $submenu['menu_slug'],
                $submenu['function']
            );

        }
    }

    public function highlight_current_menu( $parent_file ) {

        global $submenu_file, $current_screen, $pagenow;

        $cpts = FL1_Helpers::registered_post_types(ATM_SLUG);

        # Set the submenu as active/current while anywhere APM
        if (in_array($current_screen->post_type, $cpts)) {

            if ( $pagenow == 'post.php' ) {
                $submenu_file = 'edit.php?post_type=' . $current_screen->post_type;
            }

            if ( $pagenow == 'edit-tags.php' ) {
                $submenu_file = 'edit-tags.php?taxonomy='.$current_screen->taxonomy.'&post_type=' . $current_screen->post_type;
            }

            $parent_file = ATM_SLUG;

        }

        return $parent_file;

    }

    /**
     * Merchant CPT
     */
    private function register_merchant_cpt() {

        // CPT
        $cpt = new FL1_CPT(
            array(
                'post_type_name' => 'merchant',
                'plural' => 'Merchants',
                'menu_name' => 'Travel Money'
            ),
            array(
                'menu_position' => 21,
                'rewrite' => array( 'slug' => 'merchant', 'with_front' => true ),
                'show_in_menu' => false,
                'publicly_queryable' => false,
                'generator' => ATM_SLUG
            )
        );

        $cpt->columns(array(
            'cb' => '<input type="checkbox" />',
            'logo' => 'Logo',
	        'title' => __('Merchant'),
            'feed' => __('Feed'),
        ));

        $cpt->populate_column('logo', function($column, $post) {

            $post_id = $post->ID;
            $merchant = new ATM_Merchant($post_id);
            $logo = $merchant->image();
            
            if(!empty($logo)) {
                echo '<div style="padding: 10px 0;">';
                echo '<a href="'.get_edit_post_link($post_id).'" style="max-width: 200px; display: flex; flex-direction: column; align-items: center;"><img src="'.$logo['url'].'" style="max-height: 95px; width: 100%;" /></a> ';
                echo '</div>';
            }
        
        });

        $cpt->populate_column('feed', function($column, $post) {

            $post_id = $post->ID;
            $merchant = new ATM_Merchant($post_id);
            $buy_url = $merchant->redirect_url_buy();
            $sell_url = $merchant->redirect_url_sell();
            $description = $merchant->description();
            $postage = get_field('atm_merchant_postage', $post_id);

            echo ($buy_url ? '<i class="acf-js-tooltip dashicons dashicons-saved" style="color: #01d001;" title="'.$buy_url.'"></i>' : '<i class="dashicons dashicons-no-alt" style="color: tomato;"></i>').' Buy URL<br>';

            echo ($sell_url ? '<i class="acf-js-tooltip dashicons dashicons-saved" style="color: #01d001;" title="'.$sell_url.'"></i>' : '<i class="dashicons dashicons-no-alt" style="color: tomato;"></i>').' Sell URL <br>';

            echo ($description ? '<i class="dashicons dashicons-saved" style="color: #01d001;"></i>' : '<i class="dashicons dashicons-no-alt" style="color: tomato;"></i>').' Description <br>';

            echo (is_numeric($merchant->min_order()) ? '<i class="dashicons dashicons-saved" style="color: #01d001;"></i> Min Order: '.$merchant->min_order(true) : '<i class="dashicons dashicons-no-alt" style="color: tomato;"></i>  Min Order').'<br>';
            echo (is_numeric($merchant->max_order()) ? '<i class="dashicons dashicons-saved" style="color: #01d001;"></i> Max Order: '.$merchant->max_order(true) : '<i class="dashicons dashicons-no-alt" style="color: tomato;"></i> Max Order').'<br>';

            echo ($postage ? '<i class="dashicons dashicons-saved" style="color: #01d001;"></i>' : '<i class="dashicons dashicons-no-alt" style="color: tomato;"></i>').' Postage <br>';
            
        
        });

    }
    
    /**
     * Merchant CPT
     */
    private function register_travel_guide_cpt() {

        // CPT
        $cpt = new FL1_CPT(
            array(
                'post_type_name' => 'travel-guide',
                'plural' => 'Travel Guides',
                'menu_name' => 'Travel Guides'
            ),
            array(
                'menu_position' => 21,
                'rewrite' => array( 'slug' => 'travel-guide', 'with_front' => true ),
                'show_in_menu' => false,
                'generator' => ATM_SLUG
            )
        );

    }

    /**
	 * Remove duplicate sub page
	 *
	 * @since 1.0
	 */
	public function remove_duplicate_subpage() {
        remove_submenu_page(ATM_SLUG, ATM_SLUG);
    }

    /**
	 * Remove duplicate sub page
	 *
	 * @since 1.0
	 */
	public function acf_init() {
        
        acf_add_options_sub_page(array(
            'page_title'  => 'Settings',
            'menu_title'  => 'Settings',
            'menu_slug' => 'atm-settings',
            'parent_slug' => ATM_SLUG,
        ));

    }

    

}