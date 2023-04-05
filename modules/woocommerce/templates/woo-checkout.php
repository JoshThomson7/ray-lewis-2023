<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce Checkout
 *
 * @package modules/woocommerce
 * @version 1.0
*/
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <title><?php wp_title( '-', true, 'right' ); ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />

    <?php
        wp_head();
        global $woocommerce, $current_user, $post;

        $is_user_looged_in = is_user_logged_in() ? 'true' : 'false';
    ?>
</head>

<body <?php body_class(); ?> data-logged-in="<?php echo $is_user_looged_in; ?>">

    <header class="header">
        <div class="header__main">
            
            <div class="header__main--left">
                <div class="logo">
                    <a href="<?php echo esc_url(home_url()); ?>" title="<?php bloginfo('name'); ?>">
                        <img src="<?php echo esc_url(get_stylesheet_directory_uri().'/img/logo.svg'); ?>" alt="">
                    </a>
                </div><!-- logo -->
            </div><!-- left -->

            <div class="header__main--right">
                <ul class="header__main--right-actions">
                    <li class="wc__header__back">
                        <a href="<?php echo esc_url(home_url()); ?>">
                            <span>Back to <?php bloginfo('name'); ?></span>
                        </a>
                    </li>
                    
                    <li class="wc__header__cart">
                        <a href="<?php echo wc_get_cart_url(); ?>">
                            <i class="fas fa-shopping-basket"></i>
                            <figure><?php echo WC()->cart->cart_contents_count; ?></figure>
                        </a>
                    </li>
                </ul>
            </div><!-- right -->

        </div><!-- header__main -->
    </header><!-- header -->

    <div class="wc__wrapper">
        <?php echo do_shortcode('[woocommerce_checkout]') ?>

        <div class="footer__checkout">
            <ul>
                <li>&copy;<?php echo date("Y"); ?> <?php bloginfo('name') ?> and its affiliate companies.</li>
                <?php if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'): ?>
                    <li class="secure"><i class="far fa-lock"></i> Your connection is secure</li>
                <?php endif; ?>
            </ul>
        </div><!-- footer__checkout -->
    </div>

    <?php wp_footer(); ?>
</body>
</html>