<?php

namespace Tests\Feature;

use App\Models\Mentor;
use App\Models\Peserta;
use App\Models\Pelatihan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PendaftaranTest extends TestCase
{
    use RefreshDatabase;

    private function authHeaders(string $token): array
    {
        return ['Authorization' => 'Bearer ' . $token];
    }

    public function test_admin_can_manage_pendaftaran(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'admin',
            'api_token' => 'admintoken',
        ]);

        $mentor = Mentor::create([
            'nama' => 'Mentor Dua',
            'email' => 'mentor2@example.com',
            'telepon' => '08123456790',
            'keahlian' => 'Manajemen',
        ]);

        $pelatihan = Pelatihan::create([
            'judul' => 'Pelatihan Manajemen',
            'deskripsi' => 'Training untuk manajer',
            'mentor_id' => $mentor->id,
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => '2026-07-03',
            'is_active' => true,
        ]);

        $peserta = Peserta::create([
            'nama' => 'Peserta Satu',
            'email' => 'peserta@example.com',
            'telepon' => '08123456791',
            'keahlian' => 'PT Contoh',
        ]);

        $response = $this->withHeaders($this->authHeaders($user->api_token))
            ->postJson('/api/pendaftaran', [
                'peserta_id' => $peserta->id,
                'pelatihan_id' => $pelatihan->id,
                'tanggal_daftar' => '2026-06-20',
                'status' => 'terdaftar',
            ]);

        $response->assertCreated()
            ->assertJsonPath('data.peserta.id', $peserta->id)
            ->assertJsonPath('data.pelatihan.id', $pelatihan->id)
            ->assertJsonPath('data.status', 'terdaftar');

        $pendaftaranId = $response->json('data.id');

        $this->withHeaders($this->authHeaders($user->api_token))
            ->putJson("/api/pendaftaran/{$pendaftaranId}", [
                'status' => 'lulus',
            ])
            ->assertOk()
            ->assertJsonPath('data.status', 'lulus');

        $this->withHeaders($this->authHeaders($user->api_token))
            ->getJson("/api/peserta/{$peserta->id}/riwayat")
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.pelatihan.id', $pelatihan->id);
    }
}
