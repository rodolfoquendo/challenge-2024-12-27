<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Gender;
use App\Models\Participant;
use App\Models\ParticipantSkill;
use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Participant::factory()
            ->count(pow(2,3))
            ->state(new Sequence(
                ['gender_id' => fn($x) => [Gender::F, Gender::M][rand(0,1)]]
            ))
            ->create();
    }
}
