<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserMagang extends Model
{
    use HasFactory;

    protected $table = 'user_magang';

    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'no_telp',
        'mulai_magang',
        'akhir_magang',
        'id_user',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function userMagangMahasiswa(): HasOne {
        return $this->hasOne(UserMagangMahasiswa::class, 'id_user_magang', 'id');
    }

    public function userMagangSiswa(): HasOne {
        return $this->hasOne(UserMagangSiswa::class, 'id_user_magang', 'id');
    }

    public function absen(): HasOne {
        return $this->hasOne(Absen::class, "id_user_magang", "id");
    }
    
}
