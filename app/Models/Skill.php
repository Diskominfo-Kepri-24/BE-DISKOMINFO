<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Skill extends Model
{
    use HasFactory;

    protected $table = "skills";

    public $timestamps = true;

    protected $guarded = [
        "id"
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class, 'id_program', 'id');
    }
}
