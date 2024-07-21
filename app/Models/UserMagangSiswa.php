<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

}
