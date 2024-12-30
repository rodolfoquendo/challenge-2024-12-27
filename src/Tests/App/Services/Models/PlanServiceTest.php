<?php

namespace Tests\App\Services\Models;

use App\Models\Plan;

class PlanServiceTest extends \Tests\TestCase
{
    public function test__getByCod__null(){
        $this->createApplication();
        $this->assertNull($this->planService()->getByCod('asd'));
    }
    public function test__getByCod(){
        $this->createApplication();
        $this->assertTrue($this->planService()->getByCod('unlimited') instanceof Plan);
    }

}