<?php
/*
*   Default page template
*/
get_header();

global $post;
$post_id = $post->ID;
?>
    <?php if(is_account_page() || is_cart() || is_checkout()): ?>
        <div class="wc__wrapper">
            <?php while(have_posts()) : the_post(); ?>
                <?php the_content(); ?>
            <?php endwhile; ?>
        </div><!-- wc__wrapper -->
    <?php
        else:
            AVB::avb_banners();
            flexible_content();
        endif;
    ?>

<?php get_footer(); ?>
