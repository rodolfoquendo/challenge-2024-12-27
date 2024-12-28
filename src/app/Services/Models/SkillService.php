<?php 
namespace App\Services\Models;

use App\Models\Skill;
use App\Services\ServiceBase;
use Illuminate\Support\Facades\Cache;

class SkillService extends ServiceBase
{

    /**
     * Gets a skill by cod and caches it
     *
     * @param  string                 $cod The skill cod to look after
     *
     * @return \App\Models\Skill|null      placeholder_return_description
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 Rodolfo Oquendo
     */
    public function getByCod(string $cod): ?Skill
    {
        $cod = trim(\strtolower($cod));
        $cacheKey = md5(__METHOD__ . $cod);
        if(!Cache::has($cacheKey)){
            Cache::forever($cacheKey, Skill::where('cod', $cod)->first());
        }
        return Cache::get($cacheKey);
    }
}