<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mentor extends Model
{
    use HasFactory;

    protected $table = "mentors";

    public $timestamps = true;

    protected $guarded = [
        "id"
    ];


}
