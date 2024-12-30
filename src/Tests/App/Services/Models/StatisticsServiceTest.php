<?php

namespace Tests\App\Services\Models;

use App\Exceptions\PlanLimitNotValid;
use App\Exceptions\StatNotValid;
use App\Models\Plan;
use App\Models\Skill;
use App\Models\User;
use App\Models\UserStatistic;

class StatisticsServiceTest extends \Tests\TestCase
{
    public function test__current(){
        $this->createApplication();
        $user = $this->limitlessUser();
        
        $stats = $this->statisticsService($user)->current();
        $this->assertTrue($stats instanceof UserStatistic);
        $this->assertTrue($stats->date == date("Y-m"));
        $this->assertTrue($stats->user_id == $user->id);
        $new = $this->statisticsService($user)->current();
        $this->assertTrue($stats->id == $new->id);
        
    }

    public function test__check__failure__wrong_stat(){
        $this->createApplication();
        $user = $this->limitedUser();
        try {
            $stat = $this->statisticsService($user)->check('asd');
        } catch ( \Exception $e ) {
            $this->assertTrue($e instanceof StatNotValid);
        }
    }

    public function test__check__failure__wrong_plan_limit(){
        $this->createApplication();
        $user = $this->limitedUser();
        try {
            $stat = $this->statisticsService($user)->check('date');
        } catch ( \Exception $e ) {
            $this->assertTrue($e instanceof PlanLimitNotValid);
        }
    }
    public function test__check(){
        $this->createApplication();
        $user = $this->limitedUser();
        
        $stats = $this->statisticsService($user)->current();
        $this->assertTrue($this->statisticsService($user)->check(UserStatistic::TOURNAMENTS));
        $this->assertTrue($this->statisticsService($user)->check(UserStatistic::PARTICIPANTS));
    }

}