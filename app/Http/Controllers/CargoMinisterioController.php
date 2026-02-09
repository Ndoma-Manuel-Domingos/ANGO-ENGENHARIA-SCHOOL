<?php

namespace App\Http\Controllers;

use App\Exports\CargosExport;
use App\Models\Cargo;
use App\Models\Departamento;
use App\Models\Shcool;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class CargoMinisterioController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    //
    public function index()
    {
        $user = auth()->user();

        if (!$user->can('read: cargo')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $cargos = Cargo::where('level', '3')->with('departamento')->get();

        $headers = [
            "titulo" => "Cargos",
            "descricao" => env('APP_NAME'),
            "cargos" => $cargos,
        ];

        return view('sistema.ministerio.cargos.home', $headers);
    }

    public function create()
    {
        $user = auth()->user();

        if (!$user->can('create: cargo')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $departamentos = Departamento::where('level', '3')->get();

        $headers = [
            "titulo" => "Cadastrar Cargos",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "departamentos" => $departamentos,
        ];

        return view('sistema.ministerio.cargos.create', $headers);
    }

    // store do ano Lectivo
    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('create: cargo')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            "departamento_id" => 'required',
            "cargo" => 'required',
            "salario" => 'required',
            "status" => 'required',
        ], [
            "cargo.required" => "Campo Obrigatório",
            "departamento_id.required" => "Campo Obrigatório",
            "salario.required" => "Campo Obrigatório",
            "status.required" => "Campo Obrigatório",
        ]);

        $create = Cargo::create([
            "departamento_id" => $request->input('departamento_id'),
            "cargo" => $request->input('cargo'),
            "status" => $request->input('status'),
            'level' => 3,
            "salario" => $request->input('salario'),
        ]);

        Alert::success("Bom Trabalho", "Dados salvos com sucesso");
        return redirect()->route('web.cargos-ministerio');
    }

    // editar ano Lectivo view
    public function edit($id)
    {
        $user = auth()->user();

        if (!$user->can('update: cargo')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $cargo = Cargo::findOrFail($id);

        $departamentos = Departamento::where('level', '3')->get();

        $headers = [
            "titulo" => "Editar Cargo",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "cargo" => $cargo,
            "departamentos" => $departamentos,
        ];

        return view('sistema.ministerio.cargos.edit', $headers);
    }


    // actualizar os dados do ano Lectivo
    public function update(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user->can('update: cargo')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            "departamento_id" => 'required',
            "cargo" => 'required',
            "salario" => 'required',
            "status" => 'required',
        ], [
            "cargo.required" => "Campo Obrigatório",
            "departamento_id.required" => "Campo Obrigatório",
            "salario.required" => "Campo Obrigatório",
            "status.required" => "Campo Obrigatório",
        ]);

        $update = Cargo::find($id);
        $update->departamento_id = $request->input('departamento_id');
        $update->salario = $request->input('salario');
        $update->cargo = $request->input('cargo');
        $update->status = $request->input('status');
        $update->update();

        Alert::success("Bom trabalho", "Dados Actualizados com successo");
        return redirect()->route('web.cargos-ministerio');
    }

    // editar ano Lectivo view
    public function delete($id)
    {
        $user = auth()->user();

        if (!$user->can('delete: cargo')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $cargo = Cargo::findOrFail($id)->delete();

        Alert::success("Bom Trabalho", "Dados Excluido com Sucesso");
        return redirect()->route('web.cargos-ministerio');
    }


    public function Imprimir()
    {

        $user = auth()->user();

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "titulo" => "LISTA DE CARGOS",
            "cargos" => Cargo::where('level', '3')->with('departamento')->get()
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-cargos', $headers);
        return $pdf->stream('lista-cargos-ministerio.pdf');
    }

    public function excel()
    {
        return Excel::download(new CargosExport, 'cargos-ministerio.xlsx');
    }
}
