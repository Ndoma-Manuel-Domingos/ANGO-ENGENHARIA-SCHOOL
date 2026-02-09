<?php

namespace App\Models\web\calendarios;

use App\Http\Controllers\TraitChavesSaft;
use App\Http\Controllers\TraitHelpers;
use App\Models\Distrito;
use App\Models\Municipio;
use App\Models\Paise;
use App\Models\Provincia;
use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\anolectivo\EscolaFilhar;
use App\Models\web\classes\Classe;
use App\Models\web\cursos\Curso;
use App\Models\web\estudantes\Estudante;
use App\Models\web\salas\Sala;
use App\Models\web\turmas\EstudantesTurma;
use App\Models\web\turmas\Turma;
use App\Models\web\turnos\Turno;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Matricula extends Model
{
    use SoftDeletes;
    use TraitHelpers;
    use TraitChavesSaft;


    protected $table = "tb_matriculas";

    protected $fillable = [
        'status',
        'data_at',
        'numero_estudante',
        'ficha',
        'documento',
        'status_matricula',
        'status_matricula_pagamento',
        'status_inscricao',
        'resultado_final',
        'finalista',
        'at_classes_id',
        'classes_id',
        'turnos_id',
        'cursos_id',
        'tipo',
        'prova_acesso',
        'exame_acesso',
        'media',
        'cursos_primeira_opcao_id',
        'cursos_segunda_opcao_id',
        'shcools_filhar_id',
        'condicao',
        'funcionarios_id',
        'numeracao',
        'estudantes_id',
        'comprovativo',
        'comprovativo_url',
        'pais_id',
        'provincia_id',
        'municipio_id',
        'distrito_id',
        'level',
        'ano_lectivos_id',
        'shcools_id',
        'ano_lectivo_global_id',
    ];

    public function escola($id)
    {
        return Shcool::findOrFail($id)->nome;
    }

    public function escola_fihar()
    {
        return $this->belongsTo(EscolaFilhar::class, 'shcools_filhar_id', 'id');
    }

    public function escolas()
    {
        return $this->belongsTo(Shcool::class, 'shcools_id', 'id');
    }

    public function pais()
    {
        return $this->belongsTo(Paise::class, 'pais_id', 'id');
    }

    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'provincia_id', 'id');
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'municipio_id', 'id');
    }

    public function distrito()
    {
        return $this->belongsTo(Distrito::class, 'distrito_id', 'id');
    }

    public function ano_lectivo()
    {
        return $this->belongsTo(AnoLectivo::class, 'ano_lectivos_id', 'id');
    }

    public function classe_at()
    {
        return $this->belongsTo(Classe::class, 'at_classes_id', 'id');
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class, 'classes_id', 'id');
    }

    public function turno()
    {
        return $this->belongsTo(Turno::class, 'turnos_id', 'id');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'cursos_id', 'id');
    }

    public function estudante()
    {
        return $this->belongsTo(Estudante::class, 'estudantes_id', 'id');
    }

    public function sala($estudante_id = null, $ano_lectivo_id = null)
    {

        if ($ano_lectivo_id == null) {
            $ano_lectivo_id = $this->anolectivoActivo();
        }

        $turma = EstudantesTurma::where([
            ['estudantes_id', $estudante_id],
            ['ano_lectivos_id', $ano_lectivo_id],
        ])->with(['turma.sala'])->first();

        if ($turma) {
            return  $turma->turma->sala->salas ?? "";
        } else {
            return "Sem Sala";
        }
    }

    public function turma($estudante_id = null, $ano_lectivo_id = null)
    {

        if ($ano_lectivo_id == null) {
            $ano_lectivo_id = $this->anolectivoActivo();
        }

        $turma = EstudantesTurma::where([
            ['estudantes_id', $estudante_id],
            ['ano_lectivos_id', $ano_lectivo_id],
        ])->with(['turma.curso'])->first();

        if ($turma) {
            return  $turma->turma->turma ?? null;
        } else {
            return "Sem Turma";
        }
    }

    public function turma_id($estudante_id = null, $ano_lectivo_id = null)
    {

        if ($ano_lectivo_id == null) {
            $ano_lectivo_id = $this->anolectivoActivo();
        }

        $turma = EstudantesTurma::where([
            ['estudantes_id', $estudante_id],
            ['ano_lectivos_id', $ano_lectivo_id],
        ])->with(['turma.curso'])->first();

        if ($turma) {
            return  $turma->turma->id ?? null;
        } else {
            return "Sem Turma";
        }
    }
}
