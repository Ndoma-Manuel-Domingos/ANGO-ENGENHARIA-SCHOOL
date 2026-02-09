<?php

namespace App\Http\Controllers;

use App\Exports\MunicipioExport;
use App\Models\DireccaoProvincia;
use App\Models\Municipio;
use App\Models\Provincia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class MunicipioPortalProvinciaController extends Controller
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
        
        
        $direccao = DireccaoProvincia::findOrFail($user->shcools_id);
        $municipios = Municipio::where('provincia_id', $direccao->provincia_id)->get();
    
        $headers = [
            "titulo" => "Lista de Municipios",
            "descricao" => env('APP_NAME'),
            "provincias" => Provincia::whereIn('id', [$direccao->provincia_id])->get(), 
            "datas" => $municipios, 
            "usuario" => User::findOrFail(Auth::user()->id),
            
        ];

        return view('sistema.direccao-provincial.munuicipios.home', $headers);
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
            "provincia_id" => 'required',
            "status" => 'required',
            "municipio" => 'required',
        ], [
            "provincia_id.required" => "Campo Obrigatório",
            "status.required" => "Campo Obrigatório",
            "municipio.required" => "Campo Obrigatório",
        ]);


        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        }else{
    
            $verificar = Municipio::where([
                ['nome', $request->input('municipio')],
            ])->first();
    
            if($verificar){
                return response()->json([
                    'status' => 300,
                    'message' => "Este Província já Esta Cadastrado!",
                ]);
            }
            
            Municipio::create([
                'nome' => $request->input('municipio'),
                'status' => $request->input('status'),
                'provincia_id' => $request->input('provincia_id'),
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
        
        $data = Municipio::with('provincia')->findOrFail($id);

        if ($data) {
            return response()->json([
                "status" => 200,
                "data" => $data
            ]);
        }else{
            return response()->json([
                "status" => 404,
                "message" => 'Municpio não Encontrado',
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
            "provincia_id" => 'required',
            "status" => 'required',
            "municipio" => 'required',
        ], [
            "provincia_id.required" => "Campo Obrigatório",
            "status.required" => "Campo Obrigatório",
            "municipio.required" => "Campo Obrigatório",
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        }else{
            $update = Municipio::findOrFail($id);

            if ($update) {
                $update->nome = $request->input('municipio');
                $update->provincia_id = $request->input('provincia_id');
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
                    "message" => 'municipio não Encontrado'
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
        
        $data = Municipio::findOrFail($id);
        $data->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Dados Excluido com sucesso!',
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }
    
    
    // activar e desactivar municipio
    public function activarMunicipios($id)
    {
        $user = auth()->user();
        
        if(!$user->can('update: estado')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        
        $data = Municipio::findOrFail($id);
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
