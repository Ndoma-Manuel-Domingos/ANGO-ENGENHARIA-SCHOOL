<?php

namespace App\Models\web\salas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banco extends Model
{
    use SoftDeletes;

    protected $table = "tb_bancos";

    protected $fillable = [
        'ordem',
        'conta',
        'banco',
        'numero_conta',
        'iban',
        'status',
        'usuario_id',
        'shcools_id',
    ];
}
