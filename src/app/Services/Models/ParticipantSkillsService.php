<?php 
namespace App\Services\Models;

use App\Models\Participant;
use App\Models\ParticipantSkill;
use App\Models\Skill;
use App\Services\ServiceBase;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;

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
            $participantSkills[$skill->id] = $this->getFromCombination($participant, $skill);
        }
        return $participantSkills;
    }

    public function getFromCombination(Participant $participant, Skill $skill): ParticipantSkill
    {
        /**
         * For some reason this shit is not doing the job like it shoud
         * will leave this commented code to understand & test how it is failing
         * i have no time for this.
         * @todo check this shit later
         */
        try {
            $participantSkill = ParticipantSkill::where('participant_id', $participant->id)
                ->where('skill_id', $skill->id)
                ->first();
            if(!$participantSkill instanceof ParticipantSkill){
                // dd('not',$participantSkill, DB::select("select * from participant_skills where participant_id={$participant->id} and skill_id={$skill->id}"));
                $participantSkill = new ParticipantSkill();
                $participantSkill->participant_id = $participant->id;
                $participantSkill->skill_id = $skill->id;
                return $this->update($participantSkill, 0);
            }  
        } catch ( UniqueConstraintViolationException $e ) {
            return $participantSkill;
        }
        return $participantSkill;
    }

    public function update(ParticipantSkill $participantSkill,  $level): ParticipantSkill
    {
        $participantSkill->level = $level;
        $participantSkill->save();
        return $participantSkill->refresh();
    }

    public function calculateSkillTotal(Participant $participant){
        $total = 0;
        $participantSkills = $this->get($participant);
        foreach($participantSkills as $participantSkill){
            $total += $participantSkill->level;
        }
        return (int)floor($total / count($participantSkills));
    }

}