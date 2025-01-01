<?php

namespace Tests\App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;

class ParticipantControllerTest extends \Tests\TestCase
{

    /**
     * A basic test example.
     */
    public function test__get(): void
    {
        $this->createApplication();
        $response = $this->getJson($this->APIRoute_v1('participants'));
        $response->assertUnauthorized();
        $response = $this->getJson($this->APIRoute_v1('participants?name=' . \urlencode(str_repeat('a',300))), $this->limitlessHeader());
        $response->assertUnprocessable();


        $participant = Participant::find(1);
        $response = $this->getJson($this->APIRoute_v1('participants?name=' . \urlencode($participant->name)), $this->limitlessHeader());
        $response = $response->json();
        $this->assertTrue(isset($response['payload']));
        $this->assertTrue(isset($response['payload']['name']));
        $this->assertTrue($response['payload']['name'] == $participant->name);
        $response = $this->getJson($this->APIRoute_v1('participants?name=asd'), $this->limitlessHeader());
        $response = $response->json();
        $this->assertNull($response['payload']);

        Config::set('database.connections.mysql.port', '3333');
        $response = $this->getJson($this->APIRoute_v1('participants?name=asd'), $this->limitlessHeader());
        
        Config::set('database.connections.mysql.port', '3306');

    }

}