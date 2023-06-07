<?php

class Currency_Exchange_Transaction_Api extends Currency_Exchange_Api_Client {
    /**
     * API Endpoint URL
     *
     * @var string
     */
    private array $url = [
        '/api/transaction',
        '/api/transactions',
    ];

    /**
     * Returns a list of all transactions
     *
     * @return object
     */
    public function list(): object {
        return $this->request('GET', $this->url[1], []);
    }

    /**
     * Creates a transaction
     *
     * @return object
     */
    public function create(int $source_account_id, int $destination_account_id, float $amount): object {
        return $this->request('POST', $this->url[0], [
            'source_account_id' => $source_account_id,
            'destination_account_id' => $destination_account_id,
            'amount' => $amount
        ]);
    }
}