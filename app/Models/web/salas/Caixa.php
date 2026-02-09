<?php

namespace App\Models\web\salas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Caixa extends Model
{
    use SoftDeletes;

    protected $table = "tb_caixas";

    protected $fillable = [
        'ordem',
        'conta',
        'caixa',
        'status',
        'usuario_id',
        'shcools_id',
    ];
}
