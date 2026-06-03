<?php

namespace Tests\Feature;

use App\Models\Mentor;
use App\Models\Peserta;
use App\Models\Pelatihan;
use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteConstraintTest extends TestCase
{
    use RefreshDatabase;

    private function authHeaders(string $token): array
    {
        return ['Authorization' => 'Bearer ' . $token];
    }

    public function test_admin_cannot_delete_pelatihan_with_existing_pendaftaran(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'admin',
            'api_token' => 'admintoken',
        ]);

        $mentor = Mentor::create([
            'nama' => 'Mentor Satu',
            'email' => 'mentor@example.com',
            'telepon' => '08123456789',
            'keahlian' => 'Teknologi',
        ]);

        $pelatihan = Pelatihan::create([
            'judul' => 'Pelatihan Laravel',
            'deskripsi' => 'Belajar Laravel',
            'mentor_id' => $mentor->id,
            'tanggal_mulai' => '2026-06-01',
            'tanggal_selesai' => '2026-06-05',
            'is_active' => true,
        ]);

        $peserta = Peserta::create([
            'nama' => 'Peserta Satu',
            'email' => 'peserta@example.com',
            'telepon' => '08123456791',
            'keahlian' => 'Web Developer',
        ]);

        Pendaftaran::create([
            'peserta_id' => $peserta->id,
            'pelatihan_id' => $pelatihan->id,
            'tanggal_daftar' => '2026-06-10',
            'status' => 'terdaftar',
        ]);

        $this->withHeaders($this->authHeaders($user->api_token))
            ->deleteJson("/api/pelatihan/{$pelatihan->id}")
            ->assertStatus(400)
            ->assertJson(['message' => 'Masih Ada Peserta!']);
    }

    public function test_admin_cannot_delete_peserta_with_existing_pendaftaran(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin2@example.com',
            'password' => 'password',
            'role' => 'admin',
            'api_token' => 'admintoken2',
        ]);

        $mentor = Mentor::create([
            'nama' => 'Mentor Dua',
            'email' => 'mentor2@example.com',
            'telepon' => '08123456790',
            'keahlian' => 'Data Science',
        ]);

        $pelatihan = Pelatihan::create([
            'judul' => 'Pelatihan Data',
            'deskripsi' => 'Belajar Data Science',
            'mentor_id' => $mentor->id,
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => '2026-07-05',
            'is_active' => true,
        ]);

        $peserta = Peserta::create([
            'nama' => 'Peserta Dua',
            'email' => 'peserta2@example.com',
            'telepon' => '08123456792',
            'keahlian' => 'Data Analyst',
        ]);

        Pendaftaran::create([
            'peserta_id' => $peserta->id,
            'pelatihan_id' => $pelatihan->id,
            'tanggal_daftar' => '2026-06-11',
            'status' => 'terdaftar',
        ]);

        $this->withHeaders($this->authHeaders($user->api_token))
            ->deleteJson("/api/peserta/{$peserta->id}")
            ->assertStatus(400)
            ->assertJson(['message' => 'Masih Ada Peserta!']);
    }

    public function test_admin_cannot_delete_mentor_with_existing_pelatihan(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin3@example.com',
            'password' => 'password',
            'role' => 'admin',
            'api_token' => 'admintoken3',
        ]);

        $mentor = Mentor::create([
            'nama' => 'Mentor Tiga',
            'email' => 'mentor3@example.com',
            'telepon' => '08123456793',
            'keahlian' => 'UI/UX',
        ]);

        Pelatihan::create([
            'judul' => 'Pelatihan UI',
            'deskripsi' => 'Belajar desain UI',
            'mentor_id' => $mentor->id,
            'tanggal_mulai' => '2026-08-01',
            'tanggal_selesai' => '2026-08-05',
            'is_active' => true,
        ]);

        $this->withHeaders($this->authHeaders($user->api_token))
            ->deleteJson("/api/mentor/{$mentor->id}")
            ->assertStatus(400)
            ->assertJson(['message' => 'Masih Ada Kelas!']);
    }

    public function test_admin_can_delete_pendaftaran_directly(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin4@example.com',
            'password' => 'password',
            'role' => 'admin',
            'api_token' => 'admintoken4',
        ]);

        $mentor = Mentor::create([
            'nama' => 'Mentor Empat',
            'email' => 'mentor4@example.com',
            'telepon' => '08123456794',
            'keahlian' => 'DevOps',
        ]);

        $pelatihan = Pelatihan::create([
            'judul' => 'Pelatihan DevOps',
            'deskripsi' => 'Belajar DevOps',
            'mentor_id' => $mentor->id,
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-09-05',
            'is_active' => true,
        ]);

        $peserta = Peserta::create([
            'nama' => 'Peserta Empat',
            'email' => 'peserta4@example.com',
            'telepon' => '08123456794',
            'keahlian' => 'Cloud',
        ]);

        $pendaftaran = Pendaftaran::create([
            'peserta_id' => $peserta->id,
            'pelatihan_id' => $pelatihan->id,
            'tanggal_daftar' => '2026-09-02',
            'status' => 'terdaftar',
        ]);

        $this->withHeaders($this->authHeaders($user->api_token))
            ->deleteJson("/api/pendaftaran/{$pendaftaran->id}")
            ->assertNoContent();
    }
}
