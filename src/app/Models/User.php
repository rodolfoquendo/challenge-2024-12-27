<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Throwable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * About auth:
 * https://medium.com/@sirajul.anik/api-gateway-authenticating-user-without-db-storage-in-laravel-lumen-3ef1c1f300d3
 * https://jwt-auth.readthedocs.io/en/develop/quick-start/
 *
 * 
 * @property int    $id
 * @property int    $plan_id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * 
 * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
 * @copyright 2024 Afluenta
 */
class User extends Model implements JWTSubject, Authenticatable
{
    use AuthenticatableTrait // Laravel provided trait
        , SoftDeletes; 


    /**
     * Returns the master user
     * This was set up on user table migration
     * So we know for certain that 1 is the id
     *
     * @return User The master user, the owner, the company
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 Rodolfo Oquendo
     */
    public static function master(): User
    {
        if(!Cache::has(__METHOD__)){
            Cache::forever(__METHOD__, User::find(1));
        }
        return Cache::get(__METHOD__);
    }
    public function getCustomDataAttribute ($value) {
        /**
         * First check if the custom_data exists in jwt payload
         * If not found, only because the jwt is going to be
         * generated next. Otherwise, it'll always be there.
         * And attacker cannot modify
         */
        try {
            return auth()->payload()->get('custom_data');
        } catch ( Throwable $t ) {
            // When generating the payload
           return $value;
        }
    }

    public function getJWTIdentifier () {
        return $this->getKey();
    }

    public function getJWTCustomClaims () {
        return [ 
            'custom_data' => $this->custom_data,
        ];
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }
}