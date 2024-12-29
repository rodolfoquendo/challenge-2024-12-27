<?php

namespace Tests\App\Models;

use App\Models\Gender;
use App\Models\Participant;
use App\Models\ParticipantSkill;
use App\Models\Skill;
use App\Models\Tournament;
use App\Models\TournamentParticipant;
use App\Models\TournamentStep;
use App\Models\User;

class TournamentStepTest extends \Tests\TestCase
{
    public function test__structure(){
        $this->createApplication();
        $user = User::find(1);
        $tournament = Tournament::find(1);
        $this->tournamentFeatureService($user)
            ->calculateResult($tournament);
        $tournamentStep = TournamentStep::find(1);
        $this->assertTrue($tournamentStep instanceof TournamentStep);
        $this->assertTrue(isset($tournamentStep->id));
        $this->assertTrue(isset($tournamentStep->tournament_id));
        $this->assertTrue(isset($tournamentStep->data));
        $this->assertTrue(isset($tournamentStep->result));
        $this->assertTrue($tournamentStep->tournament instanceof Tournament);

    }

}