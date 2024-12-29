<?php

namespace Tests;

use App\Traits\Services;

abstract class TestCase extends \Illuminate\Foundation\Testing\TestCase
{
    use CreatesApplication,
        Services;
    protected $token = null;

    protected function getToken(){
        if(is_null($this->token)){
            $client   = new \GuzzleHttp\Client();
            $response = $client->request('POST', env('APP_URL') . '/api/auth/login', [
                'form_params' => [
                    'username' => env('JWT_USERNAME'),
                    'password' => env('JWT_PASSWORD'),
                ]
            ]);
            $response = json_decode($response->getBody()->getContents(),true);
            $this->token = $response['payload']['access_token'];
        }
        return $this->token;
    }
    protected function authHeader($separate_name = false){
        return [
            "Authorization" => "Bearer " . $this->getToken()
        ];
    }

    protected function multipart($url, array $data = [], $headers = [])
    {   
        $client   = new \GuzzleHttp\Client();
        $response = $client->request('POST', $url, options: [
            'headers'     => $headers,
            'multipart'   => $data
        ]);
        return (object)[
            'http_code' => $response->getStatusCode(),
            'success'   => $response->getStatusCode() == 200,
            'response'  => json_decode($response->getBody()->getContents()),
        ];
    }

    public function APIRoute_v1($url){
        $prefix = env('TARGET_GROUP_PREFIX','');
        if($prefix != ''){
            $prefix = substr($prefix,0,1) == '/' ? substr($prefix, 1) : $prefix;
            $prefix = substr($prefix,-1) != '/' ? "{$prefix}/" : $prefix;
        }
        $url = substr($url,0,1) == '/' ? substr($url, 1) : $url;
        $url = substr($url,-1) != '/' ? "{$url}/" : $url;
        return "{$prefix}/api/v1/{$url}";
    }
}
