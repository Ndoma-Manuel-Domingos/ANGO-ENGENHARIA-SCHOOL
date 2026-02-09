<?php

namespace App\Models\web\turmas;

use App\Http\Controllers\TraitChavesSaft;
use App\Http\Controllers\TraitHelpers;
use App\Models\ControloLancamentoNotas;
use App\Models\ControloLancamentoNotasEscolas;
use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\classes\Classe;
use App\Models\web\cursos\Curso;
use App\Models\web\salas\Sala;
use App\Models\web\turnos\Turno;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Turma extends Model
{
    use SoftDeletes;
    use TraitHelpers;
    use TraitChavesSaft;


    protected $table = "tb_turmas";

    protected $fillable = [
        'turma',
        'numero_maximo',
        'status',
        'shcools_id',
        'classes_id',
        'turnos_id',
        'finalista',
        'cursos_id',
        'salas_id',

        'grade_curricular', // true ou false

        'valor_propina',
        'valor_propina_com_iva',
        'valor_confirmacao',
        'valor_confirmacao_com_iva',
        'valor_matricula',
        'valor_matricula_com_iva',
        'ano_lectivos_id',

        'intervalo_pagamento_inicio',
        'intervalo_pagamento_final',
        'taxa_multa1',
        'taxa_multa1_dia',
        'taxa_multa2',
        'taxa_multa2_dia',
        'taxa_multa3',
        'taxa_multa3_dia',
    ];


    public function disciplinas()
    {
        return $this->hasMany(DisciplinaTurma::class, 'turmas_id', 'id');
    }

    public function escola()
    {
        return $this->belongsTo(Shcool::class, 'shcools_id', 'id');
    }

    public function anolectivo()
    {
        return $this->belongsTo(AnoLectivo::class, 'ano_lectivos_id', 'id');
    }

    public function turno()
    {
        return $this->belongsTo(Turno::class, 'turnos_id', 'id');
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class, 'classes_id', 'id');
    }

    public function sala()
    {
        return $this->belongsTo(Sala::class, 'salas_id', 'id');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'cursos_id', 'id');
    }

    function total_estudantes($id)
    {
        return EstudantesTurma::where('turmas_id', $id)->count('estudantes_id');
    }

    public function tempo_lancamento_notas($ano_lectivo, $escola)
    {
        // controle lancamento de notas se esta activo ou não
        $controlo = ControloLancamentoNotasEscolas::where('ano_lectivo_id', $ano_lectivo)->where('shcools_id', $escola)->first();

        $lancamento = ControloLancamentoNotas::where('status', 'activo')->where('id', $controlo->lancamento_id)->first();

        return $lancamento;
    }


    public function notas($estudante, $anoLectivo, $trimestre, $disciplina = null)
    {
        $query = NotaPauta::with(['ano', 'disciplina', 'trimestre', 'estudante', 'turma'])
            ->where('controlo_trimestres_id', $trimestre)
            ->where('estudantes_id', $estudante)
            ->where('ano_lectivos_id', $anoLectivo);

        if (!is_null($disciplina)) {
            $query->where('disciplinas_id', $disciplina);
        }

        return $query->get();
    }


    public function categoria_escola($escola)
    {
        $escola = Shcool::findOrFail($escola);

        return $escola->categoria;
    }
}
