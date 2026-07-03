<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\LogActivityController;
use App\Http\Controllers\TrainingCenterController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Admin\UserController;

// Deprecated Controllers - Hapus setelah testing frontend baru selesai
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KeahlianController;
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\ProfilKeahlianController;
use App\Http\Controllers\RekomendasiController;

use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth.token')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // ==========================================
    // V2 API (NEW RULE-BASED SCORING)
    // ==========================================
    // Profile
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile', [ProfileController::class, 'store']);

    // Questionnaire
    Route::get('/questionnaire', [QuestionnaireController::class, 'show']);
    Route::post('/questionnaire', [QuestionnaireController::class, 'store']);

    // Training
    Route::get('/trainings', [TrainingController::class, 'index']);

    // Training Center
    Route::apiResource('/training-centers', TrainingCenterController::class);

    // Recommendation (Hanya baca hasil dari tabel Recommendation)
    Route::get('/recommendations', [RecommendationController::class, 'index']);

    // Enrollments User V2
    Route::get('/enrollments', [\App\Http\Controllers\EnrollmentController::class, 'index']);
    Route::post('/enrollments', [\App\Http\Controllers\EnrollmentController::class, 'store']);

    // Log Activity
    Route::get('/admin/log-activities', [LogActivityController::class, 'index']);
    Route::post('/log-activity', [LogActivityController::class, 'store']);

    // Admin Enrollments
    Route::get('/admin/enrollments', [EnrollmentController::class, 'index']);

    // Admin Users
    Route::get('/admin/users', [UserController::class, 'index']);
    Route::get('/admin/users/{id}', [UserController::class, 'show']);
    Route::patch('/admin/users/{id}/status', [UserController::class, 'updateStatus']);

    // Admin Stats
    Route::get('/admin/stats', [\App\Http\Controllers\Admin\StatsController::class, 'index']);

    // ==========================================
    // DEPRECATED API (SIREKPEL LAMA) - DO NOT USE
    // Akan dihapus setelah testing frontend baru selesai
    // ==========================================
    Route::apiResource('kategori', KategoriController::class);
    Route::apiResource('keahlian', KeahlianController::class);
    Route::apiResource('pelatihan', PelatihanController::class);
    Route::apiResource('peserta', PesertaController::class)->parameters([
        'peserta' => 'peserta',
    ]);
    Route::apiResource('pendaftaran', PendaftaranController::class);

    Route::get('peserta/{peserta}/keahlian', [ProfilKeahlianController::class, 'show']);
    Route::put('peserta/{peserta}/keahlian', [ProfilKeahlianController::class, 'update']);
    Route::post('pelatihan/{pelatihan}/pendaftaran', [PelatihanController::class, 'pendaftaran']);
    Route::get('peserta/{peserta}/riwayat', [PesertaController::class, 'riwayat']);
    Route::get('rekomendasi', [RekomendasiController::class, 'index']);
});
