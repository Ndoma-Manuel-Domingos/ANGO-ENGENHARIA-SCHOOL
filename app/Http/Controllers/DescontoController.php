<?php

namespace App\Http\Controllers;

use App\Models\Shcool;
use App\Models\web\turmas\Desconto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class DescontoController extends Controller
{

    use TraitHelpers;
    use TraitHeader;
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        $user = auth()->user();
        
        if(!$user->can('read: desconto')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        $headers = [
            "escola" => $escola,
            "titulo" => "Listar Descontos",
            "descricao" => env('APP_NAME'),
        ];

        return view('admin.descontos.index', $headers);
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
    
        $query = Desconto::when($request->designacao_geral, function ($query, $value) {
            $query->where(function ($q) use ($value) {
                foreach (['nome', 'desconto', 'status'] as $field) {
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('create: desconto')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $request->validate([
            "designacao" => 'required',
            "desconto" => 'required',
        ]);
                        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
                   
            Desconto::create([
                'status' => $request->status,
                'nome' => $request->designacao,
                'desconto' => $request->desconto,
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ensino  $ensino
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = auth()->user();
        
        if(!$user->can('update: desconto')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        return Desconto::findOrFail($id); 
     
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ensino  $ensino
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        
        if(!$user->can('update: desconto')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $request->validate([
            "designacao" => 'required',
            "desconto" => 'required',
        ]);
                                 
        $update = Desconto::findOrFail($id); 
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $update->status = $request->status;
            $update->nome = $request->designacao;
            $update->desconto = $request->desconto;
    
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ensino  $ensino
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = auth()->user();
        
        if(!$user->can('delete: desconto')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
                
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            Desconto::findOrFail($id)->delete();
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
