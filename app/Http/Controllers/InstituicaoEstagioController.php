<?php

namespace App\Http\Controllers;

use App\Exports\EnsinoExport;
use App\Models\Ensino;
use App\Models\Shcool;
use App\Models\User;
use App\Models\web\turmas\Bolsa;
use App\Models\web\turmas\Estagiario;
use App\Models\web\turmas\EstagioInstituicao;
use App\Models\web\turmas\InstituicaoEducacional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use RealRashid\SweetAlert\Facades\Alert;
use Maatwebsite\Excel\Facades\Excel;

class InstituicaoEstagioController extends Controller
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
        
        if(!$user->can('read: instituicao')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
    
        
        
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());
        
        $instituicoes = InstituicaoEducacional::where('type', 'E')->where('shcools_id', $escola->id)->get(); 

        $headers = [
            "escola" => $escola,
            
            "titulo" => "Listar Instituições de Estagios",
            "descricao" => env('APP_NAME'),
            "instituicoes" => $instituicoes,
            "usuario" => User::findOrFail(Auth::user()->id),
        ];
        

        return view('admin.instituicoes_estagios.index', $headers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();
        
        if(!$user->can('create: instituicao')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
    
        
        
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada()); 
        

        $headers = [
            "escola" => $escola,
            
            "titulo" => "Cadastrar Instituições Para Estagios",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.instituicoes_estagios.create', $headers);
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
        
        if(!$user->can('create: instituicao')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $request->validate([
            "nome" => 'required',
            "status" => 'required',
            "tipo" => 'required',
        ], [
            "nome.required" => "Campo Obrigatório",
            "status.required" => "Campo Obrigatório",
            "tipo.required" => "Campo Obrigatório",
        ]);
        
        InstituicaoEducacional::create([
            'nif' => $request->nif,
            'status' => $request->status,
            'tipo' => $request->tipo,
            'type' => "E",
            'nome' => $request->nome,
            'email' => $request->email,
            'endereco' => $request->endereco,
            'director' => $request->director,
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
        
        if(!$user->can('read: instituicao')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
    
        
        
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada()); 
        $institicao = InstituicaoEducacional::findOrFail(Crypt::decrypt($id)); 
        
        $estagios = EstagioInstituicao::with(['instituicao','estagio'])->where('instituicao_id', $institicao->id)->get();
        
        
        $headers = [
            "escola" => $escola,
            "institicao" => $institicao,
            
            'estagios' =>  $estagios,
            "titulo" => "Mais detalhe da Instituições Para Estagios",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.instituicoes_estagios.show', $headers);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ensino  $ensino
     * @return \Illuminate\Http\Response
     */
    public function instituicao_estagio(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('read: bolsa') || !$user->can('read: instituicao')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
    
        
        
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada()); 
                
        $bolsas_listagem = Bolsa::where('type', 'E')->where('shcools_id', $escola->id)->get(); 
        $instituicoes = InstituicaoEducacional::where('type', 'E')->where('shcools_id', $escola->id)->get(); 

        $bolsas = EstagioInstituicao::with(['instituicao','estagio'])
        ->when($request->instituicao_id, function($query, $value){
            $query->where('instituicao_id', $value);
        })
        ->when($request->bolsa_id, function($query, $value){
            $query->where('estagio_id', $value);
        })
        ->where('shcools_id', $escola->id)
        ->get();
        
        $headers = [
            "instituicoes" => $instituicoes,
            "bolsas_listagem" => $bolsas_listagem,
            "escola" => $escola,
            
            'bolsas' =>  $bolsas,
            "titulo" => "Instituições & Estagios",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.instituicoes_estagios.instituicoes-estagios', $headers);
    }
        
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ensino  $ensino
     * @return \Illuminate\Http\Response
     */
    public function associar_estagio($id = null)
    {
        $user = auth()->user();
        
        if(!$user->can('read: bolsa') || !$user->can('read: instituicao')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        
        
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada()); 
        
        if($id){
            $id = Crypt::decrypt($id);
        }else{
            $id = "";
        }
        
        $institicao = InstituicaoEducacional::find($id); 
        
        $estagios = Bolsa::where('type', 'E')->where('shcools_id', $escola->id)->get(); 
        
        $instituicoes = InstituicaoEducacional::where('type', 'E')->when($institicao->id ?? "", function($query, $value){
            $query->where('id', $value);
        })
        ->where('shcools_id', $escola->id)
        ->get(); 
        
        $headers = [
            "escola" => $escola,
            "instituicoes" => $instituicoes,
            "institicao" => $institicao,
            
            "estagios" => $estagios,
            "titulo" => "Associar Estagio a instituição",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.instituicoes_estagios.associar-bolsas', $headers);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ensino  $ensino
     * @return \Illuminate\Http\Response
     */
    public function instituicao_listar_estagiarios(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('read: bolseiro')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
    
        
        
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada()); 
                
        $estagios = Bolsa::where('type', 'E')->where('shcools_id', $escola->id)->get(); 
        $instituicoes = InstituicaoEducacional::where('type', 'E')->where('shcools_id', $escola->id)->get(); 

        $estagiarios = Estagiario::with(['instituicao','estagio', 'instituicao_estagio', 'ano', 'estudante', 'escola'])
        ->when($request->instituicao_id, function($query, $value){
            $query->where('instituicao_id', $value);
        })
        ->when($request->estagio_id, function($query, $value){
            $query->where('estagio_id', $value);
        })
        ->where('shcools_id', $escola->id)
        ->get();
        
        $headers = [
            "instituicoes" => $instituicoes,
            "estagios" => $estagios,
            "escola" => $escola,
            'estagiarios' =>  $estagiarios,
            "titulo" => "Listar dos Estudantes Estagiarios",
            "descricao" => env('APP_NAME'),
            "filtros" => $request->all('estagio_id', 'instituicao_id'),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.instituicoes_estagios.listar-estagiarios', $headers);
    }
        
    public function associar_estagio_store(Request $request)
    {
 
        $user = auth()->user();
        
        if(!$user->can('create: bolsa') || !$user->can('create: instituicao')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $request->validate([
            "estagio_id" => 'required',
            "instituicao_id" => 'required',
            "desconto" => 'required',
        ], [
            "estagio_id.required" => "Campo Obrigatório",
            "instituicao_id.required" => "Campo Obrigatório",
            "desconto.required" => "Campo Obrigatório",
        ]);
        
           
        foreach ($request->estagio_id as $value) {
            
            $verificar = EstagioInstituicao::where('instituicao_id', $request->instituicao_id)->where('estagio_id', $value)->first();
        
            if(!$verificar){
                EstagioInstituicao::create([
                    'estagio_id' => $value,
                    'instituicao_id' => $request->instituicao_id,
                    'desconto' => $request->desconto,
                    'shcools_id' => $this->escolarLogada(),
                ]);
            }
        }
        
        Alert::success("Bom Trabalho", "Estagio atribuída a instituição");
        return redirect()->route('instituicoes_estagios.show-instituicao-estagio', Crypt::encrypt($request->instituicao_id));
     
    }
    
    public function associar_estagio_editar($id)
    {
        $user = auth()->user();
        
        if(!$user->can('update: bolsa') || !$user->can('update: instituicao')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        
        
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada()); 
                
        $estagios = Bolsa::where('type', 'E')->where('shcools_id', $escola->id)->get(); 
        
        $instituicoes = InstituicaoEducacional::where('type', 'E')->where('shcools_id', $escola->id)->get(); 
        
        $instituicao_estagio = EstagioInstituicao::findOrFail(Crypt::decrypt($id));
        
        $headers = [
            "escola" => $escola,
            "instituicoes" => $instituicoes,
            
            "estagios" => $estagios,
            'instituicao_estagio' =>  $instituicao_estagio,
            "titulo" => "Editar Associação de Estagio a instituição",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.instituicoes_estagios.editar-associar-estagio', $headers);
     
    }   
    
    
    public function associar_estagio_update(Request $request, $id)
    {
        
        $user = auth()->user();
        
        if(!$user->can('update: bolsa') || !$user->can('update: instituicao')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $request->validate([
            "estagio_id" => 'required',
            "instituicao_id" => 'required',
            "desconto" => 'required',
        ], [
            "estagio_id.required" => "Campo Obrigatório",
            "instituicao_id.required" => "Campo Obrigatório",
            "desconto.required" => "Campo Obrigatório",
        ]);
        
        $instituicao_bolsa = EstagioInstituicao::findOrFail($id);
        $instituicao_bolsa->estagio_id = $request->estagio_id;
        $instituicao_bolsa->instituicao_id = $request->instituicao_id;
        $instituicao_bolsa->desconto = $request->desconto;
        $instituicao_bolsa->update();
        

        Alert::success("Bom Trabalho", "Dados actualizados com successo");
        return redirect()->back();
         
    }   
    
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ensino  $ensino
     * @return \Illuminate\Http\Response
     */
    public function instituicao_remover_estagio_estagiario($id)
    {
        $user = auth()->user();
        
        // if(!$user->can('read: ensino')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada()); 
        $estagiario = Estagiario::findOrFail(Crypt::decrypt($id)); 
        $estagiario->delete();

        Alert::success("Bom Trabalho", "Dados Removida com sucesso");
        return redirect()->back();

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
        
        if(!$user->can('update: instituicao')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
    
        
        
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada()); 
        $institicao = InstituicaoEducacional::findOrFail(Crypt::decrypt($id)); 
        
        $headers = [
            "escola" => $escola,
            "institicao" => $institicao,
            
            "titulo" => "Editar Instituições Educacionais",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.instituicoes_estagios.edit', $headers);
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
        
        if(!$user->can('update: instituicao')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $request->validate([
            "nome" => 'required',
            "status" => 'required',
            "tipo" => 'required',
        ], [
            "nome.required" => "Campo Obrigatório",
            "status.required" => "Campo Obrigatório",
            "tipo.required" => "Campo Obrigatório",
        ]);

    
        $update = InstituicaoEducacional::findOrFail($id);
        
        $update->nif = $request->nif;
        $update->status = $request->status;
        $update->tipo = $request->tipo;
        $update->nome = $request->nome;
        $update->email = $request->email;
        $update->endereco = $request->endereco;
        $update->director = $request->director;

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
        
        if(!$user->can('delete: instituicao')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $institicao = InstituicaoEducacional::findOrFail(Crypt::decrypt($id)); 
        $institicao->delete();

        Alert::success("Bom Trabalho", "Dados Excluido com sucesso");
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ensino  $ensino
     * @return \Illuminate\Http\Response
     */
    public function associar_estagio_delete($id)
    {
        $user = auth()->user();
        
        // if(!$user->can('delete: ensino')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        $institicao = EstagioInstituicao::findOrFail(Crypt::decrypt($id)); 
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
