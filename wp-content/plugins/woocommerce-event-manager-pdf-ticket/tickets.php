<?php 
/* 
* Plugin Name: Woocommerce Event Manager Addon: PDF Tickets
* Version: 4.0.3
* Author: MagePeople Team
* Description: PDF Ticketing system for WooCommerce Event Manager Plugin
* Author URI: http://www.mage-people.com/
* Text Domain: mage-eventpress-pdf
* Domain Path: /languages/
*/

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'woocommerce/woocommerce.php' ) && is_plugin_active( 'mage-eventpress/woocommerce-event-press.php' )) {


    if (!defined('MEP_STORE_URL')) { 
      define('MEP_STORE_URL', 'https://mage-people.com/');
      }	
      define('MEP_PDF_ID', 85376);
      define('MEP_PDF_NAME', 'Woocommerce Event Manager Addon: PDF Tickets');
      
      if (!class_exists('EDD_SL_Plugin_Updater')) {
      include(dirname(__FILE__) . '/license/EDD_SL_Plugin_Updater.php');
      }
      include(dirname(__FILE__) . '/license/main.php');
      $license_key      	= trim(get_option('mep_pdf_license_key'));
      $edd_updater 		    = new EDD_SL_Plugin_Updater(MEP_STORE_URL, __FILE__, array(
      'version'     		  => '4.0.3',
      'license'     		  => $license_key,
      'item_name'   		  => MEP_PDF_NAME,
      'item_id'     		  => MEP_PDF_ID,
      'author'      		  => 'MagePeople Team',
      'url'         		  => home_url(),
      'beta'        		  => false
      ));


    if( ! defined( 'WBTM_PRO_PLUGIN_URL' ) ) define( 'WBTM_PRO_PLUGIN_URL', plugins_url('/', __FILE__) );
    if( ! defined( 'WBTM_PRO_PLUGIN_DIR' ) ) define( 'WBTM_PRO_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
    require_once(dirname(__FILE__) . "/inc/file_include.php");

}else{

function mep_pdf_ticket_admin_notice_wc_not_active() {
  $class = 'notice notice-error';
  $message = __( 'Woocommerce Event Manager Addon: PDF Tickets is Dependent on WooCommerce & Woocommerce Event Manager', 'mage-eventpress-pdf' );
  printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
}
add_action( 'admin_notices', 'mep_pdf_ticket_admin_notice_wc_not_active' );

}