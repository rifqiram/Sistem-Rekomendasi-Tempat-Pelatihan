<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelatihan extends Model
{
    protected $table = 'tabel_pelatihan';

    protected $fillable = [
        'judul',
        'deskripsi',
        'interest_category',
        'method',
        'location',
        'required_skill',
        'priority',
        'popularity',
        'kategori',
        'level',
        'durasi',
        'sertifikat',
        'training_center_id',
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

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class, 'training_id');
    }

    public function trainingCenter()
    {
        return $this->belongsTo(TrainingCenter::class, 'training_center_id');
    }
}
