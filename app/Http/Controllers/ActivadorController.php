<?php

namespace App\Http\Controllers;

use App\Models\DireccaoMunicipal;
use App\Models\DireccaoProvincia;
use App\Models\User;
use App\Models\web\seguranca\ControloSistema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class ActivadorController extends Controller
{
    //
    public function candidaturaProfessor(Request $request)
    {
            
        $user = auth()->user();
        
        // if(!$user->can('create: utilizador')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        

        $headers = [ 
            "titulo" => "",
            "usuario" => User::findOrFail(Auth::user()->id),
            "dados" => ControloSistema::where('tipo', 'PROFESSOR')->where('level', '2')->get(),
        ];

        return view('sistema.direccao-provincial.candidatura-professor', $headers);
    }
    
    //
    public function candidaturaProfessorPost(Request $request)
    {
        $request->validate([
            'data_inicio' => 'required',
            'data_final' => 'required',
        ], [
            'data_inicio.required' => 'Campo Obrigatório',
            'data_final.required' => 'Campo Obrigatório',
        ]);
        
        $user = auth()->user();
        
        // if(!$user->can('create: utilizador')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        $direccao = DireccaoProvincia::findOrFail($user->shcools_id);
        
        $verificar = ControloSistema::where('shcools_id', $direccao->id)->where('tipo', "PROFESSOR")->where('status', "activo")->where('level', "2")->first();
        
        if($verificar){
            Alert::warning('Informações', 'Não podemos registrar nenhuma activação em quanto tiver outras activadas, recomendamos que desactiva outra!');
            return redirect()->back();
        }
        
        ControloSistema::create([
            'inicio' => $request->data_inicio,
            'final' => $request->data_final,
            'level' => "2",
            'status' => "activo",
            'tipo' => "PROFESSOR",
            'user_id' => Auth::user()->id,
            'shcools_id' => $direccao->id,
        ]);
                
        Alert::success('Bom Trabalho', 'Dados salvos');
        return redirect()->back();
        
    }   
    
    
    public function candidaturaEstudante(Request $request)
    {
            
        $user = auth()->user();
        
        // if(!$user->can('create: utilizador')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        $headers = [ 
            "titulo" => "",
            "usuario" => User::findOrFail(Auth::user()->id),
            "dados" => ControloSistema::where('tipo', 'ESTUDANTE')->where('level', '2')->get(),
        ];

        return view('sistema.direccao-provincial.candidatura-estudante', $headers);
    }
    
    public function candidaturaEstudantePost(Request $request)
    {
        $request->validate([
            'data_inicio' => 'required',
            'data_final' => 'required',
        ], [
            'data_inicio.required' => 'Campo Obrigatório',
            'data_final.required' => 'Campo Obrigatório',
        ]);
        
        $user = auth()->user();
        
        // if(!$user->can('create: utilizador')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        $direccao = DireccaoProvincia::findOrFail($user->shcools_id);
        
        $verificar = ControloSistema::where('shcools_id', $direccao->id)->where('tipo', "ESTUDANTE")->where('status', "activo")->where('level', "2")->first();
        
        if($verificar){
            Alert::warning('Informações', 'Não podemos registrar nenhuma activação em quanto tiver outras activadas, recomendamos que desactiva outra!');
            return redirect()->back();
        }
        
        ControloSistema::create([
            'inicio' => $request->data_inicio,
            'final' => $request->data_final,
            'level' => "2",
            'status' => "activo",
            'tipo' => "ESTUDANTE",
            'user_id' => Auth::user()->id,
            'shcools_id' => $direccao->id,
        ]);
                
        Alert::success('Bom Trabalho', 'Dados salvos');
        return redirect()->back();
        
    }  
    
    
    public function candidaturaProfessorStatus($id)
    {
    
        $user = auth()->user();
        
        // if(!$user->can('create: utilizador')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        $find = ControloSistema::findOrFail($id);
        
        if($find->status == 'activo'){
            $status = 'desactivo';
        }else if($find->status == 'desactivo'){
            $status = 'activo';
        }
        
        $find->status = $status;
        $find->update();
        
        Alert::success('Bom Trabalho', 'Dados actualizados com sucesso!');
        return redirect()->back();
    }
    
    public function candidaturaEstudanteStatus($id)
    {
    
        $user = auth()->user();
        
        // if(!$user->can('create: utilizador')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $find = ControloSistema::findOrFail($id);
        
        if($find->status == 'activo'){
            $status = 'desactivo';
        }else if($find->status == 'desactivo'){
            $status = 'activo';
        }
        
        $find->status = $status;
        $find->update();
        
        Alert::success('Bom Trabalho', 'Dados actualizados com sucesso!');
        return redirect()->back();
    }
    
    // MUNICIPAL
    public function municipalCandidaturaProfessor(Request $request)
    {
        $user = auth()->user();
        
        // if(!$user->can('create: utilizador')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $headers = [ 
            "titulo" => "",
            "usuario" => User::findOrFail(Auth::user()->id),
            "dados" => ControloSistema::where('tipo', 'PROFESSOR')->where('level', '3')->get(),
        ];

        return view('sistema.direccao-municipal.candidatura-professor', $headers);
    }
    //
    public function municipalCandidaturaProfessorPost(Request $request)
    {
        $request->validate([
            'data_inicio' => 'required',
            'data_final' => 'required',
        ], [
            'data_inicio.required' => 'Campo Obrigatório',
            'data_final.required' => 'Campo Obrigatório',
        ]);
        
        $user = auth()->user();
        
        // if(!$user->can('create: utilizador')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        $direccao = DireccaoMunicipal::findOrFail($user->shcools_id);
        
        $verificar = ControloSistema::where('shcools_id', $direccao->id)->where('tipo', "PROFESSOR")->where('status', "activo")->where('level', "3")->first();
        
        if($verificar){
            Alert::warning('Informações', 'Não podemos registrar nenhuma activação em quanto tiver outras activadas, recomendamos que desactiva outra!');
            return redirect()->back();
        }
        
        ControloSistema::create([
            'inicio' => $request->data_inicio,
            'final' => $request->data_final,
            'level' => "3",
            'status' => "activo",
            'tipo' => "PROFESSOR",
            'user_id' => Auth::user()->id,
            'shcools_id' => $direccao->id,
        ]);
                
        Alert::success('Bom Trabalho', 'Dados salvos');
        return redirect()->back();
        
    }   
    //
    public function municipalCandidaturaProfessorStatus($id)
    {
        $user = auth()->user();
        
        // if(!$user->can('create: utilizador')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        $find = ControloSistema::findOrFail($id);
        
        if($find->status == 'activo'){
            $status = 'desactivo';
        }else if($find->status == 'desactivo'){
            $status = 'activo';
        }
        
        $find->status = $status;
        $find->update();
        
        Alert::success('Bom Trabalho', 'Dados actualizados com sucesso!');
        return redirect()->back();
    }
    
    
    public function municipalCandidaturaEstudante(Request $request)
    {
        $user = auth()->user();
        
        // if(!$user->can('create: utilizador')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        $headers = [ 
            "titulo" => "",
            "usuario" => User::findOrFail(Auth::user()->id),
            "dados" => ControloSistema::where('tipo', 'ESTUDANTE')->where('level', '3')->get(),
        ];

        return view('sistema.direccao-municipal.candidatura-estudante', $headers);
    }
    
    public function municipalCandidaturaEstudantePost(Request $request)
    {
        $request->validate([
            'data_inicio' => 'required',
            'data_final' => 'required',
        ], [
            'data_inicio.required' => 'Campo Obrigatório',
            'data_final.required' => 'Campo Obrigatório',
        ]);
        
        $user = auth()->user();
        
        // if(!$user->can('create: utilizador')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        $direccao = DireccaoMunicipal::findOrFail($user->shcools_id);
        
        $verificar = ControloSistema::where('shcools_id', $direccao->id)->where('tipo', "ESTUDANTE")->where('status', "activo")->where('level', "3")->first();
        
        if($verificar){
            Alert::warning('Informações', 'Não podemos registrar nenhuma activação em quanto tiver outras activadas, recomendamos que desactiva outra!');
            return redirect()->back();
        }
        
        ControloSistema::create([
            'inicio' => $request->data_inicio,
            'final' => $request->data_final,
            'level' => "3",
            'status' => "activo",
            'tipo' => "ESTUDANTE",
            'user_id' => Auth::user()->id,
            'shcools_id' => $direccao->id,
        ]);
                
        Alert::success('Bom Trabalho', 'Dados salvos');
        return redirect()->back();
        
    }  
    
    public function municipalCandidaturaEstudanteStatus($id)
    {
    
        $user = auth()->user();
        
        // if(!$user->can('create: utilizador')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $find = ControloSistema::findOrFail($id);
        
        if($find->status == 'activo'){
            $status = 'desactivo';
        }else if($find->status == 'desactivo'){
            $status = 'activo';
        }
        
        $find->status = $status;
        $find->update();
        
        Alert::success('Bom Trabalho', 'Dados actualizados com sucesso!');
        return redirect()->back();
    }
     
       
}
