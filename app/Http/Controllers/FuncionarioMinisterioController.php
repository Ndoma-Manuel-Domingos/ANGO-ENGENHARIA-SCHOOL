<?php

namespace App\Http\Controllers;

use App\Models\Arquivo;
use App\Models\Cargo;
use App\Models\Categoria;
use App\Models\Departamento;
use App\Models\Distrito;
use App\Models\Escolaridade;
use App\Models\Especialidade;
use App\Models\FormacaoAcedemico;
use App\Models\Instituicao;
use App\Models\Municipio;
use App\Models\Paise;
use App\Models\Provincia;
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

class FuncionarioMinisterioController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function Funcionarios()
    {
        $user = auth()->user();
        
        if(!$user->can('read: professores') ){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $funcionarios = Funcionarios::where('level', '1')
        ->orderBy('created_at', 'asc')
        ->get();

        $headers = [ 
            "titulo" => "Listagem dos Funcionários",
            "descricao" => env('APP_NAME'),
            "funcionarios" => $funcionarios,
            "usuario" => User::findOrFail(Auth::user()->id),
            
        ];
        
        return view('sistema.ministerio.funcionarios.funcionarios', $headers);
    }
    
    public function FuncionariosCreate()
    {
        $user = auth()->user();
        
        if(!$user->can('read: professores') ){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $paises = Paise::where('id', 6)->get();
        $provincias = Provincia::get();
        $municipios = Municipio::get();
        $departamento = Departamento::where('level', '3')->get();
        $cargos = Cargo::where('level', '3')->get();
        
        $especialidades = Especialidade::get();
        $categorias = Categoria::get();
        $universidades = Universidade::get();
        $instituicoes = Instituicao::get();
        $distritos = Distrito::get();
        
        
        $headers = [
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

        return view('sistema.ministerio.funcionarios.create', $headers);
    }
    
    public function FuncionariosEdit($id)
    {
        $user = auth()->user();
        
        if(!$user->can('read: professores') ){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $funcionario = Funcionarios::findOrFail($id);
        $academico = FuncionariosAcademico::where('funcionarios_id', $funcionario->id)->first();
        $arquivo = Arquivo::where('level', '1')->where('model_id', $funcionario->id)->first();
        
        $paises = Paise::where('id', 6)->get();
        $provincias = Provincia::get();
        $municipios = Municipio::get();
        $departamento = Departamento::where('level', '3')->get();
        $cargos = Cargo::where('level', '3')->get();
        
        $contrato = FuncionariosControto::where('funcionarios_id', $funcionario->id)->first();
        
        $especialidades = Especialidade::get();
        $categorias = Categoria::get();
        $universidades = Universidade::get();
        $instituicoes = Instituicao::get();
        $distritos = Distrito::get();
        
        $headers = [
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
            "academico" => $academico,
            "arquivo" => $arquivo,
            "contrato" => $contrato,
            "escolaridade" => Escolaridade::get(),
            "formacao_academicos" => FormacaoAcedemico::get(),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('sistema.ministerio.funcionarios.edit', $headers);
    }
    
    public function FuncionariosUpdate(Request $request, $id)
    {
    
        $user = auth()->user();
        
        $request->validate([
            'nome' => 'required',
            'sobre_nome' => 'required',
            'nascimento'  => 'required',
            'genero'  => 'required',
            'pais_id'  => 'required',
            'provincia_id'  => 'required',
            'municipio_id'  => 'required',
            'bilheite'  => 'required',
            
            'universidade'  => 'required',
            'curso'  => 'required',
            'area_formacao'  => 'required',
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
            
            
            'universidade.required'  => 'Campo Obrigatório',
            'curso.required'  => 'Campo Obrigatório',
            'area_formacao.required'  => 'Campo Obrigatório',
            'escolaridade_id.required'  => 'Campo Obrigatório',
            'formacao_academica_id.required'  => 'Campo Obrigatório',
        ]);
        
        $update = Funcionarios::findOrFail($id);
        
        $update->nome = $request->nome;
        $update->sobre_nome = $request->sobre_nome;
        $update->nascimento = $request->nascimento;
        $update->genero = $request->genero;
        $update->email = $request->email;
        $update->estado_civil = $request->estado_civil;
        $update->pais_id = $request->pais_id;
        $update->provincia_id = $request->provincia_id;
        $update->municipio_id = $request->municipio_id;
        $update->bilheite = $request->bilheite;
        $update->telefone = $request->telefone;
        $update->endereco = $request->endereco;
        
    
        $udpateAcademico = FuncionariosAcademico::findOrFail($request->academico_id);
        $udpateAcademico->curso = $request->curso;
        $udpateAcademico->area_formacao = $request->area_formacao;
        $udpateAcademico->escolaridade_id = $request->escolaridade_id;
        $udpateAcademico->formacao_academica_id = $request->formacao_academica_id;
        $udpateAcademico->universidade = $request->universidade;
        
        $contrato = FuncionariosControto::find($request->contrato_id);
        $cargo = Cargo::findOrFail($request->cargo_id);
        
        if($contrato){
            $contrato->cargo_geral = strtolower($cargo->cargo);
            $contrato->departamento_id = $request->departamento_id;
            $contrato->cargo_id = $request->cargo_id;
            $contrato->update();
        }else{
            
            $create3 = new FuncionariosControto();
            $create3->funcionarios_id = $update->id;
            $create3->documento = time();
            $create3->salario = $cargo->salario;
            $create3->subcidio = $cargo->salario;
            $create3->subcidio_alimentacao = $cargo->salario;
            $create3->subcidio_transporte = $cargo->salario;
    
            $create3->subcidio_ferias = $cargo->salario;
            $create3->subcidio_natal = $cargo->salario;
            $create3->subcidio_abono_familiar = $cargo->salario;
    
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
            $create3->numero_identifcador = time();
            $create3->level = '1';
    
            $create3->cargo_geral = strtolower($cargo->cargo);
    
            $create3->departamento_id = $request->departamento_id;
            $create3->cargo_id = $request->cargo_id;
            $create3->clausula = "";
            $create3->nif = $request->bilheite;
            $create3->data_at = date("Y-m-d");
            $create3->ano_lectivos_id = NULL;
            $create3->shcools_id = NULL;
            $create3->save();
    
            $meses = Mes::all();
    
            if($meses){
                foreach($meses as $mes){
                    $verificar = CartaoFuncionario::where([
                        ['funcionarios_id', '=', $update->id],
                        ['mes_id', '=', $mes->id],
                        ['level', '=', 1],
                    ])->first();
    
                    if(!$verificar){
                        $newCreate = new CartaoFuncionario();
    
                        $newCreate->funcionarios_id = $update->id;
                        $newCreate->mes_id = $mes->id;	
                        $newCreate->level = 1;	
                        $newCreate->shcools_id = NULL;	
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
            $updateArquivo->level = 1;
            $updateArquivo->update();
        }else{
            Arquivo::create([
                'model_id' => $update->id,
                'model_type' => 'funcianario',
                'certificado' => $imageNameCT,
                'bilheite' => $imageNameBI,
                'level' => 1,
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
                
        $verificarBI = Funcionarios::where('bilheite', $request->bilheite)->first();
        if($verificarBI){
            Alert::warning('Informação', 'Bilhete de identidade duplicado com sucesso!');
            return redirect()->back();
        }
        
        $user = auth()->user();
                    
        $codigo = time();
                    
        $funcionario = Funcionarios::create([
            'nome' => $request->nome,
            'sobre_nome' => $request->sobre_nome,
            'pai' => $request->pai,
            'mae' => $request->mae,
            'codigo' => $codigo,
            'nascimento' => $request->nascimento,
            'genero' => $request->genero,
            'email' => $request->email,
            'level' => '1',
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
            'shcools_id' => NULL,
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
            'shcools_id' => NULL,
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
        $create3->level = '1';

        $create3->cargo_geral = strtolower($cargo->cargo);

        $create3->departamento_id = $request->departamento_id;
        $create3->cargo_id = $request->cargo_id;
        $create3->clausula = "";
        $create3->nif = $request->bilheite;
        $create3->data_at = date("Y-m-d");
        $create3->ano_lectivos_id = NULL;
        $create3->shcools_id = NULL;
        $create3->save();

        $meses = Mes::all();

        if($meses){
            foreach($meses as $mes){
                $verificar = CartaoFuncionario::where([
                    ['funcionarios_id', '=', $funcionario->id],
                    ['mes_id', '=', $mes->id],
                    ['level', '=', '1'],
                    ['codigo', '=', $funcionario->codigo],
                ])->first();

                if(!$verificar){
                    $newCreate = new CartaoFuncionario();

                    $newCreate->funcionarios_id = $funcionario->id;
                    $newCreate->mes_id = $mes->id;	
                    $newCreate->codigo = $codigo;	
                    $newCreate->level = 1;	
                    $newCreate->shcools_id = NULL;	
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
            'level' => '1',
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
        $arquivo = Arquivo::where('level', '1')->where('model_type', 'funcianario')->where('model_id', $funcionario->id)->first();
        
        $academico->delete();
        $arquivo->delete();
        $funcionario->delete();
        
        Alert::success('Bom Trabalho', 'Dados Eliminado com sucesso!');
        return redirect()->back();
    }
}
