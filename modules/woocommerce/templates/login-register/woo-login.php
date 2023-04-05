<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce Login Form
 *
 * @package modules/woocommerce
 * @version 1.0
*/

do_action( 'woocommerce_before_customer_login_form' );
?>

<form class="woocommerce-form woocommerce-form-login login" method="post">

    <?php do_action( 'woocommerce_login_form_start' ); ?>

    <div class="wc__form__row">
        <div class="wc__form__field">
            <label for="username"><?php esc_html_e( 'Email', 'woocommerce' ); ?> <span class="required">*</span></label>
            <input type="text" name="username" id="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" placeholder="Email address" />
        </div><!-- wc__form__field -->
    </div><!-- wc__form__row -->

    <div class="wc__form__row">
        <div class="wc__form__field">
            <label for="password"><?php esc_html_e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>
            <input type="password" name="password" id="password" placeholder="Password" />
        </div><!-- wc__form__field -->
    </div><!-- wc__form__row -->

    <?php do_action( 'woocommerce_login_form' ); ?>

    <div class="wc__form__row">
        <div class="wc__form__field submit">
            <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
            <div class="wc__remember__me">
                <input name="rememberme" type="checkbox" id="rememberme" value="forever" />
                <label for="rememberme">Remember me</label>
            </div><!-- wc__remember__me -->
            <button type="submit" name="login" class="button" value="<?php esc_attr_e( 'Sign in', 'woocommerce' ); ?>"><?php esc_html_e( 'Sign in', 'woocommerce' ); ?></button>
        </div><!-- wc__form__field -->
    </div><!-- wc__form__row -->

    <div class="wc__form__row">
        <div class="wc__form__field wc__forgot__password">
            <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Forgot your password?', 'woocommerce' ); ?></a>
        </div><!-- wc__form__field -->
    </div><!-- wc__form__row -->

    <?php do_action( 'woocommerce_login_form_end' ); ?>

</form>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>