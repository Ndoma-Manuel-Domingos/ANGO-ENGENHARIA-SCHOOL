<?php

namespace App\Http\Controllers;

use App\Exports\AnoLectivoExport;
use App\Models\AnoLectivoGlobal;
use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;


class AnoLectivoGlobalController extends Controller
{
    use TraitHelpers;


    public function __construct()
    {
        $this->middleware('auth');
    }


    // --------------------------------------------------------------------------------------
    // ----------------------------------START ANO LECTIVO----------------------------------------------------
    // --------------------------------------------------------------------------------------
    // --------------------------------------------------------------------------------------

    // ano lectivo
    public function anoLectivo()
    {

        $user = auth()->user();

        if (!$user->can('read: ano lectivo')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $headers = [
            "titulo" => "Lista dos Anos Lectivos",
            "descricao" => env('APP_NAME'),
            "listAnos" => AnoLectivoGlobal::get(),
        ];

        return view('sistema.anolectivos.home', $headers);
    }

    public function create()
    {

        $user = auth()->user();

        if (!$user->can('create: ano lectivo')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $headers = [
            "titulo" => "Cadastrar Ano Lectivo",
            "descricao" => env('APP_NAME'),
        ];

        return view('sistema.anolectivos.create', $headers);
    }

    // store do ano Lectivo
    public function store(Request $request)
    {

        $user = auth()->user();

        if (!$user->can('create: ano lectivo')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            "ano_lectivo" => 'required',
            "data_inicio" => 'required',
            "data_final" => 'required',
            "status_ano" => 'required',
        ], [
            "ano_lectivo.required" => "Campo Obrigatório",
            "data_inicio.required" => "Campo Obrigatório",
            "data_final.required" => "Campo Obrigatório",
            "status_ano.required" => "Campo Obrigatório",
        ]);

        $verificarAno = AnoLectivoGlobal::where([
            ['ano', '=', $request->input('ano_lectivo')],
        ])->first();


        $verificarStatus = AnoLectivoGlobal::where([
            ['status', '=', 'activo'],
        ])->first();

        // dd($verificarStatus);

        if ($request->input('status_ano') == "activo") {
            if ($verificarStatus) {
                Alert::warning("Atenção", "Não podem existir dois anos lectivos activo desativa primeiramente o que esta activo em seguida, cadastra um outro ! ");
                return redirect()->route('web.create-ano-lectivo-global');
            }
        }

        if ($verificarAno) {
            Alert::warning("Atenção", "Este ano Já existe ! ");
            return redirect()->route('web.create-ano-lectivo-global');
        }

        AnoLectivoGlobal::create([
            "ano" => $request->input('ano_lectivo'),
            "inicio" => $request->input('data_inicio'),
            "final" => $request->input('data_final'),
            "status" => $request->input('status_ano'),
        ]);

        Alert::success("Bom Trabalho", "Dados salvos com sucesso");
        return redirect()->route('ano-lectivo-global');
    }

    // editar ano Lectivo view
    public function editarAnoLectivo($id)
    {
        $user = auth()->user();

        if (!$user->can('update: ano lectivo')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $listAno = AnoLectivo::findOrFail($id);

        $headers = [
            "titulo" => "Editar Ano Lectivo",
            "descricao" => env('APP_NAME'),
            "anoLectivo" => $listAno,
        ];

        return view('sistema.anolectivos.edit', $headers);
    }

    // actualizar os dados do ano Lectivo
    public function updateAnoLectivo(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user->can('update: ano lectivo')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            "ano_lectivo" => 'required',
            "data_inicio" => 'required',
            "data_final" => 'required',
            "status_ano" => 'required',
        ], [
            "ano_lectivo.required" => "Campo Obrigatório",
            "data_inicio.required" => "Campo Obrigatório",
            "data_final.required" => "Campo Obrigatório",
            "status_ano.required" => "Campo Obrigatório",
        ]);

        $update = AnoLectivoGlobal::find($id);
        $update->ano = $request->input('ano_lectivo');
        $update->inicio = $request->input('data_inicio');
        $update->final = $request->input('data_final');
        $update->status = $request->input('status_ano');
        $update->update();

        Alert::success("Bom trabalho", "Dados Actualizados com successo");
        return redirect()->route('ano-lectivo-global');
    }

    // activar e dasativar o ano lectivo
    public function activarAnoLectivo($id)
    {

        $user = auth()->user();

        if (!$user->can('update: estado')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $ano = AnoLectivoGlobal::findOrFail($id);

        if ($ano->status == "activo") {
            $status = "desactivo";
        } else if ($ano->status == "desactivo") {
            $status = "activo";
        }

        $ano->status = $status;
        $ano->update();

        Alert::success("Bom trabalho", "Ano Lectivo activo so para o Senhor(a) mais os outros continuam a usar o ano lectivo padrão");
        return redirect()->back();
    }


    public function anoLectivoImprimir()
    {
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);
        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "titulo" => "LISTA DOS ANO LECTIVOS",
            "anolectivos" => AnoLectivoGlobal::get()
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-ano-lectivos-global', $headers);
        return $pdf->stream('lista-ano-lectivos-global.pdf');
    }

    public function anoLectivoExcel()
    {
        return Excel::download(new AnoLectivoExport, 'ano-lectivos-global.xlsx');
    }

}
