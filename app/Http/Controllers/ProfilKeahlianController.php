<?php

namespace App\Http\Controllers;

use App\Http\Resources\KeahlianResource;
use App\Models\Peserta;
use Illuminate\Http\Request;

class ProfilKeahlianController extends Controller
{
    public function show(Peserta $peserta)
    {
        return $this->successResponse(
            KeahlianResource::collection($peserta->keahlians()->with('kategori')->get()),
            'Profil keahlian peserta berhasil diambil'
        );
    }

    public function update(Request $request, Peserta $peserta)
    {
        $data = $request->validate([
            'keahlian' => 'required|array',
            'keahlian.*.id' => 'required|exists:tabel_keahlian,id',
            'keahlian.*.level' => 'nullable|string|max:50',
        ]);

        $sync = collect($data['keahlian'])->mapWithKeys(function ($item) {
            return [$item['id'] => ['level' => $item['level'] ?? null]];
        })->all();

        $peserta->keahlians()->sync($sync);

        return $this->successResponse(
            KeahlianResource::collection($peserta->keahlians()->with('kategori')->get()),
            'Profil keahlian peserta berhasil diperbarui'
        );
    }
}
