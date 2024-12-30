<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Gender;
use App\Models\Participant;
use App\Models\ParticipantSkill;
use App\Models\Skill;
use App\Models\Tournament;
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
            ->count(pow(2,6))
            ->state(new Sequence(
                ['gender_id' => Gender::F],
                ['gender_id' => Gender::M]
            ))
            ->create();
        Tournament::factory()
            ->count(10)
            ->state(new Sequence(
                [
                    'starts_at' => date('Y-m-d 00:00:00'),
                    'ends_at' => date('Y-m-d 00:00:01'),
                    'gender_id' => fn($x) => rand(1,2)
                ],
                [
                    'starts_at' => date('Y-m-d 00:00:00', time() - (24 * 3600)),
                    'ends_at' => date('Y-m-d 00:00:01', time() - (24 * 3600)),
                    'gender_id' => fn($x) => rand(1,2)
                ],
                [
                    'starts_at' => date('Y-m-d 00:00:00', time() + (24 * 3600)),
                    'ends_at' => date('Y-m-d 00:00:01', time() + (24 * 3600)),
                    'gender_id' => fn($x) => rand(1,2)
                ],
            ))
            ->create();
    }
}
