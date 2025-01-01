<?php

namespace Tests\App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;

class AuthControllerTest extends \Tests\TestCase
{

    /**
     * A basic test example.
     */
    public function test__login__noData(): void
    {
        $this->createApplication();
        $response = $this->postJson($this->APIRoute_v1('auth/login'));
        $response->assertUnprocessable();

    }

    /**
     * A basic test example.
     */
    public function test__login__validationFailure(): void
    {
        $this->createApplication();
        $response = $this->postJson($this->APIRoute_v1('auth/login'),[
            'email' => 'asd',
            'password' => 'asd',
        ]);
        $response->assertUnprocessable();
    }

    /**
     * A basic test example.
     */
    public function test__login__wrongData(): void
    {
        $this->createApplication();
        $response = $this->postJson($this->APIRoute_v1('auth/login'),[
            'email' => 'asdd@asd.asdd',
            'password' => 'asdadasd',
        ],[
            'Content-Type' => 'application/json',
        ]);
        $response->assertUnprocessable();

    }
    /**
     * A basic test example.
     */
    public function test__login__exception(): void
    {
        $this->createApplication();
        Config::set('database.connections.mysql.port', '3333');
        $response = $this->postJson($this->APIRoute_v1('auth/login'),[
            'email' => env('MASTER_USER_EMAIL','rodolfoquendo@gmail.com'),
            'password' => env('MASTER_USER_PASSWORD','12345678'),
        ]);
        $this->assertTrue(isset($response['success']));
        $this->assertFalse($response['success']);
        $this->assertTrue(isset($response['status']));
        Config::set('database.connections.mysql.port', '3306');
    }

    /**
     * A basic test example.
     */
    public function test__login__success(): void
    {
        $this->createApplication();
        $response = $this->postJson($this->APIRoute_v1('auth/login'),[
            'email' => env('MASTER_USER_EMAIL','rodolfoquendo@gmail.com'),
            'password' => env('MASTER_USER_PASSWORD','12345678'),
        ]);
        $response = $response->json();
        $this->assertTrue(isset($response['success']));
        $this->assertTrue(isset($response['status']));
        $this->assertTrue(isset($response['payload']));
        $this->assertTrue($response['success']);
        $this->assertTrue($response['status'] == Response::HTTP_OK);
        $this->assertTrue(isset($response['payload']['access_token']));
        $this->assertTrue(\is_string($response['payload']['access_token']));

    }
}