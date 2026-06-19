<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peserta extends Model
{
    protected $table = 'tabel_peserta';

    protected $fillable = [
        'nama',
        'email',
        'telepon',
        'keahlian',
    ];

    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class, 'peserta_id');
    }

    public function pelatihans()
    {
        return $this->belongsToMany(Pelatihan::class, 'tabel_pendaftaran', 'peserta_id', 'pelatihan_id')
            ->withTimestamps()
            ->withPivot(['status', 'tanggal_daftar']);
    }

    public function keahlians()
    {
        return $this->belongsToMany(Keahlian::class, 'tabel_peserta_keahlian', 'peserta_id', 'keahlian_id')
            ->withPivot('level')
            ->withTimestamps();
    }
}
