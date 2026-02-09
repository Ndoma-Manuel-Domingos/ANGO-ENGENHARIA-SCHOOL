<?php

namespace App\Http\Controllers;

use App\Models\Notificacao;
use App\Models\Professor;
use App\Models\Shcool;
use App\Models\User;
use App\Models\SolicitacaoProfessor;
use App\Models\web\funcionarios\CartaoFuncionario;
use App\Models\web\funcionarios\FuncionariosControto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class NotificacaoController extends Controller
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
        //
        $escola = Shcool::with(['ensino'])->findOrFail($this->escolarLogada());
        
        if($request->notification && $request->notification == "all"){
       
            $nots = Notificacao::where('shcools_id', $escola->id)
                ->where('status', 0)
                ->get();
                
            if(count($nots) > 0){
                foreach ($nots as $item) {
                    $up = Notificacao::findOrFail($item->id);
                    $up->status = 1;
                    $up->update();
                }
            }
                
        }
        
        if($request->notification && $request->notification != 'all'){
            $update = Notificacao::findOrFail($request->notification);
            $update->status = 1;
            $update->update();
        }

        $notificacoes = Notificacao::when($request->notification, function($query, $value){
            if($value != 'all') {
                $query->where('id', $value);
            }
        })
        ->with(['user', 'escola'])
            ->where('status', 1)
            ->where('shcools_id', $escola->id)
            ->orderBy('created_at', 'DESC')
        ->get();
        
        $headers = [
            "titulo" => "Listagem Notificações",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "notificacoes" => $notificacoes,
            "escola" => $escola,
        ];

        return view('admin.notificacoes.index', $headers);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notificacao  $notificacao
     * @return \Illuminate\Http\Response
     */
    public function show(Notificacao $notificacao)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Notificacao  $notificacao
     * @return \Illuminate\Http\Response
     */
    public function edit(Notificacao $notificacao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notificacao  $notificacao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notificacao $notificacao)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notificacao  $notificacao
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notificacao $notificacao)
    {
        //
    }
    
    public function solicitacoes(Request $request)
    {
        
        $solicitacoes = SolicitacaoProfessor::where('status', 1)
            ->with(['professor', 'disciplina', 'classe', 'instituicao1', 'curso'])
            ->where('level_destino', '4')
            ->where('instituicao_id', $this->escolarLogada())
        ->get();
        
        $headers = [
            "titulo" => "Listar Notificações",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "solicitacoes" => $solicitacoes,
        ];

        return view('admin.notificacoes.solicitacoes', $headers);

    }
    
    public function solicitacoesResposta(Request $request, $id)
    {
        $request->validate([
            'resposta' => 'required', 
            'resposta_opcao' => 'required'
        ]);     
        
                    
        try {
            DB::beginTransaction();
            
            $update = SolicitacaoProfessor::findOrFail($id);
            $update->resposta_descricao = $request->resposta;
            $update->resposta_opcao = $request->resposta_opcao;
            $update->resposta_user_id = Auth::user()->id;
            $update->status = 1;
            $update->processo = 'CONCLUIDO';
            $update->resposta_instituicao_id = $this->escolarLogada();
            $update->level_respondido = '4';
            $update->update();
          
            DB::commit();

        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
                    

        Alert::success('Bom Trabalho', 'Solicitação respondida com sucesso');
        return redirect()->back();
        
    }
    
    
    public function transferenciaProfessoresDireccao(Request $request)
    {
               
        // transferencia pemitidas ou validades lo pelo govervo e falta o aval da escola
        $transferincias_professores = SolicitacaoProfessor::where('status', '1')
            ->where('escola_destino_level', '4')
            ->where('resposta_opcao', 'Sim')
            ->where('resposta_escola', 'Nao')
            ->where('solicitacao', 'transferencia')
            ->where('escola_transferencia_id', $this->escolarLogada())
            ->with('professor', 'disciplina', 'classe', 'instituicao1', 'curso')
        ->get();
        
        $headers = [
            "titulo" => "Listar Notificações",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "solicitacoes" => $transferincias_professores,
        ];

        return view('admin.notificacoes.transferencia-professores-direccoes', $headers);

    }
     
    public function transferenciaProfessoresDireccaoAprovacaoEscola($id)
    {
                            
        try {
            DB::beginTransaction(); 
        
            // transferencia pemitidas ou validades lo pelo govervo e falta o aval da escola
            $transferincias = SolicitacaoProfessor::findOrFail($id);
            $professor = Professor::findOrFail($transferincias->professor_id);
            
            $contrato = FuncionariosControto::where('funcionarios_id', $professor->id)->where('level', '4')->first();
            
            $escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->findOrFail($this->escolarLogada());
    
            $update = FuncionariosControto::findOrFail($contrato->id);
            
            $update->nif = $professor->bilheite;
            $update->pais_id = $escola->pais_id;
            $update->provincia_id = $escola->provincia_id;
            $update->municipio_id = $escola->municipio_id;
            $update->distrito_id = $escola->distrito_id;
            $update->shcools_id = $escola->id;
            $update->update();
            
            $transferincias->escola_transferencia_id = $this->escolarLogada();
            $transferincias->resposta_escola = "Sim";
            $transferincias->update();
            
            $cartao = CartaoFuncionario::where('funcionarios_id', $professor->id)->where('level', '2')->where('shcools_id', $transferincias->escola_transferencia_id)->get();
            if($cartao){
                foreach($cartao as $cart){
                    $updateCartao = CartaoFuncionario::findOrFail($cart->id);
                    $updateCartao->shcools_id = $escola->id;
                    $updateCartao->update();
                }
            }
                        
          
            DB::commit();

        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
                    

        Alert::success('Bom Trabalho', 'Solicitação respondida com sucesso');
        return redirect()->back();

    }
    
}
