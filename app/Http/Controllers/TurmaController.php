<?php

namespace App\Http\Controllers;

use App\Models\ControloLancamentoNotas;
use App\Models\ControloLancamentoNotasEscolas;
use App\Models\Efeito;
use App\Models\Professor;
use App\Models\User;
use App\Models\Shcool;
use App\Charts\PagamentoChart;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\anolectivo\ControlePeriodico;
use App\Models\web\anolectivo\Trimestre;
use App\Models\web\calendarios\CartaoEscola;
use App\Models\web\calendarios\ListaPresenca;
use App\Models\web\calendarios\MapaEfectividade;
use App\Models\web\calendarios\Matricula;
use App\Models\web\calendarios\Semana;
use App\Models\web\calendarios\Servico;
use App\Models\web\calendarios\ServicoTurma;
use App\Models\web\calendarios\Tempo;
use App\Models\web\classes\AnoLectivoClasse;
use App\Models\web\classes\Classe;
use App\Models\web\cursos\AnoLectivoCurso;
use App\Models\web\cursos\DisciplinaCurso;
use App\Models\web\disciplinas\Disciplina;
use App\Models\web\estudantes\CartaoEstudante;
use App\Models\web\estudantes\Estudante;
use App\Models\web\funcionarios\FuncionariosControto;
use App\Models\web\salas\AnoLectivoSala;
use App\Models\web\turmas\DisciplinaTurma;
use App\Models\web\turmas\EstudantesTurma;
use App\Models\web\turmas\FuncionariosTurma;
use App\Models\web\turmas\Horario;
use App\Models\web\turmas\NotaPauta;
use App\Models\web\turmas\Turma;
use App\Models\web\turmas\TurmaMeses;
use App\Models\web\turnos\AnoLectivoTurno;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt; //graficos
use Khill\Lavacharts\Lavacharts;

class TurmaController extends Controller
{
    use TraitHelpers;
    use TraitHeader;


    public function __construct()
    {
        $this->middleware('auth');
    }


    // view turmas principal
    public function turmas()
    {
        $user = auth()->user();

        if (!$user->can('read: turma')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        // Carreegar Turmas
        $turmas = Turma::where('ano_lectivos_id', $this->anolectivoActivo())
            ->with(['escola', 'anolectivo', 'turno', 'classe', 'sala', 'curso'])
            ->get();



        $classes = AnoLectivoClasse::where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('shcools_id', $this->escolarLogada())
            ->with(['classe'])
            ->get();

        $turnos = AnoLectivoTurno::where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('shcools_id', $this->escolarLogada())
            ->with(['turno'])
            ->get();

        $salas = AnoLectivoSala::where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('shcools_id', $this->escolarLogada())
            ->with(['sala'])
            ->get();


        $cursos = AnoLectivoCurso::where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('shcools_id', $this->escolarLogada())
            ->with(['curso'])
            ->get();


        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "usuario" => User::findOrFail(Auth::user()->id),

            "classes" => $classes,
            "turnos" => $turnos,
            "salas" => $salas,
            "cursos" => $cursos,

            "anolectivos" => AnoLectivo::where('shcools_id', $this->escolarLogada())
                ->where('status', 'activo')
                ->get(),

            "turmas" => $turmas,

        ];

        return view('admin.turmas.home', $headers);
    }

    function obterMesesEntre($dataInicial)
    {
        // Limpar a string de data, removendo espaços extras
        $dataInicial = trim($dataInicial);

        // Garantir que a data esteja no formato correto, caso contrário tentar normalizar
        $formatado = false;

        try {
            // Tenta criar o Carbon a partir do formato 'd-m-Y'
            $dataInicial = Carbon::createFromFormat('d-m-Y', $dataInicial);
            $formatado = true; // Caso funcione, o formato é válido
        } catch (\Exception $e) {
            // Se falhar, tenta converter para 'Y-m-d' (padrão ISO) antes de tentar novamente
            try {
                $dataInicial = Carbon::createFromFormat('Y-m-d', $dataInicial);
                $formatado = true;
            } catch (\Exception $e2) {
                // Se ainda assim falhar, exibe a mensagem de erro
                return 'Erro ao criar data: ' . $e2->getMessage();
            }
        }

        // Se a data foi formatada corretamente, continuar com o processamento
        if ($formatado) {
            // Obter o mês atual no formato M (Exemplo: "Nov")
            $mesAtual = Carbon::now()->format('M');

            // Array para armazenar os meses
            $meses = [];

            // Iterar de $dataInicial até o mês atual
            while ($dataInicial->format('M') != $mesAtual) {
                // Adicionar o mês formatado à lista
                $meses[] = $dataInicial->format('M');

                // Avançar para o próximo mês
                $dataInicial->addMonth();
            }

            // Adicionar o mês atual
            $meses[] = $mesAtual;

            return $meses;
        }

        return "Erro ao formatar a data inicial!";
    }

    function obterDatasDoMes($anoInicial, $anoAtual, $mesAbreviado)
    {
        // Converter o nome abreviado do mês para o número do mês
        $mes = Carbon::parse($mesAbreviado . ' 1')->month;

        // Se o mês for maior do que o mês atual, ajusta o ano para o ano anterior
        if ($mes > date('m')) {
            $ano = $anoAtual - 1;
        } else {
            $ano = $anoAtual;
        }

        // Criar a data de início (primeiro dia do mês)
        $dataInicio = Carbon::create($ano, $mes, 1);

        // Criar a data de fim (último dia do mês)
        $dataFim = $dataInicio->copy()->endOfMonth();

        // Retornar as datas no formato 'Y-m-d'
        return [
            'data_inicio' => $dataInicio->format('Y-m-d'),
            'data_fim' => $dataFim->format('Y-m-d'),
        ];
    }

    // view turmas principal
    public function actualizar_multas_geral($id)
    {
        $turma = Turma::with(['curso', 'turno', 'classe', 'anolectivo'])->findOrFail(Crypt::decrypt($id));
        $ano_lectivo = AnoLectivo::findOrFail($this->anolectivoActivo());
        // Exemplo de uso
        $mesesFiltrados = $this->obterMesesEntre($ano_lectivo->inicio); // Data inicial "09-10-2024"
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $estudantes = EstudantesTurma::with('turma')->where('turmas_id', $turma->id)->where('ano_lectivos_id', $this->anolectivoActivo())->get();

            if ($estudantes) {
                foreach ($estudantes as $estudante) {
                    $cartoes = CartaoEstudante::with('servico')
                        ->where('estudantes_id', $estudante->estudantes_id)
                        ->whereNotIn('status', ['Pago', 'Isento'])
                        ->where('ano_lectivos_id', $this->anolectivoActivo())
                        ->where('mes_id', 'M')
                        ->whereIn('month_name', $mesesFiltrados)
                        ->get();

                    if (!empty($cartoes)) {
                        foreach ($cartoes as $cartao) {
                            $cart = CartaoEstudante::findOrFail($cartao->id);
                            $servico_turma = ServicoTurma::where('turmas_id', $estudante->turmas_id)->where('servicos_id', $cart->servicos_id)->where('ano_lectivos_id', $this->anolectivoActivo())->first();

                            if (date('Y-m-d') > $cart->data_exp) {

                                $data_primeira_taxa = date('Y-m-d', strtotime($cart->data_exp . "+{$estudante->turma->taxa_multa1_dia}days"));
                                $data_segunda_taxa = date('Y-m-d', strtotime($cart->data_exp . "+{$estudante->turma->taxa_multa2_dia}days"));
                                $data_terceira_taxa = date('Y-m-d', strtotime($cart->data_exp . "+{$estudante->turma->taxa_multa3_dia}days"));

                                $multa1 = 0;
                                $multa2 = 0;
                                $multa3 = 0;

                                if ($cart->multa1 == 'N') {
                                    if (date('Y-m-d') > $data_primeira_taxa && $data_primeira_taxa != $cart->data_exp) {
                                        $multa1 = $servico_turma->preco * ($servico_turma->taxa_multa1 / 100);
                                        $status_multa1 = 'Y';
                                    } else {
                                        $status_multa1 = 'N';
                                    }
                                }

                                if ($cart->multa2 == 'N') {
                                    if (date('Y-m-d') > $data_segunda_taxa && $data_segunda_taxa != $cart->data_exp) {
                                        $multa1 = 0;
                                        $multa2 = $servico_turma->preco * ($servico_turma->taxa_multa2 / 100);
                                        $status_multa2 = 'Y';
                                    } else {
                                        $status_multa2 = 'N';
                                    }
                                }

                                if ($cart->multa3 == 'N') {
                                    if (date('Y-m-d') > $data_terceira_taxa && $data_terceira_taxa != $cart->data_exp) {
                                        $multa3 = $servico_turma->preco * ($servico_turma->taxa_multa3 / 100);
                                        $multa1 = 0;
                                        $multa2 = 0;
                                        $status_multa3 = 'Y';
                                    } else {
                                        $status_multa3 = 'N';
                                    }
                                }

                                $multa_final = $multa1 + $multa2 + $multa3;

                                $cart->status = 'divida';
                                $cart->preco_unitario = $servico_turma->preco;
                                $cart->multa1 = $status_multa1;
                                $cart->multa2 = $status_multa2;
                                $cart->multa3 = $status_multa3;
                                $cart->multa = $multa_final;
                                $cart->update();
                            }
                        }
                    }
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

        Alert::success('Bom Trabalho', 'Multas actualizadas com sucesso!');
        return redirect()->back();
    }

    // criar Grade Curricular Turma
    public function criarGradeCurricularTurmas($id)
    {
        ini_set('max_execution_time', '240'); // Aumenta para 120 segundos
        ini_set('memory_limit', '4096M');

        $turma = Turma::with(['curso', 'turno', 'classe', 'anolectivo'])->findOrFail(Crypt::decrypt($id));
        

        try {
            DB::beginTransaction();

            $lista_estudantes = EstudantesTurma::with(['estudante', 'turma'])->where("turmas_id", $turma->id)->get();
            $lista_disciplinas = DisciplinaTurma::with(['disciplina'])->where('turmas_id', $turma->id)->get();

            $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

            if ($escola->ensino->nome == "Ensino Superior") {
                $trimestres = ControlePeriodico::where('ensino_status', '2')->get();
            } else {
                $trimestres = ControlePeriodico::where('ensino_status', '1')->get();
            }

            $status = null;

            if ($turma->grade_curricular == true) {
                $status = false;
                NotaPauta::where('turmas_id', $turma->id)->delete();
            } else {
                $status = true;
                if ($lista_estudantes) {
                    if ($lista_disciplinas) {
                        if ($trimestres) {
                            foreach ($lista_estudantes as $estudante) {
                                foreach ($lista_disciplinas as $disciplina) {
                                    foreach ($trimestres as $trimestre) {
                                        $verificar_notas = NotaPauta::where('estudantes_id', $estudante->estudantes_id)
                                            ->where('ano_lectivos_id', $turma->ano_lectivos_id)
                                            ->where('shcools_id', $this->escolarLogada())
                                            ->where('controlo_trimestres_id', $trimestre->id)
                                            ->where('turmas_id', $turma->id)
                                            ->where('disciplinas_id', $disciplina->disciplinas_id)
                                            ->first();

                                        if (!$verificar_notas) {
                                            $this->criar_pauta_curricular($turma->id, $estudante->estudantes_id, $turma->ano_lectivos_id, $trimestre->id, $disciplina->disciplinas_id);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $turma->grade_curricular = $status;
            $turma->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        Alert::success('Bom Trabalho', 'Operação realizado com sucesso!');
        return redirect()->back();
    }

    //actualizar Grade Curricular Turma
    public function actualizarGradeCurricularTurmas($id)
    {
        ini_set('max_execution_time', '240'); // Aumenta para 120 segundos
        ini_set('memory_limit', '4096M');

        $turma = Turma::with(['curso', 'turno', 'classe', 'anolectivo'])->findOrFail(Crypt::decrypt($id));

        try {
            DB::beginTransaction();

            $lista_estudantes = EstudantesTurma::with(['estudante', 'turma'])->where("turmas_id", $turma->id)->get();
            $lista_disciplinas = DisciplinaTurma::with(['disciplina'])->where('turmas_id', $turma->id)->get();

            $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

            if ($escola->ensino->nome == "Ensino Superior") {
                $trimestres = ControlePeriodico::where('ensino_status', '2')->get();
            } else {
                $trimestres = ControlePeriodico::where('ensino_status', '1')->get();
            }

            if ($lista_estudantes) {
                if ($lista_disciplinas) {
                    if ($trimestres) {
                        foreach ($lista_estudantes as $estudante) {
                            foreach ($lista_disciplinas as $disciplina) {
                                foreach ($trimestres as $trimestre) {
                                    $verificar_notas = NotaPauta::where('estudantes_id', $estudante->estudantes_id)
                                        ->where('ano_lectivos_id', $turma->ano_lectivos_id)
                                        ->where('shcools_id', $this->escolarLogada())
                                        ->where('controlo_trimestres_id', $trimestre->id)
                                        ->where('turmas_id', $turma->id)
                                        ->where('disciplinas_id', $disciplina->disciplinas_id)
                                        ->first();

                                    if (!$verificar_notas) {
                                        $this->criar_pauta_curricular($turma->id, $estudante->estudantes_id, $turma->ano_lectivos_id, $trimestre->id, $disciplina->disciplinas_id);
                                    }
                                }
                            }
                        }
                    }
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

        Alert::success('Bom Trabalho', 'Operação realizado com sucesso!');
        return redirect()->back();
    }

    // view turmas principal
    public function encerrar_ano_lectivo($id, $status = null)
    {
        $turma = Turma::with(['curso', 'turno', 'classe', 'anolectivo'])->findOrFail(Crypt::decrypt($id));

        $ano_lectivo = AnoLectivo::find($this->anolectivoActivo());

        $disciplinas = DisciplinaTurma::with(['disciplina'])->where('turmas_id', $turma->id)->get();
        $disciplinas_ids = DisciplinaTurma::with(['disciplina'])->where('turmas_id', $turma->id)->pluck('disciplinas_id');
        $estudantes = EstudantesTurma::with(['estudante'])->where('turmas_id', $turma->id)->get();

        $trimestre1 = Trimestre::where('trimestre', 'Iª Trimestre')->first();
        $trimestre2 = Trimestre::where('trimestre', 'IIª Trimestre')->first();
        $trimestre3 = Trimestre::where('trimestre', 'IIIª Trimestre')->first();
        $trimestre4 = Trimestre::where('trimestre', 'Geral')->first();

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        if ($status == "encerrar") {

            foreach ($estudantes as $key => $estudante) {
                $soma_mfd = 0;
                $total_disciplina = count($disciplinas);

                foreach ($disciplinas as $disciplina) {
                    $notas_t_4 = NotaPauta::where([['turmas_id', '=', $turma->id], ['disciplinas_id', '=', $disciplina->disciplina->id], ['estudantes_id', '=', $estudante->estudante->id], ['ano_lectivos_id', '=', $ano_lectivo->id], ['controlo_trimestres_id', '=', $trimestre4->id]])->first();
                    $soma_mfd += $notas_t_4->mfd;
                }

                if ($notas_t_4->arredondar(($soma_mfd ?? 0) / ($total_disciplina ?? 0)) >= 10) {
                    $resultado = "aprovado";
                }

                if ($notas_t_4->arredondar(($soma_mfd ?? 0) / ($total_disciplina ?? 0)) < 10) {
                    $resultado = "reprovado";
                }

                $matricula = Matricula::where('estudantes_id', $estudante->id)->where('status_inscricao', 'Admitido')->where('status_matricula', 'confirmado')->where('ano_lectivos_id', $ano_lectivo->id)->first();

                if ($matricula) {
                    $update = Matricula::findOrFail($matricula->id);
                    $update->resultado_final = $resultado;
                    $update->finalista = 'Y';
                    $update->update();
                }
            }

            $turma->finalista = "Y";
            $turma->update();

            Alert::success('Bom Trabalho', 'Ano Lectivo Encerrado para esta turma com sucesso!');
            return redirect()->back();
        }

        $headers = [
            "escola" => $escola,
            'titulo' => "Encerramento Ano Lectivo para turma: {$turma->turma}",

            "usuario" => User::findOrFail(Auth::user()->id),

            "disciplinas" => $disciplinas,
            "estudantes" => $estudantes,
            "turma" => $turma,
            "ano_lectivo" => $ano_lectivo,

            "trimestre1" => $trimestre1 ?? 0,
            "trimestre2" => $trimestre2 ?? 0,
            "trimestre3" => $trimestre3 ?? 0,
            "trimestre4" => $trimestre4 ?? 0,

        ];

        return view('admin.turmas.encerramento-ano-lectivo', $headers);
    }

    // view turmas principal
    public function horarios(Request $request)
    {
        $professores = FuncionariosControto::where('tb_contratos.shcools_id', $this->escolarLogada())
            ->where('tb_contratos.status', 'activo')
            ->join('tb_professores', 'tb_contratos.funcionarios_id', '=', 'tb_professores.id')
            ->select('tb_professores.nome', 'tb_professores.sobre_nome', 'tb_professores.id')
        ->get();


        $turmas = Turma::where('ano_lectivos_id', '=', $this->anolectivoActivo())
            ->with(['escola', 'anolectivo', 'turno', 'classe', 'sala', 'curso'])
            ->get();

        /**
         * discipina da turma
         */
        $disciplinas = DisciplinaTurma::when($request->turmas_id, function ($query, $value) {
            $query->where('turmas_id', $value);
        })
            ->with('disciplina')
            ->get();

        /** Horario da turma */
        $horarios = Horario::with(["disciplina", "turma", "professor", "tempo", "semana"])
            ->where('shcools_id', $this->escolarLogada())
            ->get();

        $tempos = Tempo::get();
        $semanas = Semana::where('status', 'activo')->get();

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        $headers = [
            "escola" => $escola,
            "usuario" => User::findOrFail(Auth::user()->id),
            // "turma" => $turma,
            "turmas" => $turmas,
            "disciplinas" => $disciplinas,
            "horarios" => $horarios,
            "professores" => $professores,
            "tempos" => $tempos,
            "semanas" => $semanas,
        ];

        return view('admin.turmas.horarios', $headers);
    }

    // view turmas principal
    public function adicionarEstuantesTurmas($id)
    {

        $turma = Turma::findOrFail(Crypt::decrypt($id));

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        $turmas = Turma::where('classes_id', $turma->classes_id)
            ->where('turnos_id', $turma->turnos_id)
            ->where('cursos_id', $turma->cursos_id)
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->pluck('id');

        $estudantes = EstudantesTurma::whereIn("turmas_id", $turmas)->pluck('estudantes_id');

        $matriculas = Matricula::whereIn('status_inscricao', ['Admitido'])
            ->whereNotIn('estudantes_id', $estudantes)
            ->whereIn('status_matricula', ['confirmado'])

            ->where('classes_id', '=', $turma->classes_id)
            ->where('turnos_id', '=', $turma->turnos_id)
            ->where('cursos_id', '=', $turma->cursos_id)

            ->where('ano_lectivos_id', '=', $this->anolectivoActivo())
            ->where('shcools_id', '=', $this->escolarLogada())
            ->with(['escolas', 'ano_lectivo', 'classe_at', 'estudante', 'classe', 'turno', 'curso'])
            ->get()
            ->sortBy(function ($matricula) {
                return $matricula->estudante->nome;
            });

        $headers = [
            "escola" => $escola,

            "usuario" => User::findOrFail(Auth::user()->id),
            "turma" => $turma,
            "matriculas" => $matriculas,
        ];

        return view('admin.turmas.adicionar-estudantes', $headers);
    }

    // view turmas principal
    public function adicionarEstuantesTurmasStore(Request $request)
    {
        // Validação básica
        $request->validate([
            'estudantes_id' => 'required|array|min:1'
        ]);

        $turma = Turma::findOrFail($request->turma_id);

        if (count($request->estudantes_id) == 0) {
            Alert::warning('Informação', "Seleciona estudantes que pretende adicionar neste turma!");
            return redirect()->back();
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());
            $ano_lectivo = AnoLectivo::findOrFail($this->anolectivoActivo());


            if ($escola->ensino->nome == "Ensino Superior") {
                $trimestres = ControlePeriodico::where('ensino_status', '2')->get();
            } else {
                $trimestres = ControlePeriodico::where('ensino_status', '1')->get();
            }

            foreach ($request->estudantes_id as $item) {

                $matricula = Matricula::findOrFail($item);
                $estudante = Estudante::findOrFail($matricula->estudantes_id);

                if ($turma) {
                    $anoLectivoAnterior = AnoLectivo::find($this->anolectivoAnterior($ano_lectivo->id));

                    if ($escola->ensino->nome == "Ensino Superior") {
                        // Aqui vai se criar as pautas das classes anteriores caso ele não estudava neste instituição
                        if ($anoLectivoAnterior) {
                            // Aqui vai se criar as pautas das classes anteriores caso ele não estudava neste instituição
                            $_classe = Classe::findOrFail($turma->classes_id);
                            // precisamos verificar se tem as notas da 10 classe caso não vamos preenchar ou criar pauta vazias
                            if (strtolower($_classe->classes) == "2º ano") {
                                $classes_1_ano = Classe::where('classes', '1º ano')->first();
                                $this->inserir_turmas_pautas_anterior($estudante->id, $classes_1_ano->id, $matricula->cursos_id,  $this->anolectivoAnterior($ano_lectivo->id), $trimestres);
                            }
                        }
                    } else {
                        // Aqui vai se criar as pautas das classes anteriores caso ele não estudava neste instituição
                        if ($anoLectivoAnterior) {
                            // tambem temos que verificar as notas antiores do estudante caso ele nunca tinha sido estudante provavelmwnte tem notas passada que vão precisar ser inserido na ano passado
                            $_classe = Classe::findOrFail($turma->classes_id);
                            // precisamos verificar se tem as notas da 10 classe caso não vamos preenchar ou criar pauta vazias
                            if (strtolower($_classe->classes) == "11ª classe") {
                                $classes_10 = Classe::where('classes', '10ª classe')->first();
                                $this->inserir_turmas_pautas_anterior($estudante->id, $classes_10->id, $matricula->cursos_id,  $this->anolectivoAnterior($ano_lectivo->id), $trimestres);
                            }

                            if (strtolower($_classe->classes) == "12ª classe") {
                                $classes_11 = Classe::where('classes', '11ª classe')->first();
                                // inserior plano curricular deste estudante neste ano
                                $this->inserir_turmas_pautas_anterior($estudante->id, $classes_11->id, $matricula->cursos_id,  $this->anolectivoAnterior($ano_lectivo->id), $trimestres);
                                $anoLectivoAnteAnterior = AnoLectivo::find($this->anolectivoAntesAnterior($ano_lectivo->id));
                                if ($anoLectivoAnteAnterior) {
                                    $classes_10 = Classe::where('classes', '10ª classe')->first();
                                    $this->inserir_turmas_pautas_anterior($estudante->id, $classes_10->id, $matricula->cursos_id,  $this->anolectivoAntesAnterior($ano_lectivo->id), $trimestres);
                                }
                            }

                            // ENSINO SECUNDARIO
                            if (strtolower($_classe->classes) == "8ª classe") {
                                $classes_7 = Classe::where('classes', '7ª classe')->first();
                                $this->inserir_turmas_pautas_anterior($estudante->id, $classes_7->id, $matricula->cursos_id,  $this->anolectivoAnterior($ano_lectivo->id), $trimestres);
                            }

                            if (strtolower($_classe->classes) == "9ª classe") {
                                $classes_8 = Classe::where('classes', '8ª classe')->first();
                                $this->inserir_turmas_pautas_anterior($estudante->id, $classes_8->id, $matricula->cursos_id,  $this->anolectivoAnterior($ano_lectivo->id), $trimestres);

                                $anoLectivoAnteAnterior = AnoLectivo::find($this->anolectivoAntesAnterior($ano_lectivo->id));
                                if ($anoLectivoAnteAnterior) {
                                    $classes_7 = Classe::where('classes', '7ª classe')->first();
                                    $this->inserir_turmas_pautas_anterior($estudante->id, $classes_7->id, $matricula->cursos_id,  $this->anolectivoAntesAnterior($ano_lectivo->id), $trimestres);
                                }
                            }
                        }
                    }

                    $this->inserir_turmas_pautas_anterior($estudante->id, $turma->classes_id, $matricula->cursos_id,  $ano_lectivo->id, $trimestres, $turma->id);

                    $servicos = ServicoTurma::where("turmas_id", $turma->id)
                        ->where("model", "turmas")
                        ->where("ano_lectivos_id", $ano_lectivo->id)
                        ->with(["servico"])
                        ->get();

                    if ($servicos) {
                        foreach ($servicos as $servico) {
                            if ($servico->pagamento == "mensal") {
                                if ($matricula->condicao == "Isento" and $servico->servico->servico == "Propinas") {
                                    // verificar se o estudante isento ja tem este servico para n\ao lhe permitir ter esse servico duas vezes
                                    $verificarServicosEstudante = CartaoEstudante::where('estudantes_id', $estudante->id)
                                        ->where('servicos_id', $servico->servicos_id)
                                        ->where('ano_lectivos_id', $ano_lectivo->id)
                                        ->first();

                                    if ($escola->ensino->nome == "Ensino Superior") {
                                        $controle_periodico = 7;
                                    } else {
                                        $controle_periodico = 4;
                                    }

                                    if (!$verificarServicosEstudante) {
                                        CartaoEstudante::create([
                                            "mes_id" => "M",
                                            "estudantes_id" => $estudante->id,
                                            "servicos_id" => $servico->servicos_id,
                                            "preco_unitario" => $servico->preco,
                                            "data_at" => $servico->data_inicio,
                                            "data_exp" => $servico->data_final,
                                            "multa" => 0,
                                            "month_number" => date("m", strtotime($servico->data_inicio)),
                                            "month_name" =>  date("M", strtotime($servico->data_inicio)),
                                            "controle_periodico_id" => $controle_periodico,
                                            "ano_lectivos_id" => $ano_lectivo->id,
                                            "status" => 'Isento',
                                        ]);
                                    } else {
                                        $upt = CartaoEstudante::findOrFail($verificarServicosEstudante->id);
                                        $upt->preco_unitario = $servico->preco;
                                        $upt->update();
                                    }
                                } else {
                                    // meses
                                    $meses = $this->cartao_estudantes_meses(
                                        $ano_lectivo->inicio,
                                        $servico->intervalo_pagamento_inicio,
                                        $servico->intervalo_pagamento_final
                                    );

                                    foreach ($meses as $mes) {
                                        $verificarServicosEstudante = CartaoEstudante::where("estudantes_id", $estudante->id)
                                            ->where("servicos_id", $servico->servicos_id)
                                            ->where("month_number", $mes['mes'])
                                            ->where("month_name", $mes['sigla'])
                                            ->where("ano_lectivos_id", $ano_lectivo->id)
                                            ->first();

                                        if (!$verificarServicosEstudante) {

                                            if ($escola->ensino->nome == "Ensino Superior") {
                                                $controle_periodico = $this->mes_periodico($mes["sigla"], "M", "universidade");
                                            } else {
                                                $controle_periodico = $this->mes_periodico($mes["sigla"], "M", "geral");
                                            }

                                            CartaoEstudante::create([
                                                "mes_id" => "M",
                                                "estudantes_id" => $estudante->id,
                                                "servicos_id" => $servico->servicos_id,
                                                "preco_unitario" => $servico->preco,
                                                "data_at" => $mes['inicio'],
                                                "data_exp" => $mes['fim'],
                                                "month_number" => $mes["mes"],
                                                "month_name" => $mes["sigla"],
                                                "multa" => 0,
                                                "controle_periodico_id" => $controle_periodico,
                                                "status_2" => "Normal",
                                                "ano_lectivos_id" => $ano_lectivo->id,
                                                "status" => "Nao Pago",
                                            ]);
                                        } else {
                                            $upt = CartaoEstudante::findOrFail($verificarServicosEstudante->id);
                                            $upt->preco_unitario = $servico->preco;
                                            $upt->update();
                                        }
                                    }
                                }
                            } else
                            if ($servico->pagamento == "unico") {

                                $verificarServicosEstudante = CartaoEstudante::where('estudantes_id', $estudante->id)
                                    ->where('servicos_id', $servico->servicos_id)
                                    ->where('ano_lectivos_id', $ano_lectivo->id)
                                    ->first();

                                if (!$verificarServicosEstudante) {
                                    if ($servico->servico == "Matricula") {
                                        $status = 'Pago';
                                    }
                                    if ($servico->servico == "Confirmação") {
                                        $status = 'Pago';
                                    } else {
                                        $status = 'Nao Pago';
                                    }

                                    if ($escola->ensino->nome == "Ensino Superior") {
                                        $controle_periodico = $this->mes_periodico(date("M", strtotime($servico->data_inicio)), "U", "universidade");
                                    } else {
                                        $controle_periodico = $this->mes_periodico(date("M", strtotime($servico->data_inicio)), "U", "geral");
                                    }

                                    CartaoEstudante::create([
                                        "mes_id" => "U",
                                        "estudantes_id" => $estudante->id,
                                        "servicos_id" => $servico->servicos_id,
                                        "preco_unitario" => $servico->preco,
                                        "data_at" => $servico->data_inicio,
                                        "data_exp" => $servico->data_final,
                                        "month_number" => date("m", strtotime($servico->data_inicio)),
                                        "month_name" => date("M", strtotime($servico->data_inicio)),
                                        "status" => $status,
                                        "status_2" => 'Normal',
                                        "controle_periodico_id" => $controle_periodico,
                                        "ano_lectivos_id" => $ano_lectivo->id,
                                    ]);
                                } else {
                                    $upt = CartaoEstudante::findOrFail($verificarServicosEstudante->id);
                                    $upt->preco_unitario = $servico->preco;
                                    $upt->update();
                                }
                            }
                        }
                    }
                } else {
                    Alert::warning('Informação', "Turma Invalida!");
                    return redirect()->back();
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

        Alert::success('Bom Trabalho', "Estudantes Adicionados com sucesso!");
        return redirect()->route('web.apresentar-turma-informacoes', Crypt::encrypt($turma->id));
    }

    // view turmas principal
    public function removerEstuantesTurmas($turma_id, $estudante_id)
    {
        $turma = Turma::findOrFail(Crypt::decrypt($turma_id));
        $estudante = Estudante::findOrFail(Crypt::decrypt($estudante_id));

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $verificar_estundate_na_turma = EstudantesTurma::where("turmas_id", $turma->id)
                ->where("ano_lectivos_id", $turma->ano_lectivos_id)
                ->where("estudantes_id", $estudante->id)
                ->first();

            if ($verificar_estundate_na_turma) {
                // remover as pautas de notas
                $notas = NotaPauta::where('estudantes_id', $estudante->id)
                    ->where('turmas_id', $turma->id)
                    ->where('ano_lectivos_id', $turma->ano_lectivos_id)
                    ->where('shcools_id', $this->escolarLogada())
                    ->get();

                foreach ($notas as $nota) {
                    $delete_nota = NotaPauta::findOrFail($nota->id);
                    $delete_nota->delete();
                }
                EstudantesTurma::findOrFail($verificar_estundate_na_turma->id)->delete();
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

        Alert::success('Bom Trabalho', "Estudantes Removido com sucesso!");
        return redirect()->back();
    }

    // view turmas principal
    public function turmasConfiguracao($id)
    {

        $classes = AnoLectivoClasse::where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('shcools_id', $this->escolarLogada())
            ->with(['classe'])
            ->get();

        $turnos = AnoLectivoTurno::where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('shcools_id', $this->escolarLogada())
            ->with(['turno'])
            ->get();

        $salas = AnoLectivoSala::where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('shcools_id', $this->escolarLogada())
            ->with(['sala'])
            ->get();

        $cursos = AnoLectivoCurso::where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('shcools_id', $this->escolarLogada())
            ->with(['curso'])
            ->get();

        $professores = FuncionariosControto::where('shcools_id', $this->escolarLogada())
            ->where('status', 'activo')
            ->with(['funcionario'])
            ->get();

        $turma = Turma::findOrFail(Crypt::decrypt($id));

        /**
         * discipina da turma
         */
        $disciplinas = DisciplinaTurma::where('turmas_id', $turma->id)
            ->with(['disciplina'])
            ->get();

        /** disciplinas do curso */
        $disciplinasCurso = DisciplinaCurso::where('cursos_id', $turma->cursos_id)
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->with(['disciplina'])
        ->get();
        
        /** Horario da turma */
        $horarios = Horario::with(["disciplina", "turma", "professor"])
            ->where('turmas_id', $turma->id)
            ->get();

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        if ($escola->ensino->nome == "Ensino Superior") {
            $trimestres = ControlePeriodico::where('ensino_status', '2')->get();
        } else {
            $trimestres = ControlePeriodico::where('trimestre', '<>', 'Geral')->where('ensino_status', '1')->get();
        }

        $tempos = Tempo::get();
        $semanas = Semana::where('status', 'activo')->get();

        $headers = [
            "escola" => $escola,
            "usuario" => User::findOrFail(Auth::user()->id),
            "classes" => $classes,
            "turnos" => $turnos,
            "salas" => $salas,
            "cursos" => $cursos,
            "trimestres" => $trimestres,
            "anolectivos" => AnoLectivo::where('shcools_id', $this->escolarLogada())
                ->where('status', 'activo')
                ->get(),
            "turma" => $turma,
            "disciplinas" => $disciplinas,
            "disciplinasCurso" => $disciplinasCurso,
            "horarios" => $horarios,
            "professores" => $professores,
            "tempos" => $tempos,
            "semanas" => $semanas,
        ];

        return view('admin.turmas.configuracao-turma', $headers);
    }

    // cadastrar turmas
    public function cadastrarTurmas(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('create: turma')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $validate = Validator::make($request->all(), [
            "nome_turmas" => 'required',
            "status_turmas" => 'required',
            "classes_id" => 'required',
            "turnos_id" => 'required',
            "cursos_id" => 'required',
            "salas_id" => 'required',
            "ano_lectivos_id" => 'required',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->findOrFail($this->escolarLogada());

            if ($escola->categoria == "Publico") {
                $request->valor_propina = 0;
                $request->valor_confirmacao = 0;
                $request->valor_matricula = 0;
                $valor_propina_com_iva = 0;
                $valor_confirmacao_com_iva = 0;
                $valor_matricula_com_iva = 0;

                $request->intervalo_pagamento_inicio = 0;
                $request->intervalo_pagamento_final = 0;

                $request->taxa_multa1 = 0;
                $request->taxa_multa1_dia = 0;
                $request->taxa_multa2 = 0;
                $request->taxa_multa2_dia = 0;
                $request->taxa_multa3 = 0;
                $request->taxa_multa3_dia = 0;
            } else {
                $valor_propina_com_iva = $request->valor_propina;
                $valor_confirmacao_com_iva = $request->valor_confirmacao;
                $valor_matricula_com_iva = $request->valor_matricula;
            }

            $verificarTurma = Turma::where("turma", $request->nome_turmas)
                ->where("classes_id", $request->classes_id)
                ->where("turnos_id", $request->turnos_id)
                ->where("cursos_id", $request->cursos_id)
                ->where("salas_id", $request->salas_id)
                ->where("ano_lectivos_id", $request->ano_lectivos_id)
                ->where("shcools_id", $escola->id)
                ->first();

            if ($verificarTurma) {
                return response()->json([
                    'status' => 300,
                    'message' => "Este Turma já Esta Cadastrado!",
                ]);
            }

            if ($validate->fails()) {
                return response()->json([
                    'status' => 400,
                    'errors' => $validate->messages(),
                ]);
            } else {

                $create = Turma::create([
                    "turma" => $request->nome_turmas,
                    "status" => $request->status_turmas,
                    "classes_id" => $request->classes_id,
                    "turnos_id" => $request->turnos_id,
                    "cursos_id" => $request->cursos_id,
                    "salas_id" => $request->salas_id,
                    "valor_propina" => $request->valor_propina,
                    "valor_confirmacao" => $request->valor_confirmacao,
                    "valor_matricula" => $request->valor_matricula,
                    "valor_propina_com_iva" => $valor_propina_com_iva,
                    "valor_confirmacao_com_iva" => $valor_confirmacao_com_iva,
                    "valor_matricula_com_iva" => $valor_matricula_com_iva,
                    "ano_lectivos_id" => $request->ano_lectivos_id,
                    "shcools_id" => $escola->id,
                    "numero_maximo" => $request->numero_maximo,

                    "intervalo_pagamento_inicio" => $request->intervalo_pagamento_inicio,
                    "intervalo_pagamento_final" => $request->intervalo_pagamento_final,

                    "taxa_multa1" => $request->taxa_multa1,
                    "taxa_multa1_dia" => $request->taxa_multa1_dia,
                    "taxa_multa2" => $request->taxa_multa2,
                    "taxa_multa2_dia" => $request->taxa_multa2_dia,
                    "taxa_multa3" => $request->taxa_multa3,
                    "taxa_multa3_dia" => $request->taxa_multa3_dia,
                ]);

                $ano_lectivo = AnoLectivo::findOrFail($request->ano_lectivos_id);

                $matricula = Servico::where("shcools_id", $escola->id)
                    ->where("servico", "Matricula")
                    ->first()->id;

                $confirmacao = Servico::where("shcools_id", $escola->id)
                    ->where("servico", "Confirmação")
                    ->first()->id;

                $propina = Servico::where("shcools_id", $escola->id)
                    ->where("servico", "Propinas")
                    ->first()->id;

                $diversos = Servico::where("shcools_id", $escola->id)
                    ->where("servico", "Diversos")
                    ->first()->id;

                /**
                 * Cadastro de servico de matricula Turma
                 */
                ServicoTurma::create([
                    "servicos_id" => $matricula,
                    "turmas_id" =>  $create->id,
                    "model" => "turmas",
                    "preco" => $request->valor_matricula,
                    "preco_sem_iva" => $valor_matricula_com_iva,
                    "multa" => 0,
                    "data_inicio" => $ano_lectivo->inicio,
                    "data_final" => $ano_lectivo->final,
                    "total_vezes" => NULL,
                    "desconto" => 0,

                    "intervalo_pagamento_inicio" => $request->intervalo_pagamento_inicio,
                    "intervalo_pagamento_final" => $request->intervalo_pagamento_final,

                    "taxa_multa1" => $request->taxa_multa1,
                    "taxa_multa1_dia" => $request->taxa_multa1_dia,
                    "taxa_multa2" => $request->taxa_multa2,
                    "taxa_multa2_dia" => $request->taxa_multa2_dia,
                    "taxa_multa3" => $request->taxa_multa3,
                    "taxa_multa3_dia" => $request->taxa_multa3_dia,

                    "status" => "activo",
                    "pagamento" => "unico",
                    "ano_lectivos_id" => $request->ano_lectivos_id,
                    "shcools_id" => $escola->id,
                ]);

                /**
                 * Cadastro de servico diversos da Turma
                 */
                ServicoTurma::create([
                    "servicos_id" => $diversos,
                    "turmas_id" =>  $create->id,
                    "model" => "turmas",
                    "preco" => 0,
                    "preco_sem_iva" => 0,
                    "multa" => 0,
                    "data_inicio" => $ano_lectivo->inicio,
                    "data_final" => $ano_lectivo->final,
                    "total_vezes" => NULL,
                    "desconto" => 0,

                    "intervalo_pagamento_inicio" => $request->intervalo_pagamento_inicio,
                    "intervalo_pagamento_final" => $request->intervalo_pagamento_final,

                    "taxa_multa1" => $request->taxa_multa1,
                    "taxa_multa1_dia" => $request->taxa_multa1_dia,
                    "taxa_multa2" => $request->taxa_multa2,
                    "taxa_multa2_dia" => $request->taxa_multa2_dia,
                    "taxa_multa3" => $request->taxa_multa3,
                    "taxa_multa3_dia" => $request->taxa_multa3_dia,

                    "status" => "activo",
                    "pagamento" => "unico",
                    "ano_lectivos_id" => $request->ano_lectivos_id,
                    "shcools_id" => $escola->id,
                ]);

                /**
                 * Cadastro de servico de confirmacao Turma
                 */
                ServicoTurma::create([
                    "servicos_id" => $confirmacao,
                    "turmas_id" =>  $create->id,
                    "model" => "turmas",
                    "preco" => $request->valor_confirmacao,
                    "preco_com_iva" => $valor_confirmacao_com_iva,
                    "multa" => 0,
                    "intervalo_pagamento_inicio" => $request->intervalo_pagamento_inicio,
                    "intervalo_pagamento_final" => $request->intervalo_pagamento_final,

                    "taxa_multa1" => $request->taxa_multa1,
                    "taxa_multa1_dia" => $request->taxa_multa1_dia,
                    "taxa_multa2" => $request->taxa_multa2,
                    "taxa_multa2_dia" => $request->taxa_multa2_dia,
                    "taxa_multa3" => $request->taxa_multa3,
                    "taxa_multa3_dia" => $request->taxa_multa3_dia,
                    "data_inicio" => $ano_lectivo->inicio,
                    "data_final" => $ano_lectivo->final,
                    "total_vezes" => NULL,
                    "desconto" => 0,
                    "status" => "activo",
                    "pagamento" => "unico",
                    "ano_lectivos_id" => $request->ano_lectivos_id,
                    "shcools_id" => $escola->id,
                ]);

                /**
                 * Cadastro de servico de propina Turma
                 */

                ServicoTurma::create([
                    "servicos_id" => $propina,
                    "turmas_id" =>  $create->id,
                    "model" => "turmas",
                    "preco" => $request->valor_propina,
                    "preco_com_iva" => $valor_propina_com_iva,
                    "multa" => 0,

                    "data_inicio" => $ano_lectivo->inicio,
                    "data_final" => $ano_lectivo->final,

                    "total_vezes" => 12,
                    "intervalo_pagamento_inicio" => $request->intervalo_pagamento_inicio,
                    "intervalo_pagamento_final" => $request->intervalo_pagamento_final >= 28 ? $request->intervalo_pagamento_final : 28,

                    "taxa_multa1" => $request->taxa_multa1,
                    "taxa_multa1_dia" => $request->taxa_multa1_dia,
                    "taxa_multa2" => $request->taxa_multa2,
                    "taxa_multa2_dia" => $request->taxa_multa2_dia,
                    "taxa_multa3" => $request->taxa_multa3,
                    "taxa_multa3_dia" => $request->taxa_multa3_dia,
                    "desconto" => 0,
                    "status" => "activo",
                    "pagamento" => "mensal",
                    "ano_lectivos_id" => $request->ano_lectivos_id,
                    "shcools_id" => $escola->id,
                ]);

                // recuperar todos os estudantes desta turma que estamos a cadastrar isso no caso de existir estudantes neste turma
                // porque poderias ser a edicção de uma turma

                $estudantesTurma = EstudantesTurma::where("turmas_id", $create->id)
                    ->where("ano_lectivos_id", $request->ano_lectivos_id)
                    ->get();

                // o controle periodio precisa saver se a escola é universidade ou não, por o serviços normais ou gerais tem dois tipos ANUAL - para universidades GERAIS para outros
                if ($escola->ensino->nome == "Ensino Superior") {
                    $controle_periodico = 7;
                } else {
                    $controle_periodico = 4;
                }

                // servicos unitarios que so vão precisar de unica pagamento como confirmação ou matriculas
                if ($estudantesTurma) {

                    foreach ($estudantesTurma as $item) {

                        // verifica se tem estudantes naquela turma p
                        $verificarServicosEstudante = CartaoEstudante::where("estudantes_id", $item->estudantes_id)
                            ->where("servicos_id", $matricula)
                            ->where("ano_lectivos_id", $request->ano_lectivos_id)
                            ->first();

                        // servico de matricula
                        if (!$verificarServicosEstudante) {
                            CartaoEstudante::create([
                                "estudantes_id" => $item->estudantes_id,
                                "servicos_id" => $matricula,
                                "preco_unitario" => $request->valor_matricula,
                                "data_at" => $ano_lectivo->inicio,
                                "data_exp" => $ano_lectivo->final,
                                "month_number" => date("m", strtotime($ano_lectivo->inicio)),
                                "month_name" => date("M", strtotime($ano_lectivo->inicio)),
                                "controle_periodico_id" => $controle_periodico,
                                "ano_lectivos_id" => $request->ano_lectivos_id,
                                "status" => "Nao Pago",
                            ]);
                        }
                    }
                    // servico de confirmação
                    foreach ($estudantesTurma as $item) {
                        $verificarServicosEstudante = CartaoEstudante::where("estudantes_id", $item->estudantes_id)
                            ->where("servicos_id",  $confirmacao)
                            ->where("ano_lectivos_id", $request->ano_lectivos_id)
                            ->first();

                        if (!$verificarServicosEstudante) {
                            CartaoEstudante::create([
                                "estudantes_id" => $item->estudantes_id,
                                "servicos_id" => $confirmacao,
                                "preco_unitario" => $request->valor_confirmacao,
                                "data_at" => $ano_lectivo->inicio,
                                "data_exp" => $ano_lectivo->final,
                                "month_number" => date("m", strtotime($ano_lectivo->inicio)),
                                "month_name" => date("M", strtotime($ano_lectivo->inicio)),
                                "controle_periodico_id" => $controle_periodico,
                                "ano_lectivos_id" => $request->ano_lectivos_id,
                                "status" => "Nao Pago",
                            ]);
                        }
                    }


                    foreach ($estudantesTurma as $item) {
                        $verificarServicosEstudante = CartaoEstudante::where("estudantes_id", $item->estudantes_id)
                            ->where("servicos_id", $diversos)
                            ->where("ano_lectivos_id", $request->ano_lectivos_id)
                            ->first();

                        if (!$verificarServicosEstudante) {
                            CartaoEstudante::create([
                                "estudantes_id" => $item->estudantes_id,
                                "servicos_id" => $diversos,
                                "preco_unitario" => 0,
                                "data_at" => $ano_lectivo->inicio,
                                "data_exp" => $ano_lectivo->final,
                                "month_number" => date("m", strtotime($ano_lectivo->inicio)),
                                "month_name" => date("M", strtotime($ano_lectivo->inicio)),
                                "ano_lectivos_id" => $request->ano_lectivos_id,
                                "controle_periodico_id" => $controle_periodico,
                                "status" => "Nao Pago",
                            ]);
                        }
                    }

                    // meses
                    $meses = $this->cartao_estudantes_meses(
                        $ano_lectivo->inicio, // inicio do ano lectivo
                        $request->intervalo_pagamento_inicio, // primeiro dia de pagamento
                        $request->intervalo_pagamento_final // ultimo dia de pagamento
                    );

                    foreach ($estudantesTurma as $item) {
                        foreach ($meses as $mes) {

                            if ($escola->ensino->nome == "Ensino Superior") {
                                $controle_periodico = $this->mes_periodico($mes["sigla"], "M", "universidade");
                            } else {
                                $controle_periodico = $this->mes_periodico($mes["sigla"], "M", "geral");
                            }

                            $verificarServicosEstudante = CartaoEstudante::where("estudantes_id", $item->estudantes_id)
                                ->where("servicos_id", $propina)
                                ->where("month_number", $mes['mes'])
                                ->where("month_name", $mes['sigla'])
                                ->where("ano_lectivos_id", $request->ano_lectivos_id)
                                ->first();

                            if (!$verificarServicosEstudante) {
                                CartaoEstudante::create([
                                    "estudantes_id" => $item->estudantes_id,
                                    "servicos_id" => $propina,
                                    "preco_unitario" => $request->valor_propina,
                                    "data_at" => $mes["inicio"],
                                    "data_exp" => $mes["fim"],
                                    "month_number" => $mes["mes"],
                                    "month_name" => $mes["sigla"],
                                    "ano_lectivos_id" => $request->ano_lectivos_id,
                                    "controle_periodico_id" => $controle_periodico,
                                    "status" => "Nao Pago",
                                ]);
                            }
                        }
                    }
                }
            }

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning("Informação", $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        /**
         * Cadastro serviso de propina final
         */

        return response()->json([
            "status" => 200,
            "message" => "Dados salvos com sucesso!",
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }

    // editar turmas
    public function editarTurmas($id)
    {
        $user = auth()->user();

        if (!$user->can('update: turma')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $turmaId = Turma::findOrFail($id);
        if ($turmaId) {
            return response()->json([
                "status" => 200,
                "turmas" => $turmaId,
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        } else {
            return response()->json([
                "status" => 404,
                "message" => 'Turno não Encontrado'
            ]);
        }
    }

    // actualizar turmas
    public function updateTurmas(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user->can('update: turma')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $validate = Validator::make($request->all(), [
            "nome_turmas" => 'required',
            "status_turmas" => 'required',
            "classes_id" => 'required',
            "turnos_id" => 'required',
            "cursos_id" => 'required',
            "salas_id" => 'required',
            "ano_lectivos_id" => 'required',
        ]);

        $escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->findOrFail($this->escolarLogada());
        $ano_lectivo = AnoLectivo::findOrFail($request->ano_lectivos_id);

        if ($escola->categoria == "Publico") {
            $request->valor_propina = 0;
            $request->valor_confirmacao = 0;
            $request->valor_matricula = 0;

            $valor_propina_com_iva = $request->valor_propina;
            $valor_confirmacao_com_iva = $request->valor_confirmacao;
            $valor_matricula_com_iva = $request->valor_matricula;

            $request->intervalo_pagamento_inicio = 0;
            $request->intervalo_pagamento_final = 0;

            $request->taxa_multa1 = 0;
            $request->taxa_multa1_dia = 0;
            $request->taxa_multa2 = 0;
            $request->taxa_multa2_dia = 0;
            $request->taxa_multa3 = 0;
            $request->taxa_multa3_dia = 0;
        } else {
            $valor_propina_com_iva = $request->valor_propina;
            $valor_confirmacao_com_iva = $request->valor_confirmacao;
            $valor_matricula_com_iva = $request->valor_matricula;
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            if ($validate->fails()) {
                return response()->json([
                    'status' => 400,
                    'errors' => $validate->messages(),
                ]);
            } else {
                $update = Turma::findOrFail($id);

                $update->turma = $request->nome_turmas;
                $update->status = $request->status_turmas;
                $update->classes_id = $request->classes_id;
                $update->turnos_id = $request->turnos_id;
                $update->cursos_id = $request->cursos_id;
                $update->salas_id = $request->salas_id;

                $update->valor_confirmacao = $request->valor_confirmacao;
                $update->valor_propina = $request->valor_propina;
                $update->valor_matricula = $request->valor_matricula;

                $update->valor_confirmacao_com_iva = $valor_confirmacao_com_iva;
                $update->valor_propina_com_iva = $valor_propina_com_iva;
                $update->valor_matricula_com_iva = $valor_matricula_com_iva;

                $update->ano_lectivos_id = $request->ano_lectivos_id;
                $update->numero_maximo = $request->numero_maximo;

                $update->intervalo_pagamento_inicio = $request->intervalo_pagamento_inicio;
                $update->intervalo_pagamento_final = $request->intervalo_pagamento_final;

                $update->taxa_multa1 = $request->taxa_multa1;
                $update->taxa_multa1_dia = $request->taxa_multa1_dia;
                $update->taxa_multa2 = $request->taxa_multa2;
                $update->taxa_multa2_dia = $request->taxa_multa2_dia;
                $update->taxa_multa3 = $request->taxa_multa3;
                $update->taxa_multa3_dia = $request->taxa_multa3_dia;

                $update->update();

                $matricula = Servico::where('shcools_id', $escola->id)
                    ->where('servico', "Matricula")
                    ->first()->id;

                $confirmacao = Servico::where('shcools_id', $escola->id)
                    ->where('servico', "Confirmação")
                    ->first()->id;

                $propina = Servico::where('shcools_id', $escola->id)
                    ->where('servico', "Propinas")
                    ->first()->id;

                /**
                 * update de servico de matricula Turma
                 */

                $update_servico_matricula = ServicoTurma::where("shcools_id", $escola->id)->where("ano_lectivos_id", $request->ano_lectivos_id)->where("servicos_id", $matricula)->where("turmas_id", $update->id)->first();
                $update_servico_matricula->preco = $request->valor_matricula;
                $update_servico_matricula->preco_sem_iva = $valor_matricula_com_iva;
                $update_servico_matricula->intervalo_pagamento_inicio = $request->intervalo_pagamento_inicio;
                $update_servico_matricula->intervalo_pagamento_final = $request->intervalo_pagamento_final;

                $update_servico_matricula->taxa_multa1 = $request->taxa_multa1;
                $update_servico_matricula->taxa_multa1_dia = $request->taxa_multa1_dia;
                $update_servico_matricula->taxa_multa2 = $request->taxa_multa2;
                $update_servico_matricula->taxa_multa2_dia = $request->taxa_multa2_dia;
                $update_servico_matricula->taxa_multa3 = $request->taxa_multa3;
                $update_servico_matricula->taxa_multa3_dia = $request->taxa_multa3_dia;
                $update_servico_matricula->update();

                /**
                 * update de servico de confirmacao Turma
                 */
                $update_servico_confirmacao = ServicoTurma::where("shcools_id", $escola->id)->where("ano_lectivos_id", $request->ano_lectivos_id)->where("servicos_id", $confirmacao)->where("turmas_id", $update->id)->first();
                $update_servico_confirmacao->preco = $request->valor_confirmacao;
                $update_servico_confirmacao->preco_sem_iva = $valor_confirmacao_com_iva;
                $update_servico_confirmacao->intervalo_pagamento_inicio = $request->intervalo_pagamento_inicio;
                $update_servico_confirmacao->intervalo_pagamento_final = $request->intervalo_pagamento_final;

                $update_servico_confirmacao->taxa_multa1 = $request->taxa_multa1;
                $update_servico_confirmacao->taxa_multa1_dia = $request->taxa_multa1_dia;
                $update_servico_confirmacao->taxa_multa2 = $request->taxa_multa2;
                $update_servico_confirmacao->taxa_multa2_dia = $request->taxa_multa2_dia;
                $update_servico_confirmacao->taxa_multa3 = $request->taxa_multa3;
                $update_servico_confirmacao->taxa_multa3_dia = $request->taxa_multa3_dia;
                $update_servico_confirmacao->update();

                /**
                 * update de servico de propina Turma
                 */
                $update_servico_propina = ServicoTurma::where("shcools_id", $escola->id)->where("ano_lectivos_id", $request->ano_lectivos_id)->where("servicos_id", $propina)->where("turmas_id", $update->id)->first();
                $update_servico_propina->preco = $request->valor_propina;
                $update_servico_propina->preco_sem_iva = $valor_propina_com_iva;
                $update_servico_propina->intervalo_pagamento_inicio = $request->intervalo_pagamento_inicio;
                $update_servico_propina->intervalo_pagamento_final = $request->intervalo_pagamento_final;

                $update_servico_propina->taxa_multa1 = $request->taxa_multa1;
                $update_servico_propina->taxa_multa1_dia = $request->taxa_multa1_dia;
                $update_servico_propina->taxa_multa2 = $request->taxa_multa2;
                $update_servico_propina->taxa_multa2_dia = $request->taxa_multa2_dia;
                $update_servico_propina->taxa_multa3 = $request->taxa_multa3;
                $update_servico_propina->taxa_multa3_dia = $request->taxa_multa3_dia;
                $update_servico_propina->update();

                //------------------------------------------
                $estudantesTurma = EstudantesTurma::where('turmas_id', $update->id)
                    ->where('ano_lectivos_id', $request->ano_lectivos_id)
                    ->get();

                if ($estudantesTurma) {

                    if ($escola->ensino->nome == "Ensino Superior") {
                        $controle_periodico = 7;
                    } else {
                        $controle_periodico = 4;
                    }

                    foreach ($estudantesTurma as $item) {

                        $verificarServicosEstudante = CartaoEstudante::where('estudantes_id', $item->estudantes_id)
                            ->where('servicos_id', $matricula)
                            ->where('ano_lectivos_id', $request->ano_lectivos_id)
                            ->first();

                        if ($verificarServicosEstudante) {
                            $update = CartaoEstudante::findOrFail($verificarServicosEstudante->id);
                            $update->preco_unitario = $request->valor_matricula;
                            $update->data_at = $ano_lectivo->inicio;
                            $update->data_exp = $ano_lectivo->final;
                            $update->controle_periodico_id = $controle_periodico;
                            $update->update();
                        }
                    }

                    foreach ($estudantesTurma as $item) {
                        $verificarServicosEstudante = CartaoEstudante::where('estudantes_id', $item->estudantes_id)
                            ->where('servicos_id',  $confirmacao)
                            ->where('ano_lectivos_id', $this->anolectivoActivo())
                            ->first();

                        if ($verificarServicosEstudante) {
                            $update = CartaoEstudante::findOrFail($verificarServicosEstudante->id);
                            $update->preco_unitario = $request->valor_confirmacao;
                            $update->data_at = $ano_lectivo->inicio;
                            $update->data_exp = $ano_lectivo->final;
                            $update->controle_periodico_id = $controle_periodico;
                            $update->update();
                        }
                    }

                    // meses
                    $meses = $this->cartao_estudantes_meses(
                        $ano_lectivo->inicio, // inicio do ano lectivo
                        $request->intervalo_pagamento_inicio, // primeiro dia de pagamento
                        $request->intervalo_pagamento_final // ultimo dia de pagamento
                    );

                    foreach ($estudantesTurma as $item) {

                        $cartoes = CartaoEstudante::where("estudantes_id", $item->estudantes_id)
                            ->where("servicos_id", $propina)
                            ->where("ano_lectivos_id", $request->ano_lectivos_id)
                            ->get();

                        foreach ($cartoes as $item) {

                            // actualizar as datas do inicio e final do pagamento dos meses
                            foreach ($meses as $mes) {

                                if ($escola->ensino->nome == "Ensino Superior") {
                                    $controle_periodico = $this->mes_periodico($mes["sigla"], "M", "universidade");
                                } else {
                                    $controle_periodico = $this->mes_periodico($mes["sigla"], "M", "geral");
                                }

                                if ($item->month_name == $mes['sigla'] && $item->month_number == $mes['mes']) {
                                    $update = CartaoEstudante::findOrFail($item->id);
                                    $update->data_at = $mes["inicio"];
                                    $update->data_exp = $mes["fim"];
                                    $update->controle_periodico_id = $controle_periodico;
                                    // $update->month_number = $mes["mes"];
                                    // $update->month_name = $mes["sigla"];
                                    $update->preco_unitario = $request->valor_propina;
                                    $update->update();
                                }
                            }

                            // actulizar as multas nos cartões dos estudantes
                            // se o mes a se actualizar é igual ao mes actual
                            if ($item->month_name == date("M")) {

                                // recuperar todo so meses acterior do mes actual
                                $cartoes_anterior = CartaoEstudante::where("estudantes_id", $item->estudantes_id)
                                    ->whereIn("status", ["divida", "Nao Pago"])
                                    ->where("servicos_id", $propina)
                                    ->where("mes_id", "M")
                                    // ->whereIn("month_name", ["Dec", "Sep", "Oct","Nov", "Jan","Feb", "Mar"])
                                    ->whereIn("month_name", $this->meses_anterior_ao_mes($item->month_name))
                                    ->where("ano_lectivos_id", $request->ano_lectivos_id)
                                    ->get();


                                $multa1 = $multa2 = $multa3 = 0;
                                $multa = 0;

                                $data_primeira_taxa = date('Y-m-d', strtotime($mes["inicio"] . "+{$request->taxa_multa1_dia}days"));
                                $data_segunda_taxa = date('Y-m-d', strtotime($mes["inicio"] . "+{$request->taxa_multa2_dia}days"));
                                $data_terceira_taxa = date('Y-m-d', strtotime($mes["inicio"] . "+{$request->taxa_multa3_dia}days"));

                                foreach ($cartoes_anterior as $cartao_anterior) {

                                    // se o permite pagar pirmiero multa
                                    if ($cartao_anterior->multa1 === 'N' && $mes["fim"] >= $data_primeira_taxa) {
                                        $multa = $request->valor_propina * (($request->taxa_multa1 ?? 0) / 100);
                                    }

                                    if ($cartao_anterior->multa2 === 'N' && $mes["fim"] > $data_segunda_taxa) {
                                        $multa1 = "Y";
                                        $multa = $request->valor_propina * (($request->taxa_multa2 ?? 0) / 100);
                                    }

                                    if ($cartao_anterior->multa3 === 'N' && $mes["fim"] > $data_terceira_taxa) {
                                        $multa1 = "Y";
                                        $multa3 = "Y";
                                        $multa = $request->valor_propina * (($request->taxa_multa3 ?? 0) / 100);
                                    }

                                    $multa_final = $multa;

                                    $update_cartao_anterior = CartaoEstudante::findOrFail($cartao_anterior->id);
                                    $update_cartao_anterior->multa = $multa_final;
                                    $update_cartao_anterior->status = "divida";
                                    $update_cartao_anterior->update();
                                }
                            }
                        }
                    }
                }

                //------------------------------------------

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
            'message' => 'Dados ACtualizados com sucesso!',
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }

    public function cadastrarDisciplinasTurmas(Request $request)
    {

        $request->validate([
            "disciplina_id" => 'required|array',
            "status" => 'required',
            "turma_select_id" => 'required',
        ]);

        $contarExistenciasDisciplinas = 0;

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            foreach ($request->disciplina_id as $key) {

                $verificarTurno = DisciplinaTurma::where('disciplinas_id', $key)
                    ->where('turmas_id', $request->turma_select_id)
                    ->where('trimestre_id', $request->trimestre_id)
                ->first();

                if ($verificarTurno) {
                    $contarExistenciasDisciplinas++;
                } else {

                    DisciplinaTurma::create([
                        "status" => $request->status,
                        "turmas_id" => $request->turma_select_id,
                        "trimestre_id" => $request->trimestre_id,
                        "peso_primeira_freq" => $request->peso_primeira_freq,
                        "peso_segunda_freq" => $request->peso_segunda_freq,
                        "disciplinas_id" => $key,
                    ]);

                    $estudanteTurma = EstudantesTurma::where('turmas_id', $request->turma_select_id)
                        ->where('ano_lectivos_id', $this->anolectivoActivo())
                    ->get();

                    if ($estudanteTurma) {

                        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

                        if ($escola->ensino->nome == "Ensino Superior") {
                            $trimestres = ControlePeriodico::where('ensino_status', '2')->get();
                        } else {
                            $trimestres = ControlePeriodico::where('ensino_status', '1')->get();
                        }

                        foreach ($estudanteTurma as $estudantes) {
                            foreach ($trimestres as $trimestre) {
                                $verificar = NotaPauta::where("turmas_id", $request->turma_select_id)
                                    ->where("estudantes_id", $estudantes->estudantes_id)
                                    ->where("ano_lectivos_id", $this->anolectivoActivo())
                                    ->where("controlo_trimestres_id", $trimestre->id)
                                    ->where("disciplinas_id", $key)
                                    ->first();

                                if (!$verificar) {
                                    $this->criar_pauta_curricular($request->turma_select_id, $estudantes->estudantes_id, $this->anolectivoActivo(), $trimestre->id, $key);
                                }
                            }
                        }
                    }
                }
            }

            if ($contarExistenciasDisciplinas > 0) {
                return response()->json([
                    'status' => 300,
                    'message' => "Disciplinas Que já estavam adicionar foram ignoradas e as restante foram cadastradas!",
                ]);
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

    // delete turmas
    public function deleteTurmas($id)
    {
        $user = auth()->user();

        if (!$user->can('delete: turma')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $turma = Turma::findOrFail($id);
            DisciplinaTurma::where('turmas_id', $turma->id)->delete();
            EstudantesTurma::where('turmas_id', $turma->id)->delete();
            FuncionariosTurma::where('turmas_id', $turma->id)->delete();
            Horario::where('turmas_id', $turma->id)->delete();
            ServicoTurma::where('turmas_id', $turma->id)->delete();
            ListaPresenca::where('turmas_id', $turma->id)->delete();
            NotaPauta::where('turmas_id', $turma->id)->delete();
            $turma->delete();

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

    public function deleteDisciplinaProfessorTurma($id)
    {
        $user = auth()->user();

        if (!$user->can('read: turma')  && !$user->can('update: estado')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $funcionarioTurma = FuncionariosTurma::findOrFail($id);
            $funcionarioTurma->forceDelete();

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
            'message' => 'Disciplina Removida com Successo com sucesso!',
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }


    // apresentar o turmas
    public function showTurmas($id)
    {
        $user = auth()->user();

        if (!$user->can('read: turma')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $turma = Turma::with(['classe'])->findOrFail(Crypt::decrypt($id));
        $servicos_turma = ServicoTurma::with(['turma', 'servico'])->where('turmas_id', $turma->id)->get();

        $disciplinas = DisciplinaTurma::with('disciplina', 'turma', 'trimestre')
            ->where('turmas_id', $turma->id)
            ->where('status', 'activo')
        ->get();

        $disciplinasProfessores = FuncionariosTurma::where('turmas_id', $turma->id)
            ->with(['disciplina', 'professor'])
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->get();

        $professores = FuncionariosTurma::where('tb_turmas_funcionarios.turmas_id', $turma->id)
            ->with(['professor'])
            ->where('tb_turmas_funcionarios.ano_lectivos_id', $this->anolectivoActivo())
            ->get();

        $estudantes = EstudantesTurma::with(['estudante'])->where('turmas_id', $turma->id)
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->get()
            ->sortBy(function ($matricula) {
                return $matricula->estudante->nome;
            });

        $totalEstudanteTurma = EstudantesTurma::where('turmas_id', $turma->id)
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->count();

        $totalDisciplinasTurma = DisciplinaTurma::where('turmas_id', $turma->id)->count();

        $totalProfessoresTurma = FuncionariosTurma::where('turmas_id', $turma->id)
            ->where('cargo_turma', 'Professor')
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->count();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Informações gerais da Turma",
            "descricao" => "gestão de discipinas",
            "turma" => $turma,
            "disciplinas" => $disciplinas,
            "disciplinasProfessores" => $disciplinasProfessores,
            "professores" => $professores,
            "estudantes" => $estudantes,
            "totalEst" => $totalEstudanteTurma,
            "totalDisc" => $totalDisciplinasTurma,
            "totalProf" => $totalProfessoresTurma,
            "servicos_turma" => $servicos_turma,
            "semanas" => DB::table('tb_semanas')->get(),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.turmas.mais-informacoes', $headers);
    }

    // activar e desactivar turma
    public function activarTurmas($id)
    {
        $user = auth()->user();

        if (!$user->can('read: turma')  && !$user->can('update: estado')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $update = Turma::findOrFail($id);
            if ($update->status == 'activo') {
                $status = 'desactivo';
            } else {
                $status = 'activo';
            }
            $update->status = $status;
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
            "status" => 200,
            "message" => "Dodos Activados com sucesso",
        ]);
    }

    // carregamentoDisciplinasCurso
    // id da Turma para recuperar o curso
    public function carregamentoDisciplinasCurso($id)
    {

        $turmaId = Turma::findOrFail($id);

        $disciplinasCurso = DisciplinaCurso::where([
            ['tb_discplinas_cursos.cursos_id', '=', $turmaId->cursos_id],
            ['tb_discplinas_cursos.shcools_id', '=', $this->escolarLogada()],
            ['tb_discplinas_cursos.ano_lectivos_id', '=', $this->anolectivoActivo()],
        ])
        ->where(['disciplina'])
        ->join('tb_disciplinas', 'tb_discplinas_cursos.disciplinas_id', '=', 'tb_disciplinas.id')
        ->select('tb_disciplinas.id', 'tb_disciplinas.disciplina')
        ->get();

        if ($disciplinasCurso) {
            return response()->json([
                "status" => 200,
                "disciplinasCurso" => $disciplinasCurso,
                "turma" => $turmaId,
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        } else {
            return response()->json([
                "status" => 404,
                "message" => 'Sem disciplinas cadastradas para este cursos'
            ]);
        }
    }

    #TODOS2
    public function cadastrarHorarioTurmas(Request $request)
    {

        $request->validate([
            "tempo_disciplina" => 'required',
            "dias_semanas_horario" => 'required',
            "turma_select_id" => 'required',
            "disciplinas_horario" => 'required',
        ]);


        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $verificarHorario = Horario::where('disciplinas_id', $request->disciplinas_horario)
                ->where('turmas_id', $request->turma_select_id)
                ->where('semanas_id', $request->dias_semanas_horario)
                ->where('tempos_id', $request->tempo_disciplina)
                ->first();

            if ($verificarHorario) {
                return response()->json([
                    'status' => 300,
                    'message' => "Este Disciplinas já Esta Cadastrado Neste tempo!",
                ]);
            }


            Horario::create([
                "professor_id" => $request->professores_id,
                "turmas_id" => $request->turma_select_id,
                "hora_inicio" => $request->hora_inicio,
                "hora_final" => $request->hora_final,
                "disciplinas_id" => $request->disciplinas_horario,
                "semanas_id" => $request->dias_semanas_horario,
                "tempos_id" => $request->tempo_disciplina,
                'shcools_id' => $this->escolarLogada(),
                'ano_lectivos_id' => $this->anolectivoActivo(),
            ]);

            if ($request->professores_id && $request->professores_id != null) {
                FuncionariosTurma::create([
                    "turmas_id" => $request->turma_select_id,
                    "funcionarios_id" => $request->professores_id,
                    "disciplinas_id" => $request->disciplinas_horario,
                    "cargo_turma" => "Professor",
                    "tempo_edicao" => date("Y-m-d"),
                    "ano_lectivos_id" => $this->anolectivoActivo(),
                    "shcools_id" => $this->escolarLogada(),
                ]);
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

    // delete turmas disciplona
    public function removerHorarioTurma($id)
    {
        $user = auth()->user();

        if (!$user->can('read: turma')  && !$user->can('read: horario')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $horario = Horario::findOrFail($id);

            $verificar_professor_turma = FuncionariosTurma::where("funcionarios_id", $horario->professor_id)
                ->where("turmas_id", $horario->turmas_id)
                ->where("disciplinas_id", $horario->disciplinas_id)
                ->first();

            if ($verificar_professor_turma) {
                $professor_turma = FuncionariosTurma::findOrFail($verificar_professor_turma->id);
                $professor_turma->delete();
            }

            $horario->delete();

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
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }

    // editar turmas
    public function editarHorarioTurmas($id)
    {
        $user = auth()->user();

        if (!$user->can('update: horario')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $horario = Horario::findOrFail($id);

        if ($horario) {
            return response()->json([
                "status" => 200,
                "horario" => $horario,
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        } else {
            return response()->json([
                "status" => 404,
                "message" => 'Turno não Encontrado'
            ]);
        }
    }

    public function updateHorarioTurmas(Request $request)
    {


        $request->validate([
            // "turmas_id" => 'required',
            // "dias_semanas" => 'required',
            // "disciplinas_horario" => 'required',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $update = Horario::findOrFail($request->editar_horario_id);

            $update->professor_id = $request->professores_id;
            $update->hora_inicio = $request->hora_inicio;
            $update->hora_final = $request->hora_final;
            $update->disciplinas_id = $request->disciplinas_horario;
            $update->semanas_id = $request->dias_semanas;
            $update->tempos_id = $request->turmas_id;

            $verificar_professor_turma = FuncionariosTurma::where("funcionarios_id", $update->professor_id)
                ->where("turmas_id", $update->turmas_id)
                ->where("disciplinas_id", $update->disciplinas_id)
                ->first();

            if ($verificar_professor_turma) {
                $professor_turma = FuncionariosTurma::findOrFail($verificar_professor_turma->id);
                $professor_turma->turmas_id = $request->turmas_id;
                $professor_turma->funcionarios_id = $request->professores_id;
                $professor_turma->disciplinas_id = $request->disciplinas_horario;
                $professor_turma->update();
            } else {
                if ($request->professores_id && $request->professores_id != null) {
                    FuncionariosTurma::create([
                        "turmas_id" => $request->turmas_id,
                        "funcionarios_id" => $request->professores_id,
                        "disciplinas_id" => $request->disciplinas_horario,
                        "cargo_turma" => "Professor",
                        "tempo_edicao" => date("Y-m-d"),
                        "ano_lectivos_id" => $this->anolectivoActivo(),
                        "shcools_id" => $this->escolarLogada(),
                    ]);
                }
            }

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


    // laod disciplinas turmas original
    public function loadDisciplinasTurma($id)
    {
        $turma = Turma::findOrFail($id);
        $disciplinas = DisciplinaTurma::where([
            ['turmas_id', '=', $turma->id],
        ])
            ->join('tb_disciplinas',  'tb_discplinas_turmas.disciplinas_id', '=', 'tb_disciplinas.id')
            ->select('tb_disciplinas.id', 'tb_disciplinas.disciplina', 'tb_disciplinas.code', 'tb_disciplinas.abreviacao')
            ->get();

        return response()->json([
            'status' => 200,
            'results' => $disciplinas,
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }

    // load horario
    public function loadHorarioTurma($id)
    {

        $turma = Turma::findOrFail($id);

        $disciplinas = Horario::where([
            ['tb_horario_turmas.turmas_id', '=', $turma->id]
        ])
            ->join('tb_disciplinas',  'tb_horario_turmas.disciplinas_id', '=', 'tb_disciplinas.id')
            ->join('tb_turmas',  'tb_horario_turmas.turmas_id', '=', 'tb_turmas.id')
            ->select('tb_horario_turmas.hora_inicio', 'tb_horario_turmas.hora_final', 'tb_horario_turmas.tempo', 'tb_horario_turmas.id', 'tb_disciplinas.disciplina', 'tb_disciplinas.code', 'tb_disciplinas.abreviacao', 'tb_horario_turmas.semanas_id')
            ->get();

        return response()->json([
            'status' => 200,
            'results' => $disciplinas,
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }

    // load servico
    public function loadServicoTurma($id)
    {

        $user = auth()->user();

        if (!$user->can('read: turma')  && !$user->can('read: servicos')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }


        $turma = Turma::findOrFail($id);
        $servicos = ServicoTurma::where([
            ['turmas_id', '=', $turma->id],
            ['model', '=', 'turmas'],
        ])
            ->join('tb_servicos',  'tb_servicos_turma.servicos_id', '=', 'tb_servicos.id')
            ->join('tb_turmas',  'tb_servicos_turma.turmas_id', '=', 'tb_turmas.id')
            ->select(
                'tb_servicos_turma.total_vezes',
                'tb_servicos_turma.data_inicio',
                'tb_servicos_turma.data_final',
                'tb_servicos_turma.preco',
                'tb_servicos_turma.preco_com_iva',
                'tb_servicos_turma.id',
                'tb_servicos_turma.multa',
                'tb_servicos_turma.desconto',
                'tb_servicos_turma.status',
                'tb_servicos.servico',
                'tb_servicos_turma.pagamento',
                'tb_servicos.id AS idServico'
            )
            ->get();

        return response()->json([
            'status' => 200,
            'results' => $servicos,
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }


    public function removerDisciplinaTurma($id)
    {
        $user = auth()->user();

        if (!$user->can('read: turma')  && !$user->can('read: disciplina')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $disciplina = DisciplinaTurma::findOrFail($id);
            $turma = Turma::findOrFail($disciplina->turmas_id);

            NotaPauta::where('turmas_id', $turma->id)
                ->where('ano_lectivos_id', $turma->ano_lectivos_id)
                ->where('disciplinas_id', $disciplina->disciplinas_id)
                ->delete();

            $disciplina->delete();

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


    // delete turmas servico
    public function removerMesesTurmaPagar($id)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO


        $ano = TurmaMeses::findOrFail($id);
        $ano->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Dados Excluido com sucesso!',
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }

    // lancamento nas turmas estudantes
    public function lancamentoNasTurmas(Request $request)
    {
        $admin = User::findOrFail(Auth::user()->id);

        // controle lancamento de notas se esta activo ou não
        $controlo = ControloLancamentoNotasEscolas::where('ano_lectivo_id', $this->anolectivoActivo())->where('shcools_id', $admin->shcools_id)->first();
        $lancamento = null;
        if ($controlo) {
            $lancamento = ControloLancamentoNotas::where('status', 'activo')->where('id', $controlo->lancamento_id)->first();
        }


        $dados_classes = Matricula::query()
            ->join('tb_classes', 'tb_matriculas.classes_id', '=', 'tb_classes.id') // Relaciona com a tabela de cursos
            ->select('tb_classes.classes as classe', \DB::raw('COUNT(tb_matriculas.estudantes_id) as total')) // Seleciona nome do curso e total
            ->where('tb_matriculas.shcools_id', $this->escolarLogada()) // Filtra pela escola logada
            ->where('tb_matriculas.ano_lectivos_id', $this->anolectivoActivo()) // Filtra pelo ano letivo ativo
            ->groupBy('tb_matriculas.classes_id', 'tb_classes.classes') // Agrupa por curso e nome do curso
            ->orderBy('tb_classes.classes', 'asc') // Ordena os resultados por nome do curso
            ->get();

        $dados = Matricula::query()
            ->join('tb_cursos', 'tb_matriculas.cursos_id', '=', 'tb_cursos.id') // Relaciona com a tabela de cursos
            ->select('tb_cursos.curso as curso', \DB::raw('COUNT(tb_matriculas.estudantes_id) as total')) // Seleciona nome do curso e total
            ->where('tb_matriculas.shcools_id', $this->escolarLogada()) // Filtra pela escola logada
            ->where('tb_matriculas.ano_lectivos_id', $this->anolectivoActivo()) // Filtra pelo ano letivo ativo
            ->groupBy('tb_matriculas.cursos_id', 'tb_cursos.curso') // Agrupa por curso e nome do curso
            ->orderBy('tb_cursos.curso', 'asc') // Ordena os resultados por nome do curso
            ->get();

        // Labels e dados para o gráfico
        $labels = $dados->pluck('curso')->toArray(); // Nomes dos cursos
        $data = $dados->pluck('total')->toArray();        // Totais de estudantes

        // Labels e dados para o gráfico
        $labels_classe = $dados_classes->pluck('classe')->toArray(); // Nomes dos cursos
        $data_classe = $dados_classes->pluck('total')->toArray();        // Totais de estudantes

        // Criação do gráfico com PagamentoChart
        $chartEstudantesCursos = new PagamentoChart;
        $chartEstudantesCursos->labels($labels);
        $chartEstudantesCursos->dataset('Total de Estudantes por Curso', 'bar', $data)
            ->backgroundColor('rgba(30, 100, 133, 0.938)'); // Define a cor das barras

        // Criação do gráfico com PagamentoChart
        $chartEstudantesClasse = new PagamentoChart;
        $chartEstudantesClasse->labels($labels_classe);
        $chartEstudantesClasse->dataset('Total de Estudantes por Classe', 'bar', $data_classe)
            ->backgroundColor('rgba(30, 100, 133, 0.938)'); // Define a cor das barras


        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            'chartEstudantesCursos' =>  $chartEstudantesCursos,
            'chartEstudantesClasse' =>  $chartEstudantesClasse,
            "titulo" => "Lançamento de Notas",
            "descricao" => env('APP_NAME'),
            "totalturmas" => Turma::where([
                ['shcools_id', '=', $this->escolarLogada()],
                ['status', '=', 'activo'],
                ['ano_lectivos_id', '=', $this->anolectivoActivo()]
            ])->count(),
            "totaldisciplinas" => Disciplina::where([
                ['shcools_id', '=', $this->escolarLogada()]
            ])->count(),
            "totalfuncionarios" => FuncionariosControto::where([
                ['shcools_id', '=', $this->escolarLogada()],
                ['status', '=', 'Activo'],
                ['cargo_geral', '=', 'professor'],
            ])->count(),

            "totalestudantes" => Matricula::where([
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['status_inscricao', '=', 'Admitido'],
                ['status_matricula', '=', 'confirmado'],
                ['shcools_id', '=', $this->escolarLogada()],
            ])->count(),

            "lancamento" => $lancamento
        ];

        return view('admin.turmas.lacamento-estudantes-turmas', $headers);
    }

    public function lancamentoNotas($id = null)
    {
        $user = auth()->user();

        if (!$user->can('create: nota')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $turmas = Turma::where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('status', 'activo')
            ->get();

        // controle lancamento de notas se esta activo ou não
        $controlo = ControloLancamentoNotasEscolas::where('ano_lectivo_id', $this->anolectivoActivo())->where('shcools_id', Auth::user()->shcools_id)->first();
        $lancamento = null;
        if ($controlo) {
            $lancamento = ControloLancamentoNotas::where('status', 'activo')->where('id', $controlo->lancamento_id)->first();
        }

        $ano_lectivos = Anolectivo::where('shcools_id', $this->escolarLogada())->orderBy('status', 'asc')->get();

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        if ($escola->ensino->nome == "Ensino Superior") {
            $trimestres = ControlePeriodico::where('ensino_status', '2')->get();
        } else {
            $trimestres = ControlePeriodico::where('trimestre', '<>', 'Geral')->where('ensino_status', '1')->get();
        }

        $headers = [
            "escola" => $escola,
            "titulo" => "Lançamento de notas dos estudantes",
            "descricao" => env('APP_NAME'),
            "turmas" => $turmas,
            "idTurmaSelecionada" => $id,
            "trimestres" => $trimestres,
            "usuario" => $user,
            "lancamento" => $lancamento,
            "ano_lectivos" => $ano_lectivos,
        ];

        return view('admin.turmas.lancamento-notas', $headers);
    }

    public function carregamentoDisciplinasTurma($id)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO

        $turmaId = Turma::findOrFail($id);

        $professoresTurma = FuncionariosTurma::where([
            ['tb_turmas_funcionarios.turmas_id', '=', $turmaId->id],
        ])
            ->join('tb_disciplinas', 'tb_turmas_funcionarios.disciplinas_id', '=', 'tb_disciplinas.id')
            ->join('tb_turmas', 'tb_turmas_funcionarios.turmas_id', '=', 'tb_turmas.id')
            ->join('tb_professores', 'tb_turmas_funcionarios.funcionarios_id', '=', 'tb_professores.id')
            ->select('tb_professores.nome', 'tb_professores.sobre_nome', 'tb_disciplinas.disciplina', 'tb_turmas.turma', 'tb_turmas_funcionarios.id', 'tb_professores.id AS idFuncionario')
            ->get();

        $disciplinasTurma = DisciplinaTurma::where([
            ['turmas_id', '=', $turmaId->id]
        ])
            ->join('tb_disciplinas', 'tb_discplinas_turmas.disciplinas_id', '=', 'tb_disciplinas.id')
            ->select('tb_disciplinas.id', 'tb_disciplinas.disciplina')
            ->get();

        if ($disciplinasTurma) {
            return response()->json([
                "status" => 200,
                "disciplinasTurma" => $disciplinasTurma,
                "curso" => $disciplinasTurma,
                "sala" => $disciplinasTurma,
                "classe" => $disciplinasTurma,
                "turno" => $disciplinasTurma,
                "resultado" => $professoresTurma,
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        } else {
            return response()->json([
                "status" => 404,
                "message" => 'Sem disciplinas cadastradas para este cursos'
            ]);
        }
    }

    public function carregarTurmaEstudanteId($id)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
        $turmaId = Turma::findOrFail($id);

        $estudantes = EstudantesTurma::where('turmas_id', '=', $turmaId->id)
            ->with('estudante')
            ->get();

        $option = "<option value='geral'>Imprimir Lista Geral do estudantes </option>";
        foreach ($estudantes as $estudante) {
            $option .= '<option value="' . $estudante->estudante->id . '">' . $estudante->estudante->nome . '<option>';
        }
        return $option;
    }

    // configurar servico para qualquer entidade so sistema
    public function configuracaoServico()
    {

        $servicosTurmas = ServicoTurma::where([
            ['ano_lectivos_id', $this->anolectivoActivo()],
            ['shcools_id', $this->escolarLogada()]
        ])
            ->with(['ano_lectivo', 'turma', 'servico'])
            ->get();



        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "Lista dos Serviços das Turmas",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            "servicosTurmas" => $servicosTurmas,
            "servicos" => Servico::where([
                ['shcools_id', '=', $this->escolarLogada()]
            ])->get(),
        ];

        return view('admin.calendarios.configurar-servicos', $headers);
    }

    public function carregamentoDestinoServico($values)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO


        if ($values == "turmas") {

            $turmas = Turma::where([
                ['shcools_id', "=", $this->escolarLogada()],
                ['ano_lectivos_id', "=", $this->anolectivoActivo()],
            ])->get();
            if (count($turmas) != 0) {
                return response()->json([
                    'status' => 200,
                    'entidade' => 'turma',
                    'turmas' => $turmas,
                ]);
            }
        } else if ($values == "escola") {
            $escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->findOrFail($this->escolarLogada());
            if ($escola) {
                return response()->json([
                    'status' => 200,
                    'entidade' => 'escola',
                    'escola' => $escola,
                ]);
            }
        }
    }

    // editar servicos turma
    public function editarServicoTurma(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user->can('read: turma')  && !$user->can('update: servicos')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            "servicos_id" => 'required',
            "preco" => 'required',
            "multa" => 'required',
            "desconto" => 'required',
            "status" => 'required',
            "pagamento" => 'required',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            if ($request->pagamento == 'mensal' and $request->total_vezes == "") {
                return response()->json([
                    'status' => 300,
                    'message' => "Preencha o total de paracelamento, porfavor!",
                ]);
            }

            $intervalo_pagamento_inicio = date("d", strtotime($request->data_inicio));
            $intervalo_pagamento_final = date("d", strtotime($request->data_final));

            $service = Servico::findOrFail($request->servicos_id);
            $taxa = DB::table('tb_taxas')->where('id', $service->taxa_id)->first();

            $create = ServicoTurma::findOrFail($id);

            $create->servicos_id = $service->id;
            $create->preco_sem_iva = $request->preco;
            $create->preco = $request->preco + ($request->preco * (($taxa->taxa ?? 1) / 100));
            $create->multa = $request->multa;
            $create->data_inicio = $request->data_inicio;
            $create->data_final = $request->data_final;
            $create->total_vezes = $request->total_vezes;
            $create->desconto = $request->desconto;
            $create->status = $request->status;
            $create->pagamento = $request->pagamento;

            $create->intervalo_pagamento_inicio = $intervalo_pagamento_inicio;
            $create->intervalo_pagamento_final = $intervalo_pagamento_final;

            $create->taxa_multa1 = $request->taxa_multa1;
            $create->taxa_multa1_dia = $request->taxa_multa1_dia;
            $create->taxa_multa2 = $request->taxa_multa2;
            $create->taxa_multa2_dia = $request->taxa_multa2_dia;
            $create->taxa_multa3 = $request->taxa_multa3;
            $create->taxa_multa3_dia = $request->taxa_multa3_dia;
            $create->update();

            if ($create->model == "turmas") {

                if ($service->servico == "Matricula") {
                    $update_turma_matricula = Turma::findOrFail($create->turmas_id);
                    $update_turma_matricula->valor_matricula = $request->preco;
                    $update_turma_matricula->valor_matricula_com_iva = $request->preco + ($request->preco * ($taxa->taxa / 100));
                    $update_turma_matricula->intervalo_pagamento_inicio = $intervalo_pagamento_inicio;
                    $update_turma_matricula->intervalo_pagamento_final = $intervalo_pagamento_final;

                    $update_turma_matricula->taxa_multa1 = $request->taxa_multa1;
                    $update_turma_matricula->taxa_multa1_dia = $request->taxa_multa1_dia;
                    $update_turma_matricula->taxa_multa2 = $request->taxa_multa2;
                    $update_turma_matricula->taxa_multa2_dia = $request->taxa_multa2_dia;
                    $update_turma_matricula->taxa_multa3 = $request->taxa_multa3;
                    $update_turma_matricula->taxa_multa3_dia = $request->taxa_multa3_dia;
                    $update_turma_matricula->update();
                }

                if ($service->servico == "Confirmação") {
                    $update_turma_confirmacao = Turma::findOrFail($create->turmas_id);
                    $update_turma_confirmacao->valor_confirmacao = $request->preco;
                    $update_turma_confirmacao->valor_confirmacao_com_iva = $request->preco + ($request->preco * ($taxa->taxa / 100));
                    $update_turma_confirmacao->intervalo_pagamento_inicio = $intervalo_pagamento_inicio;
                    $update_turma_confirmacao->intervalo_pagamento_final = $intervalo_pagamento_final;

                    $update_turma_confirmacao->taxa_multa1 = $request->taxa_multa1;
                    $update_turma_confirmacao->taxa_multa1_dia = $request->taxa_multa1_dia;
                    $update_turma_confirmacao->taxa_multa2 = $request->taxa_multa2;
                    $update_turma_confirmacao->taxa_multa2_dia = $request->taxa_multa2_dia;
                    $update_turma_confirmacao->taxa_multa3 = $request->taxa_multa3;
                    $update_turma_confirmacao->taxa_multa3_dia = $request->taxa_multa3_dia;
                    $update_turma_confirmacao->update();
                }

                if ($service->servico == "Propinas") {
                    $update_turma_propina = Turma::findOrFail($create->turmas_id);
                    $update_turma_propina->valor_propina = $request->preco;
                    $update_turma_propina->valor_propina_com_iva = $request->preco + ($request->preco * ($taxa->taxa / 100));
                    $update_turma_propina->intervalo_pagamento_inicio = $intervalo_pagamento_inicio;
                    $update_turma_propina->intervalo_pagamento_final = $intervalo_pagamento_final;

                    $update_turma_propina->taxa_multa1 = $request->taxa_multa1;
                    $update_turma_propina->taxa_multa1_dia = $request->taxa_multa1_dia;
                    $update_turma_propina->taxa_multa2 = $request->taxa_multa2;
                    $update_turma_propina->taxa_multa2_dia = $request->taxa_multa2_dia;
                    $update_turma_propina->taxa_multa3 = $request->taxa_multa3;
                    $update_turma_propina->taxa_multa3_dia = $request->taxa_multa3_dia;
                    $update_turma_propina->update();
                }

                $estudantes = EstudantesTurma::where('turmas_id', $create->turmas_id)
                    ->where('ano_lectivos_id', $create->ano_lectivos_id)
                    ->get();

                if ($estudantes) {
                    foreach ($estudantes as $item) {
                        $verificarServicosEstudante = CartaoEstudante::where('estudantes_id', $item->estudantes_id)
                            ->where('servicos_id', $service->id)
                            ->where('ano_lectivos_id', $create->ano_lectivos_id)
                            ->first();

                        if ($verificarServicosEstudante) {
                            $update = CartaoEstudante::findOrFail($verificarServicosEstudante->id);
                            $update->preco_unitario = $request->preco;
                            $update->multa = $request->multa;
                            $update->desconto = $request->desconto;
                            $update->update();
                        }
                    }
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
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }

    // cadastrar servicos turma
    public function cadastrarServicoTurma(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('create: servicos')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $validate = Validator::make($request->all(), [
            "preco" => 'required',
            "multa" => 'required',
            "desconto" => 'required',
            "status" => 'required',
            "pagamento" => 'required',
            "servicos_para" => 'required',
            "data_final" => 'required',
            "data_inicio" => 'required',
        ], [
            "preco.required" => "Campo Obrigatório",
            "multa.required" => "Campo Obrigatório",
            "status.required" => "Campo Obrigatório",
            "desconto.required" => "Campo Obrigatório",
            "pagamento.required" => "Campo Obrigatório",
            "servicos_para.required" => "Campo Obrigatório",
            "data_inicio.required" => "Campo Obrigatório",
            "data_final.required" => "Campo Obrigatório",
        ]);

        if ($request->input('pagamento') == 'mensal' and $request->input('total_vezes') == "") {
            return response()->json([
                'status' => 300,
                'message' => "Preencha o total de paracelamento, porfavor!",
            ]);
        }

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        try {
            // Inicia a transação
            DB::beginTransaction();


            if ($request->input('servicos_desitno')) {
                if ($request->input('servicos_id')) {
                    foreach ($request->input('servicos_id') as $servicos_id) {
                        foreach ($request->input('servicos_desitno') as $item_servivo) {

                            $servico = ServicoTurma::where([
                                ['servicos_id', '=', $servicos_id],
                                // ['servicos_id', '=', $request->input('servicos_id')],
                                ['turmas_id', '=', $item_servivo],
                                ['model', '=', $request->input('servicos_para')],
                                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                            ])->first();

                            $service = Servico::findOrFail($servicos_id);
                            $taxa = DB::table('tb_taxas')->where('id', $service->taxa_id)->first();
                            if (!$servico) {

                                if ($validate->fails()) {
                                    return response()->json([
                                        'status' => 400,
                                        'errors' => $validate->messages(),
                                    ]);
                                } else {

                                    $intervalo_pagamento_inicio = date("d", strtotime($request->input('data_inicio')));
                                    $intervalo_pagamento_final = date("d", strtotime($request->input('data_final')));

                                    $create = ServicoTurma::create([
                                        "servicos_id" => $servicos_id,
                                        // "servicos_id" => $request->input('servicos_id'),
                                        "turmas_id" => $item_servivo,
                                        "model" => $request->input('servicos_para'),
                                        "preco" => $request->input('preco') + ($request->input('preco') * (($taxa->taxa ?? 0) / 100)),
                                        "preco_sem_iva" => $request->input('preco'),
                                        "multa" => $request->input('multa'),
                                        "data_inicio" => $request->input('data_inicio'),
                                        "data_final" => $request->input('data_final'),
                                        "total_vezes" => $request->input('total_vezes'),
                                        "desconto" => $request->input('desconto'),
                                        "status" => $request->input('status'),

                                        "intervalo_pagamento_inicio" => $intervalo_pagamento_inicio,
                                        "intervalo_pagamento_final" => $intervalo_pagamento_final,

                                        "taxa_multa1" => $request->input('taxa_multa1'),
                                        "taxa_multa1_dia" => $request->input('taxa_multa1_dia'),
                                        "taxa_multa2" => $request->input('taxa_multa2'),
                                        "taxa_multa2_dia" => $request->input('taxa_multa2_dia'),
                                        "taxa_multa3" => $request->input('taxa_multa3'),
                                        "taxa_multa3_dia" => $request->input('taxa_multa3_dia'),

                                        "pagamento" => $request->input('pagamento'),
                                        "ano_lectivos_id" => $this->anolectivoActivo(),
                                        "shcools_id" => $this->escolarLogada(),
                                    ]);

                                    $service = Servico::join('tb_taxas', 'tb_servicos.taxa_id', '=', 'tb_taxas.id')
                                        ->select('tb_servicos.id', 'taxa', 'contas', 'tb_servicos.servico', 'tb_servicos.contas')
                                        ->findOrFail($servicos_id);

                                    if ($request->input('servicos_para') == "turmas") {

                                        // update da turma
                                        $update_turma = Turma::findOrFail($item_servivo);
                                        $update_turma->intervalo_pagamento_inicio = $intervalo_pagamento_inicio;
                                        $update_turma->intervalo_pagamento_final = $intervalo_pagamento_final;

                                        $update_turma->taxa_multa1 = $request->input('taxa_multa1');
                                        $update_turma->taxa_multa1_dia = $request->input('taxa_multa1_dia');
                                        $update_turma->taxa_multa2 = $request->input('taxa_multa2');
                                        $update_turma->taxa_multa2_dia = $request->input('taxa_multa2_dia');
                                        $update_turma->taxa_multa3 = $request->input('taxa_multa3');
                                        $update_turma->taxa_multa3_dia = $request->input('taxa_multa3_dia');

                                        if ($service->servico == 'Matricula') {
                                            $update_turma->valor_matricula = $request->input('preco');
                                            $update_turma->valor_matricula_com_iva = $request->input('preco') + ($request->input('preco') * (($service->taxa ?? 0) / 100));
                                        }

                                        if ($service->servico == 'Confirmação') {
                                            $update_turma->valor_confirmacao = $request->input('preco');
                                            $update_turma->valor_confirmacao_com_iva = $request->input('preco') + ($request->input('preco') * (($service->taxa ?? 0) / 100));
                                        }

                                        if ($service->servico == 'Propinas') {
                                            $update_turma->valor_propina = $request->input('preco');
                                            $update_turma->valor_propina_com_iva = $request->input('preco') + ($request->input('preco') * (($service->taxa ?? 0) / 100));
                                        }

                                        $update_turma->update();

                                        // adidcionar os servicos aos clientes
                                        $estudantes = EstudantesTurma::where([
                                            ['turmas_id', '=', $item_servivo],
                                            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                                        ])->get();

                                        if ($estudantes) {
                                            foreach ($estudantes as $estudante) {
                                                // recuperar
                                                if ($request->input('pagamento') == 'mensal') {

                                                    $data_inicio = date("Y-m-d", strtotime($request->input('data_inicio')));
                                                    $data_final = date("Y-m-d", strtotime($request->input('data_final')));

                                                    if ($escola->ensino->nome == "Ensino Superior") {

                                                        for ($i = 1; $i <= $request->input('total_vezes'); $i++) {

                                                            $verificarServicosEstudante = CartaoEstudante::where([
                                                                ['estudantes_id',  '=', $estudante->estudantes_id],
                                                                ['servicos_id',  '=', $servicos_id],
                                                                ['data_at',  '=', date("Y-m-d", strtotime($data_inicio . "+{$i}month"))],
                                                                ['data_exp',  '=', date("Y-m-d", strtotime($data_final . "+{$i}month"))],
                                                                ['ano_lectivos_id',  '=', $this->anolectivoActivo()],
                                                            ])->first();

                                                            if (!$verificarServicosEstudante) {

                                                                if ($i >= 1 && $i <= 6) {
                                                                    $status = '1º Semestre';
                                                                }
                                                                if ($i >= 7 && $i <= 12) {
                                                                    $status = '2º Semestre';
                                                                }

                                                                $create = CartaoEstudante::create([
                                                                    "mes_id" => "M",
                                                                    "estudantes_id" => $estudante->estudantes_id,
                                                                    "servicos_id" => $servicos_id,
                                                                    "preco_unitario" => $request->input('preco'),

                                                                    "data_at" => date("Y-m-d", strtotime($data_inicio . "+{$i}month")),
                                                                    "data_exp" => date("Y-m-d", strtotime($data_final . "+{$i}month")),
                                                                    "month_number" => date("m", strtotime($data_inicio . "+{$i}month")),
                                                                    "month_name" => date("M", strtotime($data_inicio . "+{$i}month")),
                                                                    "trimestral" => 'Normal',
                                                                    "semestral" => $status,
                                                                    "status_2" => 'Normal',
                                                                    "ano_lectivos_id" => $this->anolectivoActivo(),
                                                                    "status" => 'Nao Pago',
                                                                ]);
                                                            }
                                                        }
                                                    } else {

                                                        for ($i = 1; $i <= $request->input('total_vezes'); $i++) {

                                                            $verificarServicosEstudante = CartaoEstudante::where([
                                                                ['estudantes_id',  '=', $estudante->estudantes_id],
                                                                ['servicos_id',  '=', $servicos_id],
                                                                ['data_at',  '=', date("Y-m-d", strtotime($data_inicio . "+{$i}month"))],
                                                                ['data_exp',  '=', date("Y-m-d", strtotime($data_final . "+{$i}month"))],
                                                                ['ano_lectivos_id',  '=', $this->anolectivoActivo()],
                                                            ])->first();

                                                            if (!$verificarServicosEstudante) {

                                                                if ($i >= 1 && $i <= 4) {
                                                                    $status = "1º Trimestre";
                                                                } elseif ($i >= 5 && $i <= 8) {
                                                                    $status = "2º Trimestre";
                                                                } elseif ($i >= 9 && $i <= 12) {
                                                                    $status = "3º Trimestre";
                                                                }

                                                                $create = CartaoEstudante::create([
                                                                    "mes_id" => "M",
                                                                    "estudantes_id" => $estudante->estudantes_id,
                                                                    "servicos_id" => $servicos_id,
                                                                    "preco_unitario" => $request->input('preco'),
                                                                    "data_at" => date("Y-m-d", strtotime($data_inicio . "+{$i}month")),
                                                                    "data_exp" => date("Y-m-d", strtotime($data_final . "+{$i}month")),
                                                                    "month_number" => date("m", strtotime($data_inicio . "+{$i}month")),
                                                                    "month_name" => date("M", strtotime($data_inicio . "+{$i}month")),
                                                                    "trimestral" => $status,
                                                                    "semestral" => 'Normal',
                                                                    "status_2" => 'Normal',
                                                                    "ano_lectivos_id" => $this->anolectivoActivo(),
                                                                    "status" => 'Nao Pago',

                                                                ]);
                                                            }
                                                        }
                                                    }
                                                } else if ($request->input('pagamento') == 'unico') {
                                                    $verificarServicosEstudante = CartaoEstudante::where([
                                                        ['estudantes_id',  '=', $estudante->estudantes_id],
                                                        ['servicos_id',  '=',  $servicos_id],
                                                        ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                                                    ])->first();

                                                    if (!$verificarServicosEstudante) {

                                                        CartaoEstudante::create([
                                                            "mes_id" => "U",
                                                            "estudantes_id" => $estudante->estudantes_id,
                                                            "servicos_id" => $servicos_id,
                                                            "preco_unitario" => $request->input('preco'),

                                                            "data_at" => $request->input('data_inicio'),
                                                            "data_exp" => $request->input('data_final'),
                                                            "month_number" => date("m", strtotime($request->input('data_inicio'))),
                                                            "month_name" => date("M", strtotime($request->input('data_inicio'))),

                                                            "status_2" => 'Normal',
                                                            "trimestral" => 'Normal',
                                                            "semestral" => 'Normal',

                                                            "ano_lectivos_id" => $this->anolectivoActivo(),
                                                            "status" => 'Nao Pago',
                                                        ]);
                                                    }
                                                }
                                            }
                                        }
                                    } else if ($request->input('servicos_para') == "escola") {

                                        if ($request->input('pagamento') == 'mensal') {

                                            $data_inicio = date("Y-m-d", strtotime($request->input('data_inicio')));
                                            $data_final = date("Y-m-d", strtotime($request->input('data_final')));

                                            for ($i = 1; $i <= $request->input('total_vezes'); $i++) {

                                                $verificarServicosEstudante = CartaoEscola::where([
                                                    ['shcools_id',  '=', $item_servivo],
                                                    // ['servicos_id',  '=', $request->input('servicos_id')],
                                                    ['servicos_id',  '=', $servicos_id],
                                                    ['data_at',  '=', date("Y-m-d", strtotime($data_inicio . "+{$i}month"))],
                                                    ['data_exp',  '=', date("Y-m-d", strtotime($data_final . "+{$i}month"))],
                                                    ['ano_lectivos_id',  '=', $this->anolectivoActivo()],
                                                ])->first();

                                                if (!$verificarServicosEstudante) {
                                                    $create = CartaoEscola::create([
                                                        "shcools_id" => $item_servivo,
                                                        "servicos_id" => $servicos_id,
                                                        // "servicos_id" => $request->input('servicos_id'),
                                                        "data_at" => date("Y-m-d", strtotime($data_inicio . "+{$i}month")),
                                                        "data_exp" => date("Y-m-d", strtotime($data_final . "+{$i}month")),
                                                        "month_number" => date("m", strtotime($data_inicio . "+{$i}month")),
                                                        "month_name" => date("M", strtotime($data_inicio . "+{$i}month")),
                                                        "ano_lectivos_id" => $this->anolectivoActivo(),
                                                        "status" => 'Nao Pago',
                                                    ]);
                                                }
                                            }
                                        } else if ($request->input('pagamento') == 'unico') {
                                            $verificarServicosEstudante = CartaoEscola::where([
                                                ['shcools_id',  '=', $item_servivo],
                                                ['servicos_id',  '=', $servicos_id],
                                                // ['servicos_id',  '=', $request->input('servicos_id')],
                                                ['ano_lectivos_id',  '=', $this->anolectivoActivo()],
                                            ])->first();

                                            if (!$verificarServicosEstudante) {
                                                $create = CartaoEscola::create([
                                                    "shcools_id" => $item_servivo,
                                                    "servicos_id" => $servicos_id,
                                                    // "servicos_id" => $request->input('servicos_id'),
                                                    "data_at" => $request->input('data_inicio'),
                                                    "data_exp" => $request->input('data_final'),
                                                    "month_number" => date("m", strtotime($request->input('data_inicio'))),
                                                    "month_name" => date("M", strtotime($request->input('data_inicio'))),
                                                    "ano_lectivos_id" => $this->anolectivoActivo(),
                                                    "status" => 'Nao Pago',
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            // Comita a transação se tudo estiver correto
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            Alert::error('Error', $e->getMessage());
        }
        return response()->json([
            'status' => 200,
            'message' => 'Dados salvos com sucesso!',
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }


    // delete turmas servico
    public function removerServicoTurma($id)
    {
        $user = auth()->user();

        if (!$user->can('delete: turma')  && !$user->can('delete: servicos')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        try {
            DB::beginTransaction();

            ServicoTurma::findOrFail($id)->delete();

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


    // adicionar estudantes individuais
    public function adiocionarEstudanteTurmasIndividual(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "classes_id_add" => 'required',
            "turnos_id_add" => 'required',
            "cursos_id_add" => 'required',
        ], [
            "classes_id_add.required" => "Campo Obrigatório",
            "turnos_id_add.required" => "Campo Obrigatório",
            "cursos_id_add.required" => "Campo Obrigatório",
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        } else {

            $pesquisa = Matricula::where([
                ['tb_matriculas.turnos_id', '=', $request->input('turnos_id_add')],
                ['tb_matriculas.cursos_id', '=', $request->input('cursos_id_add')],
                ['tb_matriculas.classes_id', '=', $request->input('classes_id_add')],
                ['tb_matriculas.ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['tb_matriculas.status_matricula', '=', 'confirmado'],
                ['tb_matriculas.shcools_id', '=', $this->escolarLogada()],
            ])
                ->join('tb_estudantes', 'tb_matriculas.estudantes_id', '=', 'tb_estudantes.id')
                ->join('tb_cursos', 'tb_matriculas.cursos_id', '=', 'tb_cursos.id')
                ->join('tb_classes', 'tb_matriculas.classes_id', '=', 'tb_classes.id')
                ->join('tb_turnos', 'tb_matriculas.turnos_id', '=', 'tb_turnos.id')
                ->select('tb_matriculas.numero_estudante', 'tb_matriculas.documento', 'tb_estudantes.id', 'tb_estudantes.nome', 'tb_estudantes.sobre_nome', 'tb_cursos.curso', 'tb_cursos.id AS idCurso', 'tb_classes.classes', 'tb_classes.id AS idClasse', 'tb_turnos.turno', 'tb_turnos.id AS idTurno')
                ->get();

            if (!$pesquisa) {
                return response()->json([
                    'status' => 300,
                    'message' => "Esta pesquisa não gerou resultados tenta novamente",
                ]);
            } else {
                $turma = Turma::where([
                    ['turnos_id', '=', $request->input('turnos_id_add')],
                    ['cursos_id', '=', $request->input('cursos_id_add')],
                    ['classes_id', '=', $request->input('classes_id_add')],
                    ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ])->first();

                return response()->json([
                    'status' => 200,
                    'results' => $pesquisa,
                    'turma' => $turma,
                    "usuario" => User::findOrFail(Auth::user()->id),
                ]);
            }
        }
    }

    public function adiocionarEstudanteTurmasIndividualConcluir(Request $request)
    {

        $validate = Validator::make($request->all(), [
            "idEstuadnte" => 'required',
            "idCurso" => 'required',
            "idClasse" => 'required',
            "idTurno" => 'required',
            "idTurma" => 'required',
        ], [
            "idEstuadnte.required" => "Campo Obrigatório",
            "idCurso.required" => "Campo Obrigatório",
            "idClasse.required" => "Campo Obrigatório",
            "idTurno.required" => "Campo Obrigatório",
            "idTurma.required" => "Campo Obrigatório",
        ]);

        $varificarTurma = Turma::where([
            ['cursos_id', '=', $request->input('idCurso')],
            ['turnos_id', '=', $request->input('idTurno')],
            ['classes_id', '=', $request->input('idClasse')],
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
        ])->first();

        if (!$varificarTurma) {
            return response()->json([
                'status' => 300,
                'message' => "Não Existe nenhuma turma cadastrada com os dados pesquisado dos: CURSO, CLASSE e TURNO! ",
            ]);
        }

        $varificarEstudante = EstudantesTurma::where([
            ['turmas_id', '=', $request->input('idTurma')],
            ['estudantes_id', '=', $request->input('idEstuadnte')],
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
        ])->first();

        if ($varificarEstudante) {
            return response()->json([
                'status' => 300,
                'message' => "Este estudante já esta nesta Turma",
            ]);
        }

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        } else {

            $totalEstudanteTurma = EstudantesTurma::where([
                ['turmas_id', $varificarTurma->id]
            ])->count();

            $novoNumero = $totalEstudanteTurma + 1;

            $addEstudante = new EstudantesTurma();
            $addEstudante->estudantes_id = $request->input('idEstuadnte');
            $addEstudante->turmas_id = $varificarTurma->id;
            $addEstudante->status = 'activo';
            $addEstudante->ordem = "EST Nº {$novoNumero}/{$varificarTurma->turma}";
            $addEstudante->ano_lectivos_id = $this->anolectivoActivo();

            if ($addEstudante->save()) {
                return response()->json([
                    'status' => 200,
                    'results' => 'Estudante Adicionado com sucesso',
                    "usuario" => User::findOrFail(Auth::user()->id),
                ]);
            }
        }
    }

    #TODOS
    public function adicionarProfessorTurma(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "funcionarios_id" => 'required',
            "turmas_id_load" => 'required',
            "disciplinas_id" => 'required',
            "cargo" => 'required',
        ], [
            "funcionarios_id.required" => "Campo Obrigatório",
            "turmas_id_load.required" => "Campo Obrigatório",
            "disciplinas_id.required" => "Campo Obrigatório",
            "cargo.required" => "Campo Obrigatório",
        ]);

        $varificarDisciplina = FuncionariosTurma::where([
            ['disciplinas_id', '=', $request->input('disciplinas_id')],
            ['turmas_id', '=', $request->input('turmas_id_load')],
        ])->first();

        if ($varificarDisciplina) {
            return response()->json([
                'status' => 300,
                'message' => "Provavelmente essa disciplina já esta adicionada nesta turma",
            ]);
        }

        $varificarTurma = FuncionariosTurma::where([
            ['turmas_id', '=', $request->input('turmas_id_load')],
            ['funcionarios_id', '=', $request->input('funcionarios_id')],
            ['disciplinas_id', '=', $request->input('disciplinas_id')],
            ['cargo_turma', '=', $request->input('cargo')],
        ])->first();

        if ($varificarTurma) {
            return response()->json([
                'status' => 300,
                'message' => "Este professor Já Esta Inserido neste turma com essa disciplina",
            ]);
        }
        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        } else {

            $addProfessor = new FuncionariosTurma();
            $addProfessor->turmas_id = $request->input('turmas_id_load');
            $addProfessor->funcionarios_id = $request->input('funcionarios_id');
            $addProfessor->disciplinas_id = $request->input('disciplinas_id');
            $addProfessor->cargo_turma = $request->input('cargo');
            $addProfessor->tempo_edicao = date("Y-m-d");
            $addProfessor->ano_lectivos_id = $this->anolectivoActivo();
            $addProfessor->shcools_id = $this->escolarLogada();

            $addProfessor->save();

            return response()->json([
                'status' => 200,
                'message' => 'Professor Adicionado com sucesso',
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        }
    }

    // gerar lista presenca
    public function gerarListaPresenca(Request $request)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO


        $validate = Validator::make(
            $request->all(),
            [
                "professores_id" => 'required',
                "disciplinas_id" => 'required',
                "dias_semanas_ano" => 'required',
                "turma_id" => 'required',
            ],
            [
                "professores_id.required" => "Campo Obrigatório",
                "disciplinas_id.required" => "Campo Obrigatório",
                "dias_semanas_ano.required" => "Campo Obrigatório",
                "turma_id.required" => "Campo Obrigatório",
            ]
        );

        $verificar = ListaPresenca::where([
            ['disciplinas_id', '=', $request->input('disciplinas_id')],
            ['turmas_id', '=', $request->input('turma_id')],
            ['funcionarios_id', '=', $request->input('professores_id')],
            ['semanas_id', '=', $request->input('dias_semanas_ano')],
            ['data_at', '=', "Y-m-d"],
        ])->first();

        if ($verificar) {
            return response()->json([
                'status' => 300,
                'message' => "Já Existe uma lista de presença gerada para esta turma hoje!",
            ]);
        }

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        } else {

            $estudanteTurma = EstudantesTurma::where([
                ['turmas_id', '=', $request->input('turma_id')],
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ])->get();

            if ($estudanteTurma) {
                foreach ($estudanteTurma as $turmas) {
                    $verificar1 = ListaPresenca::where([
                        ['disciplinas_id', '=', $request->input('disciplinas_id')],
                        ['turmas_id', '=', $request->input('turma_id')],
                        ['estudantes_id', '=', $turmas->estudantes_id],
                        ['funcionarios_id', '=', $request->input('professores_id')],
                        ['semanas_id', '=', $request->input('dias_semanas_ano')],
                        ['data_at', '=', "Y-m-d"],
                    ])->first();

                    if (!$verificar1) {
                        $create = new ListaPresenca();
                        $create->data_at = "Y-m-d";
                        $create->semanas_id =  $request->input('dias_semanas_ano');
                        $create->status = NULL;
                        $create->estudantes_id = $turmas->estudantes_id;
                        $create->funcionarios_id = $request->input('professores_id');
                        $create->disciplinas_id = $request->input('disciplinas_id');
                        $create->turmas_id = $request->input('turma_id');

                        $create->save();
                    }
                }
            }

            return response()->json([
                'status' => 200,
                'message' => 'Lista Gerada com sucesso!',
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        }
    }

    public function carregamentoValoresForm($id)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO

        $nota_id = NotaPauta::findOrFail($id);

        $usuario = User::findOrFail(Auth::user()->id);

        return response()->json([
            "status" => 200,
            "notas" => $nota_id,
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }

    // Função auxiliar para validar valores
    function isInvalid($value)
    {
        if (!is_numeric($value)) {
            return true;
        }

        $value = (float) $value;

        return $value <= -1 || $value >= 21;
    }

    public function finalizarLancamentoNota(Request $request)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());
     
        $dados = $request->only([
            'p1',
            'p2',
            'p3',
            'p4',
            'exame_1_especial',
            'exame_especial',
            'recurso',
            'pt',
            'pap',
            'ne',
            'nr',
            'npt',
            'npp',
            'mac'
        ]);

        foreach ($dados as $campo => $valor) {
            if ($escola->ensino->nome == "Ensino Superior" && in_array($campo, ['p1', 'p2', 'p3', 'p4', 'exame_1_especial', 'exame_especial', 'recurso'])) {
                if (!isset($valor) || !is_numeric($valor) || $valor < 0 || $valor > 20) {
                    return response()->json([
                        'status' => 300,
                        'errors' => "O campo {$campo} deve ser preenchido com um valor entre 0 e 20. Valor recebido: '{$valor}'.",
                    ]);
                }
            }

            if ($escola->ensino->nome != "Ensino Superior" && !in_array($campo, ['p1', 'p2', 'p3', 'p4', 'exame_1_especial', 'exame_especial', 'recurso'])) {
                if (!isset($valor) || !is_numeric($valor) || $valor < 0 || $valor > 20) {
                    return response()->json([
                        'status' => 300,
                        'errors' => "O campo {$campo} deve ser preenchido com um valor entre 0 e 20. Valor recebido: '{$valor}'.",
                    ]);
                }
            }
        }

        $validate = Validator::make($request->all(), [
            // 'nota1' => 'required',
        ], []);

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        } else {

            $updateNota = NotaPauta::findOrFail($request->notas_id);
            $turma = Turma::findOrFail($updateNota->turmas_id);

            if ($updateNota->conf_pro == 'sim') {
                return response()->json([
                    'status' => 300,
                    'errors' => 'Infelizmente essas notas já não podem ser alteradas uma vez já confirmadas!',
                ]);
            }

            $trimestre = Trimestre::findOrFail($updateNota->controlo_trimestres_id);
            // recuperar o primeiro trimestre do ano lectivo para pesquisar segundo o trimestre
            $trimestre1 = Trimestre::where('trimestre', 'Iª Trimestre')->first();
            // recuperar o segundo trimestre do ano lectivo para pesquisar segundo o trimestre
            $trimestre2 = Trimestre::where('trimestre', 'IIª Trimestre')->first();
            // recuperar o terceiro trimestre do ano lectivo para pesquisar segundo o trimestre
            $trimestre3 = Trimestre::where('trimestre', 'IIIª Trimestre')->first();
            // recuperar o quarto trimestre do ano lectivo para pesquisar segundo o trimestre
            $trimestre4 = Trimestre::where('trimestre', 'Geral')->first();

            if ($escola->ensino->nome == "Ensino Superior") {
                $updateNota->p1 = $request->p1;
                $updateNota->p2 = $request->p2;
                $updateNota->p3 = $request->p3;
                $updateNota->p4 = $request->p4;

                $updateNota->exame_1_especial = $request->exame_1_especial;
                $updateNota->nr = $request->nr;
                $updateNota->exame_especial = $request->exame_especial;

                $primiero_media = ($request->p1 + $request->p2) / 2;

                if ($primiero_media >= $escola->nota_maxima) {
                    $updateNota->obs1 = "Dispensado";
                    $updateNota->resultado_final = $primiero_media;
                    $updateNota->med = $primiero_media;
                    $updateNota->obs3 = "Apto";
                }

                if ($primiero_media < $escola->nota_maxima) {
                    $updateNota->resultado_final = $primiero_media;
                    $updateNota->med = $primiero_media;
                    $updateNota->obs1 = "Exame";
                    $updateNota->obs3 = "Não Apto";

                    if ($request->exame_1_especial != '0') {
                        $media_exame = ($updateNota->med + $request->exame_1_especial) / 2;

                        if ($media_exame >= $escola->nota_maxima_exame) {
                            $updateNota->obs2 = "Apto";
                            $updateNota->resultado_final = $media_exame;
                            $updateNota->media_final = $media_exame;
                            $updateNota->obs3 = "Apto";
                        } else {
                            $updateNota->obs2 = "Recurso";
                            $updateNota->resultado_final = $media_exame;
                            $updateNota->media_final = $media_exame;
                            $updateNota->obs3 = "Não Apto";

                            if ($request->nr != '0') {
                                $media_exame_recurso = ($updateNota->media_final + $request->nr) / 2;

                                if ($media_exame_recurso >= $escola->nota_maxima_exame) {
                                    $updateNota->obs3 = "Apto";
                                    $updateNota->resultado_final = $media_exame_recurso;
                                    $updateNota->media_final = $media_exame_recurso;
                                } else {

                                    $updateNota->obs3 = "Não Apto";
                                    $updateNota->resultado_final = $media_exame_recurso;
                                    $updateNota->media_final = $media_exame_recurso;

                                    if ($request->exame_especial != '0') {
                                        $media_exame_especial = ($updateNota->resultado_final + $request->exame_especial) / 2;

                                        if ($media_exame_especial >= $escola->nota_maxima_exame) {
                                            $updateNota->obs3 = "Apto";
                                            $updateNota->resultado_final = $media_exame_especial;
                                        } else {
                                            $updateNota->obs3 = "Não Apto";
                                            $updateNota->resultado_final = $media_exame_especial;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                $updateNota->funcionarios_id = Auth::user()->id;
                $updateNota->descricao = $request->descricao_estudante;
                $updateNota->update();
            } else {
                
                $updateNota->funcionarios_id = Auth::user()->id;

                $updateNota->descricao = $request->descricao_estudante;
                
                $media_trimestral = 0;
                
                if ($turma->curso->tipo === "Técnico") {
                    $updateNota->mac = $request->mac;
                    $updateNota->npt = $request->npt;
                    $updateNota->npp = $request->npp;
                    $media_trimestral = ($request->npt + $request->mac + $request->npp) / 3;
                }
                if ($turma->curso->tipo === "Punível") {
                    $updateNota->mac = $request->mac;
                    $updateNota->npt = $request->npt;
                    $updateNota->npp = $request->npp;
                    $media_trimestral = ($request->npt + $request->mac + $request->npp) / 3;
                }
                if ($turma->curso->tipo === "Outros") {
                    $updateNota->mac = $request->mac;
                    $updateNota->npt = $request->npt;
                    
                    $media_trimestral = ($request->npt + $request->mac) / 2;
                }
                
                $updateNota->mt = $media_trimestral;
               
                if ($trimestre->trimestre == 'Iª Trimestre') {
                    $updateNota->mt1 = $media_trimestral;
                    $updateNota->mt = $media_trimestral;
                    // cadeira dispesada
                    if ($updateNota->mt1 >= $escola->nota_maxima) {
                        $updateNota->obs = "Apto";
                        $updateNota->update();
                    } else {
                        // reprovado
                        $updateNota->obs = "Não Apto";
                        $updateNota->update();
                    }
                }
                if ($trimestre->trimestre == 'IIª Trimestre') {
                    $updateNota->mt2 = $media_trimestral;
                    $updateNota->mt = $media_trimestral;
                    // cadeira dispesada
                    if ($updateNota->mt2 >= $escola->nota_maxima) {
                        $updateNota->obs = "Apto";
                    } else {
                        // reprovado
                        $updateNota->obs = "Não Apto";
                    }
                }
                if ($trimestre->trimestre == 'IIIª Trimestre') {
                    $updateNota->mt3 = $media_trimestral;
                    $updateNota->mt = $media_trimestral;
                    // cadeira dispesada
                    if ($updateNota->mt3 >= $escola->nota_maxima) {
                        $updateNota->obs = "Apto";
                    } else {
                        // reprovado
                        $updateNota->obs = "Não Apto";
                    }
                }
                
                $updateNota->ne = $request->ne ?? 0;
                $updateNota->nr = $request->nr ?? 0;
                $updateNota->pt = $request->pt ?? 0;
                $updateNota->pap = $request->pap ?? 0;

                $updateNota->update();

                // pesquisar as notas do primeiro trimestre deste aluno, turma, disciplina, e trimestre afim de recuperar a soma das notas do primeiro trimestre
                $notaTrimestre1 = NotaPauta::where('estudantes_id', $updateNota->estudantes_id)
                    ->where('disciplinas_id', $updateNota->disciplinas_id)
                    ->where('turmas_id', $updateNota->turmas_id)
                    ->where('controlo_trimestres_id', $trimestre1->id)
                    ->first();

                // pesquisar as notas do segundo trimestre deste aluno, turma, disciplina, e trimestre afim de recuperar a soma das notas do secundo trimestre
                $notaTrimestre2 = NotaPauta::where('estudantes_id', $updateNota->estudantes_id)
                    ->where('disciplinas_id', $updateNota->disciplinas_id)
                    ->where('turmas_id', $updateNota->turmas_id)
                    ->where('controlo_trimestres_id', $trimestre2->id)
                    ->first();

                // pesquisar as notas do terceiro trimestre deste aluno, turma, disciplina, e trimestre afim de recuperar a soma das notas do terceiro trimestre
                $notaTrimestre3 = NotaPauta::where('estudantes_id', $updateNota->estudantes_id)
                    ->where('disciplinas_id', $updateNota->disciplinas_id)
                    ->where('turmas_id', $updateNota->turmas_id)
                    ->where('controlo_trimestres_id', $trimestre3->id)
                    ->first();

                // pesquisar as notas do quarto trimestre deste aluno, turma, disciplina, e trimestre afim de recuperar a soma das notas do quarto trimestre
                $notaTrimestre4 = NotaPauta::where('estudantes_id', $updateNota->estudantes_id)
                    ->where('disciplinas_id', $updateNota->disciplinas_id)
                    ->where('turmas_id', $updateNota->turmas_id)
                    ->where('controlo_trimestres_id', $trimestre4->id)
                    ->first();

                $updateMFD = NotaPauta::findOrFail($notaTrimestre4->id);
                
                // somar as notas do MT1, MT2, MT3
                $mfd = ($notaTrimestre1->mt1 + $notaTrimestre2->mt2 + $notaTrimestre3->mt3) / 3;
                
                $mf = (($request->ne ?? $updateMFD->ne) * 0.6) + ($mfd * 0.4);
              
                // AG18 < 9.5 E AF18 < 9.5 → N/Transita
                if ($request->nr < 9.5 && $mf < 9.5) {
                    $updateMFD->obs = "Não Apto";
                }
            
                // AG18 >= 9.5 OU AF18 >= 9.5 → Transita
                if ($request->nr >= 9.5 || $mf >= 9.5) {
                    $updateMFD->obs = "Apto";
                }
                
                $updateMFD->mfd = $mfd;
                $updateMFD->mf = $mf;
                
                $updateMFD->mt1 = $notaTrimestre1->mt1;
                $updateMFD->mt2 = $notaTrimestre2->mt2;
                $updateMFD->mt3 = $notaTrimestre3->mt3;
                
                $updateMFD->ne = ($request->ne ?? $updateMFD->ne);
                $updateMFD->nr = $request->nr;
                $updateMFD->update();
            }

            return response()->json([
                'status' => 200,
                'message' => 'Notas Lançadaas com sucesso!',
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        }
    }

    // view turmas estatistica
    public function estatisticaTurmas()
    {
        $aprovados = 0;
        $reprovados = 0;
        // =================================

        $matriculass = Matricula::where([
            ['shcools_id', '=', $this->escolarLogada()],
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['status_matricula', '=', 'confirmado'],
        ])->get();

        if (count($matriculass) != 0) {
            foreach ($matriculass as $est) {
                $estudante = Estudante::findOrFail($est->estudantes_id);

                $turmasEstudante = EstudantesTurma::where([
                    ['estudantes_id', '=', $estudante->id],
                    ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ])->first();

                if ($turmasEstudante) {
                    $turma = Turma::findOrFail($turmasEstudante->turmas_id);
                    $classe = Classe::findOrFail($turma->classes_id);


                    $totalDisciplinas = DisciplinaTurma::where([
                        ['turmas_id', '=', $turma->id]
                    ])->count('id');

                    $turma = Turma::findOrFail($turmasEstudante->turmas_id);
                    $classe = Classe::findOrFail($turma->classes_id);

                    $totalDisciplinas = DisciplinaTurma::where([
                        ['turmas_id', '=', $turma->id]
                    ])->count('id');
                }


                $trimestre = ControlePeriodico::where('trimestre', '=', 'Geral')->first();

                $notasSomaMdf = NotaPauta::where([
                    ['estudantes_id', '=', $estudante->id],
                    ['controlo_trimestres_id', '=', $trimestre->id],
                    ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ])->sum('mfd');

                $verificarNotasAlteradas = NotaPauta::where([
                    ['estudantes_id', '=', $estudante->id],
                    ['controlo_trimestres_id', '=', $trimestre->id],
                    ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ])
                ->get();

                if ($verificarNotasAlteradas) {
                    foreach ($verificarNotasAlteradas as $nota) {
                        if (($notasSomaMdf /  $totalDisciplinas) >= ($classe->tipo_avaliacao_nota / 2)) {
                            $aprovados++;
                        } else {
                            $reprovados++;
                        }
                    }
                }
            }
        }

        $matriculas = Matricula::where([
            ['shcools_id', $this->escolarLogada()],
            ['ano_lectivos_id', $this->anolectivoActivo()],
            ['status_matricula', 'confirmado'],
        ])->count();

        $matriculasConfirmado = Matricula::where([
            ['shcools_id', $this->escolarLogada()],
            ['ano_lectivos_id', $this->anolectivoActivo()],
            ['status_matricula', 'confirmado'],
            ['status', 'Novo'],
        ])->count();

        $matriculasAntigas = Matricula::where([
            ['shcools_id', $this->escolarLogada()],
            ['ano_lectivos_id', $this->anolectivoActivo()],
            ['status_matricula', 'confirmado'],
            ['status', 'Antigo'],
        ])->count();

        $transferidos = Matricula::where([
            ['shcools_id', $this->escolarLogada()],
            ['ano_lectivos_id', $this->anolectivoActivo()],
            ['status_matricula', 'confirmado'],
            ['status', 'Transferido'],
        ])->count();

        $desistentes = Matricula::where([
            ['shcools_id', $this->escolarLogada()],
            ['ano_lectivos_id', $this->anolectivoActivo()],
            ['status_matricula', 'confirmado'],
            ['status', 'Destistente'],
        ])->count();


        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Estatística",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "anoLectivo" => AnoLectivo::findOrFail($this->anolectivoActivo()),
            "matriculasConfirmado" => $matriculasConfirmado,
            "matriculasAntigas" => $matriculasAntigas,
            "matriculas" => $matriculas,
            "aprovados" => $aprovados,
            "reprovados" => $reprovados,
            "transferidos" => $transferidos,
            "desistentes" => $desistentes,
        ];

        return view('admin.turmas.estatisticas', $headers);
    }

    // view turmas faltas
    public function faltasTurmas()
    {




        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "Estatística",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.turmas.faltas', $headers);
    }

    // view turmas faltas funcionarios
    public function faltasTurmasEstudantes()
    {



        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "turmas" => Turma::where('shcools_id', $this->escolarLogada())
                ->where('ano_lectivos_id', $this->anolectivoActivo())
                ->get(),

            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.turmas.faltas-estudantes', $headers);
    }

    // view turmas faltas funcionarios
    public function faltasTurmasEstudantesJustificar()
    {


        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "turmas" => Turma::where('shcools_id', $this->escolarLogada())
                ->where('ano_lectivos_id', $this->anolectivoActivo())
                ->get(),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.turmas.faltas-estudantes-justificar', $headers);
    }

    public function faltasTurmasEstudantesPost(Request $request)
    {

        $validate = Validator::make(
            $request->all(),
            [
                "funcionario" => 'required',
                "disciplina" => 'required',
                "turma" => 'required',
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        } else {

            $estudanteTurma = EstudantesTurma::where('turmas_id', $request->turma)
                ->where('ano_lectivos_id', $this->anolectivoActivo())
                ->get();

            if ($estudanteTurma) {
                foreach ($estudanteTurma as $turmas) {

                    $verificar = ListaPresenca::where([
                        ['disciplinas_id', '=', $request->disciplina],
                        ['turmas_id', '=', $request->turma],
                        ['estudantes_id', '=', $turmas->estudantes_id],
                        ['funcionarios_id', '=', $request->funcionario],
                        ['semanas_id', '=', getdate()['weekday']],
                        ['mes', '=', getdate()['month']],
                        ['dia', '=', getdate()['mday']],
                        ['data_at', '=', date("Y-m-d")],
                    ])->first();

                    if (!$verificar) {
                        ListaPresenca::create([
                            "data_at" => date("Y-m-d"),
                            "semanas_id" => getdate()['weekday'],
                            "status" => 'desactivo',
                            "mes" => getdate()['month'],
                            "dia" => getdate()['mday'],
                            "estudantes_id" => $turmas->estudantes_id,
                            "funcionarios_id" => $request->funcionario,
                            "disciplinas_id" => $request->disciplina,
                            "turmas_id" => $request->turma,
                            "shcools_id" => $this->escolarLogada(),
                        ]);
                    }
                }
            }

            return response()->json([
                'status' => 200,
                'message' => 'Lista Gerada com sucesso!',
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        }
    }

    public function faltasTurmasEstudantesJustificarPost(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "disciplina" => 'required',
            "turma" => 'required',
            "funcionario" => 'required',
            "meses" => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        }

        $select = ListaPresenca::where([
            ['disciplinas_id', '=', $request->disciplina],
            ['turmas_id', '=', $request->turma],
            ['funcionarios_id', '=', $request->funcionario],
            ['mes', '=', $request->meses],
            ['data_at', '=', date("Y-m-d")],
        ])
            ->join('tb_estudantes', 'tb_turma_presencas.estudantes_id', '=', 'tb_estudantes.id')
            ->select('tb_estudantes.nome', 'tb_estudantes.sobre_nome', 'tb_turma_presencas.status', 'tb_turma_presencas.id')
            ->get();

        if (count($select) != 0) {
            return response()->json([
                'status' => 200,
                'resultado' => $select,
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        } else {
            return response()->json([
                'status' => 300,
                'message' => 'Sem resultado!',
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        }
    }

    // view turmas faltas funcionarios
    public function faltasTurmasFuncionarios()
    {


        $mapaEfectividade = MapaEfectividade::where([
            ['tb_mapa_efectividade.shcools_id', '=', $this->escolarLogada()],
            ['tb_mapa_efectividade.ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['tb_mapa_efectividade.data_at', '=', date("Y-m-d")],
        ])
            ->join('tb_professores', 'tb_mapa_efectividade.funcionarios_id', '=', 'tb_professores.id')
            ->join('tb_ano_lectivos', 'tb_mapa_efectividade.ano_lectivos_id', '=', 'tb_ano_lectivos.id')
            ->select('tb_mapa_efectividade.faltas', 'tb_mapa_efectividade.status', 'tb_mapa_efectividade.dia', 'tb_mapa_efectividade.id', 'tb_mapa_efectividade.dia_semana', 'tb_mapa_efectividade.mes', 'tb_professores.nome', 'tb_professores.sobre_nome', 'tb_professores.telefone', 'tb_ano_lectivos.ano')
            ->get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "usuario" => User::findOrFail(Auth::user()->id),
            "mapaEfectividade" => $mapaEfectividade
        ];


        return view('admin.turmas.faltas-funcionarios', $headers);
    }

    public function faltasTurmasFuncionariosGet()
    {
        $contratos = FuncionariosControto::where([
            ['shcools_id', '=', $this->escolarLogada()],
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['cargo_geral', '=', 'Professor'],
        ])->get();

        if (getdate()['month'] == "January") {
            $mes = "Janeiro";
        }
        if (getdate()['month'] == "February") {
            $mes = "Fevereiro";
        }
        if (getdate()['month'] == "March") {
            $mes = "Março";
        }
        if (getdate()['month'] == "April") {
            $mes = "Abril";
        }
        if (getdate()['month'] == "May") {
            $mes = "Maio";
        }
        if (getdate()['month'] == "June") {
            $mes = "Junho";
        }
        if (getdate()['month'] == "July") {
            $mes = "Julho";
        }
        if (getdate()['month'] == "August") {
            $mes = "Agosto";
        }
        if (getdate()['month'] == "September") {
            $mes = "Setembro";
        }
        if (getdate()['month'] == "October") {
            $mes = "Outubro";
        }
        if (getdate()['month'] == "November") {
            $mes = "Novembro";
        }
        if (getdate()['month'] == "December") {
            $mes = "Dezembro";
        }


        if (getdate()['weekday'] == "Sunday") {
            $dia_semana = "Domingo";
        }
        if (getdate()['weekday'] == "Monday") {
            $dia_semana = "Segunda-feira";
        }
        if (getdate()['weekday'] == "Tuesday") {
            $dia_semana = "Terça-feira";
        }
        if (getdate()['weekday'] == "Wednesday") {
            $dia_semana = "Quarta-feira";
        }
        if (getdate()['weekday'] == "Thursday") {
            $dia_semana = "Quinta-feira";
        }
        if (getdate()['weekday'] == "Friday") {
            $dia_semana = "Sexta-feira";
        }
        if (getdate()['weekday'] == "Saturday") {
            $dia_semana = "Sábado";
        }


        foreach ($contratos as $contrato) {

            $funcionario = Professor::findOrFail($contrato->funcionarios_id);

            $verificar = MapaEfectividade::where([
                ['shcools_id', '=', $this->escolarLogada()],
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['funcionarios_id', '=', $funcionario->id],
                ['mes', '=', $mes],
                ['dia', '=', getdate()['mday']],
                ['dia_semana', '=', $dia_semana],
                ['data_at', '=', date("Y-m-d")],
            ])->first();

            if (!$verificar) {
                MapaEfectividade::create([
                    'mes' => $mes,
                    'dia' => getdate()['mday'],
                    'dia_semana' =>    $dia_semana,
                    'funcionarios_id' => $funcionario->id,
                    'data_at' => date('Y-m-d'),
                    'ano_lectivos_id' => $this->anolectivoActivo(),
                    'shcools_id' => $this->escolarLogada(),
                ]);
            }
        }

        Alert::success('Bom Trabalho', 'Lista geral com sucesso');
        return redirect()->back();
    }

    public function faltasTurmasFuncionariosPost(Request $request)
    {


        $mapa = MapaEfectividade::findOrFail($request->controlo_id);
        $mapa->faltas = $request->controlo_faltas;
        $mapa->status = $request->controlo_status;
        $mapa->update();

        return response()->json([
            'status' => 200,
            'message' => 'Dados salvos com sucesso!',
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }

    public function faltasTurmasFuncionariosjustificar($request)
    {
        $mapa = MapaEfectividade::findOrFail($request);
        $mapa->status = "Justificado";
        $mapa->update();

        return response()->json([
            'status' => 200,
            'message' => 'Dados salvos com sucesso!',
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }

    // view turmas efectividade
    public function mapaEfectividade(Request $request)
    {
        //Manipulação de datas: data actual

        $data_inicio = date("Y-m-01");
        $data_final = date("Y-m-31");

        if ($request->data_inicio) {
            $request->data_inicio = $request->data_inicio;
        } else {
            $request->data_inicio = $data_inicio;
        }

        if ($request->data_final) {
            $request->data_final = $request->data_final;
        } else {
            $request->data_final = $data_final;
        }




        $professores = FuncionariosControto::with(['funcionario'])->where('shcools_id', '=', $this->escolarLogada())
            ->where('level', '4')
            ->where('cargo_geral', 'professor')
            ->where('status', 'activo')
            ->get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "Mapa de Efectivadade",
            "descricao" => env('APP_NAME'),
            "anoLectvosEscola" => AnoLectivo::where([
                ['shcools_id', '=', $this->escolarLogada()],
            ])
                ->orderByRaw(" status DESC")
                ->get(),

            "usuario" => User::findOrFail(Auth::user()->id),
            "professores" => $professores,
            "requests" => $request->all("data_inicio", "data_final"),
            "data_inicio" => $data_inicio,
            "data_final" => $data_final,

        ];

        return view('admin.turmas.mapa-efectividade', $headers);
    }

    // view turmas documento
    public function documentacaoEstudante()
    {
        $user = auth()->user();

        if (!$user->can('read: matricula') && !$user->can('read: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $matriculas = Matricula::where([
            ['tb_matriculas.ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['tb_matriculas.status_matricula', '=', 'confirmado'],
            ['tb_estudantes.registro', '=', 'confirmado'],
            ['tb_matriculas.shcools_id', '=', $this->escolarLogada()],
            ['tb_estudantes.deleted_at', '=', NULL],
        ])
            ->join('tb_estudantes', 'tb_matriculas.estudantes_id', '=', 'tb_estudantes.id')
            ->join('tb_cursos', 'tb_matriculas.cursos_id', '=', 'tb_cursos.id')
            ->join('tb_classes', 'tb_matriculas.classes_id', '=', 'tb_classes.id')
            ->join('tb_turnos', 'tb_matriculas.turnos_id', '=', 'tb_turnos.id')
            ->select(
                'tb_matriculas.documento',
                'tb_estudantes.id',
                'tb_estudantes.nome',
                'tb_estudantes.bilheite',
                'tb_estudantes.sobre_nome',
                'tb_matriculas.estudantes_id',
                'tb_classes.classes',
                'tb_cursos.curso',
                'tb_turnos.turno',
                'tb_matriculas.ano_lectivos_id'
            )
            ->orderBy('tb_estudantes.nome', 'ASC')
            ->get();



        $efeitos = Efeito::get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "Processo e documentos dos estudantes",
            "descricao" => env('APP_NAME'),
            "efeitos" => $efeitos,
            "usuario" => User::findOrFail(Auth::user()->id),
            "estudantes" => $matriculas,
            "ano_lectivo" => AnoLectivo::findOrFail($this->anolectivoActivo()),
        ];

        return view('admin.turmas.documentacao-estudantes', $headers);
    }

    // declaracao dos estudantes
    // view turmas efectividade
    public function declaracaoEstudante($id)
    {
        $user = auth()->user();

        if (!$user->can('read: matricula') && !$user->can('read: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $estudante = Estudante::findOrFail(Crypt::decrypt($id));



        $efeitos = Efeito::get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "usuario" => User::findOrFail(Auth::user()->id),
            "estudante" => $estudante,
            "efeitos" => $efeitos,
            "ano_lectivo" => AnoLectivo::findOrFail($this->anolectivoActivo()),
            "anolectivos" => AnoLectivo::where([
                ['shcools_id', $this->escolarLogada()]
            ])->get(),
        ];

        return view('admin.turmas.declaracao-estudante', $headers);
    }

    //  turmas notas estudante
    public function notasEstudante($id)
    {
        $user = auth()->user();

        if (!$user->can('read: matricula') && !$user->can('read: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $setudante = Estudante::findOrFail(Crypt::decrypt($id));



        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        if ($escola->ensino->nome == "Ensino Superior") {
            $trimestres = ControlePeriodico::where('ensino_status', '2')->get();
        } else {
            $trimestres = ControlePeriodico::where('trimestre', '<>', 'Geral')->where('ensino_status', '1')->get();
        }

        $headers = [
            "escola" =>  $escola,

            "usuario" => User::findOrFail(Auth::user()->id),
            "estudante" => $setudante,
            "ano_lectivo" => AnoLectivo::findOrFail($this->anolectivoActivo()),
            "anolectivos" => AnoLectivo::where([
                ['shcools_id', '=', $this->escolarLogada()]
            ])->get(),
            "trimestres" => $trimestres,
        ];

        return view('admin.turmas.notas-estudante', $headers);
    }

    // ANO LECTIVO ACTIVO
    public function escolarLogadaAdmin()
    {
        $admin = User::where([
            ['acesso', '=', 'admin']
        ])->first();

        return $admin->id;
    }

    function calcularQuantidadeEntreNumeros($numeroInicial, $numeroFinal)
    {
        // Verifica qual número é maior e ajusta o intervalo
        $inicio = min($numeroInicial, $numeroFinal);
        $fim = max($numeroInicial, $numeroFinal);

        // Calcula a quantidade de números no intervalo, incluindo ambos os valores
        return ($fim - $inicio) + 1;
    }
}
