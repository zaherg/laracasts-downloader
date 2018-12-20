<?php

namespace App;

use GuzzleHttp\Client;

class Login
{
    public function login(Client $client): void
    {
        $token = (new Crawler($client))->getCrawler('/');
        $token = $token->filter('meta[name="csrf-token"]')->attr('content');

        $client->request('POST', '/sessions', [
            'form_params' => [
                'email' => getenv('USERNAME'),
                'password' => getenv('PASSWORD'),
                '_token' => $token,
            ],
        ]);
    }
}
