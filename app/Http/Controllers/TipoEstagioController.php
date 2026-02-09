<?php

namespace App\Http\Controllers;

use App\Exports\EnsinoExport;
use App\Models\Ensino;
use App\Models\Shcool;
use App\Models\User;
use App\Models\web\turmas\Bolsa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use RealRashid\SweetAlert\Facades\Alert;
use Maatwebsite\Excel\Facades\Excel;

class TipoEstagioController extends Controller
{

    use TraitHelpers;
    use TraitHeader;
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        
        if(!$user->can('read: bolsa')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
    
        
        
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());
        
        $bolsas = Bolsa::where('type', 'E')->where('shcools_id', $escola->id)->get(); 

        $headers = [
            "escola" => $escola,
            
            "titulo" => "Listar de Tipos de Estagios",
            "descricao" => env('APP_NAME'),
            "bolsas" => $bolsas,
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.tipos-estagios.index', $headers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();
        
        if(!$user->can('create: bolsa')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
    
        
        
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada()); 
        
        $headers = [
            "escola" => $escola,
            
            "titulo" => "Cadastrar Tipo de Estagio",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.tipos-estagios.create', $headers);
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
        
        if(!$user->can('create: bolsa')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $request->validate([
            "nome" => 'required',
        ], [
            "nome.required" => "Campo Obrigatório",
        ]);
        
        Bolsa::create([
            'status' => $request->status,
            'nome' => $request->nome,
            'type' => 'E',
            'codigo' => $request->codigo,
            'descricao' => $request->descricao,
            'shcools_id' => $this->escolarLogada(),
        ]);
        
        Alert::success("Bom Trabalho", "Dados salvos com sucesso");
        return redirect()->back();
     
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ensino  $ensino
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = auth()->user();
        
        if(!$user->can('read: bolsa')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
    
        
        
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada()); 
        $bolsa = Bolsa::findOrFail(Crypt::decrypt($id)); 
        
        $headers = [
            "escola" => $escola,
            "bolsa" => $bolsa,
            
            "titulo" => "Detalhe do Tipo de Estagio",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.tipos-estagios.show', $headers);
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
        
        if(!$user->can('update: bolsa')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
    
        
        
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada()); 
        $bolsa = Bolsa::findOrFail(Crypt::decrypt($id)); 
        
        $headers = [
            "escola" => $escola,
            "bolsa" => $bolsa,
            
            "titulo" => "Editar Tipo de Estagio",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.tipos-estagios.edit', $headers);
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
        
        if(!$user->can('update: bolsa')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $request->validate([
            "nome" => 'required',
        ], [
            "nome.required" => "Campo Obrigatório",
        ]);
    
        $update = Bolsa::findOrFail($id);
        
        $update->status = $request->status;
        $update->nome = $request->nome;
        $update->codigo = $request->codigo;
        $update->descricao = $request->descricao;

        $update->update();

        Alert::success("Bom Trabalho", "Dados actualizados com sucesso");
        return redirect()->back();
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
        
        if(!$user->can('delete: bolsa')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $institicao = Bolsa::findOrFail(Crypt::decrypt($id)); 
        $institicao->delete();

        Alert::success("Bom Trabalho", "Dados Excluido com sucesso");
        return redirect()->back();
    }

    public function ensinosImprimir()
    {
        $headers = [
            "titulo" => "LISTA DOS ENSINOS",
            "datas" => Ensino::get()
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-ensinos', $headers);
        return $pdf->stream('lista-ensinos.pdf');
    }

    public function ensinosExcel()
    {
        return Excel::download(new EnsinoExport, 'ensinos.xlsx');
    }
}
