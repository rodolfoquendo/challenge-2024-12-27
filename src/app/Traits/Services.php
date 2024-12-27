<?php
namespace App\Traits;

use App\Models\User;
use App\Services\Models\SkillService;

trait Services
{
    public ?User $user = null;

    static $serviceInstances = [];

    /**
     * Sets the user that will be used by the services
     *
     * @param  \App\Models\User $user This is for that
     *
     * @return void                   This returns if this happens
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Returns an instance of the SkillService 
     * If a user is given then is also set in the service instance
     *
     * @param  \App\Models\User|null       $user The user that will use the service
     *
     * @return \App\Services\SkillService        The service instance with the user set (if given)
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 
     */
    public function skillService(?User $user = null): SkillService
    {
        $service = SkillService::class;
        if (empty(self::$serviceInstances[$service])) {
            self::$serviceInstances[$service] = app()->make($service);
            if(!$this->user instanceof User){
                $this->user = auth()->user();
            }
            if ($this->user instanceof User) {
                self::$serviceInstances[$service]->setUser($this->user);
            }
        }
        if ($user instanceof User) {
            self::$serviceInstances[$service]->setUser($user);
        }
        return self::$serviceInstances[$service];
    }

}
