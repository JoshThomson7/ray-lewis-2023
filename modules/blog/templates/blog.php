<?php
/**
 * Blog
 */

global $paged;
get_header();

$term = get_queried_object();

$title = 'Blog';
$has_featured = true;

$blog_args = array(
    'posts_per_page' => 1,
    'paged' => $paged
);

$featured_blog = FL1_Blogs::get_blogs($blog_args);
$featured_blog_id = reset($featured_blog['posts']);

if($term->taxonomy === 'category' || $term->taxonomy === 'post_tag') {

    $has_featured = false;
    $title = 'Blog - '.$term->name;
    $featured_blog_id = 0;

    // Featured

    $blog_args['tax_query'] = array(
        array(
            'taxonomy' => $term->taxonomy,
            'field' => 'id',
            'terms' => $term->term_id
        )
    );

}

$blog_args['posts_per_page'] = 15;
$blogs = FL1_Blogs::get_blogs($blog_args);

if($term->taxonomy === 'category' || $term->taxonomy === 'post_tag') {
    include 'blog-inner-banner.php';
} else {
    AVB::avb_banners();
}
?>
<section class="blog">
    <div class="max__width">
        <div class="blog__loop grid">
            <?php include FL1_BLOG_PATH .'templates/blog-loop.php'; ?>
            <!-- <?php FL1_Helpers::pagination($blogs['max_num_pages']); ?> -->
        </div>

        <?php include 'blog-filters.php'; ?>
    </div><!-- max__width -->
</section><!-- blog -->

<?php get_footer(); ?>
