<?php

namespace App\Http\Middleware;

use App\Models\User;

class MasterUser
{
    use \App\Traits\Http;
    public function handle(\Illuminate\Http\Request $request, \Closure $next)
    {
        $user = auth()->user();
        $roquendo =  User::where('email', env('MASTER_EMAIL','rodolfoquendo@gmail.com'))->first();
        if(!$user instanceof User || $user->id != $roquendo->id){
            abort(\Illuminate\Http\Response::HTTP_UNAUTHORIZED);
        }

       return $next($request);
    }
}
