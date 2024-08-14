<?php
/**
 * Main WooCommerce_Free_Shipping_Notice Class.
 *
 * @package WooCommerce_Free_Shipping_Notice
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Main WooCommerce_Free_Shipping_Notice Class.
 */
final class WooCommerce_Free_Shipping_Notice {

    /**
     * Single instance of the WooCommerce_Free_Shipping_Notice object.
     *
     * @var WooCommerce_Free_Shipping_Notice
     */
    protected static $instance = null;

    /**
     * Main WooCommerce_Free_Shipping_Notice Instance.
     *
     * Ensures only one instance of WooCommerce_Free_Shipping_Notice is loaded or can be loaded.
     *
     * @static
     * @return WooCommerce_Free_Shipping_Notice - Main instance.
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * WooCommerce_Free_Shipping_Notice Constructor.
     */
    public function __construct() {
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Include required core files used in admin and on the frontend.
     */
    private function includes() {
        include_once dirname( __FILE__ ) . '/class-woocommerce-free-shipping-notice-settings.php';
    }

    /**
     * Hook into actions and filters.
     */
    private function init_hooks() {
        add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
        add_action( 'woocommerce_before_cart', array( $this, 'display_free_shipping_notice' ) );
        add_action( 'woocommerce_before_checkout_form', array( $this, 'display_free_shipping_notice' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'wp_ajax_update_shipping_notice', array( $this, 'ajax_update_shipping_notice' ) );
        add_action( 'wp_ajax_nopriv_update_shipping_notice', array( $this, 'ajax_update_shipping_notice' ) );
        add_action( 'wp_head', array( $this, 'add_custom_css' ) );
    }

    /**
     * Load Localisation files.
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain( 'woocommerce-free-shipping-notice', false, plugin_basename( dirname( WFSN_PLUGIN_FILE ) ) . '/languages' );
    }

    /**
     * Display free shipping notice.
     */
    public function display_free_shipping_notice() {
        $threshold = floatval( get_option( 'wfsn_free_shipping_threshold', 500 ) );
        $current   = floatval( WC()->cart->subtotal );
        $notice    = $this->generate_free_shipping_notice( $threshold, $current );
        wc_print_notice( $notice, 'notice' );
    }

    /**
     * Generate free shipping notice HTML.
     *
     * @param float $threshold Free shipping threshold.
     * @param float $current Current cart subtotal.
     * @return string Notice HTML.
     */
    private function generate_free_shipping_notice( $threshold, $current ) {
        if ( $current < $threshold ) {
            $remaining        = wc_price( $threshold - $current );
            $message_template = get_option( 'wfsn_approaching_message', 'Get free shipping if you order {remaining} more!' );
            $notice_text      = str_replace( '{remaining}', '<span class="wfsn-remaining-amount">&nbsp;' . $remaining . '&nbsp;</span>', $message_template );
            
            $shop_more_text = get_option( 'wfsn_shop_more_text', 'Shop More' );
            $shop_url       = wc_get_page_permalink( 'shop' );
            $shop_button    = ' <a href="' . esc_url( $shop_url ) . '" class="wfsn-shop-more">' . esc_html( $shop_more_text ) . '</a>';
            
            $notice_text .= $shop_button;
        } else {
            $notice_text = get_option( 'wfsn_achieved_message', 'Congratulations! Shipping is on us :)' );
        }
    
        $icon_url = esc_url( get_option( 'wfsn_custom_icon', plugins_url( 'assets/images/icon-shipping.svg', WFSN_PLUGIN_FILE ) ) );
        
        $notice  = '<div id="wfsn_notice">';
        $notice .= '<img src="' . $icon_url . '" alt="Shipping icon" class="wfsn-icon">';
        $notice .= wp_kses_post( $notice_text );
        $notice .= '</div>';
    
        return $notice;
    }

    /**
     * Enqueue scripts and styles.
     */
    public function enqueue_scripts() {
        if ( is_cart() || is_checkout() ) {
            wp_enqueue_script( 'jquery' );
            add_action( 'wp_footer', array( $this, 'print_js' ) );
        }
    }

    /**
     * Print JavaScript in footer.
     */
    public function print_js() {
        ?>
        <script type="text/javascript">
        jQuery(function($) {
            function updateShippingNotice() {
                $.ajax({
                    url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
                    type: 'POST',
                    data: {
                        action: 'update_shipping_notice',
                        security: '<?php echo wp_create_nonce( 'wfsn_update_notice' ); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#wfsn_notice').replaceWith(response.data.notice);
                        }
                    }
                });
            }

            $(document.body).on('updated_cart_totals updated_checkout', updateShippingNotice);
        });
        </script>
        <?php
    }

    /**
     * AJAX handler to update shipping notice.
     */
    public function ajax_update_shipping_notice() {
        check_ajax_referer( 'wfsn_update_notice', 'security' );

        $threshold = floatval( get_option( 'wfsn_free_shipping_threshold', 500 ) );
        $current   = floatval( WC()->cart->subtotal );
        $notice    = $this->generate_free_shipping_notice( $threshold, $current );
        wp_send_json_success( array( 'notice' => $notice ) );
    }

    /**
     * Add custom CSS to head.
     */
    public function add_custom_css() {
        ?>
        <style type="text/css">
            #wfsn_notice {
                display: flex;
                align-items: center;
            }
            .wfsn-icon {
                width: 24px;
                height: 24px;
                margin-right: 10px;
            }
            .wfsn-shop-more {
                margin-left: 25px;
            }
            .wfsn-remaining-amount {
                padding: 0 3px;
            }
        </style>
        <?php
    }
}

