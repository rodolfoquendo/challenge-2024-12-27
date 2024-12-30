<?php

namespace Tests\App\Services\Models;

use App\Models\Plan;
use App\Models\Skill;
use App\Models\User;

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
    public function test__getPlan(){
        $this->createApplication();
        $user = $this->limitlessUser();
        $this->assertTrue($this->userService($user)->getPlan() instanceof Plan);
    }

}