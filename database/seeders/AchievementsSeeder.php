<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;

class AchievementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $achievements = [
            [
                'name' => 'First Game',
                'description' => 'You have played your first game',
                'achievement_type' => 'games',
                'amount' => 1,
                'reward_amount' => 10
            ],
            [
                'name' => 'First win',
                'description' => 'You have won your first game',
                'achievement_type' => 'wins',
                'amount' => 1,
                'reward_amount' => 20
            ]
        ];
        foreach ($achievements as $achievement) {
            Achievement::create($achievement);
        }
    }
}
