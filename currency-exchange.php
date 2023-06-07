<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://there.is.no.place.like.127.0.0.1
 * @since             1.0.0
 * @package           Currency_Exchange
 *
 * @wordpress-plugin
 * Plugin Name:       Currency Exchange
 * Plugin URI:        https://there.is.no.place.like.127.0.0.1
 * Description:       Fairest exchange on the planet!
 * Version:           1.0.0
 * Author:            Igor Hrcek
 * Author URI:        https://there.is.no.place.like.127.0.0.1
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       currency-exchange
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
define( 'CURRENCY_EXCHANGE_VERSION', '1.0.0' );

/**
 * Hardcoded API Base URL
 * In real world this would be stored as an option value
 */
define( 'CURRENCY_EXCHANGE_API_BASE_URL', 'http://host.internal' );
define( 'CURRENCY_EXCHANGE_API_PORT', '32775' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-currency-exchange-activator.php
 */
function activate_currency_exchange() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/base/class-currency-exchange-activator.php';
	Currency_Exchange_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-currency-exchange-deactivator.php
 */
function deactivate_currency_exchange() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/base/class-currency-exchange-deactivator.php';
	Currency_Exchange_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_currency_exchange' );
register_deactivation_hook( __FILE__, 'deactivate_currency_exchange' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-currency-exchange.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_currency_exchange() {

	$plugin = new Currency_Exchange();
	$plugin->run();

}
run_currency_exchange();
