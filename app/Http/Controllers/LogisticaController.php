<?php

namespace App\Http\Controllers;

use App\Models\DireccaoMunicipal;
use App\Models\DireccaoProvincia;
use App\Models\Fornecedor;
use App\Models\Instituicao;
use App\Models\Mercadoria;
use App\Models\Shcool;
use App\Models\Stock;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class LogisticaController extends Controller
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
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // if(!$user->can('read: escola')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $usuario = User::findOrFail(Auth::user()->id);

        $headers =  [
            "titulo" => "Logística",
            "total_mercadorias" => Mercadoria::count(),
            "descricao" => "Gestão de Logistica",
            "usuario" => $usuario,
        ];
        
        return view('sistema.ministerio.logisticas.index', $headers);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function stock(Request $request)
    {
        $user = auth()->user();
        
        // if(!$user->can('read: escola')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $usuario = User::findOrFail(Auth::user()->id);
        
        $stocks = Stock::when($request->data_inicio, function($query, $value){
            $query->where('created_at', '>=', Carbon::createFromDate($value));
        })
        ->when($request->data_final, function($query, $value){
            $query->where('created_at', '<=', Carbon::createFromDate($value));
        })
        ->with(["user", "mercadoria", "fornecedor"])
        ->get();

        $headers =  [
            "titulo" => "Stock de Mercadorias",
            "descricao" => "Gestão de Stock de Mercadorias",
            "usuario" => $usuario,
            "stocks" => $stocks,
        ];
        
        return view('sistema.ministerio.logisticas.stock', $headers);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function stock_create(Request $request)
    {
        $user = auth()->user();
        
        // if(!$user->can('read: escola')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $usuario = User::findOrFail(Auth::user()->id);
        $mercadorias = Mercadoria::get();
        $fornecedores = Fornecedor::get();

        $headers =  [
            "titulo" => "Actualizar Stock de Mercadorias",
            "descricao" => "Gestão de Stock de Mercadorias",
            "usuario" => $usuario,
            "mercadorias" => $mercadorias,
            "fornecedores" => $fornecedores,
        ];
        
        return view('sistema.ministerio.logisticas.stock-create', $headers);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function stock_distribuicao(Request $request)
    {
        $user = auth()->user();
        
        // if(!$user->can('read: escola')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $usuario = User::findOrFail(Auth::user()->id);
        $mercadorias = Mercadoria::get();
        $fornecedores = Fornecedor::get();
        $tipo_instituicoes = Instituicao::where('nome', '!=', 'MINISTERIO')->get();
        
        $headers =  [
            "titulo" => "Distribuição de Mercadorias",
            "descricao" => "Distribuição de Mercadorias",
            "usuario" => $usuario,
            "mercadorias" => $mercadorias,
            "fornecedores" => $fornecedores,
            "tipo_instituicoes" => $tipo_instituicoes,
        ];
        
        return view('sistema.ministerio.logisticas.stock-distribuicao', $headers);
    }
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function stock_distribuicao_post(Request $request)
    {
    
        $user = auth()->user();
        
        // if(!$user->can('read: escola')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        $request->validate([
            "mercadoria_id" => 'required',
            "quantidade" => 'required',
            "instituicoes_destino" => 'required',
            "status" => 'required',
            "descricao" => 'required',
            "unidade" => 'required',
        ], [
            "mercadoria_id.required" => "Campo Obrigatório",
            "quantidade.required" => "Campo Obrigatório",
            "unidade.required" => "Campo Obrigatório",
            "status.required" => "Campo Obrigatório",
            "instituicoes_destino.required" => "Campo Obrigatório",
            "descricao.required" => "Campo Obrigatório",
        ]);
        
                
        $level = 0;
        $instituicao = Instituicao::findOrFail($request->instituicao_id);
        
        if($instituicao->nome == "MINISTERIO"){
            $level = 1;
            $destino = NULL;
        }
        if($instituicao->nome == "PROVINCIAS"){
            $level = 2;
            $destino = DireccaoProvincia::findOrFail($request->instituicoes_destino);
        }
        if($instituicao->nome == "MUNICIPAIS"){
            $level = 3;
            $destino = DireccaoMunicipal::findOrFail($request->instituicoes_destino);
        }
        if($instituicao->nome == "ESCOLAS"){
            $level = 4;
            $destino = Shcool::findOrFail($request->instituicoes_destino);
        }

        $create = Stock::create([
            'user_id' => $user->id,
            'status' => $request->status,
            'quantidade' => $request->quantidade,
            'unidade' => $request->unidade,
            'mercadoria_id' => $request->mercadoria_id,
            'shcools_id' => $destino->id,
            'level' => $level,
            'descricao' => $request->descricao,
        ]);

        Alert::success("Bom Trabalho", "Dados salvos com sucesso");
        return redirect()->route('web.stock-mercadorias-create');
    }
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function stock_post(Request $request)
    {
    
        $user = auth()->user();
        
        // if(!$user->can('read: escola')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        $request->validate([
            "mercadoria_id" => 'required',
            "quantidade" => 'required',
            "fornecedor_id" => 'required',
            "status" => 'required',
            "descricao" => 'required',
            "unidade" => 'required',
        ], [
            "mercadoria_id.required" => "Campo Obrigatório",
            "quantidade.required" => "Campo Obrigatório",
            "unidade.required" => "Campo Obrigatório",
            "status.required" => "Campo Obrigatório",
            "fornecedor_id.required" => "Campo Obrigatório",
            "descricao.required" => "Campo Obrigatório",
        ]);

        $create = Stock::create([
            'user_id' => $user->id,
            'status' => $request->status,
            'quantidade' => $request->quantidade,
            'unidade' => $request->unidade,
            'mercadoria_id' => $request->mercadoria_id,
            'fornecedor_id' => $request->fornecedor_id,
            // 'shcools_id' => $request->status,
            // 'level' => $request->status,
            'descricao' => $request->descricao,
        ]);

        Alert::success("Bom Trabalho", "Dados salvos com sucesso");
        return redirect()->route('web.stock-mercadorias-create');
    }
    
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function stock_edit(Request $request, $id)
    {
        $user = auth()->user();
        
        // if(!$user->can('read: escola')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $usuario = User::findOrFail(Auth::user()->id);
        $mercadorias = Mercadoria::get();
        $fornecedores = Fornecedor::get();
        
        $stock = Stock::findOrFail($id);

        $headers =  [
            "titulo" => "Actualizar Stock de Mercadorias",
            "descricao" => "Gestão de Stock de Mercadorias",
            "usuario" => $usuario,
            "mercadorias" => $mercadorias,
            "fornecedores" => $fornecedores,
            "stock" => $stock,
        ];
        
        return view('sistema.ministerio.logisticas.stock-edit', $headers);
    }
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function stock_distribuicao_edit(Request $request, $id)
    {
        $user = auth()->user();
        
        // if(!$user->can('read: escola')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $usuario = User::findOrFail(Auth::user()->id);
        $mercadorias = Mercadoria::get();
        $fornecedores = Fornecedor::get();
        $tipo_instituicoes = Instituicao::where('nome', '!=', 'MINISTERIO')->get();
        $stock = Stock::findOrFail($id);
        
        
        if($stock->level == "4"){
            $instituicao = Shcool::findOrFail($stock->shcools_id);
        }
        
        if($stock->level == "3"){
           $instituicao = DireccaoMunicipal::findOrFail($stock->shcools_id);
        }
        
        if($stock->level == "2"){
           $instituicao = DireccaoProvincia::findOrFail($stock->shcools_id);
        }

        
        $headers =  [
            "titulo" => "Distribuição de Mercadorias",
            "descricao" => "Distribuição de Mercadorias",
            "usuario" => $usuario,
            "mercadorias" => $mercadorias,
            "fornecedores" => $fornecedores,
            "tipo_instituicoes" => $tipo_instituicoes,
            "stock" => $stock,
            "instituicao" => $instituicao,
        ];
        
        return view('sistema.ministerio.logisticas.stock-distribuicao-edit', $headers);
    }
    
    

}
