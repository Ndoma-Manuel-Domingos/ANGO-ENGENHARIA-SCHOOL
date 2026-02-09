<?php

namespace App\Models\web\financeiros;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartaoAgua extends Model
{
    use SoftDeletes;

    protected $table = "tb_cartao_agua";

    protected $fillable = [
        'documento',
        'numero_processo',
        'nome',
        'sobre_nome',
        'nascimento',
        'genero',
        'estado_civil',
        'nacionalidade',
        'bilheite',
        'status',
        'dificiencia',
        'provincia',
        'munincipio',
        'naturalidade',
        'pai',
        'mae',
        'telefone_estudante',
        'telefone_pai',
        'telefone_mae',
        'endereco',
        'image',
        'registro',
        'ano_lectivos_id',
        'shcools_id',
    ];
}
