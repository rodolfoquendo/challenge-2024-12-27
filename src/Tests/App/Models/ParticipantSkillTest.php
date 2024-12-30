<?php

namespace Tests\App\Models;

use App\Models\Gender;
use App\Models\Participant;
use App\Models\ParticipantSkill;
use App\Models\Skill;

class ParticipantSkillTest extends \Tests\TestCase
{
    public function test__structure(){
        $this->createApplication();
        $model = ParticipantSkill::with(['participant','skill'])->find(1);
        $this->assertTrue(isset($model->id));
        $this->assertTrue(isset($model->participant_id));
        $this->assertTrue(isset($model->skill_id));
        $this->assertTrue(isset($model->level));
        $this->assertTrue($model->participant instanceof Participant );
        $this->assertTrue($model->skill instanceof Skill );
    }

}