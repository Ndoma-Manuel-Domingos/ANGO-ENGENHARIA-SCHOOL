<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Professor;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\calendarios\Pagamento;
use App\Models\web\calendarios\Servico;
use App\Models\web\financeiros\DetalhesPagamentoPropina;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\FormaPagamento;
use App\Models\Shcool;
use App\Models\web\anolectivo\ControlePeriodico;
use App\Models\web\calendarios\CartaoEscola;
use App\Models\web\calendarios\Deposito;
use App\Models\web\calendarios\MapaEfectividade;
use App\Models\web\calendarios\Matricula;
use App\Models\web\calendarios\Mes;
use App\Models\web\calendarios\ServicoTurma;
use App\Models\web\classes\AnoLectivoClasse;
use App\Models\web\classes\Classe;
use App\Models\web\cursos\AnoLectivoCurso;
use App\Models\web\estudantes\CartaoEstudante;
use App\Models\web\estudantes\Estudante;
use App\Models\web\funcionarios\CartaoFuncionario;
use App\Models\web\funcionarios\FuncionariosControto;
use App\Models\web\salas\Banco;
use App\Models\web\salas\Caixa;
use App\Models\web\salas\MovimentoCaixa;
use App\Models\web\turmas\EstudantesTurma;
use App\Models\web\turmas\Turma;
use App\Models\web\turnos\AnoLectivoTurno;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use phpseclib\Crypt\RSA;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use NumberFormatter;


class FinanceiroController extends Controller
{
    use TraitChavesSaft;
    use TraitHelpers;


    public function __construct()
    {
        $this->middleware('auth');
    }


    public function indexPagamento()
    {
        $user = auth()->user();

        if (!$user->can('read: pagamento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário
        
        $inicioUltimoMes = Carbon::now()->subMonth()->startOfMonth();
        $fimUltimoMes    = Carbon::now()->subMonth()->endOfMonth();

        $ano = AnoLectivo::findOrFail($this->anolectivoActivo());
        
        $queryContaReceber = Pagamento::where('ano_lectivos_id', $ano->id)
            ->where('caixa_at', 'receita')
            ->where('status', 'Confirmado')
            ->where('tipo_factura', 'FR')
            ->where('anulado', 'N');
        
        $pagValReceber = (clone $queryContaReceber)->sum('valor2');
        
        $pagValReceberUltimoMes = (clone $queryContaReceber)
            ->whereBetween('created_at', [$inicioUltimoMes, $fimUltimoMes])
            ->sum('valor2');    
        
        $queryContaPagar = Pagamento::where('ano_lectivos_id', $ano->id)
            ->where('caixa_at', 'despesa')
            ->where('status', 'Confirmado')
            ->where('anulado', 'N');
        
        $pagValPagar = (clone $queryContaPagar)->sum('valor2');
        
        $pagValPagarUltimoMes = (clone $queryContaPagar)
            ->whereBetween('created_at', [$inicioUltimoMes, $fimUltimoMes])
            ->sum('valor2');
            

        $dividaAcumuladas = CartaoEstudante::with(['estudante.matricula', 'servico'])
            ->where('ano_lectivos_id', $ano->id)
            ->whereHas('estudante', function ($query) {
                $query->where('shcools_id', $this->escolarLogada());
            })
            ->whereIn('status', ['divida'])
            ->sum('preco_unitario');

        $multaAcumuladasPagas = CartaoEstudante::with(['estudante.matricula', 'servico'])
            ->where('ano_lectivos_id', $ano->id)
            ->whereHas('estudante', function ($query) {
                $query->where('shcools_id', $this->escolarLogada());
            })
            ->whereIn('status', ['Pago'])
            ->sum('multa');

        $multaAcumuladasNaoPagas = CartaoEstudante::with(['estudante.matricula', 'servico'])
            ->where('ano_lectivos_id', $ano->id)
            ->whereHas('estudante', function ($query) {
                $query->where('shcools_id', $this->escolarLogada());
            })
            ->whereIn('status', ['divida'])
            ->sum('multa');
            
            
        $total = $pagValReceber + $pagValPagar;
        
        $pagValPagar   = (float) ($pagValPagar ?? 0);
        $pagValReceber = (float) ($pagValReceber ?? 0);
        
        if ($pagValReceber > 0) {
            $saida_percentagem     = ($pagValPagar / $pagValReceber) * 100;
            $restante_percentagem  = (($pagValReceber - $pagValPagar) / $pagValReceber) * 100;
        } else {
            $saida_percentagem     = 0;
            $restante_percentagem  = 0;
        }

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Painel Financeiro",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "verAnoLectivoActivo" => $ano,
            'pagamentos' => Pagamento::where('tb_pagamentos.ano_lectivos_id', $ano->id)
                ->join('users', 'tb_pagamentos.funcionarios_id', '=', 'users.id')
                ->get(),

            'pagamentosValoresReceber' => $pagValReceber,
            'pagValReceberUltimoMes' => $pagValReceberUltimoMes,
            'saida_percentagem' => $saida_percentagem,
            
            'pagamentosValoresPagar' => $pagValPagar,
            'pagValPagarUltimoMes' => $pagValPagarUltimoMes,
            'restante_percentagem' => $restante_percentagem,
            
            'multaAcumuladasPagas' => $multaAcumuladasPagas,
            'multaAcumuladasNaoPagas' => $multaAcumuladasNaoPagas,
            'dividaAcumuladas' => $dividaAcumuladas,

        ];

        return view('admin.financeiros.controle', $headers);
    }

    public function depositos(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: estudante') && !$user->can('read: matricula')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário


        if ($request->ano_lectivos_id) {
            $request->ano_lectivos_id = Crypt::decrypt($request->ano_lectivos_id);
        } else {
            $request->ano_lectivos_id = $this->anolectivoActivo();
        }

        $depositos = Deposito::with(['escola', 'estudante', 'ano', 'operador'])
            ->when($request->ano_lectivos_id, function ($query, $value) {
                $query->where('ano_lectivos_id', $value);
            })
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate('date_at', '>=', $value);
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('date_at', '<=', $value);
            })
            ->get();

        $anos_lectivos = AnoLectivo::where('shcools_id', '=', $this->escolarLogada())->get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Esxtrato Financeiro do Estudantes",
            "descricao" => env('APP_NAME'),
            "depositos" => $depositos,
            "anos_lectivos" => $anos_lectivos,
            "usuario" => User::findOrFail(Auth::user()->id),
        ];


        return view('admin.financeiros.depositos', $headers);
    }

    public function estudantes(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: estudante') && !$user->can('read: matricula')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

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
            ->where('tb_matriculas.ano_lectivos_id', $this->anolectivoActivo())
            ->where('tb_matriculas.shcools_id', $this->escolarLogada())
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

        $cursos = AnoLectivoCurso::where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('shcools_id', $this->escolarLogada())
            ->with(['curso'])
            ->get();


        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "Listagem de estudantes com valores da mensalidade",
            "descricao" => env('APP_NAME'),
            "classes" => $classes,
            "turnos" => $turnos,
            "cursos" => $cursos,
            "matriculas" => $matriculas,
            "usuario" => User::findOrFail(Auth::user()->id),
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            "filtros" => $request->all('status', 'cursos_id', 'classes_id', 'turnos_id', 'genero', 'finalista'),
        ];

        return view('admin.financeiros.estudantes', $headers);
    }

    public function actualizar_factura($id)
    {
        // $user = auth()->user();

        // if (!$user->can('read: pagamento') && !$user->can('update: pagamento')) {
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        //grafico todos os pagamentos entradas
        $pagamento = Pagamento::findOrFail(Crypt::decrypt($id));

        $pagamentos = Pagamento::where('shcools_id', $this->escolarLogada())->where('ano_lectivos_id', $this->anolectivoActivo())->get();



        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "Actualizar data do documento",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),

            'pagamento' => $pagamento,
            'pagamentos' => $pagamentos,
        ];

        return view('admin.financeiros.actualizar-pagamento', $headers);
    }

    public function actualizar_factura_store(Request $request)
    {
        // $user = auth()->user();

        // if (!$user->can('read: pagamento') && !$user->can('update: pagamento')) {
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $request->validate([
            'data_emissao' => 'required',
            'pagamento_id' => 'required'
        ], [
            'data_emissao.required' => 'Campo obrigatório',
            'pagamento_id.required' => 'Campo obrigatório'
        ]);

        if (count($request->item_id) == 0) {
            //grafico todos os pagamentos entradas
            $pagamento = Pagamento::findOrFail($request->pagamento_id);
            $pagamento->data_at = $request->data_emissao;
            $pagamento->data_vencimento = $request->data_emissao;
            $pagamento->data_disponibilizacao = $request->data_emissao;
            $pagamento->created_at = $request->data_emissao . " " . date('H:i:s');
            $pagamento->update();
        } else {

            foreach ($request->item_id as $i) {
                $update_pagamento = Pagamento::findOrFail($i);
                $update_pagamento->data_at = $request->data_emissao;
                $update_pagamento->data_vencimento = $request->data_emissao;
                $update_pagamento->data_disponibilizacao = $request->data_emissao;
                $update_pagamento->created_at = $request->data_emissao . " " . date('H:i:s');
                $update_pagamento->update();
            }
        }

        Alert::success('Bom Trabalho', 'Documento actualizado com successo!');
        return redirect()->back();
    }

    public function dadosMensalidades()
    {

        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '2048M'); // ou mais se necessário

        $servico_propina = Servico::whereIn('servico', ['Propinas'])->where('shcools_id', $this->escolarLogada())->first();

        $dados = CartaoEstudante::select(
            'month_name',
            // Contagem
            DB::raw("SUM(CASE WHEN status = 'Pago' THEN 1 ELSE 0 END) as qtd_pago"),
            DB::raw("SUM(CASE WHEN status = 'divida' THEN 1 ELSE 0 END) as qtd_divida"),
            DB::raw("SUM(CASE WHEN status = 'Nao Pago' THEN 1 ELSE 0 END) as qtd_nao_pago"),
            DB::raw("SUM(CASE WHEN status = 'Isento' THEN 1 ELSE 0 END) as qtd_isento"),

            // Somatórios
            DB::raw("SUM(CASE WHEN status = 'Pago' THEN preco_unitario + multa ELSE 0 END) as total_pago"),
            DB::raw("SUM(CASE WHEN status = 'divida' THEN preco_unitario ELSE 0 END) as total_divida"),
            DB::raw("SUM(CASE WHEN status = 'Nao Pago' THEN preco_unitario ELSE 0 END) as total_nao_pago"),
            DB::raw("SUM(CASE WHEN status = 'Isento' THEN preco_unitario ELSE 0 END) as total_isento")
        )
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('servicos_id', $servico_propina->id)
            ->where('mes_id', 'M')
            ->groupBy('month_name')
            ->orderByRaw("FIELD(month_name, 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug')")
            ->get();

        $totalAno = [
            'pago' => 0,
            'divida' => 0,
            'nao_pago' => 0,
            'isento' => 0,
        ];

        foreach ($dados as $dado) {
            $totalAno['pago']     += $dado->total_pago;
            $totalAno['divida']   += $dado->total_divida;
            $totalAno['nao_pago'] += $dado->total_nao_pago;
            $totalAno['isento']   += $dado->total_isento;
        }

        $valorTotalAno = array_sum($totalAno);

        $percentuais = [
            'pago' => $valorTotalAno > 0 ? round(($totalAno['pago'] * 100) / $valorTotalAno, 2) : 0,
            'divida' => $valorTotalAno > 0 ? round(($totalAno['divida'] * 100) / $valorTotalAno, 2) : 0,
            'nao_pago' => $valorTotalAno > 0 ? round(($totalAno['nao_pago'] * 100) / $valorTotalAno, 2) : 0,
            'isento' => $valorTotalAno > 0 ? round(($totalAno['isento'] * 100) / $valorTotalAno, 2) : 0,
        ];

        return response()->json([
            'mensalidades' => $dados,
            'total_ano' => $totalAno,
            'percentuais' => $percentuais
        ]);
    }



    public function buscasGerais(Request $request)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $user = auth()->user();

        if (!$user->can('read: pagamento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        if (!$request->ano_lectivo_id) {
            $request->ano_lectivo_id = $this->anolectivoActivo();
        }

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        $pagamentos = DetalhesPagamentoPropina::with(["pagamento.operador", "servico"])
            ->when($request->ano_lectivo_id, function ($query, $value) {
                $query->where('ano_lectivos_id', $value);
            })
            ->whereHas('pagamento', function ($q) use ($request) {
                $q->where('caixa_at', 'receita')
                    ->where('status', 'Confirmado');

                $q->when($request->forma_pagamento_id, function ($query, $value) {
                    $query->where('pagamento_id', $value);
                });

                $q->when($request->caixa_id, function ($query, $value) {
                    $query->where('caixa_id', '=', $value);
                })
                    ->when($request->user_id, function ($query, $value) {
                        $query->where('funcionarios_id', '=', $value);
                    })
                    ->when($request->type, function ($query, $value) {
                        $query->where('caixa_at', '=', $value);
                    });
            })
            ->when($request->servico_id, function ($query, $value) {
                $query->where('servicos_id', $value);
            })
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate('date_att', '>=', Carbon::createFromDate($value));
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('date_att', '<=', Carbon::createFromDate($value));
            })
            ->where('shcools_id', $escola->id)
            ->get();


        $headers = [
            "escola" => $escola,
            "titulo" => "Listagem de Pagamentos",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "servicos" => Servico::where('shcools_id', $escola->id)->get(),
            "listasanolectivo" => AnoLectivo::where('shcools_id', $escola->id)->get(),
            "pagamentos" => $pagamentos,
            "formas_pagamento" => FormaPagamento::where('status_id', 1)->get(),
            "filtro" => $request->all('data_inicio', 'data_final', 'servico_id', 'forma_pagamento_id', 'ano_lectivo_id', 'type_id'),
        ];

        return view('admin.financeiros.buscas-gerais', $headers);
    }


    public function outrasBuscas(Request $request)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $user = auth()->user();

        if (!$user->can('read: pagamento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        if (!$request->ano_lectivo_id) {
            $request->ano_lectivo_id = $this->anolectivoActivo();
        }



        $pagamentosDetalhes = [];
        $pagamentos = [];
        $servico = Servico::find($request->input('servico_id'));

        $pagamentos = Pagamento::when($request->ano_lectivo_id, function ($query, $value) {
            $query->where('ano_lectivos_id', '=', $value);
        })
            ->when($request->servico_id, function ($query, $value) {
                $query->where('servicos_id', '=', $value);
            })
            ->when($request->mensal, function ($query, $value) {
                $query->where('mensal', '=', $value);
            })
            ->when($request->forma_pagamento, function ($query, $value) {
                $query->where('pagamento_id', '=', $value);
            })
            ->whereIn('caixa_at', ['receita'])
            ->whereIn('status', ['Confirmado'])
            ->with(['servico', 'operador'])
            ->where('shcools_id', $this->escolarLogada())
            ->get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "Buscas de Pagamentos Mensais",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            'pagamentos' => $pagamentos,
            "formas_pagamento" => FormaPagamento::where('status_id', 1)->get(),
            'pagamentosDetalhes' => $pagamentosDetalhes,

            "servicos" => Servico::where('contas', 'receita')->where('shcools_id', '=', $this->escolarLogada())->get(),
            "listasanolectivo" => AnoLectivo::where('shcools_id', $this->escolarLogada())->get(),

            'servico' => $servico,
            'requests' => $request->all('servico_id', 'mensal', 'forma_pagamento', 'estado_pagamento', 'ano_lectivo_id'),
        ];
        return view('admin.financeiros.outras-buscas',  $headers);
    }


    public function pagamentosPropina(Request $request)
    {

        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário
        $user = auth()->user();

        if (!$user->can('create: pagamento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $matriculas = null;
        if ($request->search) {
            $estudantes = Estudante::where('documento', $request->search)
                ->orWhere('numero_processo', $request->search)
                ->orWhere('bilheite', $request->search)
                ->orWhere('nome', 'like', "%{$request->search}%")
                ->orWhere('sobre_nome', 'like', "%{$request->search}%")
                ->orWhere('nome_completo', 'like', "%{$request->search}%")
                ->pluck('id');

            $matriculas = Matricula::with(['estudante'])->whereIn('estudantes_id', $estudantes)
                ->where('shcools_id', $this->escolarLogada())
                ->where('status_matricula', '=', 'confirmado')
                ->where('ano_lectivos_id', '=', $this->anolectivoActivo())
                ->get();
        }

        $estudantes_ = Estudante::where('shcools_id', $this->escolarLogada())
            ->where('registro', 'confirmado')
            ->get();



        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "Pesquisar estudante",
            'matriculas' =>  $matriculas,
            'estudantes_' =>  $estudantes_,
            "descricao" => env('APP_NAME'),
            "matriculass" => Matricula::where([
                ['status_matricula', '=', 'confirmado'],
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['shcools_id', '=', $this->escolarLogada()],
            ])
                ->select('tb_matriculas.documento', 'tb_matriculas.numero_estudante')
                ->get(),
            "usuario" => User::findOrFail(Auth::user()->id),
            "requests" => $request->all('search'),
        ];

        return view('admin.financeiros.estudante-pagar', $headers);
    }

    public function propinasPorCurso(Request $request)
    {

        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário
        $user = auth()->user();

        if (!$user->can('create: pagamento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        if (!$request->anos_lectivos_id) {
            $request->anos_lectivos_id = $this->anolectivoActivo();
        }

        if (!$request->mes_id) {
            $mes = date("M");
        } else {
            $mes = $request->mes_id;
        }

        $ano_lectivos = AnoLectivo::where('shcools_id', $this->escolarLogada())->get();

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());
        $servico = Servico::where('servico', 'Propinas')->where('shcools_id', $this->escolarLogada())->first();

        $anolectivo = AnoLectivo::findOrFail($request->anos_lectivos_id);

        $cursos = DB::table('tb_ano_lectivo_cursos as alc')
            ->join('tb_cursos as c', 'c.id', '=', 'alc.cursos_id')
            ->select('alc.id as id_ano_curso', 'c.id as curso_id', 'c.curso', 'alc.ano_lectivos_id')
            ->where('alc.shcools_id', $this->escolarLogada())
            ->where('alc.ano_lectivos_id', $anolectivo->id)
            ->get()
            ->map(function ($curso) use ($anolectivo, $mes, $escola, $servico) {

                $classes = DB::table('tb_ano_lectivo_classes as alcl')
                    ->join('tb_classes as cl', 'cl.id', '=', 'alcl.classes_id')
                    ->leftJoin('tb_matriculas as m', function ($join) use ($curso, $anolectivo) {
                        $join->on('m.classes_id', '=', 'alcl.classes_id')
                            ->where('m.cursos_id', '=', $curso->curso_id)
                            ->where('m.ano_lectivos_id', '=', $anolectivo->id);
                    })
                    ->leftJoin('tb_cartao_estudantes as ce', 'ce.estudantes_id', '=', 'm.estudantes_id')
                    // 🔹 Adiciona o JOIN na tabela turmas para pegar o valor da propina
                    ->leftJoin('tb_turmas as t', function ($join) use ($curso, $anolectivo, $escola) {
                        $join->on('t.classes_id', '=', 'alcl.classes_id')
                            ->where('t.cursos_id', '=', $curso->curso_id)
                            ->where('t.shcools_id', '=', $escola->id)
                            ->where('t.ano_lectivos_id', '=', $anolectivo->id);
                    })
                    ->where('alcl.ano_lectivos_id', $anolectivo->id)
                    ->where('ce.month_name', $mes)
                    ->where('ce.servicos_id', $servico->id)
                    ->select(
                        'cl.classes',
                        DB::raw('IFNULL(t.valor_propina, 0) as valor_propina'), // 🔹 valor da mensalidade
                        DB::raw('COUNT(DISTINCT m.estudantes_id) as total_estudantes'),
                        DB::raw("COUNT(DISTINCT CASE WHEN ce.status IN ('Pago', 'Isento') AND ce.mes_id = 'M' THEN m.estudantes_id END) as total_pago"),
                        DB::raw("COUNT(DISTINCT CASE WHEN ce.status IN ('Nao Pago', 'divida') AND ce.mes_id = 'M' THEN m.estudantes_id END) as total_nao_pago")
                    )
                    ->groupBy('cl.id', 'cl.classes', 't.valor_propina')
                    ->orderBy('cl.id') // 🔹 garante que as classes não fiquem duplicadas
                    ->get();

                // 🔹 Soma total de estudantes e totais gerais
                $curso->classes = $classes;
                $curso->total_geral = $classes->sum('total_estudantes');
                $curso->total_pago = $classes->sum('total_pago');
                $curso->total_nao_pago = $classes->sum('total_nao_pago');

                return $curso;
            });

        $headers = [
            "escola" => $escola,
            "titulo" => "Mapas de pagamentos de Propinas Referente ao Mês Outubro de 2025",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "cursos" => $cursos,
            "anolectivo" => $anolectivo,
            "ano_lectivos" => $ano_lectivos,
            "requests" => ['anos_lectivos_id' => $request->anos_lectivos_id, 'mes_id' => $request->mes_id]
        ];

        return view('admin.financeiros.propinas-por-pagamentos', $headers);
    }

    // pagamento de propina estudanets
    public function estudantesPagamentoPropina($id)
    {

        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário
        $user = auth()->user();
        if (!$user->can('read: pagamento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $caixa = Caixa::where('status', '=', "activo")->where('shcools_id', '=', $this->escolarLogada())->where('usuario_id', '=', Auth::user()->id)->first();

        if (!$caixa) {
            Alert::error('Atenção', "Deves primeiramente fazer abertura do caixa, antes de fazer qualquer pagamento!");
            return redirect()->back();
        }

        $estudantes = Estudante::findOrFail(Crypt::decrypt($id));

        $turma = EstudantesTurma::where('estudantes_id', $estudantes->id)
            ->where('status', 'activo')
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->with(['turma.anolectivo', 'turma.turno', 'turma.classe', 'turma.sala', 'turma.curso'])
            ->first();

        if (!$turma) {
            Alert::warning("Informação", "Infelizmente não pode acessar esta área porque estudante não esta inserido em nenhuma turma!");
            return redirect()->back();
        }

        if ($turma) {
            $servicos = ServicoTurma::where('turmas_id', $turma->turmas_id)
                ->where('ano_lectivos_id', $this->anolectivoActivo())
                ->where('model', 'turmas')
                ->with(['servico'])
                ->get();
        }

        $bancos = Banco::where('shcools_id', $this->escolarLogada())->get();
        $caixas = Caixa::where('shcools_id', $this->escolarLogada())->whereIn('id', [$caixa->id])->get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            "anos_lectivos" => AnoLectivo::where('shcools_id', $this->escolarLogada())->orderBy('ordem', 'ASC')->get(),
            "formas_pagamento" => FormaPagamento::where('tipo_credito', 2)->get(),
            "servicos" => $servicos,
            "bancos" => $bancos,
            "caixas" => $caixas,
            "turma" => $turma,
            "estudantes" => $estudantes,
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.financeiros.pagamento-propina', $headers);
    }

    public function estudantesPagamentoPropinaCreate(Request $request)
    {
 
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário
        $user = auth()->user();

        if (!$user->can('read: pagamento') && !$user->can('create: pagamento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $caixa = Caixa::where('status', '=', "activo")->where('shcools_id', '=', $this->escolarLogada())->where('usuario_id', '=', Auth::user()->id)->first();

        if (!$caixa) {
            return response()->json([
                'status' => 300,
                'message' => "Não há nenhum caixa aberto no momento. Por favor, abra um caixa para continuar o processo.",
            ]);
        }

        $escola = Shcool::findOrFail($this->escolarLogada());

        $caixaAberto = MovimentoCaixa::where('caixa_id', $caixa->id)
            ->where('usuario_id', Auth::user()->id)
            ->where('status', "aberto")
        ->first()->id;

        if (!$caixaAberto) {
            return response()->json([
                'status' => 300,
                'message' => "Não há nenhum caixa aberto no momento. Por favor, abra um caixa para continuar o processo.",
            ]);
        }

        $estudantes = Estudante::findOrFail($request->input('estudantes_id'));

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $forma_pagamento = FormaPagamento::where('sigla_tipo_pagamento', $request->tipo_pagamento)->first();

            $validate = Validator::make($request->all(), [
                'valor' => 'required',
                'servico' => 'required',
                'tipo_pagamento' => 'required',
                'valor_entregue' => 'required',
                'valor_entregue_multicaixa' => 'required',
            ]);

            if ((!filter_var($request->valor, FILTER_VALIDATE_FLOAT) and !filter_var($request->valor, FILTER_VALIDATE_INT)) and
                (!filter_var($request->desconto, FILTER_VALIDATE_FLOAT) and !filter_var($request->desconto, FILTER_VALIDATE_INT)) and
                (!filter_var($request->valor_entregue, FILTER_VALIDATE_FLOAT) and !filter_var($request->valor_entregue, FILTER_VALIDATE_INT)) and
                (!filter_var($request->valor_entregue_multicaixa, FILTER_VALIDATE_FLOAT) and !filter_var($request->valor_entregue_multicaixa, FILTER_VALIDATE_INT)) and
                (!filter_var($request->multa, FILTER_VALIDATE_FLOAT) and !filter_var($request->multa, FILTER_VALIDATE_INT))
            ) {
                return response()->json([
                    'status' => 300,
                    'message' => "Os Valores não podem ser Letras por favor",
                ]);
            }

            $request->valor_entregue = (int) $request->valor_entregue;
            $request->desconto = (int) $request->desconto;
            $request->valor_entregue_multicaixa = (int) $request->valor_entregue_multicaixa;
            $request->saldo_a_descontar_do_estudante_id = (int) $request->saldo_a_descontar_do_estudante_id;
            $request->valor = (int) $request->valor;
            $request->quantidade = 1;

            if ($escola->desconto_percentagem == "Y") {
                // valor a pagar depois do desconto
                $valor_aplicado_desconto = $request->valor - ($request->valor * ($request->desconto / 100));
                // valor do desconto
                $valor_desconto = ($request->valor * ($request->desconto / 100));
            }

            if ($escola->desconto_percentagem == "N") {
                // valor a pagar depois do desconto
                $valor_aplicado_desconto = $request->valor - $request->desconto;
                // valor do desconto
                $valor_desconto = $request->desconto;
            }

            if ($forma_pagamento->sigla_tipo_pagamento == "OU") {
                if (($request->valor_entregue + $request->valor_entregue_multicaixa + $request->saldo_a_descontar_do_estudante_id) > $valor_aplicado_desconto * $request->quantidade) {
                    return response()->json([
                        'status' => 300,
                        'message' => "Por estares a fazer um pagamento DUPLO, a valor entregue em CASH  e TPA devem corresponder o valor total do serviço a Pagar!",
                    ]);
                }
            }

            if (($request->valor_entregue + $request->valor_entregue_multicaixa + $request->saldo_a_descontar_do_estudante_id) < $valor_aplicado_desconto * $request->quantidade) {
                return response()->json([
                    'status' => 300,
                    'message' => "O valor Entregue para o pagamento deste serviço é insuficiente O total seria " . number_format($valor_aplicado_desconto * $request->quantidade, 2, ',', '.'),
                ]);
            }

            if ($validate->fails()) {
                return response()->json([
                    'status' => 400,
                    'errors' => $validate->messages(),
                ]);
            } else {

                $cartao_estudantil = CartaoEstudante::where('status', 'processo')
                    ->where('estudantes_id', $estudantes->id)
                    ->where('ano_lectivos_id', $request->ano_lectivos_id)
                    ->get();

                if ($escola->processo_pagamento_servico == "Secretaria") {
                    if ($request->documento == "FP") {
                        $retificado = 'N';
                        $convertido_factura = 'N';
                        $factura_divida = 'N';
                        $anulado = 'N';
                        $status = "Pendente";

                        $texto_hash_c = true;
                        $hash_c = true;
                    }

                    if ($request->documento == "FT") {
                        $retificado = 'N';
                        $convertido_factura = 'N';
                        $factura_divida = 'Y';
                        $anulado = 'N';
                        $status = "Pendente";

                        $texto_hash_c = true;
                        $hash_c = true;
                    }

                    if ($request->documento == "FR") {
                        $retificado = 'N';
                        $convertido_factura = 'N';
                        $factura_divida = 'N';
                        $anulado = 'N';
                        $status = "Confirmado";

                        $texto_hash_c = true;
                        $hash_c = true;
                    }
                }

                if ($escola->processo_pagamento_servico == "Financeira") {
                    if ($request->documento == "FT") {
                        $retificado = 'N';
                        $convertido_factura = 'N';
                        $factura_divida = 'Y';
                        $anulado = 'N';
                        $status = "Pendente";
                        $texto_hash_c = false;
                        $hash_c = false;
                    } else {
                        $retificado = 'N';
                        $convertido_factura = 'N';
                        $factura_divida = 'N';
                        $anulado = 'N';
                        $status = "Pendente";

                        $texto_hash_c = false;
                        $hash_c = false;
                    }
                }

                $items = DetalhesPagamentoPropina::selectRaw('
                    SUM(multa) as total_multa,
                    SUM(preco) as total_preco,
                    SUM(total_pagar) as total_a_pagar,
                    SUM(quantidade) as total_quantidade,
                    SUM(desconto_valor) as total_desconto_valor,
                    SUM(valor_incidencia) as total_incidencia,
                    SUM(valor_iva) as total_iva
                ')->where('status', 'processo')
                    ->where('funcionarios_id', Auth::user()->id)
                    ->where('model_id', $estudantes->id)
                    ->first();

                $timestemps = DetalhesPagamentoPropina::where('status', 'processo')->where('funcionarios_id', Auth::user()->id)
                    ->where('model_id', $estudantes->id)
                    ->get();
                    

                $servicosDiferentes = DetalhesPagamentoPropina::where('status', 'processo')
                    ->where('funcionarios_id', Auth::user()->id)
                    ->where('model_id', $estudantes->id)
                    ->distinct()
                    ->count('servicos_id');

                if ($servicosDiferentes > 1) {
                    // Existem dois ou mais servico_id diferentes.
                    $servico = Servico::where([
                        ['shcools_id', $this->escolarLogada()],
                        ['servico', "Diversos"],
                    ])
                        ->join('tb_taxas', 'tb_servicos.taxa_id', '=', 'tb_taxas.id')->select('tb_servicos.id', 'taxa', 'contas', 'tb_servicos.servico', 'tb_servicos.contas')
                        ->first();
                } else {
                    // Existe apenas um ou nenhum servico_id.
                    $servico = Servico::join('tb_taxas', 'tb_servicos.taxa_id', '=', 'tb_taxas.id')->select('tb_servicos.id', 'taxa', 'contas', 'tb_servicos.servico', 'tb_servicos.contas')->findOrFail($request->servico);
                }

                $ano_lectivo_activo = AnoLectivo::findOrFail($this->anolectivoActivo());

                $contarFactura = Pagamento::where('tipo_factura', '=', $request->documento)
                    ->where('factura_ano', '=', $ano_lectivo_activo->serie ?? date("Y"))
                    ->where('shcools_id', '=', $this->escolarLogada())
                    ->whereIn('status', $request->documento ==  'FR' ? ['Confirmado'] : ['Confirmado', 'Pendente'])
                    ->count();

                $ultimoRecibo = Pagamento::where('tipo_factura', '=', $request->documento)
                    ->where('factura_ano', '=', $ano_lectivo_activo->serie ?? date("Y"))
                    ->where('shcools_id', '=', $this->escolarLogada())
                    ->whereIn('status', $request->documento ==  'FR' ? ['Confirmado'] : ['Confirmado', 'Pendente'])
                    ->latest()
                    ->first();

                if (!$ultimoRecibo) {
                    $hashAnterior = "";
                } else {
                    $hashAnterior = $ultimoRecibo->hash;
                }


                //Manipulação de datas: data actual
                $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

                $numeroFactura = $contarFactura + 1;

                $rsa = new RSA(); //Algoritimo RSA

                $privatekey = $this->pegarChavePrivada();
                $publickey = $this->pegarChavePublica();

                // Lendo a private key
                $rsa->loadKey($privatekey);

                $codigo_designacao_factura = "EAV";

                // Texto que deverá ser assinado com a assinatura RSA::SIGNATURE_PKCS1, e o Texto estará mais ou menos assim após as
                // Concatenações com os dados preliminares da factura: 2020-09-14;2020-09-14T20:34:09;FP PAT2020/1;457411.2238438;

                $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ';' . "{$request->documento} {$codigo_designacao_factura}{$ano_lectivo_activo->serie}/{$numeroFactura}" . ';' . number_format($items->total_a_pagar, 2, ".", "") . ';' . $hashAnterior;

                // HASH
                $hash = 'sha1'; // Tipo de Hash
                $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima

                //ASSINATURA
                $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
                $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)

                // Lendo a public key
                $rsa->loadKey($publickey);

                $valor_extenso = $this->valor_por_extenso($items->total_a_pagar);

                if ($forma_pagamento->sigla_tipo_pagamento == "NU") {
                    $valor_cash = $items->total_a_pagar;
                    $valor_multicaixa = 0;
                } else if ($forma_pagamento->sigla_tipo_pagamento == "MB") {
                    $valor_cash = 0;
                    $valor_multicaixa = $items->total_a_pagar;
                } else if ($forma_pagamento->sigla_tipo_pagamento == "OU") {
                    $valor_cash = $request->valor_entregue;
                    $valor_multicaixa = $request->valor_entregue_multicaixa;
                } else if ($forma_pagamento->sigla_tipo_pagamento == "TT") {
                    $valor_cash = 0;
                    $valor_multicaixa = $request->valor_entregue;
                } else if ($forma_pagamento->sigla_tipo_pagamento == "DD") {
                    $valor_cash = 0;
                    $valor_multicaixa = $request->valor_entregue;
                }

                $saldo_aguardado = ($request->valor_entregue + $request->valor_entregue_multicaixa) - ($items->total_a_pagar);

                $codigo_referencia = time();

                $createPagamento = Pagamento::create([
                    "pago_at" =>  $servico->servico,
                    "quantidade" => $items->total_quantidade ?? 1,
                    "servicos_id" => $servico->id,
                    'tipo_servico_detalhe' => 'mensal',
                    "status" => $status,
                    "caixa_at" => 'receita',
                    "ficha" => $codigo_referencia,
                    "referencia" => $codigo_referencia,

                    "valor" => $items->total_preco / $items->total_quantidade, //$request->input('valor'),
                    "valor2" =>  $items->total_a_pagar, //$request->input('valor'),

                    "troco" => $request->salvar_troco == 1 ? 0 : ($request->valor_entregue + $request->valor_entregue_multicaixa) - ($items->total_a_pagar),
                    "valor_entregue" => $request->valor_entregue_multicaixa + $request->valor_entregue,
                    "desconto" => $items->total_desconto_valor,
                    "multa" => $request->multa,
                    "banco_id" => $request->banco_id,
                    "caixa_id" => $caixa ? $caixa->id : "",

                    "numero_transacao" => $request->input('numero_transicao'),
                    // "data_at" => $this->data_sistema(),
                    "mensal" => $this->mesecompleto(),
                    "funcionarios_id" => Auth::user()->id,
                    "estudantes_id" => $estudantes->id,
                    "model" => 'estudante',
                    "ano_lectivos_id" => $this->anolectivoActivo(),
                    "numero_factura" => $numeroFactura,
                    "tipo_factura" => $request->documento,
                    "next_factura" => "{$request->documento} {$codigo_designacao_factura}{$ano_lectivo_activo->serie}/{$numeroFactura}",
                    "shcools_id" => $this->escolarLogada(),

                    'retificado' => $retificado,
                    'convertido_factura' => $convertido_factura,
                    'factura_divida' => $factura_divida,
                    'anulado' => $anulado,

                    'data_at' => $request->data_pagamento,
                    'data_vencimento' => $request->data_pagamento,
                    'data_disponibilizacao' => $request->data_pagamento,

                    "tipo_pagamento" => $forma_pagamento->sigla_tipo_pagamento,
                    "pagamento_id" => $forma_pagamento->id,
                    "valor_extenso" => $valor_extenso,
                    'factura_ano' => $ano_lectivo_activo->serie ?? date("Y"),
                    'prazo' => 0,
                    'total_iva' => $items->total_iva,
                    'valor_cash' => $valor_cash,
                    'valor_multicaixa' => $valor_multicaixa,
                    'texto_hash' => $texto_hash_c == false ? NULL : $plaintext,
                    'hash' => $hash_c == false ? NULL : base64_encode($signaturePlaintext),
                    'nif_cliente' => $estudantes->bilheite,
                    'total_incidencia' => $items->total_incidencia,
                    'conta_corrente_cliente' => $estudantes->conta_corrente,
                ]);

                if ($timestemps) {
                    foreach ($timestemps as $item) {
                        $upd = DetalhesPagamentoPropina::findOrFail($item->id);
                        $upd->status = 'Pago';
                        $upd->code = $codigo_referencia;
                        $upd->pagamentos_id = $createPagamento->id;
                        $upd->update();
                    }
                }

                if ($cartao_estudantil) {
                    foreach ($cartao_estudantil as $cartao) {
                        $upd = CartaoEstudante::findOrFail($cartao->id);
                        if ($upd->mes_id == "M") {
                            $upd->status = 'Pago';
                        }
                        if ($upd->mes_id == "U") {
                            $upd->status = 'Nao Pago';
                        }
                        // $upd->cobertura = 'Y';
                        $upd->update();
                    }
                }

                if ($escola->processo_pagamento_servico == "Secretaria") {

                    $updateCaixaAberto = MovimentoCaixa::findOrFail($caixaAberto);

                    if ($forma_pagamento->sigla_tipo_pagamento == "NU") {
                        $updateCaixaAberto->valor_cache = $updateCaixaAberto->valor_cache +  $items->total_a_pagar;
                    }

                    if ($forma_pagamento->sigla_tipo_pagamento == "MB") {
                        $updateCaixaAberto->valor_tpa = $updateCaixaAberto->valor_tpa +  $items->total_a_pagar;
                    }

                    if ($forma_pagamento->sigla_tipo_pagamento == "OU") {
                        $updateCaixaAberto->valor_cache = $request->valor_entregue;
                        $updateCaixaAberto->valor_tpa = $request->valor_entregue_multicaixa;
                    }

                    if ($forma_pagamento->sigla_tipo_pagamento == "TT") {
                        $updateCaixaAberto->valor_transferencia = $request->valor_entregue;
                    }

                    if ($forma_pagamento->sigla_tipo_pagamento == "DD") {
                        $updateCaixaAberto->valor_depositado = $request->valor_entregue;
                    }

                    $updateCaixaAberto->valor_fecha = $updateCaixaAberto->valor_fecha +  $items->total_a_pagar;
                    $updateCaixaAberto->qtd_itens = $updateCaixaAberto->qtd_itens + $items->total_quantidade;
                    $updateCaixaAberto->update();
                }

                if ($request->pagamento_com_reserva_saldo) {
                    $estudantes->saldo_anterior = $estudantes->saldo;
                    $estudantes->saldo = $estudantes->saldo - $request->saldo_a_descontar_do_estudante_id;
                    $estudantes->update();
                }

                // Actualizar o saldo do estudante
                if ($request->salvar_troco == 1) {
                    $estudantes->saldo_anterior = $estudantes->saldo;
                    $estudantes->saldo = $estudantes->saldo + $saldo_aguardado;
                    $estudantes->update();
                }
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
            'message' => 'Dados salvos com sucesso!',
            "ficha" => $createPagamento->ficha, //->get();
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }


    public function financeiroConcluirPagamentoCreate(Request $request, $id)
    {

        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário
        $user = auth()->user();

        if (!$user->can('read: pagamento') && !$user->can('create: pagamento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $caixa = Caixa::where('status', '=', "activo")->where('shcools_id', '=', $this->escolarLogada())->where('usuario_id', '=', Auth::user()->id)->first();

        if (!$caixa) {
            Alert::error('Atenção', 'Deves primeiramente fazer abertura do caixa, antes de fazer qualquer pagamento!');
            return redirect()->back();
        }

        $caixaAberto = MovimentoCaixa::where([
            ['caixa_id', $caixa->id],
            ['usuario_id', Auth::user()->id],
            ['status', "aberto"],
        ])->first()->id;

        if (!$caixaAberto) {
            Alert::error('Atenção', 'Deves primeiramente fazer abertura do caixa, antes de fazer qualquer pagamento!');
            return redirect()->back();
        }

        try {
            DB::beginTransaction();


            // Realizar operações de banco de dados aqui
            $pagamento = Pagamento::findOrFail(Crypt::decrypt($id));

            $ano_lectivo_activo = AnoLectivo::findOrFail($this->anolectivoActivo());

            $contarFactura = Pagamento::where('tipo_factura', '=', $pagamento->tipo_factura)
                ->where('factura_ano', '=', $ano_lectivo_activo->serie ?? date("Y"))
                ->where('shcools_id', '=', $this->escolarLogada())
                ->where('status', 'Confirmado')
                ->count();

            // dd($contarFactura, $pagamento->tipo_factura, $ano_lectivo_activo->serie);

            $ultimoRecibo = Pagamento::where('tipo_factura', '=', $pagamento->tipo_factura)
                ->where('factura_ano', '=', $ano_lectivo_activo->serie ?? date("Y"))
                ->where('shcools_id', '=', $this->escolarLogada())
                ->where('status', 'Confirmado')
                ->latest()
                ->first();

            if (!$ultimoRecibo) {
                $hashAnterior = "";
            } else {
                $hashAnterior = $ultimoRecibo->hash;
            }


            //Manipulação de datas: data actual
            $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

            $numeroFactura = $contarFactura + 1;

            $rsa = new RSA(); //Algoritimo RSA

            $privatekey = $this->pegarChavePrivada();
            $publickey = $this->pegarChavePublica();

            // Lendo a private key
            $rsa->loadKey($privatekey);

            $codigo_designacao_factura = "EAV";

            // Texto que deverá ser assinado com a assinatura RSA::SIGNATURE_PKCS1, e o Texto estará mais ou menos assim após as
            // Concatenações com os dados preliminares da factura: 2020-09-14;2020-09-14T20:34:09;FP PAT2020/1;457411.2238438;

            $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ';' . "{$pagamento->tipo_factura} {$codigo_designacao_factura}{$ano_lectivo_activo->serie}/{$numeroFactura}" . ';' . number_format($pagamento->valor2, 2, ".", "") . ';' . $hashAnterior;

            // HASH
            $hash = 'sha1'; // Tipo de Hash
            $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima

            //ASSINATURA
            $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
            $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)

            // Lendo a public key
            $rsa->loadKey($publickey);

            $pagamento->status = "Confirmado";
            $pagamento->texto_hash = $plaintext;
            $pagamento->hash = base64_encode($signaturePlaintext);
            $pagamento->factura_ano = $ano_lectivo_activo->serie ?? date("Y");
            $pagamento->next_factura = "{$pagamento->tipo_factura} {$codigo_designacao_factura}{$ano_lectivo_activo->serie}/{$numeroFactura}";

            $pagamento->created_at = date("Y-m-d H:i:s");
            $pagamento->numero_factura = $numeroFactura;

            $pagamento->update();

            $updateCaixaAberto = MovimentoCaixa::findOrFail($caixaAberto);

            if ($pagamento->tipo_pagamento == "NU") {
                $updateCaixaAberto->valor_cache = $updateCaixaAberto->valor_cache + $pagamento->valor_cash;
            }

            if ($pagamento->tipo_pagamento == "MB") {
                $updateCaixaAberto->valor_tpa = $updateCaixaAberto->valor_tpa + $pagamento->valor_multicaixa;
            }

            if ($pagamento->tipo_pagamento == "OU") {
                $updateCaixaAberto->valor_cache = $updateCaixaAberto->valor_cache + $pagamento->valor_cash;
                $updateCaixaAberto->valor_tpa = $updateCaixaAberto->valor_tpa + $pagamento->valor_multicaixa;
            }

            if ($pagamento->tipo_pagamento == "TT") {
                $updateCaixaAberto->valor_transferencia = ($pagamento->valor_cash + $pagamento->valor_multicaixa);
            }

            if ($pagamento->tipo_pagamento == "DD") {
                $updateCaixaAberto->valor_depositado = ($pagamento->valor_cash + $pagamento->valor_multicaixa);
            }

            $updateCaixaAberto->valor_fecha = $updateCaixaAberto->valor_fecha + ($pagamento->valor_cash + $pagamento->valor_multicaixa);
            $updateCaixaAberto->qtd_itens = $updateCaixaAberto->qtd_itens + $pagamento->quantidade;
            $updateCaixaAberto->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        Alert::success('Bom trabalho', 'Pagamento concluído com sucesso!');
        return redirect()->route('comprovativo-factura-recibo', $pagamento->ficha);
    }


    public function estudantesEfectuarPagamentoEspeciais(Request $request)
    {

        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário
        $user = auth()->user();

        if (!$user->can('create: pagamento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $caixa = Caixa::where('status', '=', "activo")->where('shcools_id', '=', $this->escolarLogada())->where('usuario_id', '=', Auth::user()->id)->first();

        if (!$caixa) {
            Alert::error('Atenção', 'Deves primeiramente fazer abertura do caixa, antes de fazer qualquer pagamento!');
            return redirect()->back();
        }

        $matricula = null;
        $turma = null;
        $servico = null;

        if ($request->search) {

            $matricula = Matricula::with(['estudante', 'classe', 'turno', 'curso'])
                ->where('ficha', $request->search)
                ->where('status_matricula_pagamento', 'Nao Pago')
                ->where('status_matricula', 'nao_confirmado')
                ->where('shcools_id', $this->escolarLogada())
                // ->where('ano_lectivos_id', $this->anolectivoActivo())
                ->first();

            if ($matricula) {
                if ($matricula->tipo == "confirmacao") {
                    $servico = Servico::where('shcools_id', $this->escolarLogada())->where('servico', 'Confirmação')->first();
                }
                if ($matricula->tipo == "matricula" || $matricula->tipo == "candidatura" || $matricula->tipo == "inscricao") {
                    $servico = Servico::where('shcools_id', $this->escolarLogada())->where('servico', 'Matricula')->first();
                }

                $turma = Turma::where('classes_id', $matricula->classes_id)
                    ->where('shcools_id', $this->escolarLogada())
                    ->where('ano_lectivos_id', $matricula->ano_lectivos_id)
                    ->where('cursos_id', $matricula->cursos_id)
                    ->first();

                if (!$turma) {
                    Alert::warning('Informação', "Tem um irregularidade no acto da matrícula deste estudante, por favor, verifique os dados academicos associados!");
                    return redirect()->back();
                }
            }

            $matricula_pagamento = Matricula::with(['estudante', 'classe', 'turno', 'curso'])
                ->where('ficha', $request->search)
                ->where('status_matricula_pagamento', 'Pago')
                ->where('status_matricula', 'confirmado')
                ->where('shcools_id', $this->escolarLogada())
                ->where('ano_lectivos_id', $matricula->ano_lectivos_id ?? $this->anolectivoActivo())
                ->first();

            if ($matricula_pagamento) {
                Alert::warning('Informação', "O Este estudante já fez o pagamento da sua matrícula/confirmação");
                return redirect()->back();
            }
        }

        $bancos = Banco::where('shcools_id', $this->escolarLogada())->get();
        $caixas = Caixa::where('shcools_id', $this->escolarLogada())->whereIn('id', [$caixa->id])->get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "Pesquisar estudantes para pagamentos",
            'matricula' =>  $matricula,
            'turma' =>  $turma,
            "bancos" => $bancos,
            "caixas" => $caixas,
            'servico' =>  $servico,
            "descricao" => env('APP_NAME'),

            "usuario" => User::findOrFail(Auth::user()->id),

            "formas_pagamento" => FormaPagamento::where('tipo_credito', 2)->get(),
            "requests" => $request->all('search'),
        ];

        return view('admin.financeiros.efectuar-pagamento-especial', $headers);
    }

    public function estudantesEfectuarPagamentoEspeciaisStore(Request $request)
    {

        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário
        $user = auth()->user();

        if (!$user->can('create: pagamento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->findOrFail($this->escolarLogada());
        $matricula = Matricula::with('estudante')->findOrFail($request->matricula_id);

        if ($matricula->tipo == "confirmacao") {
            $ano_lectivo = AnoLectivo::findOrFail($matricula->ano_lectivos_id);
        } else {
            $ano_lectivo = AnoLectivo::findOrFail($this->anolectivoActivo());
        }

        $caixa = NULL;

        if ($escola->categoria == "Privado") {

            if ($request->input('tipo_matricula') == 'confirmacao') {
                $request->validate([
                    'valor_a_pagar' => 'required',
                    'tipo_pagamento' => 'required',
                ]);
                $request->valor_entregue = $request->valor_a_pagar;
            } else {
                $request->validate([
                    'valor_a_pagar' => 'required',
                    'valor_entregue' => 'required',
                    'tipo_pagamento' => 'required',
                ]);
            }
        } else {
            $request->valor_entregue = 0;
        }

        if ($escola->categoria == "Privado") {
            if ($escola->modulo != "Basico") {
                if (!filter_var($request->valor_entregue, FILTER_VALIDATE_INT) and !!filter_var($request->valor_entregue, FILTER_VALIDATE_INT)) {
                    Alert::warning('Informação', "O Valor Invalido");
                    return redirect()->back();
                }

                if ($request->valor_entregue < $request->valor_a_pagar) {
                    Alert::warning('Informação', "O NUMERARIO Entregue para o pagamento deste serviço é insuficiente");
                    return redirect()->back();
                }
            }
        }

        // caixa
        if ($escola->categoria == "Privado") {
            if ($escola->modulo != "Basico") {

                $caixa = Caixa::where('status', "activo")
                    ->where('shcools_id', $this->escolarLogada())
                    ->where('usuario_id', Auth::user()->id)
                    ->first();

                if (!$caixa) {
                    Alert::warning('Informação', "Não há nenhum caixa aberto no momento. Por favor, abra um caixa para continuar o processo.");
                    return redirect()->back();
                }

                $caixaAberto = MovimentoCaixa::where('caixa_id', $caixa->id)
                    ->where('usuario_id', Auth::user()->id)
                    ->where('status', "aberto")
                    ->first()->id;

                if (!$caixaAberto) {
                    Alert::warning('Informação', "Não há nenhum caixa aberto no momento. Por favor, abra um caixa para continuar o processo.");
                    return redirect()->back();
                }
            }
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $servico_operacional = Servico::join('tb_taxas', 'tb_servicos.taxa_id', '=', 'tb_taxas.id')
                ->select('tb_servicos.id', 'contas', 'taxa', 'tb_servicos.servico', 'tb_servicos.contas')
                ->findOrFail($request->servicos_id);


            $estudante = Estudante::findOrFail($matricula->estudante->id);

            $forma_pagamento = FormaPagamento::where('sigla_tipo_pagamento', $request->tipo_pagamento)->first();

            $turma = Turma::where('classes_id', $matricula->classes_id)
                ->where('turnos_id', $matricula->turnos_id)
                ->where('cursos_id', $matricula->cursos_id)
                ->where('ano_lectivos_id', $matricula->ano_lectivos_id)
                ->first();

            // existe a turma
            if ($turma) {
                ##TODOS
                $anoLectivoAnterior = AnoLectivo::find($this->anolectivoAnterior($ano_lectivo->id));

                $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

                if ($escola->ensino->nome == "Ensino Superior") {
                    $trimestres = ControlePeriodico::where('ensino_status', '2')->get();
                } else {
                    $trimestres = ControlePeriodico::where('ensino_status', '1')->get();
                }

                if ($escola->ensino->nome == "Ensino Superior") {
                    // Aqui vai se criar as pautas das classes anteriores caso ele não estudava neste instituição
                    if ($anoLectivoAnterior) {
                        // tambem temos que verificar as notas antiores do estudante caso ele nunca tinha sido estudante provavelmwnte tem notas passada que vão precisar ser inserido na ano passado
                        $_classe = Classe::findOrFail($turma->classes_id);
                        // precisamos verificar se tem as notas da 10 classe caso não vamos preenchar ou criar pauta vazias
                        if (strtolower($_classe->classes) == "2º ano") {
                            $classes_1_ano = Classe::where('classes', '1º ano')->first();
                            $this->inserir_turmas_pautas_anterior($estudante->id, $classes_1_ano->id, $matricula->cursos_id, $this->anolectivoAnterior($ano_lectivo->id), $trimestres);
                        }
                    }
                } else {
                    // Aqui vai se criar as pautas das classes anteriores caso ele não estudava neste instituição
                    if ($anoLectivoAnterior) {
                        // tambem temos que verificar as notas antiores do estudante caso ele nunca tinha sido estudante provavelmwnte tem notas passada que vão precisar ser inserido na ano passado
                        $_classe = Classe::findOrFail($turma->classes_id);

                        // precisamos verificar se tem as notas da 10 classe caso não vamos preenchar ou criar pauta vazias
                        if (strtolower($_classe->classes) == "11ª classe") {
                            $classes_10 = Classe::where('classes', '10ª classe')->first();
                            $this->inserir_turmas_pautas_anterior($estudante->id, $classes_10->id, $matricula->cursos_id, $this->anolectivoAnterior($ano_lectivo->id), $trimestres);
                        }

                        if (strtolower($_classe->classes) == "12ª classe") {
                            $classes_11 = Classe::where('classes', '11ª classe')->first();
                            $this->inserir_turmas_pautas_anterior($estudante->id, $classes_11->id, $matricula->cursos_id, $this->anolectivoAnterior($ano_lectivo->id), $trimestres);
                            $anoLectivoAnteAnterior = AnoLectivo::find($this->anolectivoAntesAnterior($ano_lectivo->id));

                            if ($anoLectivoAnteAnterior) {
                                $classes_10 = Classe::where('classes', '10ª classe')->first();
                                $this->inserir_turmas_pautas_anterior($estudante->id, $classes_10->id, $matricula->cursos_id, $this->anolectivoAntesAnterior($ano_lectivo->id), $trimestres);
                            }
                        }

                        // ENSINO SECUNDARIO
                        if (strtolower($_classe->classes) == "8ª classe") {
                            $classes_7 = Classe::where('classes', '7ª classe')->first();
                            $this->inserir_turmas_pautas_anterior($estudante->id, $classes_7->id, $matricula->cursos_id, $this->anolectivoAnterior($ano_lectivo->id), $trimestres);
                        }

                        if (strtolower($_classe->classes) == "9ª classe") {
                            $classes_8 = Classe::where('classes', '8ª classe')->first();
                            $this->inserir_turmas_pautas_anterior($estudante->id, $classes_8->id, $matricula->cursos_id, $this->anolectivoAnterior($ano_lectivo->id), $trimestres);

                            $anoLectivoAnteAnterior = AnoLectivo::find($this->anolectivoAntesAnterior($ano_lectivo->id));
                            if ($anoLectivoAnteAnterior) {
                                $classes_7 = Classe::where('classes', '7ª classe')->first();
                                $this->inserir_turmas_pautas_anterior($estudante->id, $classes_7->id, $matricula->cursos_id, $this->anolectivoAntesAnterior($ano_lectivo->id), $trimestres);
                            }
                        }
                    }
                }

                // criar mini pautas
                $this->inserir_turmas_pautas_anterior($estudante->id, $turma->classes_id, $matricula->cursos_id, $ano_lectivo->id, $trimestres, $turma->id);

                $servicos = ServicoTurma::where("turmas_id", $turma->id)
                    ->where("model", "turmas")
                    ->where("ano_lectivos_id", $ano_lectivo->id)
                    ->with(["servico"])
                    ->get();

                ///////////////////////////////////////////////////////////////
                $condicao_estudante = $request->condicao_estudante;

                if ($servicos) {
                    foreach ($servicos as $servico) {
                        if ($servico->pagamento == 'mensal') {
                            if ($condicao_estudante == "Isento" and $servico->servico->servico == "Propinas") {

                                // verificar se o estudante isento ja tem este servico para n\ao lhe permitir ter esse servico duas vezes
                                $verificarServicosEstudante = CartaoEstudante::where('estudantes_id', $estudante->id)
                                    ->where('servicos_id', $servico->servicos_id)
                                    ->where('ano_lectivos_id', $ano_lectivo->id)
                                    ->first();

                                if ($escola->ensino->nome == "Ensino Superior") {
                                    $controle_periodico = 7;
                                } else {
                                    $controle_periodico = 4;
                                }

                                if (!$verificarServicosEstudante) {
                                    CartaoEstudante::create([
                                        "mes_id" => "M",
                                        "estudantes_id" => $estudante->id,
                                        "servicos_id" => $servico->servicos_id,
                                        "preco_unitario" => $servico->preco,
                                        "data_at" => $servico->data_inicio,
                                        "data_exp" => $servico->data_final,
                                        "multa" => 0,
                                        "month_number" => date("m", strtotime($servico->data_inicio)),
                                        "month_name" =>  date("M", strtotime($servico->data_inicio)),
                                        "controle_periodico_id" => $controle_periodico,
                                        "ano_lectivos_id" => $ano_lectivo->id,
                                        "status" => 'Isento',
                                    ]);
                                }
                            } else {
                                // meses
                                $meses = $this->cartao_estudantes_meses(
                                    $ano_lectivo->inicio,
                                    $servico->intervalo_pagamento_inicio,
                                    $servico->intervalo_pagamento_final
                                );

                                foreach ($meses as $mes) {
                                    $verificarServicosEstudante = CartaoEstudante::where("estudantes_id", $estudante->id)
                                        ->where("servicos_id", $servico->servicos_id)
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
                                            "servicos_id" => $servico->servicos_id,
                                            "preco_unitario" => $servico->preco,

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
                        } else
                        if ($servico->pagamento == 'unico') {
                            $verificarServicosEstudante = CartaoEstudante::where('estudantes_id', $estudante->id)
                                ->where('servicos_id', $servico->servicos_id)
                                ->where('ano_lectivos_id', $ano_lectivo->id)
                                ->first();

                            if (!$verificarServicosEstudante) {
                                if ($servico->servico == "Matricula") {
                                    $status = 'Pago';
                                }
                                if ($servico->servico == "Confirmação") {
                                    $status = 'Pago';
                                } else {
                                    $status = 'Nao Pago';
                                }

                                if ($escola->ensino->nome == "Ensino Superior") {
                                    $controle_periodico = $this->mes_periodico(date("M", strtotime($servico->data_inicio)), "U", "universidade");
                                } else {
                                    $controle_periodico = $this->mes_periodico(date("M", strtotime($servico->data_inicio)), "U", "geral");
                                }

                                CartaoEstudante::create([
                                    "mes_id" => "U",
                                    "estudantes_id" => $estudante->id,
                                    "servicos_id" => $servico->servicos_id,
                                    "preco_unitario" => $servico->preco,
                                    "data_at" => $servico->data_inicio,
                                    "data_exp" => $servico->data_final,
                                    "month_number" => date("m", strtotime($servico->data_inicio)),
                                    "month_name" => date("M", strtotime($servico->data_inicio)),
                                    "status" => $status,
                                    "status_2" => 'Normal',
                                    "controle_periodico_id" => $controle_periodico,
                                    "ano_lectivos_id" => $ano_lectivo->id,
                                ]);
                            }
                        }
                    }
                }
            }

            $valor_multicaixa = 0;
            $valor_cash = 0;

            if ($forma_pagamento->sigla_tipo_pagamento == "NU") {
                $valor_cash = $request->valor_a_pagar;
                $valor_multicaixa = 0;
            } else if ($forma_pagamento->sigla_tipo_pagamento == "MB") {
                $valor_cash = 0;
                $valor_multicaixa = $request->valor_a_pagar;
            } else if ($forma_pagamento->sigla_tipo_pagamento == "TT") {
                $valor_cash = 0;
                $valor_multicaixa = $request->valor_a_pagar;
            } else if ($forma_pagamento->sigla_tipo_pagamento == "DD") {
                $valor_cash = 0;
                $valor_multicaixa = $request->valor_a_pagar;
            } else if ($forma_pagamento->sigla_tipo_pagamento == "OU") {
                $valor_cash = 0;
                $valor_multicaixa = $request->valor_a_pagar;
            }

            $ano_lectivo_activo = AnoLectivo::findOrFail($this->anolectivoActivo());

            $contarFactura = Pagamento::where('tipo_factura', '=', 'FR')
                ->where('factura_ano', '=', $ano_lectivo_activo->serie ?? date("Y"))
                ->where('shcools_id', '=', $this->escolarLogada())
                ->count();

            $ultimoRecibo = Pagamento::where('tipo_factura', '=', 'FR')
                ->where('factura_ano', '=', $ano_lectivo_activo->serie ?? date("Y"))
                ->where('shcools_id', '=', $this->escolarLogada())
                ->latest()
                ->first();

            if (!$ultimoRecibo) {
                $hashAnterior = "";
            } else {
                $hashAnterior = $ultimoRecibo->hash;
            }

            //Manipulação de datas: data actual
            $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

            // $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

            $numeroFactura = $contarFactura + 1;

            $rsa = new RSA(); //Algoritimo RSA

            $privatekey = $this->pegarChavePrivada();
            $publickey = $this->pegarChavePublica();

            // Lendo a private key
            $rsa->loadKey($privatekey);

            $codigo_designacao_factura = "EAV";

            // Texto que deverá ser assinado com a assinatura RSA::SIGNATURE_PKCS1, e o Texto estará mais ou menos assim após as
            // Concatenações com os dados preliminares da factura: 2020-09-14;2020-09-14T20:34:09;FP PAT2020/1;457411.2238438;

            $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ';' . "FR {$codigo_designacao_factura}{$ano_lectivo_activo->serie}/{$numeroFactura}" . ';' . number_format($request->valor_a_pagar, 2, ".", "") . ';' . $hashAnterior;

            // HASH
            $hash = 'sha1'; // Tipo de Hash
            $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima

            //ASSINATURA
            $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
            $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)

            // Lendo a public key
            $rsa->loadKey($publickey);

            $valor_extenso = $this->valor_por_extenso($request->valor_a_pagar);

            $code = time();

            $createP = Pagamento::create([
                "pago_at" => strtolower($servico_operacional->servico),
                "servicos_id" => $servico_operacional->id,
                "caixa_at" => $servico_operacional->contas,
                "ficha" => $code,
                "status" => "Confirmado",
                "desconto" => 0,
                'tipo_servico_detalhe' => 'unico',
                "valor" => $request->valor_a_pagar,
                "valor2" => $request->valor_a_pagar,
                "multa" => 0,
                "data_at" => $this->data_sistema(),
                "mensal" => $this->mesecompleto(),
                "funcionarios_id" => Auth::user()->id,
                "estudantes_id" => $matricula->estudantes_id,
                'valor_entregue' => $request->valor_entregue,
                'banco_id' => $request->banco_id,
                'caixa_id' => $caixa ? $caixa->id : "",
                "numero_factura" => $numeroFactura,
                'troco' => $request->valor_entregue - $request->valor_a_pagar,
                'data_vencimento' => date("Y-m-d"),
                'data_disponibilizacao' => date("Y-m-d"),
                'factura_ano' => $ano_lectivo_activo->serie ?? date("Y"),
                'prazo' => 0,
                'data_vencimento' => date("Y-m-d"),
                "model" => 'estudante',
                "ano_lectivos_id" => $this->anolectivoActivo(),
                "tipo_factura" =>  'FR',
                "tipo_pagamento" => $forma_pagamento->sigla_tipo_pagamento,
                "pagamento_id" => $forma_pagamento->id,
                'next_factura' => "FR {$codigo_designacao_factura}{$ano_lectivo_activo->serie}/{$numeroFactura}",
                'observacao' => "",
                'referencia' => $code,
                'shcools_id' => $this->escolarLogada(),
                'numero_transacao' => $request->numero_transicao,
                'retificado' => 'N',
                'convertido_factura' => 'N',
                'factura_divida' => 'N',
                'anulado' => 'N',

                'moeda' => 'AOA',
                'valor_extenso' => $valor_extenso,
                'valor_cash' => $valor_cash,
                'valor_multicaixa' => $valor_multicaixa,
                'texto_hash' => $plaintext,
                'hash' => base64_encode($signaturePlaintext),
                'nif_cliente' => $matricula->estudante->bilheite,
                'conta_corrente_cliente' => $matricula->estudante->conta_corrente,
                'total_iva' => 0,
                'total_incidencia' => $request->valor_a_pagar,
                'quantidade' => 1,
            ]);

            // calcudo do total de incidencia
            // ________________ valor total _____________
            $valorBase = $request->valor_a_pagar * 1;
            // calculo do iva
            $valorIva = ($servico_operacional->taxa / 100) * $valorBase;

            $desconto = ($request->valor_a_pagar * 1) * (0 / 100);

            DetalhesPagamentoPropina::create([
                'total_pagar' => $valorBase + $valorIva,
                'code' => $code,
                'mes_id' => "NULL",
                'valor_incidencia' => $valorBase,
                'desconto' => 0,
                'desconto_valor' => $desconto,
                'valor_iva' => 0,
                'taxa_id' => $servico_operacional->taxa,
                'mes' => date("M"),
                'model_id' => $matricula->estudantes_id,
                'multa' => 0,
                'quantidade' => 1,
                'funcionarios_id' => Auth::user()->id,
                'preco' => $valorBase,
                'status' => 'Pago',
                'servicos_id' => $servico_operacional->id,
                'date_att' => $this->data_sistema(),
                'ano_lectivos_id' => $this->anolectivoActivo(),
                'shcools_id' => $this->escolarLogada(),
                'pagamentos_id' => $createP->id,
            ]);

            if ($escola->modulo != "Basico") {
                $updateCaixaAberto = MovimentoCaixa::findOrFail($caixaAberto);
                if ($forma_pagamento->sigla_tipo_pagamento == "NU") {
                    $updateCaixaAberto->valor_cache = $updateCaixaAberto->valor_cache + $request->valor_a_pagar;
                }
                if ($forma_pagamento->sigla_tipo_pagamento == "MB") {
                    $updateCaixaAberto->valor_tpa = $updateCaixaAberto->valor_tpa + $request->valor_a_pagar;
                }

                if ($forma_pagamento->sigla_tipo_pagamento == "OU") {
                    $updateCaixaAberto->valor_cache = $updateCaixaAberto->valor_cache  + $request->valor;
                    $updateCaixaAberto->valor_tpa = 0;
                }

                if ($forma_pagamento->sigla_tipo_pagamento == "TT") {
                    $updateCaixaAberto->valor_transferencia = $updateCaixaAberto->valor_transferencia + $request->valor;
                }

                if ($forma_pagamento->sigla_tipo_pagamento == "DD") {
                    $updateCaixaAberto->valor_depositado = $updateCaixaAberto->valor_depositado + $request->valor;
                }

                $updateCaixaAberto->valor_fecha = $updateCaixaAberto->valor_fecha + $request->valor_a_pagar;
                $updateCaixaAberto->qtd_itens = $updateCaixaAberto->qtd_itens + 1;
                $updateCaixaAberto->update();
            }

            $matricula->status_matricula = 'confirmado';
            $matricula->status_matricula_pagamento = 'Pago';
            $matricula->update();

            $estudante->registro = "confirmado";
            $estudante->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        Alert::success('Bom Trabalho', 'Dados salvos com sucesso!');
        return redirect()->route('comprovativo-factura-recibo', $createP->ficha);
    }

    public function financeiroGestaoDividas(Request $request)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $user = auth()->user();

        if (!$user->can('read: dividas')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        // $this->actualizarCartao();

        $cursos_id = $request->cursos_id;
        $classes_id = $request->classes_id;
        $turnos_id = $request->turnos_id;
        $input_estudante = $request->input_estudante;

        $cartoes = CartaoEstudante::with(['estudante.matricula', 'servico'])
            // Filtrar pelo ID do serviço
            ->when($request->servico, function ($query, $servico) {
                $query->where('servicos_id', $servico);
            })
            // // Filtrar pelo mês
            ->when($request->mes, function ($query, $mes) {
                $query->whereIn('month_name', $mes);
            })
            // // Filtrar pela condição do status
            ->when($request->condicao, function ($query, $status) {
                $query->where('status', $status);
            })
            // // Verifica o estudante e filtra pelo bilhete e escola logada
            ->whereHas('estudante', function ($query) use ($input_estudante) {
                $query->when($input_estudante, function ($query, $bilhete) {
                    $query->where('bilheite', $bilhete);
                });
                $query->where('shcools_id', $this->escolarLogada());
            })
            // // Verifica e filtra os dados da matrícula
            ->whereHas('estudante.matricula', function ($query) use ($cursos_id, $classes_id, $turnos_id) {
                //dd($cursos_id, $classes_id, $turnos_id);
                $query->when($cursos_id, function ($query, $curso) {
                    $query->where('cursos_id', $curso);
                });
                $query->when($classes_id, function ($query, $classe) {
                    $query->where('classes_id', $classe);
                });
                $query->when($turnos_id, function ($query, $turno) {
                    $query->where('turnos_id', $turno);
                });
            })
            // Filtra pelo ano letivo, com valor padrão se não fornecido
            ->where('ano_lectivos_id', $request->ano_lectivos_id ?? $this->anolectivoActivo())
            ->get();

        // dd(count($cartoes));

        $classes = AnoLectivoClasse::where('ano_lectivos_id', $request->ano_lectivos_id ?? $this->anolectivoActivo())
            ->where('shcools_id', $this->escolarLogada())
            ->with(['classe'])
            ->get();

        $turnos = AnoLectivoTurno::where('ano_lectivos_id', $request->ano_lectivos_id ?? $this->anolectivoActivo())
            ->where('shcools_id', $this->escolarLogada())
            ->with(['turno'])
            ->get();

        $cursos = AnoLectivoCurso::where('ano_lectivos_id', $request->ano_lectivos_id ?? $this->anolectivoActivo())
            ->where('shcools_id', $this->escolarLogada())
            ->with(['curso'])
            ->get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "Controlo de dividas",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            // "turmas" => Turma::where('ano_lectivos_id', $this->anolectivoActivo())->get(),
            "servicos" => Servico::where('shcools_id', $this->escolarLogada())->get(),
            "anos_lectivos" => AnoLectivo::where('shcools_id', $this->escolarLogada())->get(),
            'cartoes' =>  $cartoes,
            "classes" => $classes,
            "turnos" => $turnos,
            "cursos" => $cursos,
            "requests" => $request->all('ano_lectivos_id', 'servico', 'mes', 'condicao', 'cursos_id', 'classes_id', 'turnos_id', 'input_estudante')
        ];

        return view('admin.financeiros.gestao-dividas', $headers);
    }

    public function listagemServicos(Request $request)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $user = auth()->user();

        if (!$user->can('read: servicos')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $servicosTurmas = ServicoTurma::when($request->ano_lectivos_id, function ($query, $value) {
            $query->where('ano_lectivos_id', $value);
        })->when($request->turma_id, function ($query, $value) {
            $query->where('turmas_id', $value);
        })->when($request->servico_id, function ($query, $value) {
            $query->where('servicos_id', $value);
        })->where([
            ['shcools_id', '=', $this->escolarLogada()]
        ])
            ->with(['ano_lectivo', 'turma', 'servico'])
            ->get();



        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "Listagem de serviços",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            "turmas" => Turma::where([
                ['ano_lectivos_id', '=', $this->anolectivoActivo()]
            ])->get(),
            "servicos" => Servico::where([
                ['shcools_id', '=', $this->escolarLogada()]
            ])->get(),
            "anos_lectivos" => AnoLectivo::where([
                ['shcools_id', '=', $this->escolarLogada()]
            ])->get(),
            'servicosTurmas' =>  $servicosTurmas,
            "requests" => $request->all('ano_lectivos_id', 'servico_id', 'turma_id')
        ];

        return view('admin.financeiros.listagem-servicos', $headers);
    }

    public function servicosEdit($id)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $user = auth()->user();

        if (!$user->can('read: servicos')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $servico_turma = ServicoTurma::with(['ano_lectivo', 'turma', 'servico'])->findOrFail(Crypt::decrypt($id));




        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "Editar serviço",
            "descricao" => env('APP_NAME'),
            "servicos" => Servico::where('shcools_id', $this->escolarLogada())->get(),
            "servico_turma" =>  $servico_turma,
        ];

        return view('admin.financeiros.editar-servicos', $headers);
    }

    // -----------------------------------------------------------------------------------
    // ---------------------------------- FINANCEIRO ------------------------------------
    // ----------------------------------------------------------------------------------

    public function financeiroPagamento()
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário
        $user = auth()->user();

        if (!$user->can('read: pagamento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "usuario" => User::findOrFail(Auth::user()->id),
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            'pagamentos' => Pagamento::where([
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['status', '=', "Confirmado"],
            ])
                ->with(['escola', 'forma_pagamento', 'estudante', 'ano', 'operador', 'servico'])
                ->get(),

        ];

        return view('admin.financeiros.pagamentos', $headers);
    }

    public function concluirPagamento()
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário
        $user = auth()->user();

        if (!$user->can('read: pagamento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "usuario" => User::findOrFail(Auth::user()->id),
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),

            'pagamentos' => Pagamento::where('status', '!=', "Confirmado")
                ->where('shcools_id', $this->escolarLogada())
                ->with(['escola', 'forma_pagamento', 'estudante', 'ano', 'operador', 'servico'])
                ->get(),
        ];

        return view('admin.financeiros.concluir-pagamentos', $headers);
    }

    public function pagamentosSalario(Request $request)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $user = auth()->user();

        if (!$user->can('read: pagamento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $professores = FuncionariosControto::when($request->professores, function ($query, $value) {
            $query->whereIn('documento', $value);
        })
            ->with(['funcionario'])
            ->where('shcools_id', $this->escolarLogada())
            ->where('level', '4')
            ->where('cargo_geral', 'professor')
            ->where('status', 'activo')
            ->get();

        $servicos = Servico::where('contas', "despesa")
            ->where('shcools_id', $this->escolarLogada())
            ->get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "formas_pagamentos" => FormaPagamento::where('status_id', 1)->get(),
            "servicos" => $servicos,
            "professores" => $professores,
            "requests" => $request->all("data_inicio", "data_final", "mensal", "professores", "estado_pagamento", "servico_id", "forma_pagamento"),
            "titulo" => "Pagamentos",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.financeiros.funcionario-pagar', $headers);
    }

    public function pagamentosSalarioCreate(Request $request)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $user = auth()->user();

        if (!$user->can('create: salario')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $professores = FuncionariosControto::when($request->professores, function ($query, $value) {
            $query->whereIn('documento', $value);
        })
            ->with(['funcionario'])
            ->where('shcools_id', '=', $this->escolarLogada())
            ->where('level', '4')
            ->where('cargo_geral', 'professor')
            ->where('status', 'activo')
            ->get();


        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $servico = Servico::join('tb_taxas', 'tb_servicos.taxa_id', '=', 'tb_taxas.id')->select('tb_servicos.id', 'taxa', 'contas', 'tb_servicos.servico', 'tb_servicos.contas')->findOrFail($request->servico_id);
            $forma_pagamento = FormaPagamento::where('sigla_tipo_pagamento', $request->forma_pagamento)->first();


            foreach ($professores as $item) {
                $codigo = time();
                sleep(1);

                $professor = Professor::findOrFail($item->funcionario->id);

                $mapas = MapaEfectividade::where('funcionarios_id', $professor->id)
                    ->when($request->data_inicio, function ($query, $value) {
                        $query->where('created_at', '>=', Carbon::parse($value));
                    })->when($request->data_fina, function ($query, $value) {
                        $query->where('created_at', '<=', Carbon::parse($value));
                    })
                    ->get();


                $total_presenca = 0;
                $total_ausencia = 0;
                $total_justificada = 0;
                $total_indefinida = 0;


                foreach ($mapas as $map) {

                    if ($map->status == 'Presente') {
                        $total_presenca = $total_presenca + $map->faltas;
                    }
                    if ($map->status == 'Ausente') {
                        $total_ausencia = $total_ausencia + $map->faltas;
                    }
                    if ($map->status == 'Justitificado') {
                        $total_justificada = $total_justificada + $map->faltas;
                    }
                    if ($map->status == 'Indefinido') {
                        $total_indefinida = $total_indefinida + $map->faltas;
                    }
                }

                $total_presenca;
                $total_ausencia;
                $total_justificada;
                $total_indefinida;
                $total_tempos_semanal = $professor->total_tempos_professor($professor->id);
                $total_tempos_mensal = $professor->total_tempos_professor($professor->id) * 4;

                $salario_por_tempo = $item->salario;
                $desconto_por_tempo = $item->falta_por_dia;
                $salario_bruto = $item->salario * $professor->total_tempos_professor($professor->id) * 4;
                $valor_desconto = $item->falta_por_dia * $total_ausencia;
                $valor_receber = $total_presenca * $item->salario;

                $subcidio_alimentacao = $item->subcidio_alimentacao;
                $subcidio_rransporte = $item->subcidio_transporte;

                $salario = $valor_receber + $subcidio_alimentacao + $subcidio_rransporte;


                $codigo_designacao_factura = "EAV";

                $documento = "FR";

                if ($forma_pagamento->sigla_tipo_pagamento == "NU") {
                    $valor_cash = $salario;
                    $valor_multicaixa = 0;
                } else if ($forma_pagamento->sigla_tipo_pagamento == "MB") {
                    $valor_cash = 0;
                    $valor_multicaixa = $salario;
                } else if ($forma_pagamento->sigla_tipo_pagamento == "OU") {
                    $valor_cash = $request->valor_entregue;
                    $valor_multicaixa = $request->valor_entregue_multicaixa;
                }

                $ano_lectivo_activo = AnoLectivo::findOrFail($this->anolectivoActivo());

                $contarFactura = Pagamento::where('tipo_factura', '=', $documento)
                    ->where('factura_ano', '=', $ano_lectivo_activo->serie ?? date("Y"))
                    ->where('shcools_id', '=', $this->escolarLogada())
                    ->count();

                $ultimoRecibo = Pagamento::where('tipo_factura', '=', $documento)
                    ->where('factura_ano', '=', $ano_lectivo_activo->serie ?? date("Y"))
                    ->where('shcools_id', '=', $this->escolarLogada())
                    ->latest()
                    ->first();

                if (!$ultimoRecibo) {
                    $hashAnterior = "";
                } else {
                    $hashAnterior = $ultimoRecibo->hash;
                }

                //Manipulação de datas: data actual
                $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

                $numeroFactura = $contarFactura + 1;

                $rsa = new RSA(); //Algoritimo RSA

                $privatekey = $this->pegarChavePrivada();
                $publickey = $this->pegarChavePublica();

                // Lendo a private key
                $rsa->loadKey($privatekey);
                $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ';' . "{$documento} {$codigo_designacao_factura}{$ano_lectivo_activo->serie}/{$numeroFactura}" . ';' . number_format($salario, 2, ".", "") . ';' . $hashAnterior;

                // HASH
                $hash = 'sha1'; // Tipo de Hash
                $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima

                //ASSINATURA
                $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
                $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)

                // Lendo a public key
                $rsa->loadKey($publickey);

                $valor_extenso = $this->valor_por_extenso($salario);

                if ($request->estado_pagamento == "Pendente") {
                    $retificado = 'N';
                    $convertido_factura = 'N';
                    $factura_divida = 'Y';
                    $anulado = 'N';
                } else {
                    $retificado = 'N';
                    $convertido_factura = 'N';
                    $factura_divida = 'N';
                    $anulado = 'N';
                }

                $createPagamento = Pagamento::create([
                    "pago_at" => $servico->servico,
                    "quantidade" => 1,
                    "servicos_id" => $servico->id,
                    "status" => $request->estado_pagamento,
                    'tipo_servico_detalhe' => 'mensal',
                    'type_service' => 'salario',
                    "caixa_at" => $servico->contas,
                    "ficha" => $codigo,
                    "referencia" => $codigo,
                    "valor" => $salario,
                    "valor2" => $salario,
                    "troco" => 0,
                    "valor_entregue" => $salario,
                    "desconto" => $valor_desconto,
                    "multa" => NULL,
                    "banco" => NULL,

                    "numero_transacao" => NULL,
                    "data_at" => $request->data_inicio,
                    "mensal" => $this->mesecompleto(),
                    "funcionarios_id" => Auth::user()->id,
                    "estudantes_id" => $professor->id,
                    "numero_factura" => $numeroFactura,
                    "tipo_factura" => $documento,
                    "next_factura" => "{$documento} {$codigo_designacao_factura}{$ano_lectivo_activo->serie}/{$numeroFactura}",
                    "shcools_id" => $this->escolarLogada(),

                    "model" => 'professor',
                    "ano_lectivos_id" => $this->anolectivoActivo(),
                    'data_vencimento' => $request->data_final,
                    'data_disponibilizacao' => date("Y-m-d"),

                    'retificado' => $retificado,
                    'convertido_factura' => $convertido_factura,
                    'factura_divida' => $factura_divida,
                    'anulado' => $anulado,


                    "total_tempos_semanal" => $total_tempos_semanal,
                    "total_tempos_mensal" => $total_tempos_mensal,
                    "salario_por_tempo" => $salario_por_tempo,
                    "desconto_por_tempo" => $desconto_por_tempo,
                    "salario_bruto" => $salario_bruto,


                    "subcidio" => 0,
                    "subcidio_transporte" => $subcidio_rransporte,
                    "subcidio_alimentacao" => $subcidio_alimentacao,
                    "subcidio_natal" => 0,
                    "subcidio_ferias" => 0,
                    "subcidio_abono_familiar" => 0,
                    "inss" => 0,
                    "irt" => 0,
                    "faltas" => $total_ausencia,
                    "presenca" => $total_presenca,

                    'factura_ano' => $ano_lectivo_activo->serie ?? date("Y"),
                    'prazo' => 0,

                    "tipo_pagamento" => $forma_pagamento->sigla_tipo_pagamento,
                    "pagamento_id" => $forma_pagamento->id,
                    "valor_extenso" => $valor_extenso,
                    'total_iva' => 0,
                    'valor_cash' => $valor_cash,
                    'valor_multicaixa' => $valor_multicaixa,
                    'texto_hash' => $plaintext,
                    'hash' => base64_encode($signaturePlaintext),
                    'nif_cliente' => $professor->bilheite,
                    'total_incidencia' => $salario,
                    'conta_corrente_cliente' => "31.1.2.1.{$professor->id}",
                ]);

                DetalhesPagamentoPropina::create([
                    'code' => $codigo,
                    'pagamentos_id' => $createPagamento->id,
                    'multa' => 0,
                    'total_pagar' => $salario,
                    'mes_id' => "NULL",
                    'valor_incidencia' => $salario,
                    'valor_iva' => 0,
                    'taxa_id' => $servico->taxa,
                    'desconto' => 0,
                    'desconto_valor' => $valor_desconto,
                    'mes' => date('M'),
                    'model_id' => $professor->id,
                    'quantidade' => 1,
                    'funcionarios_id' => Auth::user()->id,
                    'preco' => $salario,
                    'status' => 'Pago',
                    'servicos_id' => $servico->id,
                    'date_att' => $this->data_sistema(),
                    'ano_lectivos_id' => $this->anolectivoActivo(),
                    'shcools_id' => $this->escolarLogada(),
                ]);

                $cartao_funcionario = CartaoFuncionario::where('shcools_id', $this->escolarLogada())
                    ->where('level', '4')
                    ->where('funcionarios_id', $professor->id)
                    ->where('month_name', $request->mensal)
                    ->where('status', "Nao Pago")
                    ->where('ano_lectivos_id', '=', $this->anolectivoActivo())
                    ->first();

                if ($cartao_funcionario) {
                    $cartao = CartaoFuncionario::findOrFail($cartao_funcionario->id);
                    $cartao->status = "Pago";
                    $cartao->codigo = $professor->codigo;
                    $cartao->descricao = "Pago";
                    $cartao->update();
                }

                $cartao_escola = CartaoEscola::where([
                    ['month_name', '=', $request->mensal],
                    ['shcools_id', '=', $this->escolarLogada()],
                    ['servicos_id', '=', $servico->id],
                    ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ])->first();

                $updateCartao = CartaoEscola::find($cartao_escola->id);
                $updateCartao->status = 'Pago';
                $updateCartao->update();
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
            'resulatado' => $createPagamento,
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }

    public function mesFolhaSalario()
    {

        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário
        $user = auth()->user();

        if (!$user->can('read: pagamento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            "meses" => Mes::all(),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.financeiros.mes-folha-salario', $headers);
    }

    // contas a pagar lista dos pagamentos
    // LUZ, AGUA, INTERNET, FUNCIONARIO
    public function novoPagamentoPagar()
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $user = auth()->user();

        if (!$user->can('read: pagamento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->findOrFail($this->escolarLogada());

        $servicos = ServicoTurma::where('turmas_id', $this->escolarLogada())
            ->where('model', 'escola')
            ->join('tb_servicos', 'tb_servicos_turma.servicos_id', '=', 'tb_servicos.id')
            ->select('tb_servicos.servico', 'tb_servicos.id')
            ->get();

        $bancos = Banco::where('shcools_id', $this->escolarLogada())->get();
        $caixas = Caixa::where('shcools_id', $this->escolarLogada())->get();

        $servicos_receitas = ServicoTurma::where('turmas_id', $this->escolarLogada())
            ->where('model', 'turmas')
            ->join('tb_servicos', 'tb_servicos_turma.servicos_id', '=', 'tb_servicos.id')
            ->select('tb_servicos.servico', 'tb_servicos.id')
            ->get();

        $headers = [
            "servicos" => $servicos,
            "servicos_receitas" => $servicos_receitas,
            "bancos" => $bancos,
            "caixas" => $caixas,
            "formas_pagamento" => FormaPagamento::where('tipo_credito', 2)->get(),
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            "escola" => $escola,
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.financeiros.novo-pagamentos-pagar', $headers);
    }

    // selecionar servicos da escola
    public function carregarServicoEscola($id)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $servico = Servico::findOrFail($id);
        $escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->findOrFail($this->escolarLogada());

        // servicos da turma
        $servico = ServicoTurma::where('turmas_id', $escola->id)
            ->where('model', "escola")
            ->where('servicos_id', $servico->id)
            // ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->with('servico')
            ->first();

        if (!$servico) {
            return response()->json([
                "status" => 404,
                "message" => 'Nenhum serviço foi localizado nesta escola, ou seja esta serviço não esta cadastrado nesta escola'
            ]);
        }

        $cartao = CartaoEscola::where('shcools_id', $escola->id)
            ->where('servicos_id', $servico->servico->id)
            // ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('status', '<>', 'Pago')
            ->select('tb_cartao_escola.id', 'tb_cartao_escola.month_name', 'tb_cartao_escola.data_at', 'tb_cartao_escola.data_exp', 'tb_cartao_escola.status')
            ->get();


        if (!$cartao) {
            return response()->json([
                "status" => 404,
                "message" => 'Este escola esta sem cartão para este serviço'
            ]);
        }

        if ($servico) {
            return response()->json([
                "status" => 200,
                "servico" => $servico,
                "cartao" => $cartao,
                "escola" => $escola,
                "usuario" => User::findOrFail(Auth::user()->id),
                "mesesAdd" => DetalhesPagamentoPropina::where('status', 'processo')
                    ->where('funcionarios_id', Auth::user()->id)
                    ->where('servicos_id', $servico->servico->id)
                    ->get(),
                "somaVolores" => DetalhesPagamentoPropina::where('status', 'processo')
                    ->where('funcionarios_id', Auth::user()->id)
                    ->where('servicos_id', $servico->servico->id)
                    ->sum('total_pagar'),

                "somaMulta" => DetalhesPagamentoPropina::where('status', 'processo')
                    ->where('funcionarios_id', Auth::user()->id)
                    ->where('servicos_id', $servico->servico->id)
                    ->sum('multa'),

                "somaQuantidade" => DetalhesPagamentoPropina::where('status', 'processo')
                    ->where('funcionarios_id', Auth::user()->id)
                    ->where('servicos_id', $servico->servico->id)
                    ->sum('quantidade'),
            ]);
        } else {
            return response()->json([
                "status" => 404,
                "message" => 'Turno não Encontrado'
            ]);
        }
    }

    public function escolaDetalhesPagamentoPropina($id, $servico)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $user = auth()->user();

        if (!$user->can('read: pagamento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $cartao = CartaoEscola::findOrFail($id);
        // $servicos = Servico::findOrFail($servico);
        $servicos = Servico::join('tb_taxas', 'tb_servicos.taxa_id', '=', 'tb_taxas.id')->select('tb_servicos.id', 'taxa', 'contas', 'tb_servicos.servico')->findOrFail($servico);

        $escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->findOrFail($this->escolarLogada());

        $servicoTurma = ServicoTurma::where([
            ['servicos_id', '=', $servicos->id],
            ['turmas_id', '=', $escola->id],
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['model', '=', 'escola'],
        ])->first();

        $descontoGeral = $servicoTurma->desconto ?? 0;

        $valorBase = $servicoTurma->preco * 1;

        $subTotalTaxaIva = ($servicos->taxa / 100) * $valorBase;

        $subTotalIncidencia = (($servicoTurma->preco * 1) - ($servicoTurma->preco * $descontoGeral / 100));

        $valor_a_descontar = $servicoTurma->preco * ($servicoTurma->desconto / 100);


        $verificar = DetalhesPagamentoPropina::where([
            ['status', '=', 'processo'],
            ['funcionarios_id', '=', Auth::user()->id],
            // ['date_att', '=', $this->data_sistema()],
            ['servicos_id', '=', $servicos->id],
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
        ])->first();

        $add = new DetalhesPagamentoPropina();
        if ($verificar) {
            $add->code = $verificar->code;
        } else {
            $add->code = time();
        }

        if ($this->data_sistema() > $cartao->data_exp) {
            $add->multa = $servicoTurma->multa;
        } else {
            $add->multa = "0";
        }

        $add->mes_id = "NULL";
        $add->mes = $cartao->month_name;
        $add->quantidade =     '1';
        $add->funcionarios_id = Auth::user()->id;
        $add->preco = $servicoTurma->preco;
        $add->status = 'processo';
        $add->servicos_id = $servicos->id;
        $add->date_att = $this->data_sistema();
        $add->ano_lectivos_id = $this->anolectivoActivo();
        $add->valor_iva = $subTotalTaxaIva;
        $add->taxa_id = $servicos->taxa;
        $add->total_pagar = ($servicoTurma->preco + $subTotalTaxaIva) - $valor_a_descontar;

        $add->desconto = $descontoGeral;
        $add->desconto_valor = $valor_a_descontar;
        $add->valor_incidencia = $subTotalIncidencia;

        $add->mes = $cartao->month_name;
        $add->model_id = $this->escolarLogada();
        $add->shcools_id = $this->escolarLogada();

        if ($add->save()) {
            $cartao->status = 'processo';
            $cartao->update();

            return response()->json([
                'status' => 200,
                'servico' => $servicos,
                "mesesAdd" => DetalhesPagamentoPropina::where([
                    ['status', '=', 'processo'],
                    ['funcionarios_id', '=', Auth::user()->id],
                    // ['date_att', '=', $this->data_sistema()],
                    ['servicos_id', '=', $servicos->id],
                ])
                    ->get(),

                "somaVolores" => DetalhesPagamentoPropina::where([
                    ['status', '=', 'processo'],
                    ['funcionarios_id', '=', Auth::user()->id],
                    // ['date_att', '=', $this->data_sistema()],
                    ['servicos_id', '=', $servicos->id],
                ])->sum('total_pagar'),

                "somaMulta" => DetalhesPagamentoPropina::where([
                    ['status', '=', 'processo'],
                    ['funcionarios_id', '=', Auth::user()->id],
                    // ['date_att', '=', $this->data_sistema()],
                    ['servicos_id', '=', $servicos->id],
                ])->sum('multa'),

                "somaQuantidade" => DetalhesPagamentoPropina::where([
                    ['status', '=', 'processo'],
                    ['funcionarios_id', '=', Auth::user()->id],
                    // ['date_att', '=', $this->data_sistema()],
                    ['servicos_id', '=', $servicos->id],
                ])->sum('quantidade'),

                "cartao" => CartaoEscola::where([
                    ['shcools_id', '=', $escola->id],
                    ['servicos_id', '=', $servicos->id],
                    ['status', '<>', 'Pago'],
                    ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ])
                    ->get(),

            ]);
        }
    }

    public function escolaDetalhesPagamentoPropinaRemoverMes($id, $servico)
    {

        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $user = auth()->user();

        if (!$user->can('read: pagamento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $detalhe = DetalhesPagamentoPropina::findOrFail($id);
        $escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->findOrFail($this->escolarLogada());
        $servicos = Servico::findOrFail($servico);

        $updateCartao = CartaoEscola::where([
            ['status', '=', 'processo'],
            ['shcools_id', '=', $escola->id],
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['month_name', '=', $detalhe->mes],
        ])->first();

        $update = CartaoEscola::findOrFail($updateCartao->id);
        $update->status = 'Nao Pago';

        $detalhe->delete();
        $update->update();

        return response()->json([
            'status' => 200,
            'servico' => $servicos,
            "mesesAdd" => DetalhesPagamentoPropina::where([
                ['status', '=', 'processo'],
                ['servicos_id', '=', $servicos->id],
                ['funcionarios_id', '=', Auth::user()->id],
                // ['date_att', '=', $this->data_sistema()],
            ])
                // ->select('tb_detalhes_pagamentos.quantidade', 'tb_detalhes_pagamentos.status', 'tb_detalhes_pagamentos.preco', 'tb_detalhes_pagamentos.id', 'tb_detalhes_pagamentos.mes', 'tb_detalhes_pagamentos.multa')
                ->get(),

            "somaVolores" => DetalhesPagamentoPropina::where([
                ['status', '=', 'processo'],
                ['funcionarios_id', '=', Auth::user()->id],
                // ['date_att', '=', $this->data_sistema()],
                ['servicos_id', '=', $servicos->id],
            ])->sum('total_pagar'),

            "somaMulta" => DetalhesPagamentoPropina::where([
                ['status', '=', 'processo'],
                ['funcionarios_id', '=', Auth::user()->id],
                // ['date_att', '=', $this->data_sistema()],
                ['servicos_id', '=', $servicos->id],
            ])->sum('multa'),

            "somaQuantidade" => DetalhesPagamentoPropina::where([
                ['status', '=', 'processo'],
                ['funcionarios_id', '=', Auth::user()->id],
                // ['date_att', '=', $this->data_sistema()],
                ['servicos_id', '=', $servicos->id],
            ])->sum('quantidade'),

            "cartao" => CartaoEscola::where([
                ['shcools_id', '=', $escola->id],
                ['servicos_id', '=', $servicos->id],
                ['status', '<>', 'Pago'],
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ])
                // ->select('tb_cartao_escola.id', 'tb_cartao_escola.month_name', 'tb_cartao_escola.data_at', 'tb_cartao_escola.data_exp', 'tb_cartao_escola.status')
                ->get(),
        ]);
    }

    public function escolaPagamentoServicoCreate(Request $request)
    {

        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $user = auth()->user();

        if (!$user->can('read: pagamento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $escola = Shcool::findOrFail($request->input('escola'));
        $forma_pagamento = FormaPagamento::where('sigla_tipo_pagamento', $request->tipo_pagamento)->first();

        $validate = Validator::make($request->all(), [
            'valor' => 'required',
            'servico' => 'required',
            'tipo_pagamento' => 'required',
            'valor_pago' => 'required',
        ], [
            "valor.required" => "******",
            "servico.required" => "******",
            "tipo_pagamento.required" => "******",
            "valor_pago.required" => "******",
        ]);

        $request->multa = (int) $request->multa;
        $request->desconto = (int) $request->desconto;
        $request->valor = (int) $request->valor;

        // $servico = Servico::with('taxa', 'motivo')->findOrFail($request->servico);
        $servico = Servico::join('tb_taxas', 'tb_servicos.taxa_id', '=', 'tb_taxas.id')->select('tb_servicos.id', 'taxa', 'contas', 'tb_servicos.servico')->findOrFail($request->servico);

        // if ((!filter_var($request->input('valor'), FILTER_VALIDATE_FLOAT) and !filter_var($request->input('valor'), FILTER_VALIDATE_INT)) and
        //     (!filter_var($request->input('desconto'), FILTER_VALIDATE_FLOAT) and !filter_var($request->input('desconto'), FILTER_VALIDATE_INT)) and
        //     (!filter_var($request->input('multa'), FILTER_VALIDATE_FLOAT) and !filter_var($request->input('multa'), FILTER_VALIDATE_INT))
        // ) {
        //     return response()->json([
        //         'status' => 300,
        //         'message' => "Os Valores não podem ser Letras por favor",
        //     ]);
        // }

        $request->valor = $request->valor_pago;

        $valor_multicaixa = 0;
        $valor_cash = 0;

        if ($request->tipo_pagamento == "NU") {
            $valor_cash = $request->valor;
            $valor_multicaixa = 0;
        } else if ($request->tipo_pagamento == "MB") {
            $valor_cash = 0;
            $valor_multicaixa = $request->valor;
        } else if ($request->tipo_pagamento == "TT") {
            $valor_cash = 0;
            $valor_multicaixa = $request->valor;
        } else if ($request->tipo_pagamento == "DD") {
            $valor_cash = 0;
            $valor_multicaixa = $request->valor;
        } else if ($request->tipo_pagamento == "OU") {
            $valor_cash = 0;
            $valor_multicaixa = $request->valor;
        }

        $ano_lectivo_activo = AnoLectivo::findOrFail($this->anolectivoActivo());

        $contarFactura = Pagamento::where('tipo_factura', '=', $request->documento)
            ->where('factura_ano', '=', $ano_lectivo_activo->serie ?? date("Y"))
            ->where('caixa_at', '=', $servico->contas)
            ->where('shcools_id', '=', $this->escolarLogada())
            ->count();

        $ultimoRecibo = Pagamento::where('tipo_factura', '=', $request->documento)
            ->where('factura_ano', '=', $ano_lectivo_activo->serie ?? date("Y"))
            ->where('caixa_at', '=', $servico->contas)
            ->where('shcools_id', '=', $this->escolarLogada())
            ->latest()
            ->first();

        if (!$ultimoRecibo) {
            $hashAnterior = "";
        } else {
            $hashAnterior = $ultimoRecibo->hash;
        }

        //Manipulação de datas: data actual
        $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

        $numeroFactura = $contarFactura + 1;

        $rsa = new RSA(); //Algoritimo RSA

        $privatekey = $this->pegarChavePrivada();
        $publickey = $this->pegarChavePublica();

        // Lendo a private key
        $rsa->loadKey($privatekey);

        $codigo_designacao_factura = "NOTA DE SAÍDA";

        // Texto que deverá ser assinado com a assinatura RSA::SIGNATURE_PKCS1, e o Texto estará mais ou menos assim após as
        // Concatenações com os dados preliminares da factura: 2020-09-14;2020-09-14T20:34:09;FP PAT2020/1;457411.2238438;

        $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ';' . "{$request->documento} {$codigo_designacao_factura}{$ano_lectivo_activo->serie}/{$numeroFactura}" . ';' . number_format($request->valor, 2, ".", "") . ';' . $hashAnterior;

        // HASH
        $hash = 'sha1'; // Tipo de Hash
        $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima

        //ASSINATURA
        $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
        $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)

        // Lendo a public key
        $rsa->loadKey($publickey);

        $valor_extenso = $this->valor_por_extenso($request->valor);

        $code = time();

        // ----------------------------------------------------------

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        } else {

            if ($request->input('status_servico_pagar') == "unico") {

                $createP = Pagamento::create([
                    "pago_at" => strtolower($servico->servico),
                    "servicos_id" => $servico->id,
                    "caixa_id" => $request->caixa_id,
                    "banco_id" => $request->banco_id,
                    "caixa_at" => $servico->contas,
                    "ficha" => $code,
                    "status" => "Confirmado",
                    "desconto" => 0,
                    'tipo_servico_detalhe' => 'unico',
                    "valor" => $request->valor,
                    "valor2" => $request->valor,
                    "multa" => 0,
                    "data_at" => $this->data_sistema(),
                    "mensal" => $this->mesecompleto(),
                    "funcionarios_id" => Auth::user()->id,
                    "estudantes_id" => $escola->id,
                    'valor_entregue' => $request->valor,
                    "numero_factura" => $numeroFactura,
                    'troco' => $request->valor - $request->valor,
                    'valor_entregue' => $request->valor,
                    'data_vencimento' => date("Y-m-d"),
                    'data_disponibilizacao' => date("Y-m-d"),
                    'factura_ano' => $ano_lectivo_activo->serie ?? date("Y"),
                    'prazo' => 0,
                    'data_vencimento' => date("Y-m-d"),
                    "model" => 'escola',
                    "ano_lectivos_id" => $this->anolectivoActivo(),
                    "tipo_factura" =>  $request->documento,
                    "tipo_pagamento" => $forma_pagamento->sigla_tipo_pagamento,
                    "pagamento_id" => $forma_pagamento->id,
                    'next_factura' => "{$request->documento} {$codigo_designacao_factura}{$ano_lectivo_activo->serie}/{$numeroFactura}",
                    'observacao' => $request->observacao,
                    'referencia' => $code,
                    'shcools_id' => $this->escolarLogada(),
                    'retificado' => 'N',
                    'convertido_factura' => 'N',
                    'factura_divida' => 'N',
                    'anulado' => 'N',
                    'moeda' => 'AOA',
                    'valor_extenso' => $valor_extenso,
                    'valor_cash' => $valor_cash,
                    'valor_multicaixa' => $valor_multicaixa,
                    'texto_hash' => $plaintext,
                    'hash' => base64_encode($signaturePlaintext),
                    'nif_cliente' => $escola->documento,
                    'conta_corrente_cliente' => $escola->documento,
                    'total_iva' => $request->valor,
                    'total_incidencia' => $request->valor,
                    'quantidade' => 1,
                ]);

                DetalhesPagamentoPropina::create([
                    'total_pagar' => $request->valor,
                    'code' => $code,
                    'mes_id' => "NULL",
                    'valor_incidencia' => $request->valor,
                    'valor_iva' => 0,
                    'taxa_id' => $servico->taxa,
                    'mes' => date("M"),
                    'model_id' => $escola->id,
                    'multa' => 0,
                    'quantidade' => 1,
                    'funcionarios_id' => Auth::user()->id,
                    'preco' => $request->valor,
                    'status' => 'Pago',
                    'servicos_id' => $servico->id,
                    'date_att' => $this->data_sistema(),
                    'ano_lectivos_id' => $this->anolectivoActivo(),
                    'shcools_id' => $this->escolarLogada(),
                    'pagamentos_id' => $createP->id,
                ]);

                $cartao = CartaoEscola::where([
                    ['shcools_id', '=', $escola->id],
                    ['servicos_id', '=', $servico->id],
                    ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ])->first();

                $updateCartao = CartaoEscola::find($cartao->id);
                $updateCartao->status = 'Pago';
                $updateCartao->save();
            } else
            if ($request->input('status_servico_pagar') == 'mensal') {

                $items = DetalhesPagamentoPropina::selectRaw('
                    SUM(multa) as total_multa,
                    SUM(preco) as total_preco,
                    SUM(total_pagar) as total_a_pagar,
                    SUM(quantidade) as total_quantidade,
                    SUM(desconto_valor) as total_desconto_valor,
                    SUM(valor_incidencia) as total_incidencia,
                    SUM(valor_iva) as total_iva
                ')->where([
                    ['status', '=', 'processo'],
                    ['funcionarios_id', '=', Auth::user()->id],
                    ['servicos_id', '=', $servico->id],
                    ['model_id', '=', $escola->id],
                ])
                    ->first();

                $timestemp = DetalhesPagamentoPropina::where('status', 'processo')->where('funcionarios_id', Auth::user()->id)->where('servicos_id', $servico->id)->where('model_id', $escola->id)
                    ->first();

                $timestemps = DetalhesPagamentoPropina::where('status', 'processo')->where('funcionarios_id', Auth::user()->id)->where('servicos_id', $servico->id)->where('model_id', $escola->id)
                    ->get();

                if ($forma_pagamento->sigla_tipo_pagamento == "NU") {
                    $valor_cash = $request->valor;
                    $valor_multicaixa = 0;
                } else if ($forma_pagamento->sigla_tipo_pagamento == "MB") {
                    $valor_cash = 0;
                    $valor_multicaixa = $request->valor;
                }

                $createPagamento = Pagamento::create([
                    "pago_at" =>  $servico->servico,
                    "caixa_id" => $request->caixa_id,
                    "banco_id" => $request->banco_id,
                    "quantidade" => $items->total_quantidade ?? 1,
                    "servicos_id" => $servico->id,
                    'tipo_servico_detalhe' => 'mensal',
                    "status" => "Confirmado",
                    "caixa_at" => $servico->contas,
                    "ficha" => $timestemp->code,
                    "referencia" => $timestemp->code,

                    "valor" => $request->valor,
                    "valor2" => $request->valor,

                    "troco" => 0,
                    "valor_entregue" => $request->valor,
                    "desconto" => $request->desconto ?? 0,
                    "multa" => $request->multa ?? 0,

                    "data_at" => $this->data_sistema(),
                    "mensal" => $this->mesecompleto(),
                    "funcionarios_id" => Auth::user()->id,
                    "estudantes_id" => $escola->id,
                    "model" => 'escola',
                    "ano_lectivos_id" => $this->anolectivoActivo(),
                    "numero_factura" => $numeroFactura,
                    "tipo_factura" => $request->documento,
                    "next_factura" => "{$request->documento} {$codigo_designacao_factura}{$ano_lectivo_activo->serie}/{$numeroFactura}",
                    "shcools_id" => $this->escolarLogada(),

                    'data_vencimento' => date("Y-m-d"),
                    'data_disponibilizacao' => date("Y-m-d"),
                    'observacao' => $request->observacao,

                    "tipo_pagamento" => $forma_pagamento->sigla_tipo_pagamento,
                    "pagamento_id" => $forma_pagamento->id,
                    "valor_extenso" => $valor_extenso,
                    'factura_ano' => $ano_lectivo_activo->serie ?? date("Y"),
                    'prazo' => 0,
                    'total_iva' => $items->total_iva,
                    'valor_cash' => $valor_cash,
                    'valor_multicaixa' => $valor_multicaixa,
                    'texto_hash' => $plaintext,
                    'hash' => base64_encode($signaturePlaintext),
                    'nif_cliente' => $escola->documento,
                    'total_incidencia' => $items->total_incidencia,
                    'conta_corrente_cliente' => $escola->documento,
                ]);

                if ($timestemps) {
                    foreach ($timestemps as $item) {
                        $upd = DetalhesPagamentoPropina::findOrFail($item->id);
                        $upd->status = 'Pago';
                        $upd->pagamentos_id = $createPagamento->id;
                        $upd->update();
                    }
                }

                $cartao = CartaoEscola::where([
                    ['status', '=', 'processo'],
                    ['shcools_id', '=', $escola->id],
                    ['servicos_id', '=', $servico->id],
                    ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ])->get();

                if ($cartao) {
                    foreach ($cartao as $carta) {
                        $upd = CartaoEscola::findOrFail($carta->id);
                        $upd->status = 'Pago';
                        $upd->update();
                    }
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
}
