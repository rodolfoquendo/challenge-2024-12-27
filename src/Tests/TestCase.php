<?php

namespace Tests;

use App\Models\Plan;
use App\Models\User;
use App\Models\UserLanguage;
use App\Models\UserStage;
use App\Models\UserStatistic;
use App\Traits\Services;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

abstract class TestCase extends \Illuminate\Foundation\Testing\TestCase
{
    use CreatesApplication,
        Services;

    protected function getToken(User $user){
        if(!Cache::has(__METHOD__)){
            $token = auth()->login($user);
            Cache::put(__METHOD__, $token, 3600);
        }
        return Cache::get(__METHOD__);
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
        $host = "http://localhost";
        $url  = substr($url,0,1) == '/' ? substr($url, 1) : $url;
        $url  = substr($url, -1) == '/' ? substr($url, 0, -1) : $url;
        $prefix = env('TARGET_GROUP_PREFIX','');
        if($prefix != ''){
            $prefix = substr($prefix,0,1) == '/' ? substr($prefix, 1) : $prefix;
            $prefix = substr($prefix,-1)  != '/' ? "{$prefix}/" : $prefix;
        }
        return "{$host}/{$prefix}api/v1/{$url}";
    }

    public function limitlessUser(): User
    {
        $user = User::find(1);
        $user->plan_id = Plan::UNLIMITED;
        $user->save();
        return $user;
    }
    public function switchPlan(User $user, $newPlanID = Plan::FREE, $oldPlanID = Plan::UNLIMITED): User
    {
        $user->plan_id = $user->plan_id == $oldPlanID ? $newPlanID : $oldPlanID;
        $user->save();
        return $user;
    }
    protected function limitlessHeader(){
        return [
            "Authorization" => "Bearer " . $this->tokenLimitlessUser()
        ];
    }
    protected function authHeaderLimitTester(){
        return [
            "Authorization" => "Bearer " . $this->tokenLimitedUser()
        ];
    }
    protected function tokenLimitedUser($email = 'rodolfoquendo.ar@gmail.com'){
        $user = $this->limitedUser($email);
        return $this->getToken($user);
    }
    protected function tokenLimitlessUser(){
        $user = $this->limitlessUser();
        return $this->getToken($user);
    }

    public function limitedUser($email = 'rodolfoquendo.ar@gmail.com', $password = 123456, int $plan_id = Plan::FREE){
        $user = User::where('email',$email)->first();
        if(!$user instanceof User){
            $user = new User();
            $user->name = 'Rodolfo Oquendo';
            $user->email = $email;
            $user->password = Hash::make($password);
            $user->plan_id = $plan_id;
            $user->save();
        }
        return $user;
    }
}
