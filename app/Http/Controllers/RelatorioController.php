<?php

namespace App\Http\Controllers;

use App\Exports\ListaCandidaturaExport;
use App\Exports\ListaMatriculaExport;
use App\Models\Shcool;
use App\Models\User;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\calendarios\Matricula;
use App\Models\web\classes\AnoLectivoClasse;
use App\Models\web\classes\Classe;
use App\Models\web\cursos\AnoLectivoCurso;
use App\Models\web\cursos\Curso;
use App\Models\web\estudantes\Estudante;
use App\Models\web\salas\AnoLectivoSala;
use App\Models\web\salas\Sala;
use App\Models\web\turmas\DisciplinaTurma;
use App\Models\web\turmas\EstudantesTurma;
use App\Models\web\turmas\Turma;
use App\Models\web\turnos\AnoLectivoTurno;
use App\Models\web\turnos\Turno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;

class RelatorioController extends Controller
{
    use TraitHelpers;
    use TraitHeader;

        
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * estudantes geral
     */
    public function relatoriosEstudantes(Request $requset)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
        

        $estudantes = Estudante::when($requset->cursos_id, function($query, $value){
            $query->where('tb_matriculas.cursos_id', $value);
        })
        ->when($requset->turnos_id, function($query, $value){
            $query->where('tb_matriculas.turnos_id', $value);
        })
        ->when($requset->classes_id, function($query, $value){
            $query->where('tb_matriculas.classes_id', $value);
        })
        ->when($requset->genero, function($query, $value){
            $query->where('tb_estudantes.genero', $value);
        })
        ->where('tb_estudantes.shcools_id', $this->escolarLogada())
        ->join('tb_matriculas', 'tb_estudantes.id', '=', 'tb_matriculas.id')
        ->join('tb_turnos', 'tb_matriculas.turnos_id', '=', 'tb_turnos.id')
        ->join('tb_classes', 'tb_matriculas.classes_id', '=', 'tb_classes.id')
        ->join('tb_cursos', 'tb_matriculas.cursos_id', '=', 'tb_cursos.id')
        ->select('tb_estudantes.nascimento', 'tb_estudantes.genero','tb_estudantes.nome', 'tb_estudantes.sobre_nome', 'tb_estudantes.id', 'tb_classes.classes', 'tb_cursos.curso', 'tb_turnos.turno')
        ->get();

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
            "titulo" => "Relatórios",
            "descricao" => "Estudante",
            
            "usuario" => User::findOrFail(Auth::user()->id),
            "turmas" => Turma::where('shcools_id', $this->escolarLogada())->get(),
            "classes_list" => $classes,
            "cursos_list" => $cursos,
            "salas_list" => $salas,
            "turnos_list" => $turnos,
            "estudantes" => $estudantes,

            "requests" => $requset->all('cursos_id', 'classes_id', 'turnos_id', 'genero')

        ];

        return view('admin.relatorios.estudantes', $headers);
    }

    /**
     * relatorios de matriculas
     */
    public function relatoriosMatriculas(Request $requset)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
        

        $estudantes = Matricula::when($requset->cursos_id, function($query, $value){
            $query->where('cursos_id', $value);
        })
        ->when($requset->turnos_id, function($query, $value){
            $query->where('turnos_id', $value);
        })
        ->when($requset->classes_id, function($query, $value){
            $query->where('classes_id', $value);
        })
        ->when($requset->status, function($query, $value){
            $query->where('status_matricula', $value);
        })
        ->where('tipo', 'matricula')
        ->where('shcools_id', $this->escolarLogada())
        ->with('classe')
        ->with('turno')
        ->with('curso')
        ->with('estudante')
        ->get();

        $classes = AnoLectivoClasse::where([
            ['ano_lectivos_id', $this->anolectivoActivo()],
            ['shcools_id', $this->escolarLogada()],
        ])
        ->with('classe')
        ->get();

        $turnos = AnoLectivoTurno::where([
            ['ano_lectivos_id', $this->anolectivoActivo()],
            ['shcools_id', $this->escolarLogada()],
        ])
        ->with('turno')
        ->get();

        $salas = AnoLectivoSala::where([
            ['ano_lectivos_id', $this->anolectivoActivo()],
            ['shcools_id', $this->escolarLogada()],
        ])
        ->with('sala')
        ->get();

        $cursos = AnoLectivoCurso::where([
            ['ano_lectivos_id', $this->anolectivoActivo()],
            ['shcools_id', $this->escolarLogada()],
        ])
        ->with('curso')
        ->get();
              
        $headers = [ 
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Relatórios de Matriculas",
            "descricao" => env('APP_NAME'),
            
            "usuario" => User::findOrFail(Auth::user()->id),
            "turmas" => Turma::where('shcools_id', $this->escolarLogada())->get(),
            "classes_list" => $classes,
            "cursos_list" => $cursos,
            "salas_list" => $salas,
            "turnos_list" => $turnos,
            "estudantes" => $estudantes,
            "requests" => $requset->all('cursos_id', 'classes_id', 'turnos_id', 'status')
        ];

        return view('admin.relatorios.matriculas', $headers);
    }

    public function relatoriosMatriculasExcel(Request $request)
    {
        if($request->cursos_id == null){ $request->cursos_id = ""; }
        if($request->classes_id == null){ $request->classes_id = ""; }
        if($request->turnos_id == null){ $request->turnos_id = ""; }
        if($request->status == null){ $request->status = ""; }

        return Excel::download(new ListaMatriculaExport($request->cursos_id, $request->classes_id, $request->turnos_id, $request->status), 'lista-matricula-geral.xlsx');
    }

    /**
     * candidatura inscrições
     */
    public function relatoriosCandidaturaInscricao(Request $requset)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
        

        $estudantes = Matricula::where('shcools_id', $this->escolarLogada())
        ->when($requset->cursos_id, function($query, $value){
            $query->where('cursos_id', $value);
        })
        ->when($requset->turnos_id, function($query, $value){
            $query->where('turnos_id', $value);
        })
        ->when($requset->classes_id, function($query, $value){
            $query->where('classes_id', $value);
        })
        ->when($requset->status, function($query, $value){
            $query->where('status_matricula', $value);
        })
        ->where('tipo', 'candidatura')
        ->with('classe')
        ->with('turno')
        ->with('curso')
        ->with('estudante')
        ->get();

        $classes = AnoLectivoClasse::where([
            ['ano_lectivos_id', $this->anolectivoActivo()],
            ['shcools_id', $this->escolarLogada()],
        ])
        ->with('classe')
        ->get();

        $turnos = AnoLectivoTurno::where([
            ['ano_lectivos_id', $this->anolectivoActivo()],
            ['shcools_id', $this->escolarLogada()],
        ])
        ->with('turno')
        ->get();

        $salas = AnoLectivoSala::where([
            ['ano_lectivos_id', $this->anolectivoActivo()],
            ['shcools_id', $this->escolarLogada()],
        ])
        ->with('sala')
        ->get();

        $cursos = AnoLectivoCurso::where([
            ['ano_lectivos_id', $this->anolectivoActivo()],
            ['shcools_id', $this->escolarLogada()],
        ])
        ->with('curso')
        ->get();
              
        $headers = [ "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Relatórios",
            "descricao" => "Candidaturas",
            
            "usuario" => User::findOrFail(Auth::user()->id),
            "turmas" => Turma::where('shcools_id', $this->escolarLogada())->get(),
            "classes_list" => $classes,
            "cursos_list" => $cursos,
            "salas_list" => $salas,
            "turnos_list" => $turnos,
            "estudantes" => $estudantes,
            "requests" => $requset->all('cursos_id', 'classes_id', 'turnos_id', 'status')
        ];

        return view('admin.relatorios.candidatura-inscricao', $headers);
    }
 
    public function relatoriosCandidaturaInscricaoExcel(Request $request)
    {
        if($request->cursos_id == null){ $request->cursos_id = ""; }
        if($request->classes_id == null){ $request->classes_id = ""; }
        if($request->turnos_id == null){ $request->turnos_id = ""; }
        if($request->genero == null){ $request->genero = ""; }

        return Excel::download(new ListaCandidaturaExport($request->cursos_id, $request->classes_id, $request->turnos_id, $request->genero), 'lista-candidatura-geral.xlsx');
    }

    // relatorios
    public function relatoriosApp()
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
        $escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->findOrFail($this->escolarLogada());
        

              
        $headers = [ "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Relatórios",
            "descricao" => env('APP_NAME'),
            
            "escola" => $escola,
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.relatorios.home', $headers);
    }
 
    public function relatoriosTurmasApp(Request $request)
    {
        $escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->findOrFail($this->escolarLogada());
        

        $turmas = Turma::when($request->ano_lectivos_id, function ($query, $value) {
           $query->where('ano_lectivos_id', $value);
        })
            ->where('shcools_id', $this->escolarLogada())
            ->with(['turno'])
            ->with(['classe'])
            ->with(['sala'])
            ->with(['curso'])
        ->get();

        $headers = [ 
            
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Relatórios das Turmas",
            "anos" => AnoLectivo::where('shcools_id', $this->escolarLogada())->get(),
            "descricao" => env('APP_NAME'),
            
            "escola" => $escola,
            "usuario" => User::findOrFail(Auth::user()->id),
            "turmas" => $turmas,
            "requests" => $request->all('ano_lectivos_id')
        ];

        return view('admin.relatorios.turmas', $headers);
    }

    public function relatoriosCursosApp()
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
        $escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->findOrFail($this->escolarLogada());

        

        $cursos = Curso::where('shcools_id', $this->escolarLogada())->get();

        $headers = [ "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Relatórios dos Cursos",
            "descricao" => env('APP_NAME'),
            
            "escola" => $escola,
            "usuario" => User::findOrFail(Auth::user()->id),
            "cursos" => $cursos
        ];

        return view('admin.relatorios.cursos', $headers);
    }
    
    public function relatoriosTurnosApp()
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
        $escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->findOrFail($this->escolarLogada());
        

        $turnos = Turno::where('shcools_id', $this->escolarLogada())->get();

        $headers = [ "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Relatórios dos Turnos",
            "descricao" => env('APP_NAME'),
            
            "escola" => $escola,
            "usuario" => User::findOrFail(Auth::user()->id),
            "turnos" => $turnos
        ];

        return view('admin.relatorios.turnos', $headers);
    }

    public function relatoriosClassesApp()
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
        $escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->findOrFail($this->escolarLogada());
        

        $classes = Classe::where('shcools_id', $this->escolarLogada())->get();

        $headers = [ "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Relatórios das Classes",
            "descricao" => env('APP_NAME'),
            
            "escola" => $escola,
            "usuario" => User::findOrFail(Auth::user()->id),
            "classes" => $classes
        ];
        
        return view('admin.relatorios.classes', $headers);
    }



    public function listaEstudantesTurma($id)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO

        $turma = Turma::findOrFail(Crypt::decrypt($id));

        
        
        $headers = [ 
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "turma" => $turma,
            "escola" => Shcool::find($this->escolarLogada()),
            "anolectivo" => AnoLectivo::find($this->anolectivoActivo()),
            "curso" => Curso::findOrFail($turma->cursos_id),
            "sala" => Sala::findOrFail($turma->salas_id),
            "turno" => Turno::findOrFail($turma->turnos_id),
            "classe" => Classe::findOrFail($turma->classes_id),
            "estudantes" => EstudantesTurma::where([
                ['status', 'activo'],
                ['turmas_id', $turma->id],
            ])->get(),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('relatorios.listar-estudantes-turma', $headers);
    }

    public function listaDisciplinasTurma($id)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO


        $turma = Turma::findOrFail($id);

        

        
        $headers = [ "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "usuario" => User::findOrFail(Auth::user()->id),
            "turma" => $turma,
            "escola" => Shcool::find($this->escolarLogada()),
            "anolectivo" => AnoLectivo::find($this->anolectivoActivo()),
            "curso" => Curso::findOrFail($turma->cursos_id),
            "sala" => Sala::findOrFail($turma->salas_id),
            "turno" => Turno::findOrFail($turma->turnos_id),
            "classe" => Classe::findOrFail($turma->classes_id),
            "disciplinas" => DisciplinaTurma::where([
                ['status', '=', 'activo'],
                ['turmas_id', '=', $turma->id],
            ])->get()
        ];


        return view('relatorios.listar-disciplinas-turma', $headers);
    }

    public function listaEstudantesCurso($id)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO


        $curso = Curso::findOrFail($id);

        

        
        $headers = [ "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "usuario" => User::findOrFail(Auth::user()->id),
            "matriculas" => Matricula::where([
                ['cursos_id', '=', $curso->id],
                ['status_matricula', '=', 'confirmado'],
                ['shcools_id', '=', $this->escolarLogada()],
            ])->get(),
            "curso" => $curso,
            "escola" => Shcool::find($this->escolarLogada()),
            "anolectivo" => AnoLectivo::find($this->anolectivoActivo())
        ];
       
        return view('relatorios.listar-estudantes-curso', $headers);
    }

    public function listaEstudantesclasse($id)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO

        $classe = Classe::findOrFail($id);

        

        
        $headers = [ "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "usuario" => User::findOrFail(Auth::user()->id),
            "matriculas" => Matricula::where([
                ['classes_id', '=', $classe->id],
                ['status_matricula', '=', 'confirmado'],
                ['shcools_id', '=', $this->escolarLogada()],
            ])->get(),
            "classe" => $classe,
            "escola" => Shcool::find($this->escolarLogada()),
            "anolectivo" => AnoLectivo::find($this->anolectivoActivo())
        ];

        return view('relatorios.listar-estudantes-classe', $headers);
    }
    
    public function listaEstudantesTurno($id)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO

        $turno = Turno::findOrFail($id);

        
        
        $headers = [ "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "usuario" => User::findOrFail(Auth::user()->id),
            "matriculas" => Matricula::where([
                ['turnos_id', '=', $turno->id],
                ['status_matricula', '=', 'confirmado'],
                ['shcools_id', '=', $this->escolarLogada()],
            ])->get(),
            "turno" => $turno,
            "escola" => Shcool::find($this->escolarLogada()),
            "anolectivo" => AnoLectivo::find($this->anolectivoActivo())
        ];
        
        return view('relatorios.listar-estudantes-turno', $headers);
    }  
       
    public function listaEstudantesNovos()
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO

        
        

        
        $headers = [ "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "usuario" => User::findOrFail(Auth::user()->id),
            "matriculas" => Matricula::where([
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['status_matricula', '=', 'confirmado'],
                ['status', '=', 'Novo'],
                ['shcools_id', '=', $this->escolarLogada()],
            ])->get(),
            "escola" => Shcool::find($this->escolarLogada()),
            "anolectivo" => AnoLectivo::find($this->anolectivoActivo())
        ];

        return view('relatorios.listar-estudantes-novos', $headers);
    }  

    public function listaEstudantesRepitentes()
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO


        

        
        $headers = [ "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "matriculas" => Matricula::where([
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['status', '=', 'Repitente'],
                ['status_matricula', '=', 'confirmado'],
                ['shcools_id', '=', $this->escolarLogada()],
            ])->get(),
            "escola" => Shcool::find($this->escolarLogada()),
            "anolectivo" => AnoLectivo::find($this->anolectivoActivo()),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('relatorios.listar-estudantes-repitentes', $headers);
    }  


}
