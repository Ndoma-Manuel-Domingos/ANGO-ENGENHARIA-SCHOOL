<?php

namespace App\Http\Controllers;

use App\Exports\TipoMercadoriaExport;
use App\Models\TipoMercadoria;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class TipoMercadoriasController extends Controller
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
            "titulo" => "Tipo de Mercadorias",
            "descricao" => env('APP_NAME'),
            "tipos_mercadorias" => TipoMercadoria::get(),
        ];

        return view('sistema.ministerio.tipo_mercadorias.home', $headers);
    }

    public function create()
    {
        $user = auth()->user();

        // if (!$user->can('create: departamento')) {
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $headers = [
            "titulo" => "Cadastrar Tipo Mercadoria",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('sistema.ministerio.tipo_mercadorias.create', $headers);
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
            "designacao" => 'required',
            "status" => 'required',
        ], [
            "designacao.required" => "Campo Obrigatório",
            "status.required" => "Campo Obrigatório",
        ]);

        $create = TipoMercadoria::create([
            "designacao" => $request->input('designacao'),
            "status" => $request->input('status'),
        ]);

        Alert::success("Bom Trabalho", "Dados salvos com sucesso");
        return redirect()->route('web.tipos-mercadorias');
    }

    // editar ano Lectivo view
    public function edit($id)
    {
        $user = auth()->user();

        // if (!$user->can('update: departamento')) {
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $tipo_mercadoria = TipoMercadoria::findOrFail($id);

        $headers = [
            "titulo" => "Editar Tipo Mercadoria",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "tipo_mercadoria" => $tipo_mercadoria,
        ];

        return view('sistema.ministerio.tipo_mercadorias.edit', $headers);
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
            "designacao" => 'required',
            "status" => 'required',
        ], [
            "designacao.required" => "Campo Obrigatório",
            "status.required" => "Campo Obrigatório",
        ]);

        $update = TipoMercadoria::find($id);
        $update->designacao = $request->input('designacao');
        $update->status = $request->input('status');
        $update->update();

        Alert::success("Bom trabalho", "Dados Actualizados com successo");
        return redirect()->route('web.tipos-mercadorias');
    }


    // editar ano Lectivo view
    public function delete($id)
    {
        $user = auth()->user();

        // if (!$user->can('delete: departamento')) {
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $tipo_mercadoria = TipoMercadoria::findOrFail($id)->delete();

        Alert::success("Bom Trabalho", "Dados Excluido com Sucesso");
        return redirect()->route('web.tipos-mercadorias');
    }

    public function Imprimir()
    {
        $user = auth()->user();

        $headers = [
            "titulo" => "LISTA DE TIPOS DE MERCADORIAS",
            "tipos_mercadorias" => TipoMercadoria::get()
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-tipos-mercadorias', $headers);
        return $pdf->stream('tipos-de-mercadorias.pdf');
    }

    public function excel()
    {
        return Excel::download(new TipoMercadoriaExport, 'tipo-mercadorias.xlsx');
    }
}
