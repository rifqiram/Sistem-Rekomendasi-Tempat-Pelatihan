<?php

namespace App\Http\Controllers;

use App\Http\Resources\KategoriResource;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        return $this->successResponse(KategoriResource::collection(Kategori::latest()->get()), 'Data kategori berhasil diambil');
    }

    public function store(Request $request)
    {
        if ($response = $this->authorizeAdmin($request)) {
            return $response;
        }

        $data = $request->validate([
            'nama' => 'required|string|max:255|unique:tabel_kategori,nama',
            'deskripsi' => 'nullable|string',
        ]);

        $kategori = Kategori::create($data);

        return $this->successResponse(new KategoriResource($kategori), 'Kategori berhasil dibuat', 201);
    }

    public function show(Kategori $kategori)
    {
        return $this->successResponse(new KategoriResource($kategori), 'Detail kategori berhasil diambil');
    }

    public function update(Request $request, Kategori $kategori)
    {
        if ($response = $this->authorizeAdmin($request)) {
            return $response;
        }

        $data = $request->validate([
            'nama' => 'sometimes|required|string|max:255|unique:tabel_kategori,nama,' . $kategori->id,
            'deskripsi' => 'nullable|string',
        ]);

        $kategori->update($data);

        return $this->successResponse(new KategoriResource($kategori), 'Kategori berhasil diperbarui');
    }

    public function destroy(Request $request, Kategori $kategori)
    {
        if ($response = $this->authorizeAdmin($request)) {
            return $response;
        }

        if ($kategori->keahlians()->exists()) {
            return $this->errorResponse('Kategori masih memiliki keahlian', 400);
        }

        $kategori->delete();

        return $this->successResponse(null, 'Kategori berhasil dihapus');
    }
}
