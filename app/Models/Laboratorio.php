<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Laboratorio extends Model
{
    use HasFactory, SoftDeletes;
        
    protected $table = "tb_laboratorios";

    protected $fillable = [
        'nome',
        'status',
    ];
}
