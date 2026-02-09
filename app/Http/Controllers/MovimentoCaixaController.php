<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Shcool;
use App\Models\web\salas\Caixa;
use App\Models\web\salas\MovimentoCaixa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;

class MovimentoCaixaController extends Controller
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

    public function abertura()
    {
        $user = auth()->user();

        if (!$user->can('abertura caixa')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $caixas = Caixa::where('status', '=', "activo")->where('shcools_id', '=', $this->escolarLogada())->where('usuario_id', '=', Auth::user()->id)->first();

        if ($caixas) {
            Alert::warning('Atenção', 'Não podes ter dois (2) caixas aberto, fecha o primeiro e so assim podes abrir outro caixa!');
            return redirect()->route('operacoes-caixas.fechamento');
        }



        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Abertura do Caixa",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "caixas" => Caixa::where([
                ['status', "desactivo"],
                ['shcools_id', $this->escolarLogada()]
            ])->get(),
        ];

        return view('admin.operacoes-caixas.abertura', $headers);
    }

    // cadastrar salas
    public function aberturacaixas(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('abertura caixa')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            "valor_inicial" => 'required',
            "caixa_id" => 'required',
        ]);

        if ($request->valor_inicial <= -1) {
            Alert::warning('Atenção', 'Valor Insuficiente para Abertura do Caixa!');
            return redirect()->back();
        }

        $create = MovimentoCaixa::create([
            "caixa_id" => $request->caixa_id,
            "status" => "aberto",
            "valor_abrir" => $request->valor_inicial,
            "valor_fecha" => "0",
            "valor_tpa" => 0,
            "valor_cache" => 0,
            "valor_retirado1" => 0,
            "valor_retirado2" => 0,
            "valor_retirado3" => 0,
            "qtd_itens" => "0",
            "data_abrir" => date("Y-m-d"),
            "data_fechar" => NULL,
            "usuario_id" => Auth::user()->id,
            "shcools_id" => $this->escolarLogada(),
        ]);

        $updated = Caixa::findOrFail($request->caixa_id);
        $updated->status = "activo";
        $updated->usuario_id = Auth::user()->id;
        $updated->update();

        session()->put('sessaoCaixa', $updated->id);

        Alert::success('Bom Trabalho', 'Abertura do Caixa realizado com sucesso!');

        return response()->json(['message' => "Caixa Aberto com sucesso", 'success' => true, 'redirect' => route('paineis.painel-informativo-administrativo')]);

        return redirect()->route('paineis.painel-informativo-administrativo');
    }

    public function fechamento()
    {
        $user = auth()->user();

        if (!$user->can('fecho caixa')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $caixas = Caixa::where('status', '=', "activo")->where('shcools_id', '=', $this->escolarLogada())->where('usuario_id', '=', Auth::user()->id)->first();

        if ($caixas == null) {
            Alert::warning('Atenção', 'Nenhum caixa está aberto no momento para fechamento. Por favor, abra um caixa para realizar as operações de tesouraria e, em seguida, proceda com o fechamento!');
            return redirect()->route('tesourarias.index');
        }



        $caixas = Caixa::where('status', '=', "activo")->where('shcools_id', '=', $this->escolarLogada())->where('usuario_id', '=', Auth::user()->id)->first();
        $movimento = null;
        if ($caixas) {
            $movimento = MovimentoCaixa::where('status', "aberto")
                ->where('caixa_id', $caixas->id)
                ->where('usuario_id', Auth::user()->id)
                ->where('shcools_id', $this->escolarLogada())
                ->with(['user_abrir', 'caixa'])
                ->first();
        }

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Fechamento do Caixa",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "caixas" => $caixas,
            "movimento" => $movimento,
        ];

        return view('admin.operacoes-caixas.fechamento', $headers);
    }

    // cadastrar salas
    public function fechamentocaixas(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('fecho caixa')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $validate = Validator::make($request->all(), [
            "valor_tpa" => 'required',
            "valor_cache" => 'required',
            "caixa_id" => 'required',
        ]);

        if ($request->input('valor_tpa') <= -1 or $request->input('valor_cache') <= -1) {
            return response()->json([
                'status' => 300,
                'message' => "Valores Insuficiente para Fechamento do Caixa Invalidos",
            ]);
        }

        $caixas = Caixa::where([
            ['status', '=', "activo"],
            ['usuario_id', '=', Auth::user()->id],
            ['shcools_id', '=', $this->escolarLogada()],
        ])->first();

        $movimentos = MovimentoCaixa::where([
            ['tb_movimento_caixas.status', '=', "aberto"],
            ['tb_movimento_caixas.caixa_id', '=', $caixas->id],
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
            ->first();

        if ($movimentos->valor_tpa != $request->input('valor_tpa')) {
            return response()->json([
                'status' => 300,
                'message' => "Existe um erro! O Saldo do TPA Processado pelo Sistema é diferente de Saldo Informado!",
            ]);
        }
        //
        // no processo das vendas
        $valorRetiradoCaixa1 = $movimentos->valor_retirado1 + $movimentos->valor_retirado2 + $movimentos->valor_retirado3;
        // no processo do fecho do caixa
        $valorRetiradoCaixa2 = $request->valor_retirado1 + $request->valor_retirado2 + $request->valor_retirado3;

        if ($valorRetiradoCaixa1 != $valorRetiradoCaixa2) {
            return response()->json([
                'status' => 300,
                'message' => "Existe uma diferença no valores retirados!\n Na Primeira Retirada foi: {$movimentos->valor_retirado1} O Valor do Lançamento para o fecho: {$request->valor_retirado1}
                \n Na Segunda Retirada foi: {$movimentos->valor_retirado2} O Valor do Lançamento para o fecho: {$request->valor_retirado2} \n
                Na Terceira Retirada foi: {$movimentos->valor_retirado3} O Valor do Lançamento para o fecho: {$request->valor_retirado3}",
            ]);
        }

        if ($movimentos->valor_cache != $request->input('valor_cache')) {
            return response()->json([
                'status' => 300,
                'message' => "Existe um erro! O Saldo do CACHE Processado pelo Sistema é diferente de Saldo Informado!",
            ]);
        }

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        } else {
            $create = MovimentoCaixa::findOrFail($movimentos->id);
            $create->caixa_id = $request->caixa_id;
            $create->data_fechar = date("Y-m-d");
            $create->status = "fechado";
            $create->usuario_fechar_id = Auth::user()->id;
            $create->observacao = $request->observacao;

            if ($create->update()) {
                $updated = Caixa::findOrFail($request->caixa_id);
                $updated->status = "desactivo";
                $updated->usuario_id = NULL;
                $updated->update();

                session()->forget('sessaoCaixa');
                return response()->json([
                    'status' => 200,
                    'message' => 'Dados salvos com sucesso!',
                    "usuario" => User::findOrFail(Auth::user()->id),
                    "movimento_id" => Crypt::encrypt($movimentos->id),
                ]);
            }

            return response()->json([
                'status' => 300,
                'message' => "Não Foi possível Abrir o Caixa",
            ]);
        }
    }


    public function retirarValoresCaixa($id)
    {
        $user = auth()->user();

        if (!$user->can('retirar valores caixa')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $movimento = MovimentoCaixa::findOrFail(Crypt::decrypt($id));
        $caixa = Caixa::findOrFail($movimento->caixa_id);




        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Sagriamento do Caixa",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "movimento" => $movimento,
            "caixa" => $caixa,
            "movimento_id" => $id,
        ];

        return view('admin.operacoes-caixas.retirar-valores', $headers);
    }


    // cadastrar salas
    public function retirarValoresCaixaPost(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('retirar valores caixa')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            'caixa_id' => 'required',
            'valor_retirado1' => 'required',
            'motivo_retirar1' => 'required',
            'valor_retirado2' => 'required',
            'motivo_retirar2' => 'required',
            'valor_retirado3' => 'required',
            'motivo_retirar3' => 'required',
        ]);


        $create = MovimentoCaixa::findOrFail($request->movimento_id);
        if ($create->valor_cache < ($request->valor_retirado1 + $request->valor_retirado2 + $request->valor_retirado3)) {
            Alert::warning('Atenção', "O valor arrecadado em Cash é inferior que oa valor que pretende retirar no caixa Sr(º) $user->nome !");
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


    public function reeniciarCaixas()
    {
        $user = auth()->user();

        if (!$user->can('reeniciar caixa')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $caixas = Caixa::where('status', "activo")->where('usuario_id', Auth::user()->id)->where('shcools_id', $this->escolarLogada())->get();

        if ($caixas) {
            foreach ($caixas as $key) {
                $update = Caixa::findOrFail($key->id);
                $update->status = "desactivo";
                $update->usuario_id = NULL;

                $movimentos = MovimentoCaixa::where('status', '=', "aberto")
                    ->where('caixa_id', '=', $key->id)
                    ->where('shcools_id', '=', $this->escolarLogada())
                    ->get();

                if ($movimentos) {
                    foreach ($movimentos as $value) {
                        $updateMovimento = MovimentoCaixa::findOrFail($value->id);
                        $updateMovimento->data_fechar = date("Y-m-d");
                        $updateMovimento->status = "fechado";
                        $updateMovimento->usuario_fechar_id = Auth::user()->id;
                        $updateMovimento->update();
                    }
                }
                $update->update();
            }

            session()->forget('sessaoCaixa');

            Alert::success("Bom Trabalho", "Caixas Reeniciados do Sucesso!");
            return redirect()->route('paineis.painel-informativo-administrativo');
        }

        Alert::warning("Informação", "Nenhuma caixa disponível para serve reenciado. Obrigado!");
    }

    public function movimentoscaixas()
    {
        $user = auth()->user();

        if (!$user->can('movimento caixa diario')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Movimentos do Caixa",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),

            "caixas" => MovimentoCaixa::where('status', '=', "aberto")
                ->where('usuario_id', '=', Auth::user()->id)
                ->where('shcools_id', '=', $this->escolarLogada())
                ->with(['user_abrir', 'caixa', 'escola'])
                ->first(),
        ];

        return view('admin.operacoes-caixas.movimentos', $headers);
    }

    public function movimentoscaixasOutro(Request $request)
    {

        $user = auth()->user();

        if (!$user->can('movimento caixa')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $movimentos = MovimentoCaixa::when($request->data_inicio, function ($query, $value) {
            $query->where('data_abrir', '>=', Carbon::parse($value));
        })
            ->when($request->data_final, function ($query, $value) {
                $query->where('data_abrir', '<=', Carbon::parse($value));
            })
            ->when($request->operador_id, function ($query, $value) {
                $query->where('usuario_id', $value);
            })
            ->when($request->caixa_id, function ($query, $value) {
                $query->where('caixa_id', $value);
            })
            ->where('usuario_id', Auth::user()->id)
            ->where('shcools_id', $this->escolarLogada())
            ->with(['user_abrir', 'caixa', 'escola'])
            ->get();


        $headers = [

            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "caixas" => Caixa::where('shcools_id', $this->escolarLogada())->get(),
            
            "titulo" => "Movimentos do Caixa",
            "descricao" => env('APP_NAME'),
            "operadores" => User::whereIn('acesso', ['user', 'secretario', 'financeiro', 'admin'])->where('shcools_id', $this->escolarLogada())->where('level2', '4')->get(),
            "usuario" => User::findOrFail(Auth::user()->id),

            "movimentos" => $movimentos,

            "requests" => $request->all('data_inicio', 'data_final', 'operador_id', 'caixa_id')
        ];

        return view('admin.operacoes-caixas.movimentos-outro', $headers);
    }


    public function imprimir(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: caixa')  && !$user->can('update: caixa')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $data_inicio = Crypt::decrypt($request->data_inicio);
        $data_final = Crypt::decrypt($request->data_inicio);
        $operador_id = Crypt::decrypt($request->operador_id);
        $caixa_id = Crypt::decrypt($request->caixa_id);

        $movimentos = MovimentoCaixa::when($data_inicio, function ($query, $value) {
            $query->where('data_abrir', '>=', Carbon::parse($value));
        })
            ->when($data_final, function ($query, $value) {
                $query->where('data_abrir', '<=', Carbon::parse($value));
            })
            ->when($operador_id, function ($query, $value) {
                $query->where('usuario_id', $value);
            })
            ->when($caixa_id, function ($query, $value) {
                $query->where('caixa_id', $value);
            })
            ->where('usuario_id', Auth::user()->id)
            ->where('shcools_id', $this->escolarLogada())
            ->with(['user_abrir', 'caixa', 'escola'])
            ->get();

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            
            "titulo" => "MOVIMENTOS DO CAIXA",

            "caixa" => Caixa::find(Crypt::decrypt($request->caixa_id)),
            "operador" => User::find(Crypt::decrypt($request->operador_id)),

            "movimentos" => $movimentos,
            "data_inicio" => Crypt::decrypt($request->data_inicio),
            "data_final" => Crypt::decrypt($request->data_final),
        ];

        $pdf = \PDF::loadView('downloads.relatorios.movimentos-caixa', $headers)->setPaper('A4', 'landscape');
        return $pdf->stream('lista-estudantes.pdf');
    }

    public function imprimirMovimentoCaixa($id)
    {
        $user = auth()->user();

        if (!$user->can('read: caixa')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $movimento = MovimentoCaixa::where([
            ['id', '=', Crypt::decrypt($id)],
        ])
            ->where('usuario_id', '=', Auth::user()->id)
            ->where('shcools_id', '=', $this->escolarLogada())
            ->with(['user_abrir', 'caixa', 'escola'])
            ->first();

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            
            "titulo" => "RELATÓRIO DO MOVIMENTO DO CAIXA",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "escola" => Shcool::find($this->escolarLogada()),
            "movimento" => $movimento,
        ];

        $pdf = \PDF::loadView('downloads.relatorios.imprimir-movimentos-caixa', $headers);
        return $pdf->stream('imprimir-movimentos-caixa.pdf');
    }
}
