<?php

namespace Tests\App\Services\Validators;

use App\Models\Plan;
use App\Models\Tournament;
use App\Models\TournamentParticipant;
use App\Models\User;

class EmailValidationServiceTest extends \Tests\TestCase
{

    public function test__sanitize(){
        $this->assertTrue($this->emailValidationService()->sanitize(' asd ') == 'asd');
        $this->assertTrue($this->emailValidationService()->sanitize(' DSA ') == 'dsa');
    }
    public function test__validate(){
        $this->createApplication();
        $this->assertFalse($this->emailValidationService()->validate('asd'));
        $this->assertFalse($this->emailValidationService()->validate('asd@'));
        $this->assertFalse($this->emailValidationService()->validate('asd@asd'));
        $this->assertFalse($this->emailValidationService()->validate('asd@asd.com'));
        $this->assertTrue($this->emailValidationService()->validate('rodolfoquendo@gmail.com'));
    }

}