<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $table = "comments";

    public $timestamps = true;


    protected $fillable = [
        "id_berita",
        "id_user",
        "tanggal",
        "isi_komentar",
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, "id_user", "id");
    }

    public function news(): BelongsTo {
        return $this->belongsTo(News::class, "id_berita", "id");
    }

    public function getCommentWithRelation($slug, $id_user, $id_comment){
    }

}
