<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class News extends Model
{
    use HasFactory;

    protected $table = "news";

    public $timestamps = true;

    protected $fillable = [
        "id_user",
        "tanggal",
        "slug",
        "kategori",
        "judul",
        "gambar",
        "isi_berita",
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

}
