<?php

namespace App\Http\Controllers;

use App\Models\Shcool;
use App\Models\SolicitacaoDocumento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use RealRashid\SweetAlert\Facades\Alert;

class SolicitacaoDocumentoController extends Controller
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
        // $user = auth()->user();
        
        // if(!$user->can('read: encarregado')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        $documentos = SolicitacaoDocumento::when($request->processo, function($query, $value){
            $query->where('processo', $value);
        })
        ->with(['trimestre', 'estudante','ano','escola', 'efeito'])
        ->where('shcools_id', '=', $this->escolarLogada())
        ->get();

        

        $headers = [ 
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Lista dos notificações",
            "descricao" => env('APP_NAME'),  
            "usuario" => User::findOrFail(Auth::user()->id), 
            "documentos" => $documentos,
        ];


        return view('admin.solicitacoes.index', $headers);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    
        $documento = SolicitacaoDocumento::findOrFail($id);
        
        $document = "";
        
        if($documento->tipo_documento == "declaracao com nota"){
            $document = "declaracao-nota";
        }
        
        if($documento->tipo_documento == "declaracao sem nota"){
            $document = "declarcao-sem-nota";
        }
        
        if($documento->tipo_documento == "declaracao"){
            $document = "classificacao-final";
        }
        
        $route = "/download/pauta-estudante?id=" . Crypt::encrypt($documento->user_id). "&ano=" . Crypt::encrypt($documento->ano_lectivos_id) . "&condicao=" . Crypt::encrypt($document) . "&condicao2=" .Crypt::encrypt($documento->efeito_id);
        
        $documento->processo = "CONCLUIDO";
        $documento->status = 1;
        $documento->links = $route;
        $documento->user_final_id = Auth::user()->id;
        
        $documento->update();

        Alert::success("Bom Trabalho", "Documento concluido para o Estudante");
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    
        $documento = SolicitacaoDocumento::findOrFail($id);
        
        if($documento->status == 0 AND $documento->processo == 'EM PROCESSO'){
            $documento->processo = "ENCAMINHADO";
            
            $documento->update();
            
            Alert::success("Bom Trabalho", "Documento Encaminhado para o Director Geral");
            return redirect()->back();
        }
        
        if($documento->status == 1 AND $documento->processo == 'ENCAMINHADO'){
            $documento->processo = "CONCLUIDO";
            
            $documento->update();
            
            Alert::success("Bom Trabalho", "Documento Encaminhado para o Estudante");
            return redirect()->back();
        }
        
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
