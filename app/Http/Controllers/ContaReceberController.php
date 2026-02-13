<?php

namespace App\Http\Controllers;

use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\calendarios\Servico;
use App\Models\web\financeiros\DetalhesPagamentoPropina;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\FormaPagamento;
use App\Models\Shcool;
use App\Models\web\calendarios\Pagamento;
use Carbon\Carbon;


class ContaReceberController extends Controller
{
    use TraitChavesSaft;
    use TraitHelpers;


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function home()
    {
        $user = auth()->user();

        if (!$user->can('read: pagamento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
   
        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Contas a Receber",
            "servicos" => Servico::where('contas', 'receita')->where('shcools_id', $this->escolarLogada())->get(),
            "anos_lectivos" => AnoLectivo::where('shcools_id', $this->escolarLogada())->get(),
            "forma_pagamentos" => FormaPagamento::where('status_id', 1)->get(),
        ];

        return view('admin.financeiros.contas-receber.home', $headers);
    }

    public function index(Request $request)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '2048M'); // ou mais se necessário

        if (!$request->ano_lectivo) {
            $request->ano_lectivo = $this->anolectivoActivo();
        }

        $user = auth()->user();

        if (!$user->can('read: pagamento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $paginacao = $request->paginacao ?? 5;

        $query = DetalhesPagamentoPropina::when($request->designacao_geral, function ($query, $value) {
            $query->where(function ($q) use ($value) {
                // campo da própria tabela
                //$q->where('total_vagas', 'like', "%{$value}%")
        
                // campo da tabela classe
                $q->orWhereHas('pagamento', function ($qc) use ($value) {
                    $qc->where('next_factura', 'like', "%{$value}%");
                })
                ->orWhereHas('servico', function ($qc) use ($value) {
                    $qc->where('servico', 'like', "%{$value}%");
                })
                // campo da tabela ensino (classe->ensino)
                ->orWhereHas('pagamento.estudante', function ($qe) use ($value) {
                    $qe->where('nome', 'like', "%{$value}%");
                })
                // campo da tabela ensino (classe->ensino)
                ->orWhereHas('pagamento.estudante', function ($qe) use ($value) {
                    $qe->where('sobre_nome', 'like', "%{$value}%");
                });
            });
        })->with(["pagamento.estudante", "pagamento.operador", "servico"])
            ->when($request->ano_lectivo_id, function ($query, $value) {
                $query->where('ano_lectivos_id', $value);
            })
            ->whereHas('pagamento', function ($q) use ($request) {
                $q->where('caixa_at', 'receita')
                    ->where('status', 'Confirmado');

                $q->when($request->forma_pagamento_id, function ($query, $value) {
                    $query->where('pagamento_id', $value);
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
            ->where('shcools_id', $this->escolarLogada());

        return response()->json(
            $query->orderByDesc('id')->paginate($paginacao)
        );
    }
   
    public function show($id)
    {
        return Pagamento::with(["operador", "ano", "servico", "estudante", "items"])->findOrFail($id);
    }
    
}
