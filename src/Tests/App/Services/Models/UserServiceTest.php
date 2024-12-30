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
    public function test__createOrUpdate(){
        $this->createApplication();
        $plan = Plan::findCached(Plan::UNLIMITED);
        $this->assertTrue($this->userService()->createOrUpdate($plan, 'Rodolfo','asd', '12345678') == null);
        $this->assertTrue($this->userService()->createOrUpdate($plan, 'Rodolfo','asd@', '12345678') == null);
        $this->assertTrue($this->userService()->createOrUpdate($plan, 'Rodolfo','asd@asd', '12345678') == null);
        $this->assertTrue($this->userService()->createOrUpdate($plan, 'Rodolfo','asd@asd.', '12345678') == null);
        $this->assertTrue($this->userService()->createOrUpdate($plan, 'Rodolfo','asd@asd.asd', '12345678') == null);
        $this->assertTrue($this->userService()->createOrUpdate($plan, 'Rodolfo','rodolfoquendor@gmail.com', '12345678') instanceof User);
    }
    public function test__getPlan(){
        $this->createApplication();
        $user = $this->limitlessUser();
        $this->assertTrue($this->userService($user)->getPlan() instanceof Plan);
    }

}