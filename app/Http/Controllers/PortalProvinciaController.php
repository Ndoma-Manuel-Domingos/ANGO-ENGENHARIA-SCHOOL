<?php

namespace App\Http\Controllers;

use App\Models\AnoLectivoGlobal;
use App\Models\Arquivo;
use App\Models\Cargo;
use App\Models\Departamento;
use App\Models\Categoria;
use App\Models\ControloLancamentoNotas;
use App\Models\ControloLancamentoNotasEscolas;
use App\Models\Escolaridade;
use App\Models\Especialidade;
use App\Models\Director;
use App\Models\FormacaoAcedemico;
use App\Models\Universidade;
use App\Models\DireccaoProvincia;
use App\Models\Distrito;
use App\Models\Ensino;
use App\Models\LaboratorioEscola;
use App\Models\Municipio;
use App\Models\Notificacao;
use App\Models\Paise;
use App\Models\Professor;
use App\Models\Provincia;
use App\Models\Shcool;
use App\Models\SolicitacaoProfessor;
use App\Models\User;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\anolectivo\ControlePeriodico;
use App\Models\web\calendarios\Matricula;
use App\Models\web\calendarios\Mes;
use App\Models\web\classes\AnoLectivoClasse;
use App\Models\web\classes\Classe;
use App\Models\web\cursos\AnoLectivoCurso;
use App\Models\web\cursos\Curso;
use App\Models\web\disciplinas\Disciplina;
use App\Models\web\encarregados\EncarregadoEstudantes;
use App\Models\web\estudantes\Estudante;
use App\Models\web\funcionarios\CartaoFuncionario;
use App\Models\web\funcionarios\FuncionariosControto;
use App\Models\web\salas\AnoLectivoSala;
use App\Models\web\salas\Sala;
use App\Models\web\turmas\DisciplinaTurma;
use App\Models\web\turmas\EstudantesTurma;
use App\Models\web\turmas\FuncionariosTurma;
use App\Models\web\turmas\NotaPauta;
use App\Models\web\turmas\Turma;
use App\Models\web\turnos\AnoLectivoTurno;
use App\Models\web\turnos\Turno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Role;


use Illuminate\Support\Facades\Validator;

use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;

class PortalProvinciaController extends Controller
{

    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function home()
    {
        $user = auth()->user();
        
        $direccao = DireccaoProvincia::findOrFail($user->shcools_id);
   
        $total_escola = Shcool::where('status', 'activo')->where('provincia_id', $direccao->provincia_id)->count();
        // as escolas desta provincia
        $escolas = Shcool::where('status', 'activo')->where('provincia_id', $direccao->provincia_id)->get(['id']);
        
        $total_estudante = Estudante::whereIn('shcools_id', $escolas)->where('registro', 'confirmado')->count();
        
        $total_professores_funcional = FuncionariosControto::where('provincia_id', $direccao->provincia_id)->where('status', 'activo')->where('cargo_geral', 'professor')->where('level', '4')->whereIn('shcools_id', $escolas)->count();
        
        $total_professores = Professor::where('level', '4')->count();
        
        $total_funcionario = FuncionariosControto::where('pais_id', $direccao->pais_id)
        ->where('provincia_id', $direccao->provincia_id)
        ->where('municipio_id', $direccao->municipio_id)
        ->where('distrito_id', $direccao->distrito_id)
        ->where('status', 'activo')
        ->where('level', '2')
        ->count();

        $pais = Paise::where('name', 'Angola')->first();
        $municipios = Municipio::where('provincia_id', $direccao->provincia_id)->get();

        $usuario = User::findOrFail(Auth::user()->id);
        
        $solicitacoes = SolicitacaoProfessor::where('status', '0')->with('professor', 'disciplina', 'classe', 'instituicao1', 'curso')->where('level_destino', '2')
        ->where('instituicao_id', $direccao->id)
        ->get();
        
        $headers =  [
            "usuario" => $usuario,
            "total_escola" => $total_escola,
            "total_estudante" => $total_estudante,
            "total_professores" => $total_professores,
            "total_funcionario" => $total_funcionario,
            "total_professores_funcional" => $total_professores_funcional,
            "pais" => $pais,
            "municipios" => $municipios,
            "direccao" => $direccao,
            'solicitacoes' =>  $solicitacoes,
        ];

        return view('sistema.direccao-provincial.home', $headers);
    }
    
    
    public function listagemEscolas(Request $request, $id = null)
    {
        $user = auth()->user();
        
        if(!$user->can('read: escola')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
       
        /* dd(Crypt::decrypt($id)); */
       
        if(Crypt::decrypt($id) == 'null'){
            $id = Crypt::decrypt("");
        }else{
            $id = Crypt::decrypt($id);
        }
         
        $direccao = DireccaoProvincia::findOrFail($user->shcools_id);
        $municipios = Municipio::where('provincia_id', $direccao->provincia_id)->get();
        $ano_lectivos = AnoLectivoGlobal::get();
        $ensinos = Ensino::get();
        $distritos = Distrito::get();
        $municipio = Municipio::find($id);
   
        $escolas = Shcool::when($id, function($query, $value){
            $query->where('municipio_id', $value);
        })
        ->when($request->categoria, function($query, $value){
            $query->where('categoria', $value);
        })
        ->when($request->municipio_id, function($query, $value){
            $query->where('municipio_id', $value);
        })
        ->when($request->distrito_id, function($query, $value){
            $query->where('distrito_id', $value);
        })
        ->when($request->ensino_id, function($query, $value){
            $query->where('ensino_id', $value);
        })
        ->when($request->status, function($query, $value){
            $query->where('status', $value);
        })
        ->where('provincia_id', $direccao->provincia_id)
        ->with('municipio', 'pais', 'ensino')
        ->orderBy('id', 'desc')
        ->get();

        $headers =  [
            "usuario" => $user,
            "escolas" => $escolas,
            "municipio" => $municipio,
            "distritos" => $distritos,
            "municipios" => $municipios,
            "ensinos" => $ensinos,
            "ano_lectivos" => $ano_lectivos,
            "requests" => $request->all('ano_lectivo', 'municipio_id', 'ensino_id', 'distrito_id', 'categoria', 'status'),
        ];

        return view('sistema.direccao-provincial.listagem-escolas', $headers);
    }

    //  Mais informações 
    public function informacaoEscolar($id)
    {
        $user = auth()->user();
        
        $direccao = DireccaoProvincia::findOrFail($user->shcools_id);
        
        if(!$user->can('read: escola')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $escola = Shcool::with('pais','provincia', 'municipio', 'ensino', 'distrito')->findOrFail(Crypt::decrypt($id));
        $director = Director::where('instituicao_id', $escola->id)->where('level', '4')->first();
        
         // controle lancamento de notas se esta activo ou não
         
        $ano_ano_lectivo = AnoLectivo::where('status', 'activo')->where('shcools_id', $escola->id)->first();
        
        $controlo = null;
        $lancamento = null;
        $notas = null;
        $results = null;
        
        if($ano_ano_lectivo){
        
            $controlo = ControloLancamentoNotasEscolas::where('ano_lectivo_id', $ano_ano_lectivo->id)->where('shcools_id', $escola->id)->first();
            
            if($controlo){
                $lancamento = ControloLancamentoNotas::where('status', 'activo')->where('id', $controlo->lancamento_id)->first();
            
                if($lancamento){
                    $notas = NotaPauta::with([
                        'disciplina',
                        'trimestre',
                        'escola',
                        'estudante',
                        'turma'
                    ])
                    ->where('controlo_trimestres_id', $lancamento->trimestre_id)
                    ->where('ano_lectivos_id', $ano_ano_lectivo->id)
                    ->where('shcools_id', $escola->id)
                    ->orderBy('turmas_id', 'ASC')
                    ->get();
                    
                    $results = NotaPauta::selectRaw('
                        COUNT(*) as total,
                        SUM(CASE WHEN status_nota = "0" THEN 1 ELSE 0 END) as total_nao_lancada,
                        SUM(CASE WHEN status_nota = "1" THEN 1 ELSE 0 END) as total_lancada,
                        (SUM(CASE WHEN status_nota = "0" THEN 1 ELSE 0 END) * 100 / COUNT(*)) AS percentual_nao_lancada,
                        (SUM(CASE WHEN status_nota = "1" THEN 1 ELSE 0 END) * 100 / COUNT(*)) AS percentual_lancada
                    ')
                    ->where('controlo_trimestres_id', $lancamento->trimestre_id)
                    ->where('ano_lectivos_id', $ano_ano_lectivo->id)
                    ->where('shcools_id', $escola->id)
                    ->first();
                }
            }
        }

        $matriculas = Estudante::where('registro', 'confirmado')->where('shcools_id', $escola->id)->count();
        
        $ano_lectivo_activo = AnoLectivo::where('shcools_id', $escola->id)->where('status', 'activo')->first();
        
        $turmas = Turma::where('ano_lectivos_id', $ano_lectivo_activo->id ?? "")->where('shcools_id', $escola->id)->count();
        $salas = AnoLectivoSala::where('ano_lectivos_id', $ano_lectivo_activo->id ?? "")->where('shcools_id', $escola->id)->count();
        $cursos = AnoLectivoCurso::where('ano_lectivos_id', $ano_lectivo_activo->id ?? "")->where('shcools_id', $escola->id)->count();
        $classes = AnoLectivoClasse::where('ano_lectivos_id', $ano_lectivo_activo->id ?? "")->where('shcools_id', $escola->id)->count();
        $turnos = AnoLectivoTurno::where('ano_lectivos_id', $ano_lectivo_activo->id ?? "")->where('shcools_id', $escola->id)->count();
        
        $laboratorios = LaboratorioEscola::where('shcools_id', $escola->id)->count();
        $funcionarios = FuncionariosControto::where('level', '4')->where('cargo_geral', 'professor')->where('shcools_id', $escola->id)->count();
        $utilizadores = 0; //FuncionariosControto::orWhere('level', '4')->where('shcools_id', $escola->id)->count();
        
        $headers =  [
            "escola" => $escola,
            "director" => $director,
            "usuario" => $user,
            "matriculas" => $matriculas,
            "laboratorios" => $laboratorios,
            "funcionarios" => $funcionarios,
            "utilizadores" => $utilizadores,
            "direccao" => $direccao,
            "lancamento" => $lancamento,
            "notas" => $notas,
            "results" => $results,
            
            "turmas" => $turmas,
            "salas" => $salas,
            "cursos" => $cursos,
            "classes" => $classes,
            "turnos" => $turnos,
                        
        ];

        return view('sistema.direccao-provincial.mais-informacao', $headers);

    }
    
    
    public function listagemEstudantes($id)
    {
        $user = auth()->user();
        
        if(!$user->can('read: estudante')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
    
        $escola = Shcool::findOrFail(Crypt::decrypt($id));

        $matriculas = Estudante::where([
            ['registro', '=', 'confirmado'],
            ['shcools_id', '=', $escola->id],
        ])
        ->get();

        $headers =  [
            "titulo" => "Listagem dos estudantes da escola: {$escola->nome}",
            "descricao" => env('APP_NAME'),
            "matriculas" => $matriculas,
            "escola" => $escola,
            "usuario" => User::findOrFail(Auth::user()->id),
        ];
        
        return view('sistema.direccao-provincial.estudantes', $headers);
    }

    public function informacaoEstudante($id)
    {
    
        $user = auth()->user();
        
        if(!$user->can('read: estudante')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $estudante = Estudante::findOrFail(Crypt::decrypt($id));

        $matricula = Matricula::where([
            ['estudantes_id', '=', $estudante->id],
        ])->first();

        $turma = Turma::where([
            ['cursos_id', '=', $matricula->cursos_id],
            ['classes_id', '=', $matricula->classes_id],
            ['turnos_id', '=', $matricula->turnos_id],
        ])->first();

        if($turma){
            $sala = Sala::findOrFail($turma->salas_id);
        }else{
            $turma = null;
            $sala = null;
        }

        $encarregado = EncarregadoEstudantes::where([
            ['estudantes_id', '=', $estudante->id],
        ])
        ->join('tb_encarregados', 'tb_encarregado_estudantes.encarregados_id', '=', 'tb_encarregados.id')
        ->first();
        
        $headers =  [
            "titulo" => "Informações geral do estudante",
            "descricao" => env('APP_NAME'),
            'estudante' => $estudante,
            'curso' => Curso::findOrFail($matricula->cursos_id),
            'turno' => Turno::findOrFail($matricula->turnos_id),
            'classe' => Classe::findOrFail($matricula->classes_id),
            'escola' => Shcool::with(['provincia', 'municipio'])->findOrFail($matricula->shcools_id),
            'sala' => $sala,
            'turma' => $turma,
            'matricula' => $matricula,
            'encarregado' => $encarregado,            
        ];

        return view('sistema.direccao-provincial.mais-informacoes-estudante', $headers);
    }

    public function listagemProfessores($id)
    {
        $user = auth()->user();
        
        if(!$user->can('read: professores')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $escola = Shcool::findOrFail(Crypt::decrypt($id));
        
        $professores = FuncionariosControto::where('level', '4')->where('cargo_geral', 'professor')->with('funcionario.academico')->where('shcools_id', $escola->id)->get();

        $headers =  [
            "titulo" => "Listagem dos Professores {$escola->nome}",
            "descricao" => env('APP_NAME'),
            "professores" => $professores,
            "escola" => $escola,
            "usuario" => User::findOrFail(Auth::user()->id),
        ];
        
        return view('sistema.direccao-provincial.professores', $headers);
    }

    public function informacaoProfessores($id)
    {

        $user = auth()->user();
        
        if(!$user->can('read: professores')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $professor = Professor::where('level', 4)->with('nacionalidade')
        ->with('provincia', 'distrito', 'municipio')
        ->with('academico.escolaridade')
        ->with('academico.especialidade')
        ->with('academico.categoria')
        ->with('academico.escolaridade')
        ->with('academico.universidade')
        ->findOrFail(Crypt::decrypt($id));
        
        $contrato = FuncionariosControto::where('level', '4')
        ->with('departamento', 'cargos')
        ->where('funcionarios_id', $professor->id)
        ->first();

        $escolas = FuncionariosControto::where('level', '4')->whereIn('funcionarios_id', [$professor->id])->distinct()->orderBy('shcools_id', "desc")->get(['shcools_id']);
    
        $infor_escola = Shcool::with('provincia')->whereIn('id', $escolas)->get();
        $arquivo = Arquivo::where('level', $professor->level)
        ->where('model_type', 'professor')
        ->where('model_id', $professor->id)
        ->first();

        $headers =  [
            "titulo" => "Informações geral do Professor",
            "descricao" => env('APP_NAME'),
            'professor' => $professor,
            'contrato' => $contrato,
            'escolas' => $escolas,
            'documentos' => $arquivo,
            'infor_escola' => $infor_escola,
        ];

        return view('sistema.direccao-provincial.mais-informacoes-professor', $headers);
    }

    public function estadoCandidaturaProfessores($estado)
    {
        $professor = Professor::findOrFail($estado);

        if($professor->status == "activo"){
            $professor->status = "desactivo";
            $professor->update();
            
            Alert::success("Bom trabalho", "Candidatura desactivada com sucesso!");
            return redirect()->back()->with("message", "Candidatura desactivada com sucesso!");
        }
        
        if($professor->status == "desactivo"){
            $professor->status = "activo";
            $professor->update();

            Alert::success("Bom trabalho", "Candidatura activada com sucesso!");
            return redirect()->back()->with("message", "Candidatura activada com sucesso!");
        }

    }


    public function dispanhoProfessoresIndex($id = null)
    {
        $user = auth()->user();
        
        if(!$user->can('read: distribuicao de professor')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        if($id != null){
        
            $professor = Professor::where('id', $id)->where('status', 'activo')->first();
            if( !$professor ){
                Alert::warning('Informação', 'Este professor ainda não foi aceite a sua candidatura, entra no seu perfil e activa a sua candidatura!');
                return redirect()->back();
            }
        
        }
       
        
        $professores = Professor::when($id, function($query, $value){
            $query->where('id', $value);
        })
        ->where('status', 'activo')->get();
        $escolas = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->where('status', 'activo')->get();
        $departamentos = Departamento::where('level', '3')->get();
        $cargos = Cargo::where('level', '3')->get();
        
        $headers =  [
            "titulo" => "Transfêrencias de Professores",
            "descricao" => env('APP_NAME'),
            "professores" => $professores,
            "escolas" => $escolas,
            "cargos" => $cargos,
            "departamentos" => $departamentos,
        ];

        return view('sistema.direccao-provincial.distribuicao-professor', $headers);
    }

    public function dispanhoProfessoresStore(Request $request)
    {
        $request->validate(
            [
                'professor_id' => 'required',
                'escola_id' => 'required',
                'departamento_id' => 'required',
                'cargo_id' => 'required',
            ],
            [
                'professor_id.required' => "Senha Obrigatória",
                'cargo_id.required' => "Senha Obrigatória",
                'departamento_id.required' => "Senha Obrigatória",
                'escola_id.required' => "Senha Obrigatória",
            ]
        ); 
    
        $user = auth()->user();
        
        if(!$user->can('create: distribuicao de professor')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $professor = Professor::findOrFail($request->professor_id);
        $escola = Shcool::findOrFail($request->escola_id);

        $anolectivo = AnoLectivo::where('status', 'activo')->where('shcools_id', $escola->id)->first();

        $verificar = FuncionariosControto::where('shcools_id', $escola->id)
        ->where('level', '4')
        ->where('shcools_id', $escola->id)
        ->where('provincia_id', $escola->provincia_id)
        ->where('pais_id', $escola->pais_id)
        ->where('distrito_id', $escola->distrito_id)
        ->where('municipio_id', $escola->municipio_id)
        ->where('status', 'activo')
        ->where('funcionarios_id', $professor->id)
        ->first();

        if($verificar){
            Alert::warning("Informação", "Esta distribuição não pode ser realizada, este professor já faz parte desta escola!");
            return redirect()->route('app.dispanho-professores-provincial-index')->with('danger', 'Esta distribuição não pode ser realizada, este professor já faz parte desta escola!');
        }
        
        
         // Criar contrato
        $create3 = new FuncionariosControto();
        $create3->funcionarios_id = $professor->id;
        $create3->documento = $professor->codigo;
        $create3->salario = 0;
        $create3->subcidio = 0;
        $create3->subcidio_alimentacao = 0;
        $create3->subcidio_transporte = 0;

        $create3->subcidio_ferias = 0;
        $create3->subcidio_natal = 0;
        $create3->subcidio_abono_familiar = 0;
        $create3->falta_por_dia =  0;
        $create3->level =  '4';
 
        $create3->data_inicio_contrato = date("Y-m-d");
        $create3->data_final_contrato = date("Y-m-d");
        $create3->hora_entrada_contrato = "18:30";
        $create3->hora_saida_contrato = "18:30";
        $create3->cargo = $request->cargo_id;
        $create3->conta_bancaria = NULL;
        $create3->status_contrato = "activo";
        $create3->status = "activo";
        $create3->iban = NULL;
        $create3->numero_identifcador = $professor->codigo;

        $create3->cargo_geral = "professor";
        
        $create3->distrito_id = $escola->distrito_id;
        $create3->pais_id = $escola->pais_id;
        $create3->provincia_id = $escola->provincia_id;
        $create3->municipio_id = $escola->municipio_id;
 
        $create3->departamento_id = $request->departamento_id;
        
        $create3->cargo_id = $request->cargo_id;
        $create3->clausula = NULL;
        $create3->nif = $professor->bilheite;
        $create3->data_at = date("Y-m-d");
        $create3->ano_lectivos_id = $anolectivo->id ?? 0;
        $create3->shcools_id = $escola->id;
        $create3->save();

        $meses = Mes::all();

        if($meses){
            foreach($meses as $mes){
            $verificar = CartaoFuncionario::where([
                ['funcionarios_id', '=', $professor->id],
                ['codigo', '=', $professor->codigo],
                ['mes_id', '=', $mes->id],
                ['level', '=', '4'],
                ['ano_lectivos_id', '=', $anolectivo->id ?? 0],
            ])->first();

            if(!$verificar){
                $newCreate = new CartaoFuncionario();

                $newCreate->funcionarios_id = $professor->id;
                $newCreate->codigo = $professor->codigo;
                $newCreate->mes_id = $mes->id;	
                $newCreate->level = '4';	
                $newCreate->ano_lectivos_id =  $anolectivo->id ?? 0;    
                $newCreate->shcools_id =  $escola->id;	
                $newCreate->status  = 'Nao pago';
                
                $newCreate->save();
                }
            }
        }
        
        $text = "O Professor {$professor->nome} {$professor->sobre_nome} foi transferido para escola {$escola->nome}";
        $text2 = "O Sr(a) acabou de transferir o professor {$professor->nome} {$professor->sobre_nome} para a escola {$escola->nome} ";
            
        Notificacao::create([
            'user_id' => Auth::user()->id,
            'destino' => $escola->id,
            'type_destino' => 'escola',
            'type_enviado' => 'provincial',
            'notificacao' => $text,
            'notificacao_user' => $text2,
            'status' => '0',
            'model_id' => $create3->id,
            'model_type' => "distribuicao",
            'shcools_id' => $escola->id
        ]);

        Alert::success("Bom Trabalho", "A Distribuição do Professor realizada com sucesso!");
        return redirect()->route('app.dispanho-professores-provincial-index')->with('message', 'A Distribuição do Professor realizada com sucesso!');


    }
    

    public function informacaoTurmaProfessores($id, $escola = null)
    {
        $professor = Professor::with('nacionalidade')->with('provincia')->with('academico.escolaridade')->with('academico.formacao')->findOrFail($id);
        $escolas = FuncionariosControto::whereIn('funcionarios_id', [$professor->id])->distinct()->orderBy('shcools_id', "desc")->get(['shcools_id']);

        $infor_escola = Shcool::whereIn('id', $escolas)->get();
        $shcool = Shcool::findOrFail($escola);

        $turmas = FuncionariosTurma::where([
            ['tb_turmas_funcionarios.funcionarios_id', '=', $professor->id],
            ['tb_turmas_funcionarios.shcools_id', '=', $shcool->id],
        ])
        ->join('tb_disciplinas', 'tb_turmas_funcionarios.disciplinas_id', '=', 'tb_disciplinas.id')
        ->join('tb_turmas', 'tb_turmas_funcionarios.turmas_id', '=', 'tb_turmas.id')
        ->select('tb_disciplinas.disciplina', 'tb_disciplinas.id AS idDis', 'tb_turmas_funcionarios.cargo_turma', 'tb_turmas.turma',  
        'tb_turmas.id AS idTurma')
        ->get();

        $headers =  [
            "titulo" => "Informações geral do Professor",
            "descricao" => "gestão de discipinas",
            'professor' => $professor,
            'escolas' => $escolas,
            'shcool' => $shcool,
            'infor_escola' => $infor_escola,
            'turmas' => $turmas,
        ];

        return view('sistema.direccao-provincial.mais-informacoes-turmas', $headers);
    }
    
    
    public function listagemEstudantesGeral(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('read: estudante')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $direccao = DireccaoProvincia::findOrFail($user->shcools_id);
        $munucipios = Municipio::where('provincia_id', $direccao->provincia_id)->get();
        $distritos = Distrito::where('municipio_id', $direccao->municipio_id)->get();
        
        $search_municipio_id = $request->municipio_id;
        $search_distrito = $request->distrito_id;
        $search_ano_lectivos_id = $request->ano_lectivos_id;
        
        $escolas = Shcool::where('provincia_id', $direccao->provincia_id)->get(['id']);
        $get_escolas = Shcool::where('provincia_id', $direccao->provincia_id)->get(['id', 'nome']);
        
        $estudantes = Estudante::whereIn('shcools_id', $escolas)
        ->with(['escola.ano', 'escola.municipio', 'escola.provincia'])
        ->whereHas('escola', function($query) use ($search_municipio_id, $search_distrito, $search_ano_lectivos_id) {
            $query->when($search_municipio_id, function($query, $value){
                $query->where('municipio_id', $value);
            });
            
            $query->when($search_distrito, function($query, $value){
                $query->where('distrito_id', $value);
            });
            
            $query->when($search_ano_lectivos_id, function($query, $value){
                $query->where('ano_lectivo_global_id', $value);
            });
        })
        ->when($request->shcools_id, function($query, $value){
            $query->where('shcools_id', $value);
        })
        ->when($request->genero, function($query, $value){
            $query->where('genero', $value);
        })
        ->where('registro', '=', 'confirmado')
        ->get();
        
        $headers =  [
            "titulo" => "Listagem dos estudantes da provincia",
            "descricao" => env('APP_NAME'),
            "estudantes" => $estudantes,
            "munucipios" => $munucipios,
            "distritos" => $distritos,
            "escolas" => $get_escolas,
            'anos_lectivos' => AnoLectivoGlobal::get(),
            'turnos' => Turno::get(),
            'classes' => Classe::get(),
            "usuario" => User::findOrFail(Auth::user()->id),

            'requests' => $request->all('municipio_id', 'distrito_id', 'ano_lectivos_id', 'shcools_id','genero'),
        ];
        
        return view('sistema.direccao-provincial.estudantes-geral', $headers);
    }

    public function professoresIndex(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('read: professores')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $direccao = DireccaoProvincia::findOrFail($user->shcools_id);
        $municipios = Municipio::where('provincia_id', $direccao->provincia_id)->get();
        $distritos = Distrito::where('municipio_id', $direccao->municipio_id)->get();
        
      
        $universidade_id = $request->universidade_id;
        $escolaridade_id = $request->escolaridade_id;
        $formacao_id = $request->formacao_id;
        $especialidade_id = $request->especialidade_id;
        $categora_id = $request->categora_id;
        $status = $request->status;
        
   
        $get_escolas = Shcool::where('provincia_id', $direccao->provincia_id)->get(['id', 'nome']);
        
        $professores = FuncionariosControto::with('funcionario.provincia', 'funcionario.academico' ,'escola.provincia', 'escola.municipio', 'funcionario.academico.especialidade', 'funcionario.academico.categoria', 'funcionario.academico.escolaridade', 'funcionario.academico.universidade')
        ->whereHas('funcionario.academico', function($query) use ($universidade_id, $escolaridade_id, $formacao_id, $especialidade_id, $categora_id){
            $query->when($universidade_id, function ($query) use ($universidade_id){
                $query->where('universidade_id', $universidade_id);
            });
            
            $query->when($escolaridade_id, function ($query) use ($escolaridade_id){
                $query->where('escolaridade_id', $escolaridade_id);
            });
            
            $query->when($formacao_id, function ($query) use ($formacao_id){
                $query->where('formacao_academica_id', $formacao_id);
            });
            
            $query->when($especialidade_id, function ($query) use ($especialidade_id){
                $query->where('especialidade_id', $especialidade_id);
            });
            
            $query->when($categora_id, function ($query) use ($categora_id){
                $query->where('categoria_id', $categora_id);
            });
        })
        ->whereHas('funcionario', function ($query) use ($status){
            $query->when($status, function ($query) use ($status){
                $query->where('status', $status);
            });
        })
        ->when($request->municipio_id, function($query, $value){
            $query->where('municipio_id', $value);
        })
        ->when($request->distrito_id, function($query, $value){
            $query->where('distrito_id', $value);
        })
        ->when($request->shcools_id, function($query, $value){
            $query->where('shcools_id', $value);
        })
        ->when($request->ano_lectivos_id, function($query, $value){
            $query->where('ano_lectivo_global_id', $value);
        })
        // ->when($request->status, function($query, $value){
        //     $query->where('status', $value);
        // })
        ->distinct('funcionarios_id')
        ->where('provincia_id', $direccao->provincia_id)
        ->where('pais_id', $direccao->pais_id)
        ->where('level', '4')
        ->where('cargo_geral', 'professor')
        ->where('status', 'activo')
        ->get();    
        
        $especialidades = Especialidade::get();
        $categorias = Categoria::get();
        $universidades = Universidade::get();
        
        $headers =  [
            "titulo" => "Professores",
            "descricao" => env('APP_NAME'),
            "professores" => $professores,
            "distritos" => $distritos,
            "municipios" => $municipios,
            "especialidades" => $especialidades,
            "categorias" => $categorias,
            "universidades" => $universidades,
            "escolaridade" => Escolaridade::get(),
            "formacao_academicos" => FormacaoAcedemico::get(),
            
            "escolas" => $get_escolas,
            'anos_lectivos' => AnoLectivoGlobal::get(),
            
            "requests" => $request->all('municipio_id', 'ano_lectivos_id', 'shcools_id', 'distrito_id', 'status', 'universidade_id', 'escolaridade_id',
            'formacao_id', 'especialidade_id', 'categora_id'),
        ];

        return view('sistema.direccao-provincial.professores-geral', $headers);
    }
    
    
    public function professoresGestao(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('read: professores')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $especialidade_id = $request->especialidade_id;
        $categoria_id = $request->categoria_id;
        $nivel_id = $request->nivel_id;
        
        $professores = Professor::with('academico.especialidade', 'academico.categoria')
        ->whereHas('academico', function($query) use($especialidade_id, $categoria_id, $nivel_id) {
            $query->when($especialidade_id, function($query) use ($especialidade_id){
                $query->where('especialidade_id', $especialidade_id);
            });
            
            $query->when($categoria_id, function($query) use ($categoria_id){
                $query->where('categoria_id', $categoria_id);
            });
            
            $query->when($nivel_id, function($query) use ($nivel_id){
                $query->where('formacao_academica_id', $nivel_id);
            });
        })
        ->when($request->status, function($query, $value){
            $query->where('status', $value);
        })
        ->when($request->provincia_id, function($query, $value){
            $query->where('provincia_id', $value);
        })
        ->when($request->genero, function($query, $value){
            $query->where('genero', $value);
        })
        ->when($request->ano_nascimento_maior, function($query, $value){
            $query->where('nascimento', '>=', Carbon::createFromFormat('Y', $value)->endOfYear());
        })
        ->when($request->ano_nascimento_menor, function($query, $value){
            $query->where('nascimento', '<=', Carbon::createFromFormat('Y', $value)->endOfYear());
        })
        ->with('provincia')
        ->get();

        $pais = Paise::where('name', 'Angola')->first();
        $provincias = Provincia::get();
        $categorias = Categoria::get();
        $especialidades = Especialidade::get();
        $niveis = FormacaoAcedemico::get();
        
        $headers =  [
            "titulo" => "Professores",
            "descricao" => env('APP_NAME'),
            "professores" => $professores,
            "provincias" => $provincias,
            "categorias" => $categorias,
            "especialidades" => $especialidades,
            "niveis" => $niveis,
            "count" => 2051,
            "requests" => $request->all('provincia_id', 'status', 'genero', 'especialidade_id', 'nivel_id', 'categoria_id', 'ano_nascimento_menor', 'ano_nascimento_maior'),
        ];

        return view('sistema.direccao-provincial.professores.index', $headers);
    }


    public function privacidade()
    {
        $usuario = User::findOrFail(Auth::user()->id);
        
        $headers =  [
            "titulo" => "Privacidade",
            "descricao" => env('APP_NAME'),
            "usuario" => $usuario,
        ];

        return view('sistema.direccao-provincial.privacidade', $headers);
    }

    public function privacidadeUpdate(Request $request, $id)
    {

        $request->validate(
            [
                'password_1' => 'required',
                'password_2' => 'required',
                'password_3' => 'required',
                'user' => 'required',
            ],
            [
                'password_1.required' => "Senha Obrigatória",
                'password_2.required' => "Senha Obrigatória",
                'password_3.required' => "Senha Obrigatória",
                'user.required' => "Senha Obrigatória",
            ]
        );

        $usuario = User::findOrFail($id);

        if (!Hash::check($request->password_1, $usuario->password)) {
            Alert::warning('Atenção', 'Senha Actual Incorrecta');
            return redirect()->route('app.privacidade-provincial')->with('danger', 'Senha Actual Incorrecta');
        }      
        
        if ($request->password_2 != $request->password_3) {
            Alert::warning('Atenção', 'As duas novas senhas não podem ser diferentes');
            return redirect()->route('app.privacidade-provincial')->with('danger', 'As duas novas senhas não podem ser diferentes');
        } 

        $usuario->password = Hash::make($request->password_2);
        $usuario->usuario = $request->user;
        $usuario->email = $request->email;
        $usuario->nome = $request->nome;
        $usuario->telefone = $request->telefone;
        $usuario->update();

        if($usuario->update()){
            Alert::success('Bom Trabalho', 'Credências actualizados com sucesso, Usar da Proxíma vez que Entrar no sistema');
            return redirect()->route('app.privacidade-provincial')->with('message', 'Credências actualizados com sucesso, Usar da Proxíma vez que Entrar no sistema');
        }
    }
    
    
    /**
        UTILIZADORES
    */
    
        
    public function utilizadoresIndex()
    {
    
        $user = auth()->user();
        
        if(!$user->can('read: utilizador')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $usuarios = User::with('roles')
        ->where('level', 200)
        ->get();
        
        $headers =  [
            "titulo" => "Utilizadores",
            "descricao" => env('APP_NAME'),
            "usuarios" => $usuarios,
        ];

        return view('sistema.direccao-provincial.utilizadores.index', $headers);
    }

    public function utilizadoresStore(Request $request)
    {
        
        $user = auth()->user();
        
        if(!$user->can('create: utilizador')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        
        $request->validate(
            [
                'password_2' => 'required',
                'password_3' => 'required',
                'user' => 'required',
                'role_id' => 'required',
            ],
            [
                'password_2.required' => "Senha Obrigatória",
                'password_3.required' => "Senha Obrigatória",
                'user.required' => "Senha Obrigatória",
                'role_id.required' => "Senha Obrigatória",
            ]
        ); 
        
        if ($request->password_2 != $request->password_3) {
            Alert::warning('Atenção', 'As duas novas senhas não podem ser diferentes');
            return redirect()->route('app.provincial-utilizadores-create')->with('danger', 'As duas novas senhas não podem ser diferentes');
        } 
        
        
        $direccao = DireccaoProvincia::findOrFail($user->shcools_id);

        $roles = Role::findById($request->role_id);

        $user = User::create([
            "password" => Hash::make($request->password_2),
            "usuario" => $request->user,
            "numero_avaliacoes" => 3, 
            "level" => 200,
            "acesso" => 'admin',
            "login" => 'N',
            "status" => $request->status,
            "nome" => $request->nome,
            "email" => $request->email,
            "telefone" => $request->telefone,
            'shcools_id' => $direccao->id
        ]);

        $user->assignRole($roles);
       
        Alert::success('Bom Trabalho', 'Credências actualizados com sucesso, Usar da Proxíma vez que Entrar no sistema');
        return redirect()->route('app.provincial-utilizadores-create')->with('message', 'Utilizador cadastrado com sucesso!');
        
    }
    
    public function utilizadoresCreate()
    {
        
        $user = auth()->user();
        
        if(!$user->can('create: utilizador')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        
        $usuario = User::findOrFail(Auth::user()->id);

        $roles = Role::get();

        $headers =  [
            "titulo" => "Cadastrar Utilizadores",
            "descricao" => env('APP_NAME'),
            "usuario" => $usuario,
            "roles" => $roles,
        ];

        return view('sistema.direccao-provincial.utilizadores.criar', $headers);
    }  
    
    public function utilizadoresEdit($id)
    {
        
        $user = auth()->user();
        
        if(!$user->can('update: utilizador')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        
        $usuario = User::findOrFail($id);
        

        $roles = Role::get();
        $role = null;
        if(count($usuario->roles) != 0){
            $role = $usuario->roles[0];
        }
        
        $headers =  [
            "titulo" => "Editar Utilizadores",
            "descricao" => env('APP_NAME'),
            "usuario" => $usuario,
            "role" => $role,
            "roles" => $roles,
        ];

        return view('sistema.direccao-provincial.utilizadores.editar', $headers);
    }  


    public function utilizadoresUpdate(Request $request, $id)
    {
        
        $user = auth()->user();
        
        if(!$user->can('update: utilizador')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $user = User::findOrFail($id);
        
        foreach ($user->roles as $role) {
            $user->removeRole($role);
        }
        
        $new_role = Role::findById($request->role_id);
        $user->assignRole($new_role);
        
        $user->status  = $request->status;
        $user->nome  = $request->nome;
        $user->email  = $request->email;
        $user->telefone  = $request->telefone;
        $user->numero_avaliacoes  = $request->numero_avaliacoes;
        
        $user->update();
       
        return redirect()->route('app.provincial-utilizadores-index')->with('message', 'Utilizador actualizado com sucesso!');
        
    }
    
    
    public function solicitacoes(Request $request)
    {
        
        $user = auth()->user();
        
        // if(!$user->can('create: utilizador')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        $direccao = DireccaoProvincia::findOrFail($user->shcools_id);
        
        $solicitacoes = SolicitacaoProfessor::where('status', '0')->with('professor', 'disciplina', 'classe', 'instituicao1', 'curso')->where('level_destino', '2')
        ->where('instituicao_id', $direccao->id)
        ->get();
        
        $headers = [
            "titulo" => "Listar Notificações",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "solicitacoes" => $solicitacoes,
        ];

        return view('sistema.direccao-provincial.solicitacoes', $headers);

    }
    
    public function solicitacoesResposta(Request $request, $id)
    {
    
        $user = auth()->user();
        
        // if(!$user->can('create: utilizador')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        $direccao = DireccaoProvincia::findOrFail($user->shcools_id);
    
        $request->validate(
            [
                'resposta' => 'required', 
                'resposta_opcao' => 'required'
            ],
            [
                'resposta.required' => "Obrigatória", 
                'resposta_opcao.required' => "Obrigatória"
            ],
        );
        
        $update = SolicitacaoProfessor::findOrFail($id);
        $update->resposta_descricao = $request->resposta;
        $update->resposta_opcao = $request->resposta_opcao;
        $update->escola_destino_level = '4';
        $update->resposta_user_id = Auth::user()->id;
        $update->status = 1;
        $update->processo = 'CONCLUIDO';
        $update->resposta_instituicao_id = $direccao->id;
        $update->level_respondido = '2';
        $update->update();
        
        Alert::success('Bom Trabalho', 'Solicitação respondida com sucesso');
        return redirect()->back();
        
    }
 

}
