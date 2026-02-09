<?php

namespace App\Http\Controllers;

use App\Exports\MercadoriaExport;
use App\Exports\TipoMercadoriaExport;
use App\Models\Mercadoria;
use App\Models\TipoMercadoria;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class MercadoriaController extends Controller
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
            "titulo" => "Mercadorias",
            "descricao" => env('APP_NAME'),
            "mercadorias" => Mercadoria::with(['tipo'])->get(),
        ];

        return view('sistema.ministerio.mercadorias.home', $headers);
    }

    public function create()
    {
        $user = auth()->user();

        // if (!$user->can('create: departamento')) {
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
                
        $tipos_mercadorias = TipoMercadoria::get();

        $headers = [
            "titulo" => "Cadastrar Tipo Mercadoria",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "tipos_mercadorias" => $tipos_mercadorias,
        ];

        return view('sistema.ministerio.mercadorias.create', $headers);
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
            "tipo_mercadoria_id" => 'required',
        ], [
            "designacao.required" => "Campo Obrigatório",
            "status.required" => "Campo Obrigatório",
            "tipo_mercadoria_id.required" => "Campo Obrigatório",
        ]);

        $create = Mercadoria::create([
            "designacao" => $request->input('designacao'),
            "status" => $request->input('status'),
            "tipo_mercadoria_id" => $request->input('tipo_mercadoria_id'),
        ]);

        Alert::success("Bom Trabalho", "Dados salvos com sucesso");
        return redirect()->route('web.mercadorias');
    }

    // editar ano Lectivo view
    public function edit($id)
    {
        $user = auth()->user();

        // if (!$user->can('update: departamento')) {
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $mercadoria = Mercadoria::findOrFail($id);
        $tipos_mercadorias = TipoMercadoria::get();

        $headers = [
            "titulo" => "Editar Tipo Mercadoria",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "mercadoria" => $mercadoria,
            "tipos_mercadorias" => $tipos_mercadorias,
        ];

        return view('sistema.ministerio.mercadorias.edit', $headers);
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

        $update = Mercadoria::find($id);
        $update->designacao = $request->input('designacao');
        $update->status = $request->input('status');
        $update->tipo_mercadoria_id = $request->input('tipo_mercadoria_id');
        $update->update();

        Alert::success("Bom trabalho", "Dados Actualizados com successo");
        return redirect()->route('web.mercadorias');
    }


    // editar ano Lectivo view
    public function delete($id)
    {
        $user = auth()->user();

        // if (!$user->can('delete: departamento')) {
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $mercadoria = Mercadoria::findOrFail($id)->delete();

        Alert::success("Bom Trabalho", "Dados Excluido com Sucesso");
        return redirect()->route('web.mercadorias');
    }

    public function Imprimir()
    {
        $user = auth()->user();

        $headers = [
            "titulo" => "LISTA DE MERCADORIAS",
            "mercadorias" => Mercadoria::with(['tipo'])->get(),
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-mercadorias', $headers);
        return $pdf->stream('mercadorias.pdf');
    }

    public function excel()
    {
        return Excel::download(new MercadoriaExport, 'mercadorias.xlsx');
    }
}
