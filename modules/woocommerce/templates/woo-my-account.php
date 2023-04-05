<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce My Account Template
 *
 * @package modules/woocommerce
 * @version 1.0
*/

$current_user = wp_get_current_user();
?>

<header class="wc__my__account__header">
    <div class="max__width">
    	<h2>Welcome, <?php echo $current_user->user_firstname; ?></span></h2>
    </div><!-- max__width -->
</header><!-- wc__my__account__header -->
