<?php

namespace App\Http\Controllers;

use App\Models\AnoLectivoGlobal;
use App\Models\DireccaoProvincia;
use App\Models\Director;
use App\Models\Distrito;
use App\Models\Ensino;
use App\Models\Municipio;
use App\Models\Notificacao;
use App\Models\Paise;
use App\Models\Provincia;
use App\Models\Shcool;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Role;

class DireccaoProvinciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // if(!$user->can('read: escola')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        

        $provincias = Provincia::get();

        $direccoes = DireccaoProvincia::when($request->provincia_id, function($query, $value){
            $query->where('provincia_id', $value);
        })
        ->with('provincia', 'pais', 'distrito', 'municipio')
        ->get();

        $usuario = User::findOrFail(Auth::user()->id);

        $headers =  [
            "usuario" => $usuario,
            "direccoes" => $direccoes,
            "provincias" => $provincias,
            "requests" => $request->all('provincia_id'),
        ];
        
        return view('sistema.direccao-provincial.index', $headers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $user = auth()->user();
        
        // if(!$user->can('read: escola')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $paises = Paise::where('id', 6)->get();
        $provincias = Provincia::get();
        $municipios = Municipio::get();
        $ensinos = Ensino::get();
        $distritos = Distrito::get();
        
        $headers = [ 
            "titulo" => "Cadastrar direções provínciais",
            "descricao" => env('APP_NAME'),
            "usuario" => Auth::user()->id,
            "paises" => $paises,
            "provincias" => $provincias,
            "municipios" => $municipios,
            "ensinos" => $ensinos,
            "distritos" => $distritos,
        ];
        
        return view('sistema.direccao-provincial.create', $headers);
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
        

        if (!empty($request->file('logotipo'))) {
            $image = $request->file('logotipo');
            $imageName = time() .'.'. $image->extension();
            $image->move(public_path('assets/images'), $imageName);
        }else{
            $imageName = Null;
        }

        if (!empty($request->file('logotipo_assinatura_director'))) {
            $image3 = $request->file('logotipo_assinatura_director');
            $imageName3 = time() .'.'. $image3->extension();
            $image3->move(public_path('assets/images'), $imageName3);
        }else{
            $imageName3 = Null;
        }

        $provincia = Provincia::findOrFail($request->provincia_id);

        $create = DireccaoProvincia::create([
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
            'level' => 2,
            'instituicao_id' => $create->id,
        ]);
        
        $email = strtolower($provincia->nome) . "provincial@gmail.com";
        
        $user = User::create([
            'nome' => $request->nome,
            'telefone' => $request->telefone1,
            'usuario' => strtolower($provincia->nome). "@conta",
            'password' => Hash::make(strtolower($provincia->nome). "@conta"),
            'acesso' => "admin",
            'level' => '200',
            'login' => 'N',
            'numero_avaliacoes' => '0',
            'status' => "Bloqueado",
            'email' => $email,
            'funcionarios_id' => $createDirector->id,
            'shcools_id' =>  $create->id,
        ]);

        $role = Role::where('name', 'admin')->first();
        $user->assignRole($role);
        
        $text = Auth::user()->nome.", cadastrou uma direcção provincia {$request->nome}.";
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
            'model_type' => "direccao provincial",
        ]);

        Alert::success('Bom Trabalho', 'Dodos registrado com sucesso');
        return redirect()->back();

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DireccaoProvincia  $direccaoProvincia
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = auth()->user();
        
        // if(!$user->can('read: escola')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        $edit = DireccaoProvincia::with('provincia', 'pais')->findOrFail($id);
        
        $headers = [ 
            "titulo" => "Editar direções provínciais",
            "descricao" => env('APP_NAME'),
            "usuario" => Auth::user()->id,
            "data" => $edit,
        ];
        
        return view('sistema.direccao-provincial.show', $headers);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DireccaoProvincia  $direccaoProvincia
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $edit = DireccaoProvincia::findOrFail($id);
        
        $paises = Paise::where('id', 6)->get();
        $provincias = Provincia::get();
        $municipios = Municipio::get();
        $distritos = Distrito::get();
        
        $headers = [ 
            "titulo" => "Editar direções provínciais",
            "descricao" => env('APP_NAME'),
            "usuario" => Auth::user()->id,
            "paises" => $paises,
            "provincias" => $provincias,
            "municipios" => $municipios,
            "distritos" => $distritos,
            "data" => $edit,
        ];
        
        return view('sistema.direccao-provincial.edit', $headers);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DireccaoProvincia  $direccaoProvincia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // $user = auth()->user();
        
        // if(!$user->can('read: escola')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        

        if (!empty($request->file('logotipo'))) {
            $image = $request->file('logotipo');
            $imageName = time() .'.'. $image->extension();
            $image->move(public_path('assets/images'), $imageName);
        }else{
            $imageName = $request->logotipo_guardado;
        }

        if (!empty($request->file('logotipo_assinatura_director'))) {
            $image3 = $request->file('logotipo_assinatura_director');
            $imageName3 = time() .'.'. $image3->extension();
            $image3->move(public_path('assets/images'), $imageName3);
        }else{
            $imageName3 = $request->logotipo_assinatura_director_guardado;
        }


        $update = DireccaoProvincia::findOrFail($id);
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
        $update->logotipo = $imageName ;
        $update->logotipo_assinatura_director = $imageName3;
        
        $update->update();
        
        Alert::success('Bom Trabalho', 'Dodos actualizados com sucesso');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DireccaoProvincia  $direccaoProvincia
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function imprimir(Request $request)
    {
       if(isset($request->provincia_id)){
        $request->provincia_id = $request->provincia_id;
       }else{
        $request->provincia_id = "";
       }

        $provincia = Provincia::find($request->provincia_id);

        $direccoes = DireccaoProvincia::when($request->provincia_id, function($query, $value){
            $query->where('provincia_id', $value);
        })
        ->with('provincia', 'pais')
        ->get();

        if($provincia){
            $title = "DIRECÇÃO PROVÍNCIAL DE EDUCAÇÃO DE {$provincia->nome}";
        }else{
            $title = "LISTA DAS DIRECÇÕES PROVINCIAS";
        }

        $headers = [ 
            "titulo" => $title,
            "direccoes" => $direccoes,
            "provincia" => $provincia,
        ];

        $pdf = \PDF::loadView('downloads.relatorios.todas-direccoes-provincias', $headers);
        return $pdf->stream('todas-direccoes-provincias.pdf');
 
    }
}
