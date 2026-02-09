<?php

namespace App\Http\Controllers;

use App\Exports\FaculdadeExport;
use App\Models\User;
use App\Models\Shcool;
use App\Models\web\disciplinas\Candidatura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class CandidaturaAcademicaController extends Controller
{
    use TraitHelpers;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function candidaturas()
    {
        $user = auth()->user();

        if (!$user->can('read: candidatura')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $headers = [
            "titulo" => "Listagem de todas as Candidaturas",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "candidaturas" => Candidatura::get(),
        ];

        return view('sistema.candidaturas.home', $headers);
    }

    // cadastrar cursos
    public function cadastrarcandidaturas(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('create: candidatura')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $validate = Validator::make($request->all(), [
            "nome_candidaturas" => 'required',
            "status_candidaturas" => 'required',
        ], [
            "nome_candidaturas.required" => "Campo Obrigatório",
            "status_candidaturas.required" => "Campo Obrigatório",
        ]);

        $verificar = Candidatura::where([
            ['nome', '=', $request->input('nome_candidaturas')],
        ])->first();

        if ($verificar) {
            return response()->json([
                'status' => 300,
                'message' => "Este Candidatura já Esta Cadastrado!",
            ]);
        }

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        } else {
            $create = new Candidatura();
            $create->nome = $request->input('nome_candidaturas');
            $create->status = $request->input('status_candidaturas');
            $create->descricao = $request->input('descricao_candidaturas');
            $create->save();

            return response()->json([
                'status' => 200,
                'message' => 'Dados salvos com sucesso!',
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        }
    }

    // editar cursos
    public function editarcandidaturas($id)
    {
        $user = auth()->user();

        if (!$user->can('update: candidatura')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $candidatura = Candidatura::findOrFail($id);

        if ($candidatura) {
            return response()->json([
                "status" => 200,
                "candidatura" => $candidatura,
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        } else {
            return response()->json([
                "status" => 404,
                "message" => 'Candidatura não Encontrada'
            ]);
        }
    }

    // actualizar cursos
    public function updatecandidaturas(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user->can('update: candidatura')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $validate = Validator::make($request->all(), [
            "nome_candidaturas" => 'required',
            "status_candidaturas" => 'required',
        ], [
            "nome_candidaturas.required" => "Campo Obrigatório",
            "status_candidaturas.required" => "Campo Obrigatório",
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        } else {
            $update = Candidatura::findOrFail($id);

            if ($update) {
                $update->nome = $request->input('nome_candidaturas');
                $update->status = $request->input('status_candidaturas');
                $update->descricao = $request->input('descricao_candidaturas');
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
    public function deletecandidaturas($id)
    {
        $user = auth()->user();

        if (!$user->can('delete: candidatura')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $candidatura = Candidatura::findOrFail($id);
        $candidatura->delete();

        return response()->json([
            'status' => 200,
            "usuario" => User::findOrFail(Auth::user()->id),
            'message' => 'Dados Excluido com sucesso!',
        ]);
    }

    public function candidaturasImprimir()
    {

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "titulo" => "LISTA DAS CANDIDATURAS",
            "candidaturas" => Candidatura::get()
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-faculdades', $headers);
        return $pdf->stream('lista-faculdades.pdf');
    }

    public function ExcelImprimir()
    {
        return Excel::download(new FaculdadeExport, 'faculdades.xlsx');
    }
}
