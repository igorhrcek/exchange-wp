<?php

/**
 * Account Management.
 *
 * @link       https://there.is.no.place.like.127.0.0.1
 * @since      1.0.0
 *
 * @package    Currency_Exchange
 * @subpackage Currency_Exchange/admin
 */

/**
 * Account Management.
 *
 * Provides access to infrmation about accounts.
 *
 * @package    Currency_Exchange
 * @subpackage Currency_Exchange/admin
 * @author     Igor Hrcek <igor@netrunner.rs>
 */

class Currency_Exchange_Account {
    /**
     * Transient cache key for accounts
     *
     * @var string
     */
    private static string $cache_key_accounts = "ce_accounts";

    /**
     * Transient cache key for account map
     *
     * @var string
     */
    private static string $cache_key_account_map = "ce_account_map";

    /**
     * Cache expiration timer
     *
     * @var string
     */
    private static int $cache_ttl = 600;
    
    /**
     * Returns a list of accounts
     * 
     * @param boolean $force_sync
     * @return array
     */
    public static function get(bool $force_sync = false): array {
        $accounts = json_decode(get_transient(self::$cache_key_accounts));
        $mapping = json_decode(get_transient(self::$cache_key_account_map), true);

        if(!$accounts || $force_sync === true) {
            //Fetch accounts from API and cache them 
            $accounts = (new Currency_Exchange_Account_Api())->list();

            if(isset($accounts->data) && count($accounts->data) > 0) {
                set_transient(self::$cache_key_accounts, json_encode($accounts), self::$cache_ttl);

                //Lets create simple and quick mapping, cache that as well
                $mapping = [];
    
                foreach($accounts->data as $account) {
                    $mapping[$account->id] = $account->uuid;
                }
                set_transient(self::$cache_key_account_map, json_encode($mapping), self::$cache_ttl);
            }
        }

        return [$accounts, $mapping];
    }

    /**
     * Calls and API to create an account
     *
     * @param integer $currency_id
     * @return object
     */
    public static function create(int $currency_id): object {
        $account = (new Currency_Exchange_Account_Api())->create($currency_id);

        //Refresh transient cache if account was created
        if(!isset($account->errors)) {
            self::get(true);
        }

        return $account;
    }
}