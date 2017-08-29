<?php
/*
 * Plugin Name:       WP Crowdfunding (1.6.3) - FinCrowd
 * Plugin URI:        https://wpneo.com/wp-crowdfunding
 * Description:       WP crowdfunding for collect fund and investment - Modify for Five Insurance
 * Version:           1.6.3
 * Author:            WPNEO & Olivier PIERRE
 * Author URI:        https://wpneo.com/wp-crowdfunding
 * Text Domain:       wp-crowdfunding
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
	require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}

// language
add_action( 'init', 'wpneo_crowdfunding_language_load' );
function wpneo_crowdfunding_language_load(){
	$plugin_dir = basename(dirname(__FILE__))."/languages/";
	load_plugin_textdomain( 'wp-crowdfunding', false, $plugin_dir );
}


/**
 * Define wpneo_crowdfunding version
 */

define('WPNEO_CROWDFUNDING_VERSION', '1.6.3');

/**
 * Type of WPNEO CrowdFunding type
 */

define('WPNEO_CROWDFUNDING_TYPE', 'free');

/**
 * Define wpneo_crowdfunding plugin's url path
 */
define('WPNEO_CROWDFUNDING_DIR_URL', plugin_dir_url(__FILE__));

/**
 * Define wpneo_crowdfunding plugin's physical path
 */
define('WPNEO_CROWDFUNDING_DIR_PATH', plugin_dir_path(__FILE__));

/**
 * Define WPNEO_CROWDFUNDING_PLUGIN_BASENAME
 */

define('WPNEO_CROWDFUNDING_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Include Require File
 */
include_once WPNEO_CROWDFUNDING_DIR_PATH.'includes/wpneo-crowdfunding-general-functions.php';
include_once WPNEO_CROWDFUNDING_DIR_PATH.'admin/menu-settings.php';
require_once WPNEO_CROWDFUNDING_DIR_PATH.'includes/class-wpneo-crowdfunding-initial-setup.php';



/**
 * Some task during plugin activation
 */
register_activation_hook(__FILE__, array('Wpneo_Crowdfunding_Initial_Setup', 'initial_plugin_setup'));

/**
 * Checking vendor
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || is_plugin_active_for_network( 'woocommerce/woocommerce.php' )  ) {
	require_once WPNEO_CROWDFUNDING_DIR_PATH.'includes/class-wpneo-crowdfunding-base.php';
	require_once WPNEO_CROWDFUNDING_DIR_PATH.'includes/woocommerce/class-wpneo-crowdfunding.php';
	require_once WPNEO_CROWDFUNDING_DIR_PATH.'includes/class-wpneo-crowdfunding-frontend-dashboard.php';
	Wpneo_Crowdfunding();
}else{
	add_action('admin_notices', array('Wpneo_Crowdfunding_Initial_Setup', 'no_vendor_notice'));
}
