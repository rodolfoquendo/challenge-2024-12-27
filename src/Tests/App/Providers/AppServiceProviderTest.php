<?php

namespace Tests\App\Providers;

use App\Exceptions\UserNotSet;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserProject;
use App\Services\AfluentaServiceBase;
use App\Services\UserProjectService;
use Illuminate\Http\Response;
use stdClass;

class AppServiceProviderTest extends \Tests\TestCase
{
    public function testMacro(){
        $this->createApplication();
        $response = response()->api((object)['test'=> true], 200);
        $this->assertTrue(\is_string($response));
        $response = \json_decode($response, true);
        $this->assertTrue(\is_array($response));
        $this->assertTrue(isset($response['status']));
        $this->assertTrue(isset($response['time']));
        $this->assertTrue(isset($response['date']));
        $this->assertTrue(isset($response['payload']));
        $this->assertTrue(isset($response['success']));
        $this->assertTrue(isset($response['app_env']));
        $this->assertTrue(isset($response['app_url']));
        $this->assertTrue(isset($response['app_name']));
        $this->assertTrue(isset($response['payload']['test']));
        $this->assertTrue($response['payload']['test']);
        $this->assertTrue($response['app_name'] == env('APP_NAME'));
        $this->assertTrue($response['app_url'] == env('APP_URL'));
        $this->assertTrue($response['app_env'] == env('APP_ENV'));
        $this->assertTrue($response['status'] == 200);
        $this->assertTrue($response['success']);
    }
    public function test__macro__string(){
        $this->createApplication();
        $response = response()->api('hola', 200);
        $this->assertTrue(\is_string($response));
        $response = \json_decode($response, true);
        $this->assertTrue(\is_array($response));
        $this->assertTrue(isset($response['status']));
        $this->assertTrue(isset($response['time']));
        $this->assertTrue(isset($response['date']));
        $this->assertTrue(isset($response['payload']));
        $this->assertTrue(isset($response['success']));
        $this->assertTrue(isset($response['app_env']));
        $this->assertTrue(isset($response['app_url']));
        $this->assertTrue(isset($response['app_name']));
        $this->assertTrue(isset($response['payload']));
        $this->assertTrue($response['payload'] == 'hola');
        $this->assertTrue($response['app_name'] == env('APP_NAME'));
        $this->assertTrue($response['app_url'] == env('APP_URL'));
        $this->assertTrue($response['app_env'] == env('APP_ENV'));
        $this->assertTrue($response['status'] == 200);
        $this->assertTrue($response['success']);
    }
}