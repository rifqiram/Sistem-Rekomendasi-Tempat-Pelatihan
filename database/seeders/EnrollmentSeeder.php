<?php

namespace Database\Seeders;

use App\Models\Enrollment;
use App\Models\Pelatihan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan kita memiliki users (selain admin dan tester)
        // Kita buat 10 dummy users untuk pendaftar
        $users = User::factory()->count(10)->create();

        $pelatihans = Pelatihan::with('trainingCenter')->get();

        if ($pelatihans->isEmpty()) {
            return;
        }

        $statuses = ['pending', 'approved', 'rejected', 'terdaftar'];

        // Buat setidaknya 20 enrollment secara random
        for ($i = 0; $i < 20; $i++) {
            $user = $users->random();
            $pelatihan = $pelatihans->random();

            // Random date in the last 30 days
            $tanggalDaftar = Carbon::now()->subDays(rand(1, 30));

            Enrollment::create([
                'user_id' => $user->id,
                'training_center_id' => $pelatihan->training_center_id,
                'pelatihan_id' => $pelatihan->id,
                'tanggal_daftar' => $tanggalDaftar,
                'status' => $statuses[array_rand($statuses)],
            ]);
        }
    }
}
