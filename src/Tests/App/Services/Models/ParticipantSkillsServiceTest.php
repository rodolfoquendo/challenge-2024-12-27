<?php

namespace Tests\App\Services\Models;

use App\Models\Participant;
use App\Models\ParticipantSkill;
use App\Models\Plan;
use App\Models\Skill;
use App\Models\Tournament;
use App\Models\TournamentParticipant;
use App\Models\User;

class ParticipantSkillsServiceTest extends \Tests\TestCase
{
    public function test__calculateSkillTotal(){
        $this->createApplication();
        $participant = Participant::find(1);
        $total = $this->participantSkillsService()->calculateSkillTotal($participant);
        $this->assertTrue(is_int($total));
        $this->assertTrue($total >= 0);
    }

    public function test__get(){
        $this->createApplication();
        $participant = Participant::find(1);
        $skill = Skill::where('gender_id', $participant->gender_id)
            ->first();

        ParticipantSkill::where('participant_id', $participant->id)
            ->where('skill_id', $skill->id)
            ->delete();
        $participantSkills = $this->participantSkillsService()->get($participant);
        foreach($participantSkills as $participantSkill){
            $this->assertTrue($participantSkill instanceof ParticipantSkill);
            $this->assertTrue($participantSkill->participant_id == $participant->id);
        }
    }

    public function test__getFromCombination(){
        $this->createApplication();
        $participant = Participant::find(1);
        $skill = Skill::where('gender_id', $participant->gender_id)
            ->first();
        $participantSkill = $this->participantSkillsService()->getFromCombination($participant, $skill, true);
        $this->assertTrue($participantSkill instanceof ParticipantSkill);
        $this->assertTrue($participantSkill->participant_id == $participant->id);
        $this->assertTrue($participantSkill->skill_id == $skill->id);
    }
}