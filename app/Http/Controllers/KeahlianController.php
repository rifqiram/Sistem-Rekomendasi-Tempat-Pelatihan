<?php

namespace App\Http\Controllers;

use App\Http\Resources\KeahlianResource;
use App\Models\Keahlian;
use Illuminate\Http\Request;

class KeahlianController extends Controller
{
    public function index()
    {
        return $this->successResponse(KeahlianResource::collection(Keahlian::with('kategori')->latest()->get()), 'Data keahlian berhasil diambil');
    }

    public function store(Request $request)
    {
        if ($response = $this->authorizeAdmin($request)) {
            return $response;
        }

        $data = $request->validate([
            'kategori_id' => 'nullable|exists:tabel_kategori,id',
            'nama' => 'required|string|max:255|unique:tabel_keahlian,nama',
            'deskripsi' => 'nullable|string',
        ]);

        $keahlian = Keahlian::create($data);

        return $this->successResponse(new KeahlianResource($keahlian->load('kategori')), 'Keahlian berhasil dibuat', 201);
    }

    public function show(Keahlian $keahlian)
    {
        return $this->successResponse(new KeahlianResource($keahlian->load('kategori')), 'Detail keahlian berhasil diambil');
    }

    public function update(Request $request, Keahlian $keahlian)
    {
        if ($response = $this->authorizeAdmin($request)) {
            return $response;
        }

        $data = $request->validate([
            'kategori_id' => 'nullable|exists:tabel_kategori,id',
            'nama' => 'sometimes|required|string|max:255|unique:tabel_keahlian,nama,' . $keahlian->id,
            'deskripsi' => 'nullable|string',
        ]);

        $keahlian->update($data);

        return $this->successResponse(new KeahlianResource($keahlian->load('kategori')), 'Keahlian berhasil diperbarui');
    }

    public function destroy(Request $request, Keahlian $keahlian)
    {
        if ($response = $this->authorizeAdmin($request)) {
            return $response;
        }

        if ($keahlian->pelatihans()->exists() || $keahlian->pesertas()->exists()) {
            return $this->errorResponse('Keahlian masih digunakan', 400);
        }

        $keahlian->delete();

        return $this->successResponse(null, 'Keahlian berhasil dihapus');
    }
}
