<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absen extends Model
{
    use HasFactory;

    protected $table = "absens";

    protected $fillable = [
        "id_user_magang",
        "tanggal",
        "hari",
        "jam_masuk",
        "jam_pulang",
        "status"
    ];

    public function magang(): BelongsTo {

        return $this->belongsTo(Magang::class, 'id_magang', "id");

    }

}
