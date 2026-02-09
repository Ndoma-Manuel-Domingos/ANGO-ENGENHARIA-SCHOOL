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
use App\Models\Municipio;
use App\Models\Paise;
use App\Models\Provincia;
use App\Models\Universidade;
use App\Models\User;
use App\Models\DireccaoProvincia;
use App\Models\Notificacao;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

use App\Models\Professor;
use App\Models\ProfessorAcedemico;

use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;

class ProfessorProvincialController extends Controller
{
    use TraitHelpers;
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function professoresCreate()
    {
        $user = auth()->user();
        
        if(!$user->can('read: professores') ){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $direccao = DireccaoProvincia::findOrFail($user->shcools_id);
        
        $paises = Paise::where('id', 6)->get();
        $provincias = Provincia::where('id', $direccao->provincia_id)->get();
        $municipios = Municipio::where('provincia_id', $direccao->provincia_id)->get();
        // $departamento = Departamento::where('level', '2')->where('shcools_id', $direccao->id)->get();
        // $cargos = Cargo::where('level', '2')->where('shcools_id', $direccao->id)->get();
        
        $departamento = Departamento::where('level', '3')->get();
        $cargos = Cargo::where('level', '3')->get();
        
        $especialidades = Especialidade::get();
        $categorias = Categoria::get();
        $universidades = Universidade::get();
        //$instituicoes = Instituicao::get();
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
            //"instituicoes" => $instituicoes,
            "distritos" => $distritos,
            
            "escolaridade" => Escolaridade::get(),
            "formacao_academicos" => FormacaoAcedemico::get(),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('sistema.direccao-provincial.professores.create', $headers);
    }

    public function professoresStore(Request $request)
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
        
        $verificarBI = Professor::where('bilheite', $request->bilheite)->first();
        if($verificarBI){
            Alert::warning('Informação', 'Bilhete de identidade duplicado com sucesso!');
            return redirect()->back();
        }
        
        $codigo = time();
        
        $prefessor = Professor::create([
            'nome' => $request->nome,
            'sobre_nome' => $request->sobre_nome,
            'pai' => $request->pai,
            'mae' => $request->mae,
            'email' => $request->email,
            'codigo' => $codigo,
            'level' => '4',
            'nascimento' => $request->nascimento,
            'genero' => $request->genero,
            'estado_civil' => $request->estado_civil,
            'pais_id' => $request->pais_id,
            'provincia_id' => $request->provincia_id,
            'municipio_id' => $request->municipio_id,
            'bilheite' => $request->bilheite,
            'distrito_id' => $request->distrito_id,
            'emissiao_bilheite' => $request->emissiao_bilheite,
            'status' => "desactivo",
            'telefone' => $request->telefone,
            'endereco' => $request->endereco,
            'whatsapp' => $request->whatsapp,
            'facebook' => $request->facebook,
            'instagram' => $request->instagram,
            'outras_redes' => $request->outras_redes,
            "ano_lectivo_global_id" => $this->anolectivoActivoGlobal(),
        ]);

        ProfessorAcedemico::create([
            'universidade_id' => $request->universidade_id,
            'categoria_id' => $request->categoria_id,
            'escolaridade_id' => $request->escolaridade_id,
            'formacao_academica_id' => $request->formacao_academica_id,
            'especialidade_id' => $request->especialidade_id,
            'codigo' => $codigo,
            'professor_id' => $prefessor->id,
            "ano_lectivo_global_id" => $this->anolectivoActivoGlobal(),
        ]);
        
        $full = $request->nome . " " . $request->sobre_nome;
        $usernames = preg_split('/\s+/', strtolower($full), -1, PREG_SPLIT_NO_EMPTY);

        $user = User::create([
            'nome' => $request->nome . " ". $request->sobre_nome,
            'telefone' => $request->telefone,
            'usuario' => $request->bilheite,
            'password' => Hash::make('123456'),
            'acesso' => 'professor',
            'level' => 50,
            'numero_avaliacoes' => 3,
            'status' => 'Bloqueado',
            'login' => 'N',
            'email' => "{$request->bilheite}@gmail.com",
            'funcionarios_id' => $prefessor->id,
        ]);
        
        $role = Role::where('name', 'professor')->first();
        $user->assignRole($role);
        
        $text = "O Professor {$request->nome} {$request->sobre_nome} enviou uma candidatura para o ministério da educação";
        $text2 = "O Sr(a) acabou de enviar uma candidatura para o ministerio da educação ";
        
        Notificacao::create([
            'user_id' => $user->id,
            'destino' => NULL,
            'type_destino' => 'escola',
            'type_enviado' => 'provincial',
            'notificacao' => $text,
            'notificacao_user' => $text2,
            'status' => '0',
            'model_id' => $user->id,
            'model_type' => "candidatura",
            'shcools_id' => NULL
        ]);

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
            'model_id' => $prefessor->id,
            'model_type' => 'professor',
            'certificado' => $imageNameCT,
            'bilheite' => $imageNameBI,
            'level' => '4',
            'atestado' => $imageNameAT,
            'outros' => $imageNameOD,
            'codigo' => $codigo,
        ]);

        Alert::success('Bom Trabalho', 'Dados salvos com sucesso!');
        return redirect()->back();

    }   
        
    public function professoresEdit($id)
    {
        $user = auth()->user();
        
        if(!$user->can('read: professores') ){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
       // $funcionario = Professor::findOrFail($id);
        
        $funcionario = Professor::with([
            'academico.especialidade',
            'academico.categoria',
            'academico.escolaridade',
            'academico.universidade',
        ])
        ->findOrFail($id);
        
        $academico = ProfessorAcedemico::where('professor_id', $funcionario->id)->first();
        $arquivo = Arquivo::where('level', '4')->where('model_id', $funcionario->id)->first();
        
        $paises = Paise::where('id', 6)->get();
        $provincias = Provincia::get();
        $direccao = DireccaoProvincia::findOrFail($user->shcools_id);
        $municipios = Municipio::get();
        
        // $departamento = Departamento::where('level', '2')->where('shcools_id', $direccao->id)->get();
        // $cargos = Cargo::where('level', '2')->where('shcools_id', $direccao->id)->get();
        // $contrato = FuncionariosControto::where('funcionarios_id', $funcionario->id)->first();
        
        
        $departamento = Departamento::where('level', '3')->get();
        $cargos = Cargo::where('level', '3')->get();
                
        $especialidades = Especialidade::get();
        $categorias = Categoria::get();
        $universidades = Universidade::get();
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
            "funcionario" => $funcionario,
            "academico" => $academico,
            "arquivo" => $arquivo,
            "especialidades" => $especialidades,
            "categorias" => $categorias,
            "universidades" => $universidades,
            "distritos" => $distritos,
            "escolaridade" => Escolaridade::get(),
            "formacao_academicos" => FormacaoAcedemico::get(),
            "usuario" => User::findOrFail(Auth::user()->id),
            
        ];

        return view('sistema.direccao-provincial.professores.edit', $headers);
    }    

    public function professoresUpdate(Request $request, $id)
    {
    
        $user = auth()->user();
        
        $direccao = DireccaoProvincia::findOrFail($user->shcools_id);
        
        
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
        

        $update = Professor::findOrFail($id);
        
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
        $update->level = 4;
        
    
        $udpateAcademico = ProfessorAcedemico::findOrFail($request->academico_id);
        $udpateAcademico->universidade_id = $request->universidade_id;
        $udpateAcademico->categoria_id = $request->categoria_id;
        $udpateAcademico->escolaridade_id = $request->escolaridade_id;
        $udpateAcademico->formacao_academica_id = $request->formacao_academica_id;
        $udpateAcademico->especialidade_id = $request->especialidade_id;
        $udpateAcademico->ano_trabalho = $request->ano_trabalho;
             
        
        $updateArquivo = Arquivo::where('level', '4')->find($request->arquivo_id);
       
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
            $updateArquivo->level = 4;
            $updateArquivo->update();
        }else{
            Arquivo::create([
                'model_id' => $update->id,
                'model_type' => 'professor',
                'certificado' => $imageNameCT,
                'bilheite' => $imageNameBI,
                'level' => 4,
                'atestado' => $imageNameAT,
                'outros' => $imageNameOD,
            ]);
        }
        
        $update->update();   
        $udpateAcademico->update();
        
        Alert::success('Bom Trabalho', 'Dados Actualizado com sucesso!');
        return redirect()->back();
    }
    
    public function professoresDuplicar($id)
    {
        $user = auth()->user();
        
        if(!$user->can('read: professores') ){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
       // $funcionario = Professor::findOrFail($id);
        
        $funcionario = Professor::with([
            'academico.especialidade',
            'academico.categoria',
            'academico.escolaridade',
            'academico.universidade',
        ])
        ->findOrFail($id);
        
        $academico = ProfessorAcedemico::where('professor_id', $funcionario->id)->first();
        $arquivo = Arquivo::where('level', '4')->where('model_id', $funcionario->id)->first();
        
        $paises = Paise::where('id', 6)->get();
        $provincias = Provincia::get();
        $direccao = DireccaoProvincia::findOrFail($user->shcools_id);
        $municipios = Municipio::get();
        
        // $departamento = Departamento::where('level', '2')->where('shcools_id', $direccao->id)->get();
        // $cargos = Cargo::where('level', '2')->where('shcools_id', $direccao->id)->get();
        // $contrato = FuncionariosControto::where('funcionarios_id', $funcionario->id)->first();
        
        
        $departamento = Departamento::where('level', '3')->get();
        $cargos = Cargo::where('level', '3')->get();
                
        $especialidades = Especialidade::get();
        $categorias = Categoria::get();
        $universidades = Universidade::get();
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
            "funcionario" => $funcionario,
            "academico" => $academico,
            "arquivo" => $arquivo,
            "especialidades" => $especialidades,
            "categorias" => $categorias,
            "universidades" => $universidades,
            "distritos" => $distritos,
            "escolaridade" => Escolaridade::get(),
            "formacao_academicos" => FormacaoAcedemico::get(),
            "usuario" => User::findOrFail(Auth::user()->id),
            
        ];

        return view('sistema.direccao-provincial.professores.duplicar', $headers);
    } 
    
    public function professoresDuplicarStore(Request $request)
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
            //'doc_bilheite'  => 'required',
           // 'doc_certificado'  => 'required',
           // 'doc_atestedao_medico'  => 'required',
           // 'doc_outros'  => 'required',
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
           // 'doc_bilheite.required'  => 'Campo Obrigatório',
            //'doc_certificado.required'  => 'Campo Obrigatório',
            //'doc_atestedao_medico.required'  => 'Campo Obrigatório',
           // 'doc_outros.required'  => 'Campo Obrigatório',
        ]);
        
                        
        $verificarBI = Professor::where('bilheite', $request->bilheite)->first();
        if($verificarBI){
            Alert::warning('Informação', 'Bilhete de identidade duplicado com sucesso!');
            return redirect()->back();
        }
        
        $codigo = time();
        
        $prefessor = Professor::create([
            'nome' => $request->nome,
            'sobre_nome' => $request->sobre_nome,
            'pai' => $request->pai,
            'mae' => $request->mae,
            'codigo' => $codigo,
            'email' => $request->email,
            'level' => '4',
            'nascimento' => $request->nascimento,
            'genero' => $request->genero,
            'estado_civil' => $request->estado_civil,
            'pais_id' => $request->pais_id,
            'provincia_id' => $request->provincia_id,
            'municipio_id' => $request->municipio_id,
            'bilheite' => $request->bilheite,
            'distrito_id' => $request->distrito_id,
            'emissiao_bilheite' => $request->emissiao_bilheite,
            'status' => "desactivo",
            'telefone' => $request->telefone,
            'endereco' => $request->endereco,
            'whatsapp' => $request->whatsapp,
            'facebook' => $request->facebook,
            'instagram' => $request->instagram,
            'outras_redes' => $request->outras_redes,
            "ano_lectivo_global_id" => $this->anolectivoActivoGlobal(),
        ]);

        ProfessorAcedemico::create([
            'universidade_id' => $request->universidade_id,
            'categoria_id' => $request->categoria_id,
            'escolaridade_id' => $request->escolaridade_id,
            'formacao_academica_id' => $request->formacao_academica_id,
            'especialidade_id' => $request->especialidade_id,
            'codigo' => $codigo,
            'professor_id' => $prefessor->id,
            "ano_lectivo_global_id" => $this->anolectivoActivoGlobal(),
        ]);
        
        $full = $request->nome . " " . $request->sobre_nome;
        $usernames = preg_split('/\s+/', strtolower($full), -1, PREG_SPLIT_NO_EMPTY);

        $user = User::create([
            'nome' => $request->nome . " ". $request->sobre_nome,
            'telefone' => $request->telefone,
            'usuario' => $request->bilheite,
            'password' => Hash::make('123456'),
            'acesso' => 'professor',
            'level' => 50,
            'numero_avaliacoes' => 3,
            'status' => 'Bloqueado',
            'login' => 'N',
            'email' => "{$request->bilheite}@gmail.com",
            'funcionarios_id' => $prefessor->id,
        ]);
        
        $role = Role::where('name', 'professor')->first();
        $user->assignRole($role);
        
        $text = "O Professor {$request->nome} {$request->sobre_nome} enviou uma candidatura para o ministério da educação";
        $text2 = "O Sr(a) acabou de enviar uma candidatura para o ministerio da educação ";
        
        Notificacao::create([
            'user_id' => $user->id,
            'destino' => NULL,
            'type_destino' => 'escola',
            'type_enviado' => 'provincial',
            'notificacao' => $text,
            'notificacao_user' => $text2,
            'status' => '0',
            'model_id' => $user->id,
            'model_type' => "candidatura",
            'shcools_id' => NULL
        ]);

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
            'model_id' => $prefessor->id,
            'model_type' => 'professor',
            'certificado' => $imageNameCT,
            'bilheite' => $imageNameBI,
            'level' => '4',
            'codigo' => $codigo,
            'atestado' => $imageNameAT,
            'outros' => $imageNameOD,
        ]);

        Alert::success('Bom Trabalho', 'Dados salvos com sucesso!');
        return redirect()->route('web.professores-provincial-duplicar', $prefessor->id);

    }   
            
    
}
