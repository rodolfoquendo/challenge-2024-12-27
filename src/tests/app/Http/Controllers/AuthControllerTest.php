<?php

namespace Tests\Unit;

use Illuminate\Http\Response;

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
            'email' => 'asdd@asd.asd',
            'password' => 'asdd',
        ],[
            'Content-Type' => 'application/json',
        ]);
        $response->assertUnprocessable();

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

    }
}