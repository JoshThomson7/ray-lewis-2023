<?php
$blog_banner_id = get_field('avb_0_image', 1463); // Blog page

$blog_banner = '';
if ($blog_banner_id) {
    $blog_banner = vt_resize($blog_banner_id, '', 1920, 1049, true);
    $blog_banner = $blog_banner['url'];
}

if ($blog_banner) : ?>
    <section class="avb">

        <div class="avb-banners avb-vh avb-dots- avb-inner avb-has-form">
            <div class="avb-banner avb-vh" data-type="avb_image">
                <div class="avb-banner__caption">
                    <div class="max__width">
                        <div class="avb-banner__caption-wrap">
                            <h1>Blog</h1>
                        </div>
                    </div>
                </div>

                <figure class="avb-banner__overlay opacity-40"></figure>

                <div class="avb-banner__media">
                    <div class="avb-banner__medium image show" style="background-image:url(<?php echo $blog_banner; ?>);" role="img" alt="Blog">
                        <img alt="Blog" src="<?php echo $blog_banner; ?>">
                    </div>
                </div>
            </div>                
        </div><!-- avb-banners -->
    </section>
<?php endif; ?>