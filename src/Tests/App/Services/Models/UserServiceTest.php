<?php

namespace Tests\App\Services\Models;

use App\Exceptions\PlanDisabled;
use App\Exceptions\Unauthorized;
use App\Models\Plan;
use App\Models\Skill;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserServiceTest extends \Tests\TestCase
{
    public function test__getByEmail(){
        $this->createApplication();
        $this->assertTrue($this->userService()->getByEmail('asd') == null);
        $this->assertTrue($this->userService()->getByEmail('asd@') == null);
        $this->assertTrue($this->userService()->getByEmail('asd@asd') == null);
        $this->assertTrue($this->userService()->getByEmail('asd@asd.asd') == null);
        $this->assertTrue($this->userService()->getByEmail('rodolfoquendo@gmail.com') instanceof User);
    }
    public function test__create(){
        $this->createApplication();
        $user = $this->limitlessUser();
        $plan = Plan::findCached(Plan::FREE);
        $this->assertTrue($this->userService($user)->create($plan, 'Rodolfo','asd', '12345678') == null);
        $this->assertTrue($this->userService($user)->create($plan, 'Rodolfo','asd@', '12345678') == null);
        $this->assertTrue($this->userService($user)->create($plan, 'Rodolfo','asd@asd', '12345678') == null);
        $this->assertTrue($this->userService($user)->create($plan, 'Rodolfo','asd@asd.', '12345678') == null);
        $this->assertTrue($this->userService($user)->create($plan, 'Rodolfo','asd@asd.asd', '12345678') == null);
        $this->assertTrue($this->userService($user)->create($plan, 'Rodolfo','rodolfoquendor@gmail.com', '12345678') instanceof User);
    }
    public function test__update(){
        $this->createApplication();
        $user = $this->limitlessUser();
        $plan = Plan::findCached(Plan::STARTER);
        try {
            $this->userService($user)->update($user, $plan, 'Rodolfo','asd', '12345678');    
        } catch ( \Exception $e ) {
            $this->assertTrue($e instanceof PlanDisabled);
            $this->assertTrue($e->getMessage() === $plan->cod);
        }
        $user = $this->limitedUser();
        $plan = Plan::findCached(Plan::SMALL);
        try {
            $this->userService($user)->update($user, $plan, $user->name, $user->email, '12345678');    
        } catch ( \Exception $e ) {
            $this->assertTrue($e instanceof Unauthorized);
        }
        $plan = Plan::findCached(Plan::FREE);
        try {
            $this->userService($this->limitedUser())->update($this->limitedUser('rodolfoquendor@gmail.com'), $plan, $user->name, $user->email, '12345678');    
        } catch ( \Exception $e ) {
            $this->assertTrue($e instanceof Unauthorized);
        }
        $user = $this->limitedUser();
        $plan = Plan::findCached(Plan::FREE);
        try {
            $this->userService($this->limitlessUser())->update($this->limitlessUser(), $plan, 'Rodolfo','asd@', '12345678') == null;   
        } catch ( \Exception $e ) {
            $this->assertTrue($e instanceof HttpException);
        }
        $this->assertTrue($this->userService($this->limitlessUser())->update($user, $plan, 'testing name',$user->email, '12345678') instanceof User);
    }
    public function test__getPlan(){
        $this->createApplication();
        $user = $this->limitlessUser();
        $this->assertTrue($this->userService($user)->getPlan() instanceof Plan);
    }

}