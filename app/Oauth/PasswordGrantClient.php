<?php

namespace App\Oauth;

use GuzzleHttp\Client;

class PasswordGrantClient
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $http;

    /**
     * PasswordGrantClient constructor.
     *
     * @param \GuzzleHttp\Client $http
     */
    public function __construct(Client $http)
    {
        $this->http = $http;
    }

    /**
     * @param $email
     * @param $password
     *
     * @return array
     */
    public function getTokens($email, $password)
    {
        try {
            $response = $this->http->post(env('APP_URL') . '/oauth/token', [
                'form_params' => [
                    'grant_type'    => 'password',
                    'client_id'     => env('GRANT_CLIENT_ID'),
                    'client_secret' => env('GRANT_CLIENT_SECRET'),
                    'username'      => $email,
                    'password'      => $password,
                    'scope'         => '',
                ],
            ]);

            return json_decode((string)$response->getBody(), true);
        }
        catch (\Exception $e) {
            $error = json_decode((string)$e->getResponse()->getBody(), true);

            return [
                'error_code' => $e->getCode(),
                'error_message' => $error['error_description']
            ];
        }
    }
}