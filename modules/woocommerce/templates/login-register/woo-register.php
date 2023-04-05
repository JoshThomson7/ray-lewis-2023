<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce Register Form
 *
 * @package modules/woocommerce
 * @version 1.0
*/

do_action( 'woocommerce_before_customer_login_form' );
?>
<form method="post" class="register">

	<?php do_action( 'woocommerce_register_form_start' ); ?>

    <div class="wc__form__row flex">
        <div class="wc__form__field">
            <label for="reg_billing_first_name"><?php _e( 'First name', 'woocommerce' ); ?> <span class="required">*</span></label>
        	<input type="text" name="billing_first_name" id="reg_billing_first_name" value="<?php if ( ! empty( $_POST['billing_first_name'] ) ) esc_attr_e( $_POST['billing_first_name'] ); ?>" placeholder="First name" />
        </div><!-- wc__form__field -->

        <div class="wc__form__field">
        	<label for="reg_billing_last_name"><?php _e( 'Surname', 'woocommerce' ); ?> <span class="required">*</span></label>
        	<input type="text" name="billing_last_name" id="reg_billing_last_name" value="<?php if ( ! empty( $_POST['billing_last_name'] ) ) esc_attr_e( $_POST['billing_last_name'] ); ?>" placeholder="Surname" />
        </div><!-- wc__form__field -->
    </div><!-- wc__form__row -->

    <div class="wc__form__row">
        <div class="wc__form__field">
            <label for="reg_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?> <span class="required">*</span></label>
            <input type="email" name="email" id="reg_email" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" placeholder="Email address" />
            <input type="hidden" name="username" id="reg_username" value="" />
        </div><!-- wc__form__field -->
    </div><!-- wc__form__row -->

    <script>
        jQuery(document).ready(function($) {
            $('.register').on('submit', function() {
                var email = $('input[name="email"]').val();
                $('input[name="username"]').val(email);
            });
        });
    </script>

    <div class="wc__form__row">
        <div class="wc__form__field">
            <label for="reg_password"><?php esc_html_e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>
            <input type="password" name="password" id="reg_password" placeholder="Password" />
        </div><!-- wc__form__field -->
    </div><!-- wc__form__row -->

	<?php do_action( 'woocommerce_register_form' ); ?>

    <div class="wc__form__row">
        <div class="wc__form__field submit">
            <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
			<button type="submit" name="register" class="button" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"><?php esc_html_e( 'Register', 'woocommerce' ); ?></button>
        </div><!-- wc__form__field -->
    </div><!-- wc__form__row -->

	<?php do_action( 'woocommerce_register_form_end' ); ?>

</form>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>