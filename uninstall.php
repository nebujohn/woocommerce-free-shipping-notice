<?php
/**
 * Uninstall WooCommerce Free Shipping Notice
 *
 * @package WooCommerce_Free_Shipping_Notice
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Remove plugin options
delete_option( 'wfsn_free_shipping_threshold' );
delete_option( 'wfsn_custom_icon' );
delete_option( 'wfsn_approaching_message' );
delete_option( 'wfsn_achieved_message' );
delete_option( 'wfsn_shop_more_text' );
