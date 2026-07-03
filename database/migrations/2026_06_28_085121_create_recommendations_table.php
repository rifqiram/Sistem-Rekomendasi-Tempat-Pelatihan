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
        Schema::create('recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('tabel_users')->cascadeOnDelete();
            $table->foreignId('training_center_id')->nullable()->constrained('training_centers')->cascadeOnDelete();
            $table->decimal('score', 8, 2)->default(0);
            $table->decimal('distance', 8, 2)->nullable();
            $table->integer('rank')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommendations');
    }
};
