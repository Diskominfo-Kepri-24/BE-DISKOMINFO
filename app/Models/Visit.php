<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'visit_count',
        'is_online',
        'last_activity',
    ];

    protected $casts = [
        'last_activity' => 'datetime',
    ];
}
