<?php 
namespace App\Services\Models;

use App\Models\Participant;
use App\Models\Tournament;
use App\Models\TournamentParticipant;
use App\Services\ServiceBase;
use DateTime;

class TournamentParticipantService extends ServiceBase
{

    public function get(Tournament $tournament, array $eagerLoads = [])
    {
        $user = $this->getUser();
        if($tournament->user_id !== $user->id){
            abort(422, "Tournament does not exists for user");
        }
        return TournamentParticipant::with($eagerLoads)->where('tournament_id', $tournament->id)->get();
    }
    /**
     * Adds a participant to a tournament
     *
     * @param  string                $cod              the tournament shortcode
     * @param  string                $title            the tournament title
     * @param  \DateTime|string|null $starts_at        the start date
     * @param  \DateTime|string|null $ends_at          the end date
     * @param  int                   $entry_fee        the entry fee 
     * @param  int|null              $max_participants the max participants, null if no limit
     * @param  int                   $max_winners      the max winners to be returned
     *
     * @return \App\Models\Tournament                  The tournament
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 Rodolfo Oquendo
     */
    public function add(Tournament $tournament, Participant $participant): TournamentParticipant
    {
       
        $user = $this->getUser();
        if($tournament->user_id !== $user->id){
            abort(422, "Tournament does not exists for user");
        }
        if(!$this->statisticsService()->check('participants')){
            abort(429, "You have reached the participants limit");
        }
        $tournamentParticipant = TournamentParticipant::where('participant_id', $participant->id)
            ->where('tournament_id', $tournament->id)
            ->first();
        if(!$tournamentParticipant instanceof TournamentParticipant){
            $tournamentParticipant = new TournamentParticipant();
            $tournamentParticipant->tournament_id = $tournament->id;
            $tournamentParticipant->participant_id = $participant->id;
            $tournamentParticipant->save();
        }
        $this->statisticsService()->add('participants');
        return $tournamentParticipant;
    }

    /**
     * Deletes a tournament participant
     *
     * @param  \App\Models\Tournament  $tournament  placeholder_param_description
     * @param  \App\Models\Participant $participant placeholder_param_description
     *
     * @return bool                                 placeholder_return_description
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 Rodolfo Oquendo
     */
    public function remove(Tournament $tournament, Participant $participant): bool
    {
        $tournamentParticipant = TournamentParticipant::where('participant_id', $participant->id)
            ->where('tournament_id', $tournament->id)
            ->first();
        $this->statisticsService()->remove('participants',1);
        return !$tournamentParticipant instanceof TournamentParticipant || $tournament->delete();
    }

    /**
     * Removes all the participants of a tournamnet
     *
     * @param  \App\Models\Tournament $tournament The tournament to be emptied
     *
     * @return bool                               If the participants were deleted
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 Rodolfo Oquendo
     */
    public function removeAll(Tournament $tournament): bool
    {
        $count = TournamentParticipant::where('tournament_id', $tournament->id)->count();
        TournamentParticipant::where('tournament_id', $tournament->id)->delete();
        $this->statisticsService()->remove('participants',$count);
        return TournamentParticipant::where('tournament_id', $tournament->id)->count() == 0;
    }

    /**
     * Move all the participants from one tournament to other
     *
     * @param  \App\Models\Tournament $from The tournament resigning the users
     * @param  \App\Models\Tournament $to   The tournament receiving the users
     *
     * @return bool                         If the participants are no longer in the original tournament
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 Rodolfo Oquendo
     */
    public function moveParticipants(Tournament $from, Tournament $to): bool
    {
        $participantsFrom = TournamentParticipant::where('tournament_id', $from->id)->get();
        $participantsTo   = function ($participants = []) use ($to){
            /** @var TournamentParticipant $tournamentParticipant */
            foreach(TournamentParticipant::where('tournament_id', $to->id)->get() as $tournamentParticipant){
                $participants[$tournamentParticipant->participant_id] = $tournamentParticipant;
            }
            return $participants;
        };
        $participantsTo = $participantsTo();
        /**
         * @var TournamentParticipant $tournamentParticipant
         */
        foreach($participantsFrom as $tournamentParticipant){
            if(empty($participantsTo[$tournamentParticipant->participant_id])){
                $tournamentParticipant->tournament_id = $to->id;
                $tournamentParticipant->save();
            }else{
                $tournamentParticipant->delete();
                $this->statisticsService()->remove('participants',1);
            }
        }
        return TournamentParticipant::where('tournament_id', $from->id)->count() == 0
            && count($participantsFrom) <= TournamentParticipant::where('tournament_id', $to->id)->count();        
    }

}