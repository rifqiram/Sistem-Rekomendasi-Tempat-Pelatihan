<?php

namespace Database\Seeders;

use App\Models\LogActivity;
use App\Models\Pelatihan;
use App\Models\User;
use Illuminate\Database\Seeder;

class LogActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $pelatihans = Pelatihan::with('trainingCenter')->get();

        if ($users->isEmpty() || $pelatihans->isEmpty()) {
            return;
        }

        $activityTypes = [
            'login',
            'logout',
            'update_profile',
            'submit_questionnaire',
            'view_training_center',
            'view_training',
            'generate_recommendation',
            'enroll_training'
        ];

        // Buat setidaknya 50 aktivitas secara random
        for ($i = 0; $i < 50; $i++) {
            $user = $users->random();
            $activityType = $activityTypes[array_rand($activityTypes)];

            $trainingCenterId = null;
            $pelatihanId = null;
            $details = null;

            // Jika activity terkait dengan pelatihan/TC
            if (in_array($activityType, ['view_training_center', 'view_training', 'enroll_training'])) {
                $pelatihan = $pelatihans->random();
                $trainingCenterId = $pelatihan->training_center_id;
                $pelatihanId = $pelatihan->id;
            }

            // Tambahkan keterangan details untuk beberapa activity
            if ($activityType === 'update_profile') {
                $details = 'User updated their profile information';
            } elseif ($activityType === 'login') {
                $details = 'User logged in successfully';
            }

            LogActivity::create([
                'user_id' => $user->id,
                'activity_type' => $activityType,
                'training_center_id' => $trainingCenterId,
                'pelatihan_id' => $pelatihanId,
                'details' => $details,
            ]);
        }
    }
}
