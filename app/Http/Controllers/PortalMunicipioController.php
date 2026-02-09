<?php

namespace App\Http\Controllers;

use App\Models\AnoLectivoGlobal;
use App\Models\Arquivo;
use App\Models\DireccaoMunicipal;
use App\Models\Categoria;
use App\Models\Escolaridade;
use App\Models\Especialidade;
use App\Models\Director;
use App\Models\FormacaoAcedemico;
use App\Models\Universidade;
use App\Models\Ensino;
use App\Models\Municipio;
use App\Models\Paise;
use App\Models\Professor;
use App\Models\Provincia;
use App\Models\Shcool;
use App\Models\User;
use App\Models\web\calendarios\Matricula;
use App\Models\web\classes\Classe;
use App\Models\web\cursos\Curso;
use App\Models\web\encarregados\EncarregadoEstudantes;
use App\Models\web\estudantes\Estudante;
use App\Models\Distrito;
use App\Models\SolicitacaoProfessor;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\calendarios\Servico;
use App\Models\web\classes\AnoLectivoClasse;
use App\Models\web\cursos\AnoLectivoCurso;
use App\Models\web\funcionarios\Funcionarios;
use App\Models\web\funcionarios\FuncionariosControto;
use App\Models\web\salas\AnoLectivoSala;
use App\Models\web\salas\Sala;
use App\Models\web\seguranca\ControloSistema;
use App\Models\web\turmas\FuncionariosTurma;
use App\Models\web\turmas\Turma;
use App\Models\web\turnos\AnoLectivoTurno;
use App\Models\web\turnos\Turno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Role;

class PortalMunicipioController extends Controller
{
    //
    
    use TraitHelpers;
    
        
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function home()
    {
        $user = auth()->user();
        
        $direccao = DireccaoMunicipal::findOrFail($user->shcools_id);
        
        $total_escola = Shcool::where('status', 'activo')
            // ->where('municipio_id', $direccao->municipio_id)
        ->count();
        
        // as escolas desta provincia
        $escolas = Shcool::where('status', 'activo')
            // ->where('municipio_id', $direccao->municipio_id)
        ->get(['id']);
        
        $total_estudante = Estudante::where('registro', 'confirmado')->count();

        $nossos_funcionarios = Funcionarios::where('status', 'activo')
            ->whereIn('level', ['3', '4'])
            // ->where('shcools_id', $direccao->id)
        ->count();

        $pais = Paise::where('name', 'Angola')->first();
        $provincias = Provincia::get();
        $usuario = User::findOrFail(Auth::user()->id);
                
        $solicitacoes = SolicitacaoProfessor::where('status', '0')
            ->with('professor', 'disciplina', 'classe', 'instituicao1', 'curso')
            ->where('level_destino', '3')
            ->where('instituicao_id', $direccao->id)
        ->get();
        
        $headers =  [
            "usuario" => $usuario,
            "total_escola" => $total_escola,
            "total_estudante" => $total_estudante,
            "nossos_funcionarios" => $nossos_funcionarios,
            "pais" => $pais,
            "provincias" => $provincias,
            "direccao" => $direccao,
            'solicitacoes' =>  $solicitacoes,
        ];

        return view('sistema.direccao-municipal.home', $headers);
    }
     
    public function criarEscolas()
    {
        $user = auth()->user();
        
        $direccao = DireccaoMunicipal::findOrFail($user->shcools_id);
        
        $usuario = User::findOrFail(Auth::user()->id);
        
        $paises = Paise::where('id', 6)->get();
        $provincias = Provincia::get();
        $municipios = Municipio::get();
        $ensinos = Ensino::get();
        $distritos = Distrito::get();
        
        $headers =  [
            "paises" => $paises,
            "provincias" => $provincias,
            "municipios" => $municipios,
            "distritos" => $distritos,
            "ensinos" => $ensinos,
            "titulo" => "Criar Escolas",
            "descricao" => env('APP_NAME'),
            "usuario" => $usuario,
        ];

        return view('sistema.direccao-municipal.escolas.create', $headers);
    }
    
    public function criarEscolasStore(Request $request)
    {
        $request->validate([
            'director' => 'required',
            'genero' => 'required',
            'estado_civil' => 'required',
            'bilheite' => 'required',
            'nome' => 'required',
            'documento' => 'required',
            'ensino_id' => 'required',
            'pais_id' => 'required',
            'provincia_id' => 'required',
            'municipio_id' => 'required',
            'numero_escola' => 'required',
        ]); 
        
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
        
            // verificações antes do cadastro da escola
            $escola_numero = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->where('numero_escola', $request->numero_escola)->first();
            
            if($escola_numero){
                Alert::warning('Informações', 'Este Nº da Escola já esta cadastrar!');
                return redirect()->back();
            }
        
            $bilheite = Director::where('bilheite', $request->bilheite)->first();
            
            if($bilheite){
                Alert::warning('Informações', 'Este Nº do Bilheite já esta cadastrar!');
                return redirect()->back();
            }         
                    
            $create = Shcool::create([
                'nome' => $request->nome,
                'cabecalho1' => $request->nome,
                'cabecalho2' => $request->nome,
                'director' => $request->director,
                'documento' => $request->documento,
                'site' => $request->site,
                'sigla' => $request->sigla,
                'status' => 'activo',
                'categoria' => $request->sector,
                'natureza' => "exemplo Geral",
                'ensino_id' =>$request->ensino_id,
                'pais_id' =>  $request->pais_id,
                'provincia_id' => $request->provincia_id,
                'municipio_id' => $request->municipio_id,
                'distrito_id' => $request->distrito_id,
                'modulo' => $request->modulo_id,
                'endereco' => $request->endereco,
                'decreto' => $request->decreto,
                'agua' => $request->agua,
                'electricidade' => $request->electricidade,
                'cantina' => $request->cantina,
                'biblioteca' => $request->biblioteca,
                'campo_desportivo' => $request->campo_desportivo,
                'internet' => $request->internet,
                'farmacia' => $request->farmacia,
                'zip' => $request->zip,
                'computadores' => $request->computadores,
                'laboratorio' => $request->laboratorio,
                'casas_banho' => $request->casas_banho,
                'transporte' => $request->transporte,
                'telefone1' => trim($request->telefone),
                'telefone2' => "000-000-000",
                'telefone3' => "000-000-000",
                'logotipo' => NULL,
                'logotipo2' => NULL,
                'logotipo_assinatura_director' => NULL,
                'numero_escola' => $request->numero_escola,
                'ano_lectivo_global_id' => $this->anolectivoActivoGlobal(),
            ]);
          
            $createDirector = Director::create([
                'nome' => $request->director,
                'status' => 'activo',
                'bilheite' => $request->bilheite,
                'genero' => $request->genero,
                'estado_civil' => $request->estado_civil,
                'especialidade' => $request->especialidade,
                'descricao' => $request->descricao,
                'curso' => $request->curso,
                'level' => '4',
                'instituicao_id' => $create->id,
            ]);
            /**
             * CRIAR ANO LECTIVO
             */
            $data_inicio_ano_lectivo = date("Y");
            $data_final_ano_lectivo = date("Y") + 1;
                        
            AnoLectivo::create([
                'ano' => $data_inicio_ano_lectivo . "/" . $data_final_ano_lectivo,
                'serie' => "2425",
                'inicio' => $data_inicio_ano_lectivo."-09-05",
                'final' => $data_final_ano_lectivo."-09-05",
                'status' => 'activo',
                'shcools_id' => $create->id,
            ]);
                        
            Servico::create([
                "servico" => "Matricula",
                "contas" => "receita",
                "tipo" => "S",
                "motivo_id" => 4,
                "taxa_id" => 1,
                "ordem" => 1,
                "conta" => "62.1.1",
                "unidade" => "uni",
                "status" => 'activo',
                "shcools_id" => $create->id,
            ]);
            
            Servico::create([
                "servico" => "Confirmação",
                "contas" => "receita",
                "tipo" => "S",
                "unidade" => "uni",
                "motivo_id" => 4,
                "ordem" => 2,
                "conta" => "62.1.2",
                "taxa_id" => 1,
                "status" => 'activo',
                "shcools_id" => $create->id,
            ]);
          
            Servico::create([
                "servico" => "Propinas",
                "contas" => "receita",
                "status" => 'activo',
                "unidade" => "uni",
                "ordem" => 3,
                "conta" => "62.1.3",
                "tipo" => "S",
                "motivo_id" => 4,
                "taxa_id" => 1,
                "shcools_id" => $create->id,
            ]);
            
            Servico::create([
                "servico" => "Diversos",
                "contas" => "receita",
                "status" => 'activo',
                "unidade" => "uni",
                "ordem" => 4,
                "conta" => "62.1.4",
                "tipo" => "S",
                "motivo_id" => 4,
                "taxa_id" => 1,
                "shcools_id" => $create->id,
            ]);
            
            $user = User::create([
                'nome' => trim($request->nome),
                'email' => trim($request->email),
                'usuario' => trim($request->numero_escola),
                'telefone' => trim($request->telefone),
                'password' => Hash::make(trim($request->numero_escola)),
                'acesso' => "admin",
                'level' => 2,
                'numero_avaliacoes' => 3,
                'status' => "Bloqueado",
                'funcionarios_id' => $createDirector->id,
                'shcools_id' => $create->id,
            ]);
            
            $role = Role::where('name', 'admin')->first(); 
            $user->assignRole($role);
                            
            $dataActual = date("Y-m-d");
            
            ControloSistema::create([
                'inicio' => $dataActual,
                'final' => date("Y-m-d", strtotime($dataActual . "+15days")),
                'level' => "4",
                'tipo' => "ESCOLA",
                'user_id' => Auth::user()->id,
                'shcools_id' => $create->id,
            ]);
                                    
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        Alert::success('Atenção', 'Conta Criada com sucesso');
        return redirect()->back()->with('danger', 'As duas novas senhas não podem ser diferentes');
        
    }
    
    public function mundarStatusEscolas($id)
    {
    
        $user = auth()->user();
        
        if(!$user->can('update: escola')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $escola = Shcool::findOrFail($id);
        
        $status = "";
        
        if($escola->status == 'activo'){
            $status = "desactivo";
        }
        
        if($escola->status == 'desactivo'){
            $status = "activo";
        }
        
        $escola->status = $status;
        $escola->update();
        
        Alert::success('Bom Trabalho', 'Estado da escola Mudado com sucesso !!');
        return redirect()->back();
        
    }
    
    public function editarEscolas($id)
    {
        $user = auth()->user();
        
        if(!$user->can('update: escola')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $escola = Shcool::with('municipio', 'pais', 'provincia')->findOrFail($id);
        $director =  Director::where('level', '4')->where('instituicao_id', $escola->id)->first();
        
        $usuario = User::findOrFail(Auth::user()->id);
        
        $paises = Paise::where('id', 6)->get();
        $provincias = Provincia::get();
        $municipios = Municipio::get();
        $ensinos = Ensino::get();
        $distritos = Distrito::get();
        
        $headers =  [
            "paises" => $paises,
            "provincias" => $provincias,
            "municipios" => $municipios,
            "distritos" => $distritos,
            "ensinos" => $ensinos,
            "titulo" => "Editar Escolas",
            "descricao" => env('APP_NAME'),
            "usuario" => $usuario,
            "escola" => $escola,
            "director" => $director,
        ];

        return view('sistema.direccao-municipal.escolas.edit', $headers);
    }
    
    public function editEscolasUpdate(Request $request, $id)
    {
        $request->validate([
            'director' => 'required',
            'genero' => 'required',
            'estado_civil' => 'required',
            'bilheite' => 'required',
            'nome' => 'required',
            'documento' => 'required',
            'ensino_id' => 'required',
            'modulo_id' => 'required',
            'pais_id' => 'required',
            'provincia_id' => 'required',
            'municipio_id' => 'required',
            'numero_escola' => 'required',
        ]); 
        
        $escola = Shcool::findOrFail($id);
        
        $director = Director::findOrFail($request->director_id);
        
        try {
            DB::beginTransaction();
                
            $escola->nome = trim($request->nome);
            $escola->cabecalho1 = trim($request->nome);
            $escola->cabecalho2 = trim($request->nome);
            $escola->director = trim($request->director);
            $escola->documento = trim($request->documento);
            $escola->site = $request->site;
            $escola->sigla = $request->sigla;
            $escola->modulo = $request->modulo_id;
            $escola->categoria = $request->sector;
            $escola->natureza = "exemplo Geral";
            $escola->ensino_id = $request->ensino_id;
            $escola->pais_id =  $request->pais_id;
            $escola->provincia_id = $request->provincia_id;
            $escola->municipio_id = $request->municipio_id;
            $escola->distrito_id = $request->distrito_id;
            $escola->endereco = $request->endereco;
            $escola->decreto = $request->decreto;
            $escola->agua = $request->agua;
            $escola->electricidade = $request->electricidade;
            $escola->cantina = $request->cantina;
            $escola->biblioteca = $request->biblioteca;
            $escola->campo_desportivo = $request->campo_desportivo;
            $escola->internet = $request->internet;
            $escola->farmacia = $request->farmacia;
            $escola->zip = $request->zip;
            $escola->computadores = $request->computadores;
            $escola->laboratorio = $request->laboratorio;
            $escola->casas_banho = $request->casas_banho;
            $escola->transporte = $request->transporte;
            $escola->telefone1 = trim($request->telefone);
            $escola->telefone2 = "000-000-000";
            $escola->telefone3 = "000-000-000";
            $escola->logotipo = NULL;
            $escola->logotipo2 = NULL;
            $escola->logotipo_assinatura_director = NULL;
            $escola->numero_escola = $request->numero_escola;
          
            $director->nome = $request->director;
            $director->bilheite = $request->bilheite;
            $director->genero = $request->genero;
            $director->estado_civil = $request->estado_civil;
            $director->especialidade = $request->especialidade;
            $director->descricao = $request->descricao;
            $director->curso = $request->curso;
            
            $director->update();
            $escola->update();
    
            $user = User::where('acesso', 'admin')->where('level', '2')->where('funcionarios_id', $director->id)->where('shcools_id', $escola->id)->first();
            
            if( $user ) {
                $user->usuario = trim($request->numero_escola);
                $user->nome = trim($request->nome);
                $user->password = Hash::make(trim($request->numero_escola));
                $user->telefone = trim($request->telefone);
                $user->email = trim($request->email);
                $user->update();
            }
                        
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        Alert::success('Bom Trabalho', 'Dados actualizados com sucesso!');
        return redirect()->back();
        
    }
    
    public function privacidade()
    {
        $usuario = User::findOrFail(Auth::user()->id);
        
        $headers =  [
            "titulo" => "Privacidade",
            "descricao" => env('APP_NAME'),
            "usuario" => $usuario,
        ];

        return view('sistema.direccao-municipal.privacidade', $headers);
    }

    public function privacidadeUpdate(Request $request, $id)
    {

        $request->validate([
            'password_1' => 'required',
            'password_2' => 'required',
            'password_3' => 'required',
            'user' => 'required',
        ]);

        $usuario = User::findOrFail($id);
        
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
    
            if (!Hash::check($request->password_1, $usuario->password)) {
                Alert::warning('Atenção', 'Senha Actual Incorrecta');
                return redirect()->route('app.privacidade-municipal')->with('danger', 'Senha Actual Incorrecta');
            }      
            
            if ($request->password_2 != $request->password_3) {
                Alert::warning('Atenção', 'As duas novas senhas não podem ser diferentes');
                return redirect()->route('app.privacidade-municipal')->with('danger', 'As duas novas senhas não podem ser diferentes');
            } 
    
            $usuario->password = Hash::make($request->password_2);
            $usuario->usuario = $request->user;
            $usuario->email = $request->email;
            $usuario->nome = $request->nome;
            $usuario->telefone = $request->telefone;
            $usuario->update();
            
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }


        if($usuario->update()){
            Alert::success('Bom Trabalho', 'Credências actualizados com sucesso, Usar da Proxíma vez que Entrar no sistema');
            return redirect()->route('app.privacidade-municipal')->with('message', 'Credências actualizados com sucesso, Usar da Proxíma vez que Entrar no sistema');
        }
    }
    
    
    /**
        UTILIZADORES
    */
    public function utilizadoresIndex()
    {
    
        $user = auth()->user();
        
        if(!$user->can('read: utilizador')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $usuarios = User::with(['roles'])
            ->where('level', 400)
        ->get();
        
        $headers =  [
            "titulo" => "Utilizadores",
            "descricao" => env('APP_NAME'),
            "usuarios" => $usuarios,
        ];

        return view('sistema.direccao-municipal.utilizadores.index', $headers);
    }

    public function utilizadoresStore(Request $request)
    {
        
        $user = auth()->user();
        
        if(!$user->can('create: utilizador')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        
        $request->validate([
            'password_2' => 'required',
            'password_3' => 'required',
            'user' => 'required',
            'role_id' => 'required',
        ]); 
        
        
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
                
            if ($request->password_2 != $request->password_3) {
                Alert::warning('Atenção', 'As duas novas senhas não podem ser diferentes');
                return redirect()->route('app.municipal-utilizadores-create')->with('danger', 'As duas novas senhas não podem ser diferentes');
            } 
            
            $direccao = DireccaoMunicipal::findOrFail($user->shcools_id);
    
            $roles = Role::findById($request->role_id);
    
            $user = User::create([
                "password" => Hash::make($request->password_2),
                "usuario" => $request->user,
                "numero_avaliacoes" => 3, 
                "level" => 400,
                "acesso" => 'admin',
                "login" => 'N',
                "status" => $request->status,
                "nome" => $request->nome,
                "email" => $request->email,
                "telefone" => $request->telefone,
                'shcools_id' => $direccao->id
            ]);
    
            $user->assignRole($roles);
        // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
       
        Alert::success('Bom Trabalho', 'Credências actualizados com sucesso, Usar da Proxíma vez que Entrar no sistema');
        return redirect()->route('app.municipal-utilizadores-create')->with('message', 'Utilizador cadastrado com sucesso!');
        
    }
    
    public function utilizadoresCreate()
    {
        
        $user = auth()->user();
        
        if(!$user->can('create: utilizador')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $usuario = User::findOrFail(Auth::user()->id);

        $roles = Role::get();

        $headers =  [
            "titulo" => "Cadastrar Utilizadores",
            "descricao" => env('APP_NAME'),
            "usuario" => $usuario,
            "roles" => $roles,
        ];

        return view('sistema.direccao-municipal.utilizadores.criar', $headers);
    }  
    
    public function utilizadoresEdit($id)
    {
        
        $user = auth()->user();
        
        if(!$user->can('update: utilizador')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        
        $usuario = User::findOrFail($id);
        

        $roles = Role::get();
        $role = null;
        if(count($usuario->roles) != 0){
            $role = $usuario->roles[0];
        }
        
        $headers =  [
            "titulo" => "Editar Utilizadores",
            "descricao" => env('APP_NAME'),
            "usuario" => $usuario,
            "role" => $role,
            "roles" => $roles,
        ];

        return view('sistema.direccao-municipal.utilizadores.editar', $headers);
    }  


    public function utilizadoresUpdate(Request $request, $id)
    {
        
        $user = auth()->user();
        
        if(!$user->can('update: utilizador')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $user = User::findOrFail($id);
        
        foreach ($user->roles as $role) {
            $user->removeRole($role);
        }
        
        $new_role = Role::findById($request->role_id);
        $user->assignRole($new_role);
        
        $user->status  = $request->status;
        $user->nome  = $request->nome;
        $user->email  = $request->email;
        $user->telefone  = $request->telefone;
        $user->numero_avaliacoes  = $request->numero_avaliacoes;
        
        $user->update();
       
        return redirect()->route('app.municipal-utilizadores-index')->with('message', 'Utilizador actualizado com sucesso!');
        
    }
    
    // END UTILIZADORES
    
    
    public function listagemEscolas(Request $request, $id = null)
    {
        $user = auth()->user();
        
        if(!$user->can('read: escola')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        if($id == null) {
            $id = "";
        }
    
        $ensinos = Ensino::get();
        $distritos = Provincia::get();
   
        $escolas = Shcool::when($request->ensino_id, function($query, $value){
            $query->where('ensino_id', $value);
        })
        ->when($request->categoria, function($query, $value){
            $query->where('categoria', $value);
        })
        ->when($request->provincia_id, function ($query, $value){
            $query->where('provincia_id', $value);
        })
        ->when($id, function ($query, $value){
            $query->where('provincia_id', $value);
        })
        ->with(['municipio', 'pais', 'ensino'])
        ->orderBy('id', 'desc')
        ->get();

        $headers =  [
            "usuario" => $user,
            "escolas" => $escolas,
            "distritos" => $distritos,
            "ensinos" => $ensinos,
            "requests" => $request->all('provincia_id', 'ensino_id', 'categoria'),
        ];

        return view('sistema.direccao-municipal.escolas.listagem-escolas', $headers);
    }

    //  Mais informações 
    public function informacaoEscolar($id)
    {
        $user = auth()->user();
        
        $direccao = DireccaoMunicipal::findOrFail($user->shcools_id);
        
        if(!$user->can('read: escola')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $escola = Shcool::with('pais','provincia', 'municipio', 'ensino', 'distrito')->findOrFail($id);
        $director = Director::where('instituicao_id', $escola->id)->where('level', '4')->first();

        $matriculas = Estudante::where('registro', 'confirmado')->where('shcools_id', $escola->id)->count();
        
        $ano_lectivo_activo = AnoLectivo::where('shcools_id', $escola->id)->where('status', 'activo')->first();
        
        $turmas = Turma::where('ano_lectivos_id', $ano_lectivo_activo->id ?? "")->where('shcools_id', $escola->id)->count();
        $salas = AnoLectivoSala::where('ano_lectivos_id', $ano_lectivo_activo->id ?? "")->where('shcools_id', $escola->id)->count();
        $cursos = AnoLectivoCurso::where('ano_lectivos_id', $ano_lectivo_activo->id ?? "")->where('shcools_id', $escola->id)->count();
        $classes = AnoLectivoClasse::where('ano_lectivos_id', $ano_lectivo_activo->id ?? "")->where('shcools_id', $escola->id)->count();
        $turnos = AnoLectivoTurno::where('ano_lectivos_id', $ano_lectivo_activo->id ?? "")->where('shcools_id', $escola->id)->count();
        
        
        $funcionarios = FuncionariosControto::where('level', '4')->where('shcools_id', $escola->id)->count();
        $utilizadores = User::where('level', '4')->where('shcools_id', $escola->id)->count();
        
        $headers =  [
            "escola" => $escola,
            "usuario" => $user,
            "matriculas" => $matriculas,
            "turmas" => $turmas,
            "funcionarios" => $funcionarios,
            "utilizadores" => $utilizadores,
            "direccao" => $direccao,
            "director" => $director,
            
            "turmas" => $turmas,
            "salas" => $salas,
            "cursos" => $cursos,
            "classes" => $classes,
            "turnos" => $turnos,
            
        ];

        return view('sistema.direccao-municipal.escolas.mais-informacao', $headers);

    }
    
 
    //  Mais informações 
    public function activarLicencaEscola($id)
    {
        $user = auth()->user();
        
        if(!$user->can('read: escola')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $escola = Shcool::with('pais','provincia', 'municipio', 'ensino', 'distrito')->findOrFail($id);
        $contrato = ControloSistema::where('shcools_id', $escola->id)->where('tipo', "ESCOLA")->where('level', "4")->first();
             
        $headers =  [
            "titulo" => "Licença da escola",
            "escola" => $escola,
            "usuario" => $user,
            "contrato" => $contrato,
        ];

        return view('sistema.direccao-municipal.escolas.activar-licenca-escola', $headers);
    }   
    
    
 
    //  Mais informações 
    public function activarLicencaEscolaPost(Request $request)
    {
        $request->validate([
            'data_inicio' => 'required',
            'data_final' => 'required',
            'licenca_id' => 'required',
        ]);
        
        
        $licenca = ControloSistema::findOrFail($request->licenca_id);
                
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
        
            $licenca->inicio = $request->data_inicio;
            $licenca->final = $request->data_final;
            $licenca->update();
                
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
        
        Alert::success('Bom trabalho', 'Licença da escola activada com sucesso!');
        return redirect()->back();
        
    }      
    
    public function listagemEstudantes($id)
    {
        $user = auth()->user();
        
        if(!$user->can('read: estudante')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
    
        $escola = Shcool::findOrFail($id);

        $matriculas = Estudante::where('registro', 'confirmado')
            ->where('shcools_id', $id)
            ->get()
            ->sortBy('nome');

        $headers =  [
            "titulo" => "Listagem dos estudantes da escola: {$escola->nome}",
            "descricao" => env('APP_NAME'),
            "matriculas" => $matriculas,
            "escola" => $escola,
            "usuario" => User::findOrFail(Auth::user()->id),
        ];
        
        return view('sistema.direccao-municipal.escolas.estudantes', $headers);
    }

    public function informacaoEstudante($id)
    {
    
        $user = auth()->user();
        
        if(!$user->can('read: estudante')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $estudante = Estudante::findOrFail($id);

        $matricula = Matricula::where([
            ['estudantes_id', '=', $estudante->id],
        ])->first();

        $turma = Turma::where([
            ['cursos_id', '=', $matricula->cursos_id],
            ['classes_id', '=', $matricula->classes_id],
            ['turnos_id', '=', $matricula->turnos_id],
        ])->first();

        if($turma){
            $sala = Sala::findOrFail($turma->salas_id);
        }else{
            $turma = null;
            $sala = null;
        }

        $encarregado = EncarregadoEstudantes::where([
            ['estudantes_id', '=', $estudante->id],
        ])
        ->join('tb_encarregados', 'tb_encarregado_estudantes.encarregados_id', '=', 'tb_encarregados.id')
        ->first();
        
        $headers =  [
            "titulo" => "Informações geral do estudante",
            "descricao" => env('APP_NAME'),
            'estudante' => $estudante,
            'curso' => Curso::findOrFail($matricula->cursos_id),
            'turno' => Turno::findOrFail($matricula->turnos_id),
            'classe' => Classe::findOrFail($matricula->classes_id),
            'escola' => Shcool::with(['provincia', 'municipio'])->findOrFail($matricula->shcools_id),
            'sala' => $sala,
            'turma' => $turma,
            'matricula' => $matricula,
            'encarregado' => $encarregado,            
        ];

        return view('sistema.direccao-municipal.escolas.mais-informacoes-estudante', $headers);
    }

    public function listagemProfessores($id)
    {
        $user = auth()->user();
        
        if(!$user->can('read: professores')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $escola = Shcool::findOrFail($id);
        $professores = FuncionariosControto::where('level', '4')->with('funcionario.academico')->where('shcools_id', $escola->id)->get();

        $headers =  [
            "titulo" => "Listagem dos Professores {$escola->nome}",
            "descricao" => env('APP_NAME'),
            "professores" => $professores,
            "escola" => $escola,
            "usuario" => User::findOrFail(Auth::user()->id),
        ];
        
        return view('sistema.direccao-municipal.escolas.professores', $headers);
    }

    public function informacaoProfessores($id)
    {
        $user = auth()->user();
        
        if(!$user->can('read: professores')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $professor = Professor::where('level', 4)->with('nacionalidade')
        ->with('provincia', 'distrito', 'municipio')
        ->with('academico.escolaridade')
        ->with('academico.especialidade')
        ->with('academico.categoria')
        ->with('academico.escolaridade')
        ->with('academico.universidade')
        ->findOrFail($id);
        
        $contrato = FuncionariosControto::where('level', '4')
        ->with('departamento', 'cargos')
        ->where('funcionarios_id', $professor->id)
        ->first();
        
        
        $escolas = FuncionariosControto::where('level', '4')->whereIn('funcionarios_id', [$professor->id])->distinct()->orderBy('shcools_id', "desc")->get(['shcools_id']);
    
        $infor_escola = Shcool::with('provincia')->whereIn('id', $escolas)->get();
        $arquivo = Arquivo::where('level', $professor->level)
        ->where('model_type', 'professor')
        ->where('model_id', $professor->id)
        ->first();

        $headers =  [
            "titulo" => "Informações geral do Professor",
            'professor' => $professor,
            'contrato' => $contrato,
            'escolas' => $escolas,
            'documentos' => $arquivo,
            'infor_escola' => $infor_escola,
        ];

        return view('sistema.direccao-municipal.escolas.mais-informacoes-professor', $headers);
    }

    public function informacaoTurmaProfessores($id, $escola = null)
    {
        $professor = Professor::with('nacionalidade')->with('provincia')->with('academico.escolaridade')->with('academico.formacao')->findOrFail($id);
        $escolas = FuncionariosControto::whereIn('funcionarios_id', [$professor->id])->distinct()->orderBy('shcools_id', "desc")->get(['shcools_id']);

        $infor_escola = Shcool::whereIn('id', $escolas)->get();
        $shcool = Shcool::findOrFail($escola);

        $turmas = FuncionariosTurma::where([
            ['tb_turmas_funcionarios.funcionarios_id', '=', $professor->id],
            ['tb_turmas_funcionarios.shcools_id', '=', $shcool->id],
        ])
        ->join('tb_disciplinas', 'tb_turmas_funcionarios.disciplinas_id', '=', 'tb_disciplinas.id')
        ->join('tb_turmas', 'tb_turmas_funcionarios.turmas_id', '=', 'tb_turmas.id')
        ->select('tb_disciplinas.disciplina', 'tb_disciplinas.id AS idDis', 'tb_turmas_funcionarios.cargo_turma', 'tb_turmas.turma',  
        'tb_turmas.id AS idTurma')
        ->get();

        $headers =  [
            "titulo" => "Informações geral do Professor",
            "descricao" => "gestão de discipinas",
            'professor' => $professor,
            'escolas' => $escolas,
            'shcool' => $shcool,
            'infor_escola' => $infor_escola,
            'turmas' => $turmas,
        ];

        return view('sistema.direccao-municipal.escolas.mais-informacoes-turmas', $headers);
    }
    
    
    public function listagemEstudantesGeral(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('read: estudante')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $get_escolas = Shcool::get(["id", "nome"]);
        
        $estudantes = Estudante::with(["escola.ano"])
            ->when($request->search_ano_lectivos_id, function($query, $value){
                $query->where("ano_lectivo_global_id", $value);
            })
            ->when($request->shcools_id, function($query, $value){
                $query->where("shcools_id", $value);
            })
            ->when($request->genero, function($query, $value){
                $query->where("genero", $value);
            })
            ->where("registro", "confirmado")
        ->get();
        
        $headers =  [
            "titulo" => "Listagem dos estudantes da provincia",
            "descricao" => env("APP_NAME"),
            "estudantes" => $estudantes,
            "escolas" => $get_escolas,
            "anos_lectivos" => AnoLectivo::get(),
            "turnos" => Turno::get(),
            "classes" => Classe::get(),
            "usuario" => User::findOrFail(Auth::user()->id),
            "requests" => $request->all("distrito_id", "municipio_id", "ano_lectivos_id", "shcools_id","genero"),
        ];
        
        return view("sistema.direccao-municipal.escolas.estudantes-geral", $headers);
    }

}
