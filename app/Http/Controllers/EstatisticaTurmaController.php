<?php

namespace App\Http\Controllers;

use App\Exports\EstatisticaExport;
use App\Models\AnoLectivoGlobal;
use App\Models\DireccaoProvincia;
use App\Models\Distrito;
use App\Models\Municipio;
use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\anolectivo\Trimestre;
use App\Models\web\calendarios\Matricula;
use App\Models\web\classes\Classe;
use App\Models\web\turmas\DisciplinaTurma;
use App\Models\web\turmas\EstudantesTurma;
use App\Models\web\turmas\Turma;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class EstatisticaTurmaController extends Controller
{
    use TraitHelpers;


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function EstatisticaTurmasUnica(Request $request)
    {
        ini_set('memory_limit', '2048M');  // Ajuste para 1024 MB ou outro valor

        $user = auth()->user();

        $escola = Shcool::with(['ensino'])->findOrFail($this->escolarLogada());

        $disciplinas = null;
        $estudantes = null;
        $turma = Turma::with(['curso', 'turno', 'classe', 'anolectivo'])->find($request->turmas_id);
        $ano_lectivo = AnoLectivo::find($request->ano_lectivos_id ?? $this->anolectivoActivo());

        $trimestre1 = Trimestre::where('trimestre', 'Iª Trimestre')->first();
        $trimestre2 = Trimestre::where('trimestre', 'IIª Trimestre')->first();
        $trimestre3 = Trimestre::where('trimestre', 'IIIª Trimestre')->first();
        $trimestre4 = Trimestre::where('trimestre', 'Geral')->first();

        if ($turma) {
            $disciplinas = DisciplinaTurma::with(['disciplina'])->where('turmas_id', $turma->id)->get();
            $estudantes = EstudantesTurma::with(['estudante'])
                ->where('turmas_id', $turma->id)
            ->get()
            ->sortBy(function ($estudante) {
                return $estudante->estudante->nome; // Ordena pela propriedade 'nome' do estudante
            });
        }
 
        $headers = [
            "escola" => $escola,
            "usuario" => $user,
            // classe actual
            "disciplinas" => $disciplinas,
            "estudantes" => $estudantes,
            "turma" => $turma,
            "ano_lectivo" => $ano_lectivo,
            "trimestre1" => $trimestre1 ?? 0,
            "trimestre2" => $trimestre2 ?? 0,
            "trimestre3" => $trimestre3 ?? 0,
            "trimestre4" => $trimestre4 ?? 0,
            "turmas" => Turma::where('ano_lectivos_id', $request->ano_lectivos_id ?? $this->anolectivoActivo())->where('status', 'activo')->get(),
            "anolectivos" => AnoLectivo::where('shcools_id', $this->escolarLogada())
            ->get(),

            "requests" => $request->all('turmas_id', 'ano_lectivos_id', 'offset_limit'),
        ];

        return view('admin.turmas.estatistica-turma', $headers);
    }

    public function EstatisticaTurmasUnicaPdf(Request $request)
    {
        ini_set('memory_limit', '2048M');  // Ajuste para 1024 MB ou outro valor
        $user = auth()->user();

        $turma = Turma::with(['curso', 'turno', 'classe', 'anolectivo'])->find($request->turmas_id);
        $ano_lectivo = AnoLectivo::find($request->ano_lectivos_id ?? $this->anolectivoActivo());
    
        $trimestre1 = Trimestre::where('trimestre', 'Iª Trimestre')->first();
        $trimestre2 = Trimestre::where('trimestre', 'IIª Trimestre')->first();
        $trimestre3 = Trimestre::where('trimestre', 'IIIª Trimestre')->first();
        $trimestre4 = Trimestre::where('trimestre', 'Geral')->first();

        $disciplinas = null;
        $estudantes = null;

        if ($turma) {
            $disciplinas = DisciplinaTurma::with(['disciplina'])
                ->where('turmas_id', $turma->id)
                ->get();
                
            $estudantes = EstudantesTurma::with(['estudante'])
                ->where('turmas_id', $turma->id)
                ->get()
                ->sortBy(function ($estudante) {
                    return $estudante->estudante->nome; // Ordena pela propriedade 'nome' do estudante
                });
        }

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            
            "usuario" => $user,
            "titulo" => "PAUTA FINAL",
            // classe actual
            "disciplinas" => $disciplinas,
            "estudantes" => $estudantes,
            "turma" => $turma,
            // classe anterior
            "ano_lectivo" => $ano_lectivo,
            "trimestre1" => $trimestre1,
            "trimestre2" => $trimestre2,
            "trimestre3" => $trimestre3,
            "trimestre4" => $trimestre4,
            "turmas" => Turma::where('ano_lectivos_id', $request->ano_lectivos_id ?? $this->anolectivoActivo())->where('status', 'activo')->get(),
            "anolectivos" => AnoLectivo::where('shcools_id', $this->escolarLogada())->get(),
            "requests" => $request->all('turmas_id', 'ano_lectivos_id', 'offset_limit'),
        ];

        $orintacao = 'landscape';

        $pdf = \PDF::loadView('downloads.relatorios.planificacao-pautas-gerais', $headers)->setPaper('A3', $orintacao);
        return $pdf->stream('planificacao-pautas-gerais.pdf');
    }

    public function EstatisticaTurmasUnicaExcel(Request $request)
    {
        ini_set('memory_limit', '2048M');  // Ajuste para 1024 MB ou outro valor

        $codigo = date("Y-m-d");

        return Excel::download(new EstatisticaExport($request->turmas_id, $request->ano_lectivos_id ?? $this->anolectivoActivo()), "PAUTA-FINAL-{$codigo}.xlsx");
    }

    public function estatisticaGeral($genero = null)
    {
        ini_set('memory_limit', '2048M');  // Ajuste para 1024 MB ou outro valor
        $user = auth()->user();

        // if(!$user->can('read: estatistica')){
        //  Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //  return redirect()->back();
        //}

        if (!empty($genero)) {
            $EstudanteEStatisticas = Matricula::where([
                ['tb_matriculas.ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['tb_matriculas.deleted_at', '=', NULL],
                ['tb_matriculas.status_matricula', '=', "confirmado"],
                ['tb_estudantes.genero', '=', $genero],
            ])->join('tb_estudantes', 'tb_matriculas.estudantes_id', '=', 'tb_estudantes.id')
                ->select('tb_estudantes.genero', 'tb_estudantes.nascimento', 'tb_estudantes.estado_civil')
                ->count();
        } else {
            $EstudanteEStatisticas = Matricula::where([
                ['tb_matriculas.ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['tb_matriculas.deleted_at', '=', NULL],
                ['tb_matriculas.status_matricula', '=', "confirmado"],
            ])->join('tb_estudantes', 'tb_matriculas.estudantes_id', '=', 'tb_estudantes.id')
                ->select('tb_estudantes.genero', 'tb_estudantes.nascimento', 'tb_estudantes.estado_civil')
                ->get();
        }

        return $EstudanteEStatisticas;
    }

    public function estatistica($status = null, $genero = null, $totalGenero = null)
    {
        ini_set('memory_limit', '2048M');  // Ajuste para 1024 MB ou outro valor
        $user = auth()->user();

        // if(!$user->can('read: estatistica')){
        // Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        // return redirect()->back();
        //}

        // CARREGAR ESTADATES 
        if (empty($status) and empty($genero) and empty($totalGenero)) {
            $resultado = Matricula::where([
                ['tb_matriculas.ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['tb_matriculas.deleted_at', '=', NULL],
                ['tb_matriculas.status_matricula', '=', "confirmado"],
            ])->join('tb_estudantes', 'tb_matriculas.estudantes_id', '=', 'tb_estudantes.id')
                ->select('tb_estudantes.genero', 'tb_estudantes.nascimento', 'tb_estudantes.estado_civil')
                ->get();
        }

        // CARREGAR ESTADATES (MATRICULADOS OU CONFIRMADOS)
        if (!empty($status) and empty($genero) and empty($totalGenero)) {
            $resultado = Matricula::where([
                ['tb_matriculas.ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['tb_matriculas.deleted_at', '=', NULL],
                ['tb_matriculas.tipo', '=', $status],
                ['tb_matriculas.status_matricula', '=', "confirmado"],
            ])->join('tb_estudantes', 'tb_matriculas.estudantes_id', '=', 'tb_estudantes.id')
                ->select('tb_estudantes.genero', 'tb_estudantes.nascimento', 'tb_estudantes.estado_civil')
                ->get();
        }

        // CARREGAR ESTADATES (MATRICULADOS OU CONFIRMADOS) EM NUMERO
        if (!empty($status) and empty($genero) and !empty($totalGenero)) {
            $resultado = Matricula::where([
                ['tb_matriculas.ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['tb_matriculas.deleted_at', '=', NULL],
                ['tb_matriculas.tipo', '=', $status],
                ['tb_matriculas.status_matricula', '=', "confirmado"],
            ])->join('tb_estudantes', 'tb_matriculas.estudantes_id', '=', 'tb_estudantes.id')
                ->select('tb_estudantes.genero', 'tb_estudantes.nascimento', 'tb_estudantes.estado_civil')
                ->count();
        }

        // CARREGAR ESTADATES EM NUMERO
        if (empty($status) and empty($genero) and !empty($totalGenero)) {
            $resultado = Matricula::where([
                ['tb_matriculas.ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['tb_matriculas.deleted_at', '=', NULL],
                ['tb_matriculas.status_matricula', '=', "confirmado"],
            ])->join('tb_estudantes', 'tb_matriculas.estudantes_id', '=', 'tb_estudantes.id')
                ->select('tb_estudantes.genero', 'tb_estudantes.nascimento', 'tb_estudantes.estado_civil')
                ->count();
        }

        // CARREGAR ESTADATES (MATRICULADOS OU CONFIRMADOS) DE UM DETERMINADO GENERO
        if (!empty($status) and !empty($genero) and empty($totalGenero)) {
            $resultado = Matricula::where([
                ['tb_matriculas.ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['tb_matriculas.deleted_at', '=', NULL],
                ['tb_matriculas.tipo', '=', $status],
                ['tb_estudantes.genero', '=', $genero],
                ['tb_matriculas.status_matricula', '=', "confirmado"],
            ])->join('tb_estudantes', 'tb_matriculas.estudantes_id', '=', 'tb_estudantes.id')
                ->select('tb_estudantes.genero', 'tb_estudantes.nascimento', 'tb_estudantes.estado_civil')
                ->get();
        }

        // CARREGAR TOTAL ESTADATES (MATRICULADOS OU CONFIRMADOS) DE UM DETERMINADO GENERO EM NUMERO
        if (!empty($status) and !empty($genero) and !empty($totalGenero)) {
            $resultado = Matricula::where([
                ['tb_matriculas.ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['tb_matriculas.deleted_at', '=', NULL],
                ['tb_matriculas.tipo', '=', $status],
                ['tb_estudantes.genero', '=', $genero],
                ['tb_matriculas.status_matricula', '=', "confirmado"],
            ])->join('tb_estudantes', 'tb_matriculas.estudantes_id', '=', 'tb_estudantes.id')
                ->select('tb_estudantes.genero', 'tb_estudantes.nascimento', 'tb_estudantes.estado_civil')
                ->count();
        }

        return $resultado;
    }

    /** PRONVICIAL */

    public function EstatisticaProvincialTurmasUnica(Request $request)
    {
        ini_set('memory_limit', '2048M');  // Ajuste para 1024 MB ou outro valor
        $user = auth()->user();

        //if(!$user->can('read: estatistica')){
        //    Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //    return redirect()->back();
        // }

        $direccao = DireccaoProvincia::findOrFail($user->shcools_id);

        // Turmas desta ano selecionado

        $classe_10 = Classe::where('classes', '10ª Classe')->first();
        $classe_11 = Classe::where('classes', '11ª Classe')->first();
        $classe_12 = Classe::where('classes', '12ª Classe')->first();

        $classe_7 = Classe::where('classes', '7ª Classe')->first();
        $classe_8 = Classe::where('classes', '8ª Classe')->first();
        $classe_9 = Classe::where('classes', '9ª Classe')->first();


        $trimestre1 = Trimestre::where('trimestre', 'Iª Trimestre')->first();
        $trimestre2 = Trimestre::where('trimestre', 'IIª Trimestre')->first();
        $trimestre3 = Trimestre::where('trimestre', 'IIIª Trimestre')->first();
        $trimestre4 = Trimestre::where('trimestre', 'Geral')->first();

        $turma = Turma::with(['curso', 'turno', 'classe', 'anolectivo'])->find($request->turmas_id);

        // turma anterio 
        $turma_anterior = null;
        $turma_antes_anterior = null;

        $ano_lectivo = AnoLectivo::find($request->ano_lectivo_id);

        if ($ano_lectivo) {
            $ano_lectivo_anterior = AnoLectivo::find($this->anolectivoAnteriorId($ano_lectivo->id ?? 0));
            $ano_lectivo_antes_anterior = AnoLectivo::find($this->anolectivoAntesAnteriorId($ano_lectivo->id ?? 0));
        }

        $disciplinas = null;
        $estudantes = null;
        $disciplinas_ids = "";


        if ($turma && $ano_lectivo) {

            $disciplinas = DisciplinaTurma::with(['disciplina'])->where('turmas_id', $turma->id)->get();
            $disciplinas_ids = DisciplinaTurma::with(['disciplina'])->where('turmas_id', $turma->id)->pluck('disciplinas_id');
            $estudantes = EstudantesTurma::with(['estudante'])->where('turmas_id', $turma->id)->get();

            // verificar se a turma já é 10classe
            if ($turma->classes_id != $classe_10->id && $turma->classe->classes == '11ª Classe') {
                $turma_anterior = Turma::with(['curso', 'turno', 'classe', 'anolectivo'])
                    ->where('ano_lectivos_id', $this->anolectivoAnteriorId($ano_lectivo->id))
                    ->where('shcools_id', $request->shcools_id)
                    ->where('cursos_id', $turma->cursos_id)
                    ->where('classes_id', $classe_10->id)
                    ->first();
            }

            if ($turma->classes_id != $classe_11->id && $turma->classe->classes == '12ª Classe') {
                $turma_anterior = Turma::with(['curso', 'turno', 'classe', 'anolectivo'])
                    ->where('ano_lectivos_id', $this->anolectivoAnteriorId($ano_lectivo->id))
                    ->where('shcools_id', $request->shcools_id)
                    ->where('cursos_id', $turma->cursos_id)
                    ->where('classes_id', $classe_11->id)
                    ->first();

                if ($turma_anterior) {
                    $turma_antes_anterior = Turma::with(['curso', 'turno', 'classe', 'anolectivo'])
                        ->where('ano_lectivos_id', $this->anolectivoAntesAnteriorId($ano_lectivo->id))
                        ->where('shcools_id', $request->shcools_id)
                        ->where('cursos_id', $turma_anterior->cursos_id)
                        ->where('classes_id', $classe_10->id)
                        ->first();
                }
            }

            if ($turma->classes_id != $classe_7->id && $turma->classe->classes == '8ª Classe') {
                $turma_anterior = Turma::with(['curso', 'turno', 'classe', 'anolectivo'])
                    ->where('ano_lectivos_id', $this->anolectivoAnteriorId($ano_lectivo->id))
                    ->where('shcools_id', $request->shcools_id)
                    ->where('cursos_id', $turma->cursos_id)
                    ->where('classes_id', $classe_7->id)
                    ->first();
            }

            if ($turma->classes_id != $classe_8->id && $turma->classe->classes == '9ª Classe') {
                $turma_anterior = Turma::with(['curso', 'turno', 'classe', 'anolectivo'])
                    ->where('ano_lectivos_id', $this->anolectivoAnteriorId($ano_lectivo->id))
                    ->where('shcools_id', $request->shcools_id)
                    ->where('cursos_id', $turma->cursos_id)
                    ->where('classes_id', $classe_8->id)
                    ->first();

                if ($turma_anterior) {
                    $turma_antes_anterior = Turma::with(['curso', 'turno', 'classe', 'anolectivo'])
                        ->where('ano_lectivos_id', $this->anolectivoAntesAnteriorId($ano_lectivo->id))
                        ->where('shcools_id', $request->shcools_id)
                        ->where('cursos_id', $turma_anterior->cursos_id)
                        ->where('classes_id', $classe_7->id)
                        ->first();
                }
            }
        }


        $disciplinas_anterior = null;
        $disciplinas_antes_anterior = null;

        $disciplinas_anterior_ids = null;
        $disciplinas_antes_anterior_ids = null;

        $estudantes_anterior = null;
        $disciplinas_anterior_eliminadas = null;
        $disciplinas_actuais_e_anterior = null;
        $disciplinas_actuais_novas = null;

        $disciplinas_terceira_segundo_primeira_turma = null;
        $disciplinas_terceira_turma_actual = null;

        $disciplinas_eliminadas_segunda_turma = null;
        $disciplinas_eliminadas_primeira_turma = null;
        $disciplinas_eliminadas_primeira_segunda_turma = null;
        $disciplinas_segunda_terceira_turma_actual = null;
        $disciplinas_primeira_turma_actual = null;


        if ($turma_anterior && $turma_anterior->classes_id != $turma->classes_id) {

            $disciplinas_anterior = DisciplinaTurma::with(['disciplina'])->where('turmas_id', $turma_anterior->id)->get();

            $disciplinas_anterior_ids = DisciplinaTurma::with(['disciplina'])->where('turmas_id', $turma_anterior->id)->pluck('disciplinas_id');

            // disciplinas anterior que já não fazem parte da classe actual -------- as disciplinas eliminadas são aquelas que não fazem mais parte das disciplinas deste ano
            $disciplinas_anterior_eliminadas = DisciplinaTurma::whereNotIn('disciplinas_id', $disciplinas_ids)->with(['disciplina'])->where('turmas_id', $turma_anterior->id)->get();

            // disciplinas novas que não constam ou não fazem parte das disciplinas anterior somente actual
            $disciplinas_actuais_novas = DisciplinaTurma::whereNotIn('disciplinas_id', $disciplinas_anterior_ids)->with(['disciplina'])->where('turmas_id', $turma->id)->get();

            $disciplinas_actuais_e_anterior = DisciplinaTurma::whereIn('disciplinas_id', $disciplinas_anterior_ids)->with(['disciplina'])->where('turmas_id', $turma->id)->get();

            $estudantes_anterior = EstudantesTurma::with(['estudante'])->where('turmas_id', $turma_anterior->id)->get();
        }

        if ($turma_antes_anterior && $turma_antes_anterior->classes_id != $turma_anterior->classes_id) {

            $disciplinas_antes_anterior = DisciplinaTurma::with(['disciplina'])->where('turmas_id', $turma_antes_anterior->id)->get();
            $disciplinas_antes_anterior_ids = DisciplinaTurma::with(['disciplina'])->where('turmas_id', $turma_antes_anterior->id)->pluck('disciplinas_id');


            $disciplinas_terceira_segundo_primeira_turma = DisciplinaTurma::whereIn('disciplinas_id', $disciplinas_anterior_ids)->whereIn('disciplinas_id', $disciplinas_antes_anterior_ids)->with(['disciplina'])->where('turmas_id', $turma->id)->get();

            // disciplinas novas que não constam ou não fazem parte das disciplinas anterior nem no antes anterior somente actual
            $disciplinas_terceira_turma_actual = DisciplinaTurma::whereNotIn('disciplinas_id', $disciplinas_anterior_ids)->whereNotIn('disciplinas_id', $disciplinas_antes_anterior_ids)->with(['disciplina'])->where('turmas_id', $turma->id)->get();

            // disciplinas que tive na 7 e não tive na 8 nem na 9
            $disciplinas_primeira_turma_actual = DisciplinaTurma::whereNotIn('disciplinas_id', $disciplinas_anterior_ids)->whereIn('disciplinas_id', $disciplinas_antes_anterior_ids)->with(['disciplina'])->where('turmas_id', $turma->id)->get();

            // disciplinas que so tive na 8 e estou a dar continuidade com 9
            $disciplinas_segunda_terceira_turma_actual = DisciplinaTurma::whereIn('disciplinas_id', $disciplinas_anterior_ids)->whereNotIn('disciplinas_id', $disciplinas_antes_anterior_ids)->with(['disciplina'])->where('turmas_id', $turma->id)->get();

            // disciplinas anterior que já não fazem parte da classe actual -------- as disciplinas eliminadas são aquelas que não fazem mais parte das disciplinas deste ano
            $disciplinas_eliminadas_segunda_turma = DisciplinaTurma::whereNotIn('disciplinas_id', $disciplinas_ids)->with(['disciplina'])->where('turmas_id', $turma_anterior->id)->get();
            $disciplinas_eliminadas_primeira_turma = DisciplinaTurma::whereNotIn('disciplinas_id', $disciplinas_ids)->with(['disciplina'])->where('turmas_id', $turma_antes_anterior->id)->get();
        }

        $headers = [
            "usuario" => $user,

            // classe actual
            "disciplinas" => $disciplinas,
            "estudantes" => $estudantes,
            "turma" => $turma,

            // classe anterior
            "turma_anterior" => $turma_anterior,
            "disciplinas_anterior" => $disciplinas_anterior,

            "turma_antes_anterior" => $turma_antes_anterior,
            "disciplinas_antes_anterior" => $disciplinas_antes_anterior,


            //listagem das disciplinas

            "disciplinas_anterior_eliminadas" => $disciplinas_anterior_eliminadas,
            "disciplinas_actuais_novas" => $disciplinas_actuais_novas,
            "disciplinas_actuais_e_anterior" => $disciplinas_actuais_e_anterior,

            "disciplinas_terceira_segundo_primeira_turma" => $disciplinas_terceira_segundo_primeira_turma,
            "disciplinas_terceira_turma_actual" => $disciplinas_terceira_turma_actual,
            "disciplinas_eliminadas_segunda_turma" => $disciplinas_eliminadas_segunda_turma,
            "disciplinas_eliminadas_primeira_turma" => $disciplinas_eliminadas_primeira_turma,


            "ano_lectivo" => $ano_lectivo,
            "ano_lectivo_anterior" => $ano_lectivo_anterior ?? null,
            "ano_lectivo_antes_anterior" => $ano_lectivo_antes_anterior ?? null,

            "trimestre1" => $trimestre1,
            "trimestre2" => $trimestre2,
            "trimestre3" => $trimestre3,
            "trimestre4" => $trimestre4,

            "turmas" => Turma::when($request->ano_lectivos_id, function ($query, $value) {
                $query->where('ano_lectivos_id', $value);
            })->where('status', '=', 'activo')->get(),

            /*"anolectivos" => AnoLectivo::where([
                ['shcools_id', '=', $this->escolarLogada()]
            ])->get(),*/

            "ano_lectivos" => AnoLectivoGlobal::get(),
            "municipios" => Municipio::where('provincia_id', $direccao->provincia_id)->get(),
            "distritos" => Distrito::get(),
            "escolas" => Shcool::where('provincia_id', $direccao->provincia_id)->get(),
            "requests" => $request->all('ano_lectivo_id', 'municipio_id', 'distrito_id', 'turmas_id', 'shcools_id'),
        ];

        return view('sistema.direccao-provincial.estatistica.index-turma-pautas', $headers);
    }


    public function EstatisticaProvincialTurmasUnicaPdf(Request $request)
    {
        ini_set('memory_limit', '2048M');  // Ajuste para 1024 MB ou outro valor
        $user = auth()->user();

        //if(!$user->can('read: estatistica')){
        //    Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //    return redirect()->back();
        // }

        $direccao = DireccaoProvincia::findOrFail($user->shcools_id);

        // Turmas desta ano selecionado

        $classe_10 = Classe::where('classes', '10ª Classe')->first();
        $classe_11 = Classe::where('classes', '11ª Classe')->first();
        $classe_12 = Classe::where('classes', '12ª Classe')->first();

        $classe_7 = Classe::where('classes', '7ª Classe')->first();
        $classe_8 = Classe::where('classes', '8ª Classe')->first();
        $classe_9 = Classe::where('classes', '9ª Classe')->first();

        $turma = Turma::with(['curso', 'turno', 'classe', 'anolectivo'])->find($request->turmas_id);

        // turma anterio 
        $turma_anterior = null;
        $turma_antes_anterior = null;

        $ano_lectivo = AnoLectivo::find($request->ano_lectivo_id);

        if ($ano_lectivo) {
            $ano_lectivo_anterior = AnoLectivo::find($this->anolectivoAnteriorId($ano_lectivo->id ?? 0));
            $ano_lectivo_antes_anterior = AnoLectivo::find($this->anolectivoAntesAnteriorId($ano_lectivo->id ?? 0));

            $trimestre1 = Trimestre::where('trimestre', 'Iª Trimestre')->first();
            $trimestre2 = Trimestre::where('trimestre', 'IIª Trimestre')->first();
            $trimestre3 = Trimestre::where('trimestre', 'IIIª Trimestre')->first();
            $trimestre4 = Trimestre::where('trimestre', 'Geral')->first();
        }


        $disciplinas = null;
        $estudantes = null;
        $disciplinas_ids = "";


        if ($turma && $ano_lectivo) {

            $disciplinas = DisciplinaTurma::with(['disciplina'])->where('turmas_id', $turma->id)->get();
            $disciplinas_ids = DisciplinaTurma::with(['disciplina'])->where('turmas_id', $turma->id)->pluck('disciplinas_id');
            $estudantes = EstudantesTurma::with(['estudante'])->where('turmas_id', $turma->id)->get();

            // verificar se a turma já é 10classe
            if ($turma->classes_id != $classe_10->id && $turma->classe->classes == '11ª Classe') {
                $turma_anterior = Turma::with(['curso', 'turno', 'classe', 'anolectivo'])
                    ->where('ano_lectivos_id', $this->anolectivoAnteriorId($ano_lectivo->id))
                    ->where('shcools_id', $request->shcools_id)
                    ->where('cursos_id', $turma->cursos_id)
                    ->where('classes_id', $classe_10->id)
                    ->first();
            }

            if ($turma->classes_id != $classe_11->id && $turma->classe->classes == '12ª Classe') {
                $turma_anterior = Turma::with(['curso', 'turno', 'classe', 'anolectivo'])
                    ->where('ano_lectivos_id', $this->anolectivoAnteriorId($ano_lectivo->id))
                    ->where('shcools_id', $request->shcools_id)
                    ->where('cursos_id', $turma->cursos_id)
                    ->where('classes_id', $classe_11->id)
                    ->first();

                if ($turma_anterior) {
                    $turma_antes_anterior = Turma::with(['curso', 'turno', 'classe', 'anolectivo'])
                        ->where('ano_lectivos_id', $this->anolectivoAntesAnteriorId($ano_lectivo->id))
                        ->where('shcools_id', $request->shcools_id)
                        ->where('cursos_id', $turma_anterior->cursos_id)
                        ->where('classes_id', $classe_10->id)
                        ->first();
                }
            }

            if ($turma->classes_id != $classe_7->id && $turma->classe->classes == '8ª Classe') {
                $turma_anterior = Turma::with(['curso', 'turno', 'classe', 'anolectivo'])
                    ->where('ano_lectivos_id', $this->anolectivoAnteriorId($ano_lectivo->id))
                    ->where('shcools_id', $request->shcools_id)
                    ->where('cursos_id', $turma->cursos_id)
                    ->where('classes_id', $classe_7->id)
                    ->first();
            }

            if ($turma->classes_id != $classe_8->id && $turma->classe->classes == '9ª Classe') {
                $turma_anterior = Turma::with(['curso', 'turno', 'classe', 'anolectivo'])
                    ->where('ano_lectivos_id', $this->anolectivoAnteriorId($ano_lectivo->id))
                    ->where('shcools_id', $request->shcools_id)
                    ->where('cursos_id', $turma->cursos_id)
                    ->where('classes_id', $classe_8->id)
                    ->first();

                if ($turma_anterior) {
                    $turma_antes_anterior = Turma::with(['curso', 'turno', 'classe', 'anolectivo'])
                        ->where('ano_lectivos_id', $this->anolectivoAntesAnteriorId($ano_lectivo->id))
                        ->where('shcools_id', $request->shcools_id)
                        ->where('cursos_id', $turma_anterior->cursos_id)
                        ->where('classes_id', $classe_7->id)
                        ->first();
                }
            }
        }


        $disciplinas_anterior = null;
        $disciplinas_antes_anterior = null;

        $disciplinas_anterior_ids = null;
        $disciplinas_antes_anterior_ids = null;

        $estudantes_anterior = null;
        $disciplinas_anterior_eliminadas = null;
        $disciplinas_actuais_e_anterior = null;
        $disciplinas_actuais_novas = null;

        $disciplinas_terceira_segundo_primeira_turma = null;
        $disciplinas_terceira_turma_actual = null;

        $disciplinas_eliminadas_segunda_turma = null;
        $disciplinas_eliminadas_primeira_turma = null;
        $disciplinas_eliminadas_primeira_segunda_turma = null;
        $disciplinas_segunda_terceira_turma_actual = null;
        $disciplinas_primeira_turma_actual = null;


        if ($turma_anterior && $turma_anterior->classes_id != $turma->classes_id) {

            $disciplinas_anterior = DisciplinaTurma::with(['disciplina'])->where('turmas_id', $turma_anterior->id)->get();

            $disciplinas_anterior_ids = DisciplinaTurma::with(['disciplina'])->where('turmas_id', $turma_anterior->id)->pluck('disciplinas_id');

            // disciplinas anterior que já não fazem parte da classe actual -------- as disciplinas eliminadas são aquelas que não fazem mais parte das disciplinas deste ano
            $disciplinas_anterior_eliminadas = DisciplinaTurma::whereNotIn('disciplinas_id', $disciplinas_ids)->with(['disciplina'])->where('turmas_id', $turma_anterior->id)->get();

            // disciplinas novas que não constam ou não fazem parte das disciplinas anterior somente actual
            $disciplinas_actuais_novas = DisciplinaTurma::whereNotIn('disciplinas_id', $disciplinas_anterior_ids)->with(['disciplina'])->where('turmas_id', $turma->id)->get();

            $disciplinas_actuais_e_anterior = DisciplinaTurma::whereIn('disciplinas_id', $disciplinas_anterior_ids)->with(['disciplina'])->where('turmas_id', $turma->id)->get();

            $estudantes_anterior = EstudantesTurma::with(['estudante'])->where('turmas_id', $turma_anterior->id)->get();
        }

        if ($turma_antes_anterior && $turma_antes_anterior->classes_id != $turma_anterior->classes_id) {

            $disciplinas_antes_anterior = DisciplinaTurma::with(['disciplina'])->where('turmas_id', $turma_antes_anterior->id)->get();
            $disciplinas_antes_anterior_ids = DisciplinaTurma::with(['disciplina'])->where('turmas_id', $turma_antes_anterior->id)->pluck('disciplinas_id');


            $disciplinas_terceira_segundo_primeira_turma = DisciplinaTurma::whereIn('disciplinas_id', $disciplinas_anterior_ids)->whereIn('disciplinas_id', $disciplinas_antes_anterior_ids)->with(['disciplina'])->where('turmas_id', $turma->id)->get();

            // disciplinas novas que não constam ou não fazem parte das disciplinas anterior nem no antes anterior somente actual
            $disciplinas_terceira_turma_actual = DisciplinaTurma::whereNotIn('disciplinas_id', $disciplinas_anterior_ids)->whereNotIn('disciplinas_id', $disciplinas_antes_anterior_ids)->with(['disciplina'])->where('turmas_id', $turma->id)->get();

            // disciplinas que tive na 7 e não tive na 8 nem na 9
            $disciplinas_primeira_turma_actual = DisciplinaTurma::whereNotIn('disciplinas_id', $disciplinas_anterior_ids)->whereIn('disciplinas_id', $disciplinas_antes_anterior_ids)->with(['disciplina'])->where('turmas_id', $turma->id)->get();

            // disciplinas que so tive na 8 e estou a dar continuidade com 9
            $disciplinas_segunda_terceira_turma_actual = DisciplinaTurma::whereIn('disciplinas_id', $disciplinas_anterior_ids)->whereNotIn('disciplinas_id', $disciplinas_antes_anterior_ids)->with(['disciplina'])->where('turmas_id', $turma->id)->get();

            // disciplinas anterior que já não fazem parte da classe actual -------- as disciplinas eliminadas são aquelas que não fazem mais parte das disciplinas deste ano
            $disciplinas_eliminadas_segunda_turma = DisciplinaTurma::whereNotIn('disciplinas_id', $disciplinas_ids)->with(['disciplina'])->where('turmas_id', $turma_anterior->id)->get();
            $disciplinas_eliminadas_primeira_turma = DisciplinaTurma::whereNotIn('disciplinas_id', $disciplinas_ids)->with(['disciplina'])->where('turmas_id', $turma_antes_anterior->id)->get();
        }

        $headers = [
            "usuario" => $user,

            // classe actual
            "disciplinas" => $disciplinas,
            "estudantes" => $estudantes,
            "turma" => $turma,

            // classe anterior
            "turma_anterior" => $turma_anterior,
            "disciplinas_anterior" => $disciplinas_anterior,

            "turma_antes_anterior" => $turma_antes_anterior,
            "disciplinas_antes_anterior" => $disciplinas_antes_anterior,


            //listagem das disciplinas

            "disciplinas_anterior_eliminadas" => $disciplinas_anterior_eliminadas,
            "disciplinas_actuais_novas" => $disciplinas_actuais_novas,
            "disciplinas_actuais_e_anterior" => $disciplinas_actuais_e_anterior,

            "disciplinas_terceira_segundo_primeira_turma" => $disciplinas_terceira_segundo_primeira_turma,
            "disciplinas_terceira_turma_actual" => $disciplinas_terceira_turma_actual,
            "disciplinas_eliminadas_segunda_turma" => $disciplinas_eliminadas_segunda_turma,
            "disciplinas_eliminadas_primeira_turma" => $disciplinas_eliminadas_primeira_turma,


            "ano_lectivo" => $ano_lectivo,
            "ano_lectivo_anterior" => $ano_lectivo_anterior,
            "ano_lectivo_antes_anterior" => $ano_lectivo_antes_anterior,

            "trimestre1" => $trimestre1,
            "trimestre2" => $trimestre2,
            "trimestre3" => $trimestre3,
            "trimestre4" => $trimestre4,

            "turmas" => Turma::when($request->ano_lectivos_id, function ($query, $value) {
                $query->where('ano_lectivos_id', $value);
            })->where('status', '=', 'activo')->get(),

            "ano_lectivos" => AnoLectivoGlobal::get(),
            "municipios" => Municipio::where('provincia_id', $direccao->provincia_id)->get(),
            "distritos" => Distrito::get(),
            "escolas" => Shcool::where('provincia_id', $direccao->provincia_id)->get(),
            "requests" => $request->all('ano_lectivo_id', 'municipio_id', 'distrito_id', 'turmas_id', 'shcools_id'),
            "escola" => $direccao,
        ];


        $orintacao = 'landscape';

        $pdf = \PDF::loadView('downloads.relatorios.planificacao-pautas-gerais', $headers)->setPaper('A0', $orintacao);
        return $pdf->stream('planificacao-pautas-gerais.pdf');
    }
}
