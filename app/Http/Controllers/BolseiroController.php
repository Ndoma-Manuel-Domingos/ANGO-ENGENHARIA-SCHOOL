<?php

namespace App\Http\Controllers;

use App\Models\Shcool;
use App\Models\User;
use App\Models\web\turmas\Bolsa;
use App\Models\web\turmas\Bolseiro;
use App\Models\web\turmas\InstituicaoEducacional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $headers = [
            "titulo" => "Listar Bolseiros",
            "descricao" => env('APP_NAME'),
            "escola" => $escola,
            "instituicoes" => $instituicoes,
            "bolsas" => $bolsas,
        ];

        return view('admin.creditos-educacionais.bolseiros', $headers);
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
        });
        // ->when($request->instituicao_id, function($query, $value){
        //     $query->where('instituicao_id', $value);
        // })
        // ->when($request->bolsa_id, function($query, $value){
        //     $query->where('bolsa_id', $value);
        // })
        // ->where('shcools_id', $this->escolarLogada());
    
        return response()->json(
            $query->orderByDesc('id')->paginate($paginacao)
        );
    }


    // public function instituicao_remover_bolsa_bolseiro($id)
    // {
    //     $user = auth()->user();
        
    //     // if(!$user->can('read: ensino')){
    //     //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
    //     //     return redirect()->back();
    //     // }
        
    //     $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada()); 
    //     $bolseiro = Bolseiro::findOrFail(Crypt::decrypt($id)); 
        
    //     $trimestre = Trimestre::findOrFail($bolseiro->periodo_id);
        
    //     $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());
        
    //     if($escola->ensino->nome == "Ensino Superior"){
            
    //         if($bolseiro->afectacao == "mensalidade"){
    //             if($trimestre->trimestre == "Iª Simestre"){
    //                 $cartao = CartaoEstudante::where('semestral', "1º Semestre")->where('status_2', 'Bolsa')->where('estudantes_id', $bolseiro->estudante_id)->get();
    //             }
    //             if($trimestre->trimestre == "IIª Simestre"){
    //                 $cartao = CartaoEstudante::where('semestral', "2º Semestre")->where('status_2', 'Bolsa')->where('estudantes_id', $bolseiro->estudante_id)->get();
    //             }
    //             if($trimestre->trimestre == "Anual"){
    //                 $cartao = CartaoEstudante::whereIn('semestral', ["1º Semestre", "2º Semestre"])->where('status_2', 'Bolsa')->where('estudantes_id', $bolseiro->estudante_id)->get();
    //             }
                
    //             if($cartao){
    //                 foreach($cartao as $cart){
    //                     $update = CartaoEstudante::findOrFail($cart->id);
    //                     $update->status = "Nao Pago";
    //                     $update->status_2 = "Normal";
    //                     $update->update();
    //                 }
    //             }
    //         }
            
    //         if($bolseiro->afectacao == "global"){
    //             if($trimestre->trimestre == "Iª Simestre"){
    //                 $cartao = CartaoEstudante::whereIn('semestral', ["1º Semestre", "Normal"])->where('status_2', 'Bolsa')->where('estudantes_id', $bolseiro->estudante_id)->get();
    //             }
    //             if($trimestre->trimestre == "IIª Simestre"){
    //                 $cartao = CartaoEstudante::whereIn('semestral', ["2º Semestre", "Normal"])->where('status_2', 'Bolsa')->where('estudantes_id', $bolseiro->estudante_id)->get();
    //             }
    //             if($trimestre->trimestre == "Anual"){
    //                 $cartao = CartaoEstudante::whereIn('semestral', ["1º Semestre", "2º Semestre", "Normal"])->where('status_2', 'Bolsa')->where('estudantes_id', $bolseiro->estudante_id)->get();
    //             }
                
    //             if($cartao){
    //                 foreach($cartao as $cart){
    //                     $update = CartaoEstudante::findOrFail($cart->id);
    //                     $update->status = "Nao Pago";
    //                     $update->status_2 = "Normal";
    //                     $update->update();
    //                 }
    //             }
    //         }
    //     }else{
    //         if($bolseiro->afectacao == "mensalidade"){
    //             if($trimestre->trimestre == "Iª Trimestre"){
    //                 $cartao = CartaoEstudante::where('trimestral', "1º Trimestre")->where('status_2', 'Bolsa')->where('estudantes_id', $bolseiro->estudante_id)->get();
    //             }
    //             if($trimestre->trimestre == "IIª Trimestre"){
    //                 $cartao = CartaoEstudante::where('trimestral', "2º Trimestre")->where('status_2', 'Bolsa')->where('estudantes_id', $bolseiro->estudante_id)->get();
    //             }
    //             if($trimestre->trimestre == "IIIª Trimestre"){
    //                 $cartao = CartaoEstudante::where('trimestral', "3º Trimestre")->where('status_2', 'Bolsa')->where('estudantes_id', $bolseiro->estudante_id)->get();
    //             }
                
    //             if($trimestre->trimestre == "Geral"){
    //                 $cartao = CartaoEstudante::whereIn('trimestral', ["1º Trimestre", "2º Trimestre", "3º Trimestre"])->where('status_2', 'Bolsa')->where('estudantes_id', $bolseiro->estudante_id)->get();
    //             }
                
    //             if($cartao){
    //                 foreach($cartao as $cart){
    //                     $update = CartaoEstudante::findOrFail($cart->id);
    //                     $update->status = "Nao Pago";
    //                     $update->status_2 = "Normal";
    //                     $update->update();
    //                 }
    //             }
    //         }
                      
    //         if($bolseiro->afectacao == "global"){
    //             if($trimestre->trimestre == "Iª Trimestre"){
    //                 $cartao = CartaoEstudante::whereIn('trimestral', ["1º Trimestre", "Normal"])->where('status_2', 'Bolsa')->where('estudantes_id', $bolseiro->estudante_id)->get();
    //             }
    //             if($trimestre->trimestre == "IIª Trimestre"){
    //                 $cartao = CartaoEstudante::whereIn('trimestral', ["2º Trimestre", "Normal"])->where('status_2', 'Bolsa')->where('estudantes_id', $bolseiro->estudante_id)->get();
    //             }
    //             if($trimestre->trimestre == "IIIª Trimestre"){
    //                 $cartao = CartaoEstudante::whereIn('trimestral', ["3º Trimestre", "Normal"])->where('status_2', 'Bolsa')->where('estudantes_id', $bolseiro->estudante_id)->get();
    //             }
                
    //             if($trimestre->trimestre == "Geral"){
    //                 $cartao = CartaoEstudante::whereIn('trimestral', ["1º Trimestre", "2º Trimestre", "3º Trimestre", "Normal"])->where('status_2', 'Bolsa')->where('estudantes_id', $bolseiro->estudante_id)->get();
    //             }
                
    //             if($cartao){
    //                 foreach($cartao as $cart){
    //                     $update = CartaoEstudante::findOrFail($cart->id);
    //                     $update->status = "Nao Pago";
    //                     $update->status_2 = "Normal";
    //                     $update->update();
    //                 }
    //             }
    //        }
    //     }
        
    //     $bolseiro->delete();

    //     Alert::success("Bom Trabalho", "Dados Removida com sucesso");
    //     return redirect()->route('creditos-educacionais.instituicao-listar-bolseiros');

    // }

}
