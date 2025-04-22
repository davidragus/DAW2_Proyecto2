<?php

namespace Tests\Feature;

use App\Models\GameRoom;
use Tests\TestCase;

class Game_roomTest extends TestCase
{

    public function test_can_create_a_game_room()
    {
        $data = [
            'game_id' => 1,
            'name' => 'Sala de prueba',
            'max_users' => 4,
            'status' => 'WAITING',
        ];

        $response = $this->postJson('/api/game-rooms', $data);

        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'Sala de prueba']);

        $this->assertDatabaseHas('game_rooms', $data);
    }

    public function test_can_get_a_game_room()
    {
        $room = GameRoom::factory()->create();

        $response = $this->getJson("/api/game-rooms/{$room->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $room->id]);
    }

    public function test_can_update_a_game_room()
    {
        $room = GameRoom::factory()->create();

        $update = ['name' => 'Nombre actualizado'];

        $response = $this->putJson("/api/game-rooms/{$room->id}", $update);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Nombre actualizado']);

        $this->assertDatabaseHas('game_rooms', ['id' => $room->id, 'name' => 'Nombre actualizado']);
    }

    public function test_can_delete_a_game_room()
    {
        $room = GameRoom::factory()->create();

        $response = $this->deleteJson("/api/game-rooms/{$room->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Game room deleted successfully']);

        $this->assertDatabaseMissing('game_rooms', ['id' => $room->id]);
    }
}
