<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Participant;
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
            ->count(8)
            ->female()
            ->create();
        Participant::factory()
            ->count(8)
            ->male()
            ->create();
    }
}
