<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\GuzzleException;


class AuthService
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_url'=> config("app.guzzle_api_url").'/auth',
            'headers'=>[
                'Accept'=> 'application/json',
            ],
            'verify'=> false,
        ]);
    }
    public function login(array $credentials): array 
    {
        Log::info("Login: ", $credentials);
        try {
            // $response = $this->client->post('http://127.0.0.1:3000/api/auth/login', [
            //     'json' => $credentials
            // ]);
            $response = $this->client->post(config('app.guzzle_api_url').'/auth/login', [
                'json' => $credentials
            ]);

            $body = json_decode($response->getBody()->getContents(), true);
        Log::info("body: ", $body);

            return ['success'=> true, 'token'=> $body['token']];


        } catch (GuzzleException $th) {
            return ['success'=> false, 'error'=> $th->getMessage()];
        }
        
    }


    public function register(array $userData): array
{
    try {
        $response = $this->client->post('register', [
            'json' => $userData
        ]);
        $body = json_decode($response->getBody()->getContents(), true);

        return ['success'=> true, 'token'=> $body['token'], 'data'=>$body['user']];

    } catch (GuzzleException $e) {
        return ['success'=> false, 'error'=> $e->getMessage()];
    }
}

public function getUserByToken(string $token): array
{
    try {
        // $response = $this->client->get('user', [
        $response = $this->client->get('http://127.0.0.1:3000/api/admin', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);
        $body = json_decode($response->getBody()->getContents(), true);

        return ['success'=> true, 'data'=>$body['user']];

    } catch (GuzzleException $e) {
        return ['success'=> false, 'error'=> $e->getMessage()];
    }
}

}