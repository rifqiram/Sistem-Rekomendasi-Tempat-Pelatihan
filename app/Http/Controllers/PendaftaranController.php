<?php

namespace App\Http\Controllers;

use App\Http\Resources\PendaftaranResource;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    public function index(Request $request)
    {
        if ($response = $this->authorizeAdmin($request)) {
            return $response;
        }

        return $this->successResponse(
            PendaftaranResource::collection(Pendaftaran::with(['peserta', 'pelatihan.mentor'])->get()),
            'Data pendaftaran berhasil diambil'
        );
    }

    public function store(Request $request)
    {
        if ($response = $this->authorizeAdmin($request)) {
            return $response;
        }

        $data = $request->validate([
            'peserta_id' => 'required|exists:tabel_peserta,id',
            'pelatihan_id' => 'required|exists:tabel_pelatihan,id',
            'tanggal_daftar' => 'nullable|date',
            'status' => 'nullable|string|max:50',
        ]);

        $exists = Pendaftaran::where('peserta_id', $data['peserta_id'])
            ->where('pelatihan_id', $data['pelatihan_id'])
            ->exists();

        if ($exists) {
            return $this->errorResponse('Peserta sudah terdaftar pada pelatihan ini', 409);
        }

        $data['tanggal_daftar'] = $data['tanggal_daftar'] ?? now();
        $data['status'] = $data['status'] ?? 'terdaftar';

        $pendaftaran = Pendaftaran::create($data);

        return $this->successResponse(new PendaftaranResource($pendaftaran->load(['peserta', 'pelatihan.mentor'])), 'Pendaftaran berhasil dibuat', 201);
    }

    public function show(Request $request, Pendaftaran $pendaftaran)
    {
        if ($response = $this->authorizeAdmin($request)) {
            return $response;
        }

        return $this->successResponse(new PendaftaranResource($pendaftaran->load(['peserta', 'pelatihan.mentor'])), 'Detail pendaftaran berhasil diambil');
    }

    public function update(Request $request, Pendaftaran $pendaftaran)
    {
        if ($response = $this->authorizeAdmin($request)) {
            return $response;
        }

        $data = $request->validate([
            'peserta_id' => 'sometimes|required|exists:tabel_peserta,id',
            'pelatihan_id' => 'sometimes|required|exists:tabel_pelatihan,id',
            'tanggal_daftar' => 'sometimes|required|date',
            'status' => 'sometimes|required|string|max:50',
        ]);

        $pendaftaran->update($data);

        return $this->successResponse(new PendaftaranResource($pendaftaran->load(['peserta', 'pelatihan.mentor'])), 'Pendaftaran berhasil diperbarui');
    }

    public function destroy(Request $request, Pendaftaran $pendaftaran)
    {
        if ($response = $this->authorizeAdmin($request)) {
            return $response;
        }

        $pendaftaran->delete();

        return $this->successResponse(null, 'Pendaftaran berhasil dihapus');
    }
}
