<?php 
namespace App\Services\Models;

use App\Models\Participant;
use App\Models\Skill;
use App\Models\Tournament;
use App\Models\TournamentStep;
use App\Services\ServiceBase;

class TournamentStepsService extends ServiceBase
{

    /**
     * @todo Make it
     *
     * @param  \App\Models\Tournament $tournament   placeholder_param_description
     * @param  array                  $participants placeholder_param_description
     *
     * @return bool                                 placeholder_return_description
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 Rodolfo Oquendo
     */
    public function add(Tournament $tournament, array $participants = []): bool
    {
        $step = new TournamentStep();
        $luckPercentage = config('roquendo.luck');
        $skillPercentage = config('roquendo.skills');
        $levelPercentage = 1 - $luckPercentage - $skillPercentage;
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
            $level1 = $part1->level * $levelPercentage;
            $level2 = $part2->level * $levelPercentage;
            $skills1 = $this->participantSkillsService()->calculateSkillTotal($part1) * $skillPercentage;
            $skills2 = $this->participantSkillsService()->calculateSkillTotal($part2) * $skillPercentage;
            $part1Total = floor($level1 + $skills1 + $luck1); 
            $part2Total = floor($level2 + $skills2 + $luck2); 
            /**
             * This is random, so we need to stop this from counting for coverage
             * 
             * @codeCoverageIgnoreStart 
             */
            while($part2Total == $part1Total){
                $luck2 = rand(0,100) * $luckPercentage;
                $part2Total = ($part2->level * $levelPercentage) + ($luck2); 
            }
            /**
             * @codeCoverageIgnoreEnd
             */
            $result[] = [
                [
                    "id" => $part1->id,
                    "luck" => $luck1,
                    "level" => $level1,
                    "skills" => $skills1,
                    "total" => $part1Total,
                ],
                [
                    "id" => $part2->id,
                    "luck" => $luck2,
                    "level" => $level2,
                    "skills" => $skills2,
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


    /**
     * Gets the last step of a tournament for checking the winner
     *
     * @param  \App\Models\Tournament          $tournament placeholder_param_description
     *
     * @return \App\Models\TournamentStep|null             placeholder_return_description
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 Rodolfo Oquendo
     */
    public function lastStep(Tournament $tournament): ?TournamentStep
    {
        return TournamentStep::where('tournament_id', $tournament->id)
            ->orderby('id', 'desc')
            ->first();
    }
}