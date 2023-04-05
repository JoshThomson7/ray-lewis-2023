<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce Forgot Password Form
 *
 * @package modules/woocommerce
 * @version 1.0
*/

do_action( 'woocommerce_before_lost_password_form' );
?>

<form method="post" class="woocommerce-ResetPassword lost_reset_password">

	<div class="wc__form__row">
		<div class="wc__form__field">
            <label for="email"><?php esc_html_e( 'Email', 'woocommerce' ); ?> <span class="required">*</span></label>
			<input class="woocommerce-Input woocommerce-Input--text input-text" type="text" name="user_login" id="user_login" autocomplete="username" placeholder="Email address" />
		</div>
	</div>

	<?php do_action( 'woocommerce_lostpassword_form' ); ?>

	<div class="wc__form__row">
		<div class="wc__form__field">
			<input type="hidden" name="wc_reset_password" value="true" />
			<button type="submit" class="woocommerce-Button button" value="<?php esc_attr_e( 'Reset password', 'woocommerce' ); ?>"><?php esc_html_e( 'Reset password', 'woocommerce' ); ?></button>
		</div>
	</div>

	<?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>

</form>
<?php do_action( 'woocommerce_after_lost_password_form' ); ?>