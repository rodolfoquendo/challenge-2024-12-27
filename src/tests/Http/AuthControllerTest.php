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
            'username' => 'asd',
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
            'username' => 'asdd',
            'password' => 'asdd',
        ],[
            'Content-Type' => 'application/json',
        ]);
        $response = $response->json();
        $this->assertTrue(isset($response['payload']));
        $this->assertTrue(isset($response['payload']));
        $this->assertTrue(isset($response['status']));
        $this->assertFalse($response['success']);
        $this->assertTrue($response['status'] == Response::HTTP_I_AM_A_TEAPOT);

    }

    /**
     * A basic test example.
     */
    public function test__login__success(): void
    {
        $this->createApplication();
        $response = $this->postJson($this->APIRoute_v1('auth/login'),[
            'username' => env('JWT_USERNAME'),
            'password' => env('JWT_PASSWORD'),
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