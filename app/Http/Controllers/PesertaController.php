<?php

namespace App\Http\Controllers;

use App\Http\Resources\PesertaResource;
use App\Http\Resources\PendaftaranResource;
use App\Models\Peserta;
use Illuminate\Http\Request;

class PesertaController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($response = $this->authorizeAdmin($request)) {
                return $response;
            }

            return $next($request);
        });
    }

    public function index()
    {
        return PesertaResource::collection(Peserta::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:tabel_peserta,email',
            'telepon' => 'nullable|string|max:50',
            'keahlian' => 'nullable|string|max:255',
        ]);


        $peserta = Peserta::create($data);

        return new PesertaResource($peserta);
    }

    public function show(Peserta $peserta)
    {
        return new PesertaResource($peserta);
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

        return new PesertaResource($peserta);
    }

    public function destroy(Peserta $peserta)
    {
        if ($peserta->pendaftarans()->exists()) {
            return response()->json(['message' => 'Masih Ada Peserta!'], 400);
        }

        $peserta->delete();

        return response()->noContent();
    }

    public function riwayat(Peserta $peserta)
    {
        return PendaftaranResource::collection($peserta->pendaftarans()->with('pelatihan.mentor')->get());
    }
}
