<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CategoriasExport;
use App\Models\Categoria;
use App\Models\Shcool;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CategoriaController extends Controller
{

    use TraitHelpers;
    
    public function __construct()
    {
        $this->middleware('auth');
    }
        
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function home(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('read: categoria')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
    
        $headers = [
            "titulo" => "Lista de categorias",
            "descricao" => env('APP_NAME'),
            "loyout" => $request->loyout,
            "escola" => $request->loyout == "escolas" ? Shcool::with('ensino')->findOrFail($this->escolarLogada()) : null,
        ];

        return view('sistema.categorias.home', $headers);
    }
            
    public function index(Request $request)
    {
        $user = auth()->user();
    
        if (!$user->can('read: categoria')) {
            Alert::error(
                'Acesso restrito',
                'Você não possui permissão para esta operação, por favor, contacte o administrador!'
            );
            return redirect()->back();
        }
    
        $paginacao = $request->paginacao ?? 5;
    
        $query = Categoria::when($request->designacao_geral, function ($query, $value) {
            $query->where(function ($q) use ($value) {
                foreach (['nome', 'status'] as $field) {
                    $q->orWhere($field, 'like', "%{$value}%");
                }
            });
        })
        ->when($request->data_status, function($query, $value) {
            $query->where('status', $value);
        });
    
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
        
        if(!$user->can('create: categoria')){
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
          
            $verificar = Categoria::where('nome', $request->designacao)->first();
    
            if($verificar){
                return response()->json([
                    'status' => 300,
                    'message' => "Este categoria já Esta Cadastrado!",
                ]);
            }
            
            Categoria::create([
                'nome' => $request->designacao,
                'status' => $request->status,
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
            "usuario" => User::findOrFail(Auth::user()->id),
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
        
        if(!$user->can('update: categoria')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        return Categoria::findOrFail($id);

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
        
        if(!$user->can('update: categoria')){
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
    
            $update = Categoria::findOrFail($id);
            $update->nome = $request->designacao;
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
            'message' => 'Dados Actualizados com sucesso!',
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
        
        if(!$user->can('delete: categoria')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            Categoria::findOrFail($id)->delete();

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
            "titulo" => "Lista das categorias",
            "datas" => Categoria::get()
        ];
        
        $documentType = $request->documentType;
        
        if($documentType === 'excel') {
            return Excel::download(new CategoriasExport, 'categorias.xlsx');
        } else {
            $pdf = \PDF::loadView('downloads.relatorios.lista-categorias', $headers);
            return $pdf->stream('lista-categorias.pdf');
        }
    }

}
