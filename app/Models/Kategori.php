<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'tabel_kategori';

    protected $fillable = [
        'nama',
        'deskripsi',
    ];

    public function keahlians()
    {
        return $this->hasMany(Keahlian::class, 'kategori_id');
    }
}
