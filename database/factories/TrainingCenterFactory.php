<?php

namespace Database\Factories;

use App\Models\TrainingCenter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TrainingCenter>
 */
class TrainingCenterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $lembagaPrefixes = ['BLK', 'Lembaga Pelatihan', 'Pusat Diklat', 'LKP', 'LPK'];
        $cities = ['Magetan', 'Madiun', 'Ngawi', 'Ponorogo', 'Pacitan', 'Nganjuk'];

        return [
            'nama' => fake()->randomElement($lembagaPrefixes) . ' ' . fake()->company() . ' ' . fake()->randomElement($cities),
            'alamat' => fake()->address(),
            // Koordinat area Jawa Timur (Magetan dsk)
            // Latitude: ~ -7.0 to -8.0
            // Longitude: ~ 111.0 to 112.0
            'latitude' => fake()->randomFloat(8, -8.0, -7.0),
            'longitude' => fake()->randomFloat(8, 111.0, 112.0),
            'telepon' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'website' => fake()->domainName(),
            'deskripsi' => fake()->paragraph(),
            'status' => 'active',
        ];
    }
}
