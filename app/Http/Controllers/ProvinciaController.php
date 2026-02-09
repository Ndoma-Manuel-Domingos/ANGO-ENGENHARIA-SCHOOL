<?php

namespace App\Http\Controllers;

use App\Exports\ProvinciaExport;
use App\Models\Provincia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ProvinciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        
        if(!$user->can('read: provincia')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
    
        $headers = [
            "titulo" => "Lista de Províncias",
            "descricao" => env('APP_NAME'),
            "datas" => Provincia::with('municipios')->get(), 
            "usuario" => User::findOrFail(Auth::user()->id),
            
        ];

        return view('sistema.provincias.home', $headers);
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
        
        if(!$user->can('create: provincia')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $validate = Validator::make($request->all(), [
            "provincia" => 'required',
            "status" => 'required',
            "abreviacao" => 'required',
            "capital" => 'required',
        ], [
            "provincia.required" => "Campo Obrigatório",
            "status.required" => "Campo Obrigatório",
            "capital.required" => "Campo Obrigatório",
            "abreviacao.required" => "Campo Obrigatório",
        ]);


        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        }else{
    
            $verificar = Provincia::where([
                ['nome', $request->input('provincia')],
            ])->first();
    
            if($verificar){
                return response()->json([
                    'status' => 300,
                    'message' => "Este Província já Esta Cadastrado!",
                ]);
            }
            
            Provincia::create([
                'nome' => $request->input('provincia'),
                'status' => $request->input('status'),
                'abreviacao' => $request->input('abreviacao'),
                'capital' => $request->input('capital'),
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
        
        if(!$user->can('update: provincia')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $data = Provincia::find($id);

        if ($data) {
            return response()->json([
                "status" => 200,
                "data" => $data
            ]);
        }else{
            return response()->json([
                "status" => 404,
                "message" => 'Província não Encontrado',
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
        
        if(!$user->can('update: provincia')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $validate = Validator::make($request->all(), [
            "provincia" => 'required',
            "status" => 'required',
            "abreviacao" => 'required',
            "capital" => 'required',
        ], [
            "provincia.required" => "Campo Obrigatório",
            "status.required" => "Campo Obrigatório",
            "capital.required" => "Campo Obrigatório",
            "abreviacao.required" => "Campo Obrigatório",
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        }else{
            $update = Provincia::findOrFail($id);

            if ($update) {
                $update->nome = $request->input('provincia');
                $update->abreviacao = $request->input('abreviacao');
                $update->capital = $request->input('capital');
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
                    "message" => 'provincia não Encontrado'
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
        
        if(!$user->can('delete: provincia')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $data = Provincia::findOrFail($id);
        $data->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Dados Excluido com sucesso!',
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }
    
    
    // activar e desactivar provincia
    public function activarProvincias($id)
    {
        $user = auth()->user();
        
        if(!$user->can('update: estado')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        
        $data = Provincia::findOrFail($id);
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


    public function ProvinciasImprimir()
    {
        $headers = [
            "titulo" => "LISTA DAS PROVÍNCIAS",
            "datas" => Provincia::get()
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-provincias', $headers);
        return $pdf->stream('lista-provincias.pdf');
    }

    public function ProvinciasExcel()
    {
        return Excel::download(new ProvinciaExport, 'provincias.xlsx');
    }
}
