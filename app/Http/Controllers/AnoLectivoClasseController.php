<?php

namespace App\Http\Controllers;

use App\Exports\ClasseAnoLectivoExport;
use App\Models\Shcool;
use App\Models\User;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\classes\AnoLectivoClasse;
use App\Models\web\classes\Classe;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class AnoLectivoClasseController extends Controller
{
    use TraitHelpers;


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function home()
    {
        $user = auth()->user();

        if (!$user->can('read: classe')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $headers = [
            "usuario" => User::findOrFail(Auth::user()->id),
            "ano_lectivo" => AnoLectivo::findOrFail($this->anolectivoActivo()),
            "classes" => Classe::get(),
            "escola" => Shcool::with(['ensino'])->findOrFail($this->escolarLogada()),
        ];

        return view('admin.anolectivos.classes.home', $headers);
    }

    public function index(Request $request)
    {
        $user = auth()->user();
    
        if (!$user->can('read: classe')) {
            Alert::error(
                'Acesso restrito',
                'Você não possui permissão para esta operação, por favor, contacte o administrador!'
            );
            return redirect()->back();
        }
    
        $paginacao = $request->paginacao ?? 5;
        
        $query = AnoLectivoClasse::when($request->designacao_geral, function ($query, $value) {
            $query->where(function ($q) use ($value) {
                // campo da própria tabela
                $q->where('total_vagas', 'like', "%{$value}%")
        
                // campo da tabela classe
                ->orWhereHas('classe', function ($qc) use ($value) {
                    $qc->where('classes', 'like', "%{$value}%");
                })
                // campo da tabela ensino (classe->ensino)
                ->orWhereHas('classe.ensino', function ($qe) use ($value) {
                    $qe->where('nome', 'like', "%{$value}%");
                })
                // campo da tabela ano_lectivo
                ->orWhereHas('ano_lectivo', function ($qa) use ($value) {
                    $qa->where('ano', 'like', "%{$value}%");
                });
            });
        })
        ->when($request->status_data, function($query, $value) {
            $query->where('status', $value);
        })
        ->where('ano_lectivos_id', $this->anolectivoActivo())
        ->where('shcools_id', $this->escolarLogada())
        ->with(['classe.ensino', 'ano_lectivo']);
        
        return response()->json(
            $query->orderByDesc('id')->paginate($paginacao)
        );
    
    }

    public function edit($id)
    {
        return AnoLectivoClasse::findOrFail($id);
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('create: classe')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            "ano_lectivo_id" => 'required',
            "classes_id" => 'required|array',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            foreach ($request->classes_id as $idKey) {
                $verificarClasses = AnoLectivoClasse::where('ano_lectivos_id', $request->ano_lectivo_id)
                    ->where('classes_id', $idKey)
                    ->first();

                if (!$verificarClasses) {
                    AnoLectivoClasse::create([
                        "ano_lectivos_id" => $request->ano_lectivo_id,
                        "classes_id" => $idKey,
                        "total_vagas" => $request->vagas,
                        "shcools_id" => $this->escolarLogada(),
                    ]);
                }
            }

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json([
            'status' => 200,
            'message' => 'Dados salvos com sucesso!',
        ]);
    }

    public function destroy($id)
    {
        $user = auth()->user();

        if (!$user->can('delete: classe')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            AnoLectivoClasse::findOrFail($id)->delete();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json([
            'status' => 200,
            'message' => 'Dados Excluido com sucesso!',
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user->can('update: classe')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $create = AnoLectivoClasse::findOrFail($id);
            
        $request->validate([
            "ano_lectivo_id" => 'required',
            "classes_id" => 'required',
        ]);
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
          
            $create->ano_lectivos_id = $request->ano_lectivo_id;
            $create->classes_id = $request->classes_id[0];
            $create->total_vagas = $request->vagas;
            $create->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json([
            'status' => 200,
            'message' => 'Dados salvos com sucesso!',
        ]);
        
    }

    public function export(Request $request)
    {
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);
        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "titulo" => "LISTAGEM DAS CLASSES",
            "classes" => AnoLectivoClasse::where('shcools_id', $this->escolarLogada())
                ->where('ano_lectivos_id', $this->anolectivoActivo())
                ->with(['classe'])
                ->get(),
        ];

        $documentType = $request->documentType;
        
        if($documentType === 'excel') {
            return Excel::download(new ClasseAnoLectivoExport, 'classes-ano-lectivo.xlsx');
        } else {
            $pdf = \PDF::loadView('downloads.relatorios.lista-classes-ano-lectivo', $headers);
            return $pdf->stream('lista-classes-ano-lectivo.pdf');
        }

    }
}
