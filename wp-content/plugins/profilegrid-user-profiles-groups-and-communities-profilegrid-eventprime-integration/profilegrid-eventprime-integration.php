<?php

/**
 * @link              http://profilegrid.co
 * @since             1.0.0
 * @package           Profilegrid_EventPrime_Integration
 *
 * @wordpress-plugin
 * Plugin Name:       ProfileGrid EventPrime Integration
 * Plugin URI:        http://profilegrid.co
 * Description:       Create ProfileGrid Group Events by Integrating ProfileGrid User Groups with EventPrime Events.
 * Version:           1.5
 * Author:            profilegrid
 * Author URI:        http://profilegrid.co
 * License:           Commercial/ Proprietary
 * Text Domain:       profilegrid-eventprime-integration
 * Domain Path:       /languages
 * WC requires at least: 3.0.0
 * WC tested up to: 4.9.2
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-profilegrid-eventprime-integration-activator.php
 */
function activate_profilegrid_eventprime_integration() {
	$pm_woocommerce_activator = new Profilegrid_EventPrime_Integration_Activator;
	$pm_woocommerce_activator->activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-profilegrid-eventprime-integration-deactivator.php
 */
function deactivate_profilegrid_eventprime_integration() {
        $pm_woocommerce_deactivator = new Profilegrid_EventPrime_Integration_Deactivator();
	$pm_woocommerce_deactivator->deactivate();
}

register_activation_hook( __FILE__, 'activate_profilegrid_eventprime_integration' );
register_deactivation_hook( __FILE__, 'deactivate_profilegrid_eventprime_integration' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-profilegrid-eventprime-integration.php';
require_once plugin_dir_path( __FILE__ ) . 'plugin-updates/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker('http://profilegrid.co/eventprime_integration_metadata.json', __FILE__, 'profilegrid-user-profiles-groups-and-communities-profilegrid-eventprime-integration');
 
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_profilegrid_eventprime_integration() {

	$plugin = new Profilegrid_EventPrime_Integration();
	$plugin->run();

}
run_profilegrid_eventprime_integration();
