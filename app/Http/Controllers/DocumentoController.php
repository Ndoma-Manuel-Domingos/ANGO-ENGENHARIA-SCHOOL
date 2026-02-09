<?php

namespace App\Http\Controllers;

use App\Models\FormaPagamento;
use App\Models\User;
use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\calendarios\Pagamento;
use App\Models\web\calendarios\PagamentoNotaCredito;
use App\Models\web\calendarios\PagamentoRecibo;
use App\Models\web\calendarios\Servico;
use App\Models\web\calendarios\ServicoTurma;
use App\Models\web\estudantes\CartaoEstudante;
use App\Models\web\estudantes\Estudante;
use App\Models\web\financeiros\DetalhesPagamentoPropina;
use App\Models\web\salas\Banco;
use App\Models\web\salas\Caixa;
use App\Models\web\turmas\EstudantesTurma;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

class DocumentoController extends Controller
{
    //
    use TraitHelpers;


    public function __construct()
    {
        $this->middleware('auth');
    }


    public function documentos(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: factura')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '2048M'); // ou mais se necessário
        
      
        if($request->ano_lectivos_id == null || $request->ano_lectivos_id == "") {
            $request->ano_lectivos_id = $this->anolectivoActivo();
        }
        
        $pagamentos = Pagamento::with('operador')->with('servico')->when($request->factura, function ($query, $value) {
            $query->where('tipo_factura', $value);
        })
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate('data_at', '>=', Carbon::createFromDate($value));
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('data_at', '<=', Carbon::createFromDate($value));
            })
            ->when($request->servico_id, function ($query, $value) {
                $query->where('servicos_id', $value);
            })
            ->where('caixa_at', 'receita')
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $request->ano_lectivos_id)
        ->get();

        $servicos = Servico::where('shcools_id', $this->escolarLogada())->get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Documentos",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "pagamentos" => $pagamentos,
            "servicos" => $servicos,
            "filtros" => $request->all("factura", "ano_lectivos_id", "servico_id", "data_inicio", "data_final"),
            "total_facturas_anuladas" => PagamentoNotaCredito::where('ano_lectivos_id', $request->ano_lectivos_id)
                ->where('shcools_id', $this->escolarLogada())
                ->count(),
            "total_facturas" => Pagamento::where('ano_lectivos_id', $request->ano_lectivos_id)
                ->where('shcools_id', $this->escolarLogada())
                ->where('tipo_factura', 'FT')
                ->where('status', 'Pendente')
                ->count(),
            "total_facturas_proforma" => Pagamento::where('ano_lectivos_id', $request->ano_lectivos_id)
                ->where('shcools_id', $this->escolarLogada())
                ->where('tipo_factura', 'FP')
                ->where('status', 'Pendente')
                ->count(),

            "total_facturas_recibo" => Pagamento::where('ano_lectivos_id', $request->ano_lectivos_id)
                ->where('shcools_id', $this->escolarLogada())
                ->where('tipo_factura', 'FR')
                ->where('status', 'Confirmado')
                ->count(),

            "total_recibos" => PagamentoRecibo::where('ano_lectivos_id', $request->ano_lectivos_id)
                ->where('shcools_id', $this->escolarLogada())
                ->count(),

            "anos_lectivos" => AnoLectivo::where('shcools_id', $this->escolarLogada())->get(),
        ];


        return view('admin.documentos.documentos', $headers);
    }

    public function facturacao(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: factura')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $pagamentos = Pagamento::when($request->data_inicio, function ($query, $value) {
            $query->whereDate('data_at', '>=', Carbon::createFromDate($value));
        })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('data_at', '<=', Carbon::createFromDate($value));
            })
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', '=', $this->anolectivoActivo())
            ->whereIn('tipo_factura', ['FR', 'FT'])
            ->get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Documentos Facturação",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),

            "pagamentos" => $pagamentos,
            "filtros" => $request->all("factura", "data_inicio", "data_final")
        ];

        return view('admin.documentos.facturacao', $headers);
    }

    public function informativo(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: factura')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $pagamentos = Pagamento::when($request->data_inicio, function ($query, $value) {
            $query->whereDate('data_at', '>=', Carbon::createFromDate($value));
        })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('data_at', '<=', Carbon::createFromDate($value));
            })
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', '=', $this->anolectivoActivo())
            ->where('tipo_factura', '=', 'FP')
            ->get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Documentos Informativos",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),

            "pagamentos" => $pagamentos,
            "filtros" => $request->all("data_inicio", "data_final")
        ];

        return view('admin.documentos.informativo', $headers);
    }

    public function recibos(Request $request)
    {

        $user = auth()->user();

        if (!$user->can('read: factura')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $pagamentos = PagamentoRecibo::when($request->data_inicio, function ($query, $value) {
            $query->whereDate('data_at', '>=', Carbon::createFromDate($value));
        })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('data_at', '<=', Carbon::createFromDate($value));
            })
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', '=', $this->anolectivoActivo())
            ->with(['pagamento'])
            ->get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Documentos Informativos",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),

            "pagamentos" => $pagamentos,
            "filtros" => $request->all("data_inicio", "data_final")
        ];

        return view('admin.documentos.recibos', $headers);
    }

    public function facturasSemPagamentos(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: factura')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $pagamentos = Pagamento::when($request->data_inicio, function ($query, $value) {
            $query->whereDate('data_vencimento', '>=', Carbon::createFromDate($value));
        })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('data_vencimento', '<=', Carbon::createFromDate($value));
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('data_vencimento', '<=', Carbon::createFromDate($value));
            })
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', '=', $this->anolectivoActivo())
            ->where('tipo_factura', '=', 'FT')
            ->where('status', '=', 'Pendente')
            ->get();


        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor


        $dc = 0;
        $dv = 0;
        // $dividasVencida = Pagamento::whereDate('data_vencimento', '<', $this->data_sistema())->where('shcools_id', $this->escolarLogada())->where('status', '=', 'Pendente')->where('tipo_factura', '=', 'FT')->select('quantidade', 'valor', 'multa')->get();
        // $dividasCorrente = Pagamento::whereDate('data_vencimento', '>=', $this->data_sistema())->where('shcools_id', $this->escolarLogada())->where('status', '=', 'Pendente')->where('tipo_factura', '=', 'FT')->select('quantidade', 'valor', 'multa')->get();
        // if($dividasVencida){
        //     foreach ($dividasVencida as $d) {
        //         $dv = $dv + (($d->valor * $d->quantidade) + $d->multa);
        //     }
        // }

        // if($dividasCorrente){
        //     foreach ($dividasCorrente as $d) {
        //         $dc = $dc + (($d->valor * $d->quantidade)  + $d->multa);
        //     }
        // }

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Documentos Informativos",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),

            "pagamentos" => $pagamentos,
            "divCorr" => $dc,
            "divVenc" => $dv,
            "filtros" => $request->all("data_inicio", "data_final")
        ];

        return view('admin.documentos.sem-pagamentos', $headers);
    }

    public function facturasSemPagamentosCorrentes()
    {
        $user = auth()->user();

        if (!$user->can('read: factura')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $pagamentos = Pagamento::whereDate('data_vencimento', '>=', $this->data_sistema())
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', '=', $this->anolectivoActivo())
            ->where('tipo_factura', '=', 'FT')
            ->where('status', '=', 'Pendente')
            ->get();

        $dividasCorrente = Pagamento::whereDate('data_vencimento', '>=', $this->data_sistema())
            ->where('status', '=', 'Pendente')
            ->where('tipo_factura', '=', 'FT')
            ->get();

        $dc = 0;
        if ($dividasCorrente) {
            foreach ($dividasCorrente as $d) {
                $dc = $dc + ($d->valor * $d->quantidade)  + $d->multa;
            }
        }

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "titulo" => "Facturas sem pagamentos correntes",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            'escola' => Shcool::findOrFail($this->escolarLogada()),
            "pagamentos" => $pagamentos,
            "divCorr" => $dc,
        ];

        $pdf = \PDF::loadView('admin.documentos.imprimir-facturas-sem-pagamentos-corrente', $headers);
        return $pdf->stream('imprimir-facturas-sem-pagamentos-corrente.pdf');
    }

    public function facturasSemPagamentosVencidas()
    {
        $user = auth()->user();

        if (!$user->can('read: factura')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $pagamentos = Pagamento::whereDate('data_vencimento', '<', $this->data_sistema())
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', '=', $this->anolectivoActivo())
            ->where('tipo_factura', '=', 'FT')
            ->where('status', '=', 'Pendente')
            ->get();

        $dividasVencida = Pagamento::whereDate('data_vencimento', '<', $this->data_sistema())
            ->where('status', '=', 'Pendente')
            ->where('tipo_factura', '=', 'FT')
            ->get();

        $dv = 0;

        if ($dividasVencida) {
            foreach ($dividasVencida as $d) {
                $dv = $dv + ($d->valor * $d->quantidade) + $d->multa;
            }
        }

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "titulo" => "Facturas sem pagamentos vencidas",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            "pagamentos" => $pagamentos,
            "divVenc" => $dv,
        ];

        $pdf = \PDF::loadView('admin.documentos.imprimir-facturas-sem-pagamentos-vencida', $headers);
        return $pdf->stream('imprimir-facturas-sem-pagamentos-vencida.pdf');
    }

    public function facturasSemPagamentosGeral()
    {
        $user = auth()->user();

        if (!$user->can('read: factura')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $pagamentos = Pagamento::whereDate('data_vencimento', '>=', $this->data_sistema())
            // ->whereDate('data_vencimento', '<=', $this->data_sistema())
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', '=', $this->anolectivoActivo())
            ->where('tipo_factura', '=', 'FT')
            ->where('status', '=', 'Pendente')
            ->get();

        $dc = 0;
        $dv = 0;
        $dividasVencida = Pagamento::whereDate('data_vencimento', '<', $this->data_sistema())->where('status', '=', 'Pendente')->where('tipo_factura', '=', 'FT')->select('quantidade', 'valor', 'multa')->get();
        $dividasCorrente = Pagamento::whereDate('data_vencimento', '>=', $this->data_sistema())->where('status', '=', 'Pendente')->where('tipo_factura', '=', 'FT')->select('quantidade', 'valor', 'multa')->get();
        if ($dividasVencida) {
            foreach ($dividasVencida as $d) {
                $dv = $dv + ($d->valor * $d->quantidade) + $d->multa;
            }
        }

        if ($dividasCorrente) {
            foreach ($dividasCorrente as $d) {
                $dc = $dc + ($d->valor * $d->quantidade) + $d->multa;
            }
        }

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "titulo" => "Facturas sem Pagamentos",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            "pagamentos" => $pagamentos,
            "divCorr" => $dc,
            "divVenc" => $dv,
        ];

        $pdf = \PDF::loadView('admin.documentos.imprimir-facturas-sem-pagamentos', $headers);
        return $pdf->stream('imprimir-facturas-sem-pagamentos.pdf');
    }

    public function create()
    {
        $user = auth()->user();

        if (!$user->can('create: factura')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $servicos = Servico::where([
            ['shcools_id', '=', $this->escolarLogada()],
        ])->get();

        $caixas = Caixa::where('shcools_id', '=', $this->escolarLogada())->get();
        $bancos = Banco::where('shcools_id', $this->escolarLogada())->get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Criar Documento",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "forma_pagamentos" => FormaPagamento::get(),
            "servicos" => $servicos,
            "caixas" => $caixas,
            "bancos" => $bancos,
        ];

        return view('admin.documentos.create', $headers);
    }

    public function carregar_cliente($status)
    {
        if ($status == "estudante") {
            $estudantes = Estudante::where([
                ['tb_estudantes.shcools_id', '=', $this->escolarLogada()],
                ['tb_estudantes.registro', '=', 'confirmado'],
                ['tb_matriculas.status_matricula', '=', 'confirmado'],
                ['tb_matriculas.ano_lectivos_id', '=', $this->anolectivoActivo()],
            ])
                ->join('tb_matriculas', 'tb_estudantes.id', '=', 'tb_matriculas.estudantes_id')
                ->select('tb_estudantes.nome AS nome', 'tb_estudantes.sobre_nome AS SobreNome', 'tb_estudantes.id', 'tb_estudantes.registro')
                ->orderBy('nome', 'ASC')
                ->get();
        } else if ($status == "escola") {
            $estudantes = Shcool::where([
                ['id', '=', $this->escolarLogada()],
            ])
                ->select('tb_shcools.nome', 'tb_shcools.documento AS SobreNome',  'id')
                ->get();
        }

        $option = "<option value=''>Selecione o Cliente</option>";
        foreach ($estudantes as $state) {
            $option .= '<option value="' . $state->id . '">' . $state->nome . ' ' . $state->SobreNome . '<option>';
        }
        return $option;
    }

    public function carregar_servico($destino, $estudante, $servico)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
        // $turmaId = Turma::findOrFail($turma);
        $servico = Servico::findOrFail($servico);
        $estudantes = Estudante::findOrFail($estudante);

        $ano = $this->anolectivoActivo();

        $turma_estudante_ano_selecionado = EstudantesTurma::where('estudantes_id', $estudantes->id)
            ->where('status', 'activo')
            ->where('ano_lectivos_id', $ano)
            ->select('tb_turmas_estudantes.turmas_id')
        ->first();

        // servicos da turma
        $servico = ServicoTurma::where('turmas_id', $turma_estudante_ano_selecionado->turmas_id)
            ->where('servicos_id', $servico->id)
            ->where('ano_lectivos_id', $ano)
            ->where('model', 'turmas')
            ->join('tb_servicos', 'tb_servicos_turma.servicos_id', '=', 'tb_servicos.id')
            ->select('tb_servicos.servico', 'tb_servicos.id', 'tb_servicos_turma.pagamento', 'tb_servicos_turma.preco', 'tb_servicos_turma.multa', 'tb_servicos_turma.desconto')
        ->first();

        if (!$servico) {
            return response()->json([
                "status" => 404,
                "message" => 'Nenhum serviço foi localizado nesta turma, ou seja esta serviço não esta cadastrado nesta turma'
            ]);
        }

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
                "message" => 'Este estudante esta sem cartão para este serviço'
            ]);
        }

        if ($servico) {
            return response()->json([
                "status" => 200,
                "servico" => $servico,
                "servico_turma" => $servico,
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
                "message" => 'Turno não Encontrado'
            ]);
        }

    }
}
