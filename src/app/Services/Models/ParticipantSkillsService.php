<?php 
namespace App\Services\Models;

use App\Models\Participant;
use App\Models\Plan;
use App\Models\User;
use App\Services\ServiceBase;
use Illuminate\Support\Facades\Cache;

class ParticipantSkillsService extends ServiceBase
{

    public function calculateSkillTotal(Participant $participant){
        $total = 0;
        foreach($participant->participantSkills as $participantSkill){
            $total += $participantSkill->level;
        }
        return (int)floor($total / count($participant->participantSkills));
    }

}