<?php

namespace App\Http\Controllers;

use App\Models\Distrito;
use App\Models\Municipio;
use App\Models\Provincia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;


class DistritoController extends Controller
{
         /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
                
        if(!$user->can('read: municipio')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
    
        $headers = [
            "titulo" => "Lista de Distritos",
            "descricao" => env('APP_NAME'),
            "municipios" => Municipio::get(), 
            "datas" => Distrito::with('municipio')->get(), 
            "usuario" => User::findOrFail(Auth::user()->id),
            
        ];

        return view('sistema.distritos.home', $headers);
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
        
        if(!$user->can('create: municipio')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $validate = Validator::make($request->all(), [
            "municipio_id" => 'required',
            "status" => 'required',
            "nome" => 'required',
        ], [
            "municipio_id.required" => "Campo Obrigatório",
            "status.required" => "Campo Obrigatório",
            "nome.required" => "Campo Obrigatório",
        ]);


        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        }else{
    
            $verificar = Distrito::where([
                ['nome', $request->input('nome')],
            ])->first();
    
            if($verificar){
                return response()->json([
                    'status' => 300,
                    'message' => "Este Distrito já Esta Cadastrado!",
                ]);
            }
            
            Distrito::create([
                'nome' => $request->input('nome'),
                'status' => $request->input('status'),
                'municipio_id' => $request->input('municipio_id'),
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
        
        if(!$user->can('update: municipio')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $data = Distrito::with('municipio')->findOrFail($id);

        if ($data) {
            return response()->json([
                "status" => 200,
                "data" => $data
            ]);
        }else{
            return response()->json([
                "status" => 404,
                "message" => 'Distrito não Encontrado',
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
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
        $user = auth()->user();
        
        if(!$user->can('update: municipio')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $validate = Validator::make($request->all(), [
            "municipio_id" => 'required',
            "status" => 'required',
            "nome" => 'required',
        ], [
            "provincia_id.required" => "Campo Obrigatório",
            "status.required" => "Campo Obrigatório",
            "enom.required" => "Campo Obrigatório",
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        }else{
            $update = Distrito::findOrFail($id);

            if ($update) {
                $update->nome = $request->input('nome');
                $update->municipio_id = $request->input('municipio_id');
                $update->status = $request->input('status');
                $update->update();

                return response()->json([
                    'status' => 200,
                    'message' => 'Dados ACtualizados com sucesso!',
                    "usuario" => User::findOrFail(Auth::user()->id),
                ]);
            }else{
                return response()->json([
                    "status" => 404,
                    "message" => 'Distrito não Encontrado'
                ]);
            }

        }
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
        
        if(!$user->can('delete: municipio')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $data = Distrito::findOrFail($id);
        $data->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Dados Excluido com sucesso!',
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }
    
    
    // activar e desactivar municipio
    public function activardistrito($id)
    {
        $user = auth()->user();
        
        if(!$user->can('update: estado')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        
        $data = Distrito::findOrFail($id);
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


    public function municipiosImprimir()
    {
        $headers = [
            "titulo" => "LISTA DAS MUNICIPIOS",
            "datas" => Municipio::with('provincia')->get()
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-municipios', $headers);
        return $pdf->stream('lista-municipios.pdf');
    }

    public function municipiosExcel()
    {
        return Excel::download(new MunicipioExport, 'municipios.xlsx');
    }
}
