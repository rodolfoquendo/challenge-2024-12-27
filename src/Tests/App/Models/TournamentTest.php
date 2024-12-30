<?php

namespace Tests\App\Models;

use App\Models\Gender;
use App\Models\Participant;
use App\Models\ParticipantSkill;
use App\Models\Skill;
use App\Models\Tournament;
use App\Models\TournamentParticipant;
use App\Models\User;

class TournamentTest extends \Tests\TestCase
{
    public function test__structure(){
        $this->createApplication();
        $model = Tournament::with(['user'])->find(1);
        $this->assertTrue(isset($model->id));
        $this->assertTrue(isset($model->user_id));
        $this->assertTrue(isset($model->cod));
        $this->assertTrue(isset($model->title));
        $this->assertTrue(isset($model->starts_at));
        $this->assertTrue(isset($model->ends_at));
        $this->assertTrue(isset($model->entry_fee));
        $this->assertTrue(isset($model->max_winners));
        $this->assertTrue(is_null($model->max_participants));
        $this->assertTrue($model->gender instanceof Gender);
        $this->assertTrue($model->user instanceof User);
        $this->assertTrue(count($model->tournamentParticipants) > 0);
        foreach($model->tournamentParticipants as $tournamentParticipant){
            $this->assertTrue($tournamentParticipant instanceof TournamentParticipant);
        }
    }

}