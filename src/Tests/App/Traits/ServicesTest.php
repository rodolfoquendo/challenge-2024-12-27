<?php

namespace Tests\App\Traits;

use App\Exceptions\UserNotSet;
use App\Models\Plan;
use App\Models\Tournament;
use App\Models\TournamentParticipant;
use App\Models\User;
use App\Services\Features\TournamentFeatureService;
use App\Services\Models\ParticipantSkillsService;
use App\Services\Models\PlanService;
use App\Services\Models\SkillService;
use App\Services\Models\StatisticsService;
use App\Services\Models\TournamentParticipantService;
use App\Services\Models\TournamentService;
use App\Services\Models\TournamentStepsService;
use App\Services\Models\UserService;
use App\Services\Validators\EmailValidationService;

class ServicesTest extends \Tests\TestCase
{

    public function test__setUser(){
        $this->createApplication();
        $user = $this->limitlessUser();
        $this->assertTrue($this->setUser($user) instanceof self);
    }
    public function test__getUser__exception(){
        $this->createApplication();
        try {
            $this->getUser();   
        } catch ( \Exception $e ) {
            $this->assertTrue($e instanceof UserNotSet);
        }
    }
    public function test__getUser__success(){
        $this->createApplication();
        $user = $this->limitlessUser();
        $this->assertTrue($this->setUser($user) instanceof self);
        $this->assertTrue($this->getUser() instanceof User);   
    }
    public function test__sessionIsSet(){
        $this->createApplication();
        $user = $this->limitlessUser();
        $this->assertFalse($this->sessionIsSet());   
        $this->assertTrue($this->setUser($user)->sessionIsSet());
    }
    public function test__servicesInstances(){
        $this->createApplication();
        $user = $this->limitlessUser();
        $this->assertTrue($this->userService($user) instanceof UserService);
        $this->assertTrue($this->skillService($user) instanceof SkillService);
        $this->assertTrue($this->emailValidationService($user) instanceof EmailValidationService);
        $this->assertTrue($this->planService($user) instanceof PlanService);
        $this->assertTrue($this->participantSkillsService($user) instanceof ParticipantSkillsService);
        $this->assertTrue($this->statisticsService($user) instanceof StatisticsService);
        $this->assertTrue($this->tournamentService($user) instanceof TournamentService);
        $this->assertTrue($this->tournamentParticipantService($user) instanceof TournamentParticipantService);
        $this->assertTrue($this->tournamentFeatureService($user) instanceof TournamentFeatureService);
        $this->assertTrue($this->tournamentStepsService($user) instanceof TournamentStepsService);
    }

}