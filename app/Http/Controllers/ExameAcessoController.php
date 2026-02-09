<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Paise;
use App\Models\Provincia;
use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\calendarios\Matricula;
use App\Models\web\classes\AnoLectivoClasse;
use App\Models\web\cursos\AnoLectivoCurso;
use App\Models\web\salas\AnoLectivoSala;
use App\Models\web\turnos\AnoLectivoTurno;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;


use Illuminate\Support\Facades\Auth;

class ExameAcessoController extends Controller
{
    //
    use TraitHelpers;

    public function __construct()
    {
        $this->middleware('auth');
    }

    // matricula estudantes
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: estudante') && !$user->can('read: matricula')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $matriculas = Matricula::when($request->status, function ($q, $v) {
            $q->where('status_matricula', $v);
        })->when($request->cursos_id, function ($q, $v) {
            $q->where('cursos_id', $v);
        })->when($request->classes_id, function ($q, $v) {
            $q->where('classes_id', $v);
        })->when($request->turnos_id, function ($q, $v) {
            $q->where('turnos_id', $v);
        })->with(
            'ano_lectivo',
            'classe_at',
            'classe',
            'turno',
            'curso',
            'estudante'
        )->where([
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['shcools_id', '=', $this->escolarLogada()],
        ])
            ->where('status_inscricao', 'Admitido')
            ->get();



        $paises = Paise::all();
        $provincias = Provincia::all();

        $classes = AnoLectivoClasse::where([
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['shcools_id', '=', $this->escolarLogada()],
        ])
            ->with('classe')
            ->get();

        $turnos = AnoLectivoTurno::where([
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['shcools_id', '=', $this->escolarLogada()],
        ])
            ->with('turno')
            ->get();

        $salas = AnoLectivoSala::where([
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['shcools_id', '=', $this->escolarLogada()],
        ])
            ->with('sala')
            ->get();

        $cursos = AnoLectivoCurso::where([
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['shcools_id', '=', $this->escolarLogada()],
        ])
            ->with('curso')
            ->get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Exames de Acesso",
            "descricao" => env('APP_NAME'),
                    
            "totalEstudantesExamesAcesso" => Matricula::where('ano_lectivos_id', $this->anolectivoActivo())
                ->where('prova_acesso', 'Y')
                ->where('shcools_id', $this->escolarLogada())
                ->whereIn('status_inscricao', ['Nao Admitido', 'Admitido'])
                ->whereIn('status_matricula', ['nao_confirmado', 'confirmado'])
                ->count(),
                
            "totalEstudantesExamesAcessoFeito" => Matricula::where('ano_lectivos_id', $this->anolectivoActivo())
                ->where('prova_acesso', 'Y')
                ->where('exame_acesso', 'Y')
                ->where('shcools_id', $this->escolarLogada())
                ->whereIn('status_inscricao', ['Nao Admitido', 'Admitido'])
                ->whereIn('status_matricula', ['nao_confirmado', 'confirmado'])
                ->count(),
                
            "totalEstudantesExamesAcessoNaoFeito" => Matricula::where('ano_lectivos_id', $this->anolectivoActivo())
                ->where('prova_acesso', 'Y')
                ->where('exame_acesso', 'N')
                ->where('shcools_id', $this->escolarLogada())
                ->whereIn('status_inscricao', ['Nao Admitido', 'Admitido'])
                ->whereIn('status_matricula', ['nao_confirmado', 'confirmado'])
                ->count(),
            
            "paises" => $paises,
            "provincias" => $provincias,
            "matriculas" => $matriculas,
            "classes" => $classes,
            "turnos" => $turnos,
            "salas" => $salas,
            "cursos" => $cursos,
            "anolectivos" => AnoLectivo::where([
                ['shcools_id', '=', $this->escolarLogada()],
                ['status', '=', 'activo']
            ])->get(),
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            "usuario" => User::findOrFail(Auth::user()->id),
            "filtros" => $request->all('status', 'cursos_id', 'classes_id', 'turnos_id'),
        ];

        return view('admin.exames_acessos.index', $headers);
    }

    public function exameAcesso(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: estudante') && !$user->can('read: matricula')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $idade = $request->idade;

        $matriculas = Matricula::when($request->media, function ($query, $value) {
            $query->where('media', $value);
        })
            ->when($request->cursos_id, function ($query, $value) {
                $query->where('cursos_id', $value);
            })
            ->when($request->classes_id, function ($query, $value) {
                $query->where('classes_id', $value);
            })
            ->when($request->turnos_id, function ($query, $value) {
                $query->where('turnos_id', $value);
            })
            ->when($request->status, function ($query, $value) {
                $query->where('status_inscricao', $value);
            })
            ->whereHas('estudante', function ($query) use ($idade) {
                $query->when($idade, function ($query) use ($idade) {
                    $query->whereRaw("TIMESTAMPDIFF(YEAR, nascimento, NOW()) = ?", [$idade]);
                });
            })
            ->whereIn('tipo', ['inscricao', 'candidatura'])
            ->whereIn('status_inscricao', ['Nao Admitido', 'Admitido'])
            ->whereIn('prova_acesso', ['Y'])
            ->where('ano_lectivos_id', '=', $this->anolectivoActivo())
            ->where('shcools_id', '=', $this->escolarLogada())
            ->with(['escolas', 'ano_lectivo', 'classe_at', 'estudante', 'classe', 'turno', 'curso'])
            ->get()
            ->sortBy(function($matricula) {
                return $matricula->estudante->nome;
            });
        


        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Provas de Exames de Acesso",
            "descricao" => env('APP_NAME'),
            "matriculas" => $matriculas,

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
            "usuario" => User::findOrFail(Auth::user()->id),
            "filtros" => $request->all('status', 'cursos_id', 'classes_id', 'turnos_id', 'media', 'idade'),
        ];


        return view('admin.exames_acessos.home', $headers);
    }

    public function exameAcessoPost(Request $request)
    {
    
        $user = auth()->user();

        if (!$user->can('read: estudante') && !$user->can('read: matricula')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $request->validate([
            "nota" => 'required',
        ], [
            "nota.required" => "Campo Obrigatório",
        ]);         
        
        foreach ($request->estudantes_id as $key) {
        
            $matricula = Matricula::with(['estudante'])->findOrFail($key);
            
            $idade = $matricula->estudante->idade($matricula->estudante->nascimento);
            
            if($request->nota >= 14 && $idade >= 14){
                $matricula->status_inscricao = "Admitido";
            }else {
                $matricula->status_inscricao = "Nao Admitido";
            }
            
            $matricula->media = $request->nota;
            $matricula->exame_acesso = "Y";
            $matricula->update();
        }

        Alert::success("Bom Trabalho", "Dados salvos com sucesso");
        return redirect()->back();
    }

}
