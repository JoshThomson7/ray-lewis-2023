<?php
/**
 * Blog Item
 *
 * @package Blog
 * @version 1.0
*/

$blog = new FL1_Blog($blog_id);

// Image
$blog_image = $blog->image(900, 500, true);
$banner_image = '';
if(!empty($blog_image)) {
    $banner_image = ' style="background-image: url('.$blog_image['url'].')"';
} else {
    $banner_image = ' style="background-image: url('.get_stylesheet_directory_uri().'/img/sq-blog-placeholder.jpg)"';
}

// Main category
$blog_cat = $blog->main_category('id=>name');
?>
<article class="blog__article" data-post-id="<?php echo $blog_id; ?>">
    <a class="blog__img" href="<?php echo $blog->url(); ?>" <?php echo $banner_image; ?>></a>
    
    <div class="blog__content">
        <?php if($blog_cat): ?><h5><?php echo $blog_cat; ?></h5><?php endif; ?>
        <h2><a href="<?php echo $blog->url(); ?>" title="<?php echo $blog->title(); ?>"><?php echo $blog->title(); ?></a></h2>

        <date>
            <?php echo $blog->date('M jS Y') ?>
        </date>
        
        <p><?php echo $blog->excerpt(55); ?></p>

        <div class="blog__more">
            <a href="<?php echo $blog->url(); ?>" class="button primary gradient">
                <span>Read more</span>
            </a>
        </div><!-- blog__more -->
    </div>
</article><!-- featured__blog -->