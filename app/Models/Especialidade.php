<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Especialidade extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = "tb_especialidades";

    protected $fillable = [
        'nome',
        'status',
    ];
}
