<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
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
        ];

        return view('sistema.roles.index', $headers);
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
        ];

        return view('sistema.roles.create', $headers);
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

        return redirect()->route('roles.index')->with('message', 'Perfil cadastrado com sucesso!');

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
        
        $role = Role::findById($id);
        $permissions = Role::with('permissions')->where('id', $id)->first();
        
        $permissions_list = Permission::get();

        $headers =  [
            "titulo" => "Permissões",
            "descricao" => env('APP_NAME'),
            "role" => $role,
            "permissions" => $permissions,
            "permissions_list" => $permissions_list,
        ];

        return view('sistema.roles.edit', $headers);
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
        
        $request->validate(
            ['role' => 'required'],
            ['permission_id' => 'required'],
            ['role.required' => "Obrigatória"],
            ['permission.required' => "Obrigatória"]
        ); 

        $permissions = Role::with('permissions')->where('id', $id)->first();
        $role = Role::findById($id);

        foreach ($permissions->permissions as $permission) {
            $permission->removeRole($role);
        }

        if($request->permission_id){
            foreach ($request->permission_id as $permission) {
                $role->givePermissionTo($permission);
            }
        }

        $role->name = $request->role;
        $role->update();

        return redirect()->route('roles.index')->with('message', 'Perfil actualizado com sucesso!');

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
        
        $permission = Permission::destroy($id);
        return redirect()->route('permissions.index')->with('message', 'Permissão Excluida com sucesso!');

    }
}
