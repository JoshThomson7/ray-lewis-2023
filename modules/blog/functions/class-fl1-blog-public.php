<?php
/**
 * FL1 Blog Public
 *
 * Class in charge of FW Public facing side
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class FL1_Blog_Public {

    public function init() {

        add_action('wp_enqueue_scripts', array($this, 'enqueue'));

        // Templates
        add_filter('page_template', array($this, 'blog_templates'));
        add_filter('single_template', array($this, 'blog_single_template' ));
        add_filter('category_template', array($this, 'blog_category_template'));
        add_filter('archive_template', array($this, 'blog_category_template'));

    }

    public function enqueue() {

        if(is_page(array('blog', 'news')) || is_single() || is_category() || is_archive() || is_tag()) {
            wp_enqueue_style('fl1-blog', FL1_BLOG_URL . 'assets/css/fl1-blog.min.css', array(), FL1_BLOG_VERSION);
        }
    
    }

    public function blog_pages() {
        $pages = array(
            array(
                'name'  => 'blog',
                'title' => 'Blog'
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

    public function blog_templates($page_template) {
        global $post;

        if(is_page('blog')) {
            $page_template = FL1_BLOG_PATH . 'templates/blog.php';
        }

        return $page_template;

    }
    
    public function blog_single_template($single_template) {
        global $post;

        if ($post->post_type === 'post') {
            $single_template = FL1_BLOG_PATH . 'templates/blog-single.php';
        }

        return $single_template;
    }

    public function blog_category_template( $archive_template ) {

        $archive_template = FL1_BLOG_PATH . 'templates/blog.php';

        return $archive_template;
    }

}

