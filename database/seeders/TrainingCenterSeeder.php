<?php

namespace Database\Seeders;

use App\Models\TrainingCenter;
use Illuminate\Database\Seeder;

class TrainingCenterSeeder extends Seeder
{
    public function run(): void
    {
        TrainingCenter::create([
            'nama' => 'BLK Magetan',
            'alamat' => 'Jl. Raya Magetan - Maospati Km 4',
            'latitude' => -7.6433,
            'longitude' => 111.3320,
            'telepon' => '0351-123456',
            'email' => 'info@blkmagetan.go.id',
            'website' => 'https://blkmagetan.go.id',
            'deskripsi' => 'Balai Latihan Kerja milik Pemerintah Kabupaten Magetan.',
            'status' => 'active',
        ]);

        TrainingCenter::create([
            'nama' => 'BLK Madiun',
            'alamat' => 'Jl. Salak No. 12, Madiun',
            'latitude' => -7.6298,
            'longitude' => 111.5239,
            'telepon' => '0351-654321',
            'email' => 'kontak@blkmadiun.go.id',
            'website' => 'https://blkmadiun.go.id',
            'deskripsi' => 'Balai Latihan Kerja Kota Madiun yang fokus pada industri kreatif dan digital.',
            'status' => 'active',
        ]);

        TrainingCenter::create([
            'nama' => 'LPK Sakura',
            'alamat' => 'Jl. Diponegoro No. 8, Ngawi',
            'latitude' => -7.4042,
            'longitude' => 111.4446,
            'telepon' => '0351-789012',
            'email' => 'admin@lpksakura.com',
            'website' => 'https://lpksakura.com',
            'deskripsi' => 'Lembaga Pelatihan Kerja Bahasa Jepang dan Persiapan Tokutei Ginou.',
            'status' => 'active',
        ]);
    }
}
