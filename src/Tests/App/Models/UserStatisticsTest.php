<?php

namespace Tests\App\Models;

use App\Models\Plan;
use App\Models\User;

class UserStatisticsTest extends \Tests\TestCase
{
    public function test__structure(){
        $this->createApplication();
        $user = $this->limitlessUser();
        $model = $this->statisticsService($user)
            ->current();
        $this->assertTrue(isset($model->id));
        $this->assertTrue(isset($model->user_id));
        $this->assertTrue(isset($model->date));
        $this->assertTrue($model->date == date('Y-m'));
        $this->assertTrue($model->tournaments > 0);
        $this->assertTrue($model->participants > 0);
        $this->assertTrue($model->user instanceof User);
    }

}