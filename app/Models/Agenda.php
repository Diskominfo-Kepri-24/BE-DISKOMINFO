<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;

    protected $table = "agendas";

    public $timestamps = true;

    protected $fillable = [
        "judul",
        "slug",
        "status",
        "tanggal_mulai",
        "tanggal_selesai",
        "tipe_acara",
        "isi_agenda",
        "foto",
    ];

}
