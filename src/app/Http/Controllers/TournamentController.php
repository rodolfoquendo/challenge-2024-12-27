<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Gender;
use App\Models\Plan;
use App\Models\Tournament;
use App\Traits\Http;
use Illuminate\Http\Request;

class TournamentController extends Controller
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
            return $this->success($this->tournamentService()
                ->create(
                    Gender::findCached($request->gender_id), 
                    $request->cod, 
                    $request->title, 
                    $request->input('starts_at'),
                    $request->input('ends_at'),
                    $request->input('entry_fee',0),
                    $request->input('max_participants',0),
                    $request->input('max_winners',1)
                ));   
        } catch ( \Exception $e ) {
            return $this->error($e);
        }
    }

    public function update(Request $request){
        try {
            $tournament = Tournament::find($request->id);
            return $this->success($this->tournamentService()
                ->update(
                    $tournament,
                    $request->title, 
                    $request->input('starts_at'),
                    $request->input('ends_at'),
                    $request->input('entry_fee',0),
                    $request->input('max_participants',0),
                    $request->input('max_winners',1)
                ));   
        } catch ( \Exception $e ) {
            return $this->error($e);
        }
    }

    public function winner(Request $request){
        try {
            $tournament = Tournament::find($request->id);
            return $this->success($this->tournamentService()
                ->getWinner($tournament));   
        } catch ( \Exception $e ) {
            return $this->error($e);
        }
    }


}