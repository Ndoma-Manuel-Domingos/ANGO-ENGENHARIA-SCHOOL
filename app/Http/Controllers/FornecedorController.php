<?php

namespace App\Http\Controllers;

use App\Exports\FornecedoresExport;
use App\Models\Fornecedor;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class FornecedorController extends Controller
{
    use TraitHelpers;
    use TraitHeader;

    public function __construct()
    {
        $this->middleware('auth');
    }

    //
    public function index()
    {
        $user = auth()->user();

        // if (!$user->can('read: departamento')) {
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $headers = [
            "titulo" => "Fornecedores",
            "descricao" => env('APP_NAME'),
            "fornecedores" => Fornecedor::get(),
        ];

        return view('sistema.ministerio.fornecedores.home', $headers);
    }

    public function create()
    {
        $user = auth()->user();

        // if (!$user->can('create: departamento')) {
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $headers = [
            "titulo" => "Cadastrar Fornecedores",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('sistema.ministerio.fornecedores.create', $headers);
    }

    // store do ano Lectivo
    public function store(Request $request)
    {
        $user = auth()->user();

        // if (!$user->can('create: departamento')) {
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $request->validate([
            "nome" => 'required',
            "status" => 'required',
            "nif" => 'required',
        ], [
            "nome.required" => "Campo Obrigatório",
            "status.required" => "Campo Obrigatório",
            "nif.required" => "Campo Obrigatório",
        ]);

        $create = Fornecedor::create([
            "nome" => $request->input('nome'),
            "status" => $request->input('status'),
            "nif" => $request->input('nif'),
        ]);

        Alert::success("Bom Trabalho", "Dados salvos com sucesso");
        return redirect()->route('web.fornecedores');
    }

    // editar ano Lectivo view
    public function edit($id)
    {
        $user = auth()->user();

        // if (!$user->can('update: departamento')) {
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $fornecedor = Fornecedor::findOrFail($id);

        $headers = [
            "titulo" => "Editar Fornecedores",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "fornecedor" => $fornecedor,
        ];

        return view('sistema.ministerio.fornecedores.edit', $headers);
    }


    // actualizar os dados do ano Lectivo
    public function update(Request $request, $id)
    {
        $user = auth()->user();

        // if (!$user->can('update: departamento')) {
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $request->validate([
            "nome" => 'required',
            "status" => 'required',
            "nif" => 'required',
        ], [
            "nome.required" => "Campo Obrigatório",
            "status.required" => "Campo Obrigatório",
            "nif.required" => "Campo Obrigatório",
        ]);

        $update = Fornecedor::find($id);
        $update->nome = $request->input('nome');
        $update->status = $request->input('status');
        $update->nif = $request->input('nif');
        $update->update();

        Alert::success("Bom trabalho", "Dados Actualizados com successo");
        return redirect()->route('web.fornecedores');
    }


    // editar ano Lectivo view
    public function delete($id)
    {
        $user = auth()->user();

        // if (!$user->can('delete: departamento')) {
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $fornecedor = Fornecedor::findOrFail($id)->delete();

        Alert::success("Bom Trabalho", "Dados Excluido com Sucesso");
        return redirect()->route('web.fornecedores');
    }

    public function Imprimir()
    {
        $user = auth()->user();

        $headers = [
            "titulo" => "LISTA DE FORNECEDORES",
            "fornecedores" => Fornecedor::get(),
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-fornecedores', $headers);
        return $pdf->stream('fornecedores.pdf');
    }

    public function excel()
    {
        return Excel::download(new FornecedoresExport, 'fornecedores.xlsx');
    }
}
