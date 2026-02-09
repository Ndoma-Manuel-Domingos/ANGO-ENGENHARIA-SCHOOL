<?php

namespace App\Http\Controllers;

use App\Exports\CursoAnoLectivoExport;
use App\Models\CategoriaDisciplina;
use App\Models\Shcool;
use App\Models\User;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\cursos\AnoLectivoCurso;
use App\Models\web\cursos\Curso;
use App\Models\web\disciplinas\CandidaturaAnoLectivo;
use App\Models\web\disciplinas\DisciplinaAnoLectivo;
use App\Models\web\disciplinas\FaculdadeAnoLectivo;
use App\Models\web\funcionarios\Funcionarios;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class AnoLectivoCursoController extends Controller
{
    use TraitHelpers;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function home()
    {
        $user = auth()->user();

        if (!$user->can('read: curso')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $disciplinas = DisciplinaAnoLectivo::where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->with(['disciplina'])
        ->get();

        $escola = Shcool::with(['ensino'])->findOrFail($this->escolarLogada());

        if ($escola->pais_escola == "Internacional") {
            $categorias = CategoriaDisciplina::where('level', '5')->get();
        } else {
            $categorias = CategoriaDisciplina::where('level', '2')->get();
        }

        $headers = [
            "escola" => $escola,
            "usuario" => User::findOrFail(Auth::user()->id),
            "cursos" => Curso::get(),
            "categorias" => $categorias,
            "ano_lectivo" => AnoLectivo::findOrFail($this->anolectivoActivo()),
        
            "faculdades" => FaculdadeAnoLectivo::where('shcools_id', $this->escolarLogada())
                ->where('ano_lectivos_id', $this->anolectivoActivo())
                ->with(['faculdade'])
            ->get(),
                
            "lista_funcionarios" => Funcionarios::where('level', '4')->where('shcools_id', $this->escolarLogada())->get(),
            "candidaturas" => CandidaturaAnoLectivo::where('shcools_id', $this->escolarLogada())
                ->where('ano_lectivos_id', $this->anolectivoActivo())
                ->with(['candidatura'])
            ->get(),

            "disciplinas" => $disciplinas
        ];

        return view('admin.anolectivos.cursos.home', $headers);
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
        
        $query = AnoLectivoCurso::when($request->designacao_geral, function ($query, $value) {
            $query->where(function ($q) use ($value) {
                // campo da própria tabela
                $q->where('total_vagas', 'like', "%{$value}%")
        
                // campo da tabela classe
                ->orWhereHas('curso', function ($qc) use ($value) {
                    $qc->where('curso', 'like', "%{$value}%");
                })
                // campo da tabela ensino (classe->ensino)
                ->orWhereHas('faculdade', function ($qe) use ($value) {
                    $qe->where('nome', 'like', "%{$value}%");
                })
                // campo da tabela ensino (classe->ensino)
                ->orWhereHas('candidatura', function ($qe) use ($value) {
                    $qe->where('nome', 'like', "%{$value}%");
                })
                // campo da tabela ensino (classe->ensino)
                ->orWhereHas('coordenador', function ($qe) use ($value) {
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
        ->with(['curso', 'coordenador', 'faculdade', 'candidatura']);
        
        return response()->json(
            $query->orderByDesc('id')->paginate($paginacao)
        );
    
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('create: curso')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            "ano_lectivo_id" => 'required',
            "cursos_id" => 'required|array',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            foreach ($request->cursos_id as $key) {

                $verificarClasses = AnoLectivoCurso::where('ano_lectivos_id', $request->ano_lectivo_id)
                    ->where('cursos_id', $key)
                    ->first();

                if (!$verificarClasses) {
                    AnoLectivoCurso::create([
                        "ano_lectivos_id" => $request->ano_lectivo_id,
                        "cursos_id" => $key,
                        "faculdade_id" => $request->faculdade_id,
                        "candidatura_id" => $request->candidatura_id,
                        "max_cadeira" => $request->max_cadeira,
                        "duracao" => $request->duracao,
                        "coordenador_id" => $request->coordenador_id,
                        "vantagens" => $request->vantagens,
                        "area_saidas" => $request->area_saidas,
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

    public function edit($id)
    {
        $user = auth()->user();

        if (!$user->can('update: curso')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        return AnoLectivoCurso::with(['curso', 'coordenador', 'faculdade', 'candidatura'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user->can('update: curso')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            "ano_lectivo_id" => 'required',
            "cursos_id" => 'required',
        ]);

        $create = AnoLectivoCurso::findOrFail($id);
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
                    
            $create->ano_lectivos_id = $request->ano_lectivo_id;
            $create->cursos_id = $request->cursos_id[0];
            $create->total_vagas = $request->vagas;
            $create->vantagens = $request->vantagens;
            $create->area_saidas = $request->area_saidas;
            $create->faculdade_id = $request->faculdade_id;
            $create->candidatura_id = $request->candidatura_id;
            $create->coordenador_id = $request->coordenador_id;
            $create->max_cadeira = $request->max_cadeira;
            $create->duracao = $request->duracao;
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
            "titulo" => "LISTAGEM DOS CURSOS",
            "cursos" => AnoLectivoCurso::where('shcools_id', $this->escolarLogada())
                ->where('ano_lectivos_id', $this->anolectivoActivo())
                ->with(['curso'])
                ->get(),
        ];

        $documentType = $request->documentType;
        
        if($documentType === 'excel') {
            return Excel::download(new CursoAnoLectivoExport, 'curso-ano-lectivo.xlsx');
        } else {
            $pdf = \PDF::loadView('downloads.relatorios.lista-cursos-ano-lectivo', $headers);
            return $pdf->stream('lista-cursos-ano-lectivo.pdf');
        }

    }

    public function destroy($id)
    {
        $user = auth()->user();

        if (!$user->can('delete: curso')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            AnoLectivoCurso::findOrFail($id)->delete();
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
    
}
