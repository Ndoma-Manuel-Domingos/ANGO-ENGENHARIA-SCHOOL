<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartaoFuncionarioCopia extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $connection = "mysql2";
    protected $table = "tb_cartoes_funcionarios_copy";

    protected $fillable = [
        'mes_id',
        'status',
        'level',
        'codigo',
        'funcionarios_id',
        'shcools_id',
        'ano_lectivos_id',
    ];
}
