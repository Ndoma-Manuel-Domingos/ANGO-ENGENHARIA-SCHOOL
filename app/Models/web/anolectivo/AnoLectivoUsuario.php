<?php

namespace App\Models\web\anolectivo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnoLectivoUsuario extends Model
{
    use SoftDeletes;

    protected $table = "tb_ano_lectivo_usuario";

    protected $fillable = [
        'ano_lectivos_id',
        'usuario_id',
        'status',
        'sessao',
        'shcools_id',
    ];
}
