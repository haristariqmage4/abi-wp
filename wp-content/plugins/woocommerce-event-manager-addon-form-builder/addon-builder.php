<?php
/* 
* Plugin Name: Woocommerce Event Manager Addon: Form Builder
* Version: 4.0.1
* Author: MagePeople Team
* Description: This plugin will add a Event Attendee Form Builder in Event Page.
* Author URI: https://www.mage-people.com/
* Text Domain: mep-form-builder
* Domain Path: /languages/
*/


if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'woocommerce/woocommerce.php' ) && is_plugin_active( 'mage-eventpress/woocommerce-event-press.php' )) {

if (!defined('MEP_STORE_URL')) { 
  define('MEP_STORE_URL', 'https://mage-people.com/');
}	
define('MEP_BUILDER_ID', 85377);
define('MEP_BUILDER_NAME', 'Woocommerce Event Manager Addon: Form Builder');

if (!class_exists('EDD_SL_Plugin_Updater')) {
  include(dirname(__FILE__) . '/license/EDD_SL_Plugin_Updater.php');
}
include(dirname(__FILE__) . '/license/main.php');
  $license_key      	= trim(get_option('mep_builder_license_key'));
  $edd_updater 		= new EDD_SL_Plugin_Updater(MEP_STORE_URL, __FILE__, array(
  'version'     		=> '4.0.1',
  'license'     		=> $license_key,
  'item_name'   		=> MEP_BUILDER_NAME,
  'item_id'     		=> MEP_BUILDER_ID,
  'author'      		=> 'MagePeople Team',
  'url'         		=> home_url(),
  'beta'        		=> false
  ));

  require_once(dirname(__FILE__) . "/inc/file_include.php");
  
}else{
function mep_builder_admin_notice_wc_not_active() {
  $class = 'notice notice-error';
  $message = __( 'Woocommerce Event Manager Addon: Form Builder is Dependent on WooCommerce & Woocommerce Event Manager', 'mep-form-builder' );
  printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
}
add_action( 'admin_notices', 'mep_builder_admin_notice_wc_not_active' );
}

/**
 * Flush rewrite rules on plugin activation.
 */
function mfb_flush_rewrite_rules() {
	add_rewrite_endpoint( 'attendee-edit', EP_PAGES );
	flush_rewrite_rules();
}

// flush rewrite rules on activation and deactivation
register_activation_hook( __FILE__, 'mfb_flush_rewrite_rules' );
register_deactivation_hook( __FILE__, 'mfb_flush_rewrite_rules' );