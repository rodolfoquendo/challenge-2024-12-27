<?php

namespace Database\Factories;

use App\Models\Gender;
use App\Models\Participant;
use App\Models\ParticipantSkill;
use App\Models\Skill;
use App\Models\Tournament;
use App\Models\TournamentParticipant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Participants>
 */
class TournamentFactory extends Factory
{
    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Tournament $tournament) {
            /** @var Participant $participant */
            foreach(Participant::get() as $participant){
                if($participant->gender_id !== $tournament->gender_id){
                    continue;
                }
                TournamentParticipant::create([
                    'tournament_id' => $tournament->id,
                    'participant_id' => $participant->id,
                ]);
            }
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'cod' => fake()->userName(),
            'title' => fake()->title(),
            'user_id' => 1,
            'starts_at' => date('Y-m-d 00:00:00'),
            'ends_at' => date('Y-m-d 00:00:01'),
            'entry_fee' => rand(0,2000),
            'max_winners' => 1
        ];
    }
}
