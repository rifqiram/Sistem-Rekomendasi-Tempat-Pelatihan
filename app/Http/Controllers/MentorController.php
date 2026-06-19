<?php

namespace App\Http\Controllers;

use App\Http\Resources\MentorResource;
use App\Models\Mentor;
use Illuminate\Http\Request;

class MentorController extends Controller
{
    public function index(Request $request)
    {
        if ($response = $this->authorizeAdmin($request)) {
            return $response;
        }

        return $this->successResponse(MentorResource::collection(Mentor::all()), 'Data mentor berhasil diambil');
    }

    public function store(Request $request)
    {
        if ($response = $this->authorizeAdmin($request)) {
            return $response;
        }

        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:tabel_mentor,email',
            'telepon' => 'nullable|string|max:50',
            'keahlian' => 'nullable|string|max:255',
        ]);

        $mentor = Mentor::create($data);

        return $this->successResponse(new MentorResource($mentor), 'Mentor berhasil dibuat', 201);
    }

    public function show(Request $request, Mentor $mentor)
    {
        if ($response = $this->authorizeAdmin($request)) {
            return $response;
        }

        return $this->successResponse(new MentorResource($mentor), 'Detail mentor berhasil diambil');
    }

    public function update(Request $request, Mentor $mentor)
    {
        if ($response = $this->authorizeAdmin($request)) {
            return $response;
        }

        $data = $request->validate([
            'nama' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:tabel_mentor,email,' . $mentor->id,
            'telepon' => 'nullable|string|max:50',
            'keahlian' => 'nullable|string|max:255',
        ]);

        $mentor->update($data);

        return $this->successResponse(new MentorResource($mentor), 'Mentor berhasil diperbarui');
    }

    public function destroy(Request $request, Mentor $mentor)
    {
        if ($response = $this->authorizeAdmin($request)) {
            return $response;
        }

        if ($mentor->pelatihans()->exists()) {
            return $this->errorResponse('Masih ada kelas untuk mentor ini', 400);
        }

        $mentor->delete();

        return $this->successResponse(null, 'Mentor berhasil dihapus');
    }
}
