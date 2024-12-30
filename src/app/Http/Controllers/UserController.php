<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Traits\Http;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function register(Request $request){
        try {
            if(!config('roquendo.register.open')){
                abort(403, "Registration closed");
            }
            return $this->success($this->userService()
                ->createOrUpdate(
                    Plan::findCached(Plan::FREE), 
                    $request->name, 
                    $request->email, 
                    $request->password
                ));   
        } catch ( \Exception $e ) {
            return $this->error($e);
        }

    }


}