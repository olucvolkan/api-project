<?php

namespace Database\Seeders;

use App\Models\AgeLoad;
use Illuminate\Database\Seeder;

class AgeLoadSeeder extends Seeder
{
    public function run(): void
    {
        $ageLoads = [
            [
                'from_range' => 18,
                'max_range' => 30,
                'load' => 0.6
            ],
            [
                'from_range' => 31,
                'max_range' => 40,
                'load' => 0.7
            ],
            [
                'from_range' => 41,
                'max_range' => 50,
                'load' => 0.8
            ],
            [
                'from_range' => 51,
                'max_range' => 60,
                'load' => 0.9
            ],
            [
                'from_range' => 61,
                'max_range' => 70,
                'load' => 1.0
            ]
        ];

        foreach ($ageLoads as $ageLoad) {
            AgeLoad::firstOrCreate(
                [
                    'from_range' => $ageLoad['from_range'],
                    'max_range' => $ageLoad['max_range']
                ],
                $ageLoad
            );
        }
    }
} 