<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/* Add a custom payment class to WC
  ------------------------------------------------------------ */
add_action('plugins_loaded', 'woocommerce_wpcrowdfunding_authorizenet', 0);

function woocommerce_wpcrowdfunding_authorizenet(){
	if (!class_exists('WC_Payment_Gateway'))
		return; // if the WC payment gateway class is not available, do nothing
	if(class_exists('WC_Authorizenet_Gateway'))
		return;

	class WC_Authorizenet_Gateway extends WC_Payment_Gateway{

		// Logging
		public static $log_enabled = false;
		public static $log = false;

		public function __construct(){
			$this->id               = 'wpcrowdfunding_authorizenet';
			$this->method_title     = 'Authorize.Net Settings';
			$this->method_description = sprintf('Authorize.net Payment gateway is available in the %s Enterprise version %s', '<a href="https://wpneo.com/wp-crowdfunding" target="_blank">', '</a>' );
			$this->init_settings();
		}

	}

	/**
	 * Add the gateway to WooCommerce
	 **/
	function add_authorizenet_gateway($methods){
		$methods[] = 'WC_Authorizenet_Gateway';
		return $methods;
	}

	add_filter('woocommerce_payment_gateways', 'add_authorizenet_gateway');
}
