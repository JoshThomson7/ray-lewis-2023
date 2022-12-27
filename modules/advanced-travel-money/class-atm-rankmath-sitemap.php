<?php
namespace RankMath\Sitemap\Providers;

defined( 'ABSPATH' ) || exit;

class ATM_Sitemap implements Provider {

    public function handles_type( $type ) {
        return 'travel-money' === $type;
    }

    public function get_index_links( $max_entries ) {
        return [
            [
                'loc'     => \RankMath\Sitemap\Router::get_base_url( 'travel-money-sitemap.xml' ),
                'lastmod' => '',
            ]
        ];
    }

    public function get_sitemap_links( $type, $max_entries, $current_page ) {

        $modes = array('buy', 'sell');
        $currencies = \ATM_Helpers::currencies();

        foreach($modes as $mode) {
            foreach($currencies as $currency) {
                $links[] = [
                    'loc' => get_permalink(get_page_by_path('travel-money')).$mode.'/'.$currency['id'].'/',
                ];
            }
        }

        return $links;
    }

}