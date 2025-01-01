<?php

namespace Tests\App\Services\Models;

use App\Models\Participant;
use App\Models\ParticipantSkill;
use App\Models\Plan;
use App\Models\Skill;
use App\Models\Tournament;
use App\Models\TournamentParticipant;
use App\Models\User;

class ParticipantServiceTest extends \Tests\TestCase
{
    public function test__get(){
        $this->createApplication();
        $participant = Participant::find(1);
        $participant_2 = $this->participantService()->get($participant->name);
        $this->assertTrue($participant instanceof Participant);
        $this->assertTrue($participant->id == $participant_2->id);
    }

    public function test__create(){
        $this->createApplication();
        $this->assertTrue($this->participantService()->create('Rodolfo Oquendo', 10) instanceof Participant);
        $this->assertTrue($this->participantService()->create('Rodolfo Oquendo', 20) instanceof Participant);
        $this->assertTrue($this->participantService()->get('Rodolfo Oquendo')->level == 20);
    }
}