<?php

namespace App\Http\Controllers;

use App\Exports\DisciplinaExport;
use App\Models\User;
use App\Models\Shcool;
use App\Models\web\disciplinas\Disciplina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class DisciplinaController extends Controller
{
    use TraitHelpers;


    public function __construct()
    {
        $this->middleware('auth');
    }


    //
    // --------------------------------------------------------------------------------------
    // ----------------------------------START DISCIPLINA ---------------------------------------------------
    // --------------------------------------------------------------------------------------
    // --------------------------------------------------------------------------------------

    // view cursos principal
    public function disciplinas()
    {
        $user = auth()->user();

        if (!$user->can('read: disciplina')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $headers = [
            "titulo" => "Listagem de todas as disciplinas",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "listarDisciplinas" => Disciplina::get(),
        ];

        return view('sistema.disciplinas.home', $headers);
    }

    // cadastrar cursos
    public function cadastrarDisciplinas(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('create: disciplina')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $validate = Validator::make($request->all(), [
            "nome_disciplinas" => 'required',
            "abreviacao_disciplinas" => 'required',
            "code_disciplinas" => 'required',
        ], [
            "nome_disciplinas.required" => "Campo Obrigatório",
            "abreviacao_disciplinas.required" => "Campo Obrigatório",
            "code_disciplinas.required" => "Campo Obrigatório",
        ]);

        $verificarDisciplina = Disciplina::where([
            ['disciplina', '=', $request->input('nome_disciplinas')],
        ])->first();

        if ($verificarDisciplina) {
            return response()->json([
                'status' => 300,
                'message' => "Este Curso já Esta Cadastrado!",
            ]);
        }

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        } else {
            $create = new Disciplina();
            $create->disciplina = $request->input('nome_disciplinas');
            $create->abreviacao = $request->input('abreviacao_disciplinas');
            $create->code = $request->input('code_disciplinas');
            $create->descricao = $request->input('descricao_disciplinas');
            $create->save();

            return response()->json([
                'status' => 200,
                'message' => 'Dados salvos com sucesso!',
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        }
    }

    // editar cursos
    public function editarDisciplinas($id)
    {
        $user = auth()->user();

        if (!$user->can('update: disciplina')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $disciplinaId = Disciplina::findOrFail($id);

        if ($disciplinaId) {
            return response()->json([
                "status" => 200,
                "disciplinas" => $disciplinaId,
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        } else {
            return response()->json([
                "status" => 404,
                "message" => 'Turno não Encontrado'
            ]);
        }
    }

    // actualizar cursos
    public function updateDisciplinas(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user->can('update: disciplina')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $validate = Validator::make($request->all(), [
            "nome_disciplinas" => 'required',
            "abreviacao_disciplinas" => 'required',
            "code_disciplinas" => 'required',
        ], [
            "nome_disciplinas.required" => "Campo Obrigatório",
            "abreviacao_disciplinas.required" => "Campo Obrigatório",
            "code_disciplinas.required" => "Campo Obrigatório",
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        } else {
            $update = Disciplina::findOrFail($id);

            if ($update) {
                $update->disciplina = $request->input('nome_disciplinas');
                $update->abreviacao = $request->input('abreviacao_disciplinas');
                $update->code = $request->input('code_disciplinas');
                $update->descricao = $request->input('descricao_disciplinas');
                $update->update();

                return response()->json([
                    'status' => 200,
                    'message' => 'Dados ACtualizados com sucesso!',
                    "usuario" => User::findOrFail(Auth::user()->id),
                ]);
            } else {
                return response()->json([
                    "status" => 404,
                    "message" => 'Turno não Encontrado'
                ]);
            }
        }
    }

    // delete cursos
    public function deleteDisciplinas($id)
    {
        $user = auth()->user();

        if (!$user->can('delete: disciplina')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }


        $disciplina = Disciplina::findOrFail($id);
        $disciplina->delete();

        return response()->json([
            'status' => 200,
            "usuario" => User::findOrFail(Auth::user()->id),
            'message' => 'Dados Excluido com sucesso!',
        ]);
    }

    public function disciplinasImprimir()
    {
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "titulo" => "LISTA DAS DISCIPLINAS",
            "disciplinas" => Disciplina::get()
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-disciplinas', $headers);
        return $pdf->stream('lista-disciplinas.pdf');
    }

    public function ExcelImprimir()
    {
        return Excel::download(new DisciplinaExport, 'disciplinas.xlsx');
    }
}
