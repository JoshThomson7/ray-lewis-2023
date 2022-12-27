<?php
/*
*	Blog Single
*
*	@package Blog
*	@version 1.0
*/

global $post;
get_header();

$blog = new FL1_Blog($post->ID);

// Image
$blog_image = $blog->image(900, 700, true);

// Main category
$blog_cat = $blog->main_category('id=>name');

include 'blog-inner-banner.php';
?>

<div class="max__width blog__single-wrapper">
    <div class="blog__single">

        <div class="blog__info">
            <h5><a href="<?php echo esc_url(get_permalink(get_page_by_path('blog'))); ?>">&lsaquo; Blog</a> / <?php echo $blog_cat; ?></h5>
            <h1><?php echo get_the_title($post->ID); ?></h1>
            <date><?php echo $blog->date('M jS Y') ?></date>

            <?php if ($blog->excerpt(1000)) : ?>
                <p class="blog__excerpt"><?php echo $blog->excerpt(1000); ?></p>
            <?php endif; ?>
        </div>

        <?php
        if (has_post_thumbnail()) :
            $blog_image = $blog->image(900, 700, true);
        ?>
            <div class="blog__featured__image">
                <img src="<?php echo $blog_image['url'] ?>" alt="<?php the_title(); ?>">
            </div><!-- blog__featured__image -->
        <?php endif; ?>

        <?php flexible_content(); ?>
    </div><!-- blog__single -->

    <?php include 'blog-filters.php'; ?>
</div><!-- max__width -->

<?php get_footer(); ?>