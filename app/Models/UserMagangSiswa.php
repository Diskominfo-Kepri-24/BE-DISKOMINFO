<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserMagangSiswa extends Model
{
    use HasFactory;

    protected $table = 'user_magang_siswa';

    public $timestamps = true;

    protected $fillable = [
        'sekolah',
        'jurusan',
        'nisn',
        'tahun_angkatan',
        'id_user_magang',
    ];

    public function userMagang(): BelongsTo {
        return $this->belongsTo(UserMagang::class, 'id_user_magang', 'id');
    }

}
