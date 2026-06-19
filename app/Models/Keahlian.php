<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keahlian extends Model
{
    protected $table = 'tabel_keahlian';

    protected $fillable = [
        'kategori_id',
        'nama',
        'deskripsi',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function pelatihans()
    {
        return $this->belongsToMany(Pelatihan::class, 'tabel_pelatihan_keahlian', 'keahlian_id', 'pelatihan_id')
            ->withTimestamps();
    }

    public function pesertas()
    {
        return $this->belongsToMany(Peserta::class, 'tabel_peserta_keahlian', 'keahlian_id', 'peserta_id')
            ->withPivot('level')
            ->withTimestamps();
    }
}
