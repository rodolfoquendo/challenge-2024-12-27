<?php

namespace Database\Factories;

use App\Models\Gender;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Participants>
 */
class ParticipantFactory extends Factory
{

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
