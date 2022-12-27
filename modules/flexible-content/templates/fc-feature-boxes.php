<?php
/*
    Feature Boxes
*/

$fb_classes = array();

$per_row_class = '';
if(get_sub_field('feature_boxes_per_row')) { 
    $fb_classes[] = get_sub_field('feature_boxes_per_row');
}

$animate_class = '';
if(get_sub_field('feature_boxes_animate')) { 
    $fb_classes[] = 'animate';
}

$fb_classes[] = get_sub_field('feature_boxes_align') ? get_sub_field('feature_boxes_align') : 'left';
?>

<div class="fc_feature_boxes_wrapper">
    <?php
        while(have_rows('feature_boxes')) : the_row();

        $gf_id = '';
    ?>
        <article class="<?php echo trim(join(' ', $fb_classes)); ?>">
            <div class="fc__feature__box__inner">
                <?php
                    if(get_sub_field('feature_box_image')):
                        $attachment_id = get_sub_field('feature_box_image');
                        $alt_text = get_post_meta($attachment_id, '_wp_attachment_image_alt', true) ?? '';
                        $image = vt_resize($attachment_id, '', 600, 600, true);
                ?>
                    <figure>
                        <img src="<?php echo $image['url'] ?>" alt="<?php echo $alt_text; ?>" />
                    </figure>
                <?php endif; ?>

                <h3>
                    <?php if(get_sub_field('feature_box_button_url')): ?><a href="<?php the_sub_field('feature_box_button_url'); ?>" title="<?php the_sub_field('feature_box_heading'); ?>"><?php endif; ?>
                        <?php the_sub_field('feature_box_heading'); ?>
                    <?php if(get_sub_field('feature_box_button_url')): ?></a><?php endif; ?>

                    <?php if(get_sub_field('feature_box_icon')): ?><figure><i class="<?php the_sub_field('feature_box_icon'); ?>"></i></figure><?php endif; ?>
                </h3>
                <?php the_sub_field('feature_box_content'); ?>

                <?php
                    if(get_sub_field('feature_box_button_url') || get_sub_field('feature_box_load_form')):

                    $gf_id = ' data-gf-ajax-trigger data-gf-id="'.get_sub_field('feature_box_form_id').'"';
                ?>
                    <a href="<?php the_sub_field('feature_box_button_url'); ?>" class="button tertiary"<?php echo $gf_id; ?>><?php the_sub_field('feature_box_button_label'); ?> <i class="ion-arrow-right-c"></i></a>
                <?php endif; ?>
            </div><!-- fc__feature__box__inner -->
        </article>
    <?php endwhile; ?>
</div><!-- fc_feature_boxes_wrapper -->
