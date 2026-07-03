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
        Schema::create('log_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('tabel_users')->cascadeOnDelete();
            $table->string('activity_type'); // e.g., view_detail, enroll, click
            $table->foreignId('training_center_id')->nullable()->constrained('training_centers')->nullOnDelete();
            $table->foreignId('pelatihan_id')->nullable()->constrained('tabel_pelatihan')->nullOnDelete();
            $table->text('details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_activities');
    }
};
