<?php

namespace App\Http\Controllers;

use App\Models\Distrito;
use App\Models\Ensino;
use App\Models\Municipio;
use App\Models\Paise;
use App\Models\Provincia;
use App\Models\User;
use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\anolectivo\EscolaFilhar;
use App\Models\web\anolectivo\ValidacaoRupe;
use App\Models\web\calendarios\Servico;
use App\Models\web\estudantes\Estudante;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class ValidacaoRupeController extends Controller
{
    use TraitHelpers;
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    // --------------------------------------------------------------------------------------
    // ----------------------------------START ANO LECTIVO----------------------------------------------------
    // --------------------------------------------------------------------------------------
    // --------------------------------------------------------------------------------------
    
    // rupes
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // if(!$user->can('read: ano lectivo')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        
        
        $rupes = ValidacaoRupe::when($request->servicos_id, function($query, $value){
            $query->where('servicos_id', $value);
        })
        ->when($request->estudantes_id, function($query, $value){
            $query->where('estudantes_id', $value);
        })
        ->when($request->ano_lectivos_id, function($query, $value){
            $query->where('ano_lectivos_id', $value);
        })
        ->when(isset($request->status), function($query) use ($request) {
            $query->where('status', $request->status);
        })
        ->with(['escola', 'servico', 'estudante', 'ano_lectivo', 'user'])
        ->where('shcools_id', $this->escolarLogada())
        ->get();
            
            
        $estudantes = Estudante::where('shcools_id', $this->escolarLogada())->get();
        $anos = AnoLectivo::where('shcools_id', $this->escolarLogada())->get();
        $servicos = Servico::where('shcools_id', $this->escolarLogada())->get();
            
        
        $headers = [ 
            
            "titulo" => "Listagem Rupes",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "rupes" => $rupes,
            "estudantes" => $estudantes,
            "anos" => $anos,
            "servicos" => $servicos,
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
        ];

        return view('admin.rupes.home', $headers);
    }

    public function create()
    {
        $user = auth()->user();
        
        // if(!$user->can('create: ano lectivo')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        
        
        $estudantes = Estudante::where('shcools_id', $this->escolarLogada())->get();
        $anos = AnoLectivo::where('status', 'activo')->where('shcools_id', $this->escolarLogada())->get();
        $servicos = Servico::where('shcools_id', $this->escolarLogada())->get();
        
        $headers = [ 
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Registrar Rupe",
            "descricao" => env('APP_NAME'),
            
            "estudantes" => $estudantes,
            "anos" => $anos,
            "servicos" => $servicos,
            
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.rupes.create', $headers);
    }

    // store do ano Lectivo
    public function store(Request $request)
    {
        $user = auth()->user();
        
        // if(!$user->can('create: ano lectivo')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        $request->validate([
            "estudantes_id" => 'required',
            "servicos_id" => 'required',
        ], [
            "estudantes_id.required" => "Campo Obrigatório",
            "servicos_id.required" => "Campo Obrigatório",
        ]);         
        
        // ==========================USUARIO E ESCOLA LOGADAS===========================
        
        $create = ValidacaoRupe::create([
            'rupe_id' => $request->rupe_id ?? "",
            'estudantes_id' => $request->estudantes_id ?? "",
            'servicos_id' => $request->servicos_id ?? "",
            'tipo_documento' => $request->tipo_documento ?? "",
            'status' => 0,
            'status_servico' => 0,
            'user_id' => Auth::user()->id ?? "",
            'ano_lectivos_id' => $this->anolectivoActivo(),
            'shcools_id' => $this->escolarLogada(),
        ]);
        $create->save();

        Alert::success("Bom Trabalho", "Dados salvos com sucesso");
        return redirect()->back();
    }

    // editar ano Lectivo view
    public function edit($id)
    {
        $user = auth()->user();
        
        // if(!$user->can('update: ano lectivo')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        $escolas = EscolaFilhar::findOrFail(Crypt::decrypt($id));
 
        
            
        $paises = Paise::where('id', 6)->get();
        $provincias = Provincia::get();
        $municipios = Municipio::get();
        $ensinos = Ensino::get();
        $distritos = Distrito::get();
        
        
        $headers = [ 
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Editar Escola",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "escolas" => $escolas,
            
                        
            "paises" => $paises,
            "provincias" => $provincias,
            "municipios" => $municipios,
            "distritos" => $distritos,
            "ensinos" => $ensinos,
        ];

        return view('admin.escolas-afilhares.edit', $headers);
    }

    // actualizar os dados do ano Lectivo
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        
        // if(!$user->can('update: ano lectivo')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        $request->validate([
            "nome" => 'required',
            "status" => 'required',
        ], [
            "nome.required" => "Campo Obrigatório",
            "status.required" => "Campo Obrigatório",
        ]); 

        $update = EscolaFilhar::find($id);
        
        $update->nome = $request->nome;
        $update->status = $request->status;
        
        $update->director = $request->director;
        $update->sector = $request->sector;
        $update->ensino_id = $request->ensino_id;
        $update->pais_id = $request->pais_id;
        $update->provincia_id = $request->provincia_id;
        $update->municipio_id = $request->municipio_id;
        $update->distrito_id = $request->distrito_id;
        
        $update->update();

        Alert::success("Bom trabalho", "Dados Actualizados com successo");
        return redirect()->back();

    }
    
    // apresentar o ano Lectivo
    public function show($id)
    {
        $user = auth()->user();
        
        // if(!$user->can('read: ano lectivo')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        $rupe = ValidacaoRupe::findOrFail(Crypt::decrypt($id));
        
        if ($rupe->status == true) {
            $status = false;
        }else if($rupe->status == false){
            $status = true;
        }

        $rupe->status = $status;
        $rupe->update();
        
        Alert::success("Bom Trabalho", "Dados salvos com sucesso");
        return redirect()->back();
    }

    public function verificar_rupe()
    {
        $rupes = ValidacaoRupe::where('status', 0)
            ->where('status_servico', 0)
            ->with(['escola', 'servico', 'estudante', 'ano_lectivo', 'user'])
            ->where('shcools_id', $this->escolarLogada())
            ->get();
  
        // Retorne o resultado como JSON
        return response()->json(['rupes_validos' => $rupes]);
    }
    
}
