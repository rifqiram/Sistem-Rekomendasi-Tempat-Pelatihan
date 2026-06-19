<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelatihan extends Model
{
    protected $table = 'tabel_pelatihan';

    protected $fillable = [
        'judul',
        'deskripsi',
        'kategori',
        'level',
        'durasi',
        'sertifikat',
        'mentor_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active',
        'status',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function mentor()
    {
        return $this->belongsTo(Mentor::class, 'mentor_id');
    }

    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class, 'pelatihan_id');
    }

    public function pesertas()
    {
        return $this->belongsToMany(Peserta::class, 'tabel_pendaftaran', 'pelatihan_id', 'peserta_id')
            ->withTimestamps()
            ->withPivot(['status', 'tanggal_daftar']);
    }

    public function keahlians()
    {
        return $this->belongsToMany(Keahlian::class, 'tabel_pelatihan_keahlian', 'pelatihan_id', 'keahlian_id')
            ->withTimestamps();
    }
}
