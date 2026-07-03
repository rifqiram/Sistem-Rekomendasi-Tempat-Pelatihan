<?php

namespace Database\Factories;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'age' => fake()->numberBetween(18, 45),
            'education' => fake()->randomElement(['SMA/SMK', 'D3', 'S1', 'S2']),
            'district' => fake()->randomElement(['Magetan', 'Madiun', 'Ngawi', 'Ponorogo']),
            'phone' => fake()->phoneNumber(),
            'alamat_lengkap' => fake()->address(),
            // Koordinat area Jawa Timur (Magetan dsk)
            'latitude' => fake()->randomFloat(8, -8.0, -7.0),
            'longitude' => fake()->randomFloat(8, 111.0, 112.0),
        ];
    }
}
