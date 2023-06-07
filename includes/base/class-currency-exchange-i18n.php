<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://there.is.no.place.like.127.0.0.1
 * @since      1.0.0
 *
 * @package    Currency_Exchange
 * @subpackage Currency_Exchange/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Currency_Exchange
 * @subpackage Currency_Exchange/includes
 * @author     Igor Hrcek <igor@netrunner.rs>
 */
class Currency_Exchange_i18n {
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'currency-exchange',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}
