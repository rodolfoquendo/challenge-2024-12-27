<?php

namespace Tests\App\Services\Models;

use App\Models\Participant;
use App\Models\Plan;
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

}