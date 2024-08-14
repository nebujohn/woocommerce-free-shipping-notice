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
 * WC_Settings_WFSN Class.
 */
class WC_Settings_WFSN extends WC_Settings_Page {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id    = 'wfsn';
        $this->label = __( 'Free Shipping Notice', 'woocommerce-free-shipping-notice' );

        add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
        add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
        add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );
    }

    /**
     * Get settings array.
     *
     * @return array
     */
    public function get_settings() {
        $settings = array(
            array(
                'title' => __( 'Free Shipping Notice Settings', 'woocommerce-free-shipping-notice' ),
                'type'  => 'title',
                'desc'  => '',
                'id'    => 'wfsn_settings_start'
            ),
            array(
                'title'    => __( 'Free Shipping Threshold', 'woocommerce-free-shipping-notice' ),
                'desc'     => __( 'Set the amount for free shipping', 'woocommerce-free-shipping-notice' ),
                'id'       => 'wfsn_free_shipping_threshold',
                'default'  => '500',
                'type'     => 'number',
                'css'      => 'width:100px;',
                'custom_attributes' => array(
                    'min'  => '0',
                    'step' => '0.01'
                ),
            ),
            array(
                'title'    => __( 'Approaching Free Shipping Message', 'woocommerce-free-shipping-notice' ),
                'desc'     => __( 'Message to display when approaching free shipping. Use {remaining} as a placeholder for the remaining amount.', 'woocommerce-free-shipping-notice' ),
                'id'       => 'wfsn_approaching_message',
                'default'  => 'Get free shipping if you order {remaining} more!',
                'type'     => 'textarea',
                'css'      => 'width:100%; height: 75px;',
            ),
            array(
                'title'    => __( 'Free Shipping Achieved Message', 'woocommerce-free-shipping-notice' ),
                'desc'     => __( 'Message to display when free shipping is achieved.', 'woocommerce-free-shipping-notice' ),
                'id'       => 'wfsn_achieved_message',
                'default'  => 'Congratulations! Shipping is on us :)',
                'type'     => 'textarea',
                'css'      => 'width:100%; height: 75px;',
            ),
            array(
                'title'    => __( 'Shop More Button Text', 'woocommerce-free-shipping-notice' ),
                'desc'     => __( 'Text to display on the button that links to the shop page.', 'woocommerce-free-shipping-notice' ),
                'id'       => 'wfsn_shop_more_text',
                'default'  => 'Shop More',
                'type'     => 'text',
                'css'      => 'width:300px;',
            ),
            array(
                'title'    => __( 'Custom Icon', 'woocommerce-free-shipping-notice' ),
                'desc'     => __( 'Upload a custom icon for the shipping notice', 'woocommerce-free-shipping-notice' ),
                'id'       => 'wfsn_custom_icon',
                'default'  => '',
                'type'     => 'wfsn_icon_upload',
                'css'      => 'width:300px;',
            ),
            array(
                'type' => 'sectionend',
                'id'   => 'wfsn_settings_end'
            ),
        );

        return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings );
    }

    /**
     * Output the settings.
     */
    public function output() {
        $settings = $this->get_settings();
        WC_Admin_Settings::output_fields( $settings );
    }

    /**
     * Save settings.
     */
    public function save() {
        $settings = $this->get_settings();
        WC_Admin_Settings::save_fields( $settings );
    }
}

return new WC_Settings_WFSN();

