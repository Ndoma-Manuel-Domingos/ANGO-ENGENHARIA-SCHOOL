<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Shcool;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SegurancaController extends Controller
{
    //
    use TraitHelpers;

    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    
    // =======================================
    // SEGURANCA
    // =======================================

    public function perfil()
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
        $headers = [ 
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Perfil do Utilizador",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.seguranca.perfil', $headers);
        
    }
    
    public function editarPerfil(Request $request)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
        $request->validate([
            "usuario" => 'required',
        ]);
        
        
        try {
            DB::beginTransaction();
        
            $user = User::findOrFail(Auth::user()->id);
            $user->usuario = $request->input('usuario');
            $user->nome = $request->nome;
            $user->telefone = $request->telefone;
            $user->email = $request->email;
            $user->update();
            
           // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        Alert::success('Bom Trabalho', 'Dados actuazados com sucesso');
        return redirect()->back();
    }
    
    public function DefinirCorCartao(Request $request)
    {
        
        try {
            DB::beginTransaction();
    
            $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());
            $escola->cor_cartao = $request->cor_fundo;
            $escola->cor_letra_cartao = $request->cor_letra;
            $escola->update();
            
           // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        
        return response()->json([
            'status' => 200,
            'message' => 'Dados salvos com sucesso!',
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }
    
    public function DefinirTipoImpressao(Request $request)
    {
        
        
        try {
            DB::beginTransaction();
    
            $user = User::findOrFail(Auth::user()->id);
            $user->impressora = $request->impressora;
            $user->update();
            
           // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json([
            'status' => 200,
            'message' => 'Dados salvos com sucesso!',
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }
    
    public function DefinirProcessoAdmissaoEstudante(Request $request)
    {
    
        try {
            DB::beginTransaction();
            
            if($request->estado == "Prova"){
                $novo_estado = "Prova";
            }
            
            if($request->estado == "Normal"){
                $novo_estado = "Normal";
            }
            
            $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());
            $escola->processo_admissao_estudante = $novo_estado;
            $escola->update();
            
           // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }


        Alert::success('Bom Trabalho', 'Dados actuazados com sucesso');
        return redirect()->back();
    }
    
    public function DefinirProcessoPagamento(Request $request)
    {
    
    
        try {
            DB::beginTransaction();
    
            if($request->estado == "Secretaria"){
                $novo_estado = "Secretaria";
            }
            
            if($request->estado == "Financeira"){
                $novo_estado = "Financeira";
            }
            
            $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());
            $escola->processo_pagamento_servico = $novo_estado;
            $escola->update();
           // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        
        Alert::success('Bom Trabalho', 'Dados actuazados com sucesso');
        return redirect()->back();
    }


}
