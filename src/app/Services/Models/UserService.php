<?php 
namespace App\Services\Models;

use App\Models\Plan;
use App\Models\Skill;
use App\Models\User;
use App\Services\ServiceBase;
use Illuminate\Support\Facades\Cache;

class UserService extends ServiceBase
{   
    private ?Plan $plan = null;

    /**
     * Gets an user by email
     *
     * @param  string                 $cod The email
     *
     * @return \App\Models\User|null       The user if exists
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 Rodolfo Oquendo
     */
    public function getByEmail(string $email): ?User
    {
        return $this->emailValidationService()->validate($email) 
            ? User::where('email', $this->emailValidationService()->sanitize($email))->first() 
            : null;
    }

    public function createOrUpdate(string $name, string $email): User
    {
        $email = $this->emailValidationService($this->user)->sanitize($email);
        if(!$this->emailValidationService($this->user)->validate($email)){
            return null;
        }
        $user = $this->getByEmail($email);
        if(!$user instanceof User){
            $user = new User();
            $user->email = $email;
        }
        $user->name = $name;
        $user->save();
        return $user;
    }

    public function getPlan(): Plan
    {
        return Plan::findCached($this->user->plan_id);
    }
}