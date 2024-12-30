<?php

namespace Tests\App\Services\Models;

use App\Models\Plan;
use App\Models\Skill;

class SkillServiceTest extends \Tests\TestCase
{
    public function test__getByCod__null(){
        $this->createApplication();
        $this->assertNull($this->skillService()->getByCod('asd'));
    }
    public function test__getByCod(){
        $this->createApplication();
        $this->assertTrue($this->skillService()->getByCod('speed') instanceof Skill);
    }

}