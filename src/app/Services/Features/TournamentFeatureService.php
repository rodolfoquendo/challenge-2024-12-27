<?php 
namespace App\Services\Features;

use App\Models\Tournament;
use App\Models\TournamentParticipant;
use App\Services\ServiceBase;

class TournamentFeatureService extends ServiceBase
{
    public function calculateResult(Tournament $tournament)
    {
        if(strtotime($tournament->starts_at) > time() || strtotime($tournament->ends_at) > time()){
            return false;
        }
        $tournamentParticipants = $this->tournamentParticipantService()->get($tournament,['participant','participant.participantSkills','participant.participantSkills.skill']);
        if(!$this->participantAmountCheck(count($tournamentParticipants))){
            return false;
        }
        $participants = [];
        /**
         * @var TournamentParticipant $tournamentParticipant 
         */
        foreach($tournamentParticipants as $tournamentParticipant){
            $participants[] = $tournamentParticipant->participant;
        }
        $calculated = $this->tournamentStepsService()->add($tournament, $participants);
        return $calculated;
    }

    public function participantAmountCheck(int $participantAmount)
    {
        return $participantAmount >= 2 && ($participantAmount & ($participantAmount - 1)) == 0;
    }
}
