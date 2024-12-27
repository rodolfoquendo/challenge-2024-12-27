<?php

namespace App\Providers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response as HttpResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyHttpResponse;

class AppServiceProvider extends ServiceProvider
{
    use \App\Traits\Http;
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('api', function (
            $payload,
            int $status = HttpResponse::HTTP_OK
        ) {
            /**
             * this is like this because if not,for some reason the headers are printed to the response as
             * 
             *  #content: """
             *  HTTP/1.0 418 I'm a teapot\r\n
             *  Cache-Control: no-cache, private\r\n
             *  Content-Type:  application/json\r\n
             *  Date:          Tue, 05 Mar 2024 20:42:34 GMT\r\n
             *  \r\n
             *  {"app_name":"TestingApp","app_url":"http:\/\/localhost",...
             */ 
            \http_response_code($status);
            header("Status: $status");
            header("Content-Type: application/json");
            return \json_encode([
                'app_name' => env('APP_NAME'),
                'app_url'  => env("APP_URL"),
                'app_env'  => env("APP_ENV"),
                'success'  => $status == HttpResponse::HTTP_OK,
                'payload'  => $payload,
                'date'     => date('Y-m-d H:i:s'),
                'time'     => time(),
                'status'   => $status
            ]);
        });
    }
}
