<?php

namespace Tests\App\Services\Models;

use App\Exceptions\PlanLimitNotValid;
use App\Exceptions\StatNotValid;
use App\Models\Gender;
use App\Models\Participant;
use App\Models\Plan;
use App\Models\Skill;
use App\Models\Tournament;
use App\Models\TournamentParticipant;
use App\Models\User;
use App\Models\UserStatistic;
use DateTime;

class TournamentParticipantServiceTest extends \Tests\TestCase
{
    public function test__get__wrong_user(){
        $this->createApplication();
        $user = $this->limitedUser();
        $tournament = Tournament::find(1);   
        try {
            $this->tournamentParticipantService($user)->get($tournament);
        } catch ( \Exception $e ) {
            $this->assertTrue($e->getMessage() == "Tournament does not exists for user");
        }       
    }

    public function test__get(){
        $this->createApplication();
        $user = $this->limitlessUser();
        $tournament = Tournament::find(1); 
        $participants = $this->tournamentParticipantService($user)->get($tournament);
        foreach($participants as $participant){
            $this->assertTrue($participant instanceof TournamentParticipant);
        }
    }

    public function test__add__wrong_user(){
        $this->createApplication();
        $user = $this->limitedUser();
        $tournament = Tournament::find(1); 
        $participant = Participant::find(1);
        try {
            $this->tournamentParticipantService($user)->add($tournament, $participant);
        } catch ( \Exception $e ) {
            $this->assertTrue($e->getMessage() == "Tournament does not exists for user");
        }       
    }
    
    public function test__add__wrong_gender(){
        $this->createApplication();
        $user = $this->limitlessUser();
        $tournament = Tournament::find(1); 
        $participant = Participant::where('gender_id','!=',$tournament->gender_id)
            ->first();
        try {
            $this->tournamentParticipantService($user)->add($tournament, $participant);
        } catch ( \Exception $e ) {
            $this->assertTrue($e->getMessage() == "Participant must be the same gender as the tournament");
        }
    }
    
    public function test__add__limit_reached(){
        $this->createApplication();
        $user = $this->limitlessUser();
        $user = $this->switchPlan($user);
        $tournament = Tournament::find(1); 
        $participant = Participant::where('gender_id', $tournament->gender_id)
            ->first();
        try {
            $this->tournamentParticipantService($user)->add($tournament, $participant);
        } catch ( \Exception $e ) {
            $this->assertTrue($e->getMessage() == "Participant limit reached");
        } finally {
            $user = $this->switchPlan($user);
        }
    }
    
    public function test__add__first(){
        $this->createApplication();
        $user = $this->limitlessUser();
        $tournament = Tournament::find(1); 
        $participant = Participant::where('gender_id',$tournament->gender_id)
            ->first();
        TournamentParticipant::where('tournament_id', $tournament->id)
            ->where('participant_id', $participant->id)
            ->delete();
        $this->assertTrue($this->tournamentParticipantService($user)->add($tournament, $participant) instanceof TournamentParticipant);
    }
    
    public function test__add__repeated(){
        $this->createApplication();
        $user = $this->limitlessUser();
        $tournament = Tournament::find(1); 
        $participant = Participant::where('gender_id',$tournament->gender_id)
            ->first();
        $this->assertTrue($this->tournamentParticipantService($user)->add($tournament, $participant) instanceof TournamentParticipant);
    }
    
    public function test__remove(){
        $this->createApplication();
        $user = $this->limitlessUser();
        $tournament = Tournament::find(1); 
        $participant = Participant::where('gender_id',$tournament->gender_id)
            ->first();
        $this->assertTrue($this->tournamentParticipantService($user)->remove($tournament, $participant));
    }
    
    public function test__removeAll(){
        $this->createApplication();
        $user = $this->limitlessUser();
        $tournament = Tournament::find(5); 
        $this->assertTrue($this->tournamentParticipantService($user)->removeAll($tournament));
    }
    
    public function test__moveParticipants__different_user(){
        $this->createApplication();
        $user = $this->limitedUser();
        $from = Tournament::find(4); 
        $to = Tournament::find(5); 
        try {
            $this->tournamentParticipantService($user)->moveParticipants($from, $to);   
        } catch ( \Exception $e ) {
            $this->assertTrue($e->getMessage() == 'Both tournaments must be from the user');
        }
    }
    
    public function test__moveParticipants__from_no_participants(){
        $this->createApplication();
        $user = $this->limitlessUser();
        $from = Tournament::find(5); 
        $to = Tournament::find(4); 
        $this->assertTrue($this->tournamentParticipantService($user)->moveParticipants($from, $to));
    }
    
    public function test__moveParticipants__to_no_participants(){
        $this->createApplication();
        $user = $this->limitlessUser();
        $from = Tournament::find(4); 
        $to = Tournament::find(5); 
        $this->assertTrue($this->tournamentParticipantService($user)->moveParticipants($from, $to));
    }
    
    public function test__moveParticipants__all_with_participants(){
        $this->createApplication();
        $user = $this->limitlessUser();
        $from = Tournament::find(6); 
        $to = Tournament::find(7); 
        $this->assertTrue($this->tournamentParticipantService($user)->moveParticipants($from, $to));
    }
    
}