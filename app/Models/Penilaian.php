<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    use HasFactory;

    protected $table = 'penilaians';

    protected $fillable = [
        'user_id',
        'nilai',
    ];

    // Jika Anda ingin menambahkan relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}