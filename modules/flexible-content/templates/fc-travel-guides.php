<?php
/**
 * Travel Guides
 */
global $paged;

$travel_guides_display = get_sub_field('travel_guides_display');

$travel_guides = array();
$travel_guides_args = array(
    'post_type' => 'travel-guide',
    'post_status' => 'publish',
    'orderby' => 'title',
    'order' => 'ASC',
    'posts_per_page' => 32,
    'paged' => $paged
);

if($travel_guides_display === 'custom') {
    $travel_guides_ids = get_sub_field('travel_guides') ?? array();
    $travel_guides_args['post__in'] = $travel_guides_ids;
    unset($travel_guides_args['posts_per_page']);
    unset($travel_guides_args['paged']);
}

$travel_guide_query = new WP_Query($travel_guides_args);
$travel_guides = $travel_guide_query->posts;
?>

<div class="travel-guides">
    <?php
        foreach($travel_guides as $travel_guide_id):
        $image_id = get_field('avb_0_image', $travel_guide_id);
        $image = vt_resize($image_id, '', 800, 800, true);
        $image_url = $image && isset($image['url']) ? $image['url'] : null;
        $country = get_field('travel_guide_country', $travel_guide_id);
    ?>
        <a href="<?php echo get_permalink($travel_guide_id); ?>">
            <div class="padder">
                <figure style="background-image: url(<?php echo $image_url; ?>);"></figure>
                <h3>
                    <?php if($country): ?>
                        <span class="flag">
                            <img src="<?php echo ATM_URL.'assets/img/countries/'.$country.'.svg'; ?>">
                        </span>
                    <?php endif; ?>
                    <?php echo get_the_title($travel_guide_id); ?>
                    <i class="fa-regular fa-arrow-right-long"></i>
                </h3>
            </div>
        </a>
    <?php endforeach; ?>
</div>

<?php FL1_Helpers::pagination($travel_guide_query->max_num_pages); ?>