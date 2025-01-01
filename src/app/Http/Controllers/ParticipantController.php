<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ParticipantFormRequest;
use App\Http\Requests\ParticipantGetRequest;
use App\Models\Gender;
use App\Models\Plan;
use App\Models\Tournament;
use App\Traits\Http;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{

    public function get(ParticipantGetRequest $request){
        try {
            return $this->success($this->participantService()
                ->get($request->name));   
        } catch ( \Exception $e ) {
            return $this->error($e);
        }
    }

    public function create(ParticipantFormRequest $request){
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


    public function update(ParticipantFormRequest $request){
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