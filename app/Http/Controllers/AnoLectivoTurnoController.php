<?php

namespace App\Http\Controllers;


use App\Exports\TurnoAnoLectivoExport;
use App\Models\Shcool;
use App\Models\User;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\turnos\AnoLectivoTurno;
use App\Models\web\turnos\Turno;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class AnoLectivoTurnoController extends Controller
{
    use TraitHelpers;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function home()
    {
        $user = auth()->user();

        if (!$user->can('read: turno')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "usuario" => User::findOrFail(Auth::user()->id),
            "ano_lectivo" => AnoLectivo::findOrFail($this->anolectivoActivo()),
            "turnos" => Turno::get(),
        ];

        return view('admin.anolectivos.turnos.home', $headers);
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
        
        $query = AnoLectivoTurno::when($request->designacao_geral, function ($query, $value) {
            $query->where(function ($q) use ($value) {
                $q->where('total_vagas', 'like', "%{$value}%")
                ->orWhereHas('turno', function ($qc) use ($value) {
                    $qc->where('turno', 'like', "%{$value}%");
                })
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
        ->with(['turno', 'ano_lectivo']);
        
        return response()->json(
            $query->orderByDesc('id')->paginate($paginacao)
        );
    
    }
    
    public function edit($id)
    {
        return AnoLectivoTurno::findOrFail($id);
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('create: turno')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            "ano_lectivo_id" => 'required',
            "turnos_id" => 'required|array',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            foreach ($request->turnos_id as $key) {

                $verificarClasses = AnoLectivoTurno::where('ano_lectivos_id', $request->ano_lectivo_id)
                    ->where('turnos_id', $key)
                    ->first();

                if (!$verificarClasses) {
                    AnoLectivoTurno::create([
                        "ano_lectivos_id" => $request->ano_lectivo_id,
                        "turnos_id" => $key,
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

    public function update(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user->can('update: turno')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            "ano_lectivo_id" => 'required',
            "turnos_id" => 'required',
        ]);
               
        $update = AnoLectivoTurno::findOrFail($id); 
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
          
            $update->ano_lectivos_id = $request->ano_lectivo_id;
            $update->turnos_id = $request->turnos_id[0];
            $update->total_vagas = $request->vagas;
            $update->update();
            
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

        if (!$user->can('delete: turno')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            AnoLectivoTurno::findOrFail($id)->delete();
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

    public function export(Request $request)
    {
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "titulo" => "LISTAGEM DOS TURNOS",
            "turnos" => AnoLectivoTurno::where('shcools_id', $this->escolarLogada())
                ->where('ano_lectivos_id', $this->anolectivoActivo())
                ->with(['turno'])
            ->get(),
        ];
        
        $documentType = $request->documentType;
        
        if($documentType === 'excel') {
            return Excel::download(new TurnoAnoLectivoExport, 'classes-ano-lectivo.xlsx');
        } else {
            $pdf = \PDF::loadView('downloads.relatorios.lista-turno-ano-lectivo', $headers);
            return $pdf->stream('lista-turno-ano-lectivo.pdf');
        }
    }

}
