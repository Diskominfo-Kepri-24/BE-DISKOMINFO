<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $table = "programs";

    protected $fillable = [
        "title",
        "description",
        "slug",
        "jadwal",
        "tipe_program",
        "link_pendaftaran",
        "category",
        "jam_program_dimulai",
        "deskripsi_sertifikat",
        "tipe_pembelajaran",
        "tipe_mentoring",
        "tipe_modul",
        "image"
    ];
}
