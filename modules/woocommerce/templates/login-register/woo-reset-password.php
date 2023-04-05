<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce Reset Password Form
 *
 * @package modules/woocommerce
 * @version 1.0
*/

/**
 * Hack to make this page work
 * @see lost_password() function in core: woocommerce/includes/shortcodes/class-wc-shortcode-my-account.php
 */
if ( isset( $_COOKIE[ 'wp-resetpass-' . COOKIEHASH ] ) && 0 < strpos( $_COOKIE[ 'wp-resetpass-' . COOKIEHASH ], ':' ) ) {  // @codingStandardsIgnoreLine
    list( $rp_id, $rp_key ) = array_map( 'wc_clean', explode( ':', wp_unslash( $_COOKIE[ 'wp-resetpass-' . COOKIEHASH ] ), 2 ) ); // @codingStandardsIgnoreLine
	$userdata               = get_userdata( absint( $rp_id ) );
	$rp_login               = $userdata ? $userdata->user_login : '';
}
?>

<form method="post" class="woocommerce-ResetPassword lost_reset_password">

	<div class="wc__form__row">
		<div class="wc__form__field">
			<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_1" id="password_1" autocomplete="new-password" placeholder="New password" />
		</div>
	</div>

	<div class="wc__form__row">
		<div class="wc__form__field">
			<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_2" id="password_2" autocomplete="new-password" placeholder="Confirm password" />
		</div>
	</div>

	<input type="hidden" name="reset_key" value="<?php echo $rp_key; ?>" />
	<input type="hidden" name="reset_login" value="<?php echo $rp_login; ?>" />

	<?php do_action( 'woocommerce_resetpassword_form' ); ?>

	<div class="wc__form__row">
		<div class="wc__form__field">
			<input type="hidden" name="wc_reset_password" value="true" />
			<button type="submit" class="woocommerce-Button button" value="<?php esc_attr_e( 'Save', 'woocommerce' ); ?>"><?php esc_html_e( 'Set password', 'woocommerce' ); ?></button>
		</div>
	</div>

	<?php wp_nonce_field( 'reset_password', 'woocommerce-reset-password-nonce' ); ?>

</form>