<?php

namespace App\Models\web\cursos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Editora extends Model
{
    use SoftDeletes;

    protected $table = "tb_editoras";

    protected $fillable = [
        'nome',
        'nif',
        'pais',
        'ano_fundacao',
        'website',
        'email',
        'telefone',
        'endereco',
        'observacao',
        'shcools_id',
    ];

}
