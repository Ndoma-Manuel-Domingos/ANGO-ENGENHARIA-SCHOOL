<?php

namespace App\Http\Controllers;

use App\Models\Shcool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleEscolaController extends Controller
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
        
        if(!$user->can('read: role')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $roles = Role::get();
        
        
        
        $headers =  [
            "titulo" => "Perfil",
            "descricao" => env('APP_NAME'),
            "roles" => $roles,
            
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
        ];

        return view('admin.roles.index', $headers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $user = auth()->user();
        
        if(!$user->can('create: role')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $roles = Role::get();
        $permissions = Permission::get();
        
        
        
        
        $headers =  [
            "titulo" => "Permissões",
            "descricao" => env('APP_NAME'),
            "permissions" => $permissions,
            "roles" => $roles,
            
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
        ];

        return view('admin.roles.create', $headers);
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
        
        if(!$user->can('create: role')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $request->validate(
            ['role' => 'required'],
            ['role.required' => "Obrigatória"]
        ); 

        $role = Role::create([
            "name" => $request->role,
        ]);

        return redirect()->route('roles-escola.index')->with('message', 'Perfil cadastrado com sucesso!');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = auth()->user();
        
        if(!$user->can('update: role')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $role = Role::with(['permissions'])->findOrFail(Crypt::decrypt($id));
        
        $role_permissions = $role->permissions->pluck('id')->toArray();
        
        
        
        $permissions = Permission::orderBy('name', 'asc')->get();

        $headers =  [
            "titulo" => "Permissões",
            "descricao" => env('APP_NAME'),
            "role" => $role,
            
            "role_permissions" => $role_permissions,
            "permissions" => $permissions,
            
            
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
        ];

        return view('admin.roles.edit', $headers);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    
        $user = auth()->user();
        
        if(!$user->can('update: role')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        
        $request->validate([
            'role' => 'required|string',
        ],[
            'role.required' => 'Campo Obrigatório',
        ]);
        
        $role = Role::findOrFail($id);
        $role->name = $request->role;
        
        $role->permissions()->sync($request->input('permission_id', []));
        
        $role->update();
        
        return redirect()->route('roles-escola.index')->with('message', 'Perfil actualizado com sucesso!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = auth()->user();
        
        if(!$user->can('delete: role')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $role = Role::destroy(Crypt::decrypt($id));
        return redirect()->back();

    }
}
