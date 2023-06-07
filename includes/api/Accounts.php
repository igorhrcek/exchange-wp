<?php

class Currency_Exchange_Account_Api extends Currency_Exchange_Api_Client {
    /**
     * API Endpoint URL
     *
     * @var string
     */
    private array $url = [
        '/api/account',
        '/api/accounts',
    ];

    /**
     * Returns a list of all accounts
     *
     * @return object
     */
    public function list(): object {
        return $this->request('GET', $this->url[1], []);
    }

    /**
     * Creates an account
     *
     * @return object
     */
    public function create(int $currency_id): object {
        return $this->request('POST', $this->url[0], [
            'currency_id' => $currency_id
        ]);
    }
}