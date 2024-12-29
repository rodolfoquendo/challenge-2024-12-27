<?php 
namespace App\Services\Models;

use App\Models\Tournament;
use App\Models\TournamentStep;
use App\Services\ServiceBase;

class TournamentStepsService extends ServiceBase
{
    public function lastStep(Tournament $tournament): ?TournamentStep
    {
        return TournamentStep::where('tournament_id', $tournament->id)
            ->last();
    }

    public function add(Tournament $tournament, array $participants = []): true
    {
        $luckPercentage = config('roquendo.luck');
        $levelPercentage = 1 - $luckPercentage;
        $step = new TournamentStep();
        $step->tournament_id = $tournament->id;
        shuffle($participants);
        $result = [];
        $oldParticipants = [];
        $newParticipants = [];
        for($z = 0; $z < count($participants); $z++){
            /**
             * @var Participant
             */
            $part1 = $participants[$z];
            /**
             * @var Participant
             */
            $part2 = $participants[$z + 1];
            $luck1 = rand(0,100) * $luckPercentage;
            $luck2 = rand(0,100) * $luckPercentage;
            $part1Total = ($part1->level * $levelPercentage) + ($luck1); 
            $part2Total = ($part2->level * $levelPercentage) + ($luck2); 
            while($part2Total == $part1Total){
                $luck2 = rand(0,100) * $luckPercentage;
                $part2Total = ($part2->level * $levelPercentage) + ($luck2); 
            }
            $result[] = [
                [
                    "id" => $part1->id,
                    "luck" => $luck1,
                    "level" => $part1->level * $levelPercentage,
                    "total" => $part1Total,
                ],
                [
                    "id" => $part2->id,
                    "luck" => $luck2,
                    "level" => $part2->level * $levelPercentage,
                    "total" => $part2Total,
                ],
            ];
            $oldParticipants[] = $part1->id;
            $oldParticipants[] = $part2->id;
            $newParticipants[] = $part1Total > $part2Total ? $part1 : $part2;
            $z++;
        }
        $step->data = \json_encode($oldParticipants);
        $step->result = \json_encode($result);
        $step->save();
        if(count($newParticipants) > $tournament->max_winners){
            return $this->add($tournament, $newParticipants);
        }
        return true;
    }

}