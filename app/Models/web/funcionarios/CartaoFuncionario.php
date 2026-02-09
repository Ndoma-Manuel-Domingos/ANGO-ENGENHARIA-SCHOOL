<?php

namespace App\Models\web\funcionarios;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartaoFuncionario extends Model
{
    use SoftDeletes;

    protected $table = "tb_cartoes_funcionarios";

    protected $fillable = [
        'mes_id',
        'status',
        'level',
        'codigo',
        'descricao',
        'funcionarios_id',
        'shcools_id',
        'ordem',
        'data_at',
        'data_exp',
        'month_number',
        'month_name',
        'ano_lectivos_id',
    ];
}
