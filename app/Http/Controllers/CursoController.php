<?php

namespace App\Http\Controllers;

use App\Exports\CursoExport;
use App\Models\User;
use App\Models\web\cursos\Curso;
use App\Models\web\cursos\DisciplinaCurso;
use App\Models\web\disciplinas\Disciplina;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class CursoController extends Controller
{
    //
    use TraitHelpers;
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    // view cursos principal
    public function cursos()
    {
        $user = auth()->user();
        
        if(!$user->can('read: curso')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
    
        $headers = [
            "titulo" => "Lista dos Cursos",
            "descricao" => env('APP_NAME'),
            "disciplinas" => Disciplina::get(),
            "usuario" => User::findOrFail(Auth::user()->id),
            "listarCursos" => Curso::get(),
        ];
        
        return view('sistema.cursos.home', $headers);
    }

    // cadastrar cursos
    public function cadastrarCursos(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('create: curso')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
    
        $validate = Validator::make($request->all(), [
            "nome_cursos" => 'required',
            "abreviacao_cursos" => 'required',
            "tipo_cursos" => 'required',
            "area_formacao_cursos" => 'required',
            "status_cursos" => 'required',
        ], [
            "nome_cursos.required" => "Campo Obrigatório",
            "abreviacao_cursos.required" => "Campo Obrigatório",
            "tipo_cursos.required" => "Campo Obrigatório",
            "area_formacao_cursos.required" => "Campo Obrigatório",
            "status_cursos.required" => "Campo Obrigatório",
        ]);


        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        }else{
            $create = new Curso();
            $create->curso = $request->input('nome_cursos');
            $create->abreviacao = $request->input('abreviacao_cursos');
            $create->tipo = $request->input('tipo_cursos');
            $create->area_formacao = $request->input('area_formacao_cursos');
            $create->status = $request->input('status_cursos');
            $create->descricao = $request->input('descricao_cursos');
            $create->save();

            return response()->json([
                'status' => 200,
                'message' => 'Dados salvos com sucesso!',
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        }
    }

    // cadastrarDisciplinasCursos
    public function cadastrarDisciplinasCursos(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('create: disciplina curso')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
    
        $request->validate([
            "disciplina_id" => 'required|array',
            "categoria_id" => 'required',
            "curso__id" => 'required',
        ]);
                
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
                  
            foreach ($request->disciplina_id as $key) {
                $verificarTurno = DisciplinaCurso::where('disciplinas_id', $key)
                    ->where('cursos_id', $request->curso__id)
                    ->where('shcools_id', $this->escolarLogada())
                    ->where('ano_lectivos_id', $this->anolectivoActivo())
                ->first();
    
                if(!$verificarTurno){
                    DisciplinaCurso::create([
                        "categoria_id" => $request->categoria_id,
                        "disciplinas_id" => $key,
                        "cursos_id" => $request->curso__id,
                        "peso" => $request->peso,
                        "shcools_id" => $this->escolarLogada(),
                        "ano_lectivos_id" => $this->anolectivoActivo(),
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
        

        return response()->json([
            'status' => 200,
            'message' => 'Dados salvos com sucesso!',
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }

    // editar cursos
    public function editarCursos($id)
    {
        $user = auth()->user();
        
        if(!$user->can('update: curso')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $cursoId = Curso::findOrFail($id);

        if ($cursoId) {
            return response()->json([
                "status" => 200,
                "cursos" => $cursoId,
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        }else{
            return response()->json([
                "status" => 404,
                "message" => 'Turno não Encontrado',
            ]);
        }

    }

    // actualizar cursos
    public function updateCursos(Request $request, $id)
    {
        $user = auth()->user();
        
        if(!$user->can('update: curso')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $validate = Validator::make($request->all(), [
            "nome_cursos" => 'required',
            "abreviacao_cursos" => 'required',
            "tipo_cursos" => 'required',
            "area_formacao_cursos" => 'required',
            "status_cursos" => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        }else{
            $update = Curso::findOrFail($id);

            if ($update) {
                $update->curso = $request->input('nome_cursos');
                $update->abreviacao = $request->input('abreviacao_cursos');
                $update->tipo = $request->input('tipo_cursos');
                $update->area_formacao = $request->input('area_formacao_cursos');
                $update->status = $request->input('status_cursos');
                $update->descricao = $request->input('descricao_cursos');
                $update->update();

                return response()->json([
                    'status' => 200,
                    'message' => 'Dados ACtualizados com sucesso!',
                    "usuario" => User::findOrFail(Auth::user()->id),
                ]);
            }else{
                return response()->json([
                    "status" => 404,
                    "message" => 'Turno não Encontrado'
                ]);
            }

        }
    }

    // delete cursos
    public function deleteCursos($id)
    {
    
        $user = auth()->user();
        
        if(!$user->can('delete: curso')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $ano = Curso::findOrFail($id);
        $ano->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Dados Excluido com sucesso!',
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }

    // delete disciplina cursos
    public function deleteDisciplinaCursos($id)
    {
        $user = auth()->user();
        
        if(!$user->can('delete: disciplina curso')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
       
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $curso = DisciplinaCurso::findOrFail($id);
            
            $curso->delete();
            
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
        }

        return response()->json([
            'status' => 200,
            'message' => 'Dados Excluido com sucesso!',
        ]);
    }

    // delete disciplina cursos
    public function editarDisciplinaCursos($id)
    {
        $user = auth()->user();
        
        if(!$user->can('update: disciplina curso')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        return DisciplinaCurso::findOrFail($id);
    }
    
    // cadastrarDisciplinasCursos
    public function updateDisciplinasCursos(Request $request, $id)
    {
        $user = auth()->user();
        
        if(!$user->can('update: disciplina curso')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $request->validate([
            "categoria_id" => 'required',
            "disciplina_id" => 'required',
        ]);

        $update = DisciplinaCurso::findOrFail($id);
        
        try {
            DB::beginTransaction();
    
            $update->categoria_id = $request->categoria_id;
            $update->disciplinas_id = $request->disciplina_id;
            $update->peso = $request->peso;
            $update->update();            
           // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
        }

        return response()->json([
            'message' => 'Dados salvos com sucesso!',
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }

    // activar e desactivar curso
    public function activarCursos($id)
    {
        $user = auth()->user();
        
        if(!$user->can('update: estado')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $listarCurso = Curso::findOrFail($id);
        if ($listarCurso) {
            if ($listarCurso->status === 'activo') {
                $listarCurso->status = 'desactivo';
            }else{
                $listarCurso->status = 'activo';
            }
            if ($listarCurso->update()) {
                return response()->json([
                    "status" => 200,
                    "usuario" => User::findOrFail(Auth::user()->id),
                    "message" => "Dodos Activados com sucesso",
                ]);
            }
        }
    }

    public function carregarDisciplinasCursoActivo($id)
    {
        $user = auth()->user();
        
        if(!$user->can('read: disciplina curso')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
    
        $curso = Curso::findOrFail($id);

        $disciplinas = DisciplinaCurso::where('cursos_id', $curso->id)
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->with(['disciplina', 'categoria'])
        ->get();

        return response()->json([
            "usuario" => User::findOrFail(Auth::user()->id),
            "status" => 200,
            "result" => $disciplinas,
            "curso" => $curso,
        ]);
    }

    public function cursosImprimir()
    {
        $headers = [
            "titulo" => "LISTA DOS CURSOS",
            "cursos" => Curso::get()
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-cursos', $headers);
        return $pdf->stream('lista-cursos.pdf');
    }

    public function curosExcel()
    {
        return Excel::download(new CursoExport, 'cursos.xlsx');
    }

}
