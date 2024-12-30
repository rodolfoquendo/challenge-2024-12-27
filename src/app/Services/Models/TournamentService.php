<?php 
namespace App\Services\Models;

use App\Models\Gender;
use App\Models\Participant;
use App\Models\Plan;
use App\Models\Tournament;
use App\Models\TournamentStep;
use App\Models\User;
use App\Models\UserStatistic;
use App\Services\ServiceBase;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class TournamentService extends ServiceBase
{

    /**
     * Gets a plan by its cod
     *
     * @param  string                 $cod The plan cod
     *
     * @return \App\Models\Tournament|null       The plan if exists
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 Rodolfo Oquendo
     */
    public function getByCod(string $cod): ?Tournament
    {
        $cod = \strtolower(trim($cod));
        $user = $this->getUser();
        return Tournament::where('user_id', $user->id)
            ->where('cod', $cod)
            ->first();
    }

    /**
     * Gets all the tournaments for the user
     *
     * @return Illuminate\Database\Eloquent\Collection|null 
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 Rodolfo Oquendo
     */
    public function getAll(): ?Collection
    {
        $user = $this->getUser();
        return Tournament::where('user_id', $user->id)
            ->get();
    }

    /**
     * Undocumented function
     *
     * @param  Gender                $gender           The gender of the participats 
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
    public function create(
        Gender $gender,
        string $cod, 
        string $title, 
        DateTime|string|null $starts_at = null, 
        DateTime|string|null $ends_at = null, 
        float $entry_fee = 0,
        int $max_participants = null,
        int $max_winners = 1 
    ): Tournament
    {
        $cod = \strtolower(trim($cod));
        $user = $this->getUser();
        $tournament = $this->getByCod($cod);
        if($tournament instanceof Tournament){
            abort(422, 'Tournament already exists');
        }
        if(!$this->statisticsService($this->getUser())->check(UserStatistic::TOURNAMENTS)){
            abort(429, "Tournaments limit reached");
        }
        $tournament = new Tournament();
        $tournament->user_id = $user->id;
        $tournament->gender_id = $gender->id;
        $tournament->cod = $cod;
        return $this->update(
            $tournament, 
            $title, 
            $starts_at, 
            $ends_at, 
            $entry_fee, 
            $max_participants, 
            $max_winners
        );
    }

    /**
     * Updates a tournament
     *
     * @param  \App\Models\Tournament $tournament       The tournament to be updated
     * @param  string                 $title            The tournament title
     * @param  \DateTime|string|null  $starts_at        The start date
     * @param  \DateTime|string|null  $ends_at          The end date
     * @param  int                    $entry_fee        The entry fee
     * @param  int|null               $max_participants the max participants, null if no limit
     * @param  int                    $max_winners      the max winners to be returned
     *
     * @return \App\Models\Tournament                   The tournament updated
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 Rodolfo Oquendo
     */
    public function update(
        Tournament $tournament,
        string $title, 
        DateTime|string|null $starts_at = null, 
        DateTime|string|null $ends_at = null, 
        float $entry_fee = 0,
        int $max_participants = null,
        int $max_winners = 1 
    ): Tournament
    {
        $starts_at = $starts_at instanceof DateTime ? $starts_at->format('Y-m-d H:i:s') : $starts_at;
        $starts_at = is_null($starts_at) ? date("Y-m-d H:i:s") : $starts_at;
        $ends_at = $ends_at instanceof DateTime ? $ends_at->format('Y-m-d H:i:s') : $ends_at;
        $ends_at = is_null($ends_at) ? date("Y-m-d H:i:s") : $ends_at;
        $tournament->title = $title;
        $tournament->starts_at = $starts_at;
        $tournament->ends_at = $ends_at;
        $tournament->entry_fee = $entry_fee;
        $tournament->max_participants = $max_participants;
        $tournament->max_winners = $max_winners;
        $tournament->save();
        return $tournament;
    }

    public function getWinner(Tournament $tournament): ?Participant
    {
        $lastStep = $this->tournamentStepsService()
            ->lastStep($tournament);
        if(!$lastStep instanceof TournamentStep){
            return null;
        }
        $result = \json_decode($lastStep->result, false);
        $highest = 0;
        $winner_id = null;
        foreach($result as $participants){
            foreach($participants as $participantData){
                $participantData = (object)$participantData;
                if($participantData->total > $highest){
                    $winner_id = $participantData->id;
                    $highest = $participantData->total;
                }
            }
        }
        return Participant::find($winner_id);
    }

}