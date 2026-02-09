<?php

namespace App\Models\web\calendarios;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ListaPresenca extends Model
{
    use SoftDeletes;

    protected $table = "tb_turma_presencas";

    protected $fillable = [
        'data_at',
        'semanas_id',
        'status',
        'estudantes_id',
        'disciplinas_id',
        'turmas_id',
        'funcionarios_id',
        'shcools_id',
    ];
}
