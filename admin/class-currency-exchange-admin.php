<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://there.is.no.place.like.127.0.0.1
 * @since      1.0.0
 *
 * @package    Currency_Exchange
 * @subpackage Currency_Exchange/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Currency_Exchange
 * @subpackage Currency_Exchange/admin
 * @author     Igor Hrcek <igor@netrunner.rs>
 */
class Currency_Exchange_Admin {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/currency-exchange-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/currency-exchange-admin.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Register plugin in sidebar navigation
	 *
	 * @return void
	 */
	public function add_plugin_navigation() {
		add_menu_page($this->plugin_name, 'Currency Exchange', 'subscriber', $this->plugin_name, array($this, 'display_accounts_page'),
		'dashicons-money-alt', 26);

		if(Currency_Exchange_User::has_remote_account()) {
			add_submenu_page( $this->plugin_name, 'Accounts', 'Accounts', 'subscriber', $this->plugin_name, array($this, 'display_accounts_page'));
			add_submenu_page( $this->plugin_name, 'Create Account', 'Create Account', 'subscriber', $this->plugin_name.'-create-account', array($this, 'display_create_account_page'));
			add_submenu_page( $this->plugin_name, 'Transfer Money', 'Transfer Money', 'subscriber', $this->plugin_name.'-transfer-money', array($this, 'display_transfer_money_page'));
			add_submenu_page( $this->plugin_name, 'Transactions', 'Transactions', 'subscriber', $this->plugin_name.'-transactions', array($this, 'display_transactions_page'));	
		}
		add_submenu_page( $this->plugin_name, 'Currencies', 'Currencies', 'subscriber', $this->plugin_name.'-currencies', array($this, 'display_currencies_page'));
	}

	/**
	 * Provides access to accounts page
	 *
	 * @return void
	 */
	public function display_accounts_page() {
		if(Currency_Exchange_User::has_remote_account()) {
			$accounts = Currency_Exchange_Account::get();
			list($currencies, $mapping) = Currency_Exchange_Currency::get();

			//Fetch messages, if any
			$form_errors = [];
			if(is_array(get_transient("ce_admin_messages"))) {
				$form_errors = get_transient("ce_admin_messages");
			}
			delete_transient("ce_admin_messages");
			
			require_once 'partials/accounts-list.php';
		} else {
			require_once 'partials/setup.php';
		}
	}

	/**
	 * Provides access to create account page
	 *
	 * @return void
	 */
	public function display_create_account_page() {
		//Fetch information to display
		list($currencies, $mapping) = Currency_Exchange_Currency::get();

		//Fetch message errors, if any
		$form_errors = [];
		if(is_array(get_transient("ce_admin_messages"))) {
			$form_errors = get_transient("ce_admin_messages");
		}
		delete_transient("ce_admin_messages");

		require_once 'partials/account-create.php';
	}

	/**
	 * Provides access to transactions page
	 *
	 * @return void
	 */
	public function display_transactions_page() {
		//Fetch information to display
		$transactions = Currency_Exchange_Transaction::get();
		list($accounts, $mapping) = Currency_Exchange_Account::get();

		//Fetch messages, if any
		$form_errors = [];
		if(is_array(get_transient("ce_admin_messages"))) {
			$form_errors = get_transient("ce_admin_messages");
		}
		delete_transient("ce_admin_messages");
		require_once 'partials/transactions-list.php';
	}

	/**
	 * Provides access to account page
	 *
	 * @return void
	 */
	public function display_transfer_money_page() {
		$accounts = Currency_Exchange_Account::get();
		list($currencies, $mapping) = Currency_Exchange_Currency::get();

		//Fetch message errors, if any
		$form_errors = [];
		if(is_array(get_transient("ce_admin_messages"))) {
			$form_errors = get_transient("ce_admin_messages");
		}

		delete_transient("ce_admin_messages");

		require_once 'partials/transaction-create.php';
	}

	/**
	 * Provides access to currencies page
	 *
	 * @return void
	 */
	public function display_currencies_page() {
		list($currencies, $mapping) = Currency_Exchange_Currency::get();

		require_once 'partials/currencies-list.php';
	}

	/**
	 * Helper function for rendering admin messages
	 *
	 * @param string $message
	 * @param string $message_type
	 * @return string
	 */
	public function admin_message(string $message, string $message_type = "info"): string {
		return sprintf('<div id="ce_message" class="ce-alert alert-%s">%s</div>', $message_type, $message);
	}

	/**
	 * Process user account form setup
	 *
	 * @return void
	 */
	public function the_setup_form_response() {
		if(isset($_POST['ce_setup_form_nonce']) && wp_verify_nonce($_POST['ce_setup_form_nonce'], 'ce_setup_save')) {
			Currency_Exchange_User::create();
			wp_safe_redirect(admin_url('admin.php?page=currency-exchange'));
			exit;
		} else {
			wp_die(__('Invalid nonce specified', $this->plugin_name), __('Error', $this->plugin_name), [
				'response' 	=> 403,
				'back_link' => 'admin.php?page=' . $this->plugin_name,
			]);
		}
	}

	/**
	 * Process create account form
	 *
	 * @return void
	 */
	public function the_ce_create_account_form_response() {
		if(isset($_POST['ce_create_account_form_nonce']) && wp_verify_nonce($_POST['ce_create_account_form_nonce'], 'ce_create_account')) {
			$currency_id = (int)filter_var($_POST["currency_id"], FILTER_SANITIZE_NUMBER_INT);

			//Is currency selected at all?
			if($currency_id === -1) {
				add_settings_error('ce_message', 'ce_message_option', __("Please select a currency."), 'danger');
				set_transient('ce_admin_messages', get_settings_errors(), 30);
				wp_safe_redirect($_POST["_wp_http_referer"]);
				exit;
			}
			
			$currency = Currency_Exchange_Account::create($currency_id);

			if(isset($currency->errors)) {
				add_settings_error('ce_message', 'ce_message_option', $currency->errors->currency_id[0], 'danger');
				set_transient('ce_admin_messages', get_settings_errors(), 30);
				wp_safe_redirect($_POST["_wp_http_referer"]);
				exit;
			}

			//Redirect to Accounts
			add_settings_error('ce_message', 'ce_message_option', __("New account was successfully created!"), 'info');
			set_transient('ce_admin_messages', get_settings_errors(), 30);
			wp_safe_redirect($_POST["redirect"]);
			exit;
		} else {
			wp_die(__('Invalid nonce specified', $this->plugin_name), __('Error', $this->plugin_name), [
				'response' 	=> 403,
				'back_link' => 'admin.php?page=' . $this->plugin_name,
			]);
		}
	}

	/**
	 * Process create transaction form
	 *
	 * @return void
	 */
	public function the_ce_create_transaction_form_response() {
		if(isset($_POST['ce_create_transaction_form_nonce']) && wp_verify_nonce($_POST['ce_create_transaction_form_nonce'], 'ce_create_transaction')) {
			$source_account_id = (int)filter_var($_POST["source_account_id"], FILTER_SANITIZE_NUMBER_INT);
			$destination_account_id = (int)filter_var($_POST["destination_account_id"], FILTER_SANITIZE_NUMBER_INT);
			$amount = (float)filter_var($_POST["amount"], FILTER_SANITIZE_NUMBER_FLOAT);

			//Let's validate all. Never ever trust user.
			$valid = true;
			if($source_account_id === -1 || !is_numeric($source_account_id)) {
				add_settings_error('ce_message', 'ce_message_option', __("Please select a source account."), 'danger');
				$valid = false;
			}

			if($destination_account_id === -1 || !is_numeric($destination_account_id)) {
				add_settings_error('ce_message', 'ce_message_option', __("Please select a destination account."), 'danger');
				$valid = false;
			}

			if(!is_numeric($amount) || $amount <= 0) {
				add_settings_error('ce_message', 'ce_message_option', __("Please enter amount that is bigger than 0."), 'danger');
				$valid = false;
			}

			//Sayonara
			if(!$valid) {
				set_transient('ce_admin_messages', get_settings_errors(), 30);
				wp_safe_redirect($_POST["_wp_http_referer"]);
				exit;
			}
			
			//Lets make transaction
			$transaction = Currency_Exchange_Transaction::create($source_account_id, $destination_account_id, $amount);
			$created = true;

			//Check if API reported any validation errors
			if(isset($transaction->success) && $transaction->success === false) {
				if(strlen($transaction->message) > 0) {
					add_settings_error('ce_message', 'ce_message_option', $transaction->message, 'danger');
				}
				$created = false;
			}

			if(isset($transaction->errors)) {
				foreach($transaction->errors as $error) {
					add_settings_error('ce_message', 'ce_message_option', $error[0], 'danger');
				}
				$created = false;
			}

			//Transaction was not created
			if(!$created) {
				set_transient('ce_admin_messages', get_settings_errors(), 30);
				wp_safe_redirect($_POST["_wp_http_referer"]);
				exit;
			}

			//Redirect to Transactions
			add_settings_error('ce_message', 'ce_message_option', __("New transaction was successfully created!"), 'info');
			set_transient('ce_admin_messages', get_settings_errors(), 30);
			wp_safe_redirect($_POST["redirect"]);
			exit;
		} else {
			wp_die(__('Invalid nonce specified', $this->plugin_name), __('Error', $this->plugin_name), [
				'response' 	=> 403,
				'back_link' => 'admin.php?page=' . $this->plugin_name,
			]);
		}
	}
}
