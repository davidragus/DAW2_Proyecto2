<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;
use Exception; // Add this import

class AchievementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
        $images = [
            'First Game' => 'first_game.png',
            'First win' => 'first_win.png'
        ];
        foreach ($achievements as $achievement) {
            $filePath = public_path('images/' . $images[$achievement['name']]);
        
            if (!file_exists($filePath)) {
                echo "Warning: File not found: {$filePath}\n";
                continue; // Salta este archivo y continúa con el siguiente
            }
        
            $newAchievement = Achievement::create($achievement);
            $newAchievement->addMedia($filePath)->toMediaCollection('Achievements');
        }
    }
}