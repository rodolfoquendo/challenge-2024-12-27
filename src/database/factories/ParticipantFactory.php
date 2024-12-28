<?php

namespace Database\Factories;

use App\Models\Gender;
use App\Models\Participant;
use App\Models\ParticipantSkill;
use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Participants>
 */
class ParticipantFactory extends Factory
{
    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Participant $participant) {
            /** @var Skill $skill */
            foreach(Skill::getCached() as $skill){
                if($skill->gender_id != $participant->gender_id){
                    continue;
                }
                ParticipantSkill::create([
                    'skill_id' => $skill->id,
                    'participant_id' => $participant->id,
                    'level' => rand(0,100)
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
            'name' => fake()->name(),
            'gender_id' => Gender::F,
            'level' => rand(0,100),
        ];
    }
    
    public function female(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'gender_id' => Gender::F,
            ];
        });
    }
    
    public function male(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'gender_id' => Gender::M,
            ];
        });
    }
}
