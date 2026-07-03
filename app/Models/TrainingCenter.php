<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingCenter extends Model
{
    /** @use HasFactory<\Database\Factories\TrainingCenterFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function pelatihans()
    {
        return $this->hasMany(Pelatihan::class, 'training_center_id');
    }

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class, 'training_center_id');
    }
}
