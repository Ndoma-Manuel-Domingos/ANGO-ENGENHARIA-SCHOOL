<?php

namespace App\Http\Controllers;

use App\Exports\TurnoExport;
use App\Models\User;
use App\Models\web\turnos\Turno;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class TurnoController extends Controller
{
    //
    use TraitHelpers;

    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    // --------------------------------------------------------------------------------------
    // ----------------------------------START TURNOS ---------------------------------------------------
    // --------------------------------------------------------------------------------------
    // --------------------------------------------------------------------------------------

    // view turno principal
    public function turnos()
    {
    
        $user = auth()->user();
        
        if(!$user->can('read: turno')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
    
        $headers = [
            "titulo" => "Lista de Turnos",
            "descricao" => env('APP_NAME'),
            "listarTurnos" => Turno::get(), 
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('sistema.turnos.home', $headers);
    }

    // cadastrar turnos
    public function cadastrarTurnos(Request $request)
    {
    
        $user = auth()->user();
        
        if(!$user->can('create: turno')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        
        $validate = Validator::make($request->all(), [
            "nome_turnos" => 'required',
            "horario_turnos" => 'required',
            "status_turnos" => 'required',
        ], [
            "nome_turnos.required" => "Campo Obrigatório",
            "horario_turnos.required" => "Campo Obrigatório",
            "status_turnos.required" => "Campo Obrigatório",
        ]);

        $verificarTurno = Turno::where([
            ['turno', $request->input('nome_turnos')],
        ])->first();

        if($verificarTurno){
            return response()->json([
                'status' => 300,
                'message' => "Este Turno já Esta Cadastrado!",
            ]);
        }

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        }else{
            $create = new Turno();
            $create->turno = $request->input('nome_turnos');
            $create->status = $request->input('status_turnos');
            $create->horario = $request->input('horario_turnos');
            $create->descricao = $request->input('descricao_turnos');
            $create->save();

            return response()->json([
                'status' => 200,
                'message' => 'Dados salvos com sucesso!',
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        }
    }

    // editar Turnos
    public function editarTurnos($id)
    {
        $user = auth()->user();
        
        if(!$user->can('update: turno')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $turnoId = Turno::find($id);

        if ($turnoId) {
            return response()->json([
                "status" => 200,
                "turnos" => $turnoId
            ]);
        }else{
            return response()->json([
                "status" => 404,
                "message" => 'Turno não Encontrado',
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        }

    }

    // actualizar turnos
    public function updateTurnos(Request $request, $id)
    {
        $user = auth()->user();
        
        if(!$user->can('update: turno')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $validate = Validator::make($request->all(), [
            "nome_turnos" => 'required',
            "horario_turnos" => 'required',
            "status_turnos" => 'required',
        ], [
            "nome_turnos.required" => "Campo Obrigatório",
            "horario_turnos.required" => "Campo Obrigatório",
            "status_turnos.required" => "Campo Obrigatório",
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        }else{
            $update = Turno::findOrFail($id);

            if ($update) {
                $update->turno = $request->input('nome_turnos');
                $update->status = $request->input('status_turnos');
                $update->horario = $request->input('horario_turnos');
                $update->descricao = $request->input('descricao_turnos');
                $update->update();

                return response()->json([
                    'status' => 200,
                    'message' => 'Dados ACtualizados com sucesso!',
                    "usuario" => User::findOrFail(Auth::user()->id),
                ]);
            }else{
                return response()->json([
                    "status" => 404,
                    "message" => 'Turno não Encontrado'
                ]);
            }

        }
    }

    // delete turno
    public function deleteTurnos($id)
    {
        $user = auth()->user();
        
        if(!$user->can('delete: turno')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $ano = Turno::findOrFail($id);
        $ano->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Dados Excluido com sucesso!',
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }

    // activar e desactivar turno
    public function activarTurnos($id)
    {
        $user = auth()->user();
        
        if(!$user->can('update: estado')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        
        $listarTurno = Turno::findOrFail($id);
        if ($listarTurno) {
            if ($listarTurno->status === 'activo') {
                $listarTurno->status = 'desactivo';
            }else{
                $listarTurno->status = 'activo';
            }
            if ($listarTurno->update()) {
                return response()->json([
                    "status" => 200,
                    "message" => "Dodos Activados com sucesso",
                    "usuario" => User::findOrFail(Auth::user()->id),
                ]);
            }
        }
    }


    public function turnosImprimir()
    {
        $headers = [
            "titulo" => "LISTA DOS TURNOS",
            "turnos" => Turno::get()
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-turnos', $headers);
        return $pdf->stream('lista-turnos.pdf');
    }

    public function turnosExcel()
    {
        return Excel::download(new TurnoExport, 'turnos.xlsx');
    }
    

}
