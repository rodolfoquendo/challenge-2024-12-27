<?php

namespace Tests\App\Models;

use App\Models\Gender;
use App\Models\Participant;
use App\Models\ParticipantSkill;
use App\Models\Skill;
use App\Models\Tournament;
use App\Models\TournamentParticipant;
use App\Models\User;

class TournamentParticipantTest extends \Tests\TestCase
{
    public function test__structure(){
        $this->createApplication();
        $model = TournamentParticipant::with(['tournament','participant'])->find(1);
        $this->assertTrue($model instanceof TournamentParticipant);
        $this->assertTrue(isset($model->id));
        $this->assertTrue(isset($model->tournament_id));
        $this->assertTrue(isset($model->participant_id));
        $this->assertTrue(isset($model->entry_fee_paid));
        $this->assertTrue(isset($model->winner));
        $this->assertTrue($model->entry_fee_paid == 0);
        $this->assertTrue($model->winner == 0);
        $this->assertTrue($model->tournament instanceof Tournament);
        $this->assertTrue($model->participant instanceof Participant);
    }

}