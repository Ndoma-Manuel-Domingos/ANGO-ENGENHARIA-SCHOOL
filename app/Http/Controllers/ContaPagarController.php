<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\calendarios\Pagamento;
use App\Models\web\calendarios\Servico;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\FormaPagamento;
use App\Models\Shcool;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;


class ContaPagarController extends Controller
{
    use TraitChavesSaft;
    use TraitHelpers;

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    // conta a pagar
    public function home(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: pagamento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Conta a pagar",
            "servicos" => Servico::where('contas', 'receita')->where('shcools_id', $this->escolarLogada())->get(),
            "anos_lectivos" => AnoLectivo::where('shcools_id', $this->escolarLogada())->get(),
            "forma_pagamentos" => FormaPagamento::where('status_id', 1)->get(),
        ];

        return view('admin.financeiros.contas-pagar.home', $headers);
    }
    
    // conta a pagar
    public function index(Request $request)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '2048M'); // ou mais se necessário

        $user = auth()->user();

        if (!$user->can('read: pagamento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        if (!$request->ano_lectivo) {
            $request->ano_lectivo = $this->anolectivoActivo();
        }
        
        $paginacao = $request->paginacao ?? 5;

        $query = Pagamento::when($request->ano_lectivo_id, function ($query, $value) {
            $query->where('ano_lectivos_id', $value);
        })
            ->when($request->servico_id, function ($query, $value) {
                $query->where('servicos_id', $value);
            })
            ->when($request->forma_pagamento_id, function ($query, $value) {
                $query->where('pagamento_id', $value);
            })
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate('data_at', '>=', Carbon::createFromDate($value));
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('data_at', '<=', Carbon::createFromDate($value));
            })
            ->where('status', '=', 'Confirmado')
            ->whereIn('caixa_at', ['despesa'])
            ->with(['operador', 'ano', 'servico'])
        ->where('shcools_id', $this->escolarLogada());


        return response()->json(
            $query->orderByDesc('id')->paginate($paginacao)
        );

    }

}
