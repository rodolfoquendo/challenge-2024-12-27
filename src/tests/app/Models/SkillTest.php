<?php

namespace Tests\App\Models;

use App\Models\Gender;
use App\Models\Participant;
use App\Models\ParticipantSkill;
use App\Models\Skill;

class SkillTest extends \Tests\TestCase
{
    public function test__structure(){
        $this->createApplication();
        $model = Skill::with(['gender','participantSkills'])->find(1);
        $this->assertTrue(isset($model->id));
        $this->assertTrue(isset($model->gender_id));
        $this->assertTrue(isset($model->cod));
        $this->assertTrue(isset($model->title));
        $this->assertTrue($model->gender instanceof Gender );
        $this->assertTrue(count($model->participantSkills) > 0);
        foreach($model->participantSkills as $participantSkill){
            $this->assertTrue($participantSkill instanceof ParticipantSkill);
        }
    }

}