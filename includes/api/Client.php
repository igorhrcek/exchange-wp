<?php

class Currency_Exchange_Api_Client {
    /**
     * Default request headers
     *
     * @var array
     */
    private array $default_headers = [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
    ];

    /**
     * HTTP request timeout
     *
     * @var integer
     */
    private int $timeout = 30;

    /**
     * A simple wrapper around wp_remote_request
     *
     * @param string $method
     * @param string $url
     * @param array $body
     * @return object|Exception
     */
    public function request(string $method, string $url, array $body = []): object {
        $response =  wp_remote_request($this->build_url($url), [
            'method' => $method,
            'body' => count($body) > 0 ? json_encode($body) : [],
            'headers' => array_merge($this->default_headers, [
                'Authorization' => 'Bearer ' . Currency_Exchange_User::get_auth_token()
            ]),
            'timeout' => $this->timeout
        ]);

        if (!is_wp_error($response) && (200 == $response['response']['code'] || 201 == $response['response']['code'])) {
            return json_decode($response['body']);
        } else {
            return json_decode($response['body']);
        }
    }

    /**
     * Binds together base API URL and endpoint URL
     *
     * @param string $url
     * @return void
     */
    private function build_url(string $url) {
        return CURRENCY_EXCHANGE_API_BASE_URL .  ":" . CURRENCY_EXCHANGE_API_PORT .  $url;
    }

}