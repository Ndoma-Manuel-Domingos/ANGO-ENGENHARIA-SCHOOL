<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\calendarios\Servico;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Shcool;
use App\Models\web\calendarios\Matricula;
use App\Models\web\calendarios\ServicoTurma;
use App\Models\web\classes\AnoLectivoClasse;
use App\Models\web\classes\Classe;
use App\Models\web\cursos\AnoLectivoCurso;
use App\Models\web\cursos\Curso;
use App\Models\web\estudantes\CartaoEstudante;
use App\Models\web\estudantes\Estudante;
use App\Models\web\turmas\Bolseiro;
use App\Models\web\turmas\EstudantesTurma;
use App\Models\web\turmas\Turma;
use App\Models\web\turnos\AnoLectivoTurno;
use App\Models\web\turnos\Turno;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GestaoDividaController extends Controller
{
    use TraitChavesSaft;
    use TraitHelpers;


    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function home(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: dividas')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $classes = AnoLectivoClasse::where('ano_lectivos_id', $request->ano_lectivos_id ?? $this->anolectivoActivo())
            ->where('shcools_id', $this->escolarLogada())
            ->with(['classe'])
            ->get();

        $turnos = AnoLectivoTurno::where('ano_lectivos_id', $request->ano_lectivos_id ?? $this->anolectivoActivo())
            ->where('shcools_id', $this->escolarLogada())
            ->with(['turno'])
            ->get();

        $cursos = AnoLectivoCurso::where('ano_lectivos_id', $request->ano_lectivos_id ?? $this->anolectivoActivo())
            ->where('shcools_id', $this->escolarLogada())
            ->with(['curso'])
            ->get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Gestão de dívidas",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            "servicos" => Servico::where('shcools_id', $this->escolarLogada())->get(),
            "anos_lectivos" => AnoLectivo::where('shcools_id', $this->escolarLogada())->get(),
            "classes" => $classes,
            "turnos" => $turnos,
            "cursos" => $cursos,
        ];

        return view('admin.financeiros.dividas.home', $headers);
    }
    
    public function index(Request $request)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $user = auth()->user();

        if (!$user->can('read: dividas')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $cursos_id = $request->cursos_id;
        $classes_id = $request->classes_id;
        $turnos_id = $request->turnos_id;
        $input_estudante = $request->input_estudante;

        $paginacao = $request->paginacao ?? 5;

        $query = CartaoEstudante::with(['estudante.matricula', 'servico', 'estudante.matricula.curso', 'estudante.matricula.classe', 'estudante.matricula.turno'])
            ->when($request->servico_id, function ($query, $value) {
                $query->where('servicos_id', $value);
            })
            ->when($request->mes, function ($query, $mes) {
                $query->whereIn('month_name', $mes);
            })
            ->when($request->condicao, function ($query, $status) {
                $query->where('status', $status);
            })
            ->whereHas('estudante', function ($query) use ($input_estudante) {
                $query->when($input_estudante, function ($query, $bilhete) {
                    $query->where('bilheite', $bilhete);
                });
                $query->where('shcools_id', $this->escolarLogada());
            })
            ->whereHas('estudante.matricula', function ($query) use ($cursos_id, $classes_id, $turnos_id) {
                $query->when($cursos_id, function ($query, $curso) {
                    $query->where('cursos_id', $curso);
                });
                $query->when($classes_id, function ($query, $classe) {
                    $query->where('classes_id', $classe);
                });
                $query->when($turnos_id, function ($query, $turno) {
                    $query->where('turnos_id', $turno);
                });
            })
        ->where('ano_lectivos_id', $request->ano_lectivos_id ?? $this->anolectivoActivo());
        
        return response()->json(
            $query->orderByDesc('id')->paginate($paginacao)
        );

    }

    public function export(Request $request)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // 4 GB
        
        $cursos_id = $request->cursos_id;
        $classes_id = $request->classes_id;
        $turnos_id = $request->turnos_id;
        $input_estudante = $request->input_estudante;

        $cartoes = CartaoEstudante::with(['estudante.matricula', 'servico'])
        ->when($request->servico_id, function ($query, $value) {
            $query->where('servicos_id', $value);
        })->when($request->mes, function ($query, $value) {
            $query->whereIn('month_name', $value);
        })
            ->when($request->condicao, function ($query, $value) {
                $query->where('status', $value);
            })
            ->whereHas('estudante', function ($query) use ($input_estudante) {
                $query->when($input_estudante, function ($query, $value) {
                    $query->where('bilheite', $value);
                });
            })
            ->whereHas('estudante.matricula', function ($query) use ($cursos_id, $classes_id, $turnos_id) {
                $query->when($cursos_id, function ($query, $value) {
                    $query->where('cursos_id', $value);
                });
                $query->when($classes_id, function ($query, $value) {
                    $query->where('classes_id', $value);
                });
                $query->when($turnos_id, function ($query, $value) {
                    $query->where('turnos_id', $value);
                });
            })
            ->where('ano_lectivos_id', $request->ano_lectivo_id ?? $this->anolectivoActivo())
            ->get()
            ->sortBy(function ($cartao) {
                return $cartao->estudante->nome; // Ordena pela propriedade 'nome' do estudante
            });

        if ($request->condicao == "Nao Pago") {
            $titulo = "LISTA DOS ESTUDANTES COM SERVIÇOS NÃO PAGO";
        } else if ($request->condicao == "Pago")
            $titulo = "LISTA DOS ESTUDANTES COM SERVIÇOS PAGO";
        else if ($request->condicao == "divida") {
            $titulo = "LISTA DE ESTUDANTES DEVEDORES";
        } else {
            $titulo = "LISTA DOS ESTUDANTES CONTROLE FINANCEIRO";
        }

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "titulo" => $titulo,
            "mes" => $request->mes,
            "condicao" => $request->condicao,
            "cartoes" => $cartoes,
            "ano_lectivo" => AnoLectivo::find($request->ano_lectivo_id ?? $this->anolectivoActivo()),
            "curso" => Curso::find($request->cursos_id),
            "classe" => Classe::find($request->classes_id),
            "turno" => Turno::find($request->turnos_id),
            "servico" => Servico::find($request->servico),
            "requests" => $request->all('ano_lectivos_id', 'servico', 'mes', 'condicao', 'cursos_id', 'classes_id', 'turnos_id', 'input_estudante')
        ];
                        
        $documentType = $request->documentType;
        
        if($documentType === 'excel') {
            //return Excel::download(new SalaExport, 'salas.xlsx');
        } else {
            $pdf = \PDF::loadView('downloads.relatorios.lista-estudantes-devedores', $headers);
            return $pdf->stream('lista-estudantes-devedores.pdf');
        }
    }

    public function mudar_status($id, $status) 
    {
        try {
        
            $cartao = CartaoEstudante::findOrFail($id);
                
            $servicosPropina = Servico::where('servico', 'Propinas')
                ->where('shcools_id', $this->escolarLogada())
            ->first();
            
            $cartoes = CartaoEstudante::with(['servico'])->where('estudantes_id', $cartao->estudantes_id)
                ->where('ano_lectivos_id', $this->anolectivoActivo())
                ->where('mes_id', "M")
                ->where('servicos_id', $servicosPropina->id)
            ->get();
            
            $bolseiro = Bolseiro::with(['instituicao','bolsa', 'instituicao_bolsa', 'ano', 'periodo', 'estudante', 'escola'])
                ->where('ano_lectivos_id', $this->anolectivoActivo())
                ->where('status', 'activo')
                ->where('estudante_id', $cartao->estudantes_id)
            ->first();
    
            $estudantes = Estudante::findOrFail($cartao->estudantes_id);

            DB::beginTransaction();

            $cartao->status = $status;
            $cartao->save();

            DB::commit();
            
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
        
        return response()->json([
            "message" => "Status atualizado com sucesso!",
            "cartao" => $cartao,
            "bolseiro" => $bolseiro,
            "estudantes" => $estudantes,
            "cartoes" => $cartoes
        ]);
    }
    
    public function show($id)
    {
        $user = auth()->user();

        if (!$user->can('read: matricula') && !$user->can('read: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $estudantes = Estudante::findOrFail($id);

        $servicosPropina = Servico::where('servico', 'Propinas')
            ->where('shcools_id', $this->escolarLogada())
        ->first();

        $cartoes = CartaoEstudante::with(['servico'])->where('estudantes_id', $estudantes->id)
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('mes_id', "M")
            ->where('servicos_id', $servicosPropina->id)
        ->get();

        $matricula = Matricula::where("estudantes_id", $estudantes->id)
            ->where("ano_lectivos_id", $this->anolectivoActivo())
            ->whereIn("status_matricula", ["confirmado", "desistente", "inactivo", "falecido", "rejeitado"])
            ->where("shcools_id", $this->escolarLogada())
        ->first();

        $estudanteTurma = EstudantesTurma::where('estudantes_id', $estudantes->id)
            ->where('ano_lectivos_id', $this->anolectivoActivo())
        ->first();

        if (!$estudanteTurma) {
            Alert::warning("Informação", "Infelizmente não pode acessar esta área porque estudante não esta inserido em nenhuma turma!");
            return redirect()->back();
        }
        
        // $servicos =(new App\Models\web\estudantes\CartaoEstudante())::with('servico')->where([
        //     ['servicos_id', '=', $item->servicos_id],
        //     ['estudantes_id', '=', $estudante->id],
        //     ['ano_lectivos_id', '=', $ano->id],
        // ])
        // ->get();

        $servicosTurma = ServicoTurma::where('turmas_id', $estudanteTurma->turmas_id)
            ->where('model', 'turmas')
            ->where('pagamento', 'mensal')
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->with(['servico'])
        ->get();


        $turma = Turma::findOrFail($estudanteTurma->turmas_id);
        
        $bolseiro = Bolseiro::with(['instituicao','bolsa', 'instituicao_bolsa', 'ano', 'periodo', 'estudante', 'escola'])
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('status', 'activo')
            ->where('estudante_id', $estudantes->id)
        ->first();

        return response()->json([
            "estudante" => $estudantes,
            "matricula" => $matricula,
            "bolseiro" => $bolseiro,
            "cartoes" => $cartoes,
            "ano" => AnoLectivo::findOrFail($this->anolectivoActivo()),
            "turma" => $turma,
            "servicosTurma" => $servicosTurma,
            "servicosPropina" => $servicosPropina,
        ]);

        return view('admin.estudantes.situacao-financeria', $headers);
    }


}
