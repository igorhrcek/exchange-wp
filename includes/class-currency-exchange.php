<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://there.is.no.place.like.127.0.0.1
 * @since      1.0.0
 *
 * @package    Currency_Exchange
 * @subpackage Currency_Exchange/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Currency_Exchange
 * @subpackage Currency_Exchange/includes
 * @author     Igor Hrcek <igor@netrunner.rs>
 */
class Currency_Exchange {
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Currency_Exchange_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'CURRENCY_EXCHANGE_VERSION' ) ) {
			$this->version = CURRENCY_EXCHANGE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'currency-exchange';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Currency_Exchange_Loader. Orchestrates the hooks of the plugin.
	 * - Currency_Exchange_i18n. Defines internationalization functionality.
	 * - Currency_Exchange_Admin. Defines all hooks for the admin area.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/base/class-currency-exchange-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/base/class-currency-exchange-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-currency-exchange-admin.php';

		/**
		 * API related classes
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/api/Client.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/api/Users.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/api/Accounts.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/api/Currencies.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/api/Transactions.php';

		/**
		 * WordPress related classes
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/lib/class-currency-exchange-user.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/lib/class-currency-exchange-currency.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/lib/class-currency-exchange-account.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/lib/class-currency-exchange-transaction.php';

		$this->loader = new Currency_Exchange_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Currency_Exchange_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new Currency_Exchange_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Currency_Exchange_Admin( $this->get_plugin_name(), $this->get_version() );

        //Navigation
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_navigation' );

		//Form processing
		$this->loader->add_action( 'admin_post_ce_setup_save', $plugin_admin, 'the_setup_form_response' );
		$this->loader->add_action( 'admin_post_ce_create_account', $plugin_admin, 'the_ce_create_account_form_response' );
		$this->loader->add_action( 'admin_post_ce_create_transaction', $plugin_admin, 'the_ce_create_transaction_form_response' );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Currency_Exchange_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
