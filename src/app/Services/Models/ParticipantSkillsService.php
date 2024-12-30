<?php 
namespace App\Services\Models;

use App\Models\Participant;
use App\Models\ParticipantSkill;
use App\Models\Plan;
use App\Models\Skill;
use App\Models\User;
use App\Services\ServiceBase;
use Illuminate\Support\Facades\Cache;

class ParticipantSkillsService extends ServiceBase
{

    /**
     * Gets all the ParticipantSkills for a Participant
     * They get created if not exists and if its participant gender is the same as the skill 
     *
     * @param  \App\Models\Participant $participant The participant to get the skills 
     *
     * @return array                                placeholder_return_description
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 Rodolfo Oquendo
     */
    public function get(Participant $participant): array
    {
        $participantSkills = [];
        /**
         * @var \App\Models\Skill $skill
         */
        foreach(Skill::getCached() as $skill){
            if($skill->gender_id !== $participant->gender_id){
                continue;
            }
            $participantSkill = ParticipantSkill::where('participant_id', $participant->id)
                ->where('skill_id', $skill->id)
                ->first();
            if(!$participantSkill instanceof $participantSkill){
                $participantSkill = new ParticipantSkill();
                $participantSkill->participant_id = $participant->id;
                $participantSkill->skill_id = $skill->id;
                $this->update($participantSkill, 0);
            }
            $participantSkills[$skill->id] = $participantSkill;
        }
        return $participantSkills;
    }

    public function getFromCombination(Participant $participant, Skill $skill): Participant
    {
        $all = $this->get($participant);
        return $all[$skill->id];
    }

    public function update(ParticipantSkill $participantSkill,  $level)
    {
        $participantSkill->level = $level;
        $participantSkill->save();
        return $participantSkill;
    }

    public function calculateSkillTotal(Participant $participant){
        $total = 0;
        foreach($participant->participantSkills as $participantSkill){
            $total += $participantSkill->level;
        }
        return (int)floor($total / count($participant->participantSkills));
    }

}