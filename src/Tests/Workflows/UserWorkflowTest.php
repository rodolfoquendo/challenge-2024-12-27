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
    public function test__UserLogin(){
        $this->createApplication();
        $user = $this->limitedUser(password:'12345678');
        $data = [
            'email' => $user->email,
            'password' => '12345678',
        ];
        $response = $this->post($this->APIRoute_v1('auth/login'),$data);
        $response->assertOk();
        $response = $response->json();
        $this->assertTrue(isset($response['success']));
        $this->assertTrue(isset($response['status']));
        $this->assertTrue(isset($response['payload']));
        $this->assertTrue($response['success']);
        $this->assertTrue(isset($response['payload']['access_token']));
    }
    public function test__UserCreation(){
        $this->createApplication();
        $data = [
            'plan_id' => Plan::FREE,
            'name' => 'Rodolfo Oquendo',
            'email' => 'r.odolfoquendo@gmail.com',
            'password' => 'asd',
        ];
        $response = $this->putJson($this->APIRoute_v1('users/admin/create'),$data, $this->limitedHeader());
        // dd($response);
        $response->assertForbidden();
    }
    public function test__UserPlanUpdate(){
        $this->createApplication();
        $data = [
            'plan_id' => Plan::UNLIMITED,
            'name' => 'Rodolfo Oquendo',
            'email' => 'r.odolfoquendo@gmail.com',
            'password' => '87654321',
        ];
        $response = $this->patchJson($this->APIRoute_v1('users/admin/update'),$data, $this->limitedHeader());
        $response->assertForbidden();
    }
    public function test__UserDataUpdate(){
        $this->createApplication();
        $data = [
            'plan_id' => Plan::UNLIMITED,
            'name' => 'Rodolfo Alejandro ',
            'email' => 'r.odolfoquendo@gmail.com',
            'password' => '12345678',
        ];
        $response = $this->patch($this->APIRoute_v1('users/admin/update'),$data, $this->limitedHeader());
        $response->assertForbidden();
    }
}