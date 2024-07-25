<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class News extends Model
{
    use HasFactory;

    protected $table = "news";

    public $timestamps = true;

    protected $appends = ['last_updated'];

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

    public function comments(): HasMany {
        return $this->hasMany(Comment::class, "id_berita", "id");
    }

    public function getLastUpdatedAttribute(){

        return $this->updated_at->diffForHumans();

    }

}
