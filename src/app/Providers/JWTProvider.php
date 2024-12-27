<?php 

namespace App\Providers;

use App\Exceptions\AuthInvalidCredentials;
use App\Models\User;
use Throwable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use stdClass;

class JWTProvider implements UserProvider
{
    /**
     * @codeCoverageIgnore
     */
    public function retrieveByToken ($identifier, $token) {
        throw new \Throwable('Method not implemented.');
    }

    /**
     * @codeCoverageIgnore
     */
    public function updateRememberToken (Authenticatable $user, $token) {
        throw new \Throwable('Method not implemented.');
    }

    /**
     * @codeCoverageIgnore
     */
    public function retrieveById ($identifier) {
        
        return $this->getUser($identifier);
    }

    public function retrieveByCredentials (array $credentials) {
        return $this->getMemberInstance($credentials);
    }

    public function validateCredentials (Authenticatable $user, array $credentials) {
        $username = $credentials['username'];
        $password = $credentials['password'];
        return $username == env('JWT_USERNAME') && $password == env('JWT_PASSWORD');
    }

    private function getMemberInstance ($credentials) {
        $username = $credentials['username'];
        $password = $credentials['password'];
        $success  = $username == env('JWT_USERNAME') && $password == env('JWT_PASSWORD');
        if(!$success){
            throw new AuthInvalidCredentials("Invalid Credentials");
        }
        return $this->getUser($username);
    }
    private function getUser($username){
        return tap(new User(), function ($user) use ($username) {
            $user->id = md5($username);
            $user->username = (string) $username;
        });
    }
    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false){
        
    }
}