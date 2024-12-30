<?php 
namespace App\Services\Models;

use App\Exceptions\PlanLimitNotValid;
use App\Exceptions\StatNotValid;
use App\Models\Plan;
use App\Models\Skill;
use App\Models\Tournament;
use App\Models\User;
use App\Models\UserStatistic;
use App\Services\ServiceBase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StatisticsService extends ServiceBase
{

    /**
     * Gets the user plan
     *
     * @return \App\Models\Plan The plan
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 Rodolfo Oquendo
     */
    private function plan(): Plan
    {
        return $this->userService($this->getUser())->getPlan();
    }

    /**
     * Gets or create the current Statistic for the user
     *
     * @return \App\Models\UserStatistic The statistic
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 Rodolfo Oquendo
     */
    public function current(): UserStatistic
    {
        $user = $this->getUser();
        $stat = UserStatistic::where('user_id', $user->id)
            ->where('date', date('Y-m'))
            ->first();
        if(!$stat instanceof UserStatistic){
            $stat = new UserStatistic();
            $stat->user_id = $user->id;
            $stat->date = date('Y-m');
            $stat->save();
        }
        $stat->tournaments = Tournament::where('user_id', $user->id)->count();
        $stat->participants = DB::scalar("SELECT count(id) from tournament_participants where tournament_id IN (select id from tournaments where user_id = ?)",[$user->id]);
        $stat->save();
        return $stat;
    }

    /**
     * Checks if a limit is not being halted
     *
     * @param  string $cod placeholder_param_description
     *
     * @return bool        If the stat passes
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 Rodolfo Oquendo
     */
    public function check(string $cod): bool
    {
        $stat = $this->current();
        $plan = $this->plan();
        if(!array_key_exists($cod, $stat->toArray())){
            throw new StatNotValid($cod);
        }
        if(!is_null($plan) && !array_key_exists($cod, $plan->toArray())){
            throw new PlanLimitNotValid($cod);
        }
        return is_null($plan->$cod) || $stat->$cod < $plan->$cod;
    }
}