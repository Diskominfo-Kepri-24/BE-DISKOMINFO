<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserMagangMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'user_magang_mahasiswa';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'email',
        'password',
        'nim',
        'no_telp',
        'jurusan',
        'tahun_angkatan',
        'universitas',
        'id_user_magang',
    ];

    public function userMagang(): BelongsTo {
        return $this->belongsTo(UserMagang::class, 'id_user_magang', 'id');
    }


}
