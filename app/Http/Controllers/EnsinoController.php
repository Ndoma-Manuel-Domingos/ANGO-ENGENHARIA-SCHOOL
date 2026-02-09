<?php

namespace App\Http\Controllers;

use App\Exports\EnsinoExport;
use App\Models\Ensino;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class EnsinoController extends Controller
{

    use TraitHelpers;
    
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
        
        if(!$user->can('read: ensino')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
    
        $headers = [
            "titulo" => "Lista de Ensino",
            "descricao" => env('APP_NAME'),
            "datas" => Ensino::get(), 
            "usuario" => User::findOrFail(Auth::user()->id),
            "loyout" => $request->loyout,
        ];

        return view('sistema.ensinos.home', $headers);
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
        $user = auth()->user();
        
        if(!$user->can('create: ensino')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $validate = Validator::make($request->all(), [
            "ensino" => 'required',
            "status" => 'required',
        ], [
            "ensino.required" => "Campo Obrigatório",
            "status.required" => "Campo Obrigatório",
        ]);


        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        }else{
    
            $verificar = Ensino::where([
                ['nome', $request->input('ensino')],
            ])->first();
    
            if($verificar){
                return response()->json([
                    'status' => 300,
                    'message' => "Este Ensino já Esta Cadastrado!",
                ]);
            }
            
            Ensino::create([
                'nome' => $request->input('ensino'),
                'status' => $request->input('status'),
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Dados salvos com sucesso!',
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ensino  $ensino
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ensino  $ensino
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = auth()->user();
        
        if(!$user->can('update: ensino')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $data = Ensino::find($id);

        if ($data) {
            return response()->json([
                "status" => 200,
                "data" => $data
            ]);
        }else{
            return response()->json([
                "status" => 404,
                "message" => 'Enisno não Encontrado',
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ensino  $ensino
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        
        if(!$user->can('update: ensino')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $validate = Validator::make($request->all(), [
            "ensino" => 'required',
            "status" => 'required',
        ], [
            "ensino.required" => "Campo Obrigatório",
            "status.required" => "Campo Obrigatório",
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        }else{
            $update = Ensino::findOrFail($id);

            if ($update) {
                $update->nome = $request->input('ensino');
                $update->status = $request->input('status');
                $update->update();

                return response()->json([
                    'status' => 200,
                    'message' => 'Dados Actualizados com sucesso!',
                    "usuario" => User::findOrFail(Auth::user()->id),
                ]);
            }else{
                return response()->json([
                    "status" => 404,
                    "message" => 'Ensino não Encontrado'
                ]);
            }

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ensino  $ensino
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = auth()->user();
        
        if(!$user->can('delete: ensino')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $data = Ensino::findOrFail($id);
        $data->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Dados Excluido com sucesso!',
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }
    
    
    // activar e desactivar provincia
    public function activarensinos($id)
    {
        $user = auth()->user();
        
        if(!$user->can('update: estado')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        
        $data = Ensino::findOrFail($id);
        if ($data) {
            if ($data->status === 'activo') {
                $data->status = 'desactivo';
            }else{
                $data->status = 'activo';
            }
            if ($data->update()) {
                return response()->json([
                    "status" => 200,
                    "message" => "Dodos Activados com sucesso",
                    "usuario" => User::findOrFail(Auth::user()->id),
                ]);
            }
        }
    }

    public function ensinosImprimir()
    {
        $headers = [
            "titulo" => "LISTA DOS ENSINOS",
            "datas" => Ensino::get()
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-ensinos', $headers);
        return $pdf->stream('lista-ensinos.pdf');
    }

    public function ensinosExcel()
    {
        return Excel::download(new EnsinoExport, 'ensinos.xlsx');
    }
}
