<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\calendarios\Pagamento;
use App\Models\web\calendarios\Servico;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\FormaPagamento;
use App\Models\Shcool;
use Carbon\Carbon;

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

        if (!$request->ano_lectivo_id) {
            $request->ano_lectivo_id = $this->anolectivoActivo();
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

    public function export(Request $request)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // 4 GB

        if (!$request->ano_lectivo_id) {
            $request->ano_lectivo_id = $this->anolectivoActivo();
        }

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        $pagamentos = Pagamento::when($request->data_inicio, function ($query, $value) {
            $query->whereDate('data_at', '>=', Carbon::createFromDate($value));
        })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('data_at', '<=', Carbon::createFromDate($value));
            })
            ->when($request->ano_lectivo_id, function ($query, $value) {
                $query->where('ano_lectivos_id', $value);
            })
            ->when($request->forma_pagamento_id, function ($query, $value) {
                $query->where('pagamento_id', $value);
            })
            ->when($request->servico_id, function ($query, $value) {
                $query->where('servicos_id', $value);
            })
            ->when($request->type, function ($query, $value) {
                $query->where('caixa_at', $value);
            })
            ->with(['servico', 'operador'])
        ->get();

        $titulo = "LISTA DE PAGAMENTOS A PAGAR";

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);
        
        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "titulo" => $titulo,
            "verAnoLectivoActivo" => AnoLectivo::find($request->ano_lectivo_id),
            "pagamentos" => $pagamentos,
            "servico" => Servico::find($request->servico_id),
            "pagamentos" => $pagamentos,
            "requests" => $request->all('data_inicio', 'data_final', 'all'),
        ];
                
        $documentType = $request->documentType;
        
        if($documentType === 'excel') {
            //return Excel::download(new SalaExport, 'salas.xlsx');
        } else {
            $pdf = \PDF::loadView('downloads.financeiros.ficha-pagamentos-pagar', $headers); //->setPaper('A4', 'landscape');
            return $pdf->stream('turmas.financeiros.ficha-pagamentos-pagar.pdf');
        }
    }
    

}
