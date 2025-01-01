<?php

namespace Tests\App\Services\Models;

use App\Exceptions\PlanLimitNotValid;
use App\Exceptions\StatNotValid;
use App\Models\Gender;
use App\Models\Participant;
use App\Models\Plan;
use App\Models\Skill;
use App\Models\Tournament;
use App\Models\User;
use App\Models\UserStatistic;
use DateTime;

class TournamentServiceTest extends \Tests\TestCase
{
    public function test__getAll(){
        $this->createApplication();
        $user = $this->limitlessUser();
        $tournaments = $this->tournamentService($user)->getAll();
        $this->assertTrue(count($tournaments) >= 0 );
        foreach($tournaments as $tournament){
            $this->assertTrue($tournament instanceof Tournament);
            $this->assertTrue($tournament->user_id == $user->id);
        }
    }

    public function test__getByCod(){
        $this->createApplication();
        $user = $this->limitlessUser();
        $test = $this->tournamentService($user)->getByCod('asd');
        $this->assertTrue(is_null($test));
        $tournament = Tournament::where('user_id', $user->id)->first();
        $test = $this->tournamentService($user)->getByCod($tournament->cod);
        $this->assertTrue($test instanceof Tournament);
        $this->assertTrue($test->id == $tournament->id);        
    }
    public function test__create__let_defaults_work(){
        $this->createApplication();
        $user = $this->limitlessUser();
        $test = $this->tournamentService($user)
            ->create(
                Gender::findCached(Gender::M),
                'tournament-1',
                'test tournament'
            );
        $this->assertTrue($test instanceof Tournament);
        $this->assertTrue($test->gender_id == Gender::M);
        $this->assertTrue($test->cod == 'tournament-1');
        $this->assertTrue($test->title == 'test tournament');
        
    }
    public function test__create__with_data(){
        $this->createApplication();
        $user = $this->limitlessUser();
        $starts_at = new DateTime("-10 minutes");
        $ends_at = new DateTime();
        $test = $this->tournamentService($user)
            ->create(
                Gender::findCached(Gender::F),
                'tournament-2',
                'test tournament',
                $starts_at,
                $ends_at,
                10,
                10,
                2
            );
        $this->assertTrue($test instanceof Tournament);
        $this->assertTrue($test->gender_id == Gender::F);
        $this->assertTrue($test->cod == 'tournament-2');
        $this->assertTrue($test->title == 'test tournament');
        $this->assertTrue($test->starts_at == $starts_at->format('Y-m-d H:i:s'));
        $this->assertTrue($test->ends_at == $ends_at->format('Y-m-d H:i:s'));
    }
    public function test__create__failure__repeated_cod(){
        $this->createApplication();
        $user = $this->limitlessUser();
        $starts_at = new DateTime("-30 minutes");
        $ends_at = new DateTime("+30 minutes");
        try {
            $this->tournamentService($user)
            ->create(
                Gender::findCached(Gender::F),
                'tournament-2',
                'test tournament',
                $starts_at->format('Y-m-d H:i:s'),
                $ends_at->format('Y-m-d H:i:s'),
                10,
                10,
                2
            );
        } catch ( \Exception $e ) {
            $this->assertTrue($e->getMessage() == 'Tournament already exists');
        }
    }
    public function test__create__failure__limit_reached(){
        $this->createApplication();
        $user = $this->limitlessUser();
        $user = $this->switchPlan($user);
        $starts_at = new DateTime("-30 minutes");
        $ends_at = new DateTime("+30 minutes");
        try {
            $this->tournamentService($user)
                ->create(
                    Gender::findCached(Gender::F),
                    'tournament-4',
                    'test tournament',
                    $starts_at->format('Y-m-d H:i:s'),
                    $ends_at->format('Y-m-d H:i:s'),
                    10,
                    10,
                    2
                );
        } catch ( \Exception $e ) {
            $this->assertTrue($e->getMessage() == 'Tournaments limit reached');
        } finally {
            $this->switchPlan($user);
        }
    }

    public function test__update(){

        $this->createApplication();
        $user = $this->limitlessUser();
        $starts_at = new DateTime("-30 minutes");
        $ends_at = new DateTime("-5 minutes");
        $tournament = $this->tournamentService($user)
            ->create(
                Gender::findCached(Gender::F),
                'tournament-3',
                'test tournament',
                $starts_at,
                $ends_at
            );
        $tournament = $this->tournamentService($user)
            ->update($tournament, title: 'test update');
        $tournament = $this->tournamentService($user)->getByCod('tournament-3');
        $this->assertTrue($tournament->title == 'test update');
    }

    public function test__getWinner__failure(){
        $this->createApplication();
        $user = $this->limitlessUser();
        $tournament = $this->tournamentService($user)
            ->getByCod('tournament-3');
        $winner = $this->tournamentService($user)
            ->getWinner($tournament);
        $this->assertTrue(is_null($winner));
    }

    public function test__getWinner(){
        $this->createApplication();
        $user = $this->limitlessUser();
        $tournament = Tournament::where('user_id', $user->id)
            ->where('starts_at', '<=', date('Y-m-d H:i:s'))
            ->has('tournamentParticipants', 32)
            ->orderby('id','desc')
            ->first();
        $this->tournamentFeatureService($user)
            ->calculateResult($tournament);
        $winner = $this->tournamentService($user)
            ->getWinner($tournament);
        $this->assertTrue($winner instanceof Participant);
    }

}