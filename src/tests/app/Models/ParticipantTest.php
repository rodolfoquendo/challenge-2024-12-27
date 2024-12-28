<?php

namespace Tests\App\Models;

use App\Models\Gender;
use App\Models\Participant;

class ParticipantTest extends \Tests\TestCase
{
    public function test__structure(){
        $this->createApplication();
        $model = Participant::find(1);
        $this->assertTrue(isset($model->id));
        $this->assertTrue(isset($model->gender_id));
        $this->assertTrue(\in_array($model->gender_id, [Gender::M, Gender::F]));
        $this->assertTrue(isset($model->level));
        $this->assertTrue($model->level >= 0);
        $this->assertTrue($model->level <= 100);
        $this->assertTrue(isset($model->name));
        $this->assertTrue($model->gender instanceof Gender);
    }

}