<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/h8ps1tm
 * @since      1.0.0
 *
 * @package    Update_Shipping_Order_Address_Wcps
 * @subpackage Update_Shipping_Order_Address_Wcps/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Update_Shipping_Order_Address_Wcps
 * @subpackage Update_Shipping_Order_Address_Wcps/includes
 * @author     Tiago Mano <tiago@hellodev.us>
 */
class Update_Shipping_Order_Address_Wcps_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'update-shipping-order-address-wcps',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
