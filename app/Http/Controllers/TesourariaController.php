<?php

namespace App\Http\Controllers;

use App\Models\User;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\calendarios\Pagamento;
use App\Models\web\salas\Caixa;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class TesourariaController extends Controller
{
    use TraitChavesSaft;
    use TraitHelpers;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: pagamento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        
         
        $caixas = Caixa::where('status', '=', "activo")
            ->where('shcools_id', '=', $this->escolarLogada())
            ->where('usuario_id', '=', Auth::user()->id)
            ->first();
            
        if (!$request->ano_lectivo_id) {
            $request->ano_lectivo_id = $this->anolectivoActivo();
        }
        
        $pagamentos = [];
        
        $request->data_inicio = date("Y-m-d");
        $request->data_final = date("Y-m-d");
        
        if($caixas) {
                    
            $pagamentos = Pagamento::when($request->data_inicio, function ($query, $value) {
                $query->where('data_at', '>=', Carbon::parse($value));
            })
            ->when($request->data_final, function ($query, $value) {
                $query->where('data_at', '<=', Carbon::parse($value));
            })
            ->when($request->servico_id, function ($query, $value) {
                $query->where('servicos_id', $value);
            })
            ->when($request->ano_lectivo_id, function ($query, $value) {
                $query->where('ano_lectivos_id', $value);
            })
            ->when($request->forma_pagamento_id, function ($query, $value) {
                $query->where('pagamento_id', '=', $value);
            })
            ->when($request->type_id, function ($query, $value) {
                $query->where('caixa_at', '=', $value);
            })
            ->when($request->caixa_d, function ($query, $value) {
                $query->where('caixa_at', '=', $value);
            })
            ->where('caixa_id', $caixas->id)
            ->where('funcionarios_id', Auth::user()->id)
            ->where('status', '=', 'Confirmado')
            ->with(['operador', 'servico'])
            ->get();
        }
        
        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            'caixas' =>  $caixas,
            'pagamentos' =>  $pagamentos,
            "titulo" => "Painel da Tesouraria",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "ano_lectivo_id" => AnoLectivo::findOrFail($this->anolectivoActivo()),
            'filtro' => $request->all('data_inicio', 'data_final', 'servico_id', 'forma_pagamento_id', 'ano_lectivo_id', 'type_id'),
        ];

        return view('admin.tesouraria.index', $headers);
    }

}
