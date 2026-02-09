<?php

namespace App\Http\Controllers;

use App\Models\Shcool;
use App\Models\web\turmas\Bolsa;
use App\Models\web\turmas\BolsaInstituicao;
use App\Models\web\turmas\InstituicaoEducacional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class BolsaController extends Controller
{

    use TraitHelpers;
    use TraitHeader;
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function home()
    {
        $user = auth()->user();
        
        if(!$user->can('read: bolsa')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }        
        
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());
        
        $headers = [
            "escola" => $escola,
            "titulo" => "Listar Bolsas",
            "descricao" => env('APP_NAME'),
        ];

        return view('admin.bolsas.index', $headers);
    }
    
    public function index(Request $request)
    {
        $user = auth()->user();
    
        if (!$user->can('read: bolsa')) {
            Alert::error(
                'Acesso restrito',
                'Você não possui permissão para esta operação, por favor, contacte o administrador!'
            );
            return redirect()->back();
        }
    
        $paginacao = $request->paginacao ?? 5;
    
        $query = Bolsa::when($request->designacao_geral, function ($query, $value) {
            $query->where(function ($q) use ($value) {
                foreach (['nome', 'status', 'descricao', 'codigo', 'desconto', 'type'] as $field) {
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
        
        if(!$user->can('create: bolsa')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $request->validate([
            "designacao" => 'required',
        ]);
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            Bolsa::create([
                'status' => $request->status,
                'nome' => $request->designacao,
                'codigo' => $request->codigo,
                'descricao' => $request->descricao,
                'shcools_id' => $this->escolarLogada(),
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

    public function show($id)
    {
        return Bolsa::with(['instituicoes.instituicao'])->findOrFail($id);
    }

    public function edit($id)
    {
        $user = auth()->user();
        
        if(!$user->can('update: bolsa')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
    
        return Bolsa::findOrFail($id); 
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        
        if(!$user->can('update: bolsa')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $request->validate([
            "designacao" => 'required',
        ]);
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
        
            $update = Bolsa::findOrFail($id);
            
            $update->status = $request->status;
            $update->nome = $request->designacao;
            $update->codigo = $request->codigo;
            $update->descricao = $request->descricao;
    
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
            'message' => 'Dados salvos com sucesso!',
        ]);
    }

    public function destroy($id)
    {
        $user = auth()->user();
        
        if(!$user->can('delete: bolsa')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
        
            Bolsa::findOrFail($id)->delete();
            
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
}
