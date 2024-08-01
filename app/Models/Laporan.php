<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;
    protected $table = "laporan";
    
    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'link_laporan',
    ];

   
}