<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
