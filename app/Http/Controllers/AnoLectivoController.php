<?php

namespace App\Http\Controllers;

use App\Exports\AnoLectivoExport;
use App\Models\Notificacao;
use App\Models\User;
use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\anolectivo\AnoLectivoUsuario;
use App\Models\web\anolectivo\ControlePeriodico;
use App\Models\web\classes\AnoLectivoClasse;
use App\Models\web\cursos\AnoLectivoCurso;
use App\Models\web\salas\AnoLectivoSala;
use App\Models\web\turnos\AnoLectivoTurno;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class AnoLectivoController extends Controller
{
    use TraitHelpers;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function home()
    {
        $user = auth()->user();

        if (!$user->can('read: ano lectivo')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Lista dos Anos Lectivos",
            "descricao" => env('APP_NAME'),
        ];

        return view('admin.anolectivos.home', $headers);
    }
    
    public function index(Request $request)
    {
        $user = auth()->user();
    
        if (!$user->can('read: ano lectivo')) {
            Alert::error(
                'Acesso restrito',
                'Você não possui permissão para esta operação, por favor, contacte o administrador!'
            );
            return redirect()->back();
        }
    
        $paginacao = $request->paginacao ?? 5;
    
        $query = AnoLectivo::when($request->designacao, function ($query, $value) {
            $query->where(function ($q) use ($value) {
                foreach (['ano', 'serie', 'status', 'inicio', 'final'] as $field) {
                    $q->orWhere($field, 'like', "%{$value}%");
                }
            });
        })
        ->when($request->status, function($query, $value) {
            $query->where('status', $value);
        })->where('shcools_id', $this->escolarLogada());
    
        return response()->json(
            $query->orderByDesc('id')->paginate($paginacao)
        );
    }

    // store do ano Lectivo
    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('create: ano lectivo')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        // Validação
        $request->validate([
            "designacao" => 'required',
            "inicio" => 'required',
            "final" => 'required',
            "status" => 'required',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            // ==========================USUARIO E ESCOLA LOGADAS===========================
            $admin = User::findOrFail(Auth::user()->id);
            $escola = Shcool::findOrFail($admin->shcools_id);

            $ano_in = Carbon::parse($request->input('inicio'))->year; // Isso retorna 2023
            $ano_fi = Carbon::parse($request->input('final'))->year; // Isso retorna 2024
            $in = substr($ano_in, -2); // Isso retorna '23'
            $fi = substr($ano_fi, -2); // Isso retorna '24'

            $serie = "{$in}{$fi}";

            $verificarAno = AnoLectivo::where('ano',  $request->designacao)
                ->where('shcools_id', $escola->id)
            ->first();

            $verificarStatus = AnoLectivo::where('status', 'activo')
                ->where('shcools_id', $escola->id)
            ->first();

            if ($request->status == "activo") {
                if ($verificarStatus) {
                    return response()->json(
                        ['message' => 'Não podem existir dois anos lectivos activo desativa primeiramente o que esta activo em seguida, cadastra um outro!']
                    , 400);
                }
            }
            
            if ($verificarAno) {
                return response()->json(['message' => 'Este ano Já existe!'], 400);
            }

            $contador_ano_lectivo = AnoLectivo::where('shcools_id', $escola->id)->count();

            AnoLectivo::create([
                "ano" => $request->designacao,
                "inicio" => $request->inicio,
                "final" => $request->final,
                "status" => $request->status,
                "serie" => $serie,
                "ordem" => $contador_ano_lectivo + 1,
                "shcools_id" => $escola->id,
            ]);

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => 'Dados salvos com sucesso!'], 200);
    }

    public function edit($id)
    {
        return AnoLectivo::findOrFail($id);
    }

    // actualizar os dados do ano Lectivo
    public function update(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user->can('update: ano lectivo')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            "designacao" => 'required',
            "inicio" => 'required',
            "final" => 'required',
            "status" => 'required',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $ano_in = Carbon::parse($request->input('inicio'))->year; // Isso retorna 2023
            $ano_fi = Carbon::parse($request->input('final'))->year; // Isso retorna 2024
            $in = substr($ano_in, -2); // Isso retorna '23'
            $fi = substr($ano_fi, -2); // Isso retorna '24'

            $serie = "{$in}{$fi}";

            $update = AnoLectivo::find($id);

            $update->ano = $request->designacao;
            $update->serie = $serie;
            $update->inicio = $request->inicio;
            $update->final = $request->final;
            $update->status = $request->status;
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

        return response()->json(['success' => 'Dados salvos com sucesso!'], 200);
    }

    // apresentar o ano Lectivo
    public function show($id)
    {
        $user = auth()->user();

        if (!$user->can('read: ano lectivo')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $ano = AnoLectivo::findOrFail($id);

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        if ($escola->ensino->nome == "Ensino Superior") {
            $trimestres = ControlePeriodico::where('ensino_status', '2')->get();
        } else {
            $trimestres = ControlePeriodico::where('ensino_status', '1')->get();
        }

        $classes = AnoLectivoClasse::where('ano_lectivos_id', $ano->id)->with(['classe'])->get();
        $cursos = AnoLectivoCurso::where('ano_lectivos_id', $ano->id)->with(['curso'])->get();
        $salas = AnoLectivoSala::where('ano_lectivos_id', $ano->id)->with(['sala'])->get();
        $turnos = AnoLectivoTurno::where('ano_lectivos_id', $ano->id)->with(['turno'])->get();

        return response()->json([
            "ano" => $ano,
            "trimestres" => $trimestres,
            "cursos" => $cursos,
            "salas" => $salas,
            "turnos" => $turnos,
            "classes" => $classes,
        ], 200);

    }

    // desactivar ano lectivo em geral so o administrador
    public function actualizarStatus($id)
    {
        $user = auth()->user();

        if (!$user->can('update: estado')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            // esta funcao activa e desactiva o ano lectivo geral 
            $ano = AnoLectivo::findOrFail($id);

            if ($ano) {
                if ($ano->status === 'activo') {
                    $ano->status = 'desactivo';
                } else {
                    $verificar = AnoLectivo::where('status', 'activo')
                        ->where('shcools_id', $this->escolarLogada())
                        ->first();

                    if ($verificar) {
                        return response()->json(['message' => 'Existe um activo desactiva primeiriro, em seguida activa o Outro!'], 400);
                    } else {
                        $ano->status = 'activo';
                    }
                }
                $ano->update();
            }

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
        }

        return response()->json(['success' => 'Dados salvos com sucesso!'], 200);
    }

    // deletar Ano Lectivo
    public function destroy($id)
    {
        
        $user = auth()->user();
        
        if(!$user->can('delete: ano lectivo')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }              
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            AnoLectivo::findOrFail($id)->delete();
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
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
 
    }

    // retificado
    public function export(Request $request)
    {
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "titulo" => "LISTA DOS ANO LECTIVOS",
            "escola" => Shcool::find($this->escolarLogada()),
            "anolectivos" => AnoLectivo::where('shcools_id', $this->escolarLogada())->get()
        ];
        
        $documentType = $request->documentType;
        
        if($documentType === 'excel') {
            return Excel::download(new AnoLectivoExport, 'ano-lectivos.xlsx');
        } else {
            $pdf = \PDF::loadView('downloads.relatorios.lista-ano-lectivos', $headers);
            return $pdf->stream('lista-ano-lectivos-.pdf');
        }
    }

}
