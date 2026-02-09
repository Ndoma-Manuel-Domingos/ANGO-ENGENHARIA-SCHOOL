<?php

namespace App\Models\web\turmas;

use App\Models\web\estudantes\Estudante;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstudantesTurma extends Model
{
    use SoftDeletes;

    protected $table = "tb_turmas_estudantes";

    protected $fillable = [
        'ordem',
        'status',
        'turmas_id',
        'nota_pap',
        'nota_estagio',
        'estudantes_id',
        'ano_lectivos_id',
    ];

    public function estudante()
    {
        return $this->belongsTo(Estudante::class, 'estudantes_id', 'id');
    }

    public function turma()
    {
        return $this->belongsTo(Turma::class, 'turmas_id', 'id');
    }
}
