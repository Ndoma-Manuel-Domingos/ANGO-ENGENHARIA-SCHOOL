<?php

namespace App\Models\web\calendarios;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Confirmacao extends Model
{
    use SoftDeletes;

    protected $table = "tb_confirmacoes";

    protected $fillable = [
        'status',
        'estudantes_id',
        'shcools_id',
        'ano_lectivos_id',
    ];
}
