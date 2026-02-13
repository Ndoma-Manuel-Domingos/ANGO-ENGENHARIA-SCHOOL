<?php

namespace App\Http\Controllers;

use App\Models\Shcool;
use App\Models\User;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\anolectivo\ControlePeriodico;
use App\Models\web\anolectivo\Trimestre;
use App\Models\web\estudantes\CartaoEstudante;
use App\Models\web\estudantes\Estudante;
use App\Models\web\turmas\Bolsa;
use App\Models\web\turmas\BolsaInstituicao;
use App\Models\web\turmas\Bolseiro;
use App\Models\web\turmas\EstudantesTurma;
use App\Models\web\turmas\InstituicaoEducacional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class BolseiroController extends Controller
{

    use TraitHelpers;
    use TraitHeader;
    
    public function __construct()
    {
        $this->middleware('auth');
    }
 
    // lista bolseiros
    public function home(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('read: bolseiro')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada()); 
        $bolsas = Bolsa::where('type', 'B')->where('shcools_id', $escola->id)->get(); 
        $instituicoes = InstituicaoEducacional::where('type', 'B')->where('shcools_id', $escola->id)->get(); 
        $estudantes = Estudante::where('registro', 'confirmado')->where('shcools_id', $this->escolarLogada())->get();
        $anos_lectivos = AnoLectivo::where('shcools_id', $escola->id)->get();

        if ($escola->ensino->nome == "Ensino Superior") {
            $trimestres = ControlePeriodico::where('ensino_status', '2')->get();
        } else {
            $trimestres = ControlePeriodico::where('ensino_status', '1')->get();
        }

        $headers = [
            "titulo" => "Listar Bolseiros",
            "descricao" => env('APP_NAME'),
            "escola" => $escola,
            "instituicoes" => $instituicoes,
            "estudantes" => $estudantes,
            "anos_lectivos" => $anos_lectivos,
            "trimestres" => $trimestres,
            "bolsas" => $bolsas,
        ];

        return view('admin.bolseiros.home', $headers);
    }
    
    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user->can('read: bolseiro')) {
            Alert::error(
                'Acesso restrito',
                'Você não possui permissão para esta operação, por favor, contacte o administrador!'
            );
            return redirect()->back();
        }
    
        $paginacao = $request->paginacao ?? 5;
    
        $query = Bolseiro::when($request->designacao_geral, function ($query, $value) {
            $query->where(function ($q) use ($value) {
                foreach (['desconto', 'status', 'afectacao'] as $field) {
                    $q->orWhere($field, 'like', "%{$value}%");
                }
            });
        })
        ->with(['instituicao','bolsa', 'instituicao_bolsa', 'ano', 'periodo', 'estudante', 'escola'])
        ->when($request->data_status, function($query, $value) {
            $query->where('status', $value);
        })
        ->when($request->estudanteId, function($query, $value){
            $query->where('estudante_id', $value);
        })
        ->when($request->instituicaoId, function($query, $value){
            $query->where('instituicao_id', $value);
        })
        ->where('shcools_id', $this->escolarLogada());
    
        return response()->json(
            $query->orderByDesc('id')->paginate($paginacao)
        );
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('create: bolseiro')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            'estudante_id' => 'required',
            'instituicao_id' => 'required',
            'bolsa_id' => 'required',
            'ano_lectivo_id' => 'required',
            'periodo_id' => 'required',
            'afectacao' => 'required',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
     
            $instutuicao_bolsa = BolsaInstituicao::where('bolsa_id', $request->bolsa_id)
                ->where('instituicao_id', $request->instituicao_id)
            ->first();

            $verificar_bolsa = Bolseiro::where('periodo_id', $request->periodo_id)
                ->where('ano_lectivos_id', $request->ano_lectivo_id)
                ->where('instituicao_id', $request->instituicao_id)
                ->where('bolsa_id', $request->bolsa_id)
                ->where('estudante_id', $request->estudante_id)
                ->where('instutuicao_bolsa_id', $instutuicao_bolsa->id)
            ->first();

            if (!$verificar_bolsa) {

                if ($instutuicao_bolsa->desconto == 100) {
                    $cobertura = 'Y';
                } else {
                    $cobertura = 'N';
                }

                Bolseiro::create([
                    'status' => $request->status,
                    'afectacao' => $request->afectacao,
                    'estudante_id' => $request->estudante_id,
                    'instutuicao_bolsa_id' => $instutuicao_bolsa->id,
                    'bolsa_id' => $request->bolsa_id,
                    'instituicao_id' => $request->instituicao_id,
                    'periodo_id' => $request->periodo_id,
                    'ano_lectivos_id' => $request->ano_lectivo_id,
                    'shcools_id' => $this->escolarLogada(),
                ]);

                $trimestre = Trimestre::findOrFail($request->periodo_id);

                $estudanteTurma = EstudantesTurma::where('tb_turmas_estudantes.estudantes_id', $request->estudante_id)
                    ->where('tb_turmas_estudantes.ano_lectivos_id', $request->ano_lectivo_id)
                    ->where('tb_servicos_turma.model', 'turmas')
                    ->join('tb_servicos_turma', 'tb_turmas_estudantes.turmas_id', '=', 'tb_servicos_turma.turmas_id')
                ->get();

                $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

                if ($escola->ensino->nome == "Ensino Superior") {
                    foreach ($estudanteTurma as $servico) {
                        if ($request->afectacao == "mensalidade") {

                            if ($servico->pagamento == 'mensal') {

                                if ($trimestre->trimestre == "Iª Simestre") {
                                
                                    $cartao = CartaoEstudante::where('semestral', "1º Semestre")
                                        ->where('ano_lectivos_id', $request->ano_lectivo_id)
                                        ->where('servicos_id', $servico->servicos_id)
                                        ->where('estudantes_id', $request->estudante_id)
                                    ->get();
                                    
                                }
                                
                                if ($trimestre->trimestre == "IIª Simestre") {
                                
                                    $cartao = CartaoEstudante::where('semestral', "2º Semestre")
                                        ->where('ano_lectivos_id', $request->ano_lectivo_id)
                                        ->where('servicos_id', $servico->servicos_id)
                                        ->where('estudantes_id', $request->estudante_id)
                                    ->get();
                                    
                                }
                                
                                if ($trimestre->trimestre == "Anual") {
                                
                                    $cartao = CartaoEstudante::whereIn('semestral', ["1º Semestre", "2º Semestre"])
                                        ->where('ano_lectivos_id', $request->ano_lectivo_id)
                                        ->where('servicos_id', $servico->servicos_id)
                                        ->where('estudantes_id', $request->estudante_id)
                                    ->get();
                                    
                                }

                                if ($instutuicao_bolsa->desconto == 100) {
                                    if ($cartao) {
                                        foreach ($cartao as $cart) {
                                            $update = CartaoEstudante::findOrFail($cart->id);
                                            $update->status = "Pago";
                                            $update->status_2 = "Bolsa";
                                            $update->cobertura = $cobertura;
                                            $update->update();
                                        }
                                    }
                                } else {
                                    if ($cartao) {
                                        foreach ($cartao as $cart) {
                                            $update = CartaoEstudante::findOrFail($cart->id);
                                            $update->status = "Nao Pago";
                                            $update->status_2 = "Bolsa";
                                            $update->cobertura = $cobertura;
                                            $update->update();
                                        }
                                    }
                                }
                            }
                        }

                        if ($request->afectacao == "global") {

                            if ($trimestre->trimestre == "Iª Simestre") {
                            
                                $cartao = CartaoEstudante::whereIn('semestral', ["1º Semestre", "Normal"])
                                    ->where('ano_lectivos_id', $request->ano_lectivo_id)
                                    ->where('estudantes_id', $request->estudante_id)
                                ->get();
                                
                            }
                            if ($trimestre->trimestre == "IIª Simestre") {
                            
                                $cartao = CartaoEstudante::whereIn('semestral', ["2º Semestre", "Normal"])
                                    ->where('ano_lectivos_id', $request->ano_lectivo_id)
                                    ->where('estudantes_id', $request->estudante_id)
                                ->get();
                                
                            }
                            if ($trimestre->trimestre == "Anual") {
                            
                                $cartao = CartaoEstudante::whereIn('semestral', ["1º Semestre", "2º Semestre", "Normal"])
                                    ->where('ano_lectivos_id', $request->ano_lectivo_id)
                                    ->where('estudantes_id', $request->estudante_id)
                                ->get();
                                
                            }

                            $cartao = CartaoEstudante::where('estudantes_id', $request->estudante_id)->get();
                            
                            if ($cartao) {
                                foreach ($cartao as $cart) {
                                    $update = CartaoEstudante::findOrFail($cart->id);
                                    $update->status = "Pago";
                                    $update->status_2 = "Bolsa";
                                    $update->cobertura = $cobertura;
                                    $update->update();
                                }
                            }
                        }
                    }
                } else {

                    if ($request->afectacao == "mensalidade") {
                    
                        foreach ($estudanteTurma as $servico) {
                        
                            if ($servico->pagamento == 'mensal') {

                                if ($trimestre->trimestre == "Iª Trimestre") {
                                
                                    $cartao = CartaoEstudante::where('trimestral', "1º Trimestre")
                                        ->where('ano_lectivos_id', $request->ano_lectivo_id)
                                        ->where('servicos_id', $servico->servicos_id)
                                        ->where('estudantes_id', $request->estudante_id)
                                    ->get();
                                    
                                }
                                
                                if ($trimestre->trimestre == "IIª Trimestre") {
                                
                                    $cartao = CartaoEstudante::where('trimestral', "2º Trimestre")
                                        ->where('ano_lectivos_id', $request->ano_lectivo_id)
                                        ->where('servicos_id', $servico->servicos_id)
                                        ->where('estudantes_id', $request->estudante_id)
                                    ->get();
                                    
                                }
                                
                                if ($trimestre->trimestre == "IIIª Trimestre") {
                                
                                    $cartao = CartaoEstudante::where('trimestral', "3º Trimestre")
                                        ->where('ano_lectivos_id', $request->ano_lectivo_id)
                                        ->where('servicos_id', $servico->servicos_id)
                                        ->where('estudantes_id', $request->estudante_id)
                                    ->get();
                                    
                                }

                                if ($trimestre->trimestre == "Geral") {
                                
                                    $cartao = CartaoEstudante::whereIn('trimestral', ["1º Trimestre", "2º Trimestre", "3º Trimestre"])
                                        ->where('ano_lectivos_id', $request->ano_lectivo_id)
                                        ->where('servicos_id', $servico->servicos_id)
                                        ->where('estudantes_id', $request->estudante_id)
                                    ->get();
                                    
                                }

                                if ($instutuicao_bolsa->desconto == 100) {
                                    if ($cartao) {
                                        foreach ($cartao as $cart) {
                                            $update = CartaoEstudante::findOrFail($cart->id);
                                            $update->status = "Pago";
                                            $update->status_2 = "Bolsa";
                                            $update->cobertura = $cobertura;
                                            $update->update();
                                        }
                                    }
                                } else {
                                    if ($cartao) {
                                        foreach ($cartao as $cart) {
                                            $update = CartaoEstudante::findOrFail($cart->id);
                                            $update->status = "Nao Pago";
                                            $update->status_2 = "Bolsa";
                                            $update->cobertura = $cobertura;
                                            $update->update();
                                        }
                                    }
                                }
                            }
                        }
                        
                    }

                    if ($request->afectacao == "global") {

                        if ($trimestre->trimestre == "Iª Trimestre") {
                        
                            $cartao = CartaoEstudante::whereIn('trimestral', ["1º Trimestre", "Normal"])
                                ->where('ano_lectivos_id', $request->ano_lectivo_id)
                                ->where('estudantes_id', $request->estudante_id)
                            ->get();
                            
                        }
                        if ($trimestre->trimestre == "IIª Trimestre") {
                        
                            $cartao = CartaoEstudante::whereIn('trimestral', ["2º Trimestre", "Normal"])
                                ->where('ano_lectivos_id', $request->ano_lectivo_id)
                                ->where('estudantes_id', $request->estudante_id)
                            ->get();
                            
                        }
                        if ($trimestre->trimestre == "IIIª Trimestre") {
                        
                            $cartao = CartaoEstudante::whereIn('trimestral', ["3º Trimestre", "Normal"])
                                ->where('ano_lectivos_id', $request->ano_lectivo_id)
                                ->where('estudantes_id', $request->estudante_id)
                            ->get();
                            
                        }

                        if ($trimestre->trimestre == "Geral") {
                        
                            $cartao = CartaoEstudante::whereIn('trimestral', ["1º Trimestre", "2º Trimestre", "3º Trimestre", "Normal"])
                                ->where('ano_lectivos_id', $request->ano_lectivo_id)
                                ->where('estudantes_id', $request->estudante_id)
                            ->get();
                            
                        }

                        if ($instutuicao_bolsa->desconto == 100) {
                            if ($cartao) {
                                foreach ($cartao as $cart) {
                                    $up = CartaoEstudante::findOrFail($cart->id);
                                    $up->status = "Pago";
                                    $up->status_2 = "Bolsa";
                                    $up->cobertura = $cobertura;
                                    $up->update();
                                }
                            }
                        } else {
                            if ($cartao) {
                                foreach ($cartao as $cart) {
                                    $up = CartaoEstudante::findOrFail($cart->id);
                                    $up->status = "Nao Pago";
                                    $up->status_2 = "Bolsa";
                                    $up->cobertura = $cobertura;
                                    $up->update();
                                }
                            }
                        }
                    }
                }
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

    public function destroy($id)
    {
        $user = auth()->user();
        
        if(!$user->can('read: bolseiro')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada()); 
                                            
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $bolseiro = Bolseiro::findOrFail($id); 
            
            $trimestre = Trimestre::findOrFail($bolseiro->periodo_id);
                    
            $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());
            
            if($escola->ensino->nome == "Ensino Superior"){
                
                if($bolseiro->afectacao == "mensalidade"){
                    if($trimestre->trimestre == "Iª Simestre"){
                        $cartao = CartaoEstudante::where('semestral', "1º Semestre")
                            ->where('status_2', 'Bolsa')
                            ->where('estudantes_id', $bolseiro->estudante_id)
                        ->get();
                    }
                    if($trimestre->trimestre == "IIª Simestre"){
                        $cartao = CartaoEstudante::where('semestral', "2º Semestre")
                            ->where('status_2', 'Bolsa')
                            ->where('estudantes_id', $bolseiro->estudante_id)
                        ->get();
                    }
                    if($trimestre->trimestre == "Anual"){
                        $cartao = CartaoEstudante::whereIn('semestral', ["1º Semestre", "2º Semestre"])
                            ->where('status_2', 'Bolsa')
                            ->where('estudantes_id', $bolseiro->estudante_id)
                        ->get();
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
                        $cartao = CartaoEstudante::whereIn('semestral', ["1º Semestre", "Normal"])
                            ->where('status_2', 'Bolsa')
                            ->where('estudantes_id', $bolseiro->estudante_id)
                        ->get();
                    }
                    if($trimestre->trimestre == "IIª Simestre"){
                        $cartao = CartaoEstudante::whereIn('semestral', ["2º Semestre", "Normal"])
                            ->where('status_2', 'Bolsa')
                            ->where('estudantes_id', $bolseiro->estudante_id)
                        ->get();
                    }
                    if($trimestre->trimestre == "Anual"){
                        $cartao = CartaoEstudante::whereIn('semestral', ["1º Semestre", "2º Semestre", "Normal"])
                            ->where('status_2', 'Bolsa')
                            ->where('estudantes_id', $bolseiro->estudante_id)
                        ->get();
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
                        $cartao = CartaoEstudante::where('trimestral', "1º Trimestre")
                            ->where('status_2', 'Bolsa')
                            ->where('estudantes_id', $bolseiro->estudante_id)
                        ->get();
                    }
                    if($trimestre->trimestre == "IIª Trimestre"){
                        $cartao = CartaoEstudante::where('trimestral', "2º Trimestre")
                            ->where('status_2', 'Bolsa')
                            ->where('estudantes_id', $bolseiro->estudante_id)
                        ->get();
                    }
                    if($trimestre->trimestre == "IIIª Trimestre"){
                        $cartao = CartaoEstudante::where('trimestral', "3º Trimestre")
                            ->where('status_2', 'Bolsa')
                            ->where('estudantes_id', $bolseiro->estudante_id)
                        ->get();
                    }
                    
                    if($trimestre->trimestre == "Geral"){
                        $cartao = CartaoEstudante::whereIn('trimestral', ["1º Trimestre", "2º Trimestre", "3º Trimestre"])
                            ->where('status_2', 'Bolsa')
                            ->where('estudantes_id', $bolseiro->estudante_id)
                        ->get();
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
                        $cartao = CartaoEstudante::whereIn('trimestral', ["1º Trimestre", "Normal"])
                            ->where('status_2', 'Bolsa')
                            ->where('estudantes_id', $bolseiro->estudante_id)
                        ->get();
                    }
                    if($trimestre->trimestre == "IIª Trimestre"){
                        $cartao = CartaoEstudante::whereIn('trimestral', ["2º Trimestre", "Normal"])
                            ->where('status_2', 'Bolsa')
                            ->where('estudantes_id', $bolseiro->estudante_id)
                        ->get();
                    }
                    if($trimestre->trimestre == "IIIª Trimestre"){
                        $cartao = CartaoEstudante::whereIn('trimestral', ["3º Trimestre", "Normal"])
                            ->where('status_2', 'Bolsa')
                            ->where('estudantes_id', $bolseiro->estudante_id)
                        ->get();
                    }
                    
                    if($trimestre->trimestre == "Geral"){
                        $cartao = CartaoEstudante::whereIn('trimestral', ["1º Trimestre", "2º Trimestre", "3º Trimestre", "Normal"])
                            ->where('status_2', 'Bolsa')
                            ->where('estudantes_id', $bolseiro->estudante_id)
                        ->get();
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
