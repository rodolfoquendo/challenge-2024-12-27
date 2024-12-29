<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
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