<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition()
	{
		return [
			'username' => fake()->unique()->userName(),
			'name' => fake()->firstName(),
			'surname1' => fake()->lastName(),
			'surname2' => fake()->lastName(),
			'email' => fake()->unique()->safeEmail(),
			'dni' => fake()->randomNumber(9, true),
			'gender' => fake()->randomElement(['M', 'F', 'N']),
			'phone_number' => fake()->phoneNumber(),
			'birthdate' => fake()->dateTimeBetween('-50 years', '-18 years')->format('Y-m-d'),
			'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
			'remember_token' => Str::random(10),
		];
	}

	/**
	 * Indicate that the model's email address should be unverified.
	 *
	 * @return static
	 */
	public function unverified()
	{
		return $this->state(fn(array $attributes) => [
			'email_verified_at' => null,
		]);
	}

	public function configure()
	{
		return $this->afterCreating(function (\App\Models\User $user) {
			$user->pendingValidations()->create([
				'status' => 'PENDING',
				'image_url' => 'pepetron',
			]);
			$user->assignRole(fake()->randomElement([1, 2]));
		});
	}
}
