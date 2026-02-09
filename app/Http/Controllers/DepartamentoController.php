<?php

namespace App\Http\Controllers;

use App\Exports\DepartamentoExport;
use App\Models\User;
use App\Models\Departamento;
use App\Models\Shcool;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class DepartamentoController extends Controller
{
    use TraitHelpers;


    public function __construct()
    {
        $this->middleware('auth');
    }


    //
    public function index()
    {
        $user = auth()->user();

        if (!$user->can('read: departamento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Departamentos",
            "descricao" => env('APP_NAME'),
            "departamentos" => Departamento::where('level', '4')->where('shcools_id', $this->escolarLogada())->get(),
            
        ];

        return view('admin.departamentos.home', $headers);
    }

    public function create()
    {
        $user = auth()->user();

        if (!$user->can('create: departamento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Cadastrar Departamentos",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.departamentos.create', $headers);
    }

    // store do ano Lectivo
    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('create: departamento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            "departamento" => 'required',
            "status" => 'required',
        ], [
            "departamento.required" => "Campo Obrigatório",
            "status.required" => "Campo Obrigatório",
        ]);

        $create = Departamento::create([
            "departamento" => $request->input('departamento'),
            "status" => $request->input('status'),
            "level" => 4,
            "shcools_id" => $this->escolarLogada(),
        ]);

        Alert::success("Bom Trabalho", "Dados salvos com sucesso");
        return redirect()->route('web.departamento');
    }

    // editar ano Lectivo view
    public function edit($id)
    {
        $user = auth()->user();

        if (!$user->can('update: departamento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $departamento = Departamento::findOrFail($id);



        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Editar Departamento",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "departamento" => $departamento,
        ];

        return view('admin.departamentos.edit', $headers);
    }


    // actualizar os dados do ano Lectivo
    public function update(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user->can('update: departamento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            "departamento" => 'required',
            "status" => 'required',
        ], [
            "departamento.required" => "Campo Obrigatório",
            "status.required" => "Campo Obrigatório",
        ]);

        $update = Departamento::find($id);
        $update->departamento = $request->input('departamento');
        $update->status = $request->input('status');
        $update->update();

        Alert::success("Bom trabalho", "Dados Actualizados com successo");
        return redirect()->route('web.departamento');
    }


    // editar ano Lectivo view
    public function delete($id)
    {
        $user = auth()->user();

        if (!$user->can('delete: departamento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $departamento = Departamento::findOrFail($id)->delete();

        Alert::success("Bom Trabalho", "Dados Excluido com Sucesso");
        return redirect()->route('web.departamento');
    }

    public function Imprimir()
    {




        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            
            "titulo" => "LISTA DE DEPARTAMENTOS",
            "escola" => Shcool::find($this->escolarLogada()),
            "cargos" => Departamento::where('level', '4')->with('departamento')->where([
                ['shcools_id', '=', $this->escolarLogada()]
            ])->get()
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-departamentos', $headers);
        return $pdf->stream('lista-departamentos.pdf');
    }

    public function excel()
    {
        return Excel::download(new DepartamentoExport, 'departamentos.xlsx');
    }
}
