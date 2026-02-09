<?php

namespace App\Http\Controllers;

use App\Models\Arquivo;
use App\Models\User;
use App\Models\AnoLectivoGlobal;
use App\Models\Cargo;
use App\Models\CategoriaDisciplina;
use App\Models\Comunicado;
use App\Models\Departamento;
use App\Models\Director;
use App\Models\Distrito;
use App\Models\Ensino;
use App\Models\Municipio;
use App\Models\Paise;
use App\Models\Professor;
use App\Models\Provincia;
use App\Models\Shcool;
use App\Models\Sistema;
use App\Models\Software;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\anolectivo\AnoLectivoUsuario;
use App\Models\web\anolectivo\Trimestre;
use App\Models\web\calendarios\Calendario;
use App\Models\web\calendarios\CartaoEscola;
use App\Models\web\calendarios\Confirmacao;
use App\Models\web\calendarios\ListaPresenca;
use App\Models\web\calendarios\MapaEfectividade;
use App\Models\web\calendarios\Matricula;
use App\Models\web\calendarios\Mes;
use App\Models\web\calendarios\Pagamento;
use App\Models\web\calendarios\Servico;
use App\Models\web\calendarios\ServicoTurma;
use App\Models\web\classes\AnoLectivoClasse;
use App\Models\web\classes\Classe;
use App\Models\web\cursos\AnoLectivoCurso;
use App\Models\web\cursos\Curso;
use App\Models\web\cursos\DisciplinaCurso;
use App\Models\web\disciplinas\Disciplina;
use App\Models\web\encarregados\Encarregado;
use App\Models\web\encarregados\EncarregadoEstudantes;
use App\Models\web\estudantes\CartaoEstudante;
use App\Models\web\estudantes\Estudante;
use App\Models\web\extensoes\Extensao;
use App\Models\web\financeiros\DetalhesPagamentoPropina;
use App\Models\web\funcionarios\CartaoFuncionario;
use App\Models\web\funcionarios\Funcionarios;
use App\Models\web\funcionarios\FuncionariosAcademico;
use App\Models\web\funcionarios\FuncionariosControto;
use App\Models\web\salas\AnoLectivoSala;
use App\Models\web\salas\Caixa;
use App\Models\web\salas\MovimentoCaixa;
use App\Models\web\salas\Sala;
use App\Models\web\seguranca\ControloSistema;
use App\Models\web\turmas\DisciplinaTurma;
use App\Models\web\turmas\EstudantesTurma;
use App\Models\web\turmas\FuncionariosTurma;
use App\Models\web\turmas\Horario;
use App\Models\web\turmas\NotaPauta;
use App\Models\web\turmas\NotificacaoEncarregado;
use App\Models\web\turmas\Turma;
use App\Models\web\turnos\AnoLectivoTurno;
use App\Models\web\turnos\Turno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Role;
use App\Models\Notificacao;
use App\Models\web\biblioteca\Autor;
use App\Models\web\biblioteca\Editora;
use App\Models\web\biblioteca\EmprestimoLivro;
use App\Models\web\calendarios\Deposito;
use App\Models\web\salas\Banco;
use App\Models\web\turmas\Bolsa;
use App\Models\web\turmas\BolsaInstituicao;
use App\Models\web\turmas\Bolseiro;
use App\Models\web\turmas\Desconto;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    use TraitHelpers;
    use TraitHeader;
    //meu controlador

    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function homeAdmin()
    {
        $user = auth()->user();

        if (!$user->hasRole(['admin', 'super-admin', 'secretario', 'user'])) {
            return redirect()->back();
        }

        $total_escola = Shcool::where('status', 'activo')->count();
        $total_estudante = Estudante::where('registro', 'confirmado')->count();
        $total_professores = Professor::where('status', 'activo')->count();

        $pais = Paise::where('name', 'Angola')->first();
        $provincias = Provincia::get();

        $usuario = User::findOrFail(Auth::user()->id);

        $headers =  [
            "usuario" => $usuario,
            "total_escola" => $total_escola,
            "total_estudante" => $total_estudante,
            "total_professores" => $total_professores,
            "pais" => $pais,
            "provincias" => $provincias,
            "escolas" => Shcool::where([
                ['tb_shcools.deleted_at', '=', null]
            ])
                ->join('tb_controlo_sistema', 'tb_shcools.id', '=', 'tb_controlo_sistema.shcools_id')
                ->select('tb_shcools.id', 'tb_shcools.documento', 'tb_shcools.nome', 'tb_shcools.status', 'tb_shcools.telefone1', 'tb_shcools.telefone2', 'tb_shcools.telefone3', 'tb_controlo_sistema.inicio', 'tb_controlo_sistema.final')
                ->get(),
        ];

        return view('sistema.home', $headers);
    }

    public function notificacoes(Request $request)
    {

        if ($request->notification) {
            $update = Notificacao::findOrFail($request->notification);
            $update->status = '1';
            $update->update();
        }

        $notificacoes = Notificacao::when($request->notification, function ($query, $value) {
            $query->where('id', $value);
        })
            ->where('type_destino', 'ministerio')
            ->with(['user', 'escola'])
            ->orderBy('created_at', 'DESC')
            ->get();

        $headers = [
            "titulo" => "Listar Notificações",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "notificacoes" => $notificacoes,
        ];

        return view('sistema.notificacoes.index', $headers);
    }

    public function listagemEscolas(Request $request, $id = null)
    {
        $user = auth()->user();

        if (!$user->can('read: escola')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        if ($id == null) {
            $id = "";
        }

        $provincias = Provincia::get();
        $municipios = Municipio::get();
        $distritos = Distrito::get();
        $ano_lectivos = AnoLectivoGlobal::get();
        $ensinos = Ensino::get();

        $provincia = Provincia::find($id);

        $escolas = Shcool::when($id, function ($query, $value) {
            $query->where('provincia_id', $value);
        })
            ->when($request->ano_lectivo, function ($query, $value) {
                $query->where('ano_lectivo_global_id', $value);
            })
            ->when($request->provincia_id, function ($query, $value) {
                $query->where('provincia_id', $value);
            })
            ->when($request->municipio_id, function ($query, $value) {
                $query->where('municipio_id', $value);
            })
            ->when($request->ensino_id, function ($query, $value) {
                $query->where('ensino_id', $value);
            })
            ->when($request->distrito_id, function ($query, $value) {
                $query->where('distrito_id', $value);
            })
            ->where([['tb_shcools.deleted_at', '=', null]])
            ->with('municipio', 'provincia', 'pais', 'ensino', 'distrito')
            ->get();

        $usuario = User::findOrFail(Auth::user()->id);

        $headers =  [
            "usuario" => $usuario,
            "escolas" => $escolas,
            "provincia" => $provincia,
            "provincias" => $provincias,
            "distritos" => $distritos,
            "municipios" => $municipios,
            "ensinos" => $ensinos,
            "ano_lectivos" => $ano_lectivos,
            "requests" => $request->all('provincia_id', 'ano_lectivo', 'municipio_id', 'distrito_id', 'ensino_id'),
        ];

        return view('sistema.listagem-escolas', $headers);
    }

    public function termos()
    {

        $headers =  [
            "usuario" => User::findOrFail(Auth::user()->id),
            "termos" => Sistema::findOrFail(1),
        ];

        return view('sistema.termos', $headers);
    }

    public function termosEditar(Request $request)
    {
        $termos = Sistema::findOrFail(1);

        $termos->termos = $request->termos;
        $termos->update();

        return redirect()->route('termos');
    }

    public function politicas()
    {
        $headers =  [
            "usuario" => User::findOrFail(Auth::user()->id),
            "politicas" => Sistema::findOrFail(1),
        ];

        return view('sistema.politicas', $headers);
    }

    public function politicasEditar(Request $request)
    {
        $politicas = Sistema::findOrFail(1);

        $politicas->politicas = $request->politicas;
        $politicas->update();

        return redirect()->route('politicas');
    }

    //  Mais informações
    public function informacaoEscolar($id)
    {
        $user = auth()->user();

        if (!$user->can('read: escola')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $escola = Shcool::with('pais', 'provincia', 'municipio', 'ensino', 'distrito')->findOrFail($id);

        $matriculas = Estudante::where('registro', 'confirmado')->where('shcools_id', $escola->id)->count();
        $turmas = Turma::where('shcools_id', $escola->id)->count();
        $funcionarios = FuncionariosControto::where('shcools_id', $escola->id)->count();
        // $utilizadores = User::where('level' ,'>=', 2)->where('shcools_id', $escola->id)->count();
        $utilizadores = User::where('shcools_id', $escola->id)->count();

        $headers =  [
            "escola" => $escola,
            "usuario" => User::findOrFail(Auth::user()->id),
            "matriculas" => $matriculas,
            "turmas" => $turmas,
            "funcionarios" => $funcionarios,
            "utilizadores" => $utilizadores,
        ];

        return view('sistema.mais-informacao', $headers);
    }

    // mais configruaç]ao escola
    public function configurarEscola($id)
    {
        $escola = Shcool::findOrFail($id);
        $sistema = ControloSistema::where('shcools_id', $escola->id)->firstOrFail();

        $headers =  [
            "escola" => $escola,
            "sistema" => $sistema,
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('sistema.configuracao', $headers);
    }

    public function configuracaoEscola(Request $request)
    {
        $definicao = ControloSistema::findOrFail($request->configuracao_id);
        $definicao->inicio = $request->inicio;
        $definicao->final = $request->final;

        $definicao->update();

        return redirect()->route('home-admin');
    }

    public function definicoes()
    {
        $headers =  [
            "usuario" => User::findOrFail(Auth::user()->id),
            "definicao" => Sistema::findOrFail(1),
        ];

        return view('sistema.definicao', $headers);
    }

    public function definicoesEditar(Request $request)
    {
        $definicao = Sistema::findOrFail(1);

        $definicao->telefone1 = $request->telefone1;
        $definicao->telefone2 = $request->telefone2;
        $definicao->telefone3 = $request->telefone3;
        $definicao->telefone4 = $request->telefone4;
        $definicao->facebook = $request->facebook;
        $definicao->instagram = $request->instagram;
        $definicao->twetter = $request->twetter;
        $definicao->youtube = $request->youtube;
        $definicao->whatsapp = $request->whatsapp;

        $definicao->update();

        return redirect()->route('definicoes');
    }

    /**
     *
     * ELIMINAR ESCOLA
     *
     * */
    public function eliminar_escola($id)
    {
        $escola = Shcool::findOrFail($id);

        try {
            DB::beginTransaction();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            $ano_lectivo = AnoLectivo::where('shcools_id', $escola->id)->get();

            CategoriaDisciplina::where('shcools_id', $escola->id)->forceDelete();

            if ($ano_lectivo) {
                foreach ($ano_lectivo as $value) {
                    AnoLectivoSala::where('ano_lectivos_id', $value->id)->forceDelete();
                    AnoLectivoCurso::where('ano_lectivos_id', $value->id)->forceDelete();
                    AnoLectivoClasse::where('ano_lectivos_id', $value->id)->forceDelete();
                    AnoLectivoTurno::where('ano_lectivos_id', $value->id)->forceDelete();
                    DisciplinaCurso::where('ano_lectivos_id', $value->id)->forceDelete();
                    CartaoEstudante::with(["controle_periodio", "estudante", "servico", "ano"])->where('ano_lectivos_id', $value->id)->forceDelete();
                    NotaPauta::where('ano_lectivos_id', $value->id)->forceDelete();
                    NotificacaoEncarregado::where('ano_lectivos_id', $value->id)->forceDelete();
                }
            }


            $estudantes = Estudante::where('shcools_id', $escola->id)->get();

            if ($estudantes) {
                foreach ($estudantes as $value) {
                    EncarregadoEstudantes::where('estudantes_id', $value->id)->forceDelete();
                }
            }

            $turmas = Turma::where('shcools_id', $escola->id)->get();
            if ($turmas) {
                foreach ($turmas as $value) {
                    DisciplinaTurma::where('turmas_id', $value->id)->forceDelete();
                    EstudantesTurma::where('turmas_id', $value->id)->forceDelete();
                    Horario::where('turmas_id', $value->id)->forceDelete();
                    ServicoTurma::where('turmas_id', $value->id)->forceDelete();
                    FuncionariosTurma::where('turmas_id', $value->id)->forceDelete();
                    ListaPresenca::where('turmas_id', $value->id)->forceDelete();
                }
            }

            $cursos = User::where('shcools_id', $escola->id)->get();
            if ($cursos) {
                foreach ($cursos as $value) {
                    DisciplinaCurso::where('cursos_id', $value->id)->forceDelete();
                }
            }

            Servico::where('shcools_id', $escola->id)->forceDelete();
            Extensao::where('shcools_id', $escola->id)->forceDelete();
            FuncionariosAcademico::where('shcools_id', $escola->id)->forceDelete();

            ControloSistema::where('shcools_id', $escola->id)->forceDelete();
            CartaoEscola::where('shcools_id', $escola->id)->forceDelete();
            MapaEfectividade::where('shcools_id', $escola->id)->forceDelete();
            MovimentoCaixa::where('shcools_id', $escola->id)->forceDelete();
            CartaoFuncionario::where('shcools_id', $escola->id)->forceDelete();
            Confirmacao::where('shcools_id', $escola->id)->forceDelete();
            FuncionariosControto::where('shcools_id', $escola->id)->where('level', '4')->forceDelete();
            DetalhesPagamentoPropina::where('shcools_id', $escola->id)->forceDelete();
            Pagamento::where('shcools_id', $escola->id)->forceDelete();

            Funcionarios::where('shcools_id', $escola->id)->forceDelete();
            Encarregado::where('shcools_id', $escola->id)->forceDelete();
            Matricula::where('shcools_id', $escola->id)->forceDelete();
            Estudante::where('shcools_id', $escola->id)->forceDelete();

            AnoLectivoUsuario::where('shcools_id', $escola->id)->forceDelete();
            User::where('shcools_id', $escola->id)->forceDelete();
            Turma::where('shcools_id', $escola->id)->forceDelete();
            Sala::where('shcools_id', $escola->id)->forceDelete();
            Caixa::where('shcools_id', $escola->id)->forceDelete();
            AnoLectivo::where('shcools_id', $escola->id)->forceDelete();

            Director::where('shcools_id', $escola->id)->where('level', '4')->forceDelete();
            Comunicado::where('shcools_id', $escola->id)->where('level', '4')->forceDelete();
            Arquivo::where('model_id', $escola->id)->where('level', '4')->forceDelete();
            Cargo::where('shcools_id', $escola->id)->where('level', '4')->forceDelete();
            Departamento::where('shcools_id', $escola->id)->where('level', '4')->forceDelete();
            Deposito::where('shcools_id', $escola->id)->forceDelete();
            Desconto::where('shcools_id', $escola->id)->forceDelete();
            Editora::where('shcools_id', $escola->id)->forceDelete();
            EmprestimoLivro::where('shcools_id', $escola->id)->forceDelete();
            Encarregado::where('shcools_id', $escola->id)->forceDelete();
            Autor::where('shcools_id', $escola->id)->forceDelete();
            Banco::where('shcools_id', $escola->id)->forceDelete();
            Bolsa::where('shcools_id', $escola->id)->forceDelete();
            Bolseiro::where('shcools_id', $escola->id)->forceDelete();
            BolsaInstituicao::where('shcools_id', $escola->id)->forceDelete();


            Shcool::findOrFail($id)->forceDelete();
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
        }

        return response()->json([
            'status' => 200,
            'message' => 'Dados Excluido com sucesso!',
        ]);
    }

    /**
     * LISTAGEM ESTUDANTES
     */

    public function listagemEstudantes($id)
    {
        $user = auth()->user();

        if (!$user->can('read: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $escola = Shcool::findOrFail($id);

        $matriculas = Estudante::where([
            ['registro', '=', 'confirmado'],
            ['shcools_id', '=', $id],
        ])
            ->get();

        $headers =  [
            "titulo" => "Listagem dos estudantes da escola: {$escola->nome}",
            "descricao" => env('APP_NAME'),
            "matriculas" => $matriculas,
            "escola" => $escola,
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('web.estudantes-geral.estudantes-geral', $headers);
    }

    public function estatisticaEstudantesGeral(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }


        $ano = AnoLectivoGlobal::where('status', 'activo')->first();

        if ($request->ano_lectivos_id) {
            $ano_lectivo_global = $request->ano_lectivos_id;
        } else {

            if (!$ano) {
                Alert::warning('Informação', 'Cadastra um ano Lectivo para poder ter acesso a esta área!');
                return redirect()->back();
            }

            $ano_lectivo_global = $ano->id;
        }

        $pais = Paise::where('name', 'Angola')->first();
        $provincias = Provincia::get();

        $estudantes = Matricula::when($request->provincia_id, function ($query, $value) {
            $query->where('tb_estudantes.provincia_id', $value);
        })
            ->when($request->estado, function ($query, $value) {
                $query->where('tb_matriculas.status_matricula', $value);
            })
            ->when($ano_lectivo_global, function ($query, $value) {
                $query->where('tb_matriculas.ano_lectivo_global_id', $value);
            })
            ->when($request->classes_id, function ($query, $value) {
                $query->where('classes_id', $value);
            })
            ->when($request->cursos_id, function ($query, $value) {
                $query->where('cursos_id', $value);
            })
            ->when($request->turnos_id, function ($query, $value) {
                $query->where('turnos_id', $value);
            })
            ->when($request->genero, function ($query, $value) {
                $query->where('genero', $value);
            })
            ->join('tb_estudantes', 'tb_matriculas.estudantes_id', '=', 'tb_estudantes.id')
            ->join('tb_classes', 'tb_matriculas.classes_id', '=', 'tb_classes.id')
            ->join('tb_cursos', 'tb_matriculas.cursos_id', '=', 'tb_cursos.id')
            ->join('tb_turnos', 'tb_matriculas.turnos_id', '=', 'tb_turnos.id')
            ->join('tb_provincias', 'tb_estudantes.provincia_id', '=', 'tb_provincias.id')
            ->join('tb_ano_lectivos_global', 'tb_estudantes.ano_lectivo_global_id', '=', 'tb_ano_lectivos_global.id')
            ->select('tb_estudantes.nome', 'sobre_nome', 'genero',  'bilheite', 'tb_estudantes.status', 'status_matricula', 'tb_estudantes.id', 'numero_processo', 'tb_provincias.nome AS provincia', 'ano', 'classes', 'curso', 'turno')
            ->get();

        $headers =  [
            "titulo" => "Listagem dos estudantes do pais",
            "descricao" => env('APP_NAME'),
            "estudantes" => $estudantes,
            "provincias" => $provincias,
            'anos_lectivos' => AnoLectivoGlobal::get(),
            'turnos' => Turno::get(),
            'classes' => Classe::get(),
            'cursos' => Curso::get(),
            "usuario" => User::findOrFail(Auth::user()->id),

            'requests' => $request->all('provincia_id', 'ano_lectivos_id', 'genero', 'classes_id', 'turnos_id', 'cursos_id', 'estado'),
        ];

        return view('web.estudantes-geral.estatistica-estudantes-geral', $headers);
    }


    public function listagemEstudantesGeral(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $pais = Paise::where('name', 'Angola')->first();
        $provincias = Provincia::get();
        $municipios = Municipio::get();

        $search_provincia = $request->provincia_id;
        $search_ano = $request->ano_lectivos_id;
        $search_escola = $request->shcools_id;
        $search_municipio = $request->municipio_id;

        $estudantes = Estudante::with(['escola.provincia', 'escola.municipio', 'escola.ano'])
            ->whereHas('escola', function ($query) use ($search_provincia, $search_ano, $search_escola, $search_municipio) {
                $query->when($search_provincia, function ($query) use ($search_provincia) {
                    $query->where('provincia_id', $search_provincia);
                });

                $query->when($search_municipio, function ($query) use ($search_municipio) {
                    $query->where('municipio_id', $search_municipio);
                });

                $query->when($search_ano, function ($query) use ($search_ano) {
                    $query->where('ano_lectivo_global_id', $search_ano);
                });

                $query->when($search_escola, function ($query) use ($search_escola) {
                    $query->where('id', $search_escola);
                });
            })
            ->when($request->genero, function ($query, $value) {
                $query->where('genero', $value);
            })
            ->where('registro', '=', 'confirmado')
            ->get();

        $headers =  [
            "titulo" => "Listagem dos estudantes do pais",
            "descricao" => env('APP_NAME'),
            "estudantes" => $estudantes,
            "provincias" => $provincias,
            "municipios" => $municipios,
            'anos_lectivos' => AnoLectivoGlobal::get(),
            'escolas' => Shcool::get(['id', 'nome']),
            "usuario" => User::findOrFail(Auth::user()->id),

            'requests' => $request->all('municipio_id', 'provincia_id', 'ano_lectivos_id', 'genero', 'shcools_id'),
        ];

        return view('web.estudantes-geral.listagem-estudantes-geral', $headers);
    }

    public function informacaoEstudante($id)
    {

        $user = auth()->user();

        if (!$user->can('read: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $estudante = Estudante::findOrFail($id);

        $matricula = Matricula::where([
            ['estudantes_id', '=', $estudante->id],
        ])->first();

        $turma = Turma::where([
            ['cursos_id', '=', $matricula->cursos_id],
            ['classes_id', '=', $matricula->classes_id],
            ['turnos_id', '=', $matricula->turnos_id],
        ])->first();

        if ($turma) {
            $sala = Sala::findOrFail($turma->salas_id);
        } else {
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
            'escola' => Shcool::findOrFail($matricula->shcools_id),
            'sala' => $sala,
            'turma' => $turma,
            'matricula' => $matricula,
            'encarregado' => $encarregado,
        ];

        return view('web.estudantes-geral.mais-informacoes', $headers);
    }

    /**preofessores */
    public function listagemProfessores($id)
    {
        $user = auth()->user();

        if (!$user->can('read: professores')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $escola = Shcool::findOrFail($id);
        $professores = FuncionariosControto::with('funcionario.academico')->where('shcools_id', $escola->id)->get();

        $headers =  [
            "titulo" => "Listagem dos Professores {$escola->nome}",
            "descricao" => env('APP_NAME'),
            "professores" => $professores,
            "escola" => $escola,
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('web.professores-geral.listagem-professores', $headers);
    }

    public function informacaoProfessores($id)
    {
        $user = auth()->user();

        if (!$user->can('read: professores')) {
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
            ->findOrFail($id);

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
            "descricao" => "gestão de discipinas",
            'professor' => $professor,
            'contrato' => $contrato,
            'escolas' => $escolas,
            'documentos' => $arquivo,
            'infor_escola' => $infor_escola,
        ];

        return view('web.professores-geral.mais-informacoes', $headers);
    }

    public function informacaoTurmaProfessores($id, $escola = null)
    {
        $professor = Professor::with('nacionalidade')->with('provincia')->with('academico.escolaridade')->with('academico.formacao')->findOrFail($id);
        $escolas = FuncionariosControto::whereIn('funcionarios_id', [$professor->id])->distinct()->orderBy('shcools_id', "desc")->get(['shcools_id']);

        $infor_escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->whereIn('id', $escolas)->get();
        $shcool = Shcool::findOrFail($escola);

        $turmas = FuncionariosTurma::where([
            ['tb_turmas_funcionarios.funcionarios_id', '=', $professor->id],
            ['tb_turmas_funcionarios.shcools_id', '=', $shcool->id],
        ])
            ->join('tb_disciplinas', 'tb_turmas_funcionarios.disciplinas_id', '=', 'tb_disciplinas.id')
            ->join('tb_turmas', 'tb_turmas_funcionarios.turmas_id', '=', 'tb_turmas.id')
            ->select(
                'tb_disciplinas.disciplina',
                'tb_disciplinas.id AS idDis',
                'tb_turmas_funcionarios.cargo_turma',
                'tb_turmas.turma',
                'tb_turmas.id AS idTurma'
            )
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

        return view('web.professores-geral.mais-informacoes-turmas', $headers);
    }

    public function estadoCandidaturaProfessores($estado)
    {
        $professor = Professor::findOrFail($estado);

        if ($professor->status == "activo") {
            $professor->status = "desactivo";
            $professor->update();

            Alert::success("Bom trabalho", "Candidatura desactivada com sucesso!");
            return redirect()->back()->with("message", "Candidatura desactivada com sucesso!");
        }

        if ($professor->status == "desactivo") {
            $professor->status = "activo";
            $professor->update();

            Alert::success("Bom trabalho", "Candidatura activada com sucesso!");
            return redirect()->back()->with("message", "Candidatura activada com sucesso!");
        }
    }

    public function informacaoProfessoresLancamentoNota(Request $request, $id, $turma)
    {
        $professor = Professor::with('nacionalidade')->with('provincia')->with('academico.escolaridade')->with('academico.formacao')->findOrFail($id);
        $usuario = User::where('funcionarios_id', $professor->id)->firstOrFail();

        $turma = Turma::findOrFail($turma);

        $disciplinas = DisciplinaTurma::where('turmas_id', $turma->id)->with('disciplina')->get();
        $trimestres = Trimestre::where('shcools_id', $turma->shcools_id)->where('ano_lectivos_id', $turma->ano_lectivos_id)->get();

        $notas = null;
        $disciplina = null;
        $trimestre = null;

        if ($request->disciplina_id != null || $request->trimestre_id != null) {

            $notas = NotaPauta::where('disciplinas_id', $request->disciplina_id)
                ->where('controlo_trimestres_id', $request->trimestre_id)
                ->where('turmas_id', $turma->id)
                ->with(['estudante'])
                ->get();

            $disciplina = Disciplina::findOrFail($request->disciplina_id);
            $trimestre = Trimestre::findOrFail($request->trimestre_id);
        }

        $headers =  [
            "titulo" => "Lançamento de Notas",
            "descricao" => "Gestão Notas",
            'professor' => $professor,
            'turma' => $turma,

            'disciplina' => $disciplina,
            'trimestre' => $trimestre,
            'classe' => Classe::findOrFail($turma->classes_id),
            'curso' => Curso::findOrFail($turma->cursos_id),
            'turno' => Turno::findOrFail($turma->turnos_id),
            'sala' => Sala::findOrFail($turma->salas_id),
            'ano' => AnoLectivo::findOrFail($turma->ano_lectivos_id),


            'disciplinas' => $disciplinas,
            'trimestres' => $trimestres,
            'notas' => $notas,
            'usuario' => $usuario,
        ];

        return view('web.professores-geral.lancamento-notas', $headers);
    }

    public function professoresLancamentoNotaEstudante($prof, $notas = null)
    {
        $professor = Professor::with('nacionalidade')->with('provincia')->with('academico.escolaridade')->with('academico.formacao')->findOrFail($prof);
        $usuario = User::where('funcionarios_id', $professor->id)->firstOrFail();

        $nota = NotaPauta::findOrFail($notas);

        $headers =  [
            "titulo" => "Informações geral do Professor",
            "descricao" => "gestão de discipinas",
            'professor' => $professor,
            'usuario' => $usuario,
            'nota' => $nota,
        ];

        return view('web.professores-geral.lancamento-notas-index', $headers);
    }

    public function professoresLancamentoNotaEstudanteStore(Request $request)
    {
        $professor = Professor::with('nacionalidade')->with('provincia')->with('academico.escolaridade')->with('academico.formacao')->findOrFail($request->professor_id);
        $usuario = User::where('funcionarios_id', $professor->id)->firstOrFail();

        if (
            (($request->input('ne') >= 21) or ($request->input('ne') <= -1) and !filter_var($request->input('ne'), FILTER_VALIDATE_INT)) or
            (($request->input('nr') >= 21) or ($request->input('nr') <= -1) and !filter_var($request->input('nr'), FILTER_VALIDATE_INT)) or
            (($request->input('npt') >= 21) or ($request->input('npt') <= -1) and !filter_var($request->input('npt'), FILTER_VALIDATE_INT)) or
            (($request->input('mac') >= 21) or ($request->input('mac') <= -1) and !filter_var($request->input('mac'), FILTER_VALIDATE_INT))
        ) {
            return redirect()->back('message', "Os Valores devem ser Inteiros ou Decimais, e deve manter-se no intervalo de 0 à 20");
        }

        // professor_id

        $updateNota = NotaPauta::findOrFail($request->input('nota_id'));

        $trimestre = Trimestre::findOrFail($updateNota->controlo_trimestres_id);

        // recuperar o primeiro trimestre do ano lectivo para pesquisar segundo o trimestre
        $trimestre1 = Trimestre::where('trimestre', 'Iª Trimestre')->first();
        // recuperar o segundo trimestre do ano lectivo para pesquisar segundo o trimestre
        $trimestre2 = Trimestre::where('trimestre', 'IIª Trimestre')->first();
        // recuperar o terceiro trimestre do ano lectivo para pesquisar segundo o trimestre
        $trimestre3 = Trimestre::where('trimestre', 'IIIª Trimestre')->first();
        // recuperar o quarto trimestre do ano lectivo para pesquisar segundo o trimestre
        $trimestre4 = Trimestre::where('trimestre', 'Geral')->first();

        $updateNota->funcionarios_id = $request->professor_id;
        $updateNota->descricao = $request->input('descricao_estudante');

        $updateNota->mac = $request->input('mac');
        $updateNota->npt = $request->input('npt');
        $updateNota->mt = ($request->input('mac') + $request->input('npt')) / 2;

        if ($trimestre->trimestre == 'Iª Trimestre') {
            $updateNota->mt1 = ($request->input('mac') + $request->input('npt')) / 2;
            $updateNota->status_nota1 = 1;
        } else {
            if ($trimestre->trimestre == 'IIª Trimestre') {
                $updateNota->mt2 = ($request->input('mac') + $request->input('npt')) / 2;
                $updateNota->status_nota1 = 1;
            } else {
                if ($trimestre->trimestre == 'IIIª Trimestre') {
                    $updateNota->mt3 = ($request->input('mac') + $request->input('npt')) / 2;
                    $updateNota->status_nota1 = 1;
                    $updateNota->status_nota = 1;
                }
            }
        }

        // $updateNota->ne = $request->input('ne');
        $updateNota->update();

        // pesquisar as notas do primeiro trimestre deste aluno, turma, disciplina, e trimestre afim de recuperar a soma das notas do primeiro trimestre
        $notaTrimestre1 = NotaPauta::where('estudantes_id', $updateNota->estudantes_id)
            ->where('disciplinas_id', $updateNota->disciplinas_id)
            ->where('turmas_id', $updateNota->turmas_id)
            ->where('controlo_trimestres_id', $trimestre1->id)
            ->first();

        // pesquisar as notas do segundo trimestre deste aluno, turma, disciplina, e trimestre afim de recuperar a soma das notas do secundo trimestre
        $notaTrimestre2 = NotaPauta::where('estudantes_id', $updateNota->estudantes_id)
            ->where('disciplinas_id', $updateNota->disciplinas_id)
            ->where('turmas_id', $updateNota->turmas_id)
            ->where('controlo_trimestres_id', $trimestre2->id)
            ->first();

        // pesquisar as notas do terceiro trimestre deste aluno, turma, disciplina, e trimestre afim de recuperar a soma das notas do terceiro trimestre
        $notaTrimestre3 = NotaPauta::where('estudantes_id', $updateNota->estudantes_id)
            ->where('disciplinas_id', $updateNota->disciplinas_id)
            ->where('turmas_id', $updateNota->turmas_id)
            ->where('controlo_trimestres_id', $trimestre3->id)
            ->first();

        // pesquisar as notas do quarto trimestre deste aluno, turma, disciplina, e trimestre afim de recuperar a soma das notas do quarto trimestre
        $notaTrimestre4 = NotaPauta::where('estudantes_id', $updateNota->estudantes_id)
            ->where('disciplinas_id', $updateNota->disciplinas_id)
            ->where('turmas_id', $updateNota->turmas_id)
            ->where('controlo_trimestres_id', $trimestre4->id)
            ->first();

        $updateMFD = NotaPauta::findOrFail($notaTrimestre4->id);
        // somar as notas do MT1, MT2, MT3
        $mfd = ($notaTrimestre1->mt + $notaTrimestre2->mt + $notaTrimestre3->mt) / 3;

        $updateMFD->mfd = $mfd;
        $updateMFD->ne = $request->input('ne');
        $updateMFD->nr = $request->input('nr');
        $updateMFD->update();

        return redirect()->route('app.informacao-professores-lancamento-nota', [$professor->id, $updateNota->turmas_id])->with('message', 'Notas atribuida com sucesso');
    }

    public function privacidade()
    {
        $usuario = User::findOrFail(Auth::user()->id);

        $headers =  [
            "titulo" => "Privacidade",
            "descricao" => env('APP_NAME'),
            "usuario" => $usuario,
        ];

        return view('sistema.privacidade.privacidade', $headers);
    }

    public function privacidadeUpdate(Request $request, $id)
    {

        $request->validate([
            'password_1' => 'required',
            'password_2' => 'required',
            'password_3' => 'required',
            'user' => 'required',
        ]);

        $usuario = User::findOrFail($id);

        if (!Hash::check($request->password_1, $usuario->password)) {
            Alert::warning('Atenção', 'Senha Actual Incorrecta');
            return redirect()->route('app.privacidade')->with('danger', 'Senha Actual Incorrecta');
        }

        if ($request->password_2 != $request->password_3) {
            Alert::warning('Atenção', 'As duas novas senhas não podem ser diferentes');
            return redirect()->route('app.privacidade')->with('danger', 'As duas novas senhas não podem ser diferentes');
        }

        $usuario->password = Hash::make($request->password_2);
        $usuario->usuario = $request->user;
        $usuario->email = $request->email;
        $usuario->nome = $request->nome;
        $usuario->telefone = $request->telefone;
        $usuario->update();

        if ($usuario->update()) {
            Alert::success('Bom Trabalho', 'Credências actualizados com sucesso, Usar da Proxíma vez que Entrar no sistema');
            return redirect()->route('app.privacidade')->with('message', 'Credências actualizados com sucesso, Usar da Proxíma vez que Entrar no sistema');
        }
    }

    public function utilizadoresIndex()
    {

        $user = auth()->user();

        if (!$user->can('read: utilizador')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }


        $usuarios = User::with('roles')
            ->where('level', 1)
            ->orWhere('level', 50)
            ->get();

        $headers =  [
            "titulo" => "Utilizadores",
            "descricao" => env('APP_NAME'),
            "usuarios" => $usuarios,
        ];

        return view('sistema.utilizadores.index', $headers);
    }

    public function utilizadoresStore(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('create: utilizador')) {
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
            return redirect()->route('app.utilizadores-create')->with('danger', 'As duas novas senhas não podem ser diferentes');
        }

        $roles = Role::findById($request->role_id);

        $user = User::create([
            "password" => Hash::make($request->password_2),
            "usuario" => $request->user,
            "numero_avaliacoes" => 3,
            "level" => 1,
            "acesso" => 'admin',
            "login" => 'N',
            "status" => $request->status,
            "nome" => $request->nome,
            "email" => $request->email,
            "telefone" => $request->telefone,
        ]);

        $user->assignRole($roles);

        Alert::success('Bom Trabalho', 'Credências actualizados com sucesso, Usar da Proxíma vez que Entrar no sistema');
        return redirect()->route('app.utilizadores-create')->with('message', 'Utilizador cadastrado com sucesso!');
    }

    public function utilizadoresCreate()
    {

        $user = auth()->user();

        if (!$user->can('create: utilizador')) {
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

        return view('sistema.utilizadores.criar', $headers);
    }

    public function utilizadoresEdit($id)
    {

        $user = auth()->user();

        if (!$user->can('update: utilizador')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }


        $usuario = User::findOrFail($id);


        $roles = Role::get();
        $role = null;
        if (count($usuario->roles) != 0) {
            $role = $usuario->roles[0];
        }

        $headers =  [
            "titulo" => "Editar Utilizadores",
            "descricao" => env('APP_NAME'),
            "usuario" => $usuario,
            "role" => $role,
            "roles" => $roles,
        ];

        return view('sistema.utilizadores.editar', $headers);
    }


    public function utilizadoresUpdate(Request $request, $id)
    {

        $user = auth()->user();

        if (!$user->can('update: utilizador')) {
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

        return redirect()->route('app.utilizadores-index')->with('message', 'Utilizador actualizado com sucesso!');
    }

    public function professoresIndex(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: professores')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $professores = Professor::when($request->status, function ($query, $value) {
            $query->where('status', $value);
        })
            ->when($request->provincia_id, function ($query, $value) {
                $query->where('provincia_id', $value);
            })
            ->when($request->genero, function ($query, $value) {
                $query->where('genero', $value);
            })
            ->with('provincia')
            ->get();

        $pais = Paise::where('name', 'Angola')->first();
        $provincias = Provincia::get();

        $headers =  [
            "titulo" => "Professores",
            "descricao" => env('APP_NAME'),
            "professores" => $professores,
            "provincias" => $provincias,
            "requests" => $request->all('provincia_id', 'status', 'genero'),
        ];

        return view('sistema.professores.index', $headers);
    }

    public function DispanhoProfessoresIndex($id = null)
    {
        $user = auth()->user();

        if (!$user->can('read: distribuicao de professor')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $professor = null;
        if ($id) {
            $professor = Professor::where('id', $id)->where('status', 'activo')->first();
        }
        $professores = Professor::where('status', 'activo')->get();
        $escolas = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->where('status', 'activo')->get();

        $headers =  [
            "titulo" => "Distribuição de Professores",
            "descricao" => env('APP_NAME'),
            "professores" => $professores,
            "escolas" => $escolas,

            "professor" => $professor,
        ];

        return view('sistema.professores.transferencia', $headers);
    }

    public function DispanhoProfessoresStore(Request $request)
    {

        $request->validate(
            [
                'professor_id' => 'required',
                'escola_id' => 'required',
            ],
            [
                'professor_id.required' => "Senha Obrigatória",
                'escola_id.required' => "Senha Obrigatória",
            ]
        );

        $user = auth()->user();

        if (!$user->can('create: distribuicao de professor')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $professor = Professor::findOrFail($request->professor_id);
        $escola = Shcool::findOrFail($request->escola_id);

        $anolectivo = AnoLectivo::where('status', 'activo')->where('shcools_id', $escola->id)->first();

        $verificar = FuncionariosControto::where('shcools_id', $escola->id)->where('funcionarios_id', $professor->id)->first();

        if ($verificar) {
            return redirect()->route('app.Dispanho-professores-index')->with('danger', 'Esta distribuição não pode ser realizada, este professor já faz parte desta escola!');
        }

        // Criar contrato
        $create3 = new FuncionariosControto();
        $create3->funcionarios_id = $professor->id;
        $create3->documento = time();
        $create3->salario = 0;
        $create3->subcidio = 0;
        $create3->subcidio_alimentacao = 0;
        $create3->subcidio_transporte = 0;

        $create3->subcidio_ferias = 0;
        $create3->subcidio_natal = 0;
        $create3->subcidio_abono_familiar = 0;
        $create3->falta_por_dia =  0;
        $create3->level =  4;

        $create3->data_inicio_contrato = date("Y-m-d");
        $create3->data_final_contrato = date("Y-m-d");
        $create3->hora_entrada_contrato = "18:30";
        $create3->hora_saida_contrato = "18:30";
        $create3->cargo = $request->input('cargo');
        $create3->conta_bancaria = NULL;
        $create3->status_contrato = "activo";
        $create3->status = "activo";
        $create3->iban = NULL;
        $create3->numero_identifcador = time();

        $create3->cargo_geral = "professor";

        $create3->departamento_id = 1;
        $create3->cargo_id = 1;
        $create3->clausula = NULL;
        $create3->nif = NULL;
        $create3->data_at = date("Y-m-d");
        $create3->ano_lectivos_id = $anolectivo->id;
        $create3->shcools_id = $escola->id;
        $create3->save();

        $meses = Mes::all();

        if ($meses) {
            foreach ($meses as $mes) {
                $verificar = CartaoFuncionario::where([
                    ['funcionarios_id', '=', $professor->id],
                    ['mes_id', '=', $mes->id],
                    ['level', '=', 1],
                    ['ano_lectivos_id', '=', $anolectivo->id],
                ])->first();

                if (!$verificar) {
                    $newCreate = new CartaoFuncionario();

                    $newCreate->funcionarios_id = $professor->id;
                    $newCreate->mes_id = $mes->id;
                    $newCreate->level = 1;
                    $newCreate->ano_lectivos_id =  $anolectivo->id;
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
            'type_enviado' => 'ministerio',
            'notificacao' => $text,
            'notificacao_user' => $text2,
            'status' => '0',
            'model_id' => $create3->id,
            'model_type' => "transferencia",
            'shcools_id' => $escola->id
        ]);

        return redirect()->route('app.Dispanho-professores-index')->with('message', 'A Distribuição do Professor realizada com sucesso!');
    }
}
