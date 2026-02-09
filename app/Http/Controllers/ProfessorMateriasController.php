<?php

namespace App\Http\Controllers;

use App\Models\Notificacao;
use App\Models\Professor;
use App\Models\Shcool;
use App\Models\TurmaMateria;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\disciplinas\Disciplina;
use App\Models\web\funcionarios\FuncionariosControto;
use App\Models\web\turmas\FuncionariosTurma;
use App\Models\web\turmas\Turma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class ProfessorMateriasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
            
        $user = auth()->user();

        if (!$user->can('read: materias')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $professor = Professor::with('nacionalidade')->with('provincia')->with('academico.escolaridade')->with('academico.formacao')->findOrFail(Auth::user()->funcionarios_id);
        
        $escolas = FuncionariosControto::whereIn('funcionarios_id', [$professor->id])->distinct()->orderBy('shcools_id', "desc")->get(['shcools_id']);

        // escplas onde ele passa
        $infor_escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->whereIn('id', $escolas)->get();
        $anos_lectivos = AnoLectivo::whereIn('shcools_id', $escolas)->get();

        // todas asturmas que ele leciona e disxiplinas
        $turmas = FuncionariosTurma::where([
            ['tb_turmas_funcionarios.funcionarios_id', '=', $professor->id],
        ])
        ->join('tb_disciplinas', 'tb_turmas_funcionarios.disciplinas_id', '=', 'tb_disciplinas.id')
        ->join('tb_turmas', 'tb_turmas_funcionarios.turmas_id', '=', 'tb_turmas.id')
        ->select('tb_disciplinas.disciplina', 'tb_disciplinas.id AS idDis', 'tb_turmas_funcionarios.cargo_turma', 'tb_turmas.turma', 'tb_turmas.id AS idTurma')
        ->get();
        
        $materias = TurmaMateria::when($request->escola_id, function($query, $value){
            $query->where('shcools_id', $value);
        })
        ->when($request->turmas_id, function($query, $value){
            $query->where('turmas_id', $value);
        })
        ->when($request->disciplinas_id, function($query, $value){
            $query->where('disciplinas_id', $value);
        })
        ->when($request->ano_lectivos_id, function($query, $value){
            $query->where('ano_lectivos_id', $value);
        })
        ->with(['professor', 'turma', 'disciplina', 'escola', 'ano'])
        ->where('professor_id', $professor->id)
        ->get();
               
        $headers = [
            "titulo" => "Minha matérias",
            "descricao" => "Professor",
            'professor' => $professor,
            'contratos' => $escolas,
            'escolas' => $infor_escola,
            'turmas' => $turmas,
            'anos_lectivos' => $anos_lectivos,
            
            'materias' => $materias,
        ];

        return view('professores.materias.index', $headers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {         
        $user = auth()->user();

        if (!$user->can('create: materias')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $professor = Professor::with('nacionalidade')->with('provincia')->with('academico.escolaridade')->with('academico.formacao')->findOrFail(Auth::user()->funcionarios_id);
        
        $escolas = FuncionariosControto::whereIn('funcionarios_id', [$professor->id])->distinct()->orderBy('shcools_id', "desc")->get(['shcools_id']);

        // escplas onde ele passa
        $infor_escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->whereIn('id', $escolas)->get();
        $anos_lectivos = AnoLectivo::whereIn('shcools_id', $escolas)->get();

        // todas asturmas que ele leciona e disxiplinas
        $turmas = FuncionariosTurma::where([
            ['tb_turmas_funcionarios.funcionarios_id', '=', $professor->id],
        ])
        ->join('tb_disciplinas', 'tb_turmas_funcionarios.disciplinas_id', '=', 'tb_disciplinas.id')
        ->join('tb_turmas', 'tb_turmas_funcionarios.turmas_id', '=', 'tb_turmas.id')
        ->select('tb_disciplinas.disciplina', 'tb_disciplinas.id AS idDis', 'tb_turmas_funcionarios.cargo_turma', 'tb_turmas.turma', 'tb_turmas.id AS idTurma')
        ->get();

        $headers = [
            "titulo" => "Criar Novas matérias",
            "descricao" => "Professor",
            'professor' => $professor,
            'contratos' => $escolas,
            'escolas' => $infor_escola,
            'turmas' => $turmas,
            'anos_lectivos' => $anos_lectivos,
            
        ];

        return view('professores.materias.create', $headers);
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

        if (!$user->can('create: materias')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            'titulo' => 'required',
            'descricao' => 'required',
            'documento1'  => 'required',
            'escola_id'  => 'required',
            'turmas_id'  => 'required',
            'disciplinas_id'  => 'required',
            'ano_lectivos_id'  => 'required',
        ], [
            'titulo.required' => "Compo Obrigatório",
            'descricao.required' => "Compo Obrigatório",
            'documento1.required'  => 'Compo Obrigatório',
            'escola_id.required'  => 'Compo Obrigatório',
            'turmas_id.required'  => 'Compo Obrigatório',
            'disciplinas_id.required'  => 'Compo Obrigatório',
            'ano_lectivos_id.required'  => 'Compo Obrigatório',
        ]);
        
        
        if (!empty($request->file('documento1'))) {
            $image = $request->file('documento1');
            $documento1 = time() .'1.'. $image->extension();
            $image->move(public_path('assets/materias'), $documento1);
        }else{
            $documento1 = NULL;
        }

        if (!empty($request->file('documento2'))) {
            $image2 = $request->file('documento2');
            $documento2 = time() .'2.'. $image2->extension();
            $image2->move(public_path('assets/materias'), $documento2);
        }else{
            $documento2 = NULL;
        }

        if (!empty($request->file('documento3'))) {
            $image3 = $request->file('documento3');
            $documento3 = time() .'3.'. $image3->extension();
            $image3->move(public_path('assets/materias'), $documento3);
        }else{
            $documento3 = NULL;
        }
        
        $professor = Professor::findOrFail($request->professor_id);
        $turma = Turma::findOrFail($request->turmas_id);
        $disciplina = Disciplina::findOrFail($request->disciplinas_id);
        $escola = Shcool::findOrFail($request->escola_id);
        
        $create = TurmaMateria::create([
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'turmas_id' => $request->turmas_id,
            'disciplinas_id' => $request->disciplinas_id,
            'ano_lectivos_id' => $request->ano_lectivos_id,
            'shcools_id' => $request->escola_id,
            'data_limite' => $request->data_limite,
            
            'professor_id' => $professor->id,
            
            'documento1' => $documento1,
            'documento2' => $documento2,
            'documento3' => $documento3,
        ]);
        
        
        $text = "O Professor {$professor->nome} {$professor->sobre_nome} enviou matéria para turma {$turma->turma} na disciplina de {$disciplina->disciplina} na escola {$escola->nome}";
        $text2 = "O Sr(a) acabou de enviou matéria para turma {$turma->turma} na disciplina de {$disciplina->disciplina} na escola {$escola->nome}";
            
        
        Notificacao::create([
            'user_id' => Auth::user()->id,
            'destino' => $request->escola_id,
            'type_destino' => 'escola',
            'type_enviado' => 'professor',
            'notificacao' => $text,
            'notificacao_user' => $text2,
            'status' => '0',
            'model_id' => $create->id,
            'model_type' => "materia",
            'shcools_id' => $request->escola_id
        ]);
        
        Alert::success('Bom Trabalho', 'Matéria salva com sucesso!');
        return redirect()->back();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
                        
        $user = auth()->user();

        if (!$user->can('read: materias')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $materia = TurmaMateria::with(['professor', 'turma', 'disciplina', 'escola', 'ano'])
        ->findOrfail($id);
        
        $professor = Professor::with('nacionalidade')->with('provincia')->with('academico.escolaridade')->with('academico.formacao')->findOrFail(Auth::user()->funcionarios_id);

        $headers = [
            "titulo" => "Editar matérias",
            "descricao" => "Professor",
            'professor' => $professor,
            'materia' => $materia,
        ];

        return view('professores.materias.show', $headers);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
                
        $user = auth()->user();

        if (!$user->can('update: materias')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $materia = TurmaMateria::findOrfail($id);
        
        $professor = Professor::with('nacionalidade')->with('provincia')->with('academico.escolaridade')->with('academico.formacao')->findOrFail(Auth::user()->funcionarios_id);
        
        $escolas = FuncionariosControto::whereIn('funcionarios_id', [$professor->id])->distinct()->orderBy('shcools_id', "desc")->get(['shcools_id']);

        // escplas onde ele passa
        $infor_escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->whereIn('id', $escolas)->get();
        $anos_lectivos = AnoLectivo::whereIn('shcools_id', $escolas)->get();

        // todas asturmas que ele leciona e disxiplinas
        $turmas = FuncionariosTurma::where([
            ['tb_turmas_funcionarios.funcionarios_id', '=', $professor->id],
        ])
        ->join('tb_disciplinas', 'tb_turmas_funcionarios.disciplinas_id', '=', 'tb_disciplinas.id')
        ->join('tb_turmas', 'tb_turmas_funcionarios.turmas_id', '=', 'tb_turmas.id')
        ->select('tb_disciplinas.disciplina', 'tb_disciplinas.id AS idDis', 'tb_turmas_funcionarios.cargo_turma', 'tb_turmas.turma', 'tb_turmas.id AS idTurma')
        ->get();

        $headers = [
            "titulo" => "Editar matérias",
            "descricao" => "Professor",
            'professor' => $professor,
            'contratos' => $escolas,
            'escolas' => $infor_escola,
            'turmas' => $turmas,
            'anos_lectivos' => $anos_lectivos,
            
            'materia' => $materia,
            
        ];

        return view('professores.materias.edit', $headers);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
                
        $user = auth()->user();

        if (!$user->can('update: materias')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            'titulo' => 'required',
            'descricao' => 'required',
            'escola_id'  => 'required',
            'turmas_id'  => 'required',
            'disciplinas_id'  => 'required',
            'ano_lectivos_id'  => 'required',
        ], [
            'titulo.required' => "Compo Obrigatório",
            'descricao.required' => "Compo Obrigatório",
            'escola_id.required'  => 'Compo Obrigatório',
            'turmas_id.required'  => 'Compo Obrigatório',
            'disciplinas_id.required'  => 'Compo Obrigatório',
            'ano_lectivos_id.required'  => 'Compo Obrigatório',
        ]);
        
        if (!empty($request->file('documento1'))) {
            $image = $request->file('documento1');
            $documento1 = time() .'1.'. $image->extension();
            $image->move(public_path('assets/materias'), $documento1);
        }else{
            $documento1 = $request->documento1_guardado;
        }

        if (!empty($request->file('documento2'))) {
            $image2 = $request->file('documento2');
            $documento2 = time() .'2.'. $image2->extension();
            $image2->move(public_path('assets/materias'), $documento2);
        }else{
            $documento2 = $request->documento2_guardado;
        }

        if (!empty($request->file('documento3'))) {
            $image3 = $request->file('documento3');
            $documento3 = time() .'3.'. $image3->extension();
            $image3->move(public_path('assets/materias'), $documento3);
        }else{
            $documento3 = $request->documento3_guardado;
        }
        
        
        $professor = Professor::findOrFail($request->professor_id);
        $turma = Turma::findOrFail($request->turmas_id);
        $disciplina = Disciplina::findOrFail($request->disciplinas_id);
        $escola = Shcool::findOrFail($request->escola_id);
        
        $update = TurmaMateria::findOrfail($id);
        $update->titulo = $request->titulo;
        $update->descricao = $request->descricao;
        $update->turmas_id = $request->turmas_id;
        $update->disciplinas_id = $request->disciplinas_id;
        $update->ano_lectivos_id = $request->ano_lectivos_id;
        $update->shcools_id = $request->escola_id;
        $update->data_limite = $request->data_limite;
        
        $update->professor_id = $professor->id;
        
        $update->documento1 = $documento1;
        $update->documento2 = $documento2;
        $update->documento3 = $documento3;
        
        $update->update();
        
        
        $text = "O Professor {$professor->nome} {$professor->sobre_nome} actualizar uma matéria para turma {$turma->turma} na disciplina de {$disciplina->disciplina} na escola {$escola->nome}";
        $text2 = "O Sr(a) acabou de actualizar uma matéria para turma {$turma->turma} na disciplina de {$disciplina->disciplina} na escola {$escola->nome}";
            
        
        Notificacao::create([
            'user_id' => Auth::user()->id,
            'destino' => $request->escola_id,
            'type_destino' => 'escola',
            'type_enviado' => 'professor',
            'notificacao' => $text,
            'notificacao_user' => $text2,
            'status' => '0',
            'model_id' => $update->id,
            'model_type' => "materia",
            'shcools_id' => $request->escola_id
        ]);
        
        Alert::success('Bom Trabalho', 'Matéria Actualizada com sucesso!');
        return redirect()->back();
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
                
        $user = auth()->user();

        if (!$user->can('delete: materias')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        TurmaMateria::findOrfail($id)->delete();
        Alert::success('Bom Trabalho', 'Matéria Excluida com sucesso!');
        return redirect()->back();
    }
}
