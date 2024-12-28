<?php 
namespace App\Services\Models;

use App\Exceptions\PlanLimitNotValid;
use App\Exceptions\StatNotValid;
use App\Models\Plan;
use App\Models\Skill;
use App\Models\User;
use App\Models\UserStatistic;
use App\Services\ServiceBase;
use Illuminate\Support\Facades\Cache;

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
        $this->checkPropertyExists($stat, $plan, $cod);
        return is_null($plan->cod) || $stat->$cod < $plan->$cod;
    }

    /**
     * Adds an amount to current statistics
     *
     * @param  string $cod the statistic code
     *
     * @return bool        if added (saved)
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 Rodolfo Oquendo
     */
    public function add(string $cod, int $amount = 1): bool
    {
        $stat = $this->current();
        $this->checkPropertyExists($stat, cod: $cod);
        $stat->$cod += $amount;
        return $stat->save();
    }

    /**
     * Removes an amount to current statistics
     *
     * @param  string $cod the statistic code
     *
     * @return bool        if added (saved)
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 Rodolfo Oquendo
     */
    public function remove(string $cod, int $amount = 1): bool
    {
        return $this->add($cod, $amount * -1);
    }

    /**
     * Checks if the given cod exists in plan and stats
     *
     * @param  \App\Models\UserStatistic $stat The statistics
     * @param  \App\Models\Plan|null     $plan The plan 
     * @param  string                    $cod  The cod to check
     *
     * @return void                            
     * 
     * @throws \App\Exceptions\StatNotValid      If the stat property does not exists
     * @throws \App\Exceptions\PlanLimitNotValid If the plan limit does not exists
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 Rodolfo Oquendo
     */
    private function checkPropertyExists(UserStatistic $stat, ?Plan $plan = null, string $cod)
    {
        if(!isset($stat->$cod)){
            throw new StatNotValid($cod);
        }
        if(!isset($plan->$cod)){
            throw new PlanLimitNotValid($cod);
        }
    }
}