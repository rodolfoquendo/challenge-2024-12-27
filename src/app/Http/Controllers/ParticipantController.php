<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Gender;
use App\Models\Plan;
use App\Models\Tournament;
use App\Traits\Http;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{

    public function get(Request $request){
        try {
            return $this->success($this->tournamentService()
                ->getByCod($request->cod));   
        } catch ( \Exception $e ) {
            return $this->error($e);
        }
    }

    public function create(Request $request){
        try {
            return $this->success($this->participantService()
                ->create(
                    $request->name, 
                    $request->level, 
                ));   
        } catch ( \Exception $e ) {
            return $this->error($e);
        }
    }


    public function update(Request $request){
        try {
            return $this->success($this->participantService()
                ->update(
                    $this->participantService()->get($request->name), 
                    $request->level, 
                ));   
        } catch ( \Exception $e ) {
            return $this->error($e);
        }
    }


}