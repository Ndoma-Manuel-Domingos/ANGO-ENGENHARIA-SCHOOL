<?php

namespace App\Http\Controllers;

use App\Models\Arquivo;
use App\Models\Cargo;
use App\Models\Categoria;
use App\Models\Departamento;
use App\Models\DireccaoMunicipal;
use App\Models\DireccaoProvincia;
use App\Models\Distrito;
use App\Models\Escolaridade;
use App\Models\Especialidade;
use App\Models\FormacaoAcedemico;
use App\Models\Instituicao;
use App\Models\Municipio;
use App\Models\Paise;
use App\Models\Provincia;
use App\Models\Shcool;
use App\Models\Universidade;
use App\Models\User;
use App\Models\web\calendarios\Mes;
use App\Models\web\funcionarios\CartaoFuncionario;
use App\Models\web\funcionarios\Funcionarios;
use App\Models\web\funcionarios\FuncionariosAcademico;
use App\Models\web\funcionarios\FuncionariosControto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class FuncionarioMunicipalController extends Controller
{
    use TraitHelpers;
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function FuncionariosControlo(Request $request)
    {
        $user = auth()->user();
        
        // if(!$user->can('read: professores') ){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        $direccao = DireccaoMunicipal::findOrFail($user->shcools_id);

        $headers = [ 
            "direccao" => $direccao ,
            "titulo" => "Painel de controle",
            "descricao" => env('APP_NAME'),
            "departamentos" => Departamento::where('level', '3')->get(),
            "cargos" => Cargo::where('level', '3')->get(),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];
        
        return view('sistema.direccao-municipal.funcionarios.controlo', $headers);
    }
    
    public function Funcionarios(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('read: professores') ){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $direccao = DireccaoMunicipal::findOrFail($user->shcools_id);
        
        $universidade_id = $request->universidade_id;
        $escolaridade_id = $request->escolaridade_id;
        $formacao_id = $request->formacao_id;
        $especialidade_id = $request->especialidade_id;
        $categora_id = $request->categora_id;
        
        $funcionarios = Funcionarios::with('academico.especialidade', 'academico.categoria', 'academico.escolaridade', 'academico.universidade', 'nacionalidade', 'provincia' ,'municipio','distrito')
        ->whereHas('academico', function($query) use ($universidade_id, $escolaridade_id, $formacao_id, $especialidade_id, $categora_id){
            $query->when($universidade_id, function ($query) use ($universidade_id){
                $query->where('universidade_id', $universidade_id);
            });
            
            $query->when($escolaridade_id, function ($query) use ($escolaridade_id){
                $query->where('escolaridade_id', $escolaridade_id);
            });
            
            $query->when($formacao_id, function ($query) use ($formacao_id){
                $query->where('formacao_academica_id', $formacao_id);
            });
            
            $query->when($especialidade_id, function ($query) use ($especialidade_id){
                $query->where('especialidade_id', $especialidade_id);
            });
            
            $query->when($categora_id, function ($query) use ($categora_id){
                $query->where('categoria_id', $categora_id);
            });
        })
        ->when($request->status, function($query, $value){
            $query->where('status', $value);
        })
        ->where('level', '3')->where([
            ['shcools_id', '=', $direccao->id],
        ])
        ->orderBy('created_at', 'asc')
        ->get();
        
        
        $especialidades = Especialidade::get();
        $categorias = Categoria::get();
        $universidades = Universidade::get();

        $headers = [ 
            "escola" => $direccao ,
            "titulo" => "Listagem dos Funcionários",
            "descricao" => env('APP_NAME'),
            "funcionarios" => $funcionarios,
            "usuario" => User::findOrFail(Auth::user()->id),
            
            "especialidades" => $especialidades,
            "categorias" => $categorias,
            "universidades" => $universidades,
            "escolaridade" => Escolaridade::get(),
            "formacao_academicos" => FormacaoAcedemico::get(),
            
            "requests" => $request->all('status', 'universidade_id', 'escolaridade_id', 'formacao_id', 'especialidade_id', 'categora_id')
            
        ];
        
        return view('sistema.direccao-municipal.funcionarios.funcionarios', $headers);
    }
    
    public function FuncionariosDepartamento(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('read: professores') ){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
                
        $direccao = DireccaoMunicipal::findOrFail($user->shcools_id);
                
        $tempo_trabalho = $request->tempo_trabalho;
        $id = $request->departamento_id;
        
      

        $funcionarios = Funcionarios::with('academico.especialidade', 'academico.categoria', 'academico.escolaridade', 'academico.universidade', 'nacionalidade', 'provincia' ,'municipio','distrito', 'contrato.departamento', 'contrato.cargos')
        ->whereHas('academico', function($query) use ($tempo_trabalho){
            $query->when($tempo_trabalho, function ($query) use ($tempo_trabalho){
                $query->where('ano_trabalho','=' , $tempo_trabalho);
            });
        })
        ->whereHas('contrato', function($query) use ($id){  
            $query->when($id, function ($query) use ($id){ 
                $query->where('departamento_id', $id);
            });
        })
        ->when($request->status, function($query, $value){
            $query->where('status', $value);
        })
        ->when($request->genero, function($query, $value){
            $query->where('genero', $value);
        })
        ->where('level', '3')
        ->where('shcools_id', '=', $direccao->id)
        ->orderBy('created_at', 'asc')
        ->get();

        $departamento = Departamento::find($id);
        $departamentos = Departamento::where('level', 3)->get();

        $headers = [ 
            "escola" => $direccao ,
            "titulo" => "Listagem dos Funcionários por departamentos",
            "descricao" => env('APP_NAME'),
            "funcionarios" => $funcionarios,
            "usuario" => User::findOrFail(Auth::user()->id),
            "departamento" => $departamento,
            "departamentos" => $departamentos,
            "requests" => $request->all('status', 'tempo_trabalho', 'departamento_id', 'genero')
        ];
        
        return view('sistema.direccao-municipal.funcionarios.funcionarios-departamentos', $headers);
    }
    
    public function FuncionariosCargo(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('read: professores') ){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
                
        $direccao = DireccaoMunicipal::findOrFail($user->shcools_id);
                
        $tempo_trabalho = $request->tempo_trabalho;
        $id = $request->cargo_id;

        $funcionarios = Funcionarios::with('academico.especialidade', 'academico.categoria', 'academico.escolaridade', 'academico.universidade', 'nacionalidade', 'provincia' ,'municipio','distrito', 'contrato.departamento', 'contrato.cargos')
        ->whereHas('academico', function($query) use ($tempo_trabalho){
            $query->when($tempo_trabalho, function ($query) use ($tempo_trabalho){
                $query->where('ano_trabalho','=' , $tempo_trabalho);
            });
        })
        ->whereHas('contrato', function($query) use ($id){
            $query->when($id, function ($query) use ($id){
                $query->where('cargo_id', $id)->where('level', '3');
            });
        })
        ->when($request->status, function($query, $value){
            $query->where('status', $value);
        })
        ->when($request->genero, function($query, $value){
            $query->where('genero', $value);
        })
        ->where('level', '3')->where([
            ['shcools_id', '=', $direccao->id],
        ])
        ->orderBy('created_at', 'asc')
        ->get();

        $cargo = Cargo::find($id);
        $cargos = Cargo::where('level', 3)->get();

        $headers = [ 
            "escola" => $direccao ,
            "titulo" => "Listagem dos Funcionários",
            "descricao" => env('APP_NAME'),
            "funcionarios" => $funcionarios,
            "usuario" => User::findOrFail(Auth::user()->id),
            "cargo" => $cargo,
            "cargos" => $cargos,
            "requests" => $request->all('status', 'tempo_trabalho', 'cargo_id', 'genero')
        ];
        
        return view('sistema.direccao-municipal.funcionarios.funcionarios-cargos', $headers);
    }
    
    public function FuncionariosCreate()
    {
        $user = auth()->user();
        
        if(!$user->can('read: professores') ){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $direccao = DireccaoMunicipal::findOrFail($user->shcools_id);
        
        $paises = Paise::where('id', 6)->get();
        $provincias = Provincia::get();
        $municipios = Municipio::get();
        
        $departamento = Departamento::where('level', '3')->get();
        $cargos = Cargo::where('level', '3')->get();
        
        $especialidades = Especialidade::get();
        $categorias = Categoria::get();
        $universidades = Universidade::get();
        $instituicoes = Instituicao::where('nome', '!=', 'PROVINCIAS')->where('nome', '!=', 'MINISTERIO')->get();
        $distritos = Distrito::get();
        
        $headers = [
            "escola" => $direccao,
            "titulo" => "Cadastrado dos Funcionários",
            "descricao" => env('APP_NAME'),
            "paises" => $paises,
            "provincias" => $provincias,
            "municipios" => $municipios,
            "departamentos" => $departamento,
            "cargos" => $cargos,
            "especialidades" => $especialidades,
            "categorias" => $categorias,
            "universidades" => $universidades,
            "instituicoes" => $instituicoes,
            "distritos" => $distritos,
            "escolaridade" => Escolaridade::get(),
            "formacao_academicos" => FormacaoAcedemico::get(),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('sistema.direccao-municipal.funcionarios.create', $headers);
    }
    
    public function FuncionariosEdit($id)
    {
        $user = auth()->user();
        
        if(!$user->can('read: professores') ){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $funcionario = Funcionarios::with([
            'academico.especialidade',
            'academico.categoria',
            'academico.escolaridade',
            'academico.universidade',
        ])
        ->findOrFail($id);
        
        if($funcionario->level == 1){
            $id_instituicao = NULL;
        }
        if($funcionario->level == 2){
            $id_instituicao = DireccaoProvincia::findOrFail($funcionario->shcools_id);
        }
        if($funcionario->level == 3){
            $id_instituicao = DireccaoMunicipal::findOrFail($funcionario->shcools_id);
        }
        if($funcionario->level == 4){
            $id_instituicao = Shcool::findOrFail($funcionario->shcools_id);
        }
        
        $academico = FuncionariosAcademico::where('funcionarios_id', $funcionario->id)->first();
        $arquivo = Arquivo::where('level', $funcionario->level)->where('model_id', $funcionario->id)->first();
        
        $paises = Paise::where('id', 6)->get();
        $provincias = Provincia::get();
        $direccao = DireccaoMunicipal::findOrFail($user->shcools_id);
        $municipios = Municipio::get();
        $departamento = Departamento::where('level', '3')->get();
        $cargos = Cargo::where('level', '3')->get();
        
        $contrato = FuncionariosControto::where('funcionarios_id', $funcionario->id)->first();
        
        $especialidades = Especialidade::get();
        $categorias = Categoria::get();
        $universidades = Universidade::get();
        $instituicoes = Instituicao::where('nome', '!=', 'PROVINCIAS')->where('nome', '!=', 'MINISTERIO')->get();
        $distritos = Distrito::get();
        
        $headers = [
            "escola" => $direccao,
            "titulo" => "Cadastrado dos Funcionários",
            "descricao" => env('APP_NAME'),
            "paises" => $paises,
            "provincias" => $provincias,
            "municipios" => $municipios,
            "departamentos" => $departamento,
            "especialidades" => $especialidades,
            "categorias" => $categorias,
            "universidades" => $universidades,
            "instituicoes" => $instituicoes,
            "distritos" => $distritos,
            "cargos" => $cargos,
            "funcionario" => $funcionario,
            "id_instituicao" => $id_instituicao,
            "academico" => $academico,
            "arquivo" => $arquivo,
            "contrato" => $contrato,
            "escolaridade" => Escolaridade::get(),
            "formacao_academicos" => FormacaoAcedemico::get(),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('sistema.direccao-municipal.funcionarios.edit', $headers);
    }
    
    
    public function FuncionariosShow($id)
    {
        $user = auth()->user();
        
        if(!$user->can('read: professores') ){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $funcionario = Funcionarios::with([
            'academico.especialidade',
            'academico.categoria',
            'academico.escolaridade',
            'academico.universidade',
            'nacionalidade',
            'provincia',
            'municipio',
            'distrito'
        ])
        ->findOrFail($id);
        
        $academico = FuncionariosAcademico::where('funcionarios_id', $funcionario->id)->first();
        $arquivo = Arquivo::where('level', $funcionario->level)->where('model_type', 'funcianario')->where('model_id', $funcionario->id)->first();
        $direccao = DireccaoMunicipal::findOrFail($user->shcools_id);
        $contrato = FuncionariosControto::with('departamento', 'cargos')->where('funcionarios_id', $funcionario->id)->first();
 
        
        $headers = [
            "escola" => $direccao,
            "titulo" => "Visualizar funcionario",
            "descricao" => env('APP_NAME'),
            "funcionario" => $funcionario,
            "academico" => $academico,
            "documentos" => $arquivo,
            "contrato" => $contrato,
            "escolaridade" => Escolaridade::get(),
            "formacao_academicos" => FormacaoAcedemico::get(),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('sistema.direccao-municipal.funcionarios.show', $headers);
    }
    
    public function FuncionariosStatus($id)
    {
        $user = auth()->user();
        
        if(!$user->can('read: professores') ){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $funcionario = Funcionarios::findOrFail($id);
        $contrato = FuncionariosControto::with('departamento', 'cargos')->where('level', $funcionario->level)->where('funcionarios_id', $funcionario->id)->first();
        
        if($funcionario->status == 'activo'){
            $status = 'desactivo';
        }
        
        if($funcionario->status == 'desactivo'){
            $status = 'activo';
        }
        
        $funcionario->status = $status;
        $contrato->status = $status;
        $contrato->status_contrato = $status;
        
        $funcionario->update();
        $contrato->update();
 
        Alert::success('Bom Trabalho', 'Dados Salvos!');
        return redirect()->back();

    }  
    
    public function FuncionariosUpdate(Request $request, $id)
    {
    
        $user = auth()->user();
        
        $direccao = DireccaoMunicipal::findOrFail($user->shcools_id);
        
        $instituicao = Instituicao::findOrFail($request->instituicao_id);
        
        $level = 0;
        
        if($instituicao->nome == "MINISTERIO"){
            $level = 1;
            $id_instituicao = NULL;
        }
        if($instituicao->nome == "PROVINCIAS"){
            $level = 2;
            $id_instituicao = DireccaoProvincia::findOrFail($request->instituicoes_destino);
        }
        if($instituicao->nome == "MUNICIPAIS"){
            $level = 3;
            $id_instituicao = DireccaoMunicipal::findOrFail($request->instituicoes_destino);
        }
        if($instituicao->nome == "ESCOLAS"){
            $level = 4;
            $id_instituicao = Shcool::findOrFail($request->instituicoes_destino);
        }

        $request->validate([
            'nome' => 'required',
            'sobre_nome' => 'required',
            'nascimento'  => 'required',
            'genero'  => 'required',
            'pais_id'  => 'required',
            'provincia_id'  => 'required',
            'municipio_id'  => 'required',
            'bilheite'  => 'required',
            
            'universidade_id'  => 'required',
            'especialidade_id'  => 'required',
            'categoria_id'  => 'required',
            'escolaridade_id'  => 'required',
            'formacao_academica_id'  => 'required',
        ], [
            'nome.required' => "Compo Obrigatório",
            'sobre_nome.required' => "Compo Obrigatório",
            'nascimento.required'  => 'Compo Obrigatório',
            'genero.required'  => 'Compo Obrigatório',
            'pais_id.required'  => 'Compo Obrigatório',
            'provincia_id.required'  => 'Compo Obrigatório',
            'municipio_id.required'  => 'Compo Obrigatório',
            'bilheite.required'  => 'Compo Obrigatório',
            
            'universidade_id.required'  => 'Campo Obrigatório',
            'especialidade_id.required'  => 'Campo Obrigatório',
            'categoria_id.required'  => 'Campo Obrigatório',
            'escolaridade_id.required'  => 'Campo Obrigatório',
            'formacao_academica_id.required'  => 'Campo Obrigatório',
        ]);
        
        $update = Funcionarios::findOrFail($id);
        
        $update->nome = $request->nome;
        $update->sobre_nome = $request->sobre_nome;
        $update->pai = $request->pai;
        $update->mae = $request->mae;
        $update->nascimento = $request->nascimento;
        $update->genero = $request->genero;
        $update->email = $request->email;
        $update->estado_civil = $request->estado_civil;
        $update->pais_id = $request->pais_id;
        $update->provincia_id = $request->provincia_id;
        $update->municipio_id = $request->municipio_id;
        $update->distrito_id = $request->distrito_id;
        $update->bilheite = $request->bilheite;
        $update->telefone = $request->telefone;
        $update->endereco = $request->endereco;
        $update->emissiao_bilheite = $request->emissiao_bilheite;
        $update->endereco = $request->endereco;
        $update->whatsapp = $request->whatsapp;
        $update->facebook = $request->facebook;
        $update->instagram = $request->instagram;
        $update->outras_redes = $request->outras_redes;
        $update->outras_redes = $request->outras_redes;
        $update->level = $level;
        $update->shcools_id = $id_instituicao->id ?? 0;
        
    
        $udpateAcademico = FuncionariosAcademico::findOrFail($request->academico_id);
        $udpateAcademico->universidade_id = $request->universidade_id;
        $udpateAcademico->categoria_id = $request->categoria_id;
        $udpateAcademico->escolaridade_id = $request->escolaridade_id;
        $udpateAcademico->formacao_academica_id = $request->formacao_academica_id;
        $udpateAcademico->especialidade_id = $request->especialidade_id;
        $udpateAcademico->shcools_id = $id_instituicao->id ?? 0;
        $udpateAcademico->ano_trabalho = $request->ano_trabalho;
        
        
        $contrato = FuncionariosControto::find($request->contrato_id);
        $cargo = Cargo::findOrFail($request->cargo_id);
        
        if($contrato){
            $contrato->cargo_geral = strtolower($cargo->cargo);
            $contrato->departamento_id = $request->departamento_id;
            $contrato->cargo_id = $request->cargo_id;
            $contrato->shcools_id = $id_instituicao->id ?? 0;
            
            $contrato->pais_id = $id_instituicao->pais_id ?? 0;
            $contrato->provincia_id = $id_instituicao->provincia_id ?? 0;
            $contrato->municipio_id = $id_instituicao->municipio_id ?? 0;
            $contrato->distrito_id = $id_instituicao->distrito_id ?? 0;
            
            
            $contrato->level = $level;
            $contrato->update();
        }else{
            
            $create3 = new FuncionariosControto();
            $create3->funcionarios_id = $update->id;
            $create3->documento = $update->codigo;
            $create3->salario = $cargo->salario;
            $create3->subcidio = $cargo->salario;
            $create3->subcidio_alimentacao = $cargo->salario;
            $create3->subcidio_transporte = $cargo->salario;
    
            $create3->subcidio_ferias = $cargo->salario;
            $create3->subcidio_natal = $cargo->salario;
            $create3->subcidio_abono_familiar = $cargo->salario;
            
            $create3->pais_id = $id_instituicao->pais_id ?? 0;
            $create3->provincia_id = $id_instituicao->provincia_id ?? 0;
            $create3->municipio_id = $id_instituicao->municipio_id ?? 0;
            $create3->distrito_id = $id_instituicao->distrito_id ?? 0;
    
            $create3->data_inicio_contrato = date("Y-m-d");
            $create3->falta_por_dia = $request->input('falta_por_dia');
            $create3->data_final_contrato = date("Y-m-d");
            $create3->hora_entrada_contrato = "12:12:12";
            $create3->hora_saida_contrato = "12:12:12";
            $create3->cargo = $request->cargo_id;
            $create3->conta_bancaria = "";
            $create3->status_contrato = "";
            $create3->status = 'activo';
            $create3->iban = "";
            $create3->numero_identifcador = $update->codigo;
            $create3->level = $level;
    
            $create3->cargo_geral = strtolower($cargo->cargo);
    
            $create3->departamento_id = $request->departamento_id;
            $create3->cargo_id = $request->cargo_id;
            $create3->clausula = "";
            $create3->nif = $request->bilheite;
            $create3->data_at = date("Y-m-d");
            $create3->ano_lectivos_id = NULL;
            $create3->shcools_id = $id_instituicao->id ?? 0;
            $create3->save();
    
            $meses = Mes::all();
    
            if($meses){
                foreach($meses as $mes){
                    $verificar = CartaoFuncionario::where([
                        ['funcionarios_id', '=', $update->id],
                        ['codigo', '=', $update->codigo],
                        ['mes_id', '=', $mes->id],
                        ['level', '=', $level],
                    ])->first();
    
                    if(!$verificar){
                        $newCreate = new CartaoFuncionario();
    
                        $newCreate->funcionarios_id = $update->id;
                        $newCreate->mes_id = $mes->id;	
                        $newCreate->level = $level;	
                        $newCreate->codigo = $update->codigo;	
                        $newCreate->shcools_id = $id_instituicao->id ?? 0;
                        $newCreate->status  = 'Nao pago';
                        
                        $newCreate->save();
                    }
                }
            }
        
        }
        
        $updateArquivo = Arquivo::find($request->arquivo_id);
       
        if (!empty($request->file('doc_bilheite'))) {
            $image = $request->file('doc_bilheite');
            $imageNameBI = time() .'1.'. $image->extension();
            $image->move(public_path('assets/arquivos'), $imageNameBI);
        }else{
            $imageNameBI = $request->doc_bilheite_guardado;
        }

        if (!empty($request->file('doc_certificado'))) {
            $image2 = $request->file('doc_certificado');
            $imageNameCT = time() .'2.'. $image2->extension();
            $image2->move(public_path('assets/arquivos'), $imageNameCT);
        }else{
            $imageNameCT = $request->doc_certificado_guardado;
        }

        if (!empty($request->file('doc_outros'))) {
            $image2 = $request->file('doc_outros');
            $imageNameOD = time() .'3.'. $image2->extension();
            $image2->move(public_path('assets/arquivos'), $imageNameOD);
        }else{
            $imageNameOD = $request->doc_outros_guardado;
        }

        if (!empty($request->file('doc_atestedao_medico'))) {
            $image2 = $request->file('doc_atestedao_medico');
            $imageNameAT = time() .'4.'. $image2->extension();
            $image2->move(public_path('assets/arquivos'), $imageNameAT);
        }else{
            $imageNameAT = $request->doc_atestedao_medico_guardado;
        }
        if($updateArquivo){
            $updateArquivo->certificado = $imageNameCT;
            $updateArquivo->bilheite = $imageNameBI;
            $updateArquivo->atestado = $imageNameAT;
            $updateArquivo->outros = $imageNameOD;
            $updateArquivo->level = $level;
            $updateArquivo->update();
        }else{
            Arquivo::create([
                'model_id' => $update->id,
                'model_type' => 'funcianario',
                'certificado' => $imageNameCT,
                'bilheite' => $imageNameBI,
                'level' => $level,
                'codigo' => $update->codigo,
                'atestado' => $imageNameAT,
                'outros' => $imageNameOD,
            ]);
        }
        
        $update->update();   
        $udpateAcademico->update();
        
        Alert::success('Bom Trabalho', 'Dados Actualizado com sucesso!');
        return redirect()->back();
    }
    
    public function FuncionariosStore(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'sobre_nome' => 'required',
            'nascimento'  => 'required',
            'genero'  => 'required',
            'pais_id'  => 'required',
            'provincia_id'  => 'required',
            'municipio_id'  => 'required',
            'bilheite'  => 'required',
            
            'universidade_id'  => 'required',
            'especialidade_id'  => 'required',
            'categoria_id'  => 'required',
            'escolaridade_id'  => 'required',
            'formacao_academica_id'  => 'required',
            'doc_bilheite'  => 'required',
            'doc_certificado'  => 'required',
            'doc_atestedao_medico'  => 'required',
            'doc_outros'  => 'required',
        ], [
            'nome.required' => "Compo Obrigatório",
            'sobre_nome.required' => "Compo Obrigatório",
            'nascimento.required'  => 'Compo Obrigatório',
            'genero.required'  => 'Compo Obrigatório',
            'pais_id.required'  => 'Compo Obrigatório',
            'provincia_id.required'  => 'Compo Obrigatório',
            'municipio_id.required'  => 'Compo Obrigatório',
            'bilheite.required'  => 'Compo Obrigatório',
            
            'universidade_id.required'  => 'Campo Obrigatório',
            'especialidade_id.required'  => 'Campo Obrigatório',
            'categoria_id.required'  => 'Campo Obrigatório',
            'escolaridade_id.required'  => 'Campo Obrigatório',
            'formacao_academica_id.required'  => 'Campo Obrigatório',
            'doc_bilheite.required'  => 'Campo Obrigatório',
            'doc_certificado.required'  => 'Campo Obrigatório',
            'doc_atestedao_medico.required'  => 'Campo Obrigatório',
            'doc_outros.required'  => 'Campo Obrigatório',
        ]);
        
        $user = auth()->user();
        
        $direccao = DireccaoMunicipal::findOrFail($user->shcools_id);
            
        $instituicao = Instituicao::findOrFail($request->instituicao_id);
        
        $level = 0;
        
        if($instituicao->nome == "MINISTERIO"){
            $level = 1;
            $id_instituicao = NULL;
        }
        if($instituicao->nome == "PROVINCIAS"){
            $level = 2;
            $id_instituicao = DireccaoProvincia::findOrFail($request->instituicoes_destino);
        }
        if($instituicao->nome == "MUNICIPAIS"){
            $level = 3;
            $id_instituicao = DireccaoMunicipal::findOrFail($request->instituicoes_destino);
        }
        if($instituicao->nome == "ESCOLAS"){
            $level = 4;
            $id_instituicao = Shcool::findOrFail($request->instituicoes_destino);
        }
        
        $codigo = time();
                            
        $funcionario = Funcionarios::create([
            'nome' => $request->nome,
            'sobre_nome' => $request->sobre_nome,
            'pai' => $request->pai,
            'mae' => $request->mae,
            'codigo' => $request->codigo,
            'nascimento' => $request->nascimento,
            'genero' => $request->genero,
            'email' => $request->email,
            'level' => $level,
            'estado_civil' => $request->estado_civil,
            'pais_id' => $request->pais_id,
            'provincia_id' => $request->provincia_id,
            'municipio_id' => $request->municipio_id,
            'distrito_id' => $request->distrito_id,
            'bilheite' => $request->bilheite,
            'emissiao_bilheite' => $request->emissiao_bilheite,
            'status' => "activo",
            'telefone' => $request->telefone,
            'endereco' => $request->endereco,
            'whatsapp' => $request->whatsapp,
            'facebook' => $request->facebook,
            'instagram' => $request->instagram,
            'outras_redes' => $request->outras_redes,
            'shcools_id' => $id_instituicao->id ?? 0,
        ]);

        FuncionariosAcademico::create([
            'universidade_id' => $request->universidade_id,
            'categoria_id' => $request->categoria_id,
            'escolaridade_id' => $request->escolaridade_id,
            'formacao_academica_id' => $request->formacao_academica_id,
            'especialidade_id' => $request->especialidade_id,
            'funcionarios_id' => $funcionario->id,
            'ano_trabalho' => $request->ano_trabalho,
            'codigo' => $codigo,
            "shcools_id" => $id_instituicao->id ?? 0,
        ]);
        
        // controto
        $cargo = Cargo::findOrFail($request->cargo_id);
        
        $create3 = new FuncionariosControto();
        $create3->funcionarios_id = $funcionario->id;
        $create3->documento = $codigo;
        $create3->salario = $cargo->salario;
        $create3->subcidio = $cargo->salario;
        $create3->subcidio_alimentacao = $cargo->salario;
        $create3->subcidio_transporte = $cargo->salario;

        $create3->subcidio_ferias = $cargo->salario;
        $create3->subcidio_natal = $cargo->salario;
        $create3->subcidio_abono_familiar = $cargo->salario;
        
        
        $create3->pais_id = $id_instituicao->pais_id ?? 0;
        $create3->provincia_id = $id_instituicao->provincia_id ?? 0;
        $create3->municipio_id = $id_instituicao->municipio_id ?? 0;
        $create3->distrito_id = $id_instituicao->distrito_id ?? 0;

        $create3->data_inicio_contrato = date("Y-m-d");
        $create3->falta_por_dia = $request->input('falta_por_dia');
        $create3->data_final_contrato = date("Y-m-d");
        $create3->hora_entrada_contrato = "12:12:12";
        $create3->hora_saida_contrato = "12:12:12";
        $create3->cargo = $request->cargo_id;
        $create3->conta_bancaria = "";
        $create3->status_contrato = "";
        $create3->status = 'activo';
        $create3->iban = "";
        $create3->numero_identifcador = $codigo;
        $create3->level = $level;

        $create3->cargo_geral = strtolower($cargo->cargo);

        $create3->departamento_id = $request->departamento_id;
        $create3->cargo_id = $request->cargo_id;
        $create3->clausula = "";
        $create3->nif = $request->bilheite;
        $create3->data_at = date("Y-m-d");
        $create3->ano_lectivos_id = NULL;
        $create3->shcools_id = $id_instituicao->id ?? 0;
        $create3->save();

        $meses = Mes::all();

        if($meses){
            foreach($meses as $mes){
                $verificar = CartaoFuncionario::where([
                    ['funcionarios_id', '=', $funcionario->id],
                    ['mes_id', '=', $mes->id],
                    ['level', '=', $level],
                    ['codigo', '=', $funcionario->codigo],
                ])->first();

                if(!$verificar){
                    $newCreate = new CartaoFuncionario();

                    $newCreate->funcionarios_id = $funcionario->id;
                    $newCreate->mes_id = $mes->id;	
                    $newCreate->level = $level;	
                    $newCreate->codigo = $codigo;	
                    $newCreate->shcools_id = $id_instituicao->id ?? 0;	
                    $newCreate->status  = 'Nao pago';
                    
                    $newCreate->save();
                }
            }
        }
        
        
        if (!empty($request->file('doc_bilheite'))) {
            $image = $request->file('doc_bilheite');
            $imageNameBI = time() .'1.'. $image->extension();
            $image->move(public_path('assets/arquivos'), $imageNameBI);
        }else{
            $imageNameBI = Null;
        }

        if (!empty($request->file('doc_certificado'))) {
            $image2 = $request->file('doc_certificado');
            $imageNameCT = time() .'2.'. $image2->extension();
            $image2->move(public_path('assets/arquivos'), $imageNameCT);
        }else{
            $imageNameCT = Null;
        }

        if (!empty($request->file('doc_outros'))) {
            $image2 = $request->file('doc_outros');
            $imageNameOD = time() .'3.'. $image2->extension();
            $image2->move(public_path('assets/arquivos'), $imageNameOD);
        }else{
            $imageNameOD = Null;
        }

        if (!empty($request->file('doc_atestedao_medico'))) {
            $image2 = $request->file('doc_atestedao_medico');
            $imageNameAT = time() .'4.'. $image2->extension();
            $image2->move(public_path('assets/arquivos'), $imageNameAT);
        }else{
            $imageNameAT = Null;
        }

        Arquivo::create([
            'model_id' => $funcionario->id,
            'model_type' => 'funcianario',
            'certificado' => $imageNameCT,
            'bilheite' => $imageNameBI,
            'level' => $level,
            'codigo' => $codigo,
            'atestado' => $imageNameAT,
            'outros' => $imageNameOD,
        ]);
        
        Alert::success('Bom Trabalho', 'Dados Salvos com sucesso!');
        return redirect()->back();
    }
    
    public function FuncionariosDestroy($id)
    {
    
        $user = auth()->user();
        
        if(!$user->can('read: professores') ){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $funcionario = Funcionarios::findOrFail($id);
        $academico = FuncionariosAcademico::where('funcionarios_id', $funcionario->id)->first();
        $arquivo = Arquivo::where('level', '3')->where('model_type', 'funcianario')->where('model_id', $funcionario->id)->first();
        
        $academico->delete();
        $arquivo->delete();
        $funcionario->delete();
        
        Alert::success('Bom Trabalho', 'Dados Eliminado com sucesso!');
        return redirect()->back();
    }
}
