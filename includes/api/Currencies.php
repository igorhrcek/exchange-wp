<?php

class Currency_Exchange_Currency_Api extends Currency_Exchange_Api_Client {
    /**
     * API Endpoint URL
     *
     * @var string
     */
    private $url = '/api/currency';

    /**
     * Returns a list of all currencies
     *
     * @return object
     */
    public function list(): object {
        return $this->request('GET', $this->url, []);
    }
}