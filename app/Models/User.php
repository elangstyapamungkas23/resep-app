<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

// 🔥 RELASI
use App\Models\Resep;
use App\Models\Favorit;
use App\Models\Komentar;
use App\Models\Rating;
use App\Models\Riwayat;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // 🔗 RELASI
    public function reseps()
    {
        return $this->hasMany(Resep::class);
    }

    public function favorits()
    {
        return $this->hasMany(Favorit::class);
    }

    public function komentars()
    {
        return $this->hasMany(Komentar::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function riwayats()
    {
        return $this->hasMany(Riwayat::class);
    }
}