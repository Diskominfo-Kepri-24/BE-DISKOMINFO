<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

}
