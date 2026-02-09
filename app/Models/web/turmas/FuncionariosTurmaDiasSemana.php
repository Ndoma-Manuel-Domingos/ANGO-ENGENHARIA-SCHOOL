<?php

namespace App\Models\web\turmas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FuncionariosTurmaDiasSemana extends Model
{
    use SoftDeletes;

    protected $table = "tb_dias_semanas_funcionarios_turmas";

    
}
