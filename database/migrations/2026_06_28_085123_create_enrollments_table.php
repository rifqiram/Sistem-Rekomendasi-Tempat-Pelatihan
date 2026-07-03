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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('tabel_users')->cascadeOnDelete();
            $table->foreignId('training_center_id')->constrained('training_centers')->cascadeOnDelete();
            $table->foreignId('pelatihan_id')->constrained('tabel_pelatihan')->cascadeOnDelete();
            $table->date('tanggal_daftar')->nullable();
            $table->string('status')->default('terdaftar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
