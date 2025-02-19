<?php

namespace Tests\App\Services\Features;

use App\Models\Plan;
use App\Models\Tournament;
use App\Models\TournamentParticipant;
use App\Models\User;

class TournamentFeatureServiceTest extends \Tests\TestCase
{
    public function test__calculateResult__not_ready(){
        $this->createApplication();
        $user = $this->limitlessUser();
        $tournament =   Tournament::where('starts_at', '>=', date('Y-m-d H:i:s'))
            ->where('user_id', $user->id)
            ->first();
        $this->assertFalse($this->tournamentFeatureService($user)->calculateResult($tournament));
    }

    public function test__calculateResult__participants_not_power_of_2(){
        $this->createApplication();
        $user = $this->limitlessUser();
        $tournament =   Tournament::where('starts_at', '<=', date('Y-m-d H:i:s'))
            ->where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->first();
        TournamentParticipant::where('tournament_id', $tournament->id)
            ->orderBy('id', 'desc')
            ->first()
            ->delete();
        $this->assertFalse($this->tournamentFeatureService($user)->calculateResult($tournament));
    }
    
    public function test__calculateResult__success(){
        $this->createApplication();
        $user = $this->limitlessUser();
        $tournament =   Tournament::where('starts_at', '<=', date('Y-m-d H:i:s'))
            ->where('user_id', $user->id)
            ->first();
        $this->assertTrue($this->tournamentFeatureService($user)->calculateResult($tournament));
    }

}