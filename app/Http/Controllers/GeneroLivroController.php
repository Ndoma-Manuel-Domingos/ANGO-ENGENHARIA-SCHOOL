<?php

namespace App\Http\Controllers;

use App\Models\Shcool;
use App\Models\User;
use App\Models\web\biblioteca\GeneroLivro;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class GeneroLivroController extends Controller
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
            "titulo" => "Listagem de Generos para livros",
            
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "generos" => GeneroLivro::where('shcools_id', $this->escolarLogada())->get(),
        ];
        
        return view('admin.bibliotecas.genero-livros.index', $headers);
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
            "nome" => 'required',
        ], [
            "nome.required" => "Campo Obrigatório",
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        }else{
            $create = GeneroLivro::create([
                'nome' => $request->nome,
                'shcools_id' => $this->escolarLogada()
            ]);

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
        
        $registro = GeneroLivro::findOrFail($id);

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

    // actualizar cursos
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        
        // if(!$user->can('update: curso')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        $validate = Validator::make($request->all(), [
            "nome" => 'required',
        ], [
            "nome.required" => "Campo Obrigatório",
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        }else{
            $update = GeneroLivro::findOrFail($id);

            if ($update) {
                $update->nome = $request->input('nome');
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
        
        $delete = GeneroLivro::findOrFail($id);
        $delete->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Dados Excluido com sucesso!',
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }
  
}
