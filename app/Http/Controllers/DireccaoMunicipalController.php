<?php

namespace App\Http\Controllers;

use App\Models\DireccaoMunicipal;
use App\Models\DireccaoProvincia;
use App\Models\Director;
use App\Models\Distrito;
use App\Models\Municipio;
use App\Models\Notificacao;
use App\Models\Paise;
use App\Models\Provincia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Role;

class DireccaoMunicipalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $user = auth()->user();
        
        /*if(!$user->can('read: professores')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }**/
        
        $direccao = DireccaoProvincia::findOrFail($user->shcools_id);
        $municipios = Municipio::where('provincia_id', $direccao->provincia_id)->get();
        $municipios_ids = Municipio::where('provincia_id', $direccao->provincia_id)->get(['id']);
        
       $direccao_municipal = DireccaoMunicipal::when($request->municipal_id, function($query, $value){
            $query->where('municipio_id', $value);
       })
       ->with('provincia', 'distrito', 'municipio')
       ->whereIn('municipio_id', $municipios_ids)
       ->get();

       $headers =  [
           "usuario" => $user,
           "direccoes" => $direccao_municipal,
           "municipios" => $municipios,
           "requests" => $request->all('municipal_id'),
       ];
       
       return view('sistema.direccao-municipal.index', $headers);
       
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();
        
        // if(!$user->can('read: escola')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        
        $direccao = DireccaoProvincia::findOrFail($user->shcools_id);
        
        $paises = Paise::where('id', 6)->get();
        $provincias = Provincia::where('id', $direccao->provincia_id)->get();
        $municipios = Municipio::where('provincia_id', $direccao->provincia_id)->get();
        $distritos = Distrito::get();
        
        $headers = [ 
            "titulo" => "Cadastrar direções municipal",
            "descricao" => env('APP_NAME'),
            "usuario" => Auth::user()->id,
            "paises" => $paises,
            "provincias" => $provincias,
            "municipios" => $municipios,
            "distritos" => $distritos,
        ];
        
        return view('sistema.direccao-municipal.create', $headers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            
        $request->validate(
            [
                'director' => 'required',
                'genero' => 'required',
                'estado_civil' => 'required',
                'bilheite' => 'required',
                'nome' => 'required',
                'documento' => 'required',
                'pais_id' => 'required',
                'provincia_id' => 'required',
                'municipio_id' => 'required',
                'distrito_id' => 'required',
            ],
            [
                'director.required' => "Senha Obrigatória",
                'genero.required' => "Senha Obrigatória",
                'estado_civil.required' => "Senha Obrigatória",
                'bilheite.required' => "Senha Obrigatória",
                'nome.required' => "Senha Obrigatória",
                'documento.required' => "Senha Obrigatória",
                'pais_id.required' => "Senha Obrigatória",
                'provincia_id.required' => "Senha Obrigatória",
                'municipio_id.required' => "Senha Obrigatória",
                'distrito_id.required' => "Senha Obrigatória",
            ]
        ); 
        
    
        $user = auth()->user();
        
        // if(!$user->can('read: escola')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
       $verificar = DireccaoMunicipal::where('municipio_id', $request->municipio_id)->first();
       
        if($verificar){
            Alert::error('Duplicação', 'Já existe uma direccção cadastrada com este municipio, contacte o administrador!');
            return redirect()->back();
        }

        if (!empty($request->file('logotipo'))) {
            $image = $request->file('logotipo');
            $imageName = time() .'1.'. $image->extension();
            $image->move(public_path('assets/images'), $imageName);
        }else{
            $imageName = Null;
        }

        if (!empty($request->file('logotipo_assinatura_director'))) {
            $image3 = $request->file('logotipo_assinatura_director');
            $imageName3 = time() .'2.'. $image3->extension();
            $image3->move(public_path('assets/images'), $imageName3);
        }else{
            $imageName3 = Null;
        }

        $municipio = Municipio::findOrFail($request->municipio_id);

        $create = DireccaoMunicipal::create([
            'nome' => $request->nome,
            'director' => $request->director,
            'sigla' => $request->sigla, 
            'status' => 'activo',
            'decreto' => $request->decreto, 
            'documento' => $request->documento,
            'site' => $request->site,
            'pais_id' => $request->pais_id, 
            'provincia_id' => $request->provincia_id, 
            'municipio_id' => $request->municipio_id, 
            'distrito_id' => $request->distrito_id, 
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
            'telefone1' => $request->telefone1, 
            'telefone2' => $request->telefone2, 
            'endereco' => $request->endereco,
            'logotipo' => $imageName ,
            'logotipo_assinatura_director' => $imageName3,
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
            'level' => 3,
            'instituicao_id' => $create->id,
        ]);
        
        
        $usernames = preg_split('/\s+/', strtolower($municipio->nome), -1, PREG_SPLIT_NO_EMPTY);
        // $nome = head($usernames) . '.' . last($usernames);
        $nome = head($usernames);
        $email = strtolower($nome). "municipal@gmail.com";
        
        $user = User::create([
            'nome' => $request->nome,
            'telefone' => $request->telefone1,
            'usuario' => strtolower($nome) . "@conta",
            'password' => Hash::make(strtolower($nome). "@conta"),
            'acesso' => "admin",
            'level' => '400',
            'login' => 'N',
            'numero_avaliacoes' => '0',
            'status' => "Bloqueado",
            'email' => $email,
            'funcionarios_id' => $createDirector->id,
            'shcools_id' =>  $create->id,
        ]);

        $role = Role::where('name', 'admin')->first();
        $user->assignRole($role);
        
        $text = Auth::user()->nome.", cadastrou uma direcção Municipal {$request->nome}.";
        $text2 = "O Sr(a) acabou de aprovar a matricula de um estudante";
        
        Notificacao::create([
            'user_id' => Auth::user()->id,
            'destino' => NULL,
            'type_destino' => 'ministerio',
            'type_enviado' => 'ministerio',
            'notificacao' => $text,
            'notificacao_user' => $text2,
            'status' => '0',
            'model_id' => $create->id,
            'model_type' => "direccao municipal",
        ]);

        Alert::success('Bom Trabalho', 'Dodos registrado com sucesso');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DireccaoMunicipal  $direccaoMunicipal
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = auth()->user();
        
        // if(!$user->can('read: escola')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        $direccao = DireccaoProvincia::findOrFail($user->shcools_id);
        
        $director = Director::where('level', '3')->where('instituicao_id', $direccao->id)->first();
        
        $edit = DireccaoMunicipal::with('provincia', 'pais', 'municipio')->findOrFail(Crypt::decrypt($id));
        
        
        $headers = [ 
            "titulo" => "Visualizar direcçãp províncial",
            "descricao" => env('APP_NAME'),
            "usuario" => Auth::user()->id,
            "data" => $edit,
            "director" => $director,
            "direccao" => $direccao,
        ];
        
        return view('sistema.direccao-municipal.show', $headers);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DireccaoMunicipal  $direccaoMunicipal
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    
        $user = auth()->user();
        
        // if(!$user->can('read: escola')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        $edit = DireccaoMunicipal::findOrFail(Crypt::decrypt($id));
        
        $paises = Paise::where('id', 6)->get();
        $direccao = DireccaoProvincia::findOrFail($user->shcools_id);
        $director = Director::where('level', '3')->where('instituicao_id', $user->shcools_id)->first();
        
        $provincias = Provincia::where('id', $direccao->provincia_id)->get();
        
        $municipios = Municipio::where('provincia_id', $direccao->provincia_id)->get();
        $distritos = Distrito::get();
        
        $headers = [ 
            "titulo" => "Editar direções provínciais",
            "descricao" => env('APP_NAME'),
            "usuario" => Auth::user()->id,
            "paises" => $paises,
            "provincias" => $provincias,
            "municipios" => $municipios,
            "distritos" => $distritos,
            "director" => $director,
            "data" => $edit,
        ];
        
        return view('sistema.direccao-municipal.edit', $headers);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DireccaoMunicipal  $direccaoMunicipal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'director' => 'required',
                'genero' => 'required',
                'estado_civil' => 'required',
                'bilheite' => 'required',
                'nome' => 'required',
                'documento' => 'required',
                'pais_id' => 'required',
                'provincia_id' => 'required',
                'municipio_id' => 'required',
                'distrito_id' => 'required',
            ],
            [
                'director.required' => "Senha Obrigatória",
                'genero.required' => "Senha Obrigatória",
                'estado_civil.required' => "Senha Obrigatória",
                'bilheite.required' => "Senha Obrigatória",
                'nome.required' => "Senha Obrigatória",
                'documento.required' => "Senha Obrigatória",
                'pais_id.required' => "Senha Obrigatória",
                'provincia_id.required' => "Senha Obrigatória",
                'municipio_id.required' => "Senha Obrigatória",
                'distrito_id.required' => "Senha Obrigatória",
            ]
        ); 
    
        $user = auth()->user();
        
        // if(!$user->can('read: escola')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        

        if (!empty($request->file('logotipo'))) {
            $image = $request->file('logotipo');
            $imageName = time() .'1.'. $image->extension();
            $image->move(public_path('assets/images'), $imageName);
        }else{
            $imageName = $request->logotipo_guardado;
        }

        if (!empty($request->file('logotipo_assinatura_director'))) {
            $image3 = $request->file('logotipo_assinatura_director');
            $imageName3 = time() .'2.'. $image3->extension();
            $image3->move(public_path('assets/images'), $imageName3);
        }else{
            $imageName3 = $request->logotipo_assinatura_director_guardado;
        }


        $update = DireccaoMunicipal::findOrFail(Crypt::decrypt($id));
        $update->nome = $request->nome;
        $update->director = $request->director;
        $update->sigla = $request->sigla; 
        $update->status = 'activo';
        $update->decreto = $request->decreto; 
        $update->documento = $request->documento;
        $update->site = $request->site;
        $update->pais_id = $request->pais_id; 
        $update->provincia_id = $request->provincia_id; 
        $update->municipio_id = $request->municipio_id; 
        $update->distrito_id = $request->distrito_id; 
        $update->telefone1 = $request->telefone1; 
        $update->telefone2 = $request->telefone2; 
        $update->endereco = $request->endereco;
        $update->logotipo = $imageName;
        $update->logotipo_assinatura_director = $imageName3;
        $update->agua = $request->agua;
        $update->electricidade = $request->electricidade;
        $update->cantina = $request->cantina;
        $update->biblioteca = $request->biblioteca;
        $update->campo_desportivo = $request->campo_desportivo;
        $update->internet = $request->internet;
        $update->farmacia = $request->farmacia;
        $update->zip = $request->zip;
        $update->computadores = $request->computadores;
        $update->laboratorio = $request->laboratorio;
        $update->casas_banho = $request->casas_banho;
        $update->transporte = $request->transporte;
        
        $update_director = Director::findOrFail($request->director_id);
        $update_director->nome = $request->director;
        $update_director->status = 'activo';
        $update_director->bilheite = $request->bilheite;
        $update_director->genero = $request->genero;
        $update_director->estado_civil = $request->estado_civil;
        $update_director->especialidade = $request->especialidade;
        $update_director->descricao = $request->descricao;
        $update_director->curso = $request->curso;
        $update_director->level = 3;
        
        $update->update();
        $update_director->update();
        
        Alert::success('Bom Trabalho', 'Dodos actualizados com sucesso');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DireccaoMunicipal  $direccaoMunicipal
     * @return \Illuminate\Http\Response
     */
    public function destroy(DireccaoMunicipal $direccaoMunicipal)
    {
        //
    }
}
