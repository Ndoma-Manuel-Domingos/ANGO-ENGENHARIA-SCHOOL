<?php

namespace App\Http\Controllers;

use App\Models\Shcool;
use App\Models\User;
use App\Models\web\biblioteca\Autor;
use App\Models\web\biblioteca\Editora;
use App\Models\web\biblioteca\GeneroLivro;
use App\Models\web\biblioteca\Livro;
use App\Models\web\biblioteca\TipoMeterial;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LivrosController extends Controller
{
    //
    use TraitHelpers;

    public function __construct()
    {
        $this->middleware('auth');
    }

    // view cursos principal
    public function index(Request $request)
    {
        $user = auth()->user();

        // if(!$user->can('read: curso')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $livros = Livro::when($request->autor_id, function ($query, $value) {
            $query->where('autor_id', $value);
        })
            ->when($request->editora_id, function ($query, $value) {
                $query->where('editora_id', $value);
            })
            ->when($request->genero_id, function ($query, $value) {
                $query->where('genero_id', $value);
            })
            ->when($request->tipo_material_id, function ($query, $value) {
                $query->where('tipo_material_id', $value);
            })
            ->when($request->data_publicacao, function ($query, $value) {
                $query->whereDate('data_publicacao', $value);
            })
            ->with(["autor", "editora", "tipo_material", "genero", "escola"])
            ->where('shcools_id', $this->escolarLogada())
            ->get();




        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Listagem de Generos para livros",
            
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "livros" => $livros,
            "generos" => GeneroLivro::where('shcools_id', $this->escolarLogada())->get(),
            "editoras" => Editora::where('shcools_id', $this->escolarLogada())->get(),
            "autores" => Autor::where('shcools_id', $this->escolarLogada())->get(),
            "tipos_materiais" => TipoMeterial::where('shcools_id', $this->escolarLogada())->get(),
        ];

        return view('admin.bibliotecas.livros.index', $headers);
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
            "autor_id" => 'required',
            "isbn" => 'required',
            "genero_id" => 'required',
        ], [
            "nome.required" => "Campo Obrigatório",
            "autor_id.required" => "Campo Obrigatório",
            "isbn.required" => "Campo Obrigatório",
            "genero_id.required" => "Campo Obrigatório",
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        } else {

            $create = Livro::create([
                'nome' => $request->nome,
                'subtitulo' => $request->subtitulo,
                'isbn' => $request->isbn,
                'codigo_interno' => $request->codigo_interno,
                'autor_id' => $request->autor_id,
                'editora_id' => $request->editora_id,
                'edicao' => $request->edicao,
                'volume' => $request->volume,
                'numero_paginas' => $request->numero_paginas,
                'idioma' => $request->idioma,
                'localizacao' => $request->localizacao,
                'status' => $request->status,
                'genero_id' => $request->genero_id,
                'tipo_material_id' => $request->tipo_material_id,
                'data_publicacao' => $request->data_publicacao,
                'data_aquisicao' => $request->data_aquisicao,
                // 'capa' => $request->capa,
                'descricao' => $request->descricao,
                'shcools_id' => $this->escolarLogada(),
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

        $registro = Livro::with(["autor", "editora", "tipo_material", "genero", "escola"])->findOrFail($id);

        if ($registro) {
            return response()->json([
                "status" => 200,
                "registro" => $registro,
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        } else {
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

        $registro = Livro::findOrFail($id);



        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Mais informações sobre livros",
            
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "registro" => $registro,
        ];

        return view('admin.bibliotecas.livros.show', $headers);
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
            "autor_id" => 'required',
            "isbn" => 'required',
            "genero_id" => 'required',
        ], [
            "nome.required" => "Campo Obrigatório",
            "autor_id.required" => "Campo Obrigatório",
            "isbn.required" => "Campo Obrigatório",
            "genero_id.required" => "Campo Obrigatório",
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        } else {
            $update = Livro::findOrFail($id);

            if ($update) {
                $update->nome = $request->nome;
                $update->subtitulo = $request->subtitulo;
                $update->isbn = $request->isbn;
                $update->codigo_interno = $request->codigo_interno;
                $update->autor_id = $request->autor_id;
                $update->editora_id = $request->editora_id;
                $update->edicao = $request->edicao;
                $update->volume = $request->volume;
                $update->numero_paginas = $request->numero_paginas;
                $update->idioma = $request->idioma;
                $update->localizacao = $request->localizacao;
                $update->status = $request->status;
                $update->genero_id = $request->genero_id;
                $update->tipo_material_id = $request->tipo_material_id;
                $update->data_publicacao = $request->data_publicacao;
                $update->data_aquisicao = $request->data_aquisicao;
                // $update->capa = $request->capa;
                $update->descricao = $request->descricao;

                $update->update();

                return response()->json([
                    'status' => 200,
                    'message' => 'Dados ACtualizados com sucesso!',
                    "usuario" => User::findOrFail(Auth::user()->id),
                ]);
            } else {
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
