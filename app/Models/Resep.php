<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resep extends Model
{
    protected $table = 'reseps';

    protected $fillable = [
        'user_id',
        'kategori_id',
        'nama_resep',
        'deskripsi',
        'bahan',
        'langkah'
    ];

    // 🔗 RELASI

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'recipe_ingredients');
    }

    public function komentars()
    {
        return $this->hasMany(Komentar::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}