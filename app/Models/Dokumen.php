<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    use HasFactory;

    protected $table = 'dokumen';

    public $timestamps = true;

    protected $fillable = [
        'cv',
        'transkip_nilai',
        'id_user_magang',
        'surat_rekomendasi',
        'ktp',
        'sertifikat',
        'dokumen_tambahan',
    ];

}
