<?php

/**
 * @link              https://github.com/h8ps1tm
 * @since             1.0.0
 * @package           Update_Shipping_Order_Address_Wcps
 *
 * @wordpress-plugin
 * Plugin Name:       Update Shipping Order Address - WC Pickup Store
 * Plugin URI:        https://hellodev.us
 * Description:       This plugin allows updating the Shipping address order to the information that is registered in the Store.
 * Version:           1.0.0
 * Author:            Tiago Mano
 * Author URI:        https://github.com/h8ps1tm
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       update-shipping-order-address-wcps
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'UPDATE_SHIPPING_ORDER_ADDRESS_WCPS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-update-shipping-order-address-wcps-activator.php
 */
function activate_update_shipping_order_address_wcps() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-update-shipping-order-address-wcps-activator.php';
	Update_Shipping_Order_Address_Wcps_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-update-shipping-order-address-wcps-deactivator.php
 */
function deactivate_update_shipping_order_address_wcps() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-update-shipping-order-address-wcps-deactivator.php';
	Update_Shipping_Order_Address_Wcps_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_update_shipping_order_address_wcps' );
register_deactivation_hook( __FILE__, 'deactivate_update_shipping_order_address_wcps' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-update-shipping-order-address-wcps.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_update_shipping_order_address_wcps() {

	$plugin = new Update_Shipping_Order_Address_Wcps();
	$plugin->run();

}
run_update_shipping_order_address_wcps();
