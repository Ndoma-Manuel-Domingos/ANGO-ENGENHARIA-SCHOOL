<?php

namespace App\Models\web\salas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Test extends Model
{
    use SoftDeletes;

    protected $table = "tb_testes";

    protected $fillable = [
        'nome',
        'codigo_barra',
        'preco_custo',
        'status',
        'imagem',
    ];
}
