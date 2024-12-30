<?php

use Illuminate\Support\Facades\Facade;

return [
    'register' => [
        'open' => false
    ],
    'luck' => env('LUCK_PERCENTAGE', 0.2),
    'skills' => env('SKILLS_PERCENTAGE', 0.3),
];