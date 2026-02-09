<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        
        if(!$user->can('read: permission')){
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

        return view('sistema.permissions.index', $headers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();
        
        if(!$user->can('create: permission')){
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

        return view('sistema.permissions.create', $headers);
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
        
        if(!$user->can('create: permission')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $request->validate(
            ['permission' => 'required'],
            ['permission.required' => "Senha Obrigatória"]
        ); 

        $permission = Permission::create([
            "name" => $request->permission,
        ]);

        return redirect()->route('permissions.index')->with('message', 'Permissão cadastrado com sucesso!');

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
        
        if(!$user->can('update: permission')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $permissions = Permission::findById($id);
        
        $headers =  [
            "titulo" => "Permissões",
            "descricao" => env('APP_NAME'),
            "permission" => $permissions,
        ];

        return view('sistema.permissions.edit', $headers);
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
        
        if(!$user->can('update: permission')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $request->validate(
            ['permission' => 'required'],
            ['permission.required' => "Senha Obrigatória"]
        ); 

        $permissions = Permission::findById($id);
        $permissions->name = $request->permission;
        $permissions->update();

        return redirect()->route('permissions.index')->with('message', 'Permissão actualizada com sucesso!');

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
        
        if(!$user->can('delete: permission')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $permission = Permission::destroy($id);
        return redirect()->route('permissions.index')->with('message', 'Permissão Excluida com sucesso!');

    }
}
