<?php

namespace App\Http\Controllers;

use App\Models\AnoLectivoGlobal;
use App\Models\ControloLancamentoNotas;
use App\Models\ControloLancamentoNotasEscolas;
use App\Models\DireccaoMunicipal;
use App\Models\Shcool;
use App\Models\User;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\anolectivo\ControlePeriodico;
use App\Models\web\anolectivo\Trimestre;
use App\Models\web\classes\Classe;
use App\Models\web\cursos\Curso;
use App\Models\web\disciplinas\Disciplina;
use App\Models\web\salas\Sala;
use App\Models\web\turmas\DisciplinaTurma;
use App\Models\web\turmas\EstudantesTurma;
use App\Models\web\turmas\NotaPauta;
use App\Models\web\turmas\Turma;
use App\Models\web\turnos\Turno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

//graficos
use Khill\Lavacharts\Lavacharts;


class ControloLancamentoNotasMunicipalController extends Controller
{
    use TraitHelpers;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();

        // if(!$user->can('read: ensino')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $controlos = ControloLancamentoNotas::with([
            'trimestre',
            'ano_global',
        ])
            ->where('level', '3')
            ->get();

        $headers = [
            "titulo" => "Controlo de Lancamento de notas",
            "descricao" => env('APP_NAME'),
            "escolas" => Shcool::get(),
            "trimestres" => Trimestre::get(),
            "escolas" => Shcool::get(),
            "ano_lectivos" => AnoLectivoGlobal::get(),

            "controlos" => $controlos,
            // "lava" => $data,
        ];

        return view('sistema.direccao-municipal.planificacao.criar-controlo', $headers);
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        // if(!$user->can('create: ensino')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $lancamento = ControloLancamentoNotas::where('status', 'activo')->where('level', '3')->where('ano_lectivo_global_id', $this->anolectivoActivoGlobal())->where('direccao_id', $user->shcools_id)->first();

        if ($lancamento) {
            Alert::warning("Informação", "Infelizmente não podemos concluir porque ainda tem um periodo activo no momento. Desactivar o periodo activo e activar um novo periodo!");
            return redirect()->back();
        }

        $direccao = DireccaoMunicipal::findOrFail($user->shcools_id);

        $request->validate([
            'escola_id' => 'required',
            'trimestre_id' => 'required',
            'data_inicio' => 'required',
            'data_final' => 'required',
            'status' => 'required',
        ], [
            "escola_id.required" => "Campo Obrigatório",
            "trimestre_id.required" => "Campo Obrigatório",
            "data_inicio.required" => "Campo Obrigatório",
            "data_final.required" => "Campo Obrigatório",
            "status.required" => "Campo Obrigatório",
        ]);

        $escolas = Shcool::where('status', 'activo')->get();

        if (!$this->anolectivoActivoGlobal()) {
            Alert::warning('Informação', 'infelizmente não podemos activar data lançamento de notas, porque não temos nenhum ano lectiv cadastro!');
            return redirect()->back();
        }

        $verificar = ControloLancamentoNotas::where([
            ['direccao_id', '=', $direccao->id],
            ['level', '=', '3'],
            ['status', '=', 'activo'],
            ['ano_lectivo_global_id', '=', $this->anolectivoActivoGlobal()],
            ['trimestre_id', '=', $request->trimestre_id]
        ])->first();

        if (!$verificar) {
            $create = ControloLancamentoNotas::create([
                'inicio' => $request->data_inicio,
                'final' => $request->data_final,
                'level' => '3',
                'direccao_id' => $direccao->id,
                'status' => $request->status,
                'ano_lectivo_global_id' => $this->anolectivoActivoGlobal(),
                'trimestre_id' => $request->trimestre_id
            ]);

            foreach ($request->escola_id as $item) {

                if ($item == "todas") {
                    foreach ($escolas as $escola) {
                        $ano = AnoLectivo::where('status', 'activo')->where('shcools_id', $escola->id)->first();
                        if ($ano) {

                            $verificar = ControloLancamentoNotasEscolas::where([
                                ['shcools_id', '=', $escola->id],
                                ['ano_lectivo_id', '=', $ano->id],
                                ['lancamento_id', '=', $create->id],
                            ])->first();

                            if (!$verificar) {
                                ControloLancamentoNotasEscolas::create([
                                    'shcools_id' => $escola->id,
                                    'ano_lectivo_id' => $ano->id,
                                    'total_estudantes' => $this->total_estudantes($escola->id, $ano->id),
                                    'total_lancados' => '0',
                                    'total_restantes' => '0',
                                    'status' => $request->status,
                                    'lancamento_id' => $create->id
                                ]);
                            }
                        }
                    }
                } else {
                    $ano = AnoLectivo::where('status', 'activo')->where('shcools_id', $item)->first();

                    if ($ano) {

                        $verificar = ControloLancamentoNotasEscolas::where([
                            ['shcools_id', '=', $item],
                            ['ano_lectivo_id', '=', $ano->id],
                            ['lancamento_id', '=', $create->id],
                        ])->first();

                        if (!$verificar) {
                            ControloLancamentoNotasEscolas::create([
                                'shcools_id' => $item,
                                'ano_lectivo_id' => $ano->id,
                                'total_estudantes' => $this->total_estudantes($item, $ano->id),
                                'total_lancados' => '0',
                                'total_restantes' => '0',
                                'status' => $request->status,
                                'lancamento_id' => $create->id
                            ]);
                        }
                    }
                }
            }
        }
        Alert::success('Bom Trabalho', 'Lançamento de notas activada para todas as escolas!');
        return redirect()->back();
    }

    public function status($id)
    {

        $user = auth()->user();

        // if(!$user->can('read: ensino')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $update = ControloLancamentoNotas::findOrFail($id);

        if ($update->status == 'activo') {
            $status = 'desactivo';
            $final = $update->final;
        }

        if ($update->status == 'desactivo') {
            $status = 'activo';
            $final = date("Y-m-d", strtotime($update->final . "+7days"));
        }

        $update->status = $status;
        $update->final = $final;
        $update->update();

        return response()->json([
            "status" => 200,
            "text" => "Dodos Activados com sucesso",
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }

    public function escolas(Request $request)
    {

        $user = auth()->user();

        // if(!$user->can('read: ensino')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $controlos = ControloLancamentoNotasEscolas::with([
            'ano',
            'escola',
            'lancamento',
        ])
            ->when($request->escola_id, function ($query, $value) {
                $query->where('shcools_id', $value);
            })
            ->where('lancamento_id', $request->lancamento_id)
            ->get();

        $headers = [
            "titulo" => "Controlo de Lancamento de notas",
            "descricao" => "Escolas",
            "escolas" => $controlos,
            "escolas_list" => Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->where('status', 'activo')->get(),

            "requests" => $request->all('lancamento_id', 'escola_id'),
        ];

        return view('sistema.direccao-municipal.planificacao.escolas', $headers);
    }


    /**
     ** PROCESSO DE NOTAS
     */
    public function controlo()
    {
        $user = auth()->user();

        // if(!$user->can('create: ensino')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $lancamento = ControloLancamentoNotas::where('status', 'activo')->where('level', '3')->where('ano_lectivo_global_id', $this->anolectivoActivoGlobal())->where('direccao_id', $user->shcools_id)->first();

        $controlos = null;
        $data = null;

        if ($lancamento) {
            $controlos = ControloLancamentoNotasEscolas::with('escola')->where('lancamento_id', $lancamento->id)->get();
            $data = new Lavacharts;
            $datatable = $data->DataTable();

            $datatable->addStringColumn('Estudantes')
                ->addNumberColumn('Total')
                ->addNumberColumn('Lançados')
                ->addNumberColumn('Restantes');

            foreach ($controlos as  $result) {
                $datatable->addRow([$result->escola->nome, $result->total_estudantes, $result->total_lancados, $result->total_restantes]);
            }

            $options = [
                'title' => 'Escola com Notas lançadas',
                'height' => 370,
                'colors' => ['DeepSkyBlue', 'Chocolate', 'red'],
                'titleTextStyle' => [
                    'color'    => 'rgb(123, 65, 89)',
                    'fontSize' => 14
                ],
                'legend' => [
                    'position' => 'right'
                ],
                'is3D'   => true,
                'slices' => [
                    ['offset' => 0.2],
                    ['offset' => 0.25],
                    ['offset' => 0.3]
                ],
                'series' => [
                    // 2 => ['type' => 'line'],
                    // 1 => ['type' => 'line'],
                    // 3 => ['type' => 'line'],
                    // 0 => ['type' => 'line'],
                ]
            ];

            $data->ColumnChart('Grafico', $datatable, $options);
        }

        $headers = [
            "titulo" => "Mini Pautas",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),

            "controlos" => $controlos,
            "lava" => $data,
            "lancamento" => $lancamento,
        ];

        return view('sistema.direccao-municipal.planificacao.controlo', $headers);
    }


    // mini pautas geral \\ 1ª 2ª 3ª trimestres miniPauta
    public function miniPauta()
    {
        $user = auth()->user();

        // if(!$user->can('create: ensino')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $turmas = Turma::where([
            ['status', '=', 'activo'],
        ])->get();

        $trimestres = ControlePeriodico::where('trimestre', '<>', 'Geral')->get();

        $headers = [
            "titulo" => "Mini Pautas",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "turmas" => $turmas,
            "trimestres" => $trimestres,
            "escolas" => Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->where('status', 'activo')->get(),
            "escolaId" => "",
            "ano_lectivo" => "",
        ];

        return view('sistema.direccao-municipal.planificacao.mini-pauta', $headers);
    }


    // pesquisar MIni PAutas para todas as turmas
    public function pesquisarMiniPauta(Request $request)
    {
        $user = auth()->user();

        // if(!$user->can('create: ensino')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $request->validate([
            'escola_id' => 'required',
            'ano_lectivos_id' => 'required',
            'turmas_id' => 'required',
            'disciplinas_id' => 'required',
            'condicao_pesquisar' => 'required',
            'trimestre_id' => 'required',
        ]);

        $trimestres = ControlePeriodico::where('trimestre', '<>', 'Geral')->get();

        $turma = Turma::findOrFail($request->turmas_id);
        $disciplina = Disciplina::findOrFail($request->disciplinas_id);
        $trimestre = ControlePeriodico::findOrFail($request->trimestre_id);
        $escola = Shcool::findOrFail($request->escola_id);
        $anolectivo = AnoLectivo::findOrFail($request->ano_lectivos_id);
        
        $trimestre1 = ControlePeriodico::where('trimestre', 'Iª Trimestre')->first();
        $trimestre2 = ControlePeriodico::where('trimestre', 'IIª Trimestre')->first();
        $trimestre3 = ControlePeriodico::where('trimestre', 'IIIª Trimestre')->first();
        
        
        $notas = null;
        
        if (isset($disciplina->id) && isset($trimestre->id) && isset($anolectivo->id) && isset($turma->id)) {
            
            $notas = NotaPauta::where('tb_notas_pautas.disciplinas_id', $disciplina->id)
                ->where('tb_notas_pautas.controlo_trimestres_id', $trimestre->id)
                ->where('tb_notas_pautas.ano_lectivos_id', $anolectivo->id)
                ->where('tb_notas_pautas.turmas_id', $turma->id)
                ->with([
                    'ano',
                    'disciplina',
                    'trimestre',
                    'estudante',
                    'turma'
                ])
                ->get()
                ->sortBy(function ($nota) {
                    return $nota->estudante->nome;
                });
        }
        
        $disciplinasTurma = null;
        $disciplinasTotal = null;
        $estudantesTurma = null;
        
        if (isset($turma) and isset($trimestre) and isset($disciplina) and isset($request->condicao_pesquisar)) {
            $disciplinasTurma = DisciplinaTurma::where('turmas_id', $turma->id)->get();
            $disciplinasTotal = DisciplinaTurma::where('turmas_id', $turma->id)->count();
            $estudantesTurma = EstudantesTurma::with(['estudante'])
                ->where('turmas_id', $turma->id)
                ->get()
                ->sortBy(function ($nota) {
                    return $nota->estudante->nome;
                });
        }

        $headers = [
            "titulo" => "Mini Pautas",
            "descricao" => env('APP_NAME'),
            "turma" => $turma,
            "pesquisa_trimestre" => $trimestre,
            "pesquisa_disciplina" => $disciplina,
            "pesquisa_escola" => $escola,
            "pesquisa_ano_lectivo" => $anolectivo,
            
            "trimestre1" => $trimestre1,
            "trimestre2" => $trimestre2,
            "trimestre3" => $trimestre3,
            "notas" => $notas,
            "disciplinasTurma" => $disciplinasTurma,
            "disciplinasTotal" => $disciplinasTotal,
            "estudantesTurma" => $estudantesTurma,

            "pesquisa_condicao" => $request->input('condicao_pesquisar'),
            "pesquisa_ano" => $request->input('ano_lectivos_id'),
            "trimestres" => $trimestres,
            "escolas" => Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->where('status', 'activo')->get(),
        ];

        return view('sistema.direccao-municipal.planificacao.mini-pauta', $headers);
    }


    public function miniPautaGeral()
    {

        $user = auth()->user();

        // if(!$user->can('create: ensino')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }


        $headers = [
            "titulo" => "Mini Pautas Geral",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "escolaId" => "",
            "escolas" => Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->where('status', 'activo')->get(),
        ];

        return view('sistema.direccao-municipal.planificacao.mini-pauta-geral', $headers);
    }


    // pesquisar MINI Pautas Gerais para todas as turmas
    public function pesquisarMiniPautaGerais(Request $request)
    {
        $user = auth()->user();

        // if(!$user->can('create: ensino')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $request->validate([
            'escola_id' => 'required',
            'ano_lectivos_id' => 'required',
            'turmas_id' => 'required',
            'disciplinas_id' => 'required',
        ], [
            "escola_id.required" => "Campo Obrigatório",
            "ano_lectivos_id.required" => "Campo Obrigatório",
            "turmas_id.required" => "Campo Obrigatório",
            "disciplinas_id.required" => "Campo Obrigatório",
        ]);

        $turma = Turma::findOrFail($request->turmas_id);
        $disciplina = Disciplina::findOrFail($request->disciplinas_id);

        $estudantes = EstudantesTurma::where([
            ['turmas_id', '=', $turma->id]
        ])->get();

        $headers = [
            "titulo" => "Pesquisa de mini pautas gerais",
            "descricao" => env('APP_NAME'),
            "estudantes" => $estudantes,
            "turma" => $turma,
            'curso' => Curso::findOrFail($turma->cursos_id),
            'sala' => Sala::findOrFail($turma->salas_id),
            'classe' => Classe::findOrFail($turma->classes_id),
            'turno' => Turno::findOrFail($turma->turnos_id),
            'anoLectivo' => AnoLectivo::findOrFail($request->ano_lectivos_id),
            "disciplina" => $disciplina,
            "escolaId" => $request->escola_id,
            "usuario" => User::findOrFail(Auth::user()->id),
            "escolas" => Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->where('status', 'activo')->get(),
        ];

        return view('sistema.direccao-municipal.planificacao.mini-pauta-geral', $headers);
    }


    // Turmas mapa de aproveitamento
    public function mapaAproveitamentoGeral()
    {

        $user = auth()->user();

        // if(!$user->can('create: ensino')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $headers = [
            "titulo" => "Pesquisa de mini pautas gerais",
            "descricao" => env('APP_NAME'),
            "trimestres" => ControlePeriodico::where('trimestre', '<>', 'Geral')->get(),
            "usuario" => User::findOrFail(Auth::user()->id),
            "escolas" => Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->where('status', 'activo')->get(),
            "escolaId" => ""
        ];

        return view('sistema.direccao-municipal.planificacao.mapa-aproveitamento', $headers);
    }

    // turmas
    public function mapaAproveitamentoGeralCreate(Request $request)
    {
        $user = auth()->user();

        // if(!$user->can('create: ensino')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $request->validate([
            'escola_id' => 'required',
            'ano_lectivos_id' => 'required',
            'turmas_id' => 'required',
            'trimestre_id' => 'required',
        ], [
            "escola_id.required" => "Campo Obrigatório",
            "ano_lectivos_id.required" => "Campo Obrigatório",
            "turmas_id.required" => "Campo Obrigatório",
            "trimestre_id.required" => "Campo Obrigatório",
        ]);

        $turma = Turma::findOrFail($request->turmas_id);
        $trimestre = ControlePeriodico::findOrFail($request->trimestre_id);

        $estudantes = EstudantesTurma::where([
            ['turmas_id', '=', $turma->id],
        ])->get();

        $totalDisciplinas = DisciplinaTurma::where([
            ['turmas_id', '=', $turma->id]
        ])->count();

        $disciplinas = DisciplinaTurma::where([
            ['turmas_id', '=', $turma->id]
        ])->get();


        $headers = [
            "titulo" => "Mapa de aproveitamento Geral",
            "descricao" => env('APP_NAME'),
            "estudantes" => $estudantes,
            "trimestreActivo" => $trimestre,
            "turma" => $turma,
            'curso' => Curso::findOrFail($turma->cursos_id),
            'sala' => Sala::findOrFail($turma->salas_id),
            'classe' => Classe::findOrFail($turma->classes_id),
            'turno' => Turno::findOrFail($turma->turnos_id),
            'anoLectivo' => AnoLectivo::findOrFail($request->ano_lectivos_id),
            "trimestres" => ControlePeriodico::get(),
            "totalDisciplinas" => $totalDisciplinas,
            "usuario" => User::findOrFail(Auth::user()->id),
            "disciplinas" => $disciplinas,

            "escolas" => Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->where('status', 'activo')->get(),
            "escolaId" => $request->escola_id,
        ];


        return view('sistema.direccao-municipal.planificacao.mapa-aproveitamento', $headers);
    }
}
