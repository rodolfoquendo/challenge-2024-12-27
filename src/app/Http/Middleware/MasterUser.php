<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Traits\Http;
use Illuminate\Http\Response;

class MasterUser
{
    use Http;
    public function handle(\Illuminate\Http\Request $request, \Closure $next)
    {
        $user = auth()->user();
        $master =  User::master();
        if(!$user instanceof User || $user->id != $master->id){
            abort(403);
        }

       return $next($request);
    }
}
