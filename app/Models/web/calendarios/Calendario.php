<?php

namespace App\Models\web\calendarios;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Calendario extends Model
{
    use SoftDeletes;

    protected $table = "tb_calendarios";

    protected $fillable = [
        'valor_matricula',
        'valor_confirmacao',
        'valor_propina',
        'dia_inicio',
        'dia_final',
        'status',
        'classes_id',
        'turnos_id',
        'cursos_id',
        'ano_lectivos_id',
    ];
}
