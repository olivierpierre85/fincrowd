<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/* Add a custom payment class to WC
  ------------------------------------------------------------ */
add_action('plugins_loaded', 'woocommerce_twocheckout', 0);

function woocommerce_twocheckout(){
    if (!class_exists('WC_Payment_Gateway'))
        return; // if the WC payment gateway class is not available, do nothing
    if(class_exists('WC_Gateway_Twocheckout'))
        return;

    class WC_Gateway_Twocheckout extends WC_Payment_Gateway{

        // Logging
        public static $log_enabled = false;
        public static $log = false;

        public function __construct(){
            $this->id               = 'twocheckout';
            $this->method_title     = '2Checkout';
            $this->method_description = sprintf('2Checkout Payment gateway is available in the %s Enterprise version %s', '<a href="https://wpneo.com/wp-crowdfunding" target="_blank">', '</a>' );
            $this->init_settings();
        }

    }

    /**
     * Add the gateway to WooCommerce
     **/
    function add_twocheckout_gateway($methods){
        $methods[] = 'WC_Gateway_Twocheckout';
        return $methods;
    }

    add_filter('woocommerce_payment_gateways', 'add_twocheckout_gateway');

}
