<?php

namespace App\Models\web\calendarios;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartaoEscola extends Model
{
    use SoftDeletes;

    protected $table = "tb_cartao_escola";

    protected $fillable = [
        'month_number',
        'month_name',
        'data_at',
        'data_exp',
        'status',
        'servicos_id',
        'shcools_id',
        'ano_lectivos_id',
    ];
}
