<?php 
namespace App\Services\Models;

use App\Models\Plan;
use App\Models\User;
use App\Services\ServiceBase;
use Illuminate\Support\Facades\Cache;

class PlanService extends ServiceBase
{

    /**
     * Gets a plan by its cod
     *
     * @param  string                 $cod The plan cod
     *
     * @return \App\Models\Plan|null       The plan if exists
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 Rodolfo Oquendo
     */
    public function getByCod(string $cod): ?Plan
    {
        $cod = \strtolower($cod);
        $cacheKey = md5(__METHOD__ . $cod);
        if(!Cache::has($cacheKey)){
            Cache::forever($cacheKey, Plan::where('cod', $cod)->first());
        }
        return Cache::get($cacheKey);
    }

}