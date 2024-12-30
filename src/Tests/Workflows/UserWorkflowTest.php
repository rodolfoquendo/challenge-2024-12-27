<?php

namespace Tests\Workflows;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * This is the admin workflow test
 * this will do the following: 
 * - create a user
 * - change the user plan
 * - 
 *
 * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
 * @copyright 2024 Rodolfo Oquendo
 */
class UserWorkflowTest extends \Tests\TestCase
{
    public function test__TournamentCreation(){
        $this->createApplication();
        $data = [
            'plan_id' => Plan::FREE,
            'name' => 'Rodolfo Oquendo',
            'email' => 'r.odolfoquendo@gmail.com',
            'password' => 'asd',
        ];
        $response = $this->postJson($this->APIRoute_v1('users/admin/create'),$data, $this->limitlessHeader());
        $response->assertOk();
        $response = $response->json();
        $this->assertTrue(isset($response['success']));
        $this->assertTrue(isset($response['payload']));
        $this->assertTrue(isset($response['payload']['id']));
        $this->assertTrue(isset($response['payload']['plan_id']));
        $this->assertTrue(isset($response['payload']['name']));
        $this->assertTrue(isset($response['payload']['email']));
        $this->assertTrue($response['success']);
        $this->assertTrue($response['payload']['plan_id'] == $data['plan_id']);
        $this->assertTrue($response['payload']['name'] == $data['name']);
        $this->assertTrue($response['payload']['email'] == $data['email']);
    }
    public function test__UserPlanUpdate(){
        $this->createApplication();
        $data = [
            'plan_id' => Plan::UNLIMITED,
            'name' => 'Rodolfo Oquendo',
            'email' => 'r.odolfoquendo@gmail.com',
            'password' => 'asd',
        ];
        $response = $this->postJson($this->APIRoute_v1('users/admin/update'),$data, $this->limitlessHeader());
        $response->assertOk();
        $response = $response->json();
        $this->assertTrue(isset($response['success']));
        $this->assertTrue(isset($response['payload']));
        $this->assertTrue(isset($response['payload']['id']));
        $this->assertTrue(isset($response['payload']['plan_id']));
        $this->assertTrue(isset($response['payload']['name']));
        $this->assertTrue(isset($response['payload']['email']));
        $this->assertTrue($response['success']);
        $this->assertTrue($response['payload']['plan_id'] == $data['plan_id']);
        $this->assertTrue($response['payload']['name'] == $data['name']);
        $this->assertTrue($response['payload']['email'] == $data['email']);
    }
    public function test__UserDataUpdate(){
        $this->createApplication();
        $data = [
            'plan_id' => Plan::UNLIMITED,
            'name' => 'Rodolfo Alejandro ',
            'email' => 'r.odolfoquendo@gmail.com',
            'password' => '12345678',
        ];
        $response = $this->postJson($this->APIRoute_v1('users/admin/update'),$data, $this->limitlessHeader());
        $response->assertOk();
        $response = $response->json();
        $this->assertTrue(isset($response['success']));
        $this->assertTrue(isset($response['payload']));
        $this->assertTrue(isset($response['payload']['id']));
        $this->assertTrue(isset($response['payload']['plan_id']));
        $this->assertTrue(isset($response['payload']['name']));
        $this->assertTrue(isset($response['payload']['email']));
        $this->assertTrue($response['success']);
        $this->assertTrue($response['payload']['plan_id'] == $data['plan_id']);
        $this->assertTrue($response['payload']['name'] == $data['name']);
        $this->assertTrue($response['payload']['email'] == $data['email']);
    }
    public function test__UserLogin(){
        $this->createApplication();
        $data = [
            'email' => 'r.odolfoquendo@gmail.com',
            'password' => '12345678',
        ];
        $response = $this->postJson($this->APIRoute_v1('auth/login'),$data, $this->limitlessHeader());
        $response->assertOk();
        $response = $response->json();
        $this->assertTrue(isset($response['success']));
        $this->assertTrue(isset($response['status']));
        $this->assertTrue(isset($response['payload']));
        $this->assertTrue($response['success']);
        $this->assertTrue(isset($response['payload']['access_token']));
    }

}