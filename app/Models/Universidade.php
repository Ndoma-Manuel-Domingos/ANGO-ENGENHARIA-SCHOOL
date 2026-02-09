<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Universidade extends Model
{
    use HasFactory, SoftDeletes;   
    
    protected $table = "tb_universidades";

    protected $fillable = [
        'nome',
        'status',
    ];
}
