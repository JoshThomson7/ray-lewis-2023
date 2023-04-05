<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce Register Template
 *
 * @package modules/woocommerce
 * @version 1.0
*/
if(is_user_logged_in()) {
    wp_redirect(get_permalink(get_option('woocommerce_myaccount_page_id')));
}

$referrer = $_SERVER['HTTP_REFERER'];
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

    <section class="wc__login__register" data-logged-in="<?php echo $is_user_looged_in; ?>">

        <div class="wc__login__register--back">
            <a href="<?php echo esc_url(home_url()); ?>">
                <i class="fa-regular fa-times"></i>
            </a>
        </div><!-- wc__login__register back -->

        <div class="wc__login__register--wrapper">

            <div class="wc__login__register--left" style="background-image: url(<?php echo esc_url(get_stylesheet_directory_uri()).'/img/euphoria-login.png'; ?>)"></div>

            <div class="wc__login__register--right">
                <figure>
                    <a href="<?php echo esc_url(home_url()); ?>" title="<?php bloginfo('name'); ?>">
                        <img src="<?php echo esc_url(get_stylesheet_directory_uri().'/img/logo.svg'); ?>" alt="">
                    </a>
                </figure>

                <div class="wc__login__register--form">

                    <div class="wc__is__user">

                        <?php if(is_page('register')): ?>
                            <h2>Get started.</h2>
                            <p>Already registered? <a href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>">Log in here.</a></p>

                        <?php elseif(is_wc_endpoint_url( 'lost-password' )): ?>
                            <?php if(isset($_GET['show-reset-form']) && $_GET['show-reset-form'] === 'true'): ?>
                                <h2>Set your password</h2>
                                <p>Enter your password below. <a href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>">Cancel and back to login.</a></p>
                            <?php
                                else:
                                $back_to_login_url = strpos($referrer, 'book') !== false ? esc_url(get_permalink(get_page_by_path('book'))) : get_permalink(get_option('woocommerce_myaccount_page_id'));
                            ?>
                                <h2>Forgot your password?</h2>
                                <p>Please enter your username or email address. You will receive a link to create a new password via email. <a href="<?php echo $back_to_login_url; ?>">Back to login.</a></p>
                            <?php endif; ?>

                        <?php else: ?>
                            <h2>Welcome back.</h2>
                            <p>New to <?php bloginfo('name'); ?>? <a href="<?php echo esc_url(home_url()); ?>/register/">Register here.</a></p>

                        <?php endif; ?>
                        
                    </div><!-- wc__is__user -->

                    <ul class="woocommerce-error login-errors" role="alert">
                        <li></li>
                    </ul>

                    <?php if(isset($_GET['password-reset']) && $_GET['password-reset'] === 'true'): ?>
                        <ul class="woocommerce-message" role="alert">
                            <li>Password set successfully. You can now log in to your account.</li>
                        </ul>
                    <?php elseif(isset($_GET['reset-link-sent']) && $_GET['reset-link-sent'] === 'true'): ?>
                        <ul class="woocommerce-message" role="alert">
                            <li>We've sent you an email. If your account is registered you'll be able to reset your password.<br>Please make sure you also check your SPAM folder.</li>
                        </ul>
                    <?php endif; ?>

                    <?php wc_print_notices(); ?>

                    <?php                  
                        if(is_page('register')) {
                            require_once(wc_path().'templates/login-register/woo-register.php');
                        
                        } elseif(is_wc_endpoint_url( 'lost-password' )) {
                            if(isset($_GET['show-reset-form']) && $_GET['show-reset-form'] === 'true') { 
                                require_once(wc_path().'templates/login-register/woo-reset-password.php');
                            } else {
                                require_once(wc_path().'templates/login-register/woo-forgot-password.php');
                            }
                        
                        } else { 
                            require_once(wc_path().'templates/login-register/woo-login.php');
                        }
                    ?>

                    <div class="wc__login__register--footer">

                        <ul>
                            <li>&copy; <?php bloginfo('name'); ?> and its affiliate companies.</li>
                            <?php if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'): ?>
                                <li class="secure"><i class="far fa-lock"></i> Your connection is secure</li>
                            <?php endif; ?>

                            <!-- <li class="social"><a href="https://www.facebook.com/ThePriceIsWight/" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                            <li class="social"><a href="https://twitter.com/ThePriceIsWight" target="_blank"><i class="fab fa-twitter"></i></a></li>
                            <li class="social"><a href="https://plus.google.com/104981887617832465575" target="_blank"><i class="fab fa-google-plus-g"></i></a></li>
                            <li class="social"><a href="https://www.youtube.com/channel/UCc3fIYKnuabTQCDYK1PEnRg" target="_blank"><i class="fab fa-youtube"></i></a></li> -->
                        </ul>

                    </div><!-- wc__login__register meta -->

                </div><!-- wc__login__register form -->
            </div>

        </div><!-- max__width -->

    </section><!-- wc__wrapper -->

    <?php wp_footer(); ?>
</body>
</html>
