<?php
$blog_cats = FL1_Blogs::get_categories();
?>
<div class="blog__filters">
    <article>
        <h4>Categories</h4>
        <ul>
            <li>
                <a href="<?php echo home_url('/blog/'); ?>" <?php if(!$term->taxonomy): ?>class="active"<?php endif; ?>>Recent articles</a>
            </li>
            <?php
                if(!empty($blog_cats)):
                    foreach($blog_cats as $blog_cat):
                        $active = '';
                        if($term->term_id == $blog_cat->term_id) {
                            $active = 'active';
                        }
                ?>
                    <li>
                        <a href="<?php echo get_term_link($blog_cat, 'category'); ?>" class="<?php echo $active; ?>"><?php echo $blog_cat->name; ?></a>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </article>

    <article>
        <h4>Tags</h4>
        <div class="tagcloud">
            <?php 
                $args = array(
                    'smallest'                  => 8, 
                    'largest'                   => 22,
                    'unit'                      => 'px', 
                    'number'                    => 45,
                    'taxonomy'                  => 'post_tag'
                );
                wp_tag_cloud($args);
            ?>
        </div><!-- tagcloud -->
    </article>
</div>