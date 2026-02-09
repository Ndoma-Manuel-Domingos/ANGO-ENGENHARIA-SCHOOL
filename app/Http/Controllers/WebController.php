<?php

namespace App\Http\Controllers;

use App\Models\Director;
use App\Models\Efeito;
use App\Models\User;
use App\Models\Shcool;

use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\anolectivo\ControlePeriodico;
use App\Models\web\anolectivo\Trimestre;
use App\Models\web\calendarios\Matricula;
use App\Models\web\calendarios\Mes;
use App\Models\web\calendarios\Pagamento;
use App\Models\web\calendarios\Servico;
use App\Models\web\calendarios\ServicoTurma;
use App\Models\web\classes\Classe;
use App\Models\web\cursos\Curso;
use App\Models\web\disciplinas\Disciplina;
use App\Models\web\encarregados\Encarregado;
use App\Models\web\encarregados\EncarregadoEstudantes;
use App\Models\web\estudantes\CartaoEstudante;
use App\Models\web\estudantes\Estudante;
use App\Models\web\financeiros\DetalhesPagamentoPropina;
use App\Models\web\funcionarios\CartaoFuncionario;
use App\Models\web\funcionarios\Funcionarios;
use App\Models\web\funcionarios\FuncionariosControto;
use App\Models\web\modulos\Modulo;
use App\Models\web\salas\Sala;
use App\Models\web\turmas\DisciplinaTurma;
use App\Models\web\turmas\EstudantesTurma;
use App\Models\web\turmas\NotaPauta;
use App\Models\web\turmas\NotificacaoEncarregado;
use App\Models\web\turmas\Turma;
use App\Models\web\turnos\Turno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use RealRashid\SweetAlert\Facades\Alert;

use App\Services\MultaService;
use Illuminate\Support\Facades\File;

class WebController extends Controller
{
    use TraitHelpers;
    use TraitHeader;

    protected $multaService;

    public function __construct(MultaService $multaService)
    {
        $this->multaService = $multaService;
    }

    public function homePrincipal()
    {
        $headers = [
            "title" => env('APP_NAME'),
            "descricao" => "",
        ];

        return view('web.home', $headers);
    }

    // activar e desactivar turno
    public function activarEscola($id)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
        $escola = Shcool::findOrFail($id);
        if ($escola) {
            if ($escola->status === 'activo') {
                $escola->status = 'desactivo';

                $usuarios = User::where([
                    ['shcools_id', '=', $escola->id]
                ])->get();

                foreach ($usuarios as $usuario) {
                    $update = User::findOrFail($usuario->id);
                    $update->status = "Desbloqueado";
                    $update->update();
                }
            } else {
                $escola->status = 'activo';

                $usuarios = User::where([
                    ['shcools_id', '=', $escola->id]
                ])->get();

                foreach ($usuarios as $usuario) {
                    $update = User::findOrFail($usuario->id);
                    $update->status = "Bloqueado";
                    $update->update();
                }
            }
            if ($escola->update()) {
                return redirect()->route('home-admin');
            }
        }
    }

    // ANO LECTIVO ACTIVO
    public function escolarLogadaAdmin()
    {
        $admin = User::where([
            ['acesso', '=', 'admin']
        ])->first();

        return $admin->id;
    }

    // --------------------------------------------------------------------------------------
    // --------------------------------- EDN CONFIGURAÇÃO DO ANO LECTIVO --------------------
    // --------------------------------------------------------------------------------------
    // --------------------------------------------------------------------------------------


    // --------------------------------------------------------------------------------------
    // ----------------------------------START TURMAS ---------------------------------------------------
    // --------------------------------------------------------------------------------------
    // --------------------------------------------------------------------------------------


    // pesquisar MIni PAutas para todas as turmas
    public function pesquisarTurmaMiniPauta2(Request $request)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
        $validate = Validator::make($request->all(), [
            "turmas_id" => 'required',
            "disciplinas_id" => 'required',
            "trimestre_id" => 'required',
        ]);

        $turma = Turma::with(['curso', 'turno', 'classe', 'sala'])->findOrFail($request->input('turmas_id'));

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        $notas = NotaPauta::where("tb_notas_pautas.disciplinas_id", $request->disciplinas_id)
            ->where("tb_notas_pautas.controlo_trimestres_id", $request->trimestre_id)
            ->where("tb_notas_pautas.ano_lectivos_id", $this->anolectivoActivo())
            ->where("tb_notas_pautas.turmas_id", $turma->id)
            ->with(["estudante"])
            ->get();
            // ->sortBy(function ($nota) {
            //     return $nota->estudante->nome ?? ''; // evita erro se estudante for null
            // });

        if (!$notas) {
            return response()->json([
                'status' => 300,
                'errors' => "Sem Exito a Pesquisa!, tenta uma outra pesquisa!",
            ]);
        }

        if ($validate->fails()) {
            return response()->json([
                "status" => 400,
                "errors" => $validate->messages(),
            ]);
        } else {
            return response()->json([
                "status" => 200,
                "resultados" => $notas,
                "escola" => $escola,
                "curso" => Curso::findOrFail($turma->cursos_id),
                "sala" => Sala::findOrFail($turma->salas_id),
                "classe" => Classe::findOrFail($turma->classes_id),
                "turno" => Turno::findOrFail($turma->turnos_id),
                "turma" => $turma,
                "disciplina" => Disciplina::findOrFail($request->disciplinas_id),
                "anoLectivo" => AnoLectivo::findOrFail($this->anolectivoActivo()),
                "trimestre" => ControlePeriodico::findOrFail($request->trimestre_id),
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        }
    }

    // --------------------------------------------------------------------------------------
    // ----------------------------------START ESTUDANTES ---------------------------------------------------
    // --------------------------------------------------------------------------------------
    // --------------------------------------------------------------------------------------

    // pesquisar completa de um estudante ID PROCESSO
    public function pesquisarEstudanteGeral($string)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO


        $matricula = Matricula::where([
            ['tb_matriculas.documento', '=', $string],
            ['status_matricula', '=', 'confirmado'],
            ['tb_matriculas.ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['tb_matriculas.shcools_id', '=', $this->escolarLogada()],
        ])->orWhere([
            ['tb_matriculas.estudantes_id', '=', $string],
        ])
            ->join('tb_cursos', 'tb_matriculas.cursos_id', '=', 'tb_cursos.id')
            ->join('tb_turnos', 'tb_matriculas.turnos_id', '=', 'tb_turnos.id')
            ->join('tb_classes', 'tb_matriculas.classes_id', '=', 'tb_classes.id')
            ->join('tb_estudantes', 'tb_matriculas.estudantes_id', '=', 'tb_estudantes.id')
            ->select(
                'tb_estudantes.nome',
                'tb_estudantes.sobre_nome',
                'tb_estudantes.genero',
                'tb_estudantes.id AS IdEstudnte',
                'tb_matriculas.id AS IdMatricula',
                'tb_cursos.curso',
                'tb_cursos.id AS IdCurso',
                'tb_turnos.turno',
                'tb_turnos.id AS IdTurno',
                'tb_classes.classes',
                'tb_classes.id AS IdClasse',
                'tb_matriculas.documento'
            )
            ->first();

        if ($matricula) {

            $turma = Turma::where([
                ['cursos_id', '=', $matricula->IdCurso],
                ['turnos_id', '=', $matricula->IdTurno],
                ['classes_id', '=', $matricula->IdClasse],
            ])->first();

            $encarregado = EncarregadoEstudantes::where([
                ['tb_encarregado_estudantes.estudantes_id', '=', $matricula->IdEstudnte],
            ])
                ->join('tb_estudantes', 'tb_encarregado_estudantes.estudantes_id', '=', 'tb_estudantes.id')
                ->join('tb_encarregados', 'tb_encarregado_estudantes.encarregados_id', '=', 'tb_encarregados.id')
                ->select('tb_encarregado_estudantes.grau_parentesco', 'tb_encarregado_estudantes.id AS IdOriginal')
                ->first();

            return response()->json([
                'status' => 200,
                'matricula' => $matricula,
                'turma' => $turma,
                'encarregado' => $encarregado,
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        } else {
            return response()->json([
                'status' => 300,
                'message' => "Número de matricula não existe, tente o número de outra matricula!",
            ]);
        }
    }

    // selecionar servicos da turmas onde o estudantes esta e o servico que foi clicado

    public function carregarServicoTurma(Request $request)
    {

        $estudantes = Estudante::findOrFail($request->estudante_id);

        if ($request->ano_lectivo_id == null) {
            $ano = $this->anolectivoActivo();
        } else {
            $ano = $request->ano_lectivo_id;
        }

        $turma_estudante_ano_selecionado = EstudantesTurma::where('estudantes_id', $estudantes->id)
            ->where('status', 'activo')
            ->where('ano_lectivos_id', $ano)
            ->select('tb_turmas_estudantes.turmas_id')
            ->first();

        $todos_servicos_turma = ServicoTurma::where('turmas_id', $turma_estudante_ano_selecionado->turmas_id)
            ->where('ano_lectivos_id', $ano)
            ->where('model', 'turmas')
            ->with(['servico'])
            ->get();

        $servico = Servico::find($request->servico_id);

        if (!$servico) {
            return response()->json([
                "status" => 404,
                "servicos" => $todos_servicos_turma,
                "message" => 'Nenhum serviço foi localizado nesta turma, ou seja esta serviço não esta cadastrado nesta turma'
            ]);
        }

        // servicos da turma
        $servico = ServicoTurma::where('turmas_id', $turma_estudante_ano_selecionado->turmas_id)
            ->where('servicos_id', $servico->id)
            ->where('ano_lectivos_id', $ano)
            ->where('model', 'turmas')
            ->join('tb_servicos', 'tb_servicos_turma.servicos_id', '=', 'tb_servicos.id')
            ->select('tb_servicos.servico', 'tb_servicos.id', 'tb_servicos_turma.pagamento', 'tb_servicos_turma.preco', 'tb_servicos_turma.multa', 'tb_servicos_turma.desconto')
            ->first();

        if ($estudantes->bolseiro($estudantes->id) && $estudantes->bolseiro($estudantes->id)->instituicao_bolsa->desconto == 100) {
            $cartao = CartaoEstudante::where('estudantes_id', $estudantes->id)
                ->where('servicos_id', $servico->id)
                ->where('ano_lectivos_id', $ano)
                ->whereIn('status', ['Nao Pago', 'divida', 'processo'])
                ->get();
        }
        if ($estudantes->bolseiro($estudantes->id) && $estudantes->bolseiro($estudantes->id)->instituicao_bolsa->desconto != 100) {
            $cartao = CartaoEstudante::where('estudantes_id', $estudantes->id)
                ->where('servicos_id', $servico->id)
                ->where('ano_lectivos_id', $ano)
                ->whereIn('status', ['Pago', 'Nao Pago'])
                ->where('cobertura', 'N')
                ->get();
        } else {
            $cartao = CartaoEstudante::where('estudantes_id', $estudantes->id)
                ->where('servicos_id', $servico->id)
                ->where('ano_lectivos_id', $ano)
                ->whereIn('status', ['Nao Pago', 'divida', 'processo'])
                ->get();
        }

        if (!$cartao) {
            return response()->json([
                "status" => 404,
                "servicos" => $todos_servicos_turma,
                "message" => 'Este estudante esta sem cartão para este serviço'
            ]);
        }

        if ($servico) {
            return response()->json([
                "status" => 200,
                "servico" => $servico,
                "servico_turma" => $servico,
                "servicos" => $todos_servicos_turma,
                "cartao" => $cartao,
                "estudante" => $estudantes,
                "bolseiro" => $estudantes->bolseiro($estudantes->id),
                "usuario" => User::findOrFail(Auth::user()->id),
                'mesesAdd' => DetalhesPagamentoPropina::where('ano_lectivos_id', $ano)
                    ->where('status', 'processo')
                    ->where('funcionarios_id', Auth::user()->id)
                    ->where('model_id', $estudantes->id)
                    ->with(['servico'])
                    ->get(),
                "totalDesconto" => DetalhesPagamentoPropina::where('ano_lectivos_id', $ano)
                    ->where('status', 'processo')
                    ->where('funcionarios_id', Auth::user()->id)
                    ->where('model_id', $estudantes->id)
                    ->sum('desconto_valor'),
                "totalAPagar" => DetalhesPagamentoPropina::where('ano_lectivos_id', $ano)
                    ->where('status', 'processo')
                    ->where('funcionarios_id', Auth::user()->id)
                    ->where('model_id', $estudantes->id)
                    ->sum('total_pagar'),

                "somaVolores" => DetalhesPagamentoPropina::where('ano_lectivos_id', $ano)
                    ->where('status', 'processo')
                    ->where('funcionarios_id', Auth::user()->id)
                    ->where('model_id', $estudantes->id)
                    ->sum('preco'),

                "somaMulta" => DetalhesPagamentoPropina::where('ano_lectivos_id', $ano)
                    ->where('status', 'processo')
                    ->where('funcionarios_id', Auth::user()->id)
                    ->where('model_id', $estudantes->id)
                    ->sum('multa'),

                "somaQuantidade" => DetalhesPagamentoPropina::where('ano_lectivos_id', $ano)
                    ->where('status', 'processo')
                    ->where('funcionarios_id', Auth::user()->id)
                    ->where('model_id', $estudantes->id)
                    ->sum('quantidade'),
            ]);
        } else {
            return response()->json([
                "status" => 404,
                "servicos" => $todos_servicos_turma,
                "message" => 'Turno não Encontrado'
            ]);
        }
    }

    public function estudantesRemoverMulta1($id)
    {
        $multa = 1;
        $operacao = 'remover';
        $resultado = $this->multaService->adicionarMulta($id, $multa, $operacao);

        return response()->json($resultado);
    }

    public function estudantesAdicionarMulta1($id)
    {
        $multa = 1;
        $operacao = 'adicionar';
        $resultado = $this->multaService->adicionarMulta($id, $multa, $operacao);

        return response()->json($resultado);
    }

    public function estudantesRemoverMulta2($id)
    {
        $multa = 2;
        $operacao = 'remover';
        $resultado = $this->multaService->adicionarMulta($id, $multa, $operacao);

        return response()->json($resultado);
    }

    public function estudantesAdicionarMulta2($id)
    {
        $multa = 2;
        $operacao = 'adicionar';
        $resultado = $this->multaService->adicionarMulta($id, $multa, $operacao);

        return response()->json($resultado);
    }

    public function estudantesRemoverMulta3($id)
    {
        $multa = 3;
        $operacao = 'remover';
        $resultado = $this->multaService->adicionarMulta($id, $multa, $operacao);

        return response()->json($resultado);
    }

    public function estudantesAdicionarMulta3($id)
    {
        $multa = 3;
        $operacao = 'adicionar';
        $resultado = $this->multaService->adicionarMulta($id, $multa, $operacao);

        return response()->json($resultado);
    }

    public function estudantesDetalhesPagamentoPropina($id, $est, $servico, $quantidade = null, $ano = null)
    {
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            // CONTROLAR A SESSÃo INICIALIZADA OU NAO
            $cartao = CartaoEstudante::findOrFail($id);
            $mes_anterior = $cartao->id - 1;

            if ($ano == null) {
                $ano = $this->anolectivoActivo();
            } else {
                $ano = $ano;
            }

            $verificar_servico = Servico::where('servico', 'Propinas')->where('shcools_id', $this->escolarLogada())->first();
            $cartao_anterior = CartaoEstudante::find($mes_anterior);

            $servicos = Servico::join('tb_taxas', 'tb_servicos.taxa_id', '=', 'tb_taxas.id')
                ->select('tb_servicos.id', 'taxa', 'contas', 'tb_servicos.servico', 'tb_servicos.contas')
                ->findOrFail($servico);

            $estudante = Estudante::findOrFail($est);

            $estudanteTurma = EstudantesTurma::where('estudantes_id', $estudante->id)
                ->where('ano_lectivos_id', $ano)
                ->first();

            $servicoTurma = ServicoTurma::where([
                ['servicos_id', '=', $servicos->id],
                ['turmas_id', '=', $estudanteTurma->turmas_id],
                ['ano_lectivos_id', '=', $ano],
                ['model', '=', 'turmas'],
            ])->first();

            if ($verificar_servico->id == ($cartao_anterior->servicos_id ?? "") && $servicoTurma->pagamento !== 'unico') {
                if ($cartao_anterior->status == 'Nao Pago' || $cartao_anterior->status == 'divida') {
                    return response()->json([
                        'status' => 401,
                        'message' => "Mês selecionado Invalido",
                    ]);
                }
            }

            $verificar = DetalhesPagamentoPropina::where([
                ['status', '=', 'processo'],
                ['funcionarios_id', '=', Auth::user()->id],
                ['model_id', '=', $estudante->id],
                ['servicos_id', '=', $servicos->id],
                ['ano_lectivos_id', '=', $ano],
            ])->first();

            if ($estudante->bolseiro($estudante->id) && $estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto == 100) {

                if ($estudante->bolseiro($estudante->id)->periodo->trimestre == "Iª Trimestre") {
                    if ($cartao->trimestral == "1º Trimestre") {
                        $descontoGeral = $estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto;
                        $subTotalIncidencia = ($servicoTurma->preco - ($servicoTurma->preco * $descontoGeral / 100));
                        $valor_a_descontar = ($servicoTurma->preco - ($servicoTurma->preco * $descontoGeral / 100));
                    } else {
                        $descontoGeral = 0;
                        $valor_a_descontar = 0;
                        $subTotalIncidencia = $servicoTurma->preco;
                    }
                }

                if ($estudante->bolseiro($estudante->id)->periodo->trimestre == "IIª Trimestre") {
                    if ($cartao->trimestral == "2º Trimestre") {
                        $descontoGeral = $estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto;
                        $subTotalIncidencia = ($servicoTurma->preco - ($servicoTurma->preco * $descontoGeral / 100));
                        $valor_a_descontar = ($servicoTurma->preco - ($servicoTurma->preco * $descontoGeral / 100));
                    } else {
                        $descontoGeral = 0;
                        $valor_a_descontar = 0;
                        $subTotalIncidencia = $servicoTurma->preco;
                    }
                }

                if ($estudante->bolseiro($estudante->id)->periodo->trimestre == "Geral") {
                    $descontoGeral = $estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto;
                    $subTotalIncidencia = ($servicoTurma->preco - ($servicoTurma->preco * $descontoGeral / 100));
                    $valor_a_descontar = ($servicoTurma->preco - ($servicoTurma->preco * $descontoGeral / 100));
                }

                if ($estudante->bolseiro($estudante->id)->periodo->trimestre == "IIIª Trimestre") {
                    if ($cartao->trimestral == "3º Trimestre") {
                        $descontoGeral = $estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto;
                        $subTotalIncidencia = ($servicoTurma->preco - ($servicoTurma->preco * $descontoGeral / 100));
                        $valor_a_descontar = ($servicoTurma->preco - ($servicoTurma->preco * $descontoGeral / 100));
                    } else {
                        $descontoGeral = 0;
                        $valor_a_descontar = 0;
                        $subTotalIncidencia = $servicoTurma->preco;
                    }
                }

                if ($estudante->bolseiro($estudante->id)->periodo->trimestre == "Iª Simestre") {
                    if ($cartao->semestral == "1º Semestre") {
                        $descontoGeral = $estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto;
                        $subTotalIncidencia = ($servicoTurma->preco - ($servicoTurma->preco * $descontoGeral / 100));
                        $valor_a_descontar = ($servicoTurma->preco - ($servicoTurma->preco * $descontoGeral / 100));
                    } else {
                        $descontoGeral = 0;
                        $valor_a_descontar = 0;
                        $subTotalIncidencia = $servicoTurma->preco;
                    }
                }

                if ($estudante->bolseiro($estudante->id)->periodo->trimestre == "IIª Simestre") {
                    if ($cartao->semestral == "2º Semestre") {
                        $descontoGeral = $estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto;
                        $subTotalIncidencia = ($servicoTurma->preco - ($servicoTurma->preco * $descontoGeral / 100));
                        $valor_a_descontar = ($servicoTurma->preco - ($servicoTurma->preco * $descontoGeral / 100));
                    } else {
                        $descontoGeral = 0;
                        $valor_a_descontar = 0;
                        $subTotalIncidencia = $servicoTurma->preco;
                    }
                }

                if ($estudante->bolseiro($estudante->id)->periodo->trimestre == "Anual") {
                    $descontoGeral = $estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto;
                    $subTotalIncidencia = ($servicoTurma->preco - ($servicoTurma->preco * $descontoGeral / 100));
                    $valor_a_descontar = ($servicoTurma->preco - ($servicoTurma->preco * $descontoGeral / 100));
                }
            } else
            if ($estudante->bolseiro($estudante->id) && $estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto != 100) {

                if ($estudante->bolseiro($estudante->id)->periodo->trimestre == "Iª Trimestre") {

                    if ($cartao->trimestral == "1º Trimestre") {
                        $descontoGeral = $estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto;
                        $subTotalIncidencia = ($servicoTurma->preco - ($servicoTurma->preco * $descontoGeral / 100));
                        $valor_a_descontar = ($servicoTurma->preco - ($servicoTurma->preco * $descontoGeral / 100));
                    } else {
                        $descontoGeral = 0;
                        $valor_a_descontar = 0;
                        $subTotalIncidencia = $servicoTurma->preco;
                    }
                }


                if ($estudante->bolseiro($estudante->id)->periodo->trimestre == "IIª Trimestre") {

                    if ($cartao->trimestral == "2º Trimestre") {
                        $descontoGeral = $estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto;
                        $subTotalIncidencia = ($servicoTurma->preco - ($servicoTurma->preco * $descontoGeral / 100));
                        $valor_a_descontar = ($servicoTurma->preco - ($servicoTurma->preco * $descontoGeral / 100));
                    } else {
                        $descontoGeral = 0;
                        $valor_a_descontar = 0;
                        $subTotalIncidencia = $servicoTurma->preco;
                    }
                }

                if ($estudante->bolseiro($estudante->id)->periodo->trimestre == "Geral") {

                    $descontoGeral = $estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto;
                    $subTotalIncidencia = ($servicoTurma->preco - ($servicoTurma->preco * $descontoGeral / 100));
                    $valor_a_descontar = ($servicoTurma->preco - ($servicoTurma->preco * $descontoGeral / 100));
                }

                if ($estudante->bolseiro($estudante->id)->periodo->trimestre == "IIIª Trimestre") {

                    if ($cartao->trimestral == "3º Trimestre") {
                        $descontoGeral = $estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto;
                        $subTotalIncidencia = ($servicoTurma->preco - ($servicoTurma->preco * $descontoGeral / 100));
                        $valor_a_descontar = ($servicoTurma->preco - ($servicoTurma->preco * $descontoGeral / 100));
                    } else {
                        $descontoGeral = 0;
                        $valor_a_descontar = 0;
                        $subTotalIncidencia = $servicoTurma->preco;
                    }
                }

                if ($estudante->bolseiro($estudante->id)->periodo->trimestre == "Iª Simestre") {

                    if ($cartao->semestral == "1º Semestre") {
                        $descontoGeral = $estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto;
                        $subTotalIncidencia = ($servicoTurma->preco - ($servicoTurma->preco * $descontoGeral / 100));
                        $valor_a_descontar = ($servicoTurma->preco - ($servicoTurma->preco * $descontoGeral / 100));
                    } else {
                        $descontoGeral = 0;
                        $valor_a_descontar = 0;
                        $subTotalIncidencia = $servicoTurma->preco;
                    }
                }

                if ($estudante->bolseiro($estudante->id)->periodo->trimestre == "IIª Simestre") {

                    if ($cartao->semestral == "2º Semestre") {
                        $descontoGeral = $estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto;
                        $subTotalIncidencia = ($servicoTurma->preco - ($servicoTurma->preco * $descontoGeral / 100));
                        $valor_a_descontar = ($servicoTurma->preco - ($servicoTurma->preco * $descontoGeral / 100));
                    } else {
                        $descontoGeral = 0;
                        $valor_a_descontar = 0;
                        $subTotalIncidencia = $servicoTurma->preco;
                    }
                }

                if ($estudante->bolseiro($estudante->id)->periodo->trimestre == "Anual") {
                    $descontoGeral = $estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto;
                    $subTotalIncidencia = ($servicoTurma->preco - ($servicoTurma->preco * $descontoGeral / 100));
                    $valor_a_descontar = ($servicoTurma->preco - ($servicoTurma->preco * $descontoGeral / 100));
                }
                // $descontoGeral = $estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto;
            } else {
                /// vamos verificar se o estudante tem desocnto
                $estudante_com_desconto =  $estudante->desconto($estudante->id, $ano);
                if ($estudante_com_desconto != false) {
                    $descontoGeral = $estudante_com_desconto->desconto->desconto;
                    $valor_a_descontar = $servicoTurma->preco *  (($descontoGeral ?? 0) / 100);
                    $subTotalIncidencia = ($servicoTurma->preco - ($servicoTurma->preco * $descontoGeral / 100)); /// ($servicoTurma->preco + $cartao->multa) * 1;
                } else {
                    $descontoGeral = 0;
                    $valor_a_descontar = 0; // (($servicoTurma->preco + $cartao->multa) * 1 * $desconto ?? 0) / 100;
                    $subTotalIncidencia = ($servicoTurma->preco - ($servicoTurma->preco * $descontoGeral / 100)); /// ($servicoTurma->preco + $cartao->multa) * 1;
                }
            }

            // aqui estou estrair do valor que tem o iva eestou a tirar o iva que ele tem, para depois poder aplicar novamente

            $valorOriginal = $subTotalIncidencia / (1 + ($servicos->taxa / 100));

            if ($subTotalIncidencia == $valorOriginal) {
                $valorIva = 0;
                $valorTotal = $subTotalIncidencia;
            } else {
                // estou  a inverter os valores
                $valorTotal = $subTotalIncidencia;
                $subTotalIncidencia = $valorOriginal;
                $valorIva = ($servicos->taxa / 100) * $subTotalIncidencia;
            }

            if ($verificar) {

                DetalhesPagamentoPropina::create([
                    "multa" => $cartao->multa,
                    "total_pagar" => ($valorTotal * $quantidade ?? 1) + $cartao->multa,
                    "mes_id" => "NULL",
                    "desconto" => $descontoGeral * $quantidade ?? 1,
                    "desconto_valor" => $valor_a_descontar * $quantidade ?? 1,
                    "valor_incidencia" => $subTotalIncidencia * $quantidade ?? 1,
                    "valor_iva" => $valorIva * $quantidade ?? 1,
                    "taxa_id" => $servicos->taxa,
                    "preco" => $subTotalIncidencia,
                    "mes" => $cartao->month_name,
                    "model_id" => $estudante->id,
                    "quantidade" => $quantidade ?? 1,
                    "funcionarios_id" => Auth::user()->id,
                    "status" => 'processo',
                    "servicos_id" => $servicos->id,
                    "date_att" => $this->data_sistema(),
                    "ano_lectivos_id" => $ano,
                    "shcools_id" => $this->escolarLogada(),
                    "code" => $verificar->code,
                ]);
            } else {
                $novo_code = time();

                DetalhesPagamentoPropina::create([
                    "multa" => $cartao->multa,
                    "total_pagar" => ($valorTotal * $quantidade ?? 1) + $cartao->multa,
                    "mes_id" => "NULL",
                    "desconto" => $descontoGeral * $quantidade ?? 1,
                    "desconto_valor" => $valor_a_descontar * $quantidade ?? 1,
                    "valor_incidencia" => $subTotalIncidencia * $quantidade ?? 1,
                    "valor_iva" => $valorIva * $quantidade ?? 1,
                    "taxa_id" => $servicos->taxa,
                    "preco" => $subTotalIncidencia + $valor_a_descontar,
                    "mes" => $cartao->month_name,
                    "model_id" => $estudante->id,
                    "quantidade" => $quantidade ?? 1,
                    "funcionarios_id" => Auth::user()->id,
                    "status" => 'processo',
                    "servicos_id" => $servicos->id,
                    "date_att" => $this->data_sistema(),
                    "ano_lectivos_id" => $ano,
                    "shcools_id" => $this->escolarLogada(),
                    "code" => $novo_code,
                ]);
            }

            $cartao->status = 'processo';
            $cartao->update();

            if ($estudante->bolseiro($estudante->id) && $estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto == 100) {
                $cartao = CartaoEstudante::where('estudantes_id', $estudante->id)
                    ->where('servicos_id', $servicos->id)
                    ->where('ano_lectivos_id', $ano)
                    ->whereIn('status', ['Nao Pago', 'divida', 'processo'])
                    ->get();
            }
            if ($estudante->bolseiro($estudante->id) && $estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto != 100) {
                $cartao = CartaoEstudante::where('estudantes_id', $estudante->id)
                    ->where('servicos_id', $servicos->id)
                    ->where('ano_lectivos_id', $ano)
                    ->whereIn('status', ['Pago', 'Nao Pago'])
                    ->where('cobertura', 'N')
                    ->get();
            } else {
                $cartao = CartaoEstudante::where('estudantes_id', $estudante->id)
                    ->where('servicos_id', $servicos->id)
                    ->where('ano_lectivos_id', $ano)
                    ->whereIn('status', ['Nao Pago', 'divida', 'processo'])
                    // ->select('tb_cartao_estudantes.id', 'tb_cartao_estudantes.month_name', 'tb_cartao_estudantes.multa1', 'tb_cartao_estudantes.multa2',  'tb_cartao_estudantes.multa3', 'tb_cartao_estudantes.data_at', 'tb_cartao_estudantes.data_exp', 'tb_cartao_estudantes.cobertura', 'tb_cartao_estudantes.status_2', 'tb_cartao_estudantes.semestral', 'tb_cartao_estudantes.trimestral', 'tb_cartao_estudantes.status', 'tb_cartao_estudantes.multa')
                    ->get();
            }

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
            'servico' => $servicos,
            'servico_turma' => $servicoTurma,
            "bolseiro" => $estudante->bolseiro($estudante->id),

            "mesesAdd" => DetalhesPagamentoPropina::where('status', 'processo')
                ->where('funcionarios_id', Auth::user()->id)
                ->where('model_id', $estudante->id)
                ->where('ano_lectivos_id', $ano)
                ->with(['servico'])
                ->get(),

            "totalAPagar" => DetalhesPagamentoPropina::where('status', 'processo')
                ->where('funcionarios_id', Auth::user()->id)
                ->where('model_id', $estudante->id)
                ->where('ano_lectivos_id', $ano)
                ->sum('total_pagar'),

            "totalDesconto" => DetalhesPagamentoPropina::where('status', 'processo')
                ->where('funcionarios_id', Auth::user()->id)
                ->where('model_id', $estudante->id)
                ->where('ano_lectivos_id', $ano)
                ->sum('desconto_valor'),

            "somaVolores" => DetalhesPagamentoPropina::where('status', 'processo')
                ->where('funcionarios_id', Auth::user()->id)
                ->where('model_id', $estudante->id)
                ->where('ano_lectivos_id', $ano)
                ->sum('preco') + DetalhesPagamentoPropina::where('status', 'processo')
                ->where('funcionarios_id', Auth::user()->id)
                ->where('model_id', $estudante->id)
                ->where('ano_lectivos_id', $ano)
                ->sum('multa'),

            "somaMulta" => DetalhesPagamentoPropina::where('status', 'processo')
                ->where('funcionarios_id', Auth::user()->id)
                ->where('model_id', $estudante->id)
                ->where('ano_lectivos_id', $ano)
                ->sum('multa'),

            "somaQuantidade" => DetalhesPagamentoPropina::where('status', 'processo')
                ->where('funcionarios_id', Auth::user()->id)
                ->where('model_id', $estudante->id)
                ->where('ano_lectivos_id', $ano)
                ->sum('quantidade'),

            "cartao" => $cartao,

        ]);
    }

    public function estudantesDetalhesPagamentoPropinaRemoverMes($id, $est, $servico, $quantidade = null, $ano = null)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
        $detalhe = DetalhesPagamentoPropina::findOrFail($id);
        $estudantes = Estudante::findOrFail($est);
        $servicos = Servico::findOrFail($servico);

        if ($ano == null) {
            $ano = $this->anolectivoActivo();
        } else {
            $ano = $ano;
        }

        $estudanteTurma = EstudantesTurma::where('estudantes_id', $estudantes->id)
            ->where('ano_lectivos_id', $ano)
            ->first();

        $servicoTurma = ServicoTurma::where('servicos_id', $servicos->id)
            ->where('turmas_id', $estudanteTurma->turmas_id)
            ->where('ano_lectivos_id', $ano)
            ->where('model', 'turmas')
            ->first();

        $updateCartao = CartaoEstudante::where('status', 'processo')
            ->where('estudantes_id', $estudantes->id)
            ->where('ano_lectivos_id', $ano)
            ->where('month_name', $detalhe->mes)
            ->first();

        $update = CartaoEstudante::findOrFail($updateCartao->id);

        if ($update->multa != 0) {
            $status = "divida";
        } else {
            $status = 'Nao Pago';
        }

        $update->status = $status;

        $detalhe->delete();
        $update->update();

        if ($estudantes->bolseiro($estudantes->id) && $estudantes->bolseiro($estudantes->id)->instituicao_bolsa->desconto == 100) {
            $cartao = CartaoEstudante::where('estudantes_id', $estudantes->id)
                ->where('servicos_id', $servicos->id)
                ->where('ano_lectivos_id', $ano)
                ->whereIn('status', ['Nao Pago', 'divida', 'processo'])
                ->get();
        }
        if ($estudantes->bolseiro($estudantes->id) && $estudantes->bolseiro($estudantes->id)->instituicao_bolsa->desconto != 100) {
            $cartao = CartaoEstudante::where('estudantes_id', $estudantes->id)
                ->where('servicos_id', $servicos->id)
                ->where('ano_lectivos_id', $ano)
                ->whereIn('status', ['Pago', 'Nao Pago'])
                ->where('cobertura', 'N')
                ->get();
        } else {
            $cartao = CartaoEstudante::where('estudantes_id', $estudantes->id)
                ->where('servicos_id', $servicos->id)
                ->where('ano_lectivos_id', $ano)
                ->whereIn('status', ['Nao Pago', 'divida', 'processo'])
                // ->select('tb_cartao_estudantes.id', 'tb_cartao_estudantes.month_name', 'tb_cartao_estudantes.multa1', 'tb_cartao_estudantes.multa2',  'tb_cartao_estudantes.multa3', 'tb_cartao_estudantes.data_at', 'tb_cartao_estudantes.data_exp', 'tb_cartao_estudantes.cobertura', 'tb_cartao_estudantes.status_2', 'tb_cartao_estudantes.semestral', 'tb_cartao_estudantes.trimestral', 'tb_cartao_estudantes.status', 'tb_cartao_estudantes.multa')
                ->get();
        }

        return response()->json([
            'status' => 200,
            'servico' => $servicos,
            'servico_turma' => $servicoTurma,
            "bolseiro" => $estudantes->bolseiro($estudantes->id),
            "mesesAdd" => DetalhesPagamentoPropina::where('status', 'processo')
                ->where('ano_lectivos_id', $ano)
                ->where('funcionarios_id', Auth::user()->id)
                ->where('model_id', $estudantes->id)
                ->with(['servico'])
                ->get(),

            "totalAPagar" => DetalhesPagamentoPropina::where('ano_lectivos_id', $ano)
                ->where('status', 'processo')
                ->where('funcionarios_id', Auth::user()->id)
                ->where('model_id', $estudantes->id)
                ->sum('total_pagar'),

            "totalDesconto" => DetalhesPagamentoPropina::where('ano_lectivos_id', $ano)
                ->where('status', 'processo')
                ->where('funcionarios_id', Auth::user()->id)
                ->where('model_id', $estudantes->id)
                ->sum('desconto_valor'),

            "somaVolores" => DetalhesPagamentoPropina::where('ano_lectivos_id', $ano)
                ->where('status', 'processo')
                ->where('funcionarios_id', Auth::user()->id)
                ->where('model_id', $estudantes->id)
                ->sum('preco'),

            "somaMulta" => DetalhesPagamentoPropina::where('ano_lectivos_id', $ano)
                ->where('status', 'processo')
                ->where('funcionarios_id', Auth::user()->id)
                ->where('model_id', $estudantes->id)
                ->sum('multa'),

            "somaQuantidade" => DetalhesPagamentoPropina::where('ano_lectivos_id', $ano)
                ->where('status', 'processo')
                ->where('funcionarios_id', Auth::user()->id)
                ->where('model_id', $estudantes->id)
                ->sum('quantidade'),

            "cartao" => $cartao,
        ]);
    }


    public function estudantesFotoPerfil(Request $request)
    {
        $validacao = $request->validate([
            'estudanteFoto' => 'required',
            'fotografiaEstudante' => 'required|mimes:jpg,jpeg,png',
        ], [
            'estudanteFoto.required' => "***",
            'fotografiaEstudante.required' => "Deves Selecionar uma imagem"
        ]);

        $estudantes = Estudante::findOrFail($request->input('estudanteFoto'));

        if (!empty($request->file('fotografiaEstudante'))) {
            $image = $request->file('fotografiaEstudante');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('assets/images/recursosHumanos'), $imageName);
        } else {
            $imageName = Null;
        }

        $estudantes->image = $imageName;
        $estudantes->update();

        return redirect()->route('shcools.mais-informacao-estudante', Crypt::encrypt($estudantes->id));
    }

    // processos estudante
    public function processoEstudantes()
    {

        $user = auth()->user();

        if (!$user->can('read: documento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "Processos dos estudantes",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "NumeroProcesso" => Matricula::where([
                ['shcools_id', '=', $this->escolarLogada()],
                ['status_matricula', '=', 'confirmado'],
            ])->count(),
        ];

        return view('admin.estudantes.processos-estudantes', $headers);
    }

    // processos financeiros estudante
    public function processoFinanceiroEstudantes()
    {

        $user = auth()->user();

        if (!$user->can('read: documento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }




        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "Processos Financeiros dos Estudantes",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "ano_lectivos" => AnoLectivo::where('shcools_id', '=', $this->escolarLogada())->get(),
            "servicos" => Servico::where([
                ['shcools_id', '=', $this->escolarLogada()],
                ['contas', '=', "receita"],
            ])->get(),
            "matriculas_passadas" => Matricula::where([
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['shcools_id', '=', $this->escolarLogada()],
                ['status_matricula', '=', 'confirmado'],
            ])
                ->with('estudante')
                ->get(),
        ];

        return view('admin.estudantes.processos-financeiros-estudantes', $headers);
    }

    // processos pedagogicos estudante
    public function processoPedagogicosEstudantes()
    {
        $user = auth()->user();

        if (!$user->can('read: documento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $efeitos = Efeito::get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "usuario" => User::findOrFail(Auth::user()->id),
            "ano_lectivos" => AnoLectivo::where('shcools_id', '=', $this->escolarLogada())->get(),
            "matriculas_passadas" => Matricula::where([
                ['tb_matriculas.ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['tb_matriculas.shcools_id', '=', $this->escolarLogada()],
                ['tb_matriculas.status_matricula', '=', 'confirmado'],
            ])
                ->with(['estudante'])
                ->get(),
            "efeitos" => $efeitos,
        ];

        return view('admin.estudantes.processos-pedagogicos-estudantes', $headers);
    }

    // processos pedagogicos estudante
    public function numeroProcessoId($id)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO


        $estudantesId = Estudante::findOrFail($id);

        $numeroProcesso = Matricula::where([
            ['estudantes_id', '=', $estudantesId->id],
            ['status_matricula', '=', 'confirmado'],
        ])
            ->select('tb_matriculas.estudantes_id', 'tb_matriculas.documento')
            ->first();

        if ($numeroProcesso) {
            return response()->json([
                'status' => 200,
                'numero' => $numeroProcesso,
            ]);
        }

        return response()->json([
            'status' => 404,
            'message' => "Erro Com o estudante selecionado",
        ]);
    }

    // --------------------------------------------------------------------------------------
    // ----------------------------------START RELATORIOS ----------------------------------------
    // --------------------------------------------------------------------------------------
    // --------------------------------------------------------------------------------------

    // mini pautas geral \\ 1ª 2ª 3ª trimestres miniPautaGaral
    public function miniPautaGeral(Request $request)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        $turma = Turma::with(['curso','classe','turno','sala'])->find($request->turmas_id);
        $disciplina = Disciplina::find($request->disciplinas_id);

        $estudantes = NULL;

        if ($turma) {
            $estudantes = EstudantesTurma::with(['estudante'])->where('turmas_id', $turma->id)->get();
        }

        $turmas = Turma::where("ano_lectivos_id", $this->anolectivoActivo())
            ->where("status", "activo")
            ->get();

        $trimestre1 = Trimestre::where("trimestre", "Iª Trimestre")->first();
        $trimestre2 = Trimestre::where("trimestre", "IIª Trimestre")->first();
        $trimestre3 = Trimestre::where("trimestre", "IIIª Trimestre")->first();
        $trimestre4 = Trimestre::where("trimestre", "Geral")->first();
        
        $headers = [
            "escola" => Shcool::with("ensino")->findOrFail($this->escolarLogada()),
            "titulo" => "Mini Pautas",
            "descricao" => env('APP_NAME'),
            "estudantes" => $estudantes,
            "turma" => $turma,
            "curso" => Curso::find($turma->cursos_id ?? ""),
            "sala" => Sala::find($turma->salas_id ?? ""),
            "classe" => Classe::find($turma->classes_id ?? ""),
            "turno" => Turno::find($turma->turnos_id ?? ""),
            "anoLectivo" => AnoLectivo::findOrFail($this->anolectivoActivo()),
            "disciplina" => $disciplina,
            "turmas" => $turmas,
            "trimestre1" => $trimestre1 ?? 0,
            "trimestre2" => $trimestre2 ?? 0,
            "trimestre3" => $trimestre3 ?? 0,
            "trimestre4" => $trimestre4 ?? 0,
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view("relatorios.mini-pauta-geral", $headers);
    }

    // mini pautas geral \\ 1ª 2ª 3ª trimestres miniPauta
    public function miniPauta(Request $request)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
        $turmas = Turma::where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('status', 'activo')
            ->with(['curso', 'sala', 'turno', 'classe'])
            ->get();

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        if ($escola->ensino->nome == "Ensino Superior") {
            $trimestres = ControlePeriodico::where('ensino_status', '2')->get();
        } else {
            $trimestres = ControlePeriodico::where('ensino_status', '1')->get();
        }

        $turma = Turma::with(['curso','classe','turno','sala'])->find($request->turmas_id);
        $disciplina = Disciplina::find($request->disciplinas_id);
        $trimestre = ControlePeriodico::find($request->trimestre_id);

        if($turma) {
            $disciplinasTurma = DisciplinaTurma::with(["disciplina"])->where("turmas_id", $turma->id)->get();
            $estudantesTurma = EstudantesTurma::with(["estudante"])->where("turmas_id", $turma->id)
            ->get()
            ->sortBy(function($estudante) {
                return $estudante->estudante->nome;
            });
        } else {
            $disciplinasTurma = null;
            $estudantesTurma = null;
        }


        $trimestre1 = ControlePeriodico::where("trimestre", "Iª Trimestre")->first();
        $trimestre2 = ControlePeriodico::where("trimestre", "IIª Trimestre")->first();
        $trimestre3 = ControlePeriodico::where("trimestre", "IIIª Trimestre")->first();

        $headers = [
            "escola" => $escola,
            "titulo" => "MINI PAUTAS",
            "descricao" => env('APP_NAME'),
            "turma" => $turma,
            "pesquisa_trimestre" => $trimestre,
            "trimestre1" => $trimestre1,
            "trimestre2" => $trimestre2,
            "trimestre3" => $trimestre3,
            "pesquisa_disciplina" => $disciplina,
            "pesquisa_condicao" => $request->input('condicao_pesquisar'),
            "pesquisa_ano" => $this->anolectivoActivo(),
            "turmas" => $turmas,
            "disciplinasTurma" => $disciplinasTurma,
            "estudantesTurma" => $estudantesTurma,
            "trimestres" => $trimestres,
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('relatorios.mini-pauta', $headers);
    }

    // mini pautas geral \\ 1ª 2ª 3ª trimestres miniPauta
    public function beletins(Request $request)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
        $turmas = Turma::where("ano_lectivos_id", $this->anolectivoActivo())
            ->where("status", "activo")
            ->get();

        $anos_lectivos = AnoLectivo::where("shcools_id", $this->escolarLogada())->orderBy('status', 'asc')->get();

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        if ($escola->ensino->nome == "Ensino Superior") {
            $trimestres = ControlePeriodico::where('ensino_status', '2')->get();
        } else {
            $trimestres = ControlePeriodico::where('ensino_status', '1')->get();
        }

        $headers = [
            "escola" => $escola,
            "titulo" => "Mini Pautas",
            "descricao" => env('APP_NAME'),
            "turmas" => $turmas,
            "anos_lectivos" => $anos_lectivos,
            "trimestres" => $trimestres,
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('relatorios.boletins-estudantes', $headers);
    }
    // mini pautas geral \\ 1ª 2ª 3ª trimestres miniPauta
    public function beletins_post(Request $request)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        $turma = Turma::findOrFail($request->turmas_id);

        $totalDisciplinas = DisciplinaTurma::with(["disciplina"])->where("turmas_id", $turma->id)->count("id");

        $turmasEstudante = EstudantesTurma::with(["estudante"])
            ->where("turmas_id", $turma->id)
            ->where("ano_lectivos_id", $request->ano_lectivos_id)
            ->get();

        $anolectivo = AnoLectivo::findOrFail($request->ano_lectivos_id);

        $trimestre = ControlePeriodico::findOrFail($request->trimestre_id);

        $escola = Shcool::with("ensino")->findOrFail($this->escolarLogada());
        $director = Director::where("level", "4")->where("instituicao_id", $escola->id)->first();

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "titulo" => "Boletins dos Estudantes",
            "descricao" => env('APP_NAME'),
            "estudantes" => $turmasEstudante,
            "trimestre" => $trimestre,
            "turma" => $turma,
            "curso" => Curso::findOrFail($turma->cursos_id),
            "sala" => Sala::findOrFail($turma->salas_id),
            "classe" => Classe::findOrFail($turma->classes_id),
            "turno" => Turno::findOrFail($turma->turnos_id),
            "anoLectivo" => $anolectivo,
            "totalDisciplinas" => $totalDisciplinas,
            "director" => $director,
        ];

        $orintacao = 'portrait';

        $pdf = \PDF::loadView('downloads.estudantes.boletins-estudante', $headers)->setPaper('A4', $orintacao);
        return $pdf->stream('estudantes.boletins.estudante.pdf');


        return view('relatorios.boletins-estudantes', $headers);
    }

    // carregar as disciplinas de uma turma via AJAX para as mini pautas
    public function carregarTurmasPautas($id)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO

        $turmaId = Turma::findOrFail($id);

        $disciplinasTurma = DisciplinaTurma::where([
            ['turmas_id', '=', $turmaId->id]
        ])
            ->join('tb_disciplinas', 'tb_discplinas_turmas.disciplinas_id', '=', 'tb_disciplinas.id')
            ->select('tb_disciplinas.id', 'tb_disciplinas.disciplina')
            ->get();

        if ($disciplinasTurma) {
            return response()->json([
                "status" => 200,
                "disciplinasTurma" => $disciplinasTurma,
                "curso" => $disciplinasTurma,
                "sala" => $disciplinasTurma,
                "classe" => $disciplinasTurma,
                "turno" => $disciplinasTurma,
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        } else {
            return response()->json([
                "status" => 404,
                "message" => 'Sem disciplinas cadastradas para este cursos'
            ]);
        }
    }

    // OUTROS RELATORIOS

    // --------------------------------------------------------------------------------------
    // ----------------------------------END RELATORIOS ----------------------------------------
    // --------------------------------------------------------------------------------------
    // --------------------------------------------------------------------------------------


    // ExTRAS

    function mes_retorno(string $mes)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO


        if ($mes == "Feb") {
            return "Fev";
        } else if ($mes == "Apr") {
            return "Abr";
        } else if ($mes == "May") {
            return "Mai";
        } else if ($mes == "Aug") {
            return "Ago";
        } else if ($mes == "Sep") {
            return "Set";
        } else if ($mes == "Oct") {
            return "Out";
        } else if ($mes == "Dec") {
            return "Dez";
        } else {
            return $mes;
        }
    }



    // pagamento de propina estudanets
    public function funcionariosPagamentoSalario($id)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO


        $funcionarios = Funcionarios::findOrFail($id);
        $cartao = CartaoFuncionario::where([
            ['funcionarios_id', '=', $funcionarios->id],
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
        ])->join('tb_meses', 'tb_cartoes_funcionarios.mes_id', '=', 'tb_meses.id')
            ->select('tb_meses.id', 'tb_meses.meses', 'tb_meses.abreviacao', 'tb_cartoes_funcionarios.status')
            ->get();

        $contrato = FuncionariosControto::where([
            ['funcionarios_id', '=', $funcionarios->id],
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
        ])->first();




        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "usuario" => User::findOrFail(Auth::user()->id),
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            "cartao" => $cartao,
            "mesesAdd" => DetalhesPagamentoPropina::where([
                ['status', '=', 'processo'],
                ['funcionarios_id', '=', Auth::user()->id],
                ['date_att', '=', $this->data_sistema()],
            ])
                ->join('tb_meses', 'tb_detalhes_pagamentos.mes_id', '=', 'tb_meses.id')
                ->select('tb_detalhes_pagamentos.quantidade', 'tb_detalhes_pagamentos.status', 'tb_detalhes_pagamentos.preco', 'tb_detalhes_pagamentos.id', 'tb_meses.meses', 'tb_meses.abreviacao')
                ->get(),
            "funcionarios" => $funcionarios,
            "somaVolores" => DetalhesPagamentoPropina::where([
                ['status', '=', 'processo'],
                ['funcionarios_id', '=', Auth::user()->id],
                ['date_att', '=', $this->data_sistema()],
            ])->sum('preco'),
            "somaQuantidade" => DetalhesPagamentoPropina::where([
                ['status', '=', 'processo'],
                ['funcionarios_id', '=', Auth::user()->id],
                ['date_att', '=', $this->data_sistema()],
            ])->sum('quantidade'),
            "contratoFuncionario" => $contrato,
        ];

        return view('admin.financeiros.pagamento-salario', $headers);
    }

    public function INSS_IRT($id, $faltas)
    {
        $funcionarios = Funcionarios::findOrFail($id);
        $contrato = FuncionariosControto::where([
            ['funcionarios_id', '=', $funcionarios->id],
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
        ])->first();

        $total_remuneracao_iliquida = $contrato->salario + $contrato->subcidio + $contrato->subcidio_alimentacao + $contrato->subcidio_transporte  + $contrato->subcidio_ferais + $contrato->subcidio_natal  + $contrato->subcidio_abono_familiar;

        // decreto 227/18
        // regime juridica de vinculação e da contribuição proteção social obrigatorio

        // ARTIGO 13º  Este são o salario que não entrar no imposto de seguranca social
        $base_incidencia_seguranca_social = $total_remuneracao_iliquida - $contrato->subcidio_ferais - $contrato->subcidio_abono_familiar;

        $inss_conta_trabalhador = $base_incidencia_seguranca_social * (3 / 100);
        $inss_conta_empresa = $base_incidencia_seguranca_social * (8 / 100);

        // Salario base é completamente sujeita ao IRT
        /*
            existe subsidio que não são sujeitas a IRT no seu todo por exemplo O subscidio de alimentação e transporte
            só é pago o imposto quando for maior que 30000 caso não não e sujeita o IRT
            */
        if ($contrato->subcidio_alimentacao >= 30000) {
            $remuracao_nao_sujeita_irt_alimentacao = $contrato->subcidio_alimentacao - 30000;
        } else {
            $remuracao_nao_sujeita_irt_alimentacao = $contrato->subcidio_alimentacao;
        }

        if ($contrato->subcidio_transporte >= 30000) {
            $remuracao_nao_sujeita_irt_transporte = $contrato->subcidio_transporte - 30000;
        } else {
            $remuracao_nao_sujeita_irt_transporte = $contrato->subcidio_transporte;
        }

        $remuracao_sujeita_irt_salario = $contrato->salario;
        $remuracao_sujeita_irt_ferias = $contrato->subcidio_ferais;
        $remuracao_sujeita_irt_natal = $contrato->subcidio_natal;
        $remuracao_sujeita_irt_familiar = $contrato->subcidio_abono_familiar - ($contrato->salario * (5 / 100));

        $total_remuneracao_sujeita_IRT = $remuracao_nao_sujeita_irt_alimentacao + $remuracao_nao_sujeita_irt_transporte + $remuracao_sujeita_irt_familiar + $remuracao_sujeita_irt_salario + $remuracao_sujeita_irt_ferias + $remuracao_sujeita_irt_natal;

        // materia colectavel
        $base_tributaria_irt = $total_remuneracao_sujeita_IRT - $inss_conta_trabalhador;

        $escalao = 0;
        $excesso = 0;
        $taxa = 0;
        $parcela_fixa = 0;

        if ($base_tributaria_irt >= 0 and $base_tributaria_irt <= 70000) {
            // $escalao = "1º Escalão";
            $escalao = 0;
            $excesso = $base_tributaria_irt - $escalao;
            $taxa = 0;
            $parcela_fixa = 0;
        } else if ($base_tributaria_irt >= 70001 and $base_tributaria_irt <= 100000) {
            // $escalao = "2º Escalão";
            $escalao = 70000;
            $excesso = $base_tributaria_irt - $escalao;
            $taxa = 10 / 100;
            $parcela_fixa = 3000;
        } else if ($base_tributaria_irt >= 100000 and $base_tributaria_irt <= 150000) {
            // $escalao = "3º Escalão";
            $escalao = 100000;
            $excesso = $base_tributaria_irt - $escalao;
            $taxa = 13 / 100;
            $parcela_fixa = 6000;
        } else if ($base_tributaria_irt >= 150001 and $base_tributaria_irt <= 200000) {
            // $escalao = "4º Escalão";
            $escalao = 150000;
            $excesso = $base_tributaria_irt - $escalao;
            $taxa = 16 / 100;
            $parcela_fixa = 12500;
        } else if ($base_tributaria_irt >= 200001 and $base_tributaria_irt <= 300000) {
            // $escalao = "5º Escalão";
            $escalao = 200000;
            $excesso = $base_tributaria_irt - $escalao;
            $taxa = 18 / 100;
            $parcela_fixa = 31250;
        } else if ($base_tributaria_irt >= 300001 and $base_tributaria_irt <= 500000) {
            // $escalao = "6º Escalão";
            $escalao = 300000;
            $excesso = $base_tributaria_irt - $escalao;
            $taxa = 19 / 100;
            $parcela_fixa = 49250;
        } else if ($base_tributaria_irt >= 500001 and $base_tributaria_irt <= 1000000) {
            // $escalao = "7º Escalão";
            $escalao = 500000;
            $excesso = $base_tributaria_irt - $escalao;
            $taxa = 20 / 100;
            $parcela_fixa = 87250;
        } else if ($base_tributaria_irt >= 1000001 and $base_tributaria_irt <= 1500000) {
            // $escalao = "8º Escalão";
            $escalao = 1000000;
            $excesso = $base_tributaria_irt - $escalao;
            $taxa = 21 / 100;
            $parcela_fixa = 187250;
        } else if ($base_tributaria_irt >= 1500001 and $base_tributaria_irt <= 2000000) {
            // $escalao = "9º Escalão";
            $escalao = 1500000;
            $excesso = $base_tributaria_irt - $escalao;
            $taxa = 22 / 100;
            $parcela_fixa = 292000;
        } else if ($base_tributaria_irt >= 2000001 and $base_tributaria_irt <= 2500000) {
            // $escalao = "10º Escalão";
            $escalao = 2000000;
            $excesso = $base_tributaria_irt - $escalao;
            $taxa = 23 / 100;
            $parcela_fixa = 402250;
        } else if ($base_tributaria_irt >= 2500001 and $base_tributaria_irt <= 5000000) {
            // $escalao = "11º Escalão";
            $escalao = 2500000;
            $excesso = $base_tributaria_irt - $escalao;
            $taxa = 24 / 100;
            $parcela_fixa = 517250;
        } else if ($base_tributaria_irt >= 5000001 and $base_tributaria_irt <= 10000000) {
            // $escalao = "12º Escalão";
            $escalao = 5000000;
            $excesso = $base_tributaria_irt - $escalao;
            $taxa = 24.5 / 100;
            $parcela_fixa = 1117250;
        } else if ($base_tributaria_irt >= 10000001) {
            // $escalao = "13º Escalão";
            $escalao = 10000000;
            $excesso = $base_tributaria_irt - $escalao;
            $taxa = 25 / 100;
            $parcela_fixa = 2342250;
        }

        // imposto de redimento de trabalho
        $irt = ($excesso * $taxa) + $parcela_fixa;

        return $result = array(
            'salario_iliquido' => $total_remuneracao_iliquida,
            'inss_trabalhador' => $inss_conta_trabalhador,
            'inss_empresa' => $inss_conta_empresa,
            'irt' => $irt,
            'material' => $base_tributaria_irt,
            'descontos' => $inss_conta_trabalhador + $irt + ($contrato->falta_por_dia * $faltas),
            'faltas' => $contrato->falta_por_dia * $faltas,
            'salario_liquido' => $total_remuneracao_iliquida - ($inss_conta_trabalhador + $irt + ($contrato->falta_por_dia * $faltas)),
            'base_incidencia_seguranca_social' => $base_incidencia_seguranca_social,
        );
    }

    public function funcionariosPagamentoSalarioCreate(Request $request)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO


        $funcionarios = Funcionarios::findOrFail($request->input('funcionarios_id'));

        $validate = Validator::make($request->all(), [
            'valor' => 'required',
            'tipo_pagamento' => 'required',
        ], [
            "valor.required" => "******",
            "tipo_pagamento.required" => "******",
        ]);

        if (
            (!filter_var($request->input('valor'), FILTER_VALIDATE_FLOAT) and !filter_var($request->input('valor'), FILTER_VALIDATE_INT)) and
            (!filter_var($request->input('desconto'), FILTER_VALIDATE_FLOAT) and !filter_var($request->input('desconto'), FILTER_VALIDATE_INT))
        ) {
            return response()->json([
                'status' => 300,
                'message' => "Os Valores não podem ser Letras por favor",
            ]);
        }

        $contrato = FuncionariosControto::where([
            ['funcionarios_id', '=', $funcionarios->id],
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
        ])->first();

        if ($request->input('valor') < $contrato->salario) {
            return response()->json([
                'status' => 300,
                'message' => "Valores Invalido, a valor estipulado para Salário é
                {$contrato->salario}, tenta novamente!",
            ]);
        }

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        } else {

            $ficha = DetalhesPagamentoPropina::where([
                ['status', '=', 'processo'],
                ['funcionarios_id', '=', Auth::user()->id],
                ['date_att', '=', $this->data_sistema()],
            ])->first();

            $somaVolores = DetalhesPagamentoPropina::where([
                ['status', '=', 'processo'],
                ['funcionarios_id', '=', Auth::user()->id],
                ['date_att', '=', $this->data_sistema()],
            ])->sum('preco');

            $somaQuantidade = DetalhesPagamentoPropina::where([
                ['status', '=', 'processo'],
                ['funcionarios_id', '=', Auth::user()->id],
                ['date_att', '=', $this->data_sistema()],
            ])->sum('quantidade');


            $calculo = $this->INSS_IRT($funcionarios->id, $request->input('faltas'));

            $contarFacturas = Pagamento::where([
                ['ano_lectivos_id', ' = ', $this->anolectivoActivo()],
            ])->count();

            $anolectivoEscola = AnoLectivo::findOrFail($this->anolectivoActivo());

            $createP = new Pagamento();
            $createP->pago_at = "salario";
            $createP->quantidade = $somaQuantidade;
            $createP->status = $request->input('pagamento');
            $createP->caixa_at = 'despesa';
            $createP->ficha = $ficha->code;
            $createP->valor = $calculo['salario_iliquido'];
            $createP->desconto =  $calculo['descontos'];
            $createP->inss = $calculo['inss_trabalhador'];
            $createP->irt = $calculo['irt'];
            $createP->faltas = $calculo['faltas'];
            $createP->subcidio = $request->input('subcidio');
            $createP->subcidio_transporte = $request->input('subcidio_transporte');
            $createP->subcidio_alimentacao = $request->input('subcidio_alimentacao');
            $createP->subcidio_natal = $request->input('subcidio_natal');
            $createP->subcidio_ferias = $request->input('subcidio_ferias');
            $createP->subcidio_abono_familiar = $request->input('subcidio_abono_familiar');
            // $createP->multa = $request->input('multa');
            $createP->banco = $request->input('banco');
            $createP->numero_transacao = $request->input('numero_transicao');
            $createP->tipo_pagamento = $request->input('tipo_pagamento');
            $createP->data_at = $this->data_sistema();
            $createP->mensal = $this->mesecompleto();
            $createP->funcionarios_id = Auth::user()->id;
            $createP->estudantes_id = $funcionarios->id;
            $createP->model = 'funcionario';
            $createP->ano_lectivos_id = $this->anolectivoActivo();
            $createP->numero_factura = $contarFacturas + 1;
            $createP->tipo_factura = 'FR';
            $createP->next_factura = $createP->tipo_factura . "_" . $createP->numero_factura . "/" . $anolectivoEscola->ano;
            $createP->shcools_id = $this->escolarLogada();
            $createP->save();

            if (!$createP->save()) {
                return response()->json([
                    'status' => 300,
                    'message' => "Ocorreu um erro ao cadastrar as informações do pagamento para a matricula do estudante
                    [{$funcionarios->nome} {$funcionarios->sobre_nome}], tenta novamente ou entrar em contacto o desenvovidor
                    do sistema!",
                ]);
            }

            $detalhePagamento = DetalhesPagamentoPropina::where([
                ['status', '=', 'processo'],
                ['funcionarios_id', '=', Auth::user()->id],
                ['date_att', '=', $this->data_sistema()],
            ])->get();

            if ($detalhePagamento) {
                foreach ($detalhePagamento as $ficha) {
                    $upd = DetalhesPagamentoPropina::find($ficha->id);
                    $upd->status = 'Pago';
                    $upd->update();

                    $cartao = CartaoFuncionario::where([
                        ['status', '=', 'processo'],
                        ['funcionarios_id', '=', $funcionarios->id],
                        ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                    ])->first();

                    $updateCartao = CartaoFuncionario::find($cartao->id);
                    $updateCartao->status = 'Pago';
                    $updateCartao->save();
                }
            }

            $idpagamento = DB::table('tb_pagamentos')->where([
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ])->max('id');
            $fichapagamento = Pagamento::findOrFail($idpagamento);

            return response()->json([
                'status' => 200,
                'message' => 'Dados salvos com sucesso!',
                "ficha" => $fichapagamento->ficha, //->get();
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        }
    }

    public function funcionarioDetalhesPagamentoSalario($id, $func)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO


        $mes = Mes::findOrFail($id);
        $funcionarios = Estudante::findOrFail($func);

        $contrato = FuncionariosControto::where([
            ['funcionarios_id', '=', $funcionarios->id],
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
        ])->first();

        $verificar = DetalhesPagamentoPropina::where([
            ['status', '=', 'processo'],
            ['funcionarios_id', '=', Auth::user()->id],
            ['date_att', '=', $this->data_sistema()],
        ])->first();

        $verificar1 = DetalhesPagamentoPropina::where([
            ['status', '=', 'processo'],
            ['funcionarios_id', '=', Auth::user()->id],
            ['date_att', '=', $this->data_sistema()],
            ['mes_id', '=', $mes->id],
        ])->first();

        if ($verificar1) {
            return redirect()->route('web.funcionarios-pagamento-salario', $funcionarios->id);
        }

        $add = new DetalhesPagamentoPropina();
        if ($verificar) {
            $add->code = $verificar->code;
        } else {
            $add->code = time();
        }
        $add->mes_id =     $mes->id;
        $add->quantidade =     '1';
        $add->model =     'salario';
        $add->ano_lectivos_id = $this->anolectivoActivo();
        $add->shcools_id = $this->escolarLogada();
        $add->funcionarios_id = Auth::user()->id;
        $add->preco = $contrato->salario;
        $add->status = 'processo';
        $add->date_att = $this->data_sistema();

        $listCartao = CartaoFuncionario::where([
            ['status', '<>', 'Pago'],
            ['funcionarios_id', '=', $funcionarios->id],
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['mes_id', '=', $mes->id],
        ])->first();

        $update = CartaoFuncionario::findOrFail($listCartao->id);
        $update->status = 'processo';

        if ($add->save()) {
            $update->update();
            return redirect()->route('web.funcionarios-pagamento-salario', $funcionarios->id);
        }
    }

    public function funcionariosDetalhesPagamentoSalarioRemoverMes($id, $func, $mess)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO


        $detalhe = DetalhesPagamentoPropina::findOrFail($id);
        $funcionarios = Funcionarios::findOrFail($func);

        $meses = Mes::where([
            ['meses', '=', $mess]
        ])->first();

        $updateCartao = CartaoFuncionario::where([
            ['status', '=', 'processo'],
            ['funcionarios_id', '=', $funcionarios->id],
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['mes_id', '=', $meses->id],
        ])->first();

        $update = CartaoFuncionario::findOrFail($updateCartao->id);
        $update->status = 'Nao pago';

        $detalhe->delete();
        $update->update();
        return redirect()->route('web.funcionarios-pagamento-salario', $funcionarios->id);
    }


    // algumas funcções extras
    /**
     * Convert number of seconds into hours, minutes and seconds
     * and return an array containing those values
     *
     * @param integer $inputSeconds Number of seconds to parse
     * @return array
     */
    public function enviarSMS($telefone, $sms)
    {
        $res = Http::post('https://telcosms.co.ao/send_message', [
            'message' => [
                'api_key_app' => 'qas5eef79d037ec708c0fefdc49ea',
                'phone_number' => $telefone,
                'message_body' => $sms,
            ],
        ]);
        return $res['status'];
    }
}
