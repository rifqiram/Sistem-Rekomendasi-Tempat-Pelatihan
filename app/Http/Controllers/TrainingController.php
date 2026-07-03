<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    /**
     * Tampilkan seluruh data training yang aktif (bukan rekomendasi)
     */
    public function index(Request $request)
    {
        $trainings = Pelatihan::with('mentor')
            ->where('is_active', true)
            ->get();

        return $this->successResponse($trainings, 'Data training berhasil diambil');
    }
}