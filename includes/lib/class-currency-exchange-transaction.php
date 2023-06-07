<?php

/**
 * Transaction Management.
 *
 * @link       https://there.is.no.place.like.127.0.0.1
 * @since      1.0.0
 *
 * @package    Currency_Exchange
 * @subpackage Currency_Exchange/admin
 */

/**
 * Transaction Management.
 *
 * Provides access to infrmation about transactions.
 *
 * @package    Currency_Exchange
 * @subpackage Currency_Exchange/admin
 * @author     Igor Hrcek <igor@netrunner.rs>
 */

class Currency_Exchange_Transaction {
    /**
     * Transient cache key for accounts
     *
     * @var string
     */
    private static string $cache_key_transactions = "ce_transactions";

    /**
     * Cache expiration timer
     *
     * @var string
     */
    private static int $cache_ttl = 600;
    
    /**
     * Returns a list of transactions
     * 
     * @param boolean $force_sync
     * @return object
     */
    public static function get(bool $force_sync = false): object {
        $transactions = json_decode(get_transient(self::$cache_key_transactions));

        if(!$transactions || $force_sync === true) {
            //Fetch transactions from API and cache them 
            $transactions = (new Currency_Exchange_Transaction_Api())->list();

            if(isset($transactions->data) && count($transactions->data) > 0) {
                set_transient(self::$cache_key_transactions, json_encode($transactions), self::$cache_ttl);
            }
        }

        return $transactions;
    }

    /**
     * Calls and API to create an account
     *
     * @param integer $currency_id
     * @return object
     */
    public static function create(int $source_account_id, int $destination_account_id, float $amount): object {
        $transaction = (new Currency_Exchange_Transaction_Api())->create($source_account_id, $destination_account_id, $amount);

        //Refresh transient cache if transaction was created
        if(!isset($transaction->errors)) {
            self::get(true);

            //Refresh account data because balance has changed
            Currency_Exchange_Account::get(true);
        }

        return $transaction;
    }
}