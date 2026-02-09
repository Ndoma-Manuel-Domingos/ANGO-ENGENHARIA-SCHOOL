<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Director extends Model
{
    use HasFactory, SoftDeletes;
        
    protected $table = "tb_directores";

    protected $fillable = [
        'nome',
        'status',
        'bilheite',
        'genero',
        'estado_civil',
        'especialidade',
        'descricao',
        'curso',
        'level',
        'instituicao_id',
    ];
}
