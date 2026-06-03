<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tabel_peserta', function (Blueprint $table) {
            if (Schema::hasColumn('tabel_peserta', 'instansi')) {
                // MySQL: renameColumn supported from Laravel 9+
                $table->renameColumn('instansi', 'keahlian');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tabel_peserta', function (Blueprint $table) {
            if (Schema::hasColumn('tabel_peserta', 'keahlian')) {
                $table->renameColumn('keahlian', 'instansi');
            }
        });
    }
};


