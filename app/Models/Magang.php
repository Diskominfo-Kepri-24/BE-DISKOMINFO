<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Magang extends Model
{
    use HasFactory;

    protected $table = 'magang';

    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'jurusan',
        'no_induk',
        'jenjang',
        'instansi',
        'surat_magang',
        'mulai_magang',
        'akhir_magang',
        'alasan_magang',
        'motivasi_magang',
        'status',
        'id_user',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function absen(): HasOne {
        return $this->hasOne(Absen::class, "id_user_magang", "id");
    }
    
}
