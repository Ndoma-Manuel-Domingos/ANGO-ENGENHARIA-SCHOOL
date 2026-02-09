<?php

namespace App\Models\TransferenciasCopias;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartaoEstudanteCopia extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = "mysql2";
    
    protected $table = "tb_cartao_estudantes";

    protected $fillable = [
        'estudantes_id',
        'mes_id',
        'servicos_id',
        'status',
        'data_at',
        'data_exp',
        'month_number',
        'month_name',
        'ano_lectivos_id',
        'transferencia_id',
    ];
}
