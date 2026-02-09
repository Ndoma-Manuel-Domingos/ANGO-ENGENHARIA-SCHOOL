<?php

namespace App\Http\Controllers;

use App\Models\Shcool;
use App\Models\User;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\calendarios\Matricula;
use App\Models\web\calendarios\Servico;
use App\Models\web\classes\AnoLectivoClasse;
use App\Models\web\cursos\AnoLectivoCurso;
use App\Models\web\disciplinas\Disciplina;
use App\Models\web\estudantes\Estudante;
use App\Models\web\salas\AnoLectivoSala;
use App\Models\web\seguranca\ControloSistema;
use App\Models\web\turnos\AnoLectivoTurno;
use Illuminate\Support\Facades\DB;


use Illuminate\Support\Facades\Auth;

Trait TraitHeader{

    use TraitHelpers;

    public function headers()
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
    

        $headers = [ 
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "usuario" => User::findOrFail(Auth::user()->id),
            "turmas" => DB::table('tb_turmas')->where([
                ['tb_ano_lectivos.id', '=', $this->anolectivoActivo()],
                ['tb_turmas.deleted_at', '=', NULL],
            ])
            ->join('tb_cursos', 'tb_turmas.cursos_id', '=', 'tb_cursos.id')
            ->join('tb_classes', 'tb_turmas.classes_id', '=', 'tb_classes.id')
            ->join('tb_turnos', 'tb_turmas.turnos_id', '=', 'tb_turnos.id')
            ->join('tb_salas', 'tb_turmas.salas_id', '=', 'tb_salas.id')
            ->join('tb_ano_lectivos', 'tb_turmas.ano_lectivos_id', '=', 'tb_ano_lectivos.id')
            ->select('tb_turmas.id', 'tb_turmas.turma', 'tb_turmas.status','tb_ano_lectivos.id AS ids', 'tb_ano_lectivos.ano', 'tb_classes.classes','tb_salas.salas', 'tb_cursos.curso', 'tb_turnos.turno',)
            ->get(),
            "classes" => AnoLectivoClasse::where([
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['shcools_id', '=', $this->escolarLogada()],
            ])
            ->with('classe')
            ->get(),
            "turnos" => AnoLectivoTurno::where([
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['shcools_id', '=', $this->escolarLogada()],
            ])
            ->with('turno')
            ->get(),
            "salas" => AnoLectivoSala::where([
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['shcools_id', '=', $this->escolarLogada()],
            ])
            ->with('sala')
            ->get(),
            "anolectivos" => AnoLectivo::where([
                ['shcools_id', '=', $this->escolarLogada()], 
                ['status', '=', 'activo']
            ])->get(), 
            "cursos" => AnoLectivoCurso::where([
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['shcools_id', '=', $this->escolarLogada()],
            ])
            ->with('curso')
            ->get(),
            "totalServicos" => Servico::where([
                ['shcools_id', '=', $this->escolarLogada()], 
                ['status', '=', 'activo'],
            ])->count(), 
            "totalestudantes" => Estudante::where([
                ['shcools_id', '=', $this->escolarLogada()], 
                ['registro', '=', 'confirmado'], 
                ['ano_lectivos_id', '=', $this->anolectivoActivo()], 
                ['status', '=', 'activo']
            ])->count(), 
            "totalmatriculas" => Matricula::where([
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['shcools_id', '=', $this->escolarLogada()],
            ])->count(),
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            "disciplinas" => Disciplina::where([
                ['shcools_id', '=', $this->escolarLogada()],
            ])->get(),

            "matriculass" => Matricula::where([
                ['status_matricula', '=', 'confirmado'],
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['shcools_id', '=', $this->escolarLogada()],
            ])
            ->select('tb_matriculas.documento', 'tb_matriculas.numero_estudante')
            ->get(),
        ];

        return $headers;
    }

    public function controlo()
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
        $controlo = ControloSistema::where('shcools_id', '=', $this->escolarLogada())
            ->where('tipo', 'ESCOLA')
            ->where('level', '4')
            ->where('inicio', '>=', $this->data_sistema())
            ->where('final', '<=', $this->data_sistema())
            ->first();

        if ($controlo) {
            session()->forget('usuariologado');
            session()->flash('erro', ['Infelizmente não podes acessar o sistema, o sistema está bloqueiado.'] );
            return redirect()->route('login');
        }
    }


}