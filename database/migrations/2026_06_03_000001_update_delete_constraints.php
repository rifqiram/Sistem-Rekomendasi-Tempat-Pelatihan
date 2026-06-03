<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tabel_pendaftaran', function (Blueprint $table) {
            $table->dropForeign(['peserta_id']);
            $table->dropForeign(['pelatihan_id']);

            $table->foreign('peserta_id')
                ->references('id')
                ->on('tabel_peserta')
                ->restrictOnDelete();

            $table->foreign('pelatihan_id')
                ->references('id')
                ->on('tabel_pelatihan')
                ->restrictOnDelete();
        });

        Schema::table('tabel_pelatihan', function (Blueprint $table) {
            $table->dropForeign(['mentor_id']);
            $table->foreign('mentor_id')
                ->references('id')
                ->on('tabel_mentor')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tabel_pendaftaran', function (Blueprint $table) {
            $table->dropForeign(['peserta_id']);
            $table->dropForeign(['pelatihan_id']);

            $table->foreign('peserta_id')
                ->references('id')
                ->on('tabel_peserta')
                ->cascadeOnDelete();

            $table->foreign('pelatihan_id')
                ->references('id')
                ->on('tabel_pelatihan')
                ->cascadeOnDelete();
        });

        Schema::table('tabel_pelatihan', function (Blueprint $table) {
            $table->dropForeign(['mentor_id']);
            $table->foreign('mentor_id')
                ->references('id')
                ->on('tabel_mentor')
                ->nullOnDelete();
        });
    }
};
