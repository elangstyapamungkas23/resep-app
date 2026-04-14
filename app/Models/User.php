<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// 🔥 IMPORT RELASI
use App\Models\Resep;
use App\Models\Favorit;
use App\Models\Komentar;
use App\Models\Rating;
use App\Models\Riwayat;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
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