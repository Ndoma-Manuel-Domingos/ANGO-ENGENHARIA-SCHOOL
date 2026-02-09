<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Shcool;
use App\Models\web\salas\Banco;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class BancoController extends Controller
{
    use TraitHelpers;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function home()
    {
        $user = auth()->user();

        if (!$user->can('read: banco')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Lista dos Bancos",
            "descricao" => env('APP_NAME'),
        ];

        return view('admin.bancos.home', $headers);
    }
        
    public function index(Request $request)
    {
        $user = auth()->user();
    
        if (!$user->can('read: banco')) {
            Alert::error(
                'Acesso restrito',
                'Você não possui permissão para esta operação, por favor, contacte o administrador!'
            );
            return redirect()->back();
        }
    
        $paginacao = $request->paginacao ?? 5;
    
        $query = Banco::when($request->designacao_geral, function ($query, $value) {
            $query->where(function ($q) use ($value) {
                foreach (['banco', 'numero_conta', 'iban', 'conta', 'status'] as $field) {
                    $q->orWhere($field, 'like', "%{$value}%");
                }
            });
        })
        ->when($request->data_status, function($query, $value) {
            $query->where('status', $value);
        })->where('shcools_id', $this->escolarLogada());
    
    
        return response()->json(
            $query->orderByDesc('id')->paginate($paginacao)
        );
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('create: banco')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            "designacao" => 'required',
            "status" => 'required',
        ]);
        
                        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
    
            $verificarBanco = Banco::where('banco', $request->designacao)
                ->where('shcools_id', $this->escolarLogada())
            ->first();
    
            if ($verificarBanco) {
                return response()->json([
                    'status' => 300,
                    'message' => "Este Banco já Esta Cadastrado!",
                ]);
            }
    
            $verifica_conta_contabilidade = Banco::where('shcools_id', $this->escolarLogada())
                ->where('conta', 'like', "{$request->conta}%")
                ->count();
    
            $nova_conta = $request->conta . $verifica_conta_contabilidade + 1;
    
            Banco::create([
                'ordem' => $verifica_conta_contabilidade + 1,
                'conta' => $nova_conta,
                'numero_conta' => $request->numero_conta,
                'iban' => $request->iban,
                'banco' => $request->designacao,
                'status' => $request->status,
                'shcools_id' => $this->escolarLogada(),
                'usuario_id' => NULL,
            ]);
            
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
        ]);
    }

    public function edit($id)
    {
        $user = auth()->user();

        if (!$user->can('update: banco')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        return Banco::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user->can('update: banco')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            "designacao" => 'required',
            "status" => 'required',
        ]);
  
        $update = Banco::findOrFail($id);
                                
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
    
            $update->banco = $request->designacao;
            $update->numero_conta = $request->numero_conta;
            $update->iban = $request->iban;
            $update->status = $request->status;
            $update->update();
          
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
            'message' => 'Dados ACtualizados com sucesso!',
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    
    }

    public function destroy($id)
    {
        $user = auth()->user();

        if (!$user->can('delete: banco')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
                                        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            Banco::findOrFail($id)->delete();
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
            "usuario" => User::findOrFail(Auth::user()->id),
            'message' => 'Dados Excluido com sucesso!',
        ]);
    }

    public function export(Request $request)
    {
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "titulo" => "LISTA DOS BANCOS",
            "bancos" => Banco::where('shcools_id', $this->escolarLogada())->get()
        ];
        
                     
        $documentType = $request->documentType;
        
        if($documentType === 'excel') {
            //return Excel::download(new SalaExport, 'caixas.xlsx');
        } else {
            $pdf = \PDF::loadView('downloads.relatorios.lista-bancos', $headers);
            return $pdf->stream('lista-bancos.pdf');
        }

    }
}
