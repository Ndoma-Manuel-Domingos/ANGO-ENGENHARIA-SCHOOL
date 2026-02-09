<?php

namespace App\Http\Controllers;

use App\Exports\DisciplinaAnoLectivoExport;
use App\Exports\SalaAnoLectivoExport;
use App\Models\Shcool;
use App\Models\User;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\classes\AnoLectivoClasse;
use App\Models\web\classes\Classe;
use App\Models\web\cursos\AnoLectivoCurso;
use App\Models\web\cursos\Curso;
use App\Models\web\disciplinas\Candidatura;
use App\Models\web\disciplinas\CandidaturaAnoLectivo;
use App\Models\web\disciplinas\Disciplina;
use App\Models\web\disciplinas\DisciplinaAnoLectivo;
use App\Models\web\disciplinas\Faculdade;
use App\Models\web\disciplinas\FaculdadeAnoLectivo;
use App\Models\web\funcionarios\Funcionarios;
use App\Models\web\salas\AnoLectivoSala;
use App\Models\web\salas\Sala;
use App\Models\web\turnos\AnoLectivoTurno;
use App\Models\web\turnos\Turno;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class AnoLectivoConfiguracaoController extends Controller
{
    use TraitHelpers;


    public function __construct()
    {
        $this->middleware('auth');
    }


    // --------------------------------------------------------------------------------------
    // --------------------------------- CONFIGURAÇÃO DO ANO LECTIVO ------------------------
    // --------------------------------------------------------------------------------------
    // --------------------------------------------------------------------------------------

    public function ClassesIndex()
    {
        $user = auth()->user();

        if (!$user->can('read: classe')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $headers = [
            "usuario" => User::findOrFail(Auth::user()->id),
            "ano_lectivo" => AnoLectivo::findOrFail($this->anolectivoActivo()),
            "lista_classes" => Classe::get(),
            "classes" => AnoLectivoClasse::where('shcools_id', $this->escolarLogada())
                ->where('ano_lectivos_id', $this->anolectivoActivo())
                ->with(['classe'])
            ->get(),
            "escola" => Shcool::with(['ensino'])->findOrFail($this->escolarLogada()),
        ];

        return view('admin.anolectivos.classesIndex', $headers);
    }

    public function candidaturasIndex()
    {
        $user = auth()->user();

        if (!$user->can('read: classe')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $headers = [
            "usuario" => User::findOrFail(Auth::user()->id),
            "ano_lectivo" => AnoLectivo::findOrFail($this->anolectivoActivo()),
            "lista_candidaturas" => Candidatura::get(),
            "candidaturas" => CandidaturaAnoLectivo::where('shcools_id', $this->escolarLogada())
                ->where('ano_lectivos_id', $this->anolectivoActivo())
                ->with(['candidatura'])
            ->get(),
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
        ];

        return view('admin.anolectivos.candidaturaIndex', $headers);
    }

    public function SalasIndex()
    {
        $user = auth()->user();

        if (!$user->can('read: sala')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "usuario" => User::findOrFail(Auth::user()->id),
            "lista_salas" => Sala::get(),
            "ano_lectivo" => AnoLectivo::findOrFail($this->anolectivoActivo()),
            "salas" => AnoLectivoSala::where('shcools_id', $this->escolarLogada())
                ->where('ano_lectivos_id', $this->anolectivoActivo())
                ->with(['sala'])
            ->get(),
        ];

        return view('admin.anolectivos.salasIndex', $headers);
    }


    public function disciplinasIndex()
    {
        $user = auth()->user();

        if (!$user->can('read: disciplina')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "usuario" => User::findOrFail(Auth::user()->id),
            "ano_lectivo" => AnoLectivo::findOrFail($this->anolectivoActivo()),
            "lista_disciplinas" => Disciplina::get(),
            "disciplinas" => DisciplinaAnoLectivo::where('shcools_id', $this->escolarLogada())
                ->where('ano_lectivos_id', $this->anolectivoActivo())
                ->with(['disciplina'])
            ->get(),
        ];

        return view('admin.disciplinas.home', $headers);
    }

    public function faculdadesIndex()
    {
        $user = auth()->user();

        if (!$user->can('read: disciplina')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "usuario" => User::findOrFail(Auth::user()->id),
            "ano_lectivo" => AnoLectivo::findOrFail($this->anolectivoActivo()),
            "lista_faculdades" => Faculdade::get(),
            "lista_funcionarios" => Funcionarios::where('level', '4')->where('shcools_id', $this->escolarLogada())->get(),
            "faculdades" => FaculdadeAnoLectivo::where('shcools_id', $this->escolarLogada())
                ->where('ano_lectivos_id', $this->anolectivoActivo())
                ->with(['faculdade', 'decano'])
            ->get(),
        ];

        return view('admin.faculdades.home', $headers);
    }

    // deletar classes Ano Lectivo
    public function deleteDisciplina($id)
    {
        $user = auth()->user();

        if (!$user->can('delete: disciplina')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            DisciplinaAnoLectivo::findOrFail($id)->delete();
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

    // deletar classes Ano Lectivo
    public function deletefaculdade($id)
    {
        $user = auth()->user();

        if (!$user->can('delete: disciplina')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }


        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            FaculdadeAnoLectivo::findOrFail($id)->delete();
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

    // deletar classes Ano Lectivo
    public function deletecandidaturas($id)
    {
        $user = auth()->user();

        if (!$user->can('delete: candidatura')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            CandidaturaAnoLectivo::findOrFail($id)->delete();
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

    public function createDisciplinas(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('create: disciplina')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            "disciplina_id" => 'required|array',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $contarExistenciasClasseAnoLectivo = 0;

            foreach ($request->disciplina_id as $idKey) {

                $verificarClasses = DisciplinaAnoLectivo::where('shcools_id', $this->escolarLogada())
                    ->where('ano_lectivos_id', $this->anolectivoActivo())
                    ->where('disciplinas_id', $idKey)
                ->first();

                if ($verificarClasses) {
                    $contarExistenciasClasseAnoLectivo++;
                } else {
                    DisciplinaAnoLectivo::create([
                        "ano_lectivos_id" => $this->anolectivoActivo(),
                        "disciplinas_id" => $idKey,
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


    public function createfaculdade(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('create: disciplina')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            "faculdade_id" => 'required|array',
            "decano_id" => 'required',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $contarExistenciasClasseAnoLectivo = 0;

            foreach ($request->faculdade_id as $idKey) {

                $verificarClasses = FaculdadeAnoLectivo::where('shcools_id', $this->escolarLogada())
                    ->where('ano_lectivos_id', $this->anolectivoActivo())
                    ->where('faculdades_id', $idKey)
                ->first();

                if ($verificarClasses) {
                    $contarExistenciasClasseAnoLectivo++;
                } else {
                    FaculdadeAnoLectivo::create([
                        "ano_lectivos_id" => $this->anolectivoActivo(),
                        "faculdades_id" => $idKey,
                        "decano_id" => $request->decano_id,
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


    public function createSalas(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('create: sala')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            "ano_lectivo_id" => 'required',
            "salas_id" => 'required|array',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            foreach ($request->salas_id as $key) {

                $verificarClasses = AnoLectivoSala::where('ano_lectivos_id', $request->ano_lectivo_id)
                    ->where('salas_id', $key)
                ->first();

                if (!$verificarClasses) {
                    AnoLectivoSala::create([
                        "ano_lectivos_id" => $request->ano_lectivo_id,
                        "salas_id" => $key,
                        "total_vagas" => $request->total_vagas,
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

    public function createcandidatura(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('create: candidatura')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            "ano_lectivo_id" => 'required',
            "candidaturas_id" => 'required',
        ]);

        $contarExistenciasCandidaturaAnoLectivo = 0;

        foreach ($request->candidaturas_id as $idKey) {

            $verificarClasses = CandidaturaAnoLectivo::where('ano_lectivos_id', $request->ano_lectivo_id)
                ->where('candidaturas_id', $idKey)
            ->first();

            if ($verificarClasses) {
                $contarExistenciasCandidaturaAnoLectivo++;
            } else {
                CandidaturaAnoLectivo::create([
                    'ano_lectivos_id' => $request->ano_lectivo_id,
                    'candidaturas_id' => $idKey,
                    'shcools_id' => $this->escolarLogada(),
                ]);
            }
        }

        Alert::success("Atenção", "Dados salvos com sucesso! ");
        return redirect()->back();
    }

    /** ******************************************  */
    public function candidaturasEdit($id)
    {
        $user = auth()->user();

        if (!$user->can('update: classe')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $dado = CandidaturaAnoLectivo::with('candidatura')->findOrFail($id);

        return response()->json([
            "dados" => $dado,
            "candidaturas" => Candidatura::get(),
        ], 200);
    }

    /** ******************************************  */
    public function Updatecandidatura(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('update: candidatura')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            "ano_lectivo_id" => 'required',
            "candidaturas_id" => 'required',
        ], [
            "ano_lectivo_id.required" => "Campo Obrigatório",
            "candidaturas_id.required" => "Campo Obrigatório",
        ]);

        $create = CandidaturaAnoLectivo::findOrFail($request->id);
        $create->ano_lectivos_id = $request->input('ano_lectivo_id');
        $create->candidaturas_id = $request->input('candidaturas_id');
        $create->update();

        Alert::success("Atenção", "Dados Actualizados com sucesso! ");
        return redirect()->back();
    }

    public function UpdateSalas(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('update: sala')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            "ano_lectivo_id" => 'required',
            "salas_id" => 'required',
        ], [
            "ano_lectivo_id.required" => "Campo Obrigatório",
            "salas_id.required" => "Campo Obrigatório",
        ]);

        $create = AnoLectivoSala::findOrFail($request->id);
        $create->ano_lectivos_id = $request->input('ano_lectivo_id');
        $create->salas_id = $request->input('salas_id');
        $create->total_vagas = $request->input('total_vagas');
        $create->update();

        Alert::success("Atenção", "Dados salvos com sucesso! ");
        return redirect()->back();
    }


    // deletar turnos Ano Lectivo
    public function deleteTurnosAnoLectivo($id)
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

    // deletar salas Ano Lectivo
    public function deleteSalasAnoLectivo($id)
    {
        $user = auth()->user();

        if (!$user->can('delete: sala')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            AnoLectivoSala::findOr($id)->delete();
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

    // deletar cursos Ano Lectivo
    public function deleteCursosAnoLectivo($id)
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

    // deletar cursos Ano Lectivo
    public function deleteClassesAnoLectivo($id)
    {
        $user = auth()->user();

        if (!$user->can('delete: curso')) {
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

    public function carregamentoTabelasConfiguracoes($id)
    {

        $user = auth()->user();

        if (!$user->can('read: ano lectivo')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $ano = AnoLectivo::findOrFail($id);
        
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());
    
        $headers = [
            "escola" => $escola,
            "titulo" => "Configuração do Ano lectivo",
            "descricao" => env('APP_NAME'),

            "classes" => Classe::get(),
            "cursos" => Curso::get(),
            "turnos" => Turno::get(),
            "salas" => Sala::where('shcools_id', $this->escolarLogada())->get(),
            "anoLectivo" => $ano,
            
            "_classes" => AnoLectivoClasse::where('ano_lectivos_id', $ano->id)->with(['classe'])->pluck('classes_id')->toArray(),
            "_cursos" => AnoLectivoCurso::where('ano_lectivos_id', $ano->id)->with(['curso'])->pluck('cursos_id')->toArray(),
            "_salas" => AnoLectivoSala::where('ano_lectivos_id', $ano->id)->with(['sala'])->pluck('salas_id')->toArray(),
            "_turnos" => AnoLectivoTurno::where('ano_lectivos_id', $ano->id)->with(['turno'])->pluck('turnos_id')->toArray(),
        ];

        return view('admin.anolectivos.config', $headers);
    }

    // retificado
    public function DisciplinaPDF()
    {
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "titulo" => "LISTAGEM DAS DISCIPLINAS",
            "disciplinas" => DisciplinaAnoLectivo::where('shcools_id', $this->escolarLogada())
                ->with(['disciplina'])
                ->get(),
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-disciplinas-ano-lectivo', $headers);
        return $pdf->stream('lista-disciplinas-ano-lectivo.pdf');
    }

    public function SalasPDF()
    {
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);
        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "titulo" => "LISTAGEM DAS SALAS",
            "salas" => AnoLectivoSala::where([
                ['shcools_id', $this->escolarLogada()],
                ['ano_lectivos_id', $this->anolectivoActivo()],
            ])
                ->with(['sala'])
                ->get(),
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-salas-ano-lectivo', $headers);
        return $pdf->stream('lista-salas-ano-lectivo.pdf');
    }

    public function DisciplinaExcel()
    {
        return Excel::download(new DisciplinaAnoLectivoExport, 'disciplinas-ano-lectivo.xlsx');
    }

    public function SalasExcel()
    {
        return Excel::download(new SalaAnoLectivoExport, 'salas-ano-lectivo.xlsx');
    }

}
