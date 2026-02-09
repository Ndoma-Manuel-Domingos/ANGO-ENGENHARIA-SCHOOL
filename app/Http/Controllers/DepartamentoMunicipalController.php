<?php

namespace App\Http\Controllers;

use App\Exports\DepartamentoExport;
use App\Models\Departamento;
use App\Models\DireccaoMunicipal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class DepartamentoMunicipalController extends Controller
{
    //    
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    
    //
    public function index()
    {
        $user = auth()->user();
        
        if(!$user->can('read: departamento')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $direccao = DireccaoMunicipal::findOrFail($user->shcools_id);
                    
        $headers = [ 
            "direccao" => $direccao,
            "titulo" => "Departamentos",
            "descricao" => env('APP_NAME'),
            "departamentos" => Departamento::where('level', '3')->get(),
        ];

        return view('sistema.direccao-municipal.departamentos.home', $headers);
    }

    public function create()
    {
        $user = auth()->user();
        
        if(!$user->can('create: departamento')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $direccao = DireccaoMunicipal::findOrFail($user->shcools_id);
        
        $headers = [ 
            "direccao" => $direccao,
            "titulo" => "Cadastrar Departamentos",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            
        ];

        return view('sistema.direccao-municipal.departamentos.create', $headers);
    }

    // store do ano Lectivo
    public function store(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('create: departamento')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $direccao = DireccaoMunicipal::findOrFail($user->shcools_id);
        
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
            'level' => 3,
            "shcools_id" => $direccao->id,
        ]);
     
        Alert::success("Bom Trabalho", "Dados salvos com sucesso");
        return redirect()->route('web.departamento-municipal');
    }

    // editar ano Lectivo view
    public function edit($id)
    {
        $user = auth()->user();
        
        if(!$user->can('update: departamento')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $departamento = Departamento::findOrFail($id);
        $direccao = DireccaoMunicipal::findOrFail($user->shcools_id);

        $headers = [ 
            "direccao" => $direccao,
            "titulo" => "Editar Departamento",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "departamento" => $departamento,
        ];

        return view('sistema.direccao-municipal.departamentos.edit', $headers);
    }


    // actualizar os dados do ano Lectivo
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        
        if(!$user->can('update: departamento')){
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
        return redirect()->route('web.departamento-municipal');

    }
 
    
    // editar ano Lectivo view
    public function delete($id)
    {
        $user = auth()->user();
        
        if(!$user->can('delete: departamento')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $departamento = Departamento::findOrFail($id)->delete();

        Alert::success("Bom Trabalho", "Dados Excluido com Sucesso");
        return redirect()->route('web.departamento-municipal');
        
    }

    public function Imprimir()
    {
        $user = auth()->user();
        
        $direccao = DireccaoMunicipal::findOrFail($user->shcools_id);
        
        $headers = [ 
            "escola" => $direccao,
            "titulo" => "LISTA DE DEPARTAMENTOS",
            "cargos" => Departamento::where('level', '3')
            ->get()
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-departamentos', $headers);
        return $pdf->stream('lista-departamentos.pdf');

    }  
    
    public function excel()
    {
        return Excel::download(new DepartamentoExport, 'departamentos.xlsx');
    }

}
