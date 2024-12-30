<?php 
namespace App\Services\Models;

use App\Models\Participant;
use App\Models\Plan;
use App\Models\User;
use App\Services\ServiceBase;
use Illuminate\Support\Facades\Cache;

class ParticipantService extends ServiceBase
{

    public function get($name){
        return Participant::where('name', $name)
            ->first();
    }

    public function create(string $name, int $level): Participant
    {
        $participant = $this->get($name);
        if(!$participant instanceof Participant){
            $participant = new Participant();
            $participant->name = $name;
        }
        return $this->update($participant, $level);
    }

    public function update(Participant $participant, $level): Participant
    {
        $participant->level = $level;
        $participant->save();
        $this->participantSkillsService()->get($participant);
        return $participant->refresh();
    }
}