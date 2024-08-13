<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function mentors(): BelongsToMany
    {
        return $this->belongsToMany(Mentor::class, 'mentor_program', 'program_id', 'mentor_id');
    }

    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class, 'id_program', 'id');
    }
}
