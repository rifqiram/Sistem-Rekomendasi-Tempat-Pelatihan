<?php

namespace App\Http\Controllers;

use App\Http\Resources\PelatihanResource;
use App\Models\Pelatihan;
use App\Models\TrainingCenter;
use Illuminate\Http\Request;

class PelatihanController extends Controller
{
    public function index()
    {
        return $this->successResponse(
            PelatihanResource::collection(Pelatihan::with(['trainingCenter'])->get()),
            'Data pelatihan berhasil diambil'
        );
    }

    public function store(Request $request)
    {
        if ($response = $this->authorizeAdmin($request)) {
            return $response;
        }

        if (TrainingCenter::count() === 0) {
            return $this->errorResponse('Silakan tambahkan Tempat Pelatihan terlebih dahulu.', 400);
        }

        $data = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'interest_category' => 'nullable|string',
            'method' => 'nullable|string',
            'required_skill' => 'nullable|string',
            'popularity' => 'nullable|integer',
            'kategori' => 'nullable|string',
            'level' => 'nullable|string',
            'durasi' => 'nullable|string',
            'sertifikat' => 'nullable|string',
            'training_center_id' => 'required|exists:training_centers,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $data['is_active'] ?? true;
        $pelatihan = Pelatihan::create($data);

        return $this->successResponse(new PelatihanResource($pelatihan->load(['trainingCenter'])), 'Pelatihan berhasil dibuat', 201);
    }

    public function show(Pelatihan $pelatihan)
    {
        return $this->successResponse(
            new PelatihanResource($pelatihan->load(['trainingCenter'])),
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
            'interest_category' => 'nullable|string',
            'method' => 'nullable|string',
            'required_skill' => 'nullable|string',
            'popularity' => 'nullable|integer',
            'kategori' => 'nullable|string',
            'level' => 'nullable|string',
            'durasi' => 'nullable|string',
            'sertifikat' => 'nullable|string',
            'training_center_id' => 'sometimes|required|exists:training_centers,id',
            'tanggal_mulai' => 'sometimes|required|date',
            'tanggal_selesai' => 'sometimes|required|date|after_or_equal:tanggal_mulai',
            'status' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $pelatihan->update($data);

        return $this->successResponse(new PelatihanResource($pelatihan->load(['trainingCenter'])), 'Pelatihan berhasil diperbarui');
    }

    public function destroy(Request $request, Pelatihan $pelatihan)
    {
        if ($response = $this->authorizeAdmin($request)) {
            return $response;
        }

        // Use modern enrollments table check
        if ($pelatihan->enrollments()->exists()) {
            return $this->errorResponse('Masih ada peserta terdaftar', 400);
        }

        $pelatihan->delete();

        return $this->successResponse(null, 'Pelatihan berhasil dihapus');
    }
}