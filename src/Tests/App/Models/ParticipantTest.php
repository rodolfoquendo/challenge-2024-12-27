<?php

namespace Tests\App\Models;

use App\Models\Gender;
use App\Models\Participant;
use App\Models\ParticipantSkill;
use App\Models\Skill;

class ParticipantTest extends \Tests\TestCase
{
    public function test__structure(){
        $this->createApplication();
        $model = Participant::with(['participantSkills','participantSkills.skill'])->find(1);
        $this->assertTrue(isset($model->id));
        $this->assertTrue(isset($model->gender_id));
        $this->assertTrue(\in_array($model->gender_id, [Gender::M, Gender::F]));
        $this->assertTrue(isset($model->level));
        $this->assertTrue($model->level >= 0);
        $this->assertTrue($model->level <= 100);
        $this->assertTrue(isset($model->name));
        $this->assertTrue($model->gender instanceof Gender);
        $this->assertTrue(count($model->participantSkills) > 0);
        foreach($model->participantSkills as $participantSkills){
            $this->assertTrue($participantSkills instanceof ParticipantSkill);
            $this->assertTrue($participantSkills->skill instanceof Skill);
        }
    }

}