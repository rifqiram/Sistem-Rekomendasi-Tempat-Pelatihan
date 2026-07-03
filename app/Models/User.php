<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable(['name', 'email', 'password', 'api_token', 'role', 'is_active'])]
#[Hidden(['password', 'remember_token', 'api_token'])]
class User extends Authenticatable
{
    protected $table = 'tabel_users';

    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // ==========================================
    // Relasi Entity Baru (Rule-Based Scoring)
    // ==========================================

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function questionnaireResponse()
    {
        return $this->hasOne(QuestionnaireResponse::class);
    }

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class);
    }

    public function logActivities()
    {
        return $this->hasMany(LogActivity::class);
    }
}
