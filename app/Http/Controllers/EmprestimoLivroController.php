<?php

namespace App\Http\Controllers;

use App\Models\Shcool;
use App\Models\User;
use App\Models\web\biblioteca\EmprestimoLivro;
use App\Models\web\biblioteca\ItemEmprestimoLivro;
use App\Models\web\biblioteca\Livro;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class EmprestimoLivroController extends Controller
{
    //
    use TraitHelpers;
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    // view cursos principal
    public function index()
    {
        $user = auth()->user();
        
        // if(!$user->can('read: curso')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
    

        
        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Emprestimos de Livros",
            
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "emprestimos" => EmprestimoLivro::with(["emprestado_por", "emprestado_para", "items", "escola"])
                ->where('shcools_id', $this->escolarLogada())
                ->get(),
            "livros" => Livro::where('shcools_id', $this->escolarLogada())
                ->whereNotIn('status', ['Emprestado'])
                ->get(),
            "users" => User::where('shcools_id', $this->escolarLogada())
                ->get(),
        ];
        
        return view('admin.bibliotecas.emprestimos.index', $headers);
    }

    // cadastrar cursos
    public function store(Request $request)
    {
        $user = auth()->user();
        
        // if(!$user->can('create: curso')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
      
        $validate = Validator::make($request->all(), [
            "emprestado_para_id" => 'required',
            "data_emprestimo" => 'required',
            "data_prevista_devolucao" => 'required',
        ], [
            "emprestado_para_id.required" => "Campo Obrigatório",
            "data_emprestimo.required" => "Campo Obrigatório",
            "data_prevista_devolucao.required" => "Campo Obrigatório",
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        }else{
            $emprestado_para = User::findOrFail($request->emprestado_para_id);
            
            $codigo_referencia = "REF-". rand(10000, 99999);
        
            $create = EmprestimoLivro::create([
                'codigo_referencia' => $codigo_referencia,
                'emprestado_por_id' => $user->id,
                'emprestado_para_id' => $emprestado_para->id,
                'tipo_pessoa_para' => $emprestado_para->acesso,
                'data_emprestimo' => $request->data_emprestimo,
                'data_prevista_devolucao' => $request->data_prevista_devolucao,
                'hora_emprestimo' => $request->hora_emprestimo,
                'hora_devolucao' => $request->hora_devolucao,
                'status' => 1,
                'descricao' => $request->descricao,
                'shcools_id' => $this->escolarLogada(),
            ]);
            
            foreach ($request->livro_id as $item) {
                
                $livro = Livro::findOrFail($item);
                $livro->status = "Emprestado";
                $livro->update();
            
                ItemEmprestimoLivro::create([
                    'emprestimo_id' => $create->id,
                    'livro_id' => $item,
                    'shcools_id' => $this->escolarLogada(),
                ]);
            }
            

            return response()->json([
                'status' => 200,
                'message' => 'Dados salvos com sucesso!',
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        }
    }

    // editar cursos
    public function edit($id)
    {
    
        $user = auth()->user();
        
        // if(!$user->can('update: curso')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        $registro = EmprestimoLivro::with(["emprestado_por", "emprestado_para", "items", "escola"])->findOrFail($id);

        if ($registro) {
            return response()->json([
                "status" => 200,
                "registro" => $registro,
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        }else{
            return response()->json([
                "status" => 404,
                "message" => 'Turno não Encontrado',
            ]);
        }

    }

    // editar cursos
    public function show($id)
    {
    
        $user = auth()->user();
        
        // if(!$user->can('update: curso')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        $registro = EmprestimoLivro::with(["emprestado_por", "emprestado_para", "items", "escola"])->findOrFail($id);
        
        
        
        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Mais informações sobre Empréstimo",
            
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "registro" => $registro,
        ];
        
        return view('admin.bibliotecas.emprestimos.show', $headers);

    }

    // actualizar cursos
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        
        // if(!$user->can('update: curso')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
     
        $validate = Validator::make($request->all(), [
            "emprestado_para_id" => 'required',
            "livro_id" => 'required',
            "data_emprestimo" => 'required',
            "data_prevista_devolucao" => 'required',
        ], [
            "emprestado_para_id.required" => "Campo Obrigatório",
            "livro_id.required" => "Campo Obrigatório",
            "data_emprestimo.required" => "Campo Obrigatório",
            "data_prevista_devolucao.required" => "Campo Obrigatório",
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        }else{
        
            $update = EmprestimoLivro::findOrFail($id);
            
            if($update->codigo_referencia == null){
                $codigo_referencia = "REF-". rand(10000, 99999);
            }else {
                $codigo_referencia = $update->codigo_referencia;
            }
            
            if($update->livro_id != $request->livro_id){
                
                $antigo = Livro::findOrFail($update->livro_id);
                $antigo->status = "Disponível";
                $antigo->update();
                
                $novo = Livro::findOrFail($request->livro_id);
                $novo->status = "Emprestado";
                $novo->update();
                
            }
             
            if ($update) {
                
                $emprestado_para = User::findOrFail($request->emprestado_para_id);
                
                $update->emprestado_para_id = $emprestado_para->id;
                $update->codigo_referencia = $codigo_referencia;
                $update->tipo_pessoa_para = $emprestado_para->acesso;
                $update->livro_id = $request->livro_id;
                $update->data_emprestimo = $request->data_emprestimo;
                $update->data_prevista_devolucao = $request->data_prevista_devolucao;
                $update->descricao = $request->descricao;
                $update->hora_emprestimo = $request->hora_emprestimo;
                $update->hora_devolucao = $request->hora_devolucao;
                
                $update->update();

                return response()->json([
                    'status' => 200,
                    'message' => 'Dados ACtualizados com sucesso!',
                    "usuario" => User::findOrFail(Auth::user()->id),
                ]);
            }else{
                return response()->json([
                    "status" => 404,
                    "message" => 'Turno não Encontrado'
                ]);
            }
        }
    }

    // delete cursos
    public function destroy($id)
    {
        $user = auth()->user();
        
        // if(!$user->can('delete: curso')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        $delete = Livro::findOrFail($id);
        $delete->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Dados Excluido com sucesso!',
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }
  
}
