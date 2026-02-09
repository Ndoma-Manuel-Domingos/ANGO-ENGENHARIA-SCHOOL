<?php

namespace App\Http\Controllers;

use App\Models\EnsinoClasse;
use App\Models\Escolaridade;
use App\Models\FormacaoAcedemico;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\User;
use App\Models\Shcool;
use App\Models\web\calendarios\Matricula;
use App\Models\web\classes\Classe;
use App\Models\web\turmas\FuncionariosTurma;
use App\Models\web\turmas\Turma;
use App\Models\web\turnos\AnoLectivoTurno;
use Illuminate\Support\Facades\DB;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class FormularioController extends Controller
{
    //
    use TraitHelpers;
    use TraitHeader;


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function iniciacao(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('formulario iniciacao')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $estudante_matriculados = Matricula::select(
            // estudantes matriculados com  5 anos
            DB::raw('SUM(CASE WHEN tb_ensinos_classes.nome = "Iniciação" AND tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 5 THEN 1 ELSE 0 END) AS matriculados_masculino_5_anos'),
            DB::raw('SUM(CASE WHEN tb_ensinos_classes.nome = "Iniciação" AND tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 5 THEN 1 ELSE 0 END) AS matriculados_feminino_5_anos'),

            // estudantes matriculados com  6 anos
            DB::raw('SUM(CASE WHEN tb_ensinos_classes.nome = "Iniciação" AND tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 6 THEN 1 ELSE 0 END) AS matriculados_masculino_6_anos'),
            DB::raw('SUM(CASE WHEN tb_ensinos_classes.nome = "Iniciação" AND tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 6 THEN 1 ELSE 0 END) AS matriculados_feminino_6_anos'),

            // estudantes repetentes com  6 anos
            DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_ensinos_classes.nome = "Iniciação" AND tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 6 THEN 1 ELSE 0 END) AS repitentes_masculino_6_anos'),
            DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_ensinos_classes.nome = "Iniciação" AND tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 6 THEN 1 ELSE 0 END) AS repitentes_feminino_6_anos'),

            // estudantes matriculados com  7 anos
            DB::raw('SUM(CASE WHEN tb_ensinos_classes.nome = "Iniciação" AND tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 7 THEN 1 ELSE 0 END) AS matriculados_masculino_7_anos'),
            DB::raw('SUM(CASE WHEN tb_ensinos_classes.nome = "Iniciação" AND tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 7 THEN 1 ELSE 0 END) AS matriculados_feminino_7_anos'),

            // estudantes repetentes com  7 anos
            DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_ensinos_classes.nome = "Iniciação" AND tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 7 THEN 1 ELSE 0 END) AS repitentes_masculino_7_anos'),
            DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_ensinos_classes.nome = "Iniciação" AND tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 7 THEN 1 ELSE 0 END) AS repitentes_feminino_7_anos'),

            // estudantes matriculados com  8 anos
            DB::raw('SUM(CASE WHEN tb_ensinos_classes.nome = "Iniciação" AND tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 8 THEN 1 ELSE 0 END) AS matriculados_masculino_8_anos'),
            DB::raw('SUM(CASE WHEN tb_ensinos_classes.nome = "Iniciação" AND tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 8 THEN 1 ELSE 0 END) AS matriculados_feminino_8_anos'),

            // estudantes repetentes com  8 anos
            DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_ensinos_classes.nome = "Iniciação" AND tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 8 THEN 1 ELSE 0 END) AS repitentes_masculino_8_anos'),
            DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_ensinos_classes.nome = "Iniciação" AND tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 8 THEN 1 ELSE 0 END) AS repitentes_feminino_8_anos'),


            // estudantes matriculados com  9 anos
            DB::raw('SUM(CASE WHEN tb_ensinos_classes.nome = "Iniciação" AND tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 9 THEN 1 ELSE 0 END) AS matriculados_masculino_9_anos'),
            DB::raw('SUM(CASE WHEN tb_ensinos_classes.nome = "Iniciação" AND tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 9 THEN 1 ELSE 0 END) AS matriculados_feminino_9_anos'),

            // estudantes repetentes com  9 anos
            DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_ensinos_classes.nome = "Iniciação" AND tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 9 THEN 1 ELSE 0 END) AS repitentes_masculino_9_anos'),
            DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_ensinos_classes.nome = "Iniciação" AND tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 9 THEN 1 ELSE 0 END) AS repitentes_feminino_9_anos'),

            // estudantes matriculados com  10 ou mais anos
            DB::raw('SUM(CASE WHEN tb_ensinos_classes.nome = "Iniciação" AND tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 10 AND 11 THEN 1 ELSE 0 END) AS matriculados_masculino_10_anos'),
            DB::raw('SUM(CASE WHEN tb_ensinos_classes.nome = "Iniciação" AND tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 10 AND 11 THEN 1 ELSE 0 END) AS matriculados_feminino_10_anos'),


            // estudantes repetentes com  10 ou mais anos
            DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_ensinos_classes.nome = "Iniciação" AND tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 10 AND 11 THEN 1 ELSE 0 END) AS repitentes_masculino_10_anos'),
            DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_ensinos_classes.nome = "Iniciação" AND tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 10 AND 11 THEN 1 ELSE 0 END) AS repitentes_feminino_10_anos'),


            DB::raw('COUNT(*) AS total'),
            DB::raw('SUM(CASE WHEN tb_ensinos_classes.nome = "Iniciação" AND tb_estudantes.genero = "Masculino" THEN 1 ELSE 0 END) AS matriculados_masculino'),
            DB::raw('SUM(CASE WHEN tb_ensinos_classes.nome = "Iniciação" AND tb_estudantes.genero = "Femenino" THEN 1 ELSE 0 END) AS matriculados_feminino'),

            DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_ensinos_classes.nome = "Iniciação" AND tb_estudantes.genero = "Masculino" THEN 1 ELSE 0 END) AS repitentes_masculino'),
            DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_ensinos_classes.nome = "Iniciação" AND tb_estudantes.genero = "Femenino" THEN 1 ELSE 0 END) AS repitentes_feminino'),

            // Auditiva
            // Visual
            // Motora
            // Ontras

            // // estudantes com definciencias Visual
            DB::raw('SUM(CASE WHEN tb_estudantes.dificiencia = "Visual" AND tb_ensinos_classes.nome = "Iniciação" AND tb_estudantes.genero = "Masculino" THEN 1 ELSE 0 END) AS estudante_dificiencia_visual_masculino'),
            DB::raw('SUM(CASE WHEN tb_estudantes.dificiencia = "Visual" AND tb_ensinos_classes.nome = "Iniciação" AND tb_estudantes.genero = "Femenino" THEN 1 ELSE 0 END) AS estudante_dificiencia_visual_feminino'),

            // estudantes com definciencias Auditiva
            DB::raw('SUM(CASE WHEN tb_estudantes.dificiencia = "Auditiva" AND tb_ensinos_classes.nome = "Iniciação" AND tb_estudantes.genero = "Masculino" THEN 1 ELSE 0 END) AS estudante_dificiencia_auditiva_masculino'),
            DB::raw('SUM(CASE WHEN tb_estudantes.dificiencia = "Auditiva" AND tb_ensinos_classes.nome = "Iniciação" AND tb_estudantes.genero = "Femenino" THEN 1 ELSE 0 END) AS estudante_dificiencia_auditiva_feminino'),

            // estudantes com definciencias Motora
            DB::raw('SUM(CASE WHEN tb_estudantes.dificiencia = "Motora" AND tb_ensinos_classes.nome = "Iniciação" AND tb_estudantes.genero = "Masculino" THEN 1 ELSE 0 END) AS estudante_dificiencia_motora_masculino'),
            DB::raw('SUM(CASE WHEN tb_estudantes.dificiencia = "Motora" AND tb_ensinos_classes.nome = "Iniciação" AND tb_estudantes.genero = "Femenino" THEN 1 ELSE 0 END) AS estudante_dificiencia_motora_feminino'),

            // estudantes com definciencias Outras
            DB::raw('SUM(CASE WHEN tb_estudantes.dificiencia = "Outras" AND tb_ensinos_classes.nome = "Iniciação" AND tb_estudantes.genero = "Masculino" THEN 1 ELSE 0 END) AS estudante_dificiencia_outras_masculino'),
            DB::raw('SUM(CASE WHEN tb_estudantes.dificiencia = "Outras" AND tb_ensinos_classes.nome = "Iniciação" AND tb_estudantes.genero = "Femenino" THEN 1 ELSE 0 END) AS estudante_dificiencia_outras_feminino'),


            DB::raw('(SUM(CASE WHEN tb_ensinos_classes.nome = "Iniciação" AND tb_estudantes.genero = "Masculino" THEN 1 ELSE 0 END) * 100 / COUNT(*)) AS percentual_masculino'),
            DB::raw('(SUM(CASE WHEN tb_ensinos_classes.nome = "Iniciação" AND tb_estudantes.genero = "Femenino" THEN 1 ELSE 0 END) * 100 / COUNT(*)) AS percentual_feminino'),

        )
            ->whereBetween(DB::raw('TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE())'), [5, 12])
            ->join('tb_estudantes', 'tb_matriculas.estudantes_id', '=', 'tb_estudantes.id')
            ->join('tb_classes', 'tb_matriculas.classes_id', '=', 'tb_classes.id')
            ->join('tb_ensinos_classes', 'tb_classes.ensino_id', '=', 'tb_ensinos_classes.id')
            ->where('tb_estudantes.shcools_id', $this->escolarLogada())
            ->when($request->ano_lectivo_id, function ($query, $value) {
                $query->where('tb_estudantes.ano_lectivos_id', $value);
            })
            ->first();

        $turnos = AnoLectivoTurno::with('turno')->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->get();

        $ensino = EnsinoClasse::where('nome', 'Iniciação')->first();
        $classes = Classe::where('ensino_id', $ensino->id)->get();
        $classes_ids = Classe::where('ensino_id', $ensino->id)->pluck('id');

        $ids_turmas_da_iniciacao = Turma::whereIn('classes_id', $classes_ids)
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->pluck('id');

        $ids_professores_da_iniciacao = FuncionariosTurma::whereIn('turmas_id', $ids_turmas_da_iniciacao)
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->pluck('funcionarios_id');

        $headers = [
            "titulo" => "Formulário Pré-Escolar(Iniciação)",
            "descricao" => env('APP_NAME'),
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "usuario" => User::findOrFail(Auth::user()->id),

            "turnos" => $turnos,
            "classes" => $classes,

            "escolaridades" => Escolaridade::get(),
            "formacoes" => FormacaoAcedemico::get(),

            "result" => $estudante_matriculados,

            "anolectivoactual" => $this->anolectivoActivo(),

            "ids_professores" => $ids_professores_da_iniciacao,

            "ensino" => $ensino,
        ];

        return view('admin.formularios.iniciacao', $headers);
    }

    public function fichaAFEPIniciacao(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('ficha iniciacao')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $headers = [
            "titulo" => "Ficha AFEP Pré-Escolar(Iniciação)",
            "descricao" => env('APP_NAME'),
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.formularios.ficha-iniciacao', $headers);
    }

    public function primarioRegular(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('formulario primário regular')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $ensino = EnsinoClasse::where('nome', 'Ensino Primário')->first();
        $classes = Classe::where('ensino_id', $ensino->id)->get();


        $turnos = AnoLectivoTurno::with('turno')->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->get();

        $classes_ids = Classe::where('ensino_id', $ensino->id)->pluck('id');

        $ids_turmas_da_iniciacao = Turma::whereIn('classes_id', $classes_ids)
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->pluck('id');

        $ids_professores_da_iniciacao = FuncionariosTurma::whereIn('turmas_id', $ids_turmas_da_iniciacao)
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->pluck('funcionarios_id');

        $headers = [
            "titulo" => "Formulário Primário Regular",
            "descricao" => env('APP_NAME'),
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "usuario" => User::findOrFail(Auth::user()->id),

            "classes" => $classes,
            "turnos" => $turnos,

            "anolectivoactual" => $this->anolectivoActivo(),
            "escolaridades" => Escolaridade::get(),
            "formacoes" => FormacaoAcedemico::get(),
            "ids_professores" => $ids_professores_da_iniciacao,

            "ensino" => $ensino,
        ];

        return view('admin.formularios.primario-regular', $headers);
    }

    public function fichaAFEPEnsinoPrimarioRegular(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('ficha ensino primario regular')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $headers = [
            "titulo" => "Ficha AFEP Pré-Escolar(Iniciação)",
            "descricao" => env('APP_NAME'),
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.formularios.ficha-ensino-primario-regular', $headers);
    }

    public function primarioCicloRegular(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('formulario primário regular')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $ensino = EnsinoClasse::where('nome', 'Iº Ciclo de Ensino secundário')->first();
        $classes = Classe::where('ensino_id', $ensino->id)->get();

        $turnos = AnoLectivoTurno::with('turno')->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->get();

        $classes_ids = Classe::where('ensino_id', $ensino->id)->pluck('id');

        $ids_turmas_da_iniciacao = Turma::whereIn('classes_id', $classes_ids)
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->pluck('id');

        $ids_professores_da_iniciacao = FuncionariosTurma::whereIn('turmas_id', $ids_turmas_da_iniciacao)
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->pluck('funcionarios_id');

        $headers = [
            "titulo" => "Formulário Primário Ciclo Regular",
            "descricao" => env('APP_NAME'),
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "usuario" => User::findOrFail(Auth::user()->id),

            "classes" => $classes,
            "turnos" => $turnos,

            "anolectivoactual" => $this->anolectivoActivo(),
            "escolaridades" => Escolaridade::get(),
            "formacoes" => FormacaoAcedemico::get(),
            "ids_professores" => $ids_professores_da_iniciacao,


            "ensino" => $ensino,
        ];

        return view('admin.formularios.primario-ciclo-regular', $headers);
    }

    public function fichaAFEPEnsinoPrimarioCicloRegular(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('ficha ensino iº ciclo regular')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $headers = [
            "titulo" => "Ficha AFEP Pré-Escolar(Iniciação)",
            "descricao" => env('APP_NAME'),
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.formularios.ficha-ensino-primario-ciclo-regular', $headers);
    }

    public function segundoCicloRegular(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('formulario iiº cliclo regular')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $ensino = EnsinoClasse::where('nome', 'IIº Ciclo de Ensino secundário')->first();
        $classes = Classe::where('ensino_id', $ensino->id)->get();

        $turnos = AnoLectivoTurno::with('turno')->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->get();

        $classes_ids = Classe::where('ensino_id', $ensino->id)->pluck('id');

        $ids_turmas_da_iniciacao = Turma::whereIn('classes_id', $classes_ids)
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->pluck('id');

        $ids_professores_da_iniciacao = FuncionariosTurma::whereIn('turmas_id', $ids_turmas_da_iniciacao)
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->pluck('funcionarios_id');


        $headers = [
            "titulo" => "Formulário Segundo Ciclo Regular",
            "descricao" => env('APP_NAME'),
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "usuario" => User::findOrFail(Auth::user()->id),

            "classes" => $classes,
            "turnos" => $turnos,

            "anolectivoactual" => $this->anolectivoActivo(),
            "escolaridades" => Escolaridade::get(),
            "formacoes" => FormacaoAcedemico::get(),
            "ids_professores" => $ids_professores_da_iniciacao,


            "ensino" => $ensino,
        ];

        return view('admin.formularios.segundo-ciclo-regular', $headers);
    }


    public function fichaAFEPEnsinoSegundoCicloRegular(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('ficha ensino iiº ciclo regular')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $headers = [
            "titulo" => "Ficha AFEP Pré-Escolar(Iniciação)",
            "descricao" => env('APP_NAME'),
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.formularios.ficha-ensino-segundo-ciclo-regular', $headers);
    }

    public function imprimirRelatorioEstudanteDificiencia(Request $request)
    {


        $classes = Classe::where('ensino_id', $request->ensino)->get();
        $ensino = EnsinoClasse::find($request->ensino)->first();


        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            
            "titulo" => "LISTA ESTUDANTES COM DEFICIÊNCIA",

            "anolectivoactual" => $request->ano_lectivo,
            "classes" => $classes,
            "ensino" => $ensino,
        ];

        $pdf = \PDF::loadView('downloads.relatorios.listar-estudantes-dificienca', $headers);
        return $pdf->stream('listar-estudantes-dificienca.pdf');
    }


    public function imprimirRelatorioTurmasTurnos(Request $request)
    {


        $classes = Classe::where('ensino_id', $request->ensino)->get();
        $turnos = AnoLectivoTurno::with('turno')->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $request->ano_lectivo)
            ->get();

        $ensino = EnsinoClasse::find($request->ensino)->first();

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            
            "titulo" => "LISTA DE TURMAS, TURNOS POR CLASSE",

            "anolectivoactual" => $request->ano_lectivo,
            "classes" => $classes,
            "turnos" => $turnos,
            "ensino" => $ensino,
        ];

        $pdf = \PDF::loadView('downloads.relatorios.listar-turmas-turnos-por-classe', $headers);
        return $pdf->stream('listar-estudantes-dificienca.pdf');
    }

    public function imprimirRelatorioProfessorPorNivel(Request $request)
    {


        $ensino = EnsinoClasse::find($request->ensino)->first();

        $classes_ids = Classe::where('ensino_id', $ensino->id)->pluck('id');

        $ids_turmas_da_iniciacao = Turma::whereIn('classes_id', $classes_ids)
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $request->ano_lectivo)
            ->pluck('id');

        $ids_professores_da_iniciacao = FuncionariosTurma::whereIn('turmas_id', $ids_turmas_da_iniciacao)
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $request->ano_lectivo)
            ->pluck('funcionarios_id');

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            
            "titulo" => "LISTA DO PESSOAL DOCENTE",

            "anolectivoactual" => $request->ano_lectivo,
            "ensino" => $ensino,

            "escolaridades" => Escolaridade::get(),
            "formacoes" => FormacaoAcedemico::get(),
            "ids_professores" => $ids_professores_da_iniciacao,
        ];

        $pdf = \PDF::loadView('downloads.relatorios.listar-pessoal-docente-nivel-academico', $headers)->setPaper('A4', 'landscape');
        return $pdf->stream('listar-estudantes-dificienca.pdf');
    }


    public function imprimirRelatorioProfessorPorIdade(Request $request)
    {


        $ensino = EnsinoClasse::find($request->ensino)->first();

        $classes_ids = Classe::where('ensino_id', $ensino->id)->pluck('id');

        $ids_turmas_da_iniciacao = Turma::whereIn('classes_id', $classes_ids)
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $request->ano_lectivo)
            ->pluck('id');

        $ids_professores_da_iniciacao = FuncionariosTurma::whereIn('turmas_id', $ids_turmas_da_iniciacao)
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $request->ano_lectivo)
            ->pluck('funcionarios_id');

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            
            "titulo" => "LISTA DO PESSOAL DOCENTE",

            "anolectivoactual" => $request->ano_lectivo,
            "ensino" => $ensino,

            "escolaridades" => Escolaridade::get(),
            "formacoes" => FormacaoAcedemico::get(),
            "ids_professores" => $ids_professores_da_iniciacao,
        ];

        $pdf = \PDF::loadView('downloads.relatorios.listar-pessoal-docente-por-idade', $headers)->setPaper('A4', 'landscape');
        return $pdf->stream('listar-estudantes-dificienca.pdf');
    }
}
