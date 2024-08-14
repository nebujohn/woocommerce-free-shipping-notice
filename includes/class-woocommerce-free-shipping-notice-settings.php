<?php
/**
 * WooCommerce Free Shipping Notice Settings
 *
 * @package WooCommerce_Free_Shipping_Notice
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * WooCommerce Free Shipping Notice Settings Class.
 */
class WooCommerce_Free_Shipping_Notice_Settings {

    /**
     * Constructor.
     */
    public function __construct() {
        add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_settings_page' ) );
        add_action( 'woocommerce_admin_field_wfsn_icon_upload', array( $this, 'icon_upload_field' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
    }

    /**
     * Add settings page to WooCommerce settings.
     *
     * @param array $settings WooCommerce settings pages.
     * @return array
     */
    public function add_settings_page( $settings ) {
        $settings[] = include dirname( __FILE__ ) . '/class-wc-settings-wfsn.php';
        return $settings;
    }

    /**
     * Custom icon upload field.
     *
     * @param array $value Field value.
     */
    public function icon_upload_field( $value ) {
        $default_url  = plugins_url( 'assets/images/icon-shipping.svg', WFSN_PLUGIN_FILE );
        $option_value = get_option( $value['id'], $default_url );
        ?>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
            </th>
            <td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
                <input
                    name="<?php echo esc_attr( $value['id'] ); ?>"
                    id="<?php echo esc_attr( $value['id'] ); ?>"
                    type="text"
                    style="<?php echo esc_attr( $value['css'] ); ?>"
                    value="<?php echo esc_attr( $option_value ); ?>"
                    class="<?php echo esc_attr( $value['class'] ); ?>"
                    placeholder="<?php echo esc_attr( $value['placeholder'] ); ?>"
                />
                <input
                    type="button"
                    class="button wfsn-upload-button"
                    value="<?php esc_attr_e( 'Upload Icon', 'woocommerce-free-shipping-notice' ); ?>"
                />
                <input
                    type="button"
                    class="button wfsn-default-button"
                    value="<?php esc_attr_e( 'Use Default', 'woocommerce-free-shipping-notice' ); ?>"
                    data-default="<?php echo esc_attr( $default_url ); ?>"
                />
                <p class="description"><?php echo wp_kses_post( $value['desc'] ); ?></p>
                <div class="wfsn-icon-preview" style="margin-top: 10px;">
                    <img src="<?php echo esc_url( $option_value ? $option_value : $default_url ); ?>" style="max-width: 100px; height: auto;">
                </div>
            </td>
        </tr>
        <?php
    }

    /**
     * Enqueue admin scripts.
     *
     * @param string $hook Current admin page.
     */
    public function enqueue_admin_scripts( $hook ) {
        if ( 'woocommerce_page_wc-settings' !== $hook ) {
            return;
        }

        wp_enqueue_media();
        wp_enqueue_script( 'wfsn-admin', plugins_url( 'assets/js/admin.js', WFSN_PLUGIN_FILE ), array( 'jquery' ), '1.0', true );
    }
}

new WooCommerce_Free_Shipping_Notice_Settings();
