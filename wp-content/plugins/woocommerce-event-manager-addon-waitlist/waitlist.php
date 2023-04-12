<?php

/**
 * Plugin Name: Woocommerce Event Manager Addon: WaitList
 * Plugin URI: http://mage-people.com
 * Description: Waitlist Feature fro Woocommerce Event
 * Version: 4.0.0
 * Author: MagePeople Team
 * Author URI: http://www.mage-people.com/
 * Text Domain: mage-eventpress-waitlist
 * Domain Path: /languages/
 */

if (!defined('ABSPATH')) {
  die;
} // Cannot access pages directly.
include_once(ABSPATH . 'wp-admin/includes/plugin.php');
if (is_plugin_active('woocommerce/woocommerce.php') && is_plugin_active('mage-eventpress/woocommerce-event-press.php')) {


	if (!defined('MEP_STORE_URL')) { 
		define('MEP_STORE_URL', 'https://mage-people.com/');
	}	
	define('MEP_WAITLIST_ID', 85348);
	define('MEP_WAITLIST_NAME', 'Woocommerce Event Manager Addon: Waitlist');
	define('EDD_TAB_NAME', 'Waitlist');

	if (!class_exists('EDD_SL_Plugin_Updater')) {
		include(dirname(__FILE__) . '/license/EDD_SL_Plugin_Updater.php');
	}
	include(dirname(__FILE__) . '/license/main.php');
		$license_key      	= trim(get_option('mep_waitlist_license_key'));
		$edd_updater 		= new EDD_SL_Plugin_Updater(MEP_STORE_URL, __FILE__, array(
		'version'     		=> '4.0.0',
		'license'     		=> $license_key,
		'item_name'   		=> MEP_WAITLIST_NAME,
		'item_id'     		=> MEP_WAITLIST_ID,
		'author'      		=> 'MagePeople Team',
		'url'         		=> home_url(),
		'beta'        		=> false
		));
	



  require_once(dirname(__FILE__) . "/inc/file_include.php");
} else {

  function mep_waitlist_admin_notice_wc_not_active()
  {
    $class = 'notice notice-error';
    $message = __('Woocommerce Event Manager Addon: WaitList is Dependent on WooCommerce & Woocommerce Event Manager', 'mage-eventpress-waitlist');
    printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
  }
  add_action('admin_notices', 'mep_waitlist_admin_notice_wc_not_active');
}
