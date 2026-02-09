<?php

namespace App\Models\web\turmas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecursoTurma extends Model
{
    use SoftDeletes;

    protected $table = "tb_turma_recursos";

    
}
