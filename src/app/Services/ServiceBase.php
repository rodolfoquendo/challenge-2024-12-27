<?php
namespace App\Services;

use App\Models\Plan;
use App\Models\User;
use App\Traits\Services;

class ServiceBase
{
    use Services;

    /**
     * Checks if the session user has unlimited plan
     *
     * @return bool 
     *
     * @author Rodolfo Oquendo <roquendo@afluenta.com>
     * @copyright 2024 Afluenta
     */
    public function sessionUserIsUnlimited(): bool
    {
        return ($this->sessionIsSet() && $this->getUser()->plan_id == Plan::UNLIMITED);
    }
    
    public function traceToSlackCode(\Throwable $e){
        $trace = [];
        foreach($e->getTrace() as $step){
            $step = (object)$step;
            if(empty($step->file)){
                continue;
            }
            $trace[] = "{$step->file}:{$step->line} ({$step->function})";
        }
        return "```" . json_encode($trace, \JSON_PRETTY_PRINT) ."```";
    }

    /**
     * Checks if the user is authenticated (and set to the service)
     *
     * @return bool
     *
     * @author Rodolfo Oquendo <roquendo@afluenta.com>
     * @copyright 2024 Afluenta
     */
    public function sessionIsSet(): bool
    {
        return $this->user instanceof User;
    }
}