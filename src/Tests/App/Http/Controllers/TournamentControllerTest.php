<?php

namespace Tests\App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;

class TournamentControllerTest extends \Tests\TestCase
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
}