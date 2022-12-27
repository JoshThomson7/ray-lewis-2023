<?php
/**
 * AVB Banner Image
 * Class in charge of the Image type banner
 * 
 * @package AVB
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class AVB_Banner_Image extends AVB_Banner {

    public function image($return = 'url', $width = 2000, $height = 1049, $crop = true) {

        $image_id = $this->get_prop('image');
        $image = '';
        if($image_id) {
            $image = vt_resize($image_id, '', $width, $height, $crop);
            $image['alt'] = get_post_meta($image_id, '_wp_attachment_image_alt', true) ?? '';
            return isset($image[$return]) ? $image[$return] : null;
        }

        return null;

    }

}
