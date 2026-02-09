<?php

namespace App\Models\web\cursos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Autor extends Model
{
    use SoftDeletes;

    protected $table = "tb_autores";

    protected $fillable = [
        'nome',
        'documento',
        'data_nascimento',
        'genero',
        'email',
        'telefone',
        'shcools_id',
    ];

}
