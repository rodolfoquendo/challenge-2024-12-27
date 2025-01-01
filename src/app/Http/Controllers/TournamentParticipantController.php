<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Gender;
use App\Models\Plan;
use App\Models\Tournament;
use App\Traits\Http;
use Illuminate\Http\Request;

class TournamentParticipantController extends Controller
{

    public function get(Request $request){
        try {
            $tournament = $this->tournamentService()->getByCod($request->tournament);
            return $this->success($this->tournamentParticipantService()
                ->get($tournament));   
        } catch ( \Exception $e ) {
            return $this->error($e);
        }
    }

    public function add(Request $request){
        try {
            $participant = $this->participantService()->get($request->name);
            $tournament = $this->tournamentService()->getByCod($request->tournament);
            return $this->success($this->tournamentParticipantService()
                ->add($tournament, $participant));   
        } catch ( \Exception $e ) {
            return $this->error($e);
        }
    }

    public function remove(Request $request){
        try {
            $participant = $this->participantService()->get($request->name);
            $tournament = $this->tournamentService()->getByCod($request->tournament);
            return $this->success($this->tournamentParticipantService()
                ->remove($tournament, $participant));   
        } catch ( \Exception $e ) {
            return $this->error($e);
        }
    }

    public function removeAll(Request $request){
        try {
            $participant = $this->participantService()->get($request->name);
            $tournament = $this->tournamentService()->getByCod($request->tournament);
            return $this->success($this->tournamentParticipantService()
                ->remove($tournament, $participant));   
        } catch ( \Exception $e ) {
            return $this->error($e);
        }
    }

    public function moveParticipants(Request $request){
        try {
            $from = $this->tournamentService()->getByCod($request->from);
            $to = $this->tournamentService()->getByCod($request->to);
            return $this->success($this->tournamentParticipantService()
                ->moveParticipants($from, $to));   
        } catch ( \Exception $e ) {
            return $this->error($e);
        }
    }

}