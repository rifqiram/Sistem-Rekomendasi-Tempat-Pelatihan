<?php

namespace App\Http\Controllers;

use App\Http\Resources\PesertaResource;
use App\Http\Resources\PendaftaranResource;
use App\Models\Peserta;
use Illuminate\Http\Request;

class PesertaController extends Controller
{
    public function index(Request $request)
    {
        if ($response = $this->authorizeAdmin($request)) {
            return $response;
        }

        return $this->successResponse(PesertaResource::collection(Peserta::all()), 'Data peserta berhasil diambil');
    }

    public function store(Request $request)
    {
        if ($response = $this->authorizeAdmin($request)) {
            return $response;
        }

        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:tabel_peserta,email',
            'telepon' => 'nullable|string|max:50',
            'keahlian' => 'nullable|string|max:255',
        ]);

        $peserta = Peserta::create($data);

        return $this->successResponse(new PesertaResource($peserta), 'Peserta berhasil dibuat', 201);
    }

    public function show(Peserta $peserta)
    {
        return $this->successResponse(new PesertaResource($peserta), 'Detail peserta berhasil diambil');
    }

    public function update(Request $request, Peserta $peserta)
    {
        $data = $request->validate([
            'nama' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:tabel_peserta,email,' . $peserta->id,
            'telepon' => 'nullable|string|max:50',
            'keahlian' => 'nullable|string|max:255',
        ]);

        $peserta->update($data);

        return $this->successResponse(new PesertaResource($peserta), 'Peserta berhasil diperbarui');
    }

    public function destroy(Request $request, Peserta $peserta)
    {
        if ($response = $this->authorizeAdmin($request)) {
            return $response;
        }

        if ($peserta->pendaftarans()->exists()) {
            return $this->errorResponse('Masih ada pendaftaran peserta', 400);
        }

        $peserta->delete();

        return $this->successResponse(null, 'Peserta berhasil dihapus');
    }

    public function riwayat(Peserta $peserta)
    {
        return $this->successResponse(
            PendaftaranResource::collection($peserta->pendaftarans()->with('pelatihan.mentor')->get()),
            'Riwayat pelatihan berhasil diambil'
        );
    }
}
