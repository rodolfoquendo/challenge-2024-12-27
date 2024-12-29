<?php
namespace App\Traits;

use App\Exceptions\UserNotSet;
use App\Models\User;
use App\Services\Features\TournamentFeatureService;
use App\Services\Models\PlanService;
use App\Services\Models\SkillService;
use App\Services\Models\StatisticsService;
use App\Services\Models\TournamentParticipantService;
use App\Services\Models\TournamentService;
use App\Services\Models\TournamentStepsService;
use App\Services\Models\UserService;
use App\Services\Validators\EmailValidationService;

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
     * Checks if the user is set and returns it
     *
     * @throws \App\Exceptions\UserNotSet if the user is not an instance of user
     * @return User
     *
     * @author Rodolfo Oquendo <roquendo@afluenta.com>
     * @copyright 2024 Afluenta
     */
    public function getUser(): User
    {
        if(!$this->sessionIsSet()){
            throw new UserNotSet();
        }
        return $this->user;
    }

    /**
     * Returns an instance of the SkillService 
     * If a user is given then is also set in the service instance
     *
     * @param  \App\Models\User|null            $user The user that will use the service
     *
     * @return \App\Services\Models\UserService       The service instance with the user set (if given)
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 
     */
    public function userService(?User $user = null): UserService
    {
        return $this->getService(UserService::class, $user);
    }

    /**
     * Returns an instance of the SkillService 
     * If a user is given then is also set in the service instance
     *
     * @param  \App\Models\User|null             $user The user that will use the service
     *
     * @return \App\Services\Models\SkillService       The service instance with the user set (if given)
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 
     */
    public function skillService(?User $user = null): SkillService
    {
        return $this->getService(SkillService::class, $user);
    }

    /**
     * Returns an instance of the EmailValidationService 
     * If a user is given then is also set in the service instance
     *
     * @param  \App\Models\User|null                          $user The user that will use the service
     *
     * @return \App\Services\Validators\EmailValidationService      The service instance with the user set (if given)
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 
     */
    public function emailValidationService(?User $user = null): EmailValidationService
    {
        return $this->getService(EmailValidationService::class, $user);
    }

    /**
     * Returns an instance of the PlanService 
     * If a user is given then is also set in the service instance
     *
     * @param  \App\Models\User|null            $user The user that will use the service
     *
     * @return \App\Services\Models\PlanService       The service instance with the user set (if given)
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 
     */
    public function planService(?User $user = null): PlanService
    {
        return $this->getService(PlanService::class, $user);
    }

    /**
     * Returns an instance of the StatisticsService 
     * If a user is given then is also set in the service instance
     *
     * @param  \App\Models\User|null                  $user The user that will use the service
     *
     * @return \App\Services\Models\StatisticsService       The service instance with the user set (if given)
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 
     */
    public function statisticsService(?User $user = null): StatisticsService
    {
        return $this->getService(StatisticsService::class, $user);
    }

    /**
     * Returns an instance of the TournamentService 
     * If a user is given then is also set in the service instance
     *
     * @param  \App\Models\User|null                  $user The user that will use the service
     *
     * @return \App\Services\Models\TournamentService       The service instance with the user set (if given)
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 
     */
    public function tournamentService(?User $user = null): TournamentService
    {
        return $this->getService(TournamentService::class, $user);
    }

    /**
     * Returns an instance of the TournamentParticipantService 
     * If a user is given then is also set in the service instance
     *
     * @param  \App\Models\User|null                  $user The user that will use the service
     *
     * @return \App\Services\Models\TournamentParticipantService The service instance with the user set (if given)
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 
     */
    public function tournamentParticipantService(?User $user = null): TournamentParticipantService
    {
        return $this->getService(TournamentParticipantService::class, $user);
    }

    /**
     * Returns an instance of the TournamentFeatureService 
     * If a user is given then is also set in the service instance
     *
     * @param  \App\Models\User|null                  $user The user that will use the service
     *
     * @return \App\Services\Features\TournamentFeatureService The service instance with the user set (if given)
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 
     */
    public function tournamentFeatureService(?User $user = null): TournamentFeatureService
    {
        return $this->getService(TournamentFeatureService::class, $user);
    }

    /**
     * Returns an instance of the TournamentStepsService 
     * If a user is given then is also set in the service instance
     *
     * @param  \App\Models\User|null                  $user The user that will use the service
     *
     * @return \App\Services\Models\TournamentStepsService The service instance with the user set (if given)
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 
     */
    public function tournamentStepsService(?User $user = null): TournamentStepsService
    {
        return $this->getService(TournamentStepsService::class, $user);
    }


    /**
     * Gets the static instance of a given service
     *
     * @param  string                $service The service class name
     * @param  \App\Models\User|null $user    The user that will use the service
     *
     * @return \App\Services\ServiceBase The Service instance                           
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 Rodolfo Oquendo
     */
    private function getService(string $service, ?User $user = null){
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
