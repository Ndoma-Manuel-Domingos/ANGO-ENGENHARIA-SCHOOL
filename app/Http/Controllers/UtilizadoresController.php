<?php

namespace App\Http\Controllers;

use App\Models\Notificacao;
use App\Models\Shcool;
use App\Models\User;
use App\Models\web\estudantes\Estudante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Role;

class UtilizadoresController extends Controller
{

    use TraitHelpers;
    use TraitHeader;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('read: utilizador')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $usuarios = User::with('roles')
            ->where('shcools_id', $this->escolarLogada())
            ->where('level2', 4)
        ->get();
        
        
        
        $headers =  [
            "titulo" => "Utilizadores",
            "descricao" => env('APP_NAME'),
            
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "usuarios" => $usuarios,
        ];

        return view('admin.utilizadores.index', $headers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();
        
        if(!$user->can('create: utilizador')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        

        $roles = Role::get();

        $headers =  [
            "titulo" => "Cadastrar Utilizadores",
            "descricao" => env('APP_NAME'),
            "roles" => $roles,
            
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
        ];

        return view('admin.utilizadores.criar', $headers);
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
        
        if(!$user->can('create: utilizador')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $request->validate(
            [
                'password_2' => 'required',
                'password_3' => 'required',
                'user' => 'required',
                'role_id' => 'required',
            ],
            [
                'password_2.required' => "Senha Obrigatória",
                'password_3.required' => "Senha Obrigatória",
                'user.required' => "Senha Obrigatória",
                'role_id.required' => "Senha Obrigatória",
            ]
        ); 
        
        if ($request->password_2 != $request->password_3) {
            Alert::warning('Atenção', 'As duas novas senhas não podem ser diferentes');
            return redirect()->back('utilizadores-escola.create')->with('danger', 'As duas novas senhas não podem ser diferentes');
        } 

        $roles = Role::findById($request->role_id);
        
        if($roles->name == 'estudante'){
            $level = 100;
           // $acesso = 'estudante';
        }else{
            $level = 2;
           // $acesso = 'admin';
        }
   
        $user = User::create([
            "password" => Hash::make($request->password_2),
            "usuario" => $request->user,
            "numero_avaliacoes" => 3, 
            "level" => $level,
            "acesso" => $roles->name,
            "login" => 'N',
            "status" => $request->status,
            "nome" => $request->nome,
            "email" => $request->email,
            "telefone" => $request->telefone,
            "shcools_id" => $this->escolarLogada(),
        ]);

        $user->assignRole($roles);
       
        Alert::success('Bom Trabalho', 'Credências actualizados com sucesso, Usar da Proxíma vez que Entrar no sistema');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Utilizadores  $utilizadores
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Utilizadores  $utilizadores
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = auth()->user();
        
        if(!$user->can('update: utilizador')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $usuario = User::with('roles')->findOrFail(Crypt::decrypt($id));
    
        
       
        $roles = Role::get();
        $role = $usuario->roles[0];

        $headers =  [
            "titulo" => "Editar Utilizadores",
            "descricao" => env('APP_NAME'),
            "roles" => $roles,
            "role" => $role,
            "usuario" => $usuario,
            
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
        ];

        return view('admin.utilizadores.editar', $headers);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Utilizadores  $utilizadores
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
       
        Alert::success('Bom Trabalho', 'Utilizador actualizado com sucesso');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Utilizadores  $utilizadores
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //       
        $user = auth()->user();
        
        if(!$user->can('delete: utilizador')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
    }
    
    public function confirmarRedefinicaoSenha(Request $request)
    {
        $request->validate([
            'senha' => 'required',
            'estudante_id' => 'required|exists:users,id',
        ]);
    
        $usuario = Auth::user();
        
        if (!Hash::check($request->senha, $usuario->password)) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Senha incorreta.'
            ]);
        }
    
        $user = User::findOrFail($request->estudante_id);
     
        $estudante = Estudante::findOrFail($user->funcionarios_id);
                  
        // Redefinir a senha do estudante (exemplo: senha padrão "12345678")
        $user->password = Hash::make('12345678');
        $user->save();
        
        $text = "O estudante {$usuario->nome}, fez uma actualização na senha do estudante {{ $estudante->nome_completo }}.";
        $text2 = "O Sr(a) acabou de alterar a senha do estudante {{ $estudante->nome_completo }}";
            
        Notificacao::create([
            'user_id' => $usuario->id,
            'destino' => NULL,
            'type_destino' => 'escola',
            'type_enviado' => 'estudante',
            'notificacao' => $text,
            'notificacao_user' => $text2,
            'status' => '0',
            'model_id' => $user->funcionario_id,
            'model_type' => "alteracao",
            'shcools_id' => $this->escolarLogada()
        ]);
    
        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Senha redefinida com sucesso.'
        ]);
    }
    
}
