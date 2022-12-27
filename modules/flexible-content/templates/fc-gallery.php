<?php
/*
------------------------------------------------
   ______      ____
  / ____/___ _/ / /__  _______  __
 / / __/ __ `/ / / _ \/ ___/ / / /
/ /_/ / /_/ / / /  __/ /  / /_/ /
\____/\__,_/_/_/\___/_/   \__, /
                         /____/
------------------------------------------------
Gallery
*/

$max_width = '';
if(get_sub_field('max_width')) {
    $max_width = 'style="max-width:'.get_sub_field('max_width').'%";';
}

// items per row
$items = get_sub_field('items_per_row');

$carousel = '';
if(get_sub_field('gallery_carousel')) { 
    $carousel = ' gallery__carousel';
}

// Images
$images = get_sub_field('gallery');
if($images):
?>
    <ul class="gallery__images<?php echo $carousel; ?>">
        <?php
            foreach($images as $image):
            $attachment_id = $image['ID'];
            $alt_text = get_post_meta($attachment_id, '_wp_attachment_image_alt', true) ?? '';
            $gallery_img = vt_resize($attachment_id,'' , 1000, 1000, true);
            $gallery_img_org = vt_resize($attachment_id,'' , 1200, 1200, false);
        ?>
            <li data-src="<?php echo $gallery_img_org['url']; ?>" class="<?php echo $items; ?>">
                <a href="#" title=""><img src="<?php echo $gallery_img['url']; ?>" alt="<?php echo $alt_text; ?>" /></a>
            </li>
        <?php endforeach; ?>
    </ul><!-- gallery__images -->
<?php endif; ?>
