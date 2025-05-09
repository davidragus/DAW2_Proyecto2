<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GameRoom>
 */
class GameRoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'game_id' => 1,
            'name' => $this->faker->word,
            'max_users' => $this->faker->numberBetween(2, 10),
            'status' => "WAITING",
        ];
    }
}
