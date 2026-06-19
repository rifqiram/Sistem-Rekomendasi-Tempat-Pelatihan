<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tabel_kategori', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        Schema::create('tabel_keahlian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->nullable()->constrained('tabel_kategori')->nullOnDelete();
            $table->string('nama')->unique();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        Schema::create('tabel_pelatihan_keahlian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelatihan_id')->constrained('tabel_pelatihan')->cascadeOnDelete();
            $table->foreignId('keahlian_id')->constrained('tabel_keahlian')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['pelatihan_id', 'keahlian_id']);
        });

        Schema::create('tabel_peserta_keahlian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_id')->constrained('tabel_peserta')->cascadeOnDelete();
            $table->foreignId('keahlian_id')->constrained('tabel_keahlian')->cascadeOnDelete();
            $table->string('level')->nullable();
            $table->timestamps();
            $table->unique(['peserta_id', 'keahlian_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tabel_peserta_keahlian');
        Schema::dropIfExists('tabel_pelatihan_keahlian');
        Schema::dropIfExists('tabel_keahlian');
        Schema::dropIfExists('tabel_kategori');
    }
};
