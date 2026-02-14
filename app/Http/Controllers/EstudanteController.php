<?php

namespace App\Http\Controllers;

use App\Models\Arquivo;
use App\Models\Distrito;
use App\Models\Municipio;
use App\Models\Notificacao;
use App\Models\User;
use App\Models\Paise;
use App\Models\Provincia;
use App\Models\Shcool;
use App\Models\TransferenciaEscolar;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\anolectivo\ControlePeriodico;
use App\Models\web\anolectivo\Trimestre;
use App\Models\web\calendarios\Matricula;
use App\Models\web\calendarios\Deposito;
use App\Models\web\calendarios\PresencaEstudante;
use App\Models\web\calendarios\Servico;
use App\Models\web\calendarios\ServicoTurma;
use App\Models\web\classes\AnoLectivoClasse;
use App\Models\web\classes\Classe;
use App\Models\web\cursos\AnoLectivoCurso;
use App\Models\web\cursos\Curso;
use App\Models\web\encarregados\EncarregadoEstudantes;
use App\Models\web\estudantes\CartaoEstudante;
use App\Models\web\estudantes\Estudante;
use App\Models\web\salas\AnoLectivoSala;
use App\Models\web\salas\Caixa;
use App\Models\web\salas\MovimentoCaixa;
use App\Models\web\turmas\Bolsa;
use App\Models\web\turmas\Bolseiro;
use App\Models\web\turmas\DisciplinaTurma;
use App\Models\web\turmas\EstudantesTurma;
use App\Models\web\turmas\InstituicaoEducacional;
use App\Models\web\turmas\NotaPauta;
use App\Models\web\turmas\Turma;
use App\Models\web\turnos\AnoLectivoTurno;
use App\Models\web\turmas\BolsaInstituicao;
use App\Models\web\turmas\Estagiario;
use App\Models\web\turmas\EstagioInstituicao;
use App\Models\web\turnos\Turno;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;


use SimpleSoftwareIO\QrCode\Facades\QrCode;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class EstudanteController extends Controller
{
    //
    use TraitHelpers;


    public function __construct()
    {
        $this->middleware('auth');
    }

    // matricula estudantes
    public function estudantesMatricula(Request $request)
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
            ['ano_lectivos_id', $this->anolectivoActivo()],
            ['shcools_id', $this->escolarLogada()],
        ])
            ->where('status_inscricao', 'Admitido')
            ->get();



        $paises = Paise::all();
        $provincias = Provincia::all();

        $classes = AnoLectivoClasse::where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('shcools_id', $this->escolarLogada())
            ->with(['classe'])
            ->get();

        $turnos = AnoLectivoTurno::where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('shcools_id', $this->escolarLogada())
            ->with(['turno'])
            ->get();

        $salas = AnoLectivoSala::where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('shcools_id', $this->escolarLogada())
            ->with(['sala'])
            ->get();

        $cursos = AnoLectivoCurso::where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('shcools_id', $this->escolarLogada())
            ->with(['curso'])
            ->get();


        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "Listagem das matriculas",
            "descricao" => env('APP_NAME'),
            "paises" => $paises,
            "provincias" => $provincias,
            "matriculas" => $matriculas,
            "classes" => $classes,
            "turnos" => $turnos,
            "salas" => $salas,
            "cursos" => $cursos,
            "anolectivos" => AnoLectivo::where('shcools_id', $this->escolarLogada())
                ->where('status', 'activo')
                ->get(),
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            "usuario" => User::findOrFail(Auth::user()->id),
            "filtros" => $request->all('status', 'cursos_id', 'classes_id', 'turnos_id'),
        ];

        return view('admin.estudantes.matricula', $headers);
    }

    // estudantes Matriculado e Confirmado proximo ano
    public function estudantesMatriculadoConfirmado(Request $request)
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
        })
            ->with(['ano_lectivo', 'classe_at', 'classe', 'turno', 'curso', 'estudante'])
            ->where('ano_lectivos_id', $this->anolectivoProximo($this->anolectivoActivo()))
            ->where('shcools_id', $this->escolarLogada())
            ->whereIn('status_inscricao', ['Admitido', 'Nao Admitido'])
            ->get();



        $paises = Paise::all();
        $provincias = Provincia::all();

        $classes = AnoLectivoClasse::where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('shcools_id', $this->escolarLogada())
            ->with(['classe'])
            ->get();

        $turnos = AnoLectivoTurno::where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('shcools_id', $this->escolarLogada())
            ->with(['turno'])
            ->get();

        $salas = AnoLectivoSala::where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('shcools_id', $this->escolarLogada())
            ->with(['sala'])
            ->get();

        $cursos = AnoLectivoCurso::where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('shcools_id', $this->escolarLogada())
            ->with(['curso'])
            ->get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "Listagem dos estudantes matriculados e confirmados para o proximo ano",
            "descricao" => env('APP_NAME'),
            "paises" => $paises,
            "provincias" => $provincias,
            "matriculas" => $matriculas,
            "classes" => $classes,
            "turnos" => $turnos,
            "salas" => $salas,
            "cursos" => $cursos,
            "anolectivos" => AnoLectivo::where('shcools_id', $this->escolarLogada())
                ->where('status', 'activo')
                ->get(),
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            "usuario" => User::findOrFail(Auth::user()->id),
            "filtros" => $request->all('status', 'cursos_id', 'classes_id', 'turnos_id'),
        ];

        return view('admin.estudantes.matriculado-confirmado', $headers);
    }

    // view calendarios principal
    public function estudantes(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: estudante') && !$user->can('read: matricula')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $matriculas = Matricula::where('tb_matriculas.status_matricula', '!=', 'nao_confirmado')
            ->where('tb_matriculas.status_matricula', '!=', 'rejeitado')
            ->when($request->status, function ($q, $v) {
                $q->where('status_matricula', $v);
            })->when($request->cursos_id, function ($q, $v) {
                $q->where('cursos_id', $v);
            })->when($request->classes_id, function ($q, $v) {
                $q->where('classes_id', $v);
            })->when($request->turnos_id, function ($q, $v) {
                $q->where('turnos_id', $v);
            })
            ->whereHas('estudante', function ($query) use ($request) {
                $query->when($request->genero, function ($q, $v) {
                    $q->where('genero', $v);
                });
                $query->when($request->finalista, function ($q, $v) {
                    $q->where('finalista', $v);
                });
            })
            ->where('status_inscricao', 'Admitido')
            ->where('tb_matriculas.ano_lectivos_id', '=', $this->anolectivoActivo())
            ->where('tb_matriculas.shcools_id', '=', $this->escolarLogada())
            ->with(['escolas', 'ano_lectivo', 'classe_at', 'estudante', 'classe', 'turno', 'curso'])
            ->get();



        $classes = AnoLectivoClasse::where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('shcools_id', $this->escolarLogada())
            ->with(['classe'])
            ->get();

        $turnos = AnoLectivoTurno::where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('shcools_id', $this->escolarLogada())
            ->with(['turno'])
            ->get();

        $salas = AnoLectivoSala::where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('shcools_id', $this->escolarLogada())
            ->with(['sala'])
            ->get();

        $cursos = AnoLectivoCurso::where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('shcools_id', $this->escolarLogada())
            ->with(['curso'])
            ->get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "classes" => $classes,
            "turnos" => $turnos,
            "cursos" => $cursos,
            "matriculas" => $matriculas,

            "titulo" => "Lista dos Estudantes",
            "descricao" => env('APP_NAME'),
            "estudantes" => Estudante::where([
                ['shcools_id', '=', $this->escolarLogada()],
                ['status', '=', 'activo'],
            ])->get(),
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            "usuario" => User::findOrFail(Auth::user()->id),
            "filtros" => $request->all('status', 'cursos_id', 'classes_id', 'turnos_id', 'genero', 'finalista'),
        ];

        return view('admin.estudantes.home', $headers);
    }

    public function estudantesListagemGeral(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: estudante') && !$user->can('read: matricula')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $matriculas = Estudante::whereNotIn('registro', ['nao_confirmado', 'rejeitado'])
            ->when($request->ano_lectivos_id, function ($q, $v) {
                $q->where('ano_lectivos_id', $v);
            })
            ->when($request->genero, function ($q, $v) {
                $q->where('genero', $v);
            })
            ->when($request->status, function ($q, $v) {
                $q->where('registro', $v);
            })
            ->when($request->estado_civil, function ($q, $v) {
                $q->where('estado_civil', $v);
            })
            ->where('shcools_id', '=', $this->escolarLogada())
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

        $cursos = AnoLectivoCurso::where([
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['shcools_id', '=', $this->escolarLogada()],
        ])
            ->with('curso')
            ->get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "classes" => $classes,
            "turnos" => $turnos,
            "cursos" => $cursos,

            "titulo" => "Lista dos Estudantes",
            "descricao" => env('APP_NAME'),
            "matriculas" => $matriculas,

            "estudantes" => Estudante::where([
                ['shcools_id', '=', $this->escolarLogada()],
                ['status', '=', 'activo'],
            ])->get(),

            "ano_lectivos" => AnoLectivo::where('shcools_id', '=', $this->escolarLogada())->get(),

            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            "usuario" => User::findOrFail(Auth::user()->id),
            "filtros" => $request->all('status', 'genero', 'estado_civil'),
        ];

        return view('admin.estudantes.listagem-geral', $headers);
    }

    // cadastrar ESTUDANTE
    public function cadastrarEstudantes(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('create: matricula') && !$user->can('create: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }


        $validate = Validator::make($request->all(), [
            "nome" => 'required',
            "sobre_nome" => 'required',
            "nascimento" => 'required',
            "genero" => 'required',
            "estado_civil" => 'required',
            "bilheite" => 'required',
        ], [
            "nome.required" => "******",
            "sobre_nome.required" => "*****",
            "nascimento.required" => "*****",
            "genero.required" => "*****",
            "estado_civil.required" => "*****",
            "bilheite.required" => "*****",
        ]);

        $verificarCalendario = Estudante::with('matricula.curso')->with('matricula.turno')->with('matricula.classe', 'escola', 'provincia')->where([
            ['bilheite', '=', $request->input('bilheite')],
        ])->first();

        if ($verificarCalendario) {
            return response()->json([
                'status' => 300,
                'message' => "Este Bilheite já Esta Cadastrado, e Pertence ao estudante!",
                "estudante" => $verificarCalendario
            ]);
        }

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        } else {
            $create = new Estudante();
            $create->documento = time();
            $create->nome = $request->input('nome');
            $create->sobre_nome = $request->input('sobre_nome');
            $create->nascimento = $request->input('nascimento');
            $create->genero = $request->input('genero');
            $create->estado_civil = $request->input('estado_civil');
            $create->nacionalidade = $request->input('nacionalidade');
            $create->dificiencia = $request->input('dificiencia');
            $create->provincia = $request->input('provincia');
            $create->municipio_id = $request->input('municipio_id');
            $create->naturalidade = $request->input('naturalidade');
            $create->bilheite = $request->input('bilheite');
            $create->pai = $request->input('pai');
            $create->mae = $request->input('mae');
            $create->telefone_estudante = $request->input('telefone');
            $create->telefone_pai = $request->input('telefone_pai');
            $create->telefone_mae = $request->input('telefone_mae');
            $create->endereco = $request->input('endereco');
            $create->shcools_id = $this->escolarLogada();
            return response()->json([
                'status' => 200,
                'message' => 'Dados salvos com sucesso!',
                "estudante" => $create,
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        }
    }

    public function situacaFinanceiraEstudantesParaNaoIsento($id)
    {

        $user = auth()->user();

        if (!$user->can('update: matricula') && !$user->can('update: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->findOrFail($this->escolarLogada());
            $ano_lectivo = AnoLectivo::findOrFail($this->anolectivoActivo());

            $estudante = Estudante::findOrFail(Crypt::decrypt($id));

            $matricula = Matricula::where('estudantes_id', $estudante->id)
                ->where('ano_lectivos_id', $ano_lectivo->id)
                ->first();

            /** Turma */
            $turma = EstudantesTurma::where('estudantes_id', $estudante->id)
                ->where('ano_lectivos_id', $ano_lectivo->id)
                ->first();

            /** servico */
            $servico = Servico::where('servico', 'Propinas')
                ->where('shcools_id', $escola->id)
                ->first();

            /** configuração servico na turma */
            $servicoTurma = ServicoTurma::where('turmas_id', $turma->turmas_id)
                ->where('servicos_id', $servico->id)
                ->where('shcools_id', $escola->id)
                ->where('model', 'turmas')
                ->where('ano_lectivos_id', $ano_lectivo->id)
                ->first();

            if ($matricula->condicao == "Paga") {

                $status = "Isento";

                $cartãoComoIsento = CartaoEstudante::withTrashed()->where('estudantes_id', $estudante->id)
                    ->where('status', "Isento")
                    ->where('servicos_id', $servicoTurma->servicos_id)
                    ->where('ano_lectivos_id', $ano_lectivo->id)
                    ->where('deleted_at',  '<>', NULL)
                    ->get();

                if (isset($cartãoComoIsento) && count($cartãoComoIsento) > 0) {
                    foreach ($cartãoComoIsento as $cartao) {
                        $updateCartao = CartaoEstudante::withTrashed()->findOrFail($cartao->id);
                        if (!$updateCartao) {
                            $updateCartao = CartaoEstudante::findOrFail($cartao->id);
                        }
                        $updateCartao->deleted_at = NULL;
                        $updateCartao->preco_unitario = $servicoTurma->preco;
                        $updateCartao->update();
                    }

                    $anularCartaoNaoIsento = CartaoEstudante::where('estudantes_id', $estudante->id)
                        ->where('status',  '<>', "Isento")
                        ->where('servicos_id', $servicoTurma->servicos_id)
                        ->where('ano_lectivos_id', $ano_lectivo->id)
                        ->get();

                    foreach ($anularCartaoNaoIsento as $excluiar) {
                        CartaoEstudante::findOrFail($excluiar->id)->delete();
                    }
                } else {
                    // dd("nao tem cartao");
                    $anularCartaoNaoIsento = CartaoEstudante::where('estudantes_id', $estudante->id)
                        ->where('status',  '<>', "Isento")
                        ->where('servicos_id', $servicoTurma->servicos_id)
                        ->where('ano_lectivos_id', $ano_lectivo->id)
                        ->get();

                    foreach ($anularCartaoNaoIsento as $excluiar) {
                        CartaoEstudante::findOrFail($excluiar->id)->delete();
                    }

                    /**verificar se tem um cartão duplicado para anular */
                    $verificarServicosEstudante = CartaoEstudante::where('estudantes_id', $estudante->id)
                        ->where('servicos_id', $servico->id)
                        ->where('ano_lectivos_id', $ano_lectivo->id)
                        ->first();

                    if (!$verificarServicosEstudante) {

                        if ($escola->ensino->nome == "Ensino Superior") {
                            $controle_periodico = $this->mes_periodico(date("M", strtotime($servicoTurma->data_inicio)), "U", "universidade");
                        } else {
                            $controle_periodico = $this->mes_periodico(date("M", strtotime($servicoTurma->data_inicio)), "U", "geral");
                        }

                        CartaoEstudante::create([
                            "mes_id" => "U",
                            "estudantes_id" => $estudante->id,
                            "servicos_id" => $servicoTurma->servicos_id,
                            "preco_unitario" => $servicoTurma->preco,
                            "data_at" => $servicoTurma->data_inicio,
                            "data_exp" => $servicoTurma->data_final,
                            "month_number" => date("m", strtotime($servicoTurma->data_inicio)),
                            "month_name" => date("M", strtotime($servicoTurma->data_inicio)),
                            "status" => 'Isento',
                            "status_2" => 'Normal',
                            "controle_periodico_id" => $controle_periodico,
                            "ano_lectivos_id" => $ano_lectivo->id,
                        ]);
                    }
                }
            } else if ($matricula->condicao == "Isento") {

                $status = "Paga";

                /** verificar se já tem um cartão como pagante para atualizar */
                $cartaoComoPagante = CartaoEstudante::withTrashed()->where('estudantes_id', $estudante->id)
                    ->where('status',  '<>', "Isento")
                    ->where('servicos_id',  $servicoTurma->servicos_id)
                    ->where('ano_lectivos_id', $ano_lectivo->id)
                    ->where('deleted_at', '<>', NULL)
                    ->get();

                if (isset($cartaoComoPagante) && count($cartaoComoPagante) > 0) {

                    foreach ($cartaoComoPagante as $actualizar) {
                        $updateCartao = CartaoEstudante::withTrashed()->findOrFail($actualizar->id);
                        if (!$updateCartao) {
                            $updateCartao = CartaoEstudante::findOrFail($actualizar->id);
                        }

                        $updateCartao->preco_unitario = $servicoTurma->preco;
                        $updateCartao->deleted_at = NULL;
                        $updateCartao->update();
                    }

                    $cartaoComoIsento = CartaoEstudante::where('estudantes_id', $estudante->id)
                        ->where('status', "Isento")
                        ->where('servicos_id', $servicoTurma->servicos_id)
                        ->where('ano_lectivos_id', $ano_lectivo->id)
                        ->get();

                    foreach ($cartaoComoIsento as $anular) {
                        CartaoEstudante::findOrFail($anular->id)->delete();
                    }
                } else {

                    $cartaoComoIsento = CartaoEstudante::where('estudantes_id', $estudante->id)
                        ->where('status', "Isento")
                        ->where('servicos_id', $servicoTurma->servicos_id)
                        ->where('ano_lectivos_id', $ano_lectivo->id)
                        ->get();

                    foreach ($cartaoComoIsento as $anular) {
                        CartaoEstudante::findOrFail($anular->id)->delete();
                    }

                    // meses
                    $meses = $this->cartao_estudantes_meses(
                        $ano_lectivo->inicio,
                        $servicoTurma->intervalo_pagamento_inicio,
                        $servicoTurma->intervalo_pagamento_final
                    );

                    foreach ($meses as $mes) {
                        $verificarServicosEstudante = CartaoEstudante::where("estudantes_id", $estudante->id)
                            ->where("servicos_id", $servicoTurma->servicos_id)
                            ->where("month_number", $mes['mes'])
                            ->where("month_name", $mes['sigla'])
                            ->where("ano_lectivos_id", $ano_lectivo->id)
                            ->first();

                        if (!$verificarServicosEstudante) {

                            if ($escola->ensino->nome == "Ensino Superior") {
                                $controle_periodico = $this->mes_periodico($mes["sigla"], "M", "universidade");
                            } else {
                                $controle_periodico = $this->mes_periodico($mes["sigla"], "M", "geral");
                            }

                            CartaoEstudante::create([
                                "mes_id" => "M",
                                "estudantes_id" => $estudante->id,
                                "servicos_id" => $servicoTurma->servicos_id,
                                "preco_unitario" => $servicoTurma->preco,

                                "data_at" => $mes['inicio'],
                                "data_exp" => $mes['fim'],
                                "month_number" => $mes["mes"],
                                "month_name" => $mes["sigla"],
                                "multa" => 0,
                                "controle_periodico_id" => $controle_periodico,
                                "status_2" => "Normal",
                                "ano_lectivos_id" => $ano_lectivo->id,
                                "status" => "Nao Pago",
                            ]);
                        }
                    }
                }
            }

            /**finalmente actualizar cartão */
            $updateMatricula = $matricula::findOrFail($matricula->id);
            $updateMatricula->condicao = $status;
            $updateMatricula->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        Alert::success("Successo", "Pagamento Alterado com Sucesso");
        return redirect()->route('web.sistuacao-financeiro', Crypt::encrypt($estudante->id));
    }

    public function estudantesInscricaoExameAcesso(Request $request)
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
            ->sortBy(function ($matricula) {
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


        return view('admin.estudantes.exames-acesso', $headers);
    }


    public function estudantesInscricao(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: estudante') && !$user->can('read: matricula')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        if (!$request->ano_lectivos_id || $request->ano_lectivos_id == null) {
            $request->ano_lectivos_id = $this->anolectivoActivo();
        }

        $idade = $request->idade;

        $matriculas = Matricula::when($request->media, function ($query, $value) {
            $query->where('media', $value);
        })
            ->when($request->ano_lectivos_id, function ($query, $value) {
                $query->where('ano_lectivos_id', $value);
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
            ->where('shcools_id', $this->escolarLogada())
            ->with(['escolas', 'ano_lectivo', 'classe_at', 'estudante', 'classe', 'turno', 'curso'])
            ->get()
            ->sortBy(function ($matricula) {
                return $matricula->estudante->nome;
            });



        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "Listagem de todas candidaturas/Inscrições",
            "descricao" => env('APP_NAME'),
            "matriculas" => $matriculas,

            "classes" => AnoLectivoClasse::where([
                ['ano_lectivos_id', $this->anolectivoActivo()],
                ['shcools_id', $this->escolarLogada()],
            ])
                ->with('classe')
                ->get(),

            "turnos" => AnoLectivoTurno::where([
                ['ano_lectivos_id', $this->anolectivoActivo()],
                ['shcools_id', $this->escolarLogada()],
            ])
                ->with('turno')
                ->get(),

            "salas" => AnoLectivoSala::where([
                ['ano_lectivos_id', $this->anolectivoActivo()],
                ['shcools_id', $this->escolarLogada()],
            ])
                ->with('sala')
                ->get(),

            "anolectivos" => AnoLectivo::where([
                ['shcools_id', $this->escolarLogada()],
            ])->get(),

            "cursos" => AnoLectivoCurso::where([
                ['ano_lectivos_id', $this->anolectivoActivo()],
                ['shcools_id', $this->escolarLogada()],
            ])
                ->with('curso')
                ->get(),
            "usuario" => User::findOrFail(Auth::user()->id),
            "filtros" => $request->all('ano_lectivos_id', 'status', 'cursos_id', 'classes_id', 'turnos_id', 'media', 'idade'),
        ];


        return view('admin.estudantes.inscricao', $headers);
    }

    public function estudantesInscricaoCreate()
    {
        $user = auth()->user();

        if (!$user->can('read: estudante')  && !$user->can('read: matricula')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "Nova Inscrições",
            "descricao" => env('APP_NAME'),

            "paises" => Paise::where('id', 6)->get(),
            "provincias" => Provincia::get(),
            "municipios" => Municipio::get(),
            "distritos" => Distrito::get(),

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

            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.estudantes.inscricao-create', $headers);
    }

    public function estudantesInscricaoStore(Request $request)
    {

        $user = auth()->user();

        if (!$user->can('create: matricula')  && !$user->can('create: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            'nome' => 'required',
            'sobre_nome' => 'required',
            'nascimento'  => 'required',
            'genero'  => 'required',
            'pais_id'  => 'required',
            'provincia_id'  => 'required',
            'municipio_id'  => 'required',
            'bilheite'  => 'required',
            'at_classes_id'  => 'required',
            'classes_id'  => 'required',
            'cursos_id'  => 'required',
            'turnos_id'  => 'required',
            'tipo_matricula'  => 'required',
            'ano_lectivos_id'  => 'required',
        ], [
            'nome.required' => "Compo Obrigatório",
            'sobre_nome.required' => "Compo Obrigatório",
            'nascimento.required'  => 'Compo Obrigatório',
            'genero.required'  => 'Compo Obrigatório',
            'pais_id.required'  => 'Compo Obrigatório',
            'provincia_id.required'  => 'Compo Obrigatório',
            'municipio_id.required'  => 'Compo Obrigatório',
            'bilheite.required'  => 'Compo Obrigatório',
            'at_classes_id.required'  => 'Compo Obrigatório',
            'classes_id.required'  => 'Compo Obrigatório',
            'cursos_id.required'  => 'Compo Obrigatório',
            'turnos_id.required'  => 'Compo Obrigatório',
            'tipo_matricula.required'  => 'Compo Obrigatório',
            'ano_lectivos_id.required'  => 'Compo Obrigatório',
        ]);

        $virificarBI = Estudante::where('bilheite', $request->input('bilheite'))->first();

        if ($virificarBI) {
            Alert::warning('Informação', "Número do Bilheite já Existe registrado!");
            return redirect()->back();
        }

        // ================================================================
        // verfificações
        $escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->findOrFail($this->escolarLogada());

        if ($escola->processo_admissao_estudante == "Prova") {
            $media = 0;
            $prova_acesso = 'Y'; // será que faz prova de exame de acesso para ser admitido
            $exame_acesso = 'N'; // será que já fez esta prova de acesso
        }

        if ($escola->processo_admissao_estudante == "Normal") {
            $media = $request->media;
            $prova_acesso = 'N'; // Não precisa de prova
            $exame_acesso = 'N'; // Não precisa de prova
        }

        // if ($media <= 7  || $media >= 20) {
        //     Alert::warning('Informação', "A média Invalida, precisa esta no intervalo de [7, 20]!");
        //     return redirect()->back();
        // }

        // if ($this->calcularIdade($request->input('nascimento')) >= 14 && $media >= 14) {
        //     $inscricao = 'Admitido';
        // } else {
        $inscricao = 'Nao Admitido';
        // }

        // ================================================================

        $code = time();

        $nacionalidade = Paise::find($request->input('pais_id'));
        $naturalidade = Provincia::find($request->input('provincia_id'));

        if (!empty($request->file('doc_bilheite'))) {
            $image = $request->file('doc_bilheite');
            $imageNameBI = time() . '.' . $image->extension();
            $image->move(public_path('assets/arquivos'), $imageNameBI);
        } else {
            $imageNameBI = NULL;
        }

        if (!empty($request->file('doc_certificado'))) {
            $image2 = $request->file('doc_certificado');
            $imageNameCT = time() . '.' . $image2->extension();
            $image2->move(public_path('assets/arquivos'), $imageNameCT);
        } else {
            $imageNameCT = NULL;
        }

        if (!empty($request->file('doc_outros'))) {
            $image2 = $request->file('doc_outros');
            $imageNameOD = time() . '.' . $image2->extension();
            $image2->move(public_path('assets/arquivos'), $imageNameOD);
        } else {
            $imageNameOD = NULL;
        }

        if (!empty($request->file('doc_atestedao_medico'))) {
            $image2 = $request->file('doc_atestedao_medico');
            $imageNameAT = time() . '.' . $image2->extension();
            $image2->move(public_path('assets/arquivos'), $imageNameAT);
        } else {
            $imageNameAT = NULL;
        }
        try {
            // Inicia a transação
            DB::beginTransaction();

            // cadastrar os dados do estudante
            $create = Estudante::create([
                "documento" => $code,
                "nome" => $request->nome,
                "registro" => 'nao_confirmado',
                "sobre_nome" => $request->sobre_nome,
                "nome_completo" => $request->nome . " " . $request->sobre_nome,
                "nascimento" => $request->nascimento,
                "genero" => $request->genero,
                "estado_civil" => $request->estado_civil,
                "nacionalidade" => $nacionalidade->name,
                "pais_id" => $request->pais_id,
                "provincia_id" => $request->provincia_id,
                "municipio_id" => $request->municipio_id,
                "distrito_id" => $request->distrito_id,
                "dificiencia" => $request->dificiencia,
                "bilheite" => $request->bilheite,
                "pai" => $request->pai,
                "mae" => $request->mae,
                "telefone_estudante" => $request->telefone,
                "telefone_pai" => $request->telefone_pai,
                "telefone_mae" => $request->telefone_mae,
                "endereco" => $request->endereco,
                "naturalidade" => $naturalidade->nome,

                "whatsapp" => $request->whatsapp,
                "instagram" => $request->instagram,
                "facebook" => $request->facebook,
                "email" => $request->email,

                "shcools_id" => $this->escolarLogada(),
                "ano_lectivos_id" => $this->anolectivoActivo(),
                "ano_lectivo_global_id" => $this->anolectivoActivoGlobal()
            ]);

            // cadastar os dados da sua matricula
            $createM = Matricula::create([
                "documento" => $create->documento,
                "status_matricula" => 'nao_confirmado',
                "status_inscricao" => $inscricao,
                "resultado_final" => 'estudando',
                "ficha" => $code,
                "media" => $media,
                "at_classes_id" => $request->input('at_classes_id'),
                "classes_id" => $request->input('classes_id'),
                "cursos_id" => $request->input('cursos_id'),
                "turnos_id" => $request->input('turnos_id'),
                "tipo" => $request->input('tipo_matricula'), // confirmação , Matricula, inscricao
                "status" => 'Novo', // Novo ou repitente
                "condicao" => 'Isento', // Novo ou repitente

                'prova_acesso' => $prova_acesso,
                'exame_acesso' => $exame_acesso,

                'cursos_primeira_opcao_id' => $request->cursos_primeira_opcao_id,
                'cursos_segunda_opcao_id' => $request->cursos_segunda_opcao_id,

                'pais_id' => $escola->pais_id,
                'provincia_id' => $escola->provincia_id,
                'municipio_id' => $escola->municipio_id,
                'distrito_id' => $escola->distrito_id,
                'level' => '1',

                "data_at" => $this->data_sistema(),
                "ano_lectivos_id" => $this->anolectivoActivo(),
                "shcools_id" => $this->escolarLogada(),
                "estudantes_id" => $create->id,
                "funcionarios_id" => Auth::user()->id,
                "ano_lectivo_global_id" => $this->anolectivoActivoGlobal()
            ]);

            // actualizar doados com conta corrente e numero do processo do estuadntes
            $create->conta_corrente = "31.1.2.1." . $create->id;
            $create->update();

            Arquivo::create([
                "codigo" => $code,
                'model_id' => $create->id,
                'model_type' => 'estudante',
                'certificado' => $imageNameCT,
                'bilheite' => $imageNameBI,
                'atestado' => $imageNameAT,
                'outros' => $imageNameOD,
            ]);

            $text = "" . Auth::user()->nome . "  }}, faz uma inscricao do estudante {$request->input('nome')} {$request->input('sobre_nome')} no curso de " . Curso::find($request->input('cursos_id'))->curso . " classe " . Classe::find($request->input('classes_id'))->classes;
            $text2 = "O Sr(a) acabou de fazer uma inscrição para um estudante um estudante";

            Notificacao::create([
                'user_id' => Auth::user()->id,
                'destino' => NULL,
                'type_destino' => 'escola',
                'type_enviado' => 'funcionario',
                'notificacao' => $text,
                'notificacao_user' => $text2,
                'status' => '0',
                'model_id' => $createM->id,
                'model_type' => "inscricao",
                'shcools_id' => $this->escolarLogada()
            ]);

            // Comita a transação se tudo estiver correto
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            Alert::error('Error', $e->getMessage());
        }

        Alert::success('Bom Trabalho', 'Dados salvos com sucesso!');
        return redirect()->route('ficha-matricula2', $createM->ficha);
    }

    public function estudantesInscricaoShow($id)
    {

        $matricula = Matricula::whereIn('tb_matriculas.tipo', ['inscricao', 'candidatura'])
            ->where('tb_matriculas.ano_lectivos_id', '=', $this->anolectivoActivo())
            ->where('tb_matriculas.shcools_id', '=', $this->escolarLogada())
            ->with(['escolas', 'ano_lectivo', 'classe_at', 'estudante', 'classe', 'turno', 'curso'])
            ->findOrFail(Crypt::decrypt($id));



        $encarregado = EncarregadoEstudantes::where([
            ['estudantes_id', '=', $matricula->estudantes_id],
        ])
            ->join('tb_encarregados', 'tb_encarregado_estudantes.encarregados_id', '=', 'tb_encarregados.id')
            ->first();

        $documentos = Arquivo::where('codigo', $matricula->documento)->where('model_id', $matricula->estudantes_id)->where('model_type', 'estudante')->first();


        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "Detalhes da ficha de Inscrição",
            "descricao" => env('APP_NAME'),
            "matricula" => $matricula,

            "documentos" => $documentos,
            'encarregado' => $encarregado,

            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.estudantes.inscricao-show', $headers);
    }


    public function estudantesInscricaoStatus($id)
    {
        DB::beginTransaction();

        try {
            // Realizar operações de banco de dados aqui

            $matricula = Matricula::findOrFail(Crypt::decrypt($id));

            $status = "";

            if ($matricula->status_inscricao == 'Admitido') {
                $status = 'Nao Admitido';
            } else {
                $status = 'Admitido';
            }

            $matricula->status_inscricao = $status;
            $matricula->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }



        Alert::success('Bom Trabalho', 'Dados actualizado com sucesso!');
        return redirect()->back();
    }


    public function calcularIdade($dataNascimento)
    {
        $dataNascimento = new DateTime($dataNascimento);
        $hoje = new DateTime('today');
        $idade = $hoje->diff($dataNascimento)->y;
        return $idade;
    }

    public function estudantesInscricaoAceites(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: estudante')  && !$user->can('read: matricula')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        if (!$request->ano_lectivo_id || $request->ano_lectivo_id == null) {
            $request->ano_lectivo_id = $this->anolectivoActivo();
        }

        $matriculas = Matricula::when($request->ano_lectivo_id, function ($query, $value) {
            $query->where('ano_lectivos_id', $value);
        })
            ->where('status_inscricao', 'Admitido')
            ->whereIn('tipo', ['inscricao', 'candidatura'])
            ->when($request->ano_lectivos_id, function ($query, $value) {
                $query->where('ano_lectivos_id', $value);
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
            ->where('shcools_id', $this->escolarLogada())
            ->with(['escolas', 'ano_lectivo', 'classe_at', 'estudante', 'classe', 'turno', 'curso'])
            ->get()
            ->sortBy(function ($matricula) {
                return $matricula->estudante->nome;
            });



        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "Listagem de todas Inscrições",
            "descricao" => env('APP_NAME'),
            "matriculas" => $matriculas,

            "anolectivos" => AnoLectivo::where([
                ['shcools_id', $this->escolarLogada()],
            ])->get(),

            "usuario" => User::findOrFail(Auth::user()->id),
            "classes" => AnoLectivoClasse::where([
                ['ano_lectivos_id', $this->anolectivoActivo()],
                ['shcools_id', $this->escolarLogada()],
            ])
                ->with(['classe'])
                ->get(),

            "turnos" => AnoLectivoTurno::where([
                ['ano_lectivos_id', $this->anolectivoActivo()],
                ['shcools_id', $this->escolarLogada()],
            ])
                ->with(['turno'])
                ->get(),

            "cursos" => AnoLectivoCurso::where([
                ['ano_lectivos_id', $this->anolectivoActivo()],
                ['shcools_id', $this->escolarLogada()],
            ])
                ->with(['curso'])
                ->get(),
            "filtros" => $request->all('ano_lectivos_id', 'status', 'cursos_id', 'classes_id', 'turnos_id', 'media', 'idade'),
        ];

        return view('admin.estudantes.inscricao-aceites', $headers);
    }

    // editar estudante
    public function editarEstudantes($id)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO

        $user = auth()->user();

        if (!$user->can('update: matricula')  && !$user->can('upadte: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $turmaId = Estudante::findOrFail($id);

        if ($turmaId) {
            return response()->json([
                "status" => 200,
                "estudantes" => $turmaId,
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        } else {
            return response()->json([
                "status" => 404,
                "message" => 'Estudante não Encontrado'
            ]);
        }
    }

    // actualizar estudante
    public function updateEstudantes(Request $request, $id)
    {

        $user = auth()->user();

        if (!$user->can('update: matricula')  && !$user->can('update: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $validate = Validator::make($request->all(), [
            "nome" => 'required',
            "sobre_nome" => 'required',
            "nascimento" => 'required',
            "genero" => 'required',
            "estado_civil" => 'required',
            "bilheite" => 'required',
        ], [
            "nome" => "Campo Obrigatório",
            "sobre_nome" => "Campo Obrigatório",
            "nascimento" => "Campo Obrigatório",
            "genero" => "Campo Obrigatório",
            "estado_civil" => "Campo Obrigatório",
            "bilheite" => "Campo Obrigatório",
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        } else {
            $update = Estudante::findOrFail($id);

            if ($update) {

                $update->nome = $request->input('nome');
                $update->sobre_nome = $request->input('sobre_nome');
                $update->nascimento = $request->input('nascimento');
                $update->genero = $request->input('genero');
                $update->estado_civil = $request->input('estado_civil');
                $update->nacionalidade = $request->input('nacionalidade');
                $update->dificiencia = $request->input('dificiencia');
                $update->bilheite = $request->input('bilheite');
                $update->pai = $request->input('pai');
                $update->munincipio = $request->input('minincipio');
                $update->provincia = $request->input('provincia');
                $update->naturalidade = $request->input('naturalidade');
                $update->mae = $request->input('mae');
                $update->telefone_estudante = $request->input('telefone');
                $update->telefone_pai = $request->input('telefone_pai');
                $update->telefone_mae = $request->input('telefone_mae');
                $update->endereco = $request->input('endereco');

                $update->update();

                return response()->json([
                    'status' => 200,
                    'message' => 'Dados ACtualizados com sucesso!',
                    "usuario" => User::findOrFail(Auth::user()->id),
                ]);
            } else {
                return response()->json([
                    "status" => 404,
                    "message" => 'Turno não Encontrado'
                ]);
            }
        }
    }

    // delete estudante
    public function deleteEstudantes($id)
    {
        $user = auth()->user();

        if (!$user->can('delete: matricula')  && !$user->can('delete: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $estudante = Estudante::findOrFail($id);

            Matricula::where('estudantes_id', $estudante->id)->delete();
            EstudantesTurma::where('estudantes_id', $estudante->id)->delete();
            CartaoEstudante::where('estudantes_id', $estudante->id)->delete();
            NotaPauta::where('estudantes_id', $estudante->id)->delete();
            EncarregadoEstudantes::where('estudantes_id', $estudante->id)->delete();
            PresencaEstudante::where('estudantes_id', $estudante->id)->delete();
            User::where('acesso', "estudante")->where('shcools_id', $this->escolarLogada())->where('funcionarios_id', $estudante->id)->delete();

            $estudante->delete();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json([
            'status' => 200,
            'message' => 'Dados Excluido com sucesso!',
        ]);
    }
    // delete matricula do estudante
    public function deleteMatriculaEstudantes($id)
    {
        $user = auth()->user();

        if (!$user->can('delete: matricula')  && !$user->can('delete: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $matricula = Matricula::findOrFail($id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            EstudantesTurma::where('estudantes_id', $matricula->estudantes_id)->delete();
            CartaoEstudante::where('estudantes_id', $matricula->estudantes_id)->delete();
            NotaPauta::where('estudantes_id', $matricula->estudantes_id)->delete();
            EncarregadoEstudantes::where('estudantes_id', $matricula->estudantes_id)->delete();
            PresencaEstudante::where('estudantes_id', $matricula->estudantes_id)->delete();
            User::where('acesso', "estudante")->where('shcools_id', $this->escolarLogada())->where('funcionarios_id', $matricula->estudantes_id)->delete();

            $matricula->delete();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json([
            'status' => 200,
            'message' => 'Dados Excluido com sucesso!',
        ]);
    }

    // rejeitar matricula estudante
    public function rejeitarMatriculaEstudantes($id)
    {

        $user = auth()->user();

        if (!$user->can('update: matricula')  && !$user->can('update: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        $matricula = Matricula::findOrFail($id);

        try {
            DB::beginTransaction();
            // Eleminar ele na matricula

            if ($matricula->status_matricula == 'rejeitado') {
                return response()->json([
                    'status' => 204,
                    'message' => 'Este operação não foi realizada com sucesso, verifica a matricula!',
                ]);
            }

            $matricula->status_matricula = 'rejeitado';
            $matricula->resultado_final = "desistente";
            $matricula->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json([
            'status' => 200,
            'message' => 'Candidatura/Inscrição Rejeitada com sucesso!',
        ]);
    }

    // reianceitar matricula do estudante
    public function reiaceitarMatriculaEstudantes($id)
    {
        $user = auth()->user();

        if (!$user->can('update: matricula')  && !$user->can('update: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $matricula = Matricula::findOrFail($id);


        try {
            DB::beginTransaction();
            // Eleminar ele na matricula

            if ($matricula->status_matricula == 'nao_confirmado') {
                return response()->json([
                    'status' => 204,
                    'message' => 'Esta operação não foi realizada com sucesso, verifica a matricula!',
                ]);
            }

            $matricula->status_matricula = 'nao_confirmado';
            $matricula->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json([
            'status' => 200,
            'message' => 'Candidatura/Inscrição Rejeitada com sucesso!',
        ]);
    }

    // activar e desactivar turma
    public function activarEstudantes($id)
    {
        $user = auth()->user();

        if (!$user->can('read: matricula')  && !$user->can('read: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $calendario = Estudante::findOrFail($id);
        if ($calendario) {
            if ($calendario->status === 'activo') {
                $calendario->status = 'desactivo';
            } else {
                $calendario->status = 'activo';
            }
            if ($calendario->update()) {
                return response()->json([
                    "status" => 200,
                    "usuario" => User::findOrFail(Auth::user()->id),
                    "message" => "Dodos Activados com sucesso",
                ]);
            }
        }
    }

    // activar e desactivar turma
    public function definir_como_finalista($id)
    {
        $user = auth()->user();

        if (!$user->can('read: matricula')  && !$user->can('read: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $estudante = Estudante::findOrFail(Crypt::decrypt($id));

        $turma = EstudantesTurma::with(['turma.classe'])
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('estudantes_id', $estudante->id)
            ->first();

        $medio = "N";


        if ($estudante->finalista == 'Y') {
            $ano_lectivos_final_id = NULL;
            $status = 'N';
        } else if ($estudante->finalista == 'N') {

            $ano_lectivos_final_id = $this->anolectivoActivo();
            $status = 'Y';

            if ($turma && $turma->turma->classe->classes == "13ª Classe") {
                $medio = "Y";
            } else {
                $medio = "N";
            }
        }

        $estudante->medio_tecnico = $medio;
        $estudante->finalista = $status;
        $estudante->ano_lectivos_final_id = $ano_lectivos_final_id;
        $estudante->update();

        Alert::success('Bom Trabalho', 'Dodos Activados com sucesso!');
        return redirect()->back();
    }

    // Estudant pesquisa
    public function searchEstudantes($id)
    {
        $matricula = Matricula::where([
            ['documento', '=', $id],
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['status_matricula', '=', 'confirmado'],
            ['shcools_id', '=', $this->escolarLogada()],
        ])->first();

        if (!$matricula) {
            return redirect()->route('pesquisa-sem-resultado');
        }

        $estudantes = Estudante::findOrFail($matricula->estudantes_id);



        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "estudantes" => $estudantes,
            "matriculas" => $matricula,
            "curso" => Curso::findOrFail($matricula->cursos_id),
            "turno" => Turno::findOrFail($matricula->turnos_id),
            "classe" => Classe::findOrFail($matricula->classes_id),

            "turma" => Turma::where([
                ['cursos_id', '=', $matricula->cursos_id],
                ['classes_id', '=', $matricula->classes_id],
                ['turnos_id', '=', $matricula->turnos_id],
                ['ano_lectivos_id', '=', $this->anolectivoActivo()]
            ])->first(),

            "usuario" => User::findOrFail(Auth::user()->id),
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
        ];

        return view('admin.estudantes.resultado-pesquisa', $headers);
    }

    // Estudant pesquisa
    public function pesquisarEstudanteIndex(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: matricula')  && !$user->can('read: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "usuario" => User::findOrFail(Auth::user()->id),
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
        ];

        return view('admin.estudantes.search', $headers);
    }

    // Estudant pesquisa
    public function pesquisarEstudante(Request $request)
    {
        if ($request->processo_input != "" && $request->processo_input != null && !empty($request->processo_input)) {
            $estudante = Estudante::where('numero_processo', $request->processo_input)
                ->orWhere('bilheite', $request->processo_input)
                ->orWhere('id', $request->processo_input)
                ->orWhere('nome_completo', 'LIKE', "%" . $request->processo_input . "%")
                ->where('shcools_id', $this->escolarLogada())
                ->first();

            if ($estudante) {
                return response()->json(['redirect' => route('shcools.mais-informacao-estudante', Crypt::encrypt($estudante->id))]);
                // return redirect()->route('shcools.mais-informacao-estudante', Crypt::encrypt($estudante->id));
            } else {
                return response()->json(['redirect' => route('pesquisa-sem-resultado')]);
                // return redirect()->route('pesquisa-sem-resultado');
            }
        }

        return response()->json(['redirect' => route('shcools.pesquisar-estudante-index'), 'status' => 303]);
    }

    // mais informaões do estudantes
    public function maisInformacoesEstudantes($id)
    {
        $user = auth()->user();

        if (!$user->can('read: estudante') && !$user->can('read: matricula')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $estudante = Estudante::with('municipio', 'provincia')->findOrFail(Crypt::decrypt($id));

        $matricula = Matricula::where('estudantes_id', $estudante->id)
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->first();

        $matriculas = Matricula::where('estudantes_id', $estudante->id)
            ->where('shcools_id', $this->escolarLogada())
            ->with(['pais', 'provincia', 'municipio', 'distrito', 'ano_lectivo', 'classe_at', 'classe', 'turno', 'curso', 'estudante'])
            ->get();

        // esta em alguma turma
        $turma = EstudantesTurma::with(['turma'])->where('estudantes_id', $estudante->id)->where('ano_lectivos_id', $this->anolectivoActivo())->first();

        $depositos = Deposito::with(['escola', 'estudante', 'ano', 'operador'])
            ->where('estudantes_id', $estudante->id)
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->get();

        $encarregado = EncarregadoEstudantes::where('estudantes_id', $estudante->id)
            ->join('tb_encarregados', 'tb_encarregado_estudantes.encarregados_id', '=', 'tb_encarregados.id')
            ->first();

        $documentos = Arquivo::where('model_id', $estudante->id)->where('model_type', 'estudante')->first();

        $url = Crypt::encrypt($estudante->id); //route('shcools.mais-informacao-estudante', $estudante->id); // URL para abrir os detalhes do estudante
        $qrCode = QrCode::size(200)->generate($url);

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Informações geral do estudante",
            "descricao" => "gestão de discipinas",
            "documentos" => $documentos,
            'estudante' => $estudante,
            'matricula' => $matricula,
            'matriculas' => $matriculas,
            'encarregado' => $encarregado,
            'turma' => $turma,
            'depositos' => $depositos,
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.estudantes.mais-informacoes', $headers, compact('qrCode'));
    }

    // mais informaões do estudantes
    public function actualizarSaldo($id)
    {
        $user = auth()->user();
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        if (!$user->can('create: deposito') && $escola->modulo != "Basico") {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $estudante = Estudante::with('municipio', 'provincia')->findOrFail(Crypt::decrypt($id));

        $headers = [
            "escola" => $escola,
            "titulo" => "Depositos",
            "descricao" => "Estudante",
            'estudante' => $estudante,
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.estudantes.actualizar-saldo', $headers);
    }

    // mais informaões do estudantes
    public function actualizarSaldoStore(Request $request)
    {

        $user = auth()->user();
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        if (!$user->can('create: deposito') && $escola->modulo != "Basico") {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            'saldo' => 'required',
            'forma_de_pagamento' => 'required',
        ]);
        // caixa
        if ($escola->categoria == "Privado") {
            if ($escola->modulo != "Basico") {

                $caixa = Caixa::where('status', '=', "activo")->where('shcools_id', '=', $this->escolarLogada())->where('usuario_id', '=', Auth::user()->id)->first();

                if (!$caixa) {
                    Alert::warning('Informação', "Não há nenhum caixa aberto no momento. Por favor, abra um caixa para continuar o processo.");
                    return redirect()->back();
                }

                $caixaAberto = MovimentoCaixa::where([
                    ['caixa_id', $caixa->id],
                    ['usuario_id', Auth::user()->id],
                    ['status', "aberto"],
                ])->first()->id;

                if (!$caixaAberto) {
                    Alert::warning('Informação', "Não há nenhum caixa aberto no momento. Por favor, abra um caixa para continuar o processo.");
                    return redirect()->back();
                }
            }
        }

        try {
            DB::beginTransaction();

            $estudante = Estudante::findOrFail($request->estudante_id);

            // Realizar operações de banco de dados aqui
            $create = Deposito::create([
                'valor' => $request->saldo,
                'valor_anterior' => $estudante->saldo,
                'forma_de_pagamento' => $request->forma_de_pagamento,
                'descricao' => $request->descricao,
                'date_at' => date("Y-m-d"),
                'status' => 'Entrada',
                'funcionarios_id' => Auth::user()->id,
                'estudantes_id' => $request->estudante_id,
                'ano_lectivos_id' => $this->anolectivoActivo(),
                'shcools_id' => $this->escolarLogada(),
            ]);

            $create->save();

            $estudante->saldo = $estudante->saldo + $request->saldo;
            $estudante->update();

            if ($escola->modulo != "Basico") {
                $updateCaixaAberto = MovimentoCaixa::findOrFail($caixaAberto);
                if ($request->forma_de_pagamento == "NU") {
                    $updateCaixaAberto->valor_cache = $updateCaixaAberto->valor_cache + $request->saldo;
                }
                if ($request->forma_de_pagamento == "MB") {
                    $updateCaixaAberto->valor_tpa = $updateCaixaAberto->valor_tpa + $request->saldo;
                }
                $updateCaixaAberto->valor_fecha = $updateCaixaAberto->valor_fecha + $request->saldo;
                $updateCaixaAberto->qtd_itens = $updateCaixaAberto->qtd_itens + 1;
                $updateCaixaAberto->update();
            }

            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        Alert::success('Bom trabalho', 'Deposito realizado com sucesso!');
        return redirect()->back();
        // Se todas as operações foram bem-sucedidas, você pode fazer o commit

    }


    // mais informaões do estudantes
    public function removerSaldo($id)
    {
        $user = auth()->user();
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        if (!$user->can('create: deposito') && $escola->modulo != "Basico") {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $estudante = Estudante::with('municipio', 'provincia')->findOrFail(Crypt::decrypt($id));

        // esta em alguma turma
        $turma = EstudantesTurma::with(['turma'])->where('estudantes_id', $estudante->id)->where('ano_lectivos_id', '=', $this->anolectivoActivo())->first();

        $servicos = ServicoTurma::with(['servico'])->where('turmas_id', $turma->turmas_id)->get();

        $headers = [
            "escola" => $escola,
            "titulo" => "Levantamento",
            "descricao" => "Levantamento",
            'estudante' => $estudante,
            'servicos' => $servicos,
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.estudantes.retirar-saldo', $headers);
    }
    // mais informaões do estudantes
    public function removerSaldoStore(Request $request)
    {
        $user = auth()->user();
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        if (!$user->can('create: deposito') && $escola->modulo != "Basico") {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            'saldo' => 'required',
            'saida_valor_id' => 'required',
        ]);

        try {
            DB::beginTransaction();

            if ($request->saldo > $request->credito_estudante) {
                Alert::warning('Informação', 'Operação recusada, o valor a retirar é superior ao saldo do estudante!');
                return redirect()->back();
            }

            $estudante = Estudante::findOrFail($request->estudante_id);

            // Realizar operações de banco de dados aqui
            $create = Deposito::create([
                'status' => "Saida",
                'saida_valor_id' => $request->saida_valor_id,
                'valor' => $request->saldo,
                'valor_anterior' => $estudante->saldo,
                'forma_de_pagamento' => "NU",
                'descricao' => $request->descricao,
                'date_at' => date("Y-m-d"),
                'funcionarios_id' => Auth::user()->id,
                'estudantes_id' => $request->estudante_id,
                'ano_lectivos_id' => $this->anolectivoActivo(),
                'shcools_id' => $this->escolarLogada(),
            ]);

            $create->save();

            $estudante->saldo = $estudante->saldo - $request->saldo;
            $estudante->saldo_anterior = $estudante->saldo_anterior - $estudante->saldo;
            $estudante->update();

            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }


        Alert::success('Bom trabalho', 'Operação realizado com Sucesso!');
        return redirect()->back();
    }


    // mais informaões do estudantes
    public function historicosEstudantes(Request $request, $id)
    {
        $user = auth()->user();

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        if (!$user->can('read: matricula') && !$user->can('read: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        if ($request->ano_lectivo) {
            $ano_lectivo_principal = $request->ano_lectivo;
        } else {
            $ano_lectivo_principal = $this->anolectivoActivo();
        }

        $estudante = Estudante::findOrFail(Crypt::decrypt($id));



        $transferencias = TransferenciaEscolar::with([
            'user',
            'estudante',
            'origem',
            'destino'
        ])->where('estudantes_id', $estudante->id)->get();

        $matriculas = Matricula::with([
            'classe_at',
            'classe',
            'turno',
            'curso',
            'estudante',
            'ano_lectivo'
        ])
            ->where('estudantes_id', $estudante->id)
            ->orderBy('id', 'desc')
            ->get();

        $cartoes = CartaoEstudante::with(['servico', 'ano'])
            ->where('estudantes_id', $estudante->id)
            ->get();

        $estudanteTurma = EstudantesTurma::where([
            ['estudantes_id', '=', $estudante->id],
            ['ano_lectivos_id', '=',  $ano_lectivo_principal],
        ])->first();

        if (!$estudanteTurma) {
            $servicosTurma = null;
            // Alert::warning("Informação", "Infelizmente não pode acessar esta área porque estudante não estava inserido em nenhuma turma neste Ano Lectivo!");
            // return redirect()->back();
        } else {

            $servicosTurma = ServicoTurma::where([
                ['turmas_id', '=', $estudanteTurma->turmas_id],
                ['model', '=', 'turmas'],
                ['ano_lectivos_id', '=',  $ano_lectivo_principal],
            ])
                ->join('tb_servicos', 'tb_servicos_turma.servicos_id', '=', 'tb_servicos.id')
                ->get();
        }

        /**
         * npotas
         */

        if ($escola->ensino->nome == "Ensino Superior") {
            $simestre1 = ControlePeriodico::where('trimestre', 'Iª Simestre')->first();
            $simestre2 = ControlePeriodico::where('trimestre', 'IIª Simestre')->first();
            $anual = ControlePeriodico::where('trimestre', 'Anual')->first();

            $notasSomaMdf = NotaPauta::where('estudantes_id', $estudante->id)->whereIn('controlo_trimestres_id', [$simestre1->id, $simestre2->id, $anual->id])->where('ano_lectivos_id',  $ano_lectivo_principal)->sum('resultado_final');
            $notasSomaNe = NotaPauta::where('estudantes_id', $estudante->id)->whereIn('controlo_trimestres_id', [$simestre1->id, $simestre2->id, $anual->id])->where('ano_lectivos_id',  $ano_lectivo_principal)->sum('resultado_final');
        } else {
            $trimestre1 = ControlePeriodico::where('trimestre', 'Iª Trimestre')->first();
            $trimestre2 = ControlePeriodico::where('trimestre', 'IIª Trimestre')->first();
            $trimestre3 = ControlePeriodico::where('trimestre', 'IIIª Trimestre')->first();
            $trimestre4 = ControlePeriodico::where('trimestre', 'Geral')->first();

            $notasSomaMdf = NotaPauta::where('estudantes_id', $estudante->id)->where('controlo_trimestres_id', $trimestre4->id)->where('ano_lectivos_id',  $ano_lectivo_principal)->sum('mfd');
            $notasSomaNe = NotaPauta::where('estudantes_id', $estudante->id)->where('controlo_trimestres_id', $trimestre4->id)->where('ano_lectivos_id',  $ano_lectivo_principal)->sum('ne');
        }

        // notas turma do estudante
        $turmasEstudante = EstudantesTurma::where([
            ['estudantes_id', '=', $estudante->id],
            ['ano_lectivos_id', '=',  $ano_lectivo_principal],
        ])->first();
        //turma dele

        //total disciplnas turma
        if ($turmasEstudante) {
            $turma = Turma::findOrFail($turmasEstudante->turmas_id);

            $totalDisciplinas = DisciplinaTurma::where([
                ['turmas_id', '=', $turma->id]
            ])->count('id');

            $turmaDisciplinas = DisciplinaTurma::where([
                ['turmas_id', '=', $turma->id],
            ])
                ->join('tb_disciplinas', 'tb_discplinas_turmas.disciplinas_id', '=', 'tb_disciplinas.id')
                ->join('tb_turmas', 'tb_discplinas_turmas.turmas_id', '=', 'tb_turmas.id')
                ->select('tb_disciplinas.id')
                ->get();
        } else {
            $turma = null;
            $totalDisciplinas = null;
            $turmaDisciplinas = null;
        }

        $headers = [
            "escola" => $escola,
            "servicosTurma" => $servicosTurma,

            "titulo" => "Historico do Estudante",
            "descricao" => "gestão de discipinas",
            'estudante' => $estudante,

            'transferencias' => $transferencias,
            'matriculas' => $matriculas,
            'cartoes' => $cartoes,
            "ano" => AnoLectivo::findOrFail($ano_lectivo_principal),
            "anos" => AnoLectivo::where('shcools_id', $this->escolarLogada())->get(),

            "usuario" => User::findOrFail(Auth::user()->id),
            //notas
            "turmaDisciplinas" => $turmaDisciplinas,

            "anoLectivo" => AnoLectivo::findOrFail($ano_lectivo_principal),
            "somaMFD" => $notasSomaMdf ?? 0,
            "somaNE" => $notasSomaNe ?? 0,
            'totalDisciplinas' => $totalDisciplinas ?? 0,
            'trimestre1' => $trimestre1 ?? 0,
            'trimestre2' => $trimestre2 ?? 0,
            'trimestre3' => $trimestre3 ?? 0,
            'trimestre4' => $trimestre4 ?? 0,

            "requests" => $request->all('ano_lectivo')
        ];


        return view('admin.estudantes.historicos', $headers);
    }

    // ESTAGIARIOS START
    public function estudantesAtribuirEstagio(Request $request, $id = null)
    {
        $user = auth()->user();

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        if (!$user->can('read: matricula') && !$user->can('read: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        if ($id) {
            $id = Crypt::decrypt($id);
        } else {
            $id = "";
        }

        $estudante = Estudante::find($id);

        $estudantes = Estudante::where('registro', 'confirmado')->where('shcools_id', $this->escolarLogada())->get();



        $estagios = Bolsa::where('type', 'E')->where('shcools_id', $escola->id)->get();
        $instituicoes = InstituicaoEducacional::where('type', 'E')->where('shcools_id', $escola->id)->get();
        $anos_lectivos = AnoLectivo::where('shcools_id', $escola->id)->get();
        $ano_lectivo = AnoLectivo::findOrFail($this->anolectivoActivo());

        $headers = [
            "escola" => $escola,

            "titulo" => "Atribuir Estagio a Estudante",
            "descricao" => env('APP_NAME'),
            'estudante' => $estudante,
            'estudantes' => $estudantes,
            'estagios' => $estagios,
            'instituicoes' => $instituicoes,
            'anos_lectivos' => $anos_lectivos,
            'ano_lectivo' => $ano_lectivo,
        ];

        return view('admin.estudantes.atribuir-estagio', $headers);
    }

    // mais informaões do estudantes
    public function estudantesAtribuirEstagioStore(Request $request)
    {

        $user = auth()->user();

        $request->validate([
            'estudante_id' => 'required',
            'instituicao_id' => 'required',
            'estagio_id' => 'required',
            'ano_lectivos_id' => 'required',
        ], [
            'estudante_id.required' => 'Campo Obrigatório',
            'instituicao_id.required' => 'Campo Obrigatório',
            'estagio_id.required' => 'Campo Obrigatório',
            'ano_lectivos_id.required' => 'Campo Obrigatório',
        ]);

        try {
            DB::beginTransaction();

            $instutuicao_estagio = EstagioInstituicao::where('estagio_id', $request->estagio_id)->where('instituicao_id', $request->instituicao_id)->first();

            $verificar_bolsa = Estagiario::where('ano_lectivos_id', $request->ano_lectivos_id)
                ->where('instituicao_id', $request->instituicao_id)
                ->where('estagio_id', $request->estagio_id)
                ->where('estudante_id', $request->estudante_id)
                ->where('instutuicao_estagio_id', $instutuicao_estagio->id)
                ->first();

            if (!$verificar_bolsa) {

                Estagiario::create([
                    'pago_at' => 'nao_pago',
                    'status' => 'activo',
                    'estudante_id' => $request->estudante_id,
                    'instutuicao_estagio_id' => $instutuicao_estagio->id,
                    'estagio_id' => $request->estagio_id,
                    'instituicao_id' => $request->instituicao_id,
                    'data_inicio' => $request->data_inicio,
                    'data_final' => $request->data_final,
                    'ano_lectivos_id' => $request->ano_lectivos_id,
                    'shcools_id' => $this->escolarLogada(),
                ]);
            }


            DB::commit();

            Alert::success('Bom trabalho', 'Estagio Atribuido com sucesso ao estudante!');

            return redirect()->route('instituicoes_estagios.instituicao-listar-estagiarios');
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit

        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
    }

    // mais informaões do estudantes
    public function estudantesEditarEstagiarioEstagio(Request $request, $id = null)
    {

        $user = auth()->user();

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        $estagiario = Estagiario::find(Crypt::decrypt($id));

        $estudantes = Estudante::where('registro', 'confirmado')->where('shcools_id', $this->escolarLogada())->get();



        $estagios = Bolsa::where('type', 'E')->where('shcools_id', $escola->id)->get();
        $instituicoes = InstituicaoEducacional::where('type', 'E')->where('shcools_id', $escola->id)->get();

        $anos_lectivos = AnoLectivo::where('shcools_id', $escola->id)->get();
        $ano_lectivo = AnoLectivo::findOrFail($this->anolectivoActivo());

        $headers = [
            "escola" => $escola,
            "titulo" => "Editar Estagiario",
            "descricao" => env('APP_NAME'),
            'estagiario' => $estagiario,
            'estudantes' => $estudantes,
            'estagios' => $estagios,
            'instituicoes' => $instituicoes,
            'anos_lectivos' => $anos_lectivos,
            'ano_lectivo' => $ano_lectivo,
        ];

        return view('admin.estudantes.editar-estagiario', $headers);
    }

    // mais informaões do estudantes
    public function estudantesEditarBolseiroEstagiarioUpdate(Request $request, $id)
    {
        $user = auth()->user();

        $request->validate([
            'estudante_id' => 'required',
            'instituicao_id' => 'required',
            'estagio_id' => 'required',
            'ano_lectivos_id' => 'required',
        ], [
            'estudante_id.required' => 'Campo Obrigatório',
            'instituicao_id.required' => 'Campo Obrigatório',
            'estagio_id.required' => 'Campo Obrigatório',
            'ano_lectivos_id.required' => 'Campo Obrigatório',
        ]);

        try {
            DB::beginTransaction();

            $instutuicao_estagio = EstagioInstituicao::where('estagio_id', $request->estagio_id)->where('instituicao_id', $request->instituicao_id)->first();

            if ($instutuicao_estagio) {

                $update_bolseiro = Estagiario::findOrFail($id);

                $update_bolseiro->pago_at = $request->pago_at;
                $update_bolseiro->status = $request->status;
                $update_bolseiro->estudante_id = $request->estudante_id;
                $update_bolseiro->instutuicao_estagio_id = $instutuicao_estagio->id;
                $update_bolseiro->estagio_id = $request->estagio_id;
                $update_bolseiro->instituicao_id = $request->instituicao_id;
                $update_bolseiro->ano_lectivos_id = $request->ano_lectivos_id;
                $update_bolseiro->data_inicio = $request->data_inicio;
                $update_bolseiro->data_final = $request->data_final;

                $update_bolseiro->update();
            } else {
                Alert::warning('Alerta', 'O Estagio não pertence a esta instituição!');
                return redirect()->back();
            }

            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        Alert::success('Bom trabalho', 'Estagio Atribuido com sucesso ao estudante!');
        return redirect()->route('instituicoes_estagios.instituicao-listar-estagiarios');
        // Se todas as operações foram bem-sucedidas, você pode fazer o commit

    }

    // ESTAGIARIOS END

    // BOLSEIROS START
    // mais informaões do estudantes
    public function estudantesAtribuirBolsa(Request $request, $id = null)
    {
        $user = auth()->user();

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        if (!$user->can('read: matricula') && !$user->can('read: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        if ($id) {
            $id = Crypt::decrypt($id);
        } else {
            $id = "";
        }

        $estudante = Estudante::find($id);

        $estudantes = Estudante::where('registro', 'confirmado')->where('shcools_id', $this->escolarLogada())->get();

        $bolsas = Bolsa::where('type', 'B')->where('shcools_id', $escola->id)->get();
        $instituicoes = InstituicaoEducacional::where('type', 'B')->where('shcools_id', $escola->id)->get();
        $anos_lectivos = AnoLectivo::where('shcools_id', $escola->id)->get();
        $ano_lectivo = AnoLectivo::findOrFail($this->anolectivoActivo());

        if ($escola->ensino->nome == "Ensino Superior") {
            $trimestres = ControlePeriodico::where('ensino_status', '2')->get();
        } else {
            $trimestres = ControlePeriodico::where('ensino_status', '1')->get();
        }

        $headers = [
            "escola" => $escola,
            "titulo" => "Atribuir Bolsa a Estudante",
            "descricao" => env('APP_NAME'),
            "estudante" => $estudante,
            "estudantes" => $estudantes,
            "bolsas" => $bolsas,
            "instituicoes" => $instituicoes,
            "anos_lectivos" => $anos_lectivos,
            "ano_lectivo" => $ano_lectivo,
            "trimestres" => $trimestres,
        ];

        return view('admin.estudantes.atribuir-bolsa', $headers);
    }

    // mais informaões do estudantes
    public function estudantesAtribuirBolsaStore(Request $request)
    {

        $user = auth()->user();

        $request->validate([
            'estudante_id' => 'required',
            'instituicao_id' => 'required',
            'bolsa_id' => 'required',
            'ano_lectivos_id' => 'required',
            'periodo_bolsa' => 'required',
            'afectacao' => 'required',

        ], [
            'estudante_id.required' => 'Campo Obrigatório',
            'instituicao_id.required' => 'Campo Obrigatório',
            'bolsa_id.required' => 'Campo Obrigatório',
            'ano_lectivos_id.required' => 'Campo Obrigatório',
            'periodo_bolsa.required' => 'Campo Obrigatório',
            'afectacao.required' => 'Campo Obrigatório',
        ]);

        try {
            DB::beginTransaction();

            $instutuicao_bolsa = BolsaInstituicao::where('bolsa_id', $request->bolsa_id)->where('instituicao_id', $request->instituicao_id)->first();

            $verificar_bolsa = Bolseiro::where('periodo_id', $request->periodo_bolsa)
                ->where('ano_lectivos_id', $request->ano_lectivos_id)
                ->where('instituicao_id', $request->instituicao_id)
                ->where('bolsa_id', $request->bolsa_id)
                ->where('estudante_id', $request->estudante_id)
                ->where('instutuicao_bolsa_id', $instutuicao_bolsa->id)
                ->first();

            if (!$verificar_bolsa) {

                if ($instutuicao_bolsa->desconto == 100) {
                    $cobertura = 'Y';
                } else {
                    $cobertura = 'N';
                }

                Bolseiro::create([
                    'status' => 'activo',
                    'afectacao' => $request->afectacao,
                    'estudante_id' => $request->estudante_id,
                    'instutuicao_bolsa_id' => $instutuicao_bolsa->id,
                    'bolsa_id' => $request->bolsa_id,
                    'instituicao_id' => $request->instituicao_id,
                    'periodo_id' => $request->periodo_bolsa,
                    'ano_lectivos_id' => $request->ano_lectivos_id,
                    'shcools_id' => $this->escolarLogada(),
                ]);

                $trimestre = Trimestre::findOrFail($request->periodo_bolsa);

                $estudanteTurma = EstudantesTurma::where([
                    ['tb_turmas_estudantes.estudantes_id', '=', $request->estudante_id],
                    ['tb_turmas_estudantes.ano_lectivos_id', '=', $request->ano_lectivos_id],
                    ['tb_servicos_turma.model', '=', 'turmas'],
                ])
                    ->join('tb_servicos_turma', 'tb_turmas_estudantes.turmas_id', '=', 'tb_servicos_turma.turmas_id')
                    ->get();

                $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

                if ($escola->ensino->nome == "Ensino Superior") {
                    foreach ($estudanteTurma as $servico) {
                        if ($request->afectacao == "mensalidade") {

                            if ($servico->pagamento == 'mensal') {

                                if ($trimestre->trimestre == "Iª Simestre") {
                                    $cartao = CartaoEstudante::where('semestral', "1º Semestre")->where('ano_lectivos_id', $request->ano_lectivos_id)->where('servicos_id', $servico->servicos_id)->where('estudantes_id', $request->estudante_id)->get();
                                }
                                if ($trimestre->trimestre == "IIª Simestre") {
                                    $cartao = CartaoEstudante::where('semestral', "2º Semestre")->where('ano_lectivos_id', $request->ano_lectivos_id)->where('servicos_id', $servico->servicos_id)->where('estudantes_id', $request->estudante_id)->get();
                                }
                                if ($trimestre->trimestre == "Anual") {
                                    $cartao = CartaoEstudante::whereIn('semestral', ["1º Semestre", "2º Semestre"])->where('ano_lectivos_id', $request->ano_lectivos_id)->where('servicos_id', $servico->servicos_id)->where('estudantes_id', $request->estudante_id)->get();
                                }

                                if ($instutuicao_bolsa->desconto == 100) {
                                    if ($cartao) {
                                        foreach ($cartao as $cart) {
                                            $update = CartaoEstudante::findOrFail($cart->id);
                                            $update->status = "Pago";
                                            $update->status_2 = "Bolsa";
                                            $update->cobertura = $cobertura;
                                            $update->update();
                                        }
                                    }
                                } else {
                                    if ($cartao) {
                                        foreach ($cartao as $cart) {
                                            $update = CartaoEstudante::findOrFail($cart->id);
                                            $update->status = "Nao Pago";
                                            $update->status_2 = "Bolsa";
                                            $update->cobertura = $cobertura;
                                            $update->update();
                                        }
                                    }
                                }
                            }
                        }

                        if ($request->afectacao == "global") {

                            if ($trimestre->trimestre == "Iª Simestre") {
                                $cartao = CartaoEstudante::whereIn('semestral', ["1º Semestre", "Normal"])->where('ano_lectivos_id', $request->ano_lectivos_id)->where('estudantes_id', $request->estudante_id)->get();
                            }
                            if ($trimestre->trimestre == "IIª Simestre") {
                                $cartao = CartaoEstudante::whereIn('semestral', ["2º Semestre", "Normal"])->where('ano_lectivos_id', $request->ano_lectivos_id)->where('estudantes_id', $request->estudante_id)->get();
                            }
                            if ($trimestre->trimestre == "Anual") {
                                $cartao = CartaoEstudante::whereIn('semestral', ["1º Semestre", "2º Semestre", "Normal"])->where('ano_lectivos_id', $request->ano_lectivos_id)->where('estudantes_id', $request->estudante_id)->get();
                            }


                            $cartao = CartaoEstudante::where('estudantes_id', $request->estudante_id)->get();
                            if ($cartao) {
                                foreach ($cartao as $cart) {
                                    $update = CartaoEstudante::findOrFail($cart->id);
                                    $update->status = "Pago";
                                    $update->status_2 = "Bolsa";
                                    $update->cobertura = $cobertura;
                                    $update->update();
                                }
                            }
                        }
                    }
                } else {

                    if ($request->afectacao == "mensalidade") {
                        foreach ($estudanteTurma as $servico) {
                            if ($servico->pagamento == 'mensal') {

                                if ($trimestre->trimestre == "Iª Trimestre") {
                                    $cartao = CartaoEstudante::where('trimestral', "1º Trimestre")->where('ano_lectivos_id', $request->ano_lectivos_id)->where('servicos_id', $servico->servicos_id)->where('estudantes_id', $request->estudante_id)->get();
                                }
                                if ($trimestre->trimestre == "IIª Trimestre") {
                                    $cartao = CartaoEstudante::where('trimestral', "2º Trimestre")->where('ano_lectivos_id', $request->ano_lectivos_id)->where('servicos_id', $servico->servicos_id)->where('estudantes_id', $request->estudante_id)->get();
                                }
                                if ($trimestre->trimestre == "IIIª Trimestre") {
                                    $cartao = CartaoEstudante::where('trimestral', "3º Trimestre")->where('ano_lectivos_id', $request->ano_lectivos_id)->where('servicos_id', $servico->servicos_id)->where('estudantes_id', $request->estudante_id)->get();
                                }

                                if ($trimestre->trimestre == "Geral") {
                                    $cartao = CartaoEstudante::whereIn('trimestral', ["1º Trimestre", "2º Trimestre", "3º Trimestre"])->where('ano_lectivos_id', $request->ano_lectivos_id)->where('servicos_id', $servico->servicos_id)->where('estudantes_id', $request->estudante_id)->get();
                                }

                                if ($instutuicao_bolsa->desconto == 100) {
                                    if ($cartao) {
                                        foreach ($cartao as $cart) {
                                            $update = CartaoEstudante::findOrFail($cart->id);
                                            $update->status = "Pago";
                                            $update->status_2 = "Bolsa";
                                            $update->cobertura = $cobertura;
                                            $update->update();
                                        }
                                    }
                                } else {
                                    if ($cartao) {
                                        foreach ($cartao as $cart) {
                                            $update = CartaoEstudante::findOrFail($cart->id);
                                            $update->status = "Nao Pago";
                                            $update->status_2 = "Bolsa";
                                            $update->cobertura = $cobertura;
                                            $update->update();
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if ($request->afectacao == "global") {

                        if ($trimestre->trimestre == "Iª Trimestre") {
                            $cartao = CartaoEstudante::whereIn('trimestral', ["1º Trimestre", "Normal"])->where('ano_lectivos_id', $request->ano_lectivos_id)->where('estudantes_id', $request->estudante_id)->get();
                        }
                        if ($trimestre->trimestre == "IIª Trimestre") {
                            $cartao = CartaoEstudante::whereIn('trimestral', ["2º Trimestre", "Normal"])->where('ano_lectivos_id', $request->ano_lectivos_id)->where('estudantes_id', $request->estudante_id)->get();
                        }
                        if ($trimestre->trimestre == "IIIª Trimestre") {
                            $cartao = CartaoEstudante::whereIn('trimestral', ["3º Trimestre", "Normal"])->where('ano_lectivos_id', $request->ano_lectivos_id)->where('estudantes_id', $request->estudante_id)->get();
                        }

                        if ($trimestre->trimestre == "Geral") {
                            $cartao = CartaoEstudante::whereIn('trimestral', ["1º Trimestre", "2º Trimestre", "3º Trimestre", "Normal"])->where('ano_lectivos_id', $request->ano_lectivos_id)->where('estudantes_id', $request->estudante_id)->get();
                        }

                        if ($instutuicao_bolsa->desconto == 100) {
                            if ($cartao) {
                                foreach ($cartao as $cart) {
                                    $update = CartaoEstudante::findOrFail($cart->id);
                                    $update->status = "Pago";
                                    $update->status_2 = "Bolsa";
                                    $update->cobertura = $cobertura;
                                    $update->update();
                                }
                            }
                        } else {
                            if ($cartao) {
                                foreach ($cartao as $cart) {
                                    $update = CartaoEstudante::findOrFail($cart->id);
                                    $update->status = "Nao Pago";
                                    $update->status_2 = "Bolsa";
                                    $update->cobertura = $cobertura;
                                    $update->update();
                                }
                            }
                        }
                    }
                }
            }

            DB::commit();

            Alert::success('Bom trabalho', 'Bolsa Atribuido com sucesso ao estudante!');

            return redirect()->route('creditos-educacionais.instituicao-listar-bolseiros');
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit

        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
    }

    // mais informaões do estudantes
    public function estudantesEditarBolseiroBolsa(Request $request, $id = null)
    {
        $user = auth()->user();

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        $bolseiro = Bolseiro::find(Crypt::decrypt($id));

        $estudantes = Estudante::where('registro', 'confirmado')->where('shcools_id', $this->escolarLogada())->get();



        $bolsas = Bolsa::where('type', 'B')->where('shcools_id', $escola->id)->get();
        $instituicoes = InstituicaoEducacional::where('type', 'B')->where('shcools_id', $escola->id)->get();
        $anos_lectivos = AnoLectivo::where('shcools_id', $escola->id)->get();
        $ano_lectivo = AnoLectivo::findOrFail($this->anolectivoActivo());

        if ($escola->ensino->nome == "Ensino Superior") {
            $trimestres = ControlePeriodico::where('ensino_status', '2')->get();
        } else {
            $trimestres = ControlePeriodico::where('ensino_status', '1')->get();
        }

        $headers = [
            "escola" => $escola,

            "titulo" => "Editar Bolseiro",
            "descricao" => env('APP_NAME'),
            'bolseiro' => $bolseiro,
            'estudantes' => $estudantes,
            'bolsas' => $bolsas,
            'instituicoes' => $instituicoes,
            'anos_lectivos' => $anos_lectivos,
            'ano_lectivo' => $ano_lectivo,
            'trimestres' => $trimestres,
        ];

        return view('admin.estudantes.editar-bolseiro', $headers);
    }

    // mais informaões do estudantes
    public function estudantesEditarBolseiroBolsaUpdate(Request $request, $id)
    {
        $user = auth()->user();

        $request->validate([
            'estudante_id' => 'required',
            'instituicao_id' => 'required',
            'bolsa_id' => 'required',
            'ano_lectivos_id' => 'required',
            'periodo_bolsa' => 'required',
            'afectacao' => 'required',

        ], [
            'estudante_id.required' => 'Campo Obrigatório',
            'instituicao_id.required' => 'Campo Obrigatório',
            'bolsa_id.required' => 'Campo Obrigatório',
            'ano_lectivos_id.required' => 'Campo Obrigatório',
            'periodo_bolsa.required' => 'Campo Obrigatório',
            'afectacao.required' => 'Campo Obrigatório',
        ]);

        try {
            DB::beginTransaction();

            $instutuicao_bolsa = BolsaInstituicao::where('bolsa_id', $request->bolsa_id)->where('instituicao_id', $request->instituicao_id)->first();

            if ($instutuicao_bolsa) {

                if ($instutuicao_bolsa->desconto == 100) {
                    $cobertura = 'Y';
                } else {
                    $cobertura = 'N';
                }


                $update_bolseiro = Bolseiro::findOrFail($id);

                $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

                $cartao_update = CartaoEstudante::where('status_2', 'Bolsa')->where('estudantes_id', $update_bolseiro->estudante_id)->get();

                if ($cartao_update) {
                    foreach ($cartao_update as $cart) {
                        $update = CartaoEstudante::findOrFail($cart->id);
                        $update->status = "Nao Pago";
                        $update->status_2 = "Normal";
                        $update->cobertura = "N";
                        $update->update();
                    }
                }

                $estudanteTurma = EstudantesTurma::where([
                    ['tb_turmas_estudantes.estudantes_id', '=', $request->estudante_id],
                    ['tb_turmas_estudantes.ano_lectivos_id', '=', $request->ano_lectivos_id],
                    ['tb_servicos_turma.model', '=', 'turmas'],
                ])
                    ->join('tb_servicos_turma', 'tb_turmas_estudantes.turmas_id', '=', 'tb_servicos_turma.turmas_id')
                    ->get();

                $trimestre_update = Trimestre::findOrFail($request->periodo_bolsa);

                if ($escola->ensino->nome == "Ensino Superior") {
                    foreach ($estudanteTurma as $servico) {
                        if ($request->afectacao == "mensalidade") {

                            if ($servico->pagamento == 'mensal') {

                                if ($trimestre_update->trimestre == "Iª Simestre") {
                                    $cartao = CartaoEstudante::where('semestral', "1º Semestre")->where('servicos_id', $servico->servicos_id)->where('estudantes_id', $request->estudante_id)->get();
                                }
                                if ($trimestre_update->trimestre == "IIª Simestre") {
                                    $cartao = CartaoEstudante::where('semestral', "2º Semestre")->where('servicos_id', $servico->servicos_id)->where('estudantes_id', $request->estudante_id)->get();
                                }
                                if ($trimestre_update->trimestre == "Anual") {
                                    $cartao = CartaoEstudante::whereIn('semestral', ["1º Semestre", "2º Semestre"])->where('servicos_id', $servico->servicos_id)->where('estudantes_id', $request->estudante_id)->get();
                                }

                                if ($cartao) {
                                    foreach ($cartao as $cart) {
                                        $update = CartaoEstudante::findOrFail($cart->id);
                                        $update->status = "Pago";
                                        $update->status_2 = "Bolsa";
                                        $update->cobertura = $cobertura;
                                        $update->update();
                                    }
                                }
                            }
                        }

                        if ($request->afectacao == "global") {

                            if ($trimestre_update->trimestre == "Iª Simestre") {
                                $cartao = CartaoEstudante::whereIn('semestral', ["1º Semestre", "Normal"])->where('estudantes_id', $request->estudante_id)->get();
                            }
                            if ($trimestre_update->trimestre == "IIª Simestre") {
                                $cartao = CartaoEstudante::whereIn('semestral', ["2º Semestre", "Normal"])->where('estudantes_id', $request->estudante_id)->get();
                            }
                            if ($trimestre_update->trimestre == "Anual") {
                                $cartao = CartaoEstudante::whereIn('semestral', ["1º Semestre", "2º Semestre", "Normal"])->where('estudantes_id', $request->estudante_id)->get();
                            }

                            if ($cartao) {
                                foreach ($cartao as $cart) {
                                    $update = CartaoEstudante::findOrFail($cart->id);
                                    $update->status = "Pago";
                                    $update->status_2 = "Bolsa";
                                    $update->cobertura = $cobertura;
                                    $update->update();
                                }
                            }
                        }
                    }
                } else {
                    foreach ($estudanteTurma as $servico) {
                        if ($request->afectacao == "mensalidade") {
                            if ($servico->pagamento == 'mensal') {

                                if ($trimestre_update->trimestre == "Iª Trimestre") {
                                    $cartao = CartaoEstudante::where('trimestral', "1º Trimestre")->where('servicos_id', $servico->servicos_id)->where('estudantes_id', $request->estudante_id)->get();
                                }
                                if ($trimestre_update->trimestre == "IIª Trimestre") {
                                    $cartao = CartaoEstudante::where('trimestral', "2º Trimestre")->where('servicos_id', $servico->servicos_id)->where('estudantes_id', $request->estudante_id)->get();
                                }
                                if ($trimestre_update->trimestre == "IIIª Trimestre") {
                                    $cartao = CartaoEstudante::where('trimestral', "3º Trimestre")->where('servicos_id', $servico->servicos_id)->where('estudantes_id', $request->estudante_id)->get();
                                }

                                if ($trimestre_update->trimestre == "Geral") {
                                    $cartao = CartaoEstudante::whereIn('trimestral', ["1º Trimestre", "2º Trimestre", "3º Trimestre"])->where('servicos_id', $servico->servicos_id)->where('estudantes_id', $request->estudante_id)->get();
                                }

                                if ($cartao) {
                                    foreach ($cartao as $cart) {
                                        $update = CartaoEstudante::findOrFail($cart->id);
                                        $update->status = "Pago";
                                        $update->status_2 = "Bolsa";
                                        $update->cobertura = $cobertura;
                                        $update->update();
                                    }
                                }
                            }
                        } else if ($request->afectacao == "global") {

                            if ($trimestre_update->trimestre == "Iª Trimestre") {
                                $cartao = CartaoEstudante::whereIn('trimestral', ["1º Trimestre", "Normal"])->where('estudantes_id', $request->estudante_id)->get();
                            }
                            if ($trimestre_update->trimestre == "IIª Trimestre") {
                                $cartao = CartaoEstudante::whereIn('trimestral', ["2º Trimestre", "Normal"])->where('estudantes_id', $request->estudante_id)->get();
                            }
                            if ($trimestre_update->trimestre == "IIIª Trimestre") {
                                $cartao = CartaoEstudante::whereIn('trimestral', ["3º Trimestre", "Normal"])->where('estudantes_id', $request->estudante_id)->get();
                            }

                            if ($trimestre_update->trimestre == "Geral") {
                                $cartao = CartaoEstudante::whereIn('trimestral', ["1º Trimestre", "2º Trimestre", "3º Trimestre", "Normal"])->where('estudantes_id', $request->estudante_id)->get();
                            }

                            if ($cartao) {
                                foreach ($cartao as $cart) {
                                    $update = CartaoEstudante::findOrFail($cart->id);
                                    $update->status = "Pago";
                                    $update->status_2 = "Bolsa";
                                    $update->cobertura = $cobertura;
                                    $update->update();
                                }
                            }
                        }
                    }
                }

                $update_bolseiro->status = $request->status;
                $update_bolseiro->afectacao = $request->afectacao;
                $update_bolseiro->estudante_id = $request->estudante_id;
                $update_bolseiro->instutuicao_bolsa_id = $instutuicao_bolsa->id;
                $update_bolseiro->bolsa_id = $request->bolsa_id;
                $update_bolseiro->instituicao_id = $request->instituicao_id;
                $update_bolseiro->periodo_id = $request->periodo_bolsa;
                $update_bolseiro->ano_lectivos_id = $request->ano_lectivos_id;

                $update_bolseiro->update();
            }


            DB::commit();

            Alert::success('Bom trabalho', 'Bolsa Atribuido com sucesso ao estudante!');

            return redirect()->route('creditos-educacionais.instituicao-listar-bolseiros');
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit

        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
    }

    // BOLSEIROS END
    // extrato do estudante
    public function situacaFinanceiraEstudantes($id)
    {
        $user = auth()->user();

        if (!$user->can('read: matricula') && !$user->can('read: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        $estudantes = Estudante::findOrFail(Crypt::decrypt($id));

        $cartao = CartaoEstudante::where([
            ['estudantes_id', '=', $estudantes->id],
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
        ])->get();

        $matricula = Matricula::where("estudantes_id", $estudantes->id)
            ->where("ano_lectivos_id", $this->anolectivoActivo())
            ->whereIn("status_matricula", ["confirmado", "desistente", "inactivo", "falecido", "rejeitado"])
            ->where("shcools_id", $this->escolarLogada())
            ->first();

        $estudanteTurma = EstudantesTurma::where([
            ['estudantes_id', '=', $estudantes->id],
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
        ])->first();

        if (!$estudanteTurma) {
            Alert::warning("Informação", "Infelizmente não pode acessar esta área porque estudante não esta inserido em nenhuma turma!");
            return redirect()->back();
        }

        $servicosTurma = ServicoTurma::where([
            ['turmas_id', '=', $estudanteTurma->turmas_id],
            ['model', '=', 'turmas'],
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
        ])
            ->join('tb_servicos', 'tb_servicos_turma.servicos_id', '=', 'tb_servicos.id')
            ->get();

        $servicosPropina = Servico::where([
            ['servico', '=', 'Propinas'],
            ['shcools_id', $this->escolarLogada()],
        ])
            ->first();

        $turma = Turma::findOrFail($estudanteTurma->turmas_id);

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "Esxtrato Financeiro do Estudantes",
            "descricao" => env('APP_NAME'),
            "estudante" => $estudantes,
            "matricula" => $matricula,
            "cartao" => $cartao,

            "ano" => AnoLectivo::findOrFail($this->anolectivoActivo()),
            "turma" => $turma,
            "servicosTurma" => $servicosTurma,
            "servicosPropina" => $servicosPropina,
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.estudantes.situacao-financeria', $headers);
    }

    // extrato do estudante
    public function listarDepositosEstudante(Request $request, $id)
    {

        $user = auth()->user();

        if (!$user->can('read: deposito')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $estudante = Estudante::with('municipio', 'provincia')->findOrFail(Crypt::decrypt($id));

        if ($request->ano_lectivos_id) {
            $request->ano_lectivos_id = Crypt::decrypt($request->ano_lectivos_id);
        } else {
            $request->ano_lectivos_id = $this->anolectivoActivo();
        }

        $depositos = Deposito::with(['escola', 'estudante', 'ano', 'operador'])
            ->when($request->ano_lectivos_id, function ($query, $value) {
                $query->where('ano_lectivos_id', $value);
            })
            ->where('estudantes_id', $estudante->id)
            ->get();

        $anos_lectivos = AnoLectivo::where('shcools_id', '=', $this->escolarLogada())->get();



        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "Esxtrato Financeiro do Estudantes",
            "descricao" => env('APP_NAME'),
            "estudante" => $estudante,
            "depositos" => $depositos,
            "anos_lectivos" => $anos_lectivos,

            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.estudantes.listar-depositos', $headers);
    }
}
