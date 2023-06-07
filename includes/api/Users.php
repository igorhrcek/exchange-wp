<?php

class Currency_Exchange_User_Api extends Currency_Exchange_Api_Client {
    /**
     * API Endpoint URL
     *
     * @var string
     */
    private $url = '/api/user';

    /**
     * Implementation of POST /users API request
     *
     * @return object
     */
    public function create(string $email, string $name): object {
        $body = [
            'email' => $email,
            'name' => $name
        ];

        return $this->request('POST', $this->url, $body);
    }
}