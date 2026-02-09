<?php

namespace App\Http\Controllers;

use App\Models\Shcool;
use App\Models\web\salas\Caixa;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CaixaController extends Controller
{
    use TraitHelpers;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function home()
    {
        $user = auth()->user();
        
        if(!$user->can('read: caixa')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $headers = [ 
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Lista dos Caixas",
            "descricao" => env('APP_NAME'),
        ];

        return view('admin.caixas.home', $headers);
    }
        
    public function index(Request $request)
    {
        $user = auth()->user();
    
        if (!$user->can('read: caixa')) {
            Alert::error(
                'Acesso restrito',
                'Você não possui permissão para esta operação, por favor, contacte o administrador!'
            );
            return redirect()->back();
        }
    
        $paginacao = $request->paginacao ?? 5;
    
        $query = Caixa::when($request->designacao_geral, function ($query, $value) {
            $query->where(function ($q) use ($value) {
                foreach (['caixa', 'conta', 'status'] as $field) {
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
        
        if(!$user->can('create: caixa')){
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
        
            $verificar = Caixa::where('caixa', $request->designacao)
                ->where('shcools_id', $this->escolarLogada())
            ->first();
    
        
            if($verificar){
                return response()->json([
                    'status' => 404,
                    'message' => "Este caixa já Esta Cadastrado!",
                ]);
            }
           
            $verifica_conta_contabilidade = Caixa::where('shcools_id', $this->escolarLogada())
                ->where('conta', 'like', "45.1.%")
                ->count();
            
            $nova_conta = "45.1." . $verifica_conta_contabilidade + 1;
    
            $create = Caixa::create([
                'ordem' => $verifica_conta_contabilidade + 1,
                'conta' => $nova_conta,
                'caixa' => $request->designacao,
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
        
        if(!$user->can('update: caixa')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        return Caixa::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        
        if(!$user->can('update: caixa')){
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
           
            $update = Caixa::findOrFail($id);
    
            $verifica_conta_contabilidade = Caixa::where('shcools_id', $this->escolarLogada())
                ->where('conta', 'like', "45.1.%")
                ->count();
                
            if($update->conta == ""){
                $nova_conta = "45.1." . $verifica_conta_contabilidade + 1;
                $update->ordem = $verifica_conta_contabilidade + 1;
                $update->conta = $nova_conta;
            }
    
            $update->caixa = $request->designacao;
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
        ]);
    }

    public function destroy($id)
    {
        $user = auth()->user();
        
        if(!$user->can('delete: caixa')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
    
            Caixa::findOrFail($id)->delete();
         
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
            "titulo" => "LISTA DOS CAIXAS",
            "caixas" => Caixa::where('shcools_id',$this->escolarLogada())->get()
        ];
        
                
        $documentType = $request->documentType;
        
        if($documentType === 'excel') {
            //return Excel::download(new SalaExport, 'caixas.xlsx');
        } else {
            $pdf = \PDF::loadView('downloads.relatorios.lista-caixas', $headers);
            return $pdf->stream('lista-caixas.pdf');
        }

    }


}
