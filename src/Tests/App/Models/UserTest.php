<?php

namespace Tests\App\Models;

use App\Models\Plan;
use App\Models\User;

class UserTest extends \Tests\TestCase
{
    public function test__structure(){
        $this->createApplication();
        $model = User::find(1);
        $this->assertTrue(isset($model->id));
        $this->assertTrue(isset($model->plan_id));
        $this->assertTrue(isset($model->name));
        $this->assertTrue(isset($model->email));
        $this->assertTrue(isset($model->password));
        $this->assertTrue($model->plan instanceof Plan);
    }

}