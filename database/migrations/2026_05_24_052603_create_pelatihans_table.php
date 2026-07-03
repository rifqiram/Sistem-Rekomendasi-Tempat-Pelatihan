<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tabel_pelatihan', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            
            // Kolom tambahan hasil refactor
            $table->string('kategori')->nullable();
            $table->string('level')->nullable();
            $table->string('durasi')->nullable();
            $table->string('sertifikat')->nullable();
            $table->string('interest_category')->nullable();
            $table->string('method')->nullable();
            $table->string('location')->nullable();
            $table->string('required_skill')->nullable();
            $table->integer('priority')->default(1);
            $table->integer('popularity')->default(0);
            
            // Relasi
            $table->foreignId('training_center_id')->nullable()->constrained('training_centers')->onDelete('cascade');
            
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->boolean('is_active')->default(true);
            $table->string('status')->default('Aktif');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tabel_pelatihan');
    }
};
