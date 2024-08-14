<?php
/**
 * Plugin Name: WooCommerce Free Shipping Notice
 * Plugin URI: https://github.com/ajithrn/woocommerce-free-shipping-notice
 * Description: Displays a dynamic free shipping notice on cart and checkout pages.
 * Version: 1.0.1
 * Author: Ajith R N
 * Author URI: https://ajithrn.com/
 * Text Domain: woocommerce-free-shipping-notice
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.2
 * WC requires at least: 3.0
 * WC tested up to: 9.0
 *
 * @package WooCommerce_Free_Shipping_Notice
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Define WFSN_PLUGIN_FILE.
if ( ! defined( 'WFSN_PLUGIN_FILE' ) ) {
    define( 'WFSN_PLUGIN_FILE', __FILE__ );
}

/**
 * Check if WooCommerce is active
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
    // Include the main WooCommerce_Free_Shipping_Notice class.
    if ( ! class_exists( 'WooCommerce_Free_Shipping_Notice' ) ) {
        include_once dirname( __FILE__ ) . '/includes/class-woocommerce-free-shipping-notice.php';
    }

    /**
     * Declare HPOS compatibility.
     */
    add_action(
        'before_woocommerce_init',
        function() {
            if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
                \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
            }
        }
    );

    /**
     * Main instance of WooCommerce_Free_Shipping_Notice.
     *
     * Returns the main instance of WFSN to prevent the need to use globals.
     *
     * @return WooCommerce_Free_Shipping_Notice
     */
    function WFSN() {
        return WooCommerce_Free_Shipping_Notice::instance();
    }

    // Global for backwards compatibility.
    $GLOBALS['woocommerce_free_shipping_notice'] = WFSN();
} else {
    /**
     * Display admin notice if WooCommerce is not active
     */
    function wfsn_admin_notice_woocommerce_not_active() {
        ?>
        <div class="notice notice-error">
            <p><?php esc_html_e( 'WooCommerce Free Shipping Notice requires WooCommerce to be installed and active.', 'woocommerce-free-shipping-notice' ); ?></p>
        </div>
        <?php
    }
    add_action( 'admin_notices', 'wfsn_admin_notice_woocommerce_not_active' );
}
