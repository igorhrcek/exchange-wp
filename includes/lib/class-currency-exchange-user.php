<?php

/**
 * WordPress User access and management.
 *
 * @link       https://there.is.no.place.like.127.0.0.1
 * @since      1.0.0
 *
 * @package    Currency_Exchange
 * @subpackage Currency_Exchange/admin
 */

/**
 * WordPress User access and management.
 *
 * Allows access to User Information.
 *
 * @package    Currency_Exchange
 * @subpackage Currency_Exchange/admin
 * @author     Igor Hrcek <igor@netrunner.rs>
 */

class Currency_Exchange_User {
    /**
     * Used to check if user has made an account on remote API
     * 
     * If authentication token exist we can consider that user has an account
     *
     * @return boolean
     */
    public static function has_remote_account(): bool {
        $token = get_user_meta(get_current_user_id(), 'currency_exchange_token', true);

        return strlen($token > 0);
    }

    /**
     * Returns stored authorization token
     *
     * @return string
     */
    public static function get_auth_token(): string {
        return get_user_meta(get_current_user_id(), 'currency_exchange_token', true);
    }

    /**
     * Creates a user on Currency Exchange API
     *
     * @return bool
     */
    public static function create(): bool {
        try {
            $current_user = wp_get_current_user();
            $email =  $current_user->user_email;
            $name = sprintf("%s %s", $current_user->user_firstname, $current_user->user_lastname);
            $user_data = (new Currency_Exchange_User_Api())->create($email, $name);

            if(isset($user_data->data->token)) {
                add_user_meta(get_current_user_id(), 'currency_exchange_token', $user_data->data->token, true);
            }
            
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}