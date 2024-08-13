<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Mentor extends Model
{
    use HasFactory;

    protected $table = "mentors";

    public $timestamps = true;

    protected $guarded = [
        "id"
    ];

    public function programs(): BelongsToMany{
        return $this->belongsToMany(Program::class, 'mentor_program', 'mentor_id', 'program_id');
    }

}
