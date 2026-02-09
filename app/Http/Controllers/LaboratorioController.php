<?php

namespace App\Http\Controllers;

use App\Exports\LaboratorioEscolaExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaboratorioExport;
use App\Models\Laboratorio;
use App\Models\LaboratorioEscola;
use App\Models\Shcool;
use App\Models\User;
use Illuminate\Support\Facades\File;

class LaboratorioController extends Controller
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

        if (!$user->can('read: laboratorio')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $headers = [
            "titulo" => "Lista de laboratórios",
            "descricao" => env('APP_NAME'),
            "datas" => Laboratorio::get(),
            "usuario" => User::findOrFail(Auth::user()->id),
            "loyout" => $request->loyout,
        ];

        return view('sistema.laboratorios.home', $headers);
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

        if (!$user->can('create: laboratorio')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $validate = Validator::make($request->all(), [
            "laboratorio" => 'required',
            "status" => 'required',
        ], [
            "laboratorio.required" => "Campo Obrigatório",
            "status.required" => "Campo Obrigatório",
        ]);


        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        } else {

            $verificar = Laboratorio::where([
                ['nome', $request->input('laboratorio')],
            ])->first();

            if ($verificar) {
                return response()->json([
                    'status' => 300,
                    'message' => "Este laboratorio já Esta Cadastrado!",
                ]);
            }

            Laboratorio::create([
                'nome' => $request->input('laboratorio'),
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

        if (!$user->can('update: laboratorio')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $data = Laboratorio::findOrFail($id);

        if ($data) {
            return response()->json([
                "status" => 200,
                "data" => $data
            ]);
        } else {
            return response()->json([
                "status" => 404,
                "message" => 'laboratorio não Encontrado',
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

        if (!$user->can('update: laboratorio')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $validate = Validator::make($request->all(), [
            "laboratorio" => 'required',
            "status" => 'required',
        ], [
            "laboratorio.required" => "Campo Obrigatório",
            "status.required" => "Campo Obrigatório",
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        } else {
            $update = Laboratorio::findOrFail($id);

            if ($update) {
                $update->nome = $request->input('laboratorio');
                $update->status = $request->input('status');
                $update->update();

                return response()->json([
                    'status' => 200,
                    'message' => 'Dados Actualizados com sucesso!',
                    "usuario" => User::findOrFail(Auth::user()->id),
                ]);
            } else {
                return response()->json([
                    "status" => 404,
                    "message" => 'laboratorio não Encontrado'
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

        if (!$user->can('delete: laboratorio')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $data = Laboratorio::findOrFail($id);
        $data->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Dados Excluido com sucesso!',
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }


    // activar e desactivar provincia
    public function activarlaboratorios($id)
    {
        $user = auth()->user();

        if (!$user->can('update: laboratorio')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $data = Laboratorio::findOrFail($id);
        if ($data) {
            if ($data->status === 'activo') {
                $data->status = 'desactivo';
            } else {
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

    public function laboratoriosImprimir()
    {
        $headers = [
            "titulo" => "LISTA DOS LABORATÓRIOS",
            "datas" => Laboratorio::get()
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-laboratorios', $headers);
        return $pdf->stream('lista-laboratorios.pdf');
    }

    public function laboratoriosExcel()
    {
        return Excel::download(new LaboratorioExport, 'laboratorios.xlsx');
    }


    /*** LABORATORIOS ESCOLAS */
    public function laboratorioIndex(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: laboratorio')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($request->shcool_id),
            
            'loyout' =>  $request->loyout,
            "usuario" => User::findOrFail(Auth::user()->id),
            "lista_laboratorios" => Laboratorio::get(),
            "laboratorios" => LaboratorioEscola::with(['laboratorio', 'escola'])->where('shcools_id', '=', $this->escolarLogada())->get(),
        ];

        return view('admin.laboratorio-escolas.home', $headers);
    }

    // deletar classes Ano Lectivo
    public function deletelaboratorio($id)
    {
        $user = auth()->user();

        if (!$user->can('delete: laboratorio')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $classe = LaboratorioEscola::find($id);
        $classe->delete();


        Alert::success("Bom Trabalho", "Dados Eliminados com sucesso");
        return redirect()->back();
    }

    public function createlaboratorio(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('create: laboratorio')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            "disciplina_id" => 'required',
        ], [
            "disciplina_id.required" => "Campo Obrigatório",
        ]);

        $contarExistenciasClasseAnoLectivo = 0;

        foreach ($request->input('disciplina_id') as $idKey) {

            $verificarClasses = LaboratorioEscola::where([
                ['shcools_id', $this->escolarLogada()],
                ['laboratorio_id', $idKey],
            ])->first();

            if ($verificarClasses) {
                $contarExistenciasClasseAnoLectivo++;
            } else {
                $create = new LaboratorioEscola();
                $create->laboratorio_id = $idKey;
                $create->shcools_id = $this->escolarLogada();
                $create->save();
            }
        }

        Alert::success("Atenção", "Dados salvos com sucesso! ");
        return redirect()->back();
    }

    public function laboratorioPDF(Request $request)
    {

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "titulo" => "LISTAGEM DOS LABORATÓRIOS DAS ESCOLAS",
            "laboratorios" => LaboratorioEscola::where('shcools_id', '=', $request->shcool_id)
                ->with(['laboratorio', 'escola'])
                ->get(),
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-laboratorio-escola', $headers);
        return $pdf->stream('lista-laboratorio-escola.pdf');
    }

    public function laboratorioExcel(Request $request)
    {
        return Excel::download(new LaboratorioEscolaExport($request), 'laboratorios-escolas.xlsx');
    }
}
