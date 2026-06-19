<?php

namespace App\Http\Controllers;

use App\Http\Resources\PelatihanResource;
use App\Http\Resources\PendaftaranResource;
use App\Models\Pelatihan;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class PelatihanController extends Controller
{
    public function index()
    {
        return $this->successResponse(
            PelatihanResource::collection(Pelatihan::with(['mentor', 'keahlians.kategori'])->get()),
            'Data pelatihan berhasil diambil'
        );
    }

    public function store(Request $request)
    {
        if ($response = $this->authorizeAdmin($request)) {
            return $response;
        }

        $data = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori' => 'nullable|string',
            'level' => 'nullable|string',
            'durasi' => 'nullable|string',
            'sertifikat' => 'nullable|string',
            'mentor_id' => 'nullable|exists:tabel_mentor,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'nullable|string',
            'is_active' => 'boolean',
            'keahlian_ids' => 'nullable|array',
            'keahlian_ids.*' => 'exists:tabel_keahlian,id',
        ]);

        $keahlianIds = $data['keahlian_ids'] ?? [];
        unset($data['keahlian_ids']);

        $data['is_active'] = $data['is_active'] ?? true;
        $pelatihan = Pelatihan::create($data);
        $pelatihan->keahlians()->sync($keahlianIds);

        return $this->successResponse(new PelatihanResource($pelatihan->load(['mentor', 'keahlians.kategori'])), 'Pelatihan berhasil dibuat', 201);
    }

    public function show(Pelatihan $pelatihan)
    {
        return $this->successResponse(
            new PelatihanResource($pelatihan->load(['mentor', 'keahlians.kategori', 'pendaftarans.peserta'])),
            'Detail pelatihan berhasil diambil'
        );
    }

    public function update(Request $request, Pelatihan $pelatihan)
    {
        if ($response = $this->authorizeAdmin($request)) {
            return $response;
        }

        $data = $request->validate([
            'judul' => 'sometimes|required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori' => 'nullable|string',
            'level' => 'nullable|string',
            'durasi' => 'nullable|string',
            'sertifikat' => 'nullable|string',
            'mentor_id' => 'nullable|exists:tabel_mentor,id',
            'tanggal_mulai' => 'sometimes|required|date',
            'tanggal_selesai' => 'sometimes|required|date|after_or_equal:tanggal_mulai',
            'status' => 'nullable|string',
            'is_active' => 'boolean',
            'keahlian_ids' => 'nullable|array',
            'keahlian_ids.*' => 'exists:tabel_keahlian,id',
        ]);

        $keahlianIds = $data['keahlian_ids'] ?? null;
        unset($data['keahlian_ids']);

        $pelatihan->update($data);

        if ($keahlianIds !== null) {
            $pelatihan->keahlians()->sync($keahlianIds);
        }

        return $this->successResponse(new PelatihanResource($pelatihan->load(['mentor', 'keahlians.kategori'])), 'Pelatihan berhasil diperbarui');
    }

    public function destroy(Request $request, Pelatihan $pelatihan)
    {
        if ($response = $this->authorizeAdmin($request)) {
            return $response;
        }

        if ($pelatihan->pendaftarans()->exists()) {
            return $this->errorResponse('Masih ada peserta terdaftar', 400);
        }

        $pelatihan->delete();

        return $this->successResponse(null, 'Pelatihan berhasil dihapus');
    }

    public function pendaftaran(Request $request, Pelatihan $pelatihan)
    {
        $data = $request->validate([
            'peserta_id' => 'required|exists:tabel_peserta,id',
        ]);

        $exists = Pendaftaran::where('pelatihan_id', $pelatihan->id)
            ->where('peserta_id', $data['peserta_id'])
            ->exists();

        if ($exists) {
            return $this->errorResponse('Peserta sudah terdaftar pada pelatihan ini', 409);
        }

        $pendaftaran = Pendaftaran::create([
            'pelatihan_id' => $pelatihan->id,
            'peserta_id' => $data['peserta_id'],
            'tanggal_daftar' => now(),
            'status' => 'terdaftar',
        ]);

        return $this->successResponse(new PendaftaranResource($pendaftaran->load('peserta', 'pelatihan.mentor')), 'Pendaftaran berhasil dibuat', 201);
    }
}
