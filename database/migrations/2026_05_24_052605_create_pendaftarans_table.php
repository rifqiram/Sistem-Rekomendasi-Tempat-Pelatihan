<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tabel_pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_id')->constrained('tabel_peserta')->cascadeOnDelete();
            $table->foreignId('pelatihan_id')->constrained('tabel_pelatihan')->cascadeOnDelete();
            $table->date('tanggal_daftar');
            $table->string('status')->default('terdaftar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabel_pendaftaran');
    }
};
