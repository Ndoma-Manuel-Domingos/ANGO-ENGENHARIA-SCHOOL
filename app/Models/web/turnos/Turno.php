<?php

namespace App\Models\web\turnos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Turno extends Model
{
    use SoftDeletes;

    protected $table = "tb_turnos";

    protected $fillable = [
        'turno',
        'status',
        'horario',
        'descricao',
        'shcools_id',
    ];
}
