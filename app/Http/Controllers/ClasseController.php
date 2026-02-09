<?php

namespace App\Http\Controllers;

use App\Exports\ClasseExport;
use App\Models\Ensino;
use App\Models\EnsinoClasse;
use App\Models\User;
use App\Models\web\classes\Classe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class ClasseController extends Controller
{
    use TraitHelpers;
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    // --------------------------------------------------------------------------------------
    // ----------------------------------START CLASSES ---------------------------------------------------
    // --------------------------------------------------------------------------------------
    // --------------------------------------------------------------------------------------

    // view classes principal
    public function classes()
    {
        $user = auth()->user();
        
        if(!$user->can('read: classe')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
    
        $headers = [ 
            "titulo" => "Lista das Classes",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "listarClasses" => Classe::get(),
            "ensinos" => EnsinoClasse::get(),
        ];
        
        return view('sistema.classes.home', $headers);
    }

    // cadastrar classes
    public function cadastrarClasses(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('create: classe')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $validate = Validator::make($request->all(), [
            "nome_classes" => 'required',
            "status_classes" => 'required',
            "tipo_classes" => 'required',
            "ensino_id" => 'required',
        ], [
            "nome_classes.required" => "Campo Obrigatório",
            "status_classes.required" => "Campo Obrigatório",
            "tipo_classes.required" => "Campo Obrigatório",
            "ensino_id.required" => "Campo Obrigatório",
        ]);

        $verificarClasse = Classe::where([
           ['classes', $request->input('nome_classes')],
        ])->first();

        if($verificarClasse){
            return response()->json([
                'status' => 300,
                'message' => "Este Classe já Esta Cadastrado!",
            ]);
        }

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        }else{
            $create = new Classe();
            $create->classes = $request->input('nome_classes');
            $create->status = $request->input('status_classes');
            $create->tipo = $request->input('tipo_classes');
            $create->tipo_avaliacao_nota = $request->input('tipo_avaliacao_nota');
            $create->categoria = $request->input('categoria_classes');
            $create->ensino_id = $request->input('ensino_id');
            $create->save();

            return response()->json([
                'status' => 200,
                'message' => 'Dados salvos com sucesso!',
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        }
    }

    // editar classes
    public function editarClasses($id)
    {
        $user = auth()->user();
        
        if(!$user->can('update: classe')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $classesId = Classe::findOrFail($id);

        if ($classesId) {
            return response()->json([
                "status" => 200,
                "classes" => $classesId
            ]);
        }else{
            return response()->json([
                "status" => 404,
                "message" => 'Classe não Encontrado',
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        }

    }

    // actualizar classes
    public function updateClasses(Request $request, $id)
    {
    
        $user = auth()->user();
        
        if(!$user->can('update: classe')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $validate = Validator::make($request->all(), [
            "nome_classes" => 'required',
            "status_classes" => 'required',
            "tipo_classes" => 'required',
            "ensino_id" => 'required',
        ], [
            "nome_classes.required" => "Campo Obrigatório",
            "status_classes.required" => "Campo Obrigatório",
            "tipo_classes.required" => "Campo Obrigatório",
            "ensino_id.required" => "Campo Obrigatório",
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        }else{
            $update = Classe::findOrFail($id);

            if ($update) {
                $update->classes = $request->input('nome_classes');
                $update->status = $request->input('status_classes');
                $update->tipo = $request->input('tipo_classes');
                $update->tipo_avaliacao_nota = $request->input('tipo_avaliacao_nota');
                $update->categoria = $request->input('categoria_classes');
                $update->ensino_id = $request->input('ensino_id');
                $update->update();

                return response()->json([
                    'status' => 200,
                    'message' => 'Dados Actualizados com sucesso!',
                    "usuario" => User::findOrFail(Auth::user()->id),
                ]);
            }else{
                return response()->json([
                    "status" => 404,
                    "message" => 'Classe não Encontrado'
                ]);
            }

        }
    }

    // delete classes
    public function deleteClasses($id)
    {
        $user = auth()->user();
        
        if(!$user->can('delete: classe')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $ano = Classe::findOrFail($id);
        $ano->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Dados Excluido com sucesso!',
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }

    // activar e desactivar classes
    public function activarClasses($id)
    {
        $user = auth()->user();
        
        if(!$user->can('update: estado')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $listarClasses = Classe::findOrFail($id);
        if ($listarClasses) {
            if ($listarClasses->status === 'activo') {
                $listarClasses->status = 'desactivo';
            }else{
                $listarClasses->status = 'activo';
            }
            if ($listarClasses->update()) {
                return response()->json([
                    "status" => 200,
                    "message" => "Dodos Activados com sucesso",
                    "usuario" => User::findOrFail(Auth::user()->id),
                ]);
            }
        }
    }

    
    public function classesImprimir()
    {
        $headers = [
            "titulo" => "LISTA DAS CLASSES",
            "classes" => Classe::get()
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-classes', $headers);
        return $pdf->stream('lista-classes.pdf');
    }

    public function classesExcel()
    {
        return Excel::download(new ClasseExport, 'classes.xlsx');
    }


}
