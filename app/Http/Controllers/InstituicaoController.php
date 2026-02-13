<?php

namespace App\Http\Controllers;

use App\Models\Shcool;
use App\Models\User;
use App\Models\web\anolectivo\Trimestre;
use App\Models\web\estudantes\CartaoEstudante;
use App\Models\web\turmas\Bolsa;
use App\Models\web\turmas\BolsaInstituicao;
use App\Models\web\turmas\Bolseiro;
use App\Models\web\turmas\InstituicaoEducacional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class InstituicaoController extends Controller
{

    use TraitHelpers;
    use TraitHeader;
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function home()
    {
        $user = auth()->user();
        
        if(!$user->can('read: instituicao')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());
        
        $bolsas = Bolsa::where('type', 'B')->where('shcools_id', $escola->id)->get(); 
        $instituicoes = InstituicaoEducacional::where('type', 'B')->where('shcools_id', $escola->id)->get(); 

        $headers = [
            "escola" => $escola,
            "titulo" => "Listar Instituições",
            "descricao" => env('APP_NAME'),
            "bolsas" => $bolsas,
            "instituicoes" => $instituicoes,
        ];

        return view('admin.creditos-educacionais.index', $headers);
    }
    
    public function index(Request $request)
    {
        $user = auth()->user();
    
        if (!$user->can('read: instituicao')) {
            Alert::error(
                'Acesso restrito',
                'Você não possui permissão para esta operação, por favor, contacte o administrador!'
            );
            return redirect()->back();
        }
    
        $paginacao = $request->paginacao ?? 5;
    
        $query = InstituicaoEducacional::when($request->designacao_geral, function ($query, $value) {
            $query->where(function ($q) use ($value) {
                foreach (['nome', 'status', 'nif', 'tipo', 'director', 'type'] as $field) {
                    $q->orWhere($field, 'like', "%{$value}%");
                }
            });
        })
        ->where('type', 'B')
        ->when($request->data_status, function($query, $value) {
            $query->where('status', $value);
        })->where('shcools_id', $this->escolarLogada());
    
        return response()->json(
            $query->orderByDesc('id')->paginate($paginacao)
        );
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('create: instituicao')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $request->validate([
            "designacao" => 'required',
            "status" => 'required',
            "tipo" => 'required',
        ]);
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            InstituicaoEducacional::create([
                'nif' => $request->nif,
                'status' => $request->status,
                'tipo' => $request->tipo,
                'type' => "B",
                'nome' => $request->designacao,
                'email' => $request->email,
                'endereco' => $request->endereco,
                'director' => $request->director,
                'shcools_id' => $this->escolarLogada(),
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

        return response()->json([
            'status' => 200,
            'message' => 'Dados salvos com sucesso!',
        ]);
    }

    public function show($id)
    {
        return InstituicaoEducacional::with(['bolsas.bolsa'])->findOrFail($id); 
    }

    public function edit($id)
    {
        $user = auth()->user();
        
        if(!$user->can('update: instituicao')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
    
        return InstituicaoEducacional::findOrFail($id); 
    }

    public function update(Request $request, $id)
    {
   
        $user = auth()->user();
        
        if(!$user->can('update: instituicao')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $request->validate([
            "designacao" => 'required',
            "status" => 'required',
            "tipo" => 'required',
        ]);
        
        $update = InstituicaoEducacional::findOrFail($id);
                            
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $update->nif = $request->nif;
            $update->status = $request->status;
            $update->tipo = $request->tipo;
            $update->nome = $request->designacao;
            $update->email = $request->email;
            $update->endereco = $request->endereco;
            $update->director = $request->director;
    
            $update->update();            
        
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
        ]);

    }

    public function destroy($id)
    {
        $user = auth()->user();
        
        if(!$user->can('delete: instituicao')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
                                    
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            InstituicaoEducacional::findOrFail($id)->delete();
        
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
        ]);
    }

    public function instituicao_bolsa(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('read: bolsa') || !$user->can('read: instituicao')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
    
        
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada()); 
                
        $bolsas_listagem = Bolsa::where('type', 'B')->where('shcools_id', $escola->id)->get(); 
        $instituicoes = InstituicaoEducacional::where('type', 'B')->where('shcools_id', $escola->id)->get(); 

        $bolsas = BolsaInstituicao::with(['instituicao','bolsa'])
        ->when($request->instituicao_id, function($query, $value){
            $query->where('instituicao_id', $value);
        })
        ->when($request->bolsa_id, function($query, $value){
            $query->where('bolsa_id', $value);
        })
        ->where('shcools_id', $escola->id)->get();
        
        
        $headers = [
            "instituicoes" => $instituicoes,
            "bolsas_listagem" => $bolsas_listagem,
            "escola" => $escola,
            
            'bolsas' =>  $bolsas,
            "titulo" => "Instituições & Bolsas",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.creditos-educacionais.instituicoes-bolsas', $headers);
    }
        
    public function associar_bolsa($id = null)
    {
        $user = auth()->user();
        
        if(!$user->can('read: bolsa') || !$user->can('read: instituicao')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        
        
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada()); 
        
        if($id){
            $id = Crypt::decrypt($id);
        }else{
            $id = "";
        }
        
        $institicao = InstituicaoEducacional::find($id); 
        
        $bolsas = Bolsa::where('type', 'B')->where('shcools_id', $escola->id)->get(); 
        
        $instituicoes = InstituicaoEducacional::when($institicao->id ?? "", function($query, $value){
            $query->where('id', $value);
        })
        ->where('shcools_id', $escola->id)
        ->get(); 
        
        $headers = [
            "escola" => $escola,
            "instituicoes" => $instituicoes,
            "institicao" => $institicao,
            
            "bolsas" => $bolsas,
            "titulo" => "Associar Bolsas a instituição",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.creditos-educacionais.associar-bolsas', $headers);
    }

    public function store_bolsa_associada(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('create: bolsa') || !$user->can('create: instituicao')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $request->validate([
            "bolsa_id" => 'required',
            "instituicao_id" => 'required',
            "desconto" => 'required',
        ]);
                                    
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $verificar = BolsaInstituicao::where('instituicao_id', $request->instituicao_id)->where('bolsa_id', $request->bolsa_id)->first();
        
            if(!$verificar){
                BolsaInstituicao::create([
                    'bolsa_id' => $request->bolsa_id,
                    'instituicao_id' => $request->instituicao_id,
                    'desconto' => $request->desconto,
                    'shcools_id' => $this->escolarLogada(),
                ]);
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

        return response()->json([
            'status' => 200,
            'message' => 'Dados salvos com sucesso!',
        ]);
     
    }
    
    public function associar_bolsa_editar($id)
    {
    
        $user = auth()->user();
        
        if(!$user->can('update: bolsa') || !$user->can('update: instituicao')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        
        
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada()); 
                
        $bolsas = Bolsa::where('type', 'B')->where('shcools_id', $escola->id)->get(); 
        
        $instituicoes = InstituicaoEducacional::where('type', 'B')->where('shcools_id', $escola->id)->get(); 
        
        $instituicao_bolsa = BolsaInstituicao::findOrFail(Crypt::decrypt($id));
        
        $headers = [
            "escola" => $escola,
            "instituicoes" => $instituicoes,
            
            "bolsas" => $bolsas,
            'instituicao_bolsa' =>  $instituicao_bolsa,
            "titulo" => "Editar Associação de Bolsas a instituição",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.creditos-educacionais.editar-associar-bolsas', $headers);
     
    }   
    
    public function associar_bolsa_update(Request $request, $id)
    {
        
        $user = auth()->user();
        
        if(!$user->can('update: bolsa') || !$user->can('update: instituicao')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $request->validate([
            "bolsa_id" => 'required',
            "instituicao_id" => 'required',
            "desconto" => 'required',
        ], [
            "bolsa_id.required" => "Campo Obrigatório",
            "instituicao_id.required" => "Campo Obrigatório",
            "desconto.required" => "Campo Obrigatório",
        ]);
        
        $instituicao_bolsa = BolsaInstituicao::findOrFail($id);
        $instituicao_bolsa->bolsa_id = $request->bolsa_id;
        $instituicao_bolsa->instituicao_id = $request->instituicao_id;
        $instituicao_bolsa->desconto = $request->desconto;
        $instituicao_bolsa->update();
        

        Alert::success("Bom Trabalho", "Dados actualizados com successo");
        return redirect()->back();
         
    }   

    public function instituicao_remover_bolsa_bolseiro($id)
    {
        $user = auth()->user();
        
        // if(!$user->can('read: ensino')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada()); 
        $bolseiro = Bolseiro::findOrFail(Crypt::decrypt($id)); 
        
        $trimestre = Trimestre::findOrFail($bolseiro->periodo_id);
        
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());
        
        if($escola->ensino->nome == "Ensino Superior"){
            
            if($bolseiro->afectacao == "mensalidade"){
                if($trimestre->trimestre == "Iª Simestre"){
                    $cartao = CartaoEstudante::where('semestral', "1º Semestre")->where('status_2', 'Bolsa')->where('estudantes_id', $bolseiro->estudante_id)->get();
                }
                if($trimestre->trimestre == "IIª Simestre"){
                    $cartao = CartaoEstudante::where('semestral', "2º Semestre")->where('status_2', 'Bolsa')->where('estudantes_id', $bolseiro->estudante_id)->get();
                }
                if($trimestre->trimestre == "Anual"){
                    $cartao = CartaoEstudante::whereIn('semestral', ["1º Semestre", "2º Semestre"])->where('status_2', 'Bolsa')->where('estudantes_id', $bolseiro->estudante_id)->get();
                }
                
                if($cartao){
                    foreach($cartao as $cart){
                        $update = CartaoEstudante::findOrFail($cart->id);
                        $update->status = "Nao Pago";
                        $update->status_2 = "Normal";
                        $update->update();
                    }
                }
            }
            
            if($bolseiro->afectacao == "global"){
                if($trimestre->trimestre == "Iª Simestre"){
                    $cartao = CartaoEstudante::whereIn('semestral', ["1º Semestre", "Normal"])->where('status_2', 'Bolsa')->where('estudantes_id', $bolseiro->estudante_id)->get();
                }
                if($trimestre->trimestre == "IIª Simestre"){
                    $cartao = CartaoEstudante::whereIn('semestral', ["2º Semestre", "Normal"])->where('status_2', 'Bolsa')->where('estudantes_id', $bolseiro->estudante_id)->get();
                }
                if($trimestre->trimestre == "Anual"){
                    $cartao = CartaoEstudante::whereIn('semestral', ["1º Semestre", "2º Semestre", "Normal"])->where('status_2', 'Bolsa')->where('estudantes_id', $bolseiro->estudante_id)->get();
                }
                
                if($cartao){
                    foreach($cartao as $cart){
                        $update = CartaoEstudante::findOrFail($cart->id);
                        $update->status = "Nao Pago";
                        $update->status_2 = "Normal";
                        $update->update();
                    }
                }
            }
        }else{
            if($bolseiro->afectacao == "mensalidade"){
                if($trimestre->trimestre == "Iª Trimestre"){
                    $cartao = CartaoEstudante::where('trimestral', "1º Trimestre")->where('status_2', 'Bolsa')->where('estudantes_id', $bolseiro->estudante_id)->get();
                }
                if($trimestre->trimestre == "IIª Trimestre"){
                    $cartao = CartaoEstudante::where('trimestral', "2º Trimestre")->where('status_2', 'Bolsa')->where('estudantes_id', $bolseiro->estudante_id)->get();
                }
                if($trimestre->trimestre == "IIIª Trimestre"){
                    $cartao = CartaoEstudante::where('trimestral', "3º Trimestre")->where('status_2', 'Bolsa')->where('estudantes_id', $bolseiro->estudante_id)->get();
                }
                
                if($trimestre->trimestre == "Geral"){
                    $cartao = CartaoEstudante::whereIn('trimestral', ["1º Trimestre", "2º Trimestre", "3º Trimestre"])->where('status_2', 'Bolsa')->where('estudantes_id', $bolseiro->estudante_id)->get();
                }
                
                if($cartao){
                    foreach($cartao as $cart){
                        $update = CartaoEstudante::findOrFail($cart->id);
                        $update->status = "Nao Pago";
                        $update->status_2 = "Normal";
                        $update->update();
                    }
                }
            }
                      
            if($bolseiro->afectacao == "global"){
                if($trimestre->trimestre == "Iª Trimestre"){
                    $cartao = CartaoEstudante::whereIn('trimestral', ["1º Trimestre", "Normal"])->where('status_2', 'Bolsa')->where('estudantes_id', $bolseiro->estudante_id)->get();
                }
                if($trimestre->trimestre == "IIª Trimestre"){
                    $cartao = CartaoEstudante::whereIn('trimestral', ["2º Trimestre", "Normal"])->where('status_2', 'Bolsa')->where('estudantes_id', $bolseiro->estudante_id)->get();
                }
                if($trimestre->trimestre == "IIIª Trimestre"){
                    $cartao = CartaoEstudante::whereIn('trimestral', ["3º Trimestre", "Normal"])->where('status_2', 'Bolsa')->where('estudantes_id', $bolseiro->estudante_id)->get();
                }
                
                if($trimestre->trimestre == "Geral"){
                    $cartao = CartaoEstudante::whereIn('trimestral', ["1º Trimestre", "2º Trimestre", "3º Trimestre", "Normal"])->where('status_2', 'Bolsa')->where('estudantes_id', $bolseiro->estudante_id)->get();
                }
                
                if($cartao){
                    foreach($cartao as $cart){
                        $update = CartaoEstudante::findOrFail($cart->id);
                        $update->status = "Nao Pago";
                        $update->status_2 = "Normal";
                        $update->update();
                    }
                }
           }
        }
        
        $bolseiro->delete();

        Alert::success("Bom Trabalho", "Dados Removida com sucesso");
        return redirect()->route('creditos-educacionais.instituicao-listar-bolseiros');

    }

    public function delete_bolsa_associada($id)
    {
        $user = auth()->user();
        
        if(!$user->can('delete: instituicao')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
                                            
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            BolsaInstituicao::findOrFail($id)->delete();
        
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
        ]);
    }

}
