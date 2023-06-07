<?php

/**
 * Currency Management.
 *
 * @link       https://there.is.no.place.like.127.0.0.1
 * @since      1.0.0
 *
 * @package    Currency_Exchange
 * @subpackage Currency_Exchange/admin
 */

/**
 * Currency Management.
 *
 * Provides access to infrmation about currenies and exchange rates.
 *
 * @package    Currency_Exchange
 * @subpackage Currency_Exchange/admin
 * @author     Igor Hrcek <igor@netrunner.rs>
 */

class Currency_Exchange_Currency {
    /**
     * Transient cache key for currencies
     *
     * @var string
     */
    private static string $cache_key_currencies = "ce_currencies";

    /**
     * Transient cache key for currency map
     *
     * @var string
     */
    private static string $cache_key_curreny_map = "ce_currency_map";

    /**
     * Cache expiration timer
     *
     * @var string
     */
    private static int $cache_ttl = 3600;
    
    /**
     * Returns a list of currencies
     *
     * @return array
     */
    public static function get(): array {
        $currencies = json_decode(get_transient(self::$cache_key_currencies));
        $mapping = json_decode(get_transient(self::$cache_key_curreny_map), true);

        if(!$currencies) {
            //Fetch currenies from API and cache them 
            $currencies = (new Currency_Exchange_Currency_Api())->list();
            set_transient(self::$cache_key_currencies, json_encode($currencies), self::$cache_ttl);

            //Lets create simple and quick mapping, cache that as well
            $mapping = [];

            foreach($currencies->data as $currency) {
                $mapping[$currency->id] = $currency->code;
            }
            set_transient(self::$cache_key_curreny_map, json_encode($mapping), self::$cache_ttl);
        }

        return [$currencies, $mapping];
    }
}