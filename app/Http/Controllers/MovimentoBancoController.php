<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Shcool;
use App\Models\web\salas\MovimentoCaixa;
use App\Models\web\salas\Banco;
use App\Models\web\salas\MovimentoBanco;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;

class MovimentoBancoController extends Controller
{
    use TraitHelpers;


    public function __construct()
    {
        $this->middleware('auth');
    }

    // --------------------------------------------------------------------------------------
    // ----------------------------------START MOVIMENTO -----------------------------------------
    // --------------------------------------------------------------------------------------
    // --------------------------------------------------------------------------------------

    public function index()
    {
        $user = auth()->user();

        if (!$user->can('abertura banco')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $caixas = Banco::where('status', '=', "activo")->where('shcools_id', '=', $this->escolarLogada())->where('usuario_id', '=', Auth::user()->id)->first();

        if ($caixas) {
            Alert::warning('Atenção', 'Não podes ter dois (2) TPA aberto, fecha o primeiro e so assim podes abrir outro TPA!');
            return redirect()->back();
        }



        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Abertura do TPA",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "bancos" => Banco::where([
                ['status', '=', "desactivo"],
                ['shcools_id', '=', $this->escolarLogada()]
            ])->get(),
        ];

        return view('admin.movimento-bancos.abertura', $headers);
    }

    // abertura store
    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('abertura banco')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            "valor_inicial" => 'required',
            "banco_id" => 'required',
        ], [
            "valor_inicial.required" => "Campo Obrigatório",
            "banco_id.required" => "Campo Obrigatório",
        ]);

        if ($request->valor_inicial <= -1) {
            Alert::warning('Atenção', 'Valor Insuficiente para Abertura do TPA!');
            return redirect()->back();
        }

        MovimentoBanco::create([
            "banco_id" => $request->banco_id,
            "status" => "aberto",
            "valor_abrir" => $request->valor_inicial,
            "valor_fecha" => "0",
            "valor_tpa" => 0,
            "valor_retirado1" => 0,
            "valor_retirado2" => 0,
            "valor_retirado3" => 0,
            "qtd_itens" => "0",
            "data_abrir" => date("Y-m-d"),
            "data_fechar" => NULL,
            "usuario_id" => Auth::user()->id,
            "shcools_id" => $this->escolarLogada(),
        ]);

        $updated = Banco::findOrFail($request->banco_id);
        $updated->status = "activo";
        $updated->usuario_id = Auth::user()->id;
        $updated->update();

        session()->put('sessaoBanco', $updated->id);

        Alert::success('Bom Trabalho', 'Abertura do TPA realizado com sucesso!');
        return redirect()->route('paineis.painel-informativo-administrativo');
    }

    // fechamento de Bancos create
    public function fechamento()
    {
        $user = auth()->user();

        if (!$user->can('fecho banco')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $banco = Banco::where('status', '=', "activo")->where('shcools_id', '=', $this->escolarLogada())->where('usuario_id', '=', Auth::user()->id)->first();
        $movimento = null;

        if ($banco) {
            $movimento = MovimentoBanco::where('status', "aberto")
                ->where('banco_id', $banco->id)
                ->where('usuario_id', Auth::user()->id)
                ->where('shcools_id', $this->escolarLogada())
                ->with(['user_abrir', 'banco'])
                ->first();
        } else {
            Alert::error('Acesso restrito', 'Não podes desactivar nenhum TPA neste momento, porque não tem nenhum TPA activo!');
            return redirect()->back();
        }

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Fechamento do TPA",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "banco" => $banco,
            "movimento" => $movimento,
        ];

        return view('admin.movimento-bancos.fechamento', $headers);
    }

    // fechamento de Bancos store
    public function fechamentobanco(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('fecho banco')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $validate = Validator::make($request->all(), [
            "valor_tpa" => 'required',
            "banco_id" => 'required',
        ], [
            "valor_tpa.required" => "Campo Obrigatório",
            "banco_id.required" => "Campo Obrigatório",
        ]);

        if ($request->input('valor_tpa') <= -1) {
            return response()->json([
                'status' => 300,
                'message' => "Valores Insuficiente para Fechamento do Banco Invalidos",
            ]);
        }

        $banco = Banco::where([
            ['status', '=', "activo"],
            ['usuario_id', '=', Auth::user()->id],
            ['shcools_id', '=', $this->escolarLogada()],
        ])->first();

        $movimentos = MovimentoBanco::where('status', '=', "aberto")
            ->where('banco_id', '=', $banco->id)
            ->where('usuario_id', '=', Auth::user()->id)
            ->where('shcools_id', '=', $this->escolarLogada())
            ->with(['banco'])
            ->first();

        if ($movimentos->valor_tpa != $request->input('valor_tpa')) {
            return response()->json([
                'status' => 300,
                'message' => "Existe um erro! O Saldo do TPA Processado pelo Sistema é diferente de Saldo Informado!",
            ]);
        }
        // no processo das vendas
        $valorRetiradoBanco1 = $movimentos->valor_retirado1 + $movimentos->valor_retirado2 + $movimentos->valor_retirado3;
        // no processo do fecho do caixa
        $valorRetiradoBanco2 = $request->valor_retirado1 + $request->valor_retirado2 + $request->valor_retirado3;

        if ($valorRetiradoBanco1 != $valorRetiradoBanco2) {
            return response()->json([
                'status' => 300,
                'message' => "Existe uma diferença no valores retirados!\n Na Primeira Retirada foi: {$movimentos->valor_retirado1} O Valor do Lançamento para o fecho: {$request->valor_retirado1}
                \n Na Segunda Retirada foi: {$movimentos->valor_retirado2} O Valor do Lançamento para o fecho: {$request->valor_retirado2} \n
                Na Terceira Retirada foi: {$movimentos->valor_retirado3} O Valor do Lançamento para o fecho: {$request->valor_retirado3}",
            ]);
        }

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        } else {
            $create = MovimentoBanco::findOrFail($movimentos->id);
            $create->banco_id = $request->banco_id;
            $create->data_fechar = date("Y-m-d");
            $create->status = "fechado";
            $create->usuario_fechar_id = Auth::user()->id;
            $create->observacao = $request->observacao;

            if ($create->update()) {
                $updated = Banco::findOrFail($request->banco_id);
                $updated->status = "desactivo";
                $updated->usuario_id = NULL;
                $updated->update();

                session()->forget('sessaoBanco');

                return response()->json([
                    'status' => 200,
                    'message' => 'Dados salvos com sucesso!',
                    "usuario" => User::findOrFail(Auth::user()->id),
                    "movimento_id" => Crypt::encrypt($movimentos->id),
                ]);
            }

            return response()->json([
                'status' => 300,
                'message' => "Não Foi possível Abrir o Banco",
            ]);
        }
    }

    public function retirarValoresBanco($id)
    {
        $user = auth()->user();

        if (!$user->can('retirar valores banco')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $movimento = MovimentoBanco::findOrFail(Crypt::decrypt($id));

        $banco = Banco::findOrFail($movimento->banco_id);



        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Sagriamento do Banco",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "movimento" => $movimento,
            "banco" => $banco,
            "movimento_id" => $id,
        ];

        return view('admin.movimento-bancos.retirar-valores', $headers);
    }

    // cadastrar salas
    public function retirarValoresBancoPost(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('retirar valores banco')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            'banco_id' => 'required',
            'valor_retirado1' => 'required',
            // 'motivo_retirar1' => 'required',
            'valor_retirado2' => 'required',
            // 'motivo_retirar2' => 'required',
            'valor_retirado3' => 'required',
            // 'motivo_retirar3' => 'required',
        ], [

            'banco_id.required' => 'Campo Obrigatório',
            'valor_retirado1.required' => 'Campo Obrigatório',
            // 'motivo_retirar1.required' => 'Campo Obrigatório',
            'valor_retirado2.required' => 'Campo Obrigatório',
            // 'motivo_retirar2.required' => 'Campo Obrigatório',
            'valor_retirado3.required' => 'Campo Obrigatório',
            // 'motivo_retirar3.required' => 'Campo Obrigatório',
        ]);

        $create = MovimentoBanco::findOrFail(Crypt::decrypt($request->movimento_id));

        if ($create->valor_tpa < ($request->valor_retirado1 + $request->valor_retirado2 + $request->valor_retirado3)) {
            Alert::warning('Atenção', "O valor arrecadado no TPA é inferior que o valor que pretende retirar, Sr(º) $user->nome !");
            return redirect()->back();
        }

        $create->valor_retirado1 = $request->valor_retirado1;
        $create->valor_retirado2 = $request->valor_retirado2;
        $create->valor_retirado3 = $request->valor_retirado3;

        $create->motivo_retirar1 = $request->motivo_retirar1;
        $create->motivo_retirar2 = $request->motivo_retirar2;
        $create->motivo_retirar3 = $request->motivo_retirar3;

        if ($create->update()) {
            Alert::success('Bom trabalho', "Dados salvos com sucesso!");
            return redirect()->back();
        }

        Alert::warning('Atenção', "Não foi possível retirar valor no caixa Sr(º) $user->nome !");
        return redirect()->back();
    }

    // public function reeniciarCaixas()
    // {
    //     $user = auth()->user();

    //     if(!$user->can('reeniciar caixa')){
    //         Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
    //         return redirect()->back();
    //     }

    //     $caixas = Caixa::where('status', "activo")->where('usuario_id', Auth::user()->id)->where('shcools_id', $this->escolarLogada())->get();

    //     if($caixas){
    //         foreach ($caixas as $key) {
    //             $update = Caixa::findOrFail($key->id);
    //             $update->status = "desactivo";
    //             $update->usuario_id = NULL;

    //             $movimentos = MovimentoCaixa::where('status', '=', "aberto")
    //             ->where('caixa_id', '=', $key->id)
    //             ->where('shcools_id', '=', $this->escolarLogada())
    //             ->get();

    //             if($movimentos){
    //                 foreach ($movimentos as $value) {
    //                     $updateMovimento = MovimentoCaixa::findOrFail($value->id);
    //                     $updateMovimento->data_fechar = date("Y-m-d");
    //                     $updateMovimento->status = "fechado";
    //                     $updateMovimento->usuario_fechar_id = Auth::user()->id;
    //                     $updateMovimento->update();
    //                 }
    //             }
    //             $update->update();
    //         }

    //         session()->forget('sessaoCaixa');

    //         Alert::success("Bom Trabalho", "Caixas Reeniciados do Sucesso!");
    //         return redirect()->route('paineis.painel-informativo-administrativo');
    //     }

    //     Alert::warning("Informação", "Nenhuma caixa disponível para serve reenciado. Obrigado!");

    // }

    public function movimentosBancos()
    {
        $user = auth()->user();

        if (!$user->can('movimento banco diario')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Movimentos do Banco",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),

            "movimento" => MovimentoBanco::where('status', '=', "aberto")
                ->where('usuario_id', '=', Auth::user()->id)
                ->where('shcools_id', '=', $this->escolarLogada())
                ->with(['user_abrir', 'banco', 'escola'])
                ->first(),
        ];

        return view('admin.movimento-bancos.movimentos', $headers);
    }

    public function movimentosBancosOutro(Request $request)
    {

        $user = auth()->user();

        if (!$user->can('movimento banco')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $movimentos = MovimentoBanco::when($request->data_inicio, function ($query, $value) {
            $query->where('data_abrir', '>=', Carbon::parse($value));
        })
            ->when($request->data_final, function ($query, $value) {
                $query->where('data_abrir', '<=', Carbon::parse($value));
            })
            ->when($request->operador_id, function ($query, $value) {
                $query->where('usuario_id', $value);
            })
            ->when($request->banco_id, function ($query, $value) {
                $query->where('banco_id', $value);
            })
            ->where('usuario_id', '=', Auth::user()->id)
            ->where('shcools_id', '=', $this->escolarLogada())
            ->with(['user_abrir', 'banco', 'escola'])
            ->get();


        $headers = [

            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "bancos" => Banco::where('shcools_id', $this->escolarLogada())->get(),
            
            "titulo" => "Movimentos do Banco",
            "descricao" => env('APP_NAME'),
            "operadores" => User::whereIn('acesso', ['user', 'secretario', 'financeiro', 'admin'])->where('shcools_id', $this->escolarLogada())->where('level2', '4')->get(),
            "usuario" => User::findOrFail(Auth::user()->id),

            "movimentos" => $movimentos,

            "requests" => $request->all('data_inicio', 'data_final', 'operador_id', 'banco_id')
        ];

        return view('admin.movimento-bancos.movimentos-outro', $headers);
    }

    // imprimir
    public function imprimir(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: banco')  && !$user->can('update: banco')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $data_inicio = Crypt::decrypt($request->data_inicio);
        $data_final = Crypt::decrypt($request->data_inicio);
        $operador_id = Crypt::decrypt($request->operador_id);
        $banco_id = Crypt::decrypt($request->banco_id);



        $movimentos = MovimentoBanco::when($data_inicio, function ($query, $value) {
            $query->where('created_at', '>=', Carbon::parse($value));
        })
            ->when($data_final, function ($query, $value) {
                $query->where('created_at', '<=', Carbon::parse($value));
            })
            ->when($operador_id, function ($query, $value) {
                $query->where('usuario_id', $value);
            })
            ->when($banco_id, function ($query, $value) {
                $query->where('banco_id', $value);
            })
            ->where('usuario_id', '=', Auth::user()->id)
            ->where('shcools_id', '=', $this->escolarLogada())
            ->with(['user_abrir', 'banco', 'escola'])
            ->get();

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            
            "titulo" => "MOVIMENTOS DO BANCO",

            "banco" => Banco::find(Crypt::decrypt($request->banco_id)),
            "operador" => User::find(Crypt::decrypt($request->operador_id)),

            "movimentos" => $movimentos,
            "data_inicio" => Crypt::decrypt($request->data_inicio),
            "data_final" => Crypt::decrypt($request->data_final),
        ];

        $pdf = \PDF::loadView('downloads.relatorios.movimentos-bancos', $headers)->setPaper('A4', 'landscape');
        return $pdf->stream('movimentos-do-banco.pdf');
    }


    public function informacaocaixa($id)
    {
        $user = auth()->user();

        if (!$user->can('read: caixa')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Movimentos do Caixa",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "caixas" => MovimentoCaixa::where([
                ['tb_movimento_caixas.id', '=', $id],
                ['tb_movimento_caixas.status', '=', "aberto"],
                ['tb_movimento_caixas.data_abrir', '=', date("Y-m-d")],
                ['tb_movimento_caixas.usuario_id', '=', Auth::user()->id],
                ['tb_movimento_caixas.shcools_id', '=', $this->escolarLogada()]
            ])
                ->join('tb_caixas', 'tb_movimento_caixas.caixa_id', '=', 'tb_caixas.id')
                ->select(
                    'tb_movimento_caixas.id',
                    'tb_caixas.caixa',
                    'tb_movimento_caixas.status',
                    'tb_movimento_caixas.valor_abrir',
                    'tb_movimento_caixas.valor_tpa',
                    'tb_movimento_caixas.valor_cache',
                    'tb_movimento_caixas.valor_retirado1',
                    'tb_movimento_caixas.valor_retirado2',
                    'tb_movimento_caixas.valor_retirado3',
                    'tb_movimento_caixas.data_abrir',
                )
                ->first(),
        ];

        return view('admin.movimento-bancos.informacoes-caixa', $headers);
    }

    public function imprimirMovimentoBanco($id)
    {
        $user = auth()->user();

        if (!$user->can('read: banco')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            
            "titulo" => "RELATÓRIO DO MOVIMENTO DO BANCO",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "escola" => Shcool::find($this->escolarLogada()),
            "movimento" => MovimentoBanco::where('id', '=', Crypt::decrypt($id))
                ->where('usuario_id', '=', Auth::user()->id)
                ->where('shcools_id', '=', $this->escolarLogada())
                ->with(['user_abrir', 'banco', 'escola'])
                ->first(),
        ];

        $pdf = \PDF::loadView('downloads.relatorios.imprimir-movimentos-banco', $headers);
        return $pdf->stream('imprimir-movimentos-banco.pdf');
    }
}
