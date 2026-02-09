<?php

namespace App\Http\Controllers;

use App\Models\Arquivo;
use App\Models\FormaPagamento;
use App\Models\Notificacao;
use App\Models\Shcool;
use App\Models\User;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\anolectivo\ControlePeriodico;
use App\Models\web\calendarios\Confirmacao;
use App\Models\web\calendarios\Matricula;
use App\Models\web\calendarios\Pagamento;
use App\Models\web\calendarios\Servico;
use App\Models\web\calendarios\ServicoTurma;
use App\Models\web\classes\AnoLectivoClasse;
use App\Models\web\classes\Classe;
use App\Models\web\cursos\AnoLectivoCurso;
use App\Models\web\cursos\Curso;
use App\Models\web\estudantes\CartaoEstudante;
use App\Models\web\estudantes\Estudante;
use App\Models\web\financeiros\DetalhesPagamentoPropina;
use App\Models\web\salas\AnoLectivoSala;
use App\Models\web\salas\Caixa;
use App\Models\web\salas\MovimentoCaixa;
use App\Models\web\turmas\Turma;
use App\Models\web\turnos\AnoLectivoTurno;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

use Carbon\Carbon;
use NumberFormatter;
use phpseclib\Crypt\RSA;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class EstudanteConfirmacaoController extends Controller
{
    use TraitHelpers;
    use TraitChavesSaft;

    public function __construct()
    {
        $this->middleware('auth');
    }

    // confirmacao estudante
    public function estudantesConfirmacao(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: estudante')  && !$user->can('read: matricula')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $numero_processo = $request->numero_processo;

        $matricula_estudante = Matricula::where('ano_lectivos_id', $this->anolectivoActivo())->pluck('estudantes_id');

        $matriculas = Matricula::whereHas('estudante', function ($query) use ($numero_processo) {
            $query->when($numero_processo, function ($query) use ($numero_processo) {
                $query->where('numero_processo', $numero_processo);
                $query->orWhere('bilheite', $numero_processo);
                $query->orWhere('telefone_estudante', $numero_processo);
                $query->orWhere('nome', 'like', "%" . $numero_processo . "%");
            });
        })
            ->when($request->ano_lectivos_ids, function ($query, $value) {
                $query->where('ano_lectivos_id', $value);
            })
            ->when($request->cursos_id, function ($query, $value) {
                $query->where('cursos_id', $value);
            })
            ->when($request->classes_id, function ($query, $value) {
                $query->where('classes_id', $value);
            })
            ->with(['classe_at', 'classe', 'turno', 'curso', 'estudante'])
            // ->where('ano_lectivos_id', '!=', $this->anolectivoActivo())
            // ->whereNotIn('estudantes_id', $matricula_estudante)
            ->where('shcools_id', $this->escolarLogada())
            ->whereHas('estudante', function ($query) use ($request) {
                $query->where('finalista', 'N');
            })
            ->get();

        $classes = AnoLectivoClasse::when($request->ano_lectivos_ids, function ($query, $value) {
            $query->where('ano_lectivos_id', $value);
        })
            ->with(['classe'])
            ->get();

        $cursos = AnoLectivoCurso::when($request->ano_lectivos_ids, function ($query, $value) {
            $query->where('ano_lectivos_id', $value);
        })
            ->with(['curso'])
            ->get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "requests" =>  $request->all('ano_lectivos_ids', 'cursos_id', 'classes_id'),
            "usuario" => User::findOrFail(Auth::user()->id),

            "ano_lectivos" => AnoLectivo::where('shcools_id', $this->escolarLogada())->get(),
            "matriculas" => $matriculas,
            "classes" => $classes,
            "cursos" => $cursos,
        ];

        return view('admin.estudantes.confirmacao-estudante', $headers);
    }

    // confirmações estudantes incricao
    public function estudantesConfirmacaoInscricao(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: estudante')  && !$user->can('read: matricula')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $matriculas_nao_confirmadas = Matricula::where('status_matricula', 'nao_confirmado')
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('shcools_id', $this->escolarLogada())
            ->with(['classe', 'turno', 'curso', 'estudante', 'ano_lectivo'])
            ->get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Listagem das Confirmações",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),

            "matriculas_nao_confirmadas" => $matriculas_nao_confirmadas,
        ];

        return view('admin.estudantes.confirmacao-estudante-inscricao', $headers);
    }

    public function estudantesConfirmacaoNovoAno($codigo, $ano_lectivo)
    {
        $user = auth()->user();

        if (!$user->can('read: confirmacao')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $estudante = Estudante::findOrFail(Crypt::decrypt($codigo));
        $anoLectivo = AnoLectivo::findOrFail(Crypt::decrypt($ano_lectivo));

        $matricula = Matricula::with(['classe_at', 'classe', 'turno', 'curso', 'estudante'])
            ->where('estudantes_id', $estudante->id)
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $anoLectivo->id)
            ->first();

        if (!$matricula) {
            Alert::warning("Informação", "Estudante Nunca foi matrículado neste escola, não podemos fazer a sua confirmação, primeira faça uma matrícula e só assim pode confirmar a sua matrícula no proximo ano!");
            return redirect()->back();
        }

        $classes = AnoLectivoClasse::where('ano_lectivos_id', $anoLectivo->id)
            ->where('shcools_id', $this->escolarLogada())
            ->with(['classe'])
            ->get();

        $turnos = AnoLectivoTurno::where('ano_lectivos_id', $anoLectivo->id)
            ->where('shcools_id', $this->escolarLogada())
            ->with(['turno'])
            ->get();

        $salas = AnoLectivoSala::where('ano_lectivos_id', $anoLectivo->id)
            ->where('shcools_id', $this->escolarLogada())
            ->with(['sala'])
            ->get();

        $cursos = AnoLectivoCurso::where('ano_lectivos_id', $anoLectivo->id)
            ->where('shcools_id', $this->escolarLogada())
            ->with(['curso'])
            ->get();

        $arquivo = Arquivo::where('model_type', 'estudante')
            ->where('level', '0')
            ->where('codigo', $matricula->documento)
            ->first();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Confirmação para Ano Novo",
            "descricao" => env('APP_NAME'),
            "matricula" => $matricula,
            "estudante" => $estudante,
            "classes" => $classes,
            "turnos" => $turnos,
            "salas" => $salas,
            "cursos" => $cursos,
            "arquivo" => $arquivo,
            "anolectivos" => AnoLectivo::where('shcools_id', $this->escolarLogada())->get(),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.estudantes.confirmacao-novo-ano', $headers);
    }

    public function estudantesConfirmacaoNovoAnoPost(Request $request)
    {
     
        $escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])
            ->findOrFail($this->escolarLogada());

        $ano_lectivo = AnoLectivo::findOrFail($request->ano_lectivos_id ?? $this->anolectivoActivo());


        if ($escola->categoria == "Privado") {
            if ($escola->processo_pagamento_servico == "Secretaria") {
                $request->validate([
                    "at_classes_id" => "required",
                    "classes_id" => "required",
                    "cursos_id" => "required",
                    "turnos_id" => "required",
                    "ano_lectivos_id" => "required",
                    'valor'  => 'required',
                    'valor_entregue'  => 'required',
                    'tipo_pagamento'  => 'required',
                    'documento'  => 'required',
                ]);
            } else {
                $request->validate([
                    "at_classes_id" => "required",
                    "classes_id" => "required",
                    "cursos_id" => "required",
                    "turnos_id" => "required",
                    "ano_lectivos_id" => "required",
                ]);
            }
        } else {
            $request->validate([
                "at_classes_id" => "required",
                "classes_id" => "required",
                "cursos_id" => "required",
                "turnos_id" => "required",
                "ano_lectivos_id" => "required",
            ]);
        }

        $user = auth()->user();

        // ultima matricula
        $matricula = Matricula::with(['classe_at', 'classe', 'turno', 'curso', 'estudante'])->findOrFail($request->id_matricula);
        $estudante = Estudante::findOrFail($request->id_estudante);
        

        if (!$matricula) {
            return response()->json(['message' => "Este Estudante Nunca foi matrículado no sistema, não temos como fazer uma confirmação para ele!"], 404);
            Alert::warning("Informação", "Este Estudante Nunca foi matrículado no sistema, não temos como fazer uma confirmação para ele!");
            return redirect()->back();
        }

        $verificar_matricula = Matricula::where('estudantes_id', $estudante->id)
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $request->ano_lectivos_id)
            ->first();
            
        if ($verificar_matricula) {
            $tipoTexto = $verificar_matricula->tipo == 'matricula' ? 'matrícula' : 'confirmação';
            return response()->json(['message' => "Estudante já tem uma {$tipoTexto} neste ano lectivo!"], 404);
        }

        $forma_pagamento = FormaPagamento::where('sigla_tipo_pagamento', $request->tipo_pagamento)->first();

        // matricula do ano passado
        $documentoAntigo = Matricula::where('estudantes_id', $matricula->estudantes_id)
            ->where('documento', $matricula->documento)
            ->where('status_matricula', 'confirmado')
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $matricula->ano_lectivos_id)
            ->first();


        try {
            // Realizar operações de banco de dados aqui
            DB::beginTransaction();

            // caixa
            if ($escola->categoria == "Privado") {
                if ($escola->processo_pagamento_servico == "Secretaria") {
                    $status_matricula_pagamento = 'Pago';
                    $status_matricula = 'confirmado';

                    if ($user->can('create: pagamento')) {
                        if ($escola->modulo != "Básico") {
                            $caixa = Caixa::where('status', "activo")
                                ->where('shcools_id', $this->escolarLogada())
                                ->where('usuario_id', Auth::user()->id)
                                ->first();

                            if (!$caixa) {
                                return response()->json(['message' => "Não há nenhum caixa aberto no momento. Por favor, abra um caixa para continuar o processo.!"], 404);
                            }

                            $caixaAberto = MovimentoCaixa::where('caixa_id', $caixa->id)
                                ->where('usuario_id', Auth::user()->id)
                                ->where('status', "aberto")
                                ->first()->id;

                            if (!$caixaAberto) {
                                return response()->json(['message' => "Não há nenhum caixa aberto no momento. Por favor, abra um caixa para continuar o processo.!"], 404);
                            }

                            if (!filter_var($request->valor_entregue, FILTER_VALIDATE_INT) and !!filter_var($request->valor_entregue, FILTER_VALIDATE_INT)) {
                                return response()->json(['message' => "O Valor Invalido!"], 404);
                            }

                            if ($request->valor_entregue < $request->valor) {
                                return response()->json(['message' => "O Valor Entregue para o pagamento deste serviço é insuficiente!"], 404);
                            }
                        }
                    }
                } else {
                    $status_matricula_pagamento = 'Nao Pago';
                    $status_matricula = 'nao_confirmado';
                }
            } else {
                $status_matricula_pagamento = 'Pago';
                $status_matricula = 'confirmado';
            }


            $code = time();

            $createM = Matricula::create([
                "documento" => $documentoAntigo->documento,
                "numero_estudante" => $documentoAntigo->numero_estudante,
                "status_matricula" => $status_matricula,
                "status_matricula_pagamento" =>  $status_matricula_pagamento,
                "status_inscricao" => "Admitido",
                "ficha" => $code,
                "at_classes_id" => $request->at_classes_id,
                "classes_id" => $request->classes_id,
                "cursos_id" => $request->cursos_id,
                "turnos_id" => $request->turnos_id,
                "tipo" => $request->tipo_matricula, // confirmação , Matricula
                "status" => $request->situacao_estudante, // Novo ou repitente
                "condicao" => $request->condicao_estudante, // paga ou nao paga propinas

                "media" => $matricula->media,
                "cursos_primeira_opcao_id" => $matricula->cursos_primeira_opcao_id,
                "cursos_segunda_opcao_id" => $matricula->cursos_segunda_opcao_id,

                "pais_id" => $escola->pais_id,
                "provincia_id" => $escola->provincia_id,
                "municipio_id" => $escola->municipio_id,
                "distrito_id" => $escola->distrito_id,
                "level" => "1",

                "estudantes_id" => $matricula->estudantes_id,
                "shcools_id" => $this->escolarLogada(),
                "ano_lectivos_id" => $ano_lectivo->id,
                "data_at" => $this->data_sistema(),
                "funcionarios_id" => Auth::user()->id,
                "ano_lectivo_global_id" => $this->anolectivoActivoGlobal()
            ]);
            
            Confirmacao::updateOrCreate([
                "estudantes_id" => $matricula->estudantes_id,
                "shcools_id" => $this->escolarLogada(),
                "ano_lectivos_id" => $ano_lectivo->id,
            ], [
                "estudantes_id" => $matricula->estudantes_id,
                "shcools_id" => $this->escolarLogada(),
                "ano_lectivos_id" => $ano_lectivo->id,
            ]);

            $arquivo = Arquivo::where('model_type', "estudante")->where('model_id', $matricula->estudantes_id)->first();

            if(!$arquivo){
                Arquivo::create([
                    "codigo" => $createM->documento,
                    "model_type" => "estudante",
                    "model_id" => $matricula->estudantes_id,
                    "level" => "1",
                    "certificado" => NULL,
                    "bilheite" => NULL,
                    "atestado" => NULL,
                    "outros" => NULL,
                ]);
            }else {
                if (!empty($request->file('doc_bilheite'))) {
                    $image = $request->file('doc_bilheite');
                    $imageNameBI = time() . '.' . $image->extension();
                    $image->move(public_path('assets/arquivos'), $imageNameBI);
                } else {
                    $imageNameBI = $request->doc_bilheite_guardado ?? $arquivo->bilheite;
                }
                
    
                if (!empty($request->file('doc_certificado'))) {
                    $image2 = $request->file('doc_certificado');
                    $imageNameCT = time() . '.' . $image2->extension();
                    $image2->move(public_path('assets/arquivos'), $imageNameCT);
                } else {
                    $imageNameCT = $request->doc_certificado_guardado ?? $arquivo->certificado;
                }
    
                if (!empty($request->file('doc_outros'))) {
                    $image2 = $request->file('doc_outros');
                    $imageNameOD = time() . '.' . $image2->extension();
                    $image2->move(public_path('assets/arquivos'), $imageNameOD);
                } else {
                    $imageNameOD = $request->doc_outros_guardado ?? $arquivo->outros;
                }
    
                if (!empty($request->file('doc_atestedao_medico'))) {
                    $image2 = $request->file('doc_atestedao_medico');
                    $imageNameAT = time() . '.' . $image2->extension();
                    $image2->move(public_path('assets/arquivos'), $imageNameAT);
                } else {
                    $imageNameAT = $request->doc_atestedao_medico_guardado ?? $arquivo->atestado;
                }
            
                $arquivo->codigo = $matricula->documento;
                $arquivo->certificado = $imageNameCT;
                $arquivo->bilheite = $imageNameBI;
                $arquivo->atestado = $imageNameAT;
                $arquivo->outros = $imageNameOD;
                $arquivo->update();
            }
            
            $full = $request->nome . " " . $request->sobre_nome;

            $text = "" . Auth::user()->nome . "  }}, fez a confirmação do estudante {$full} no curso de " . Curso::find($request->cursos_id)->curso . " classe " . Classe::find($request->classes_id)->classes;
            $text2 = "O Sr(a) acabou de fazer a matricula de um estudante";

            Notificacao::create([
                "user_id" => Auth::user()->id,
                "destino" => NULL,
                "type_destino" => "escola",
                "type_enviado" => "funcionario",
                "notificacao" => $text,
                "notificacao_user" => $text2,
                "status" => "0",
                "model_id" => $createM->id,
                "model_type" => "confirmacao",
                "shcools_id" => $this->escolarLogada()
            ]);

            $turma = Turma::where("classes_id", $request->classes_id)
                ->where("cursos_id", $request->cursos_id)
                ->where("turnos_id", $request->turnos_id)
                ->where("ano_lectivos_id", $ano_lectivo->id)
                ->first();


            if ($turma) {
                if ($escola->categoria == "Privado" && $escola->processo_pagamento_servico == "Secretaria") {
                    if ($escola->ensino->nome == "Ensino Superior") {
                        $trimestres = ControlePeriodico::where("ensino_status", "2")->get();
                    } else {
                        $trimestres = ControlePeriodico::where("ensino_status", "1")->get();
                    }

                    // criar pauta do estudante ou seja cardeneta
                    $this->inserir_turmas_pautas_anterior($estudante->id, $createM->classes_id, $createM->cursos_id, $ano_lectivo->id, $trimestres, $turma->id);

                    $servicos = ServicoTurma::where("turmas_id", $turma->id)
                        ->where("model", "turmas")
                        ->where("ano_lectivos_id", $ano_lectivo->id)
                        ->with(["servico"])
                        ->get();

                    $condicao_estudante = $request->condicao_estudante;

                    if ($servicos) {
                        foreach ($servicos as $servico) {
                            if ($servico->pagamento == 'mensal') {
                                if ($condicao_estudante == "Isento" and $servico->servico->servico == "Propinas") {
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
                                        }
                                    }
                                }
                            } else if ($servico->pagamento == 'unico') {
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
                                }
                            }
                        }
                    }
                }
            }


            // registrar pagamento
            if ($escola->categoria == "Privado" && $escola->processo_pagamento_servico == "Secretaria") {
                if ($user->can('create: pagamento')) {

                    $forma_pagamento = FormaPagamento::where('sigla_tipo_pagamento', $request->tipo_pagamento)->first();
                    $servico_operacional = Servico::join('tb_taxas', 'tb_servicos.taxa_id', '=', 'tb_taxas.id')->select('tb_servicos.id', 'taxa', 'contas', 'tb_servicos.servico', 'tb_servicos.contas')->findOrFail($request->servicos_id);

                    $valor_multicaixa = 0;
                    $valor_cash = 0;

                    if ($forma_pagamento->sigla_tipo_pagamento == "NU") {
                        $valor_cash = $request->valor_a_pagar;
                        $valor_multicaixa = 0;
                    } else if ($forma_pagamento->sigla_tipo_pagamento == "MB") {
                        $valor_cash = 0;
                        $valor_multicaixa = $request->valor_a_pagar;
                    }

                    $contarFactura = Pagamento::where('tipo_factura', $request->documento)->where('factura_ano', date("Y"))->where('shcools_id', $this->escolarLogada())->count();

                    $ultimoRecibo = Pagamento::where('tipo_factura', $request->documento)->where('factura_ano', date("Y"))->where('shcools_id', $this->escolarLogada())->orderBy('id', 'DESC')->limit(1)->first();

                    if (!$ultimoRecibo) {
                        $hashAnterior = "";
                    } else {
                        $hashAnterior = $ultimoRecibo->hash;
                    }

                    //Manipulação de datas: data actual
                    $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

                    // $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

                    $ano_lectivo_activo = AnoLectivo::findOrFail($this->anolectivoActivo());

                    $ano = date("Y");
                    $numeroFactura = $contarFactura + 1;

                    $rsa = new RSA(); //Algoritimo RSA

                    $privatekey = $this->pegarChavePrivada();
                    $publickey = $this->pegarChavePublica();

                    // Lendo a private key
                    $rsa->loadKey($privatekey);

                    $codigo_designacao_factura = "EAV";

                    /**
                     * Texto que deverá ser assinado com a assinatura RSA::SIGNATURE_PKCS1, e o Texto estará mais ou menos assim após as
                     * Concatenações com os dados preliminares da factura: 2020-09-14;2020-09-14T20:34:09;FP PAT2020/1;457411.2238438; */

                    $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ';' . "{$request->documento} {$codigo_designacao_factura}{$ano_lectivo_activo->serie}/{$numeroFactura}" . ';' . number_format($request->valor, 2, ".", "") . ';' . $hashAnterior;
                    // HASH
                    $hash = 'sha1'; // Tipo de Hash
                    $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima

                    //ASSINATURA
                    $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
                    $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)

                    // Lendo a public key
                    $rsa->loadKey($publickey);

                    $valor_extenso = $this->valor_por_extenso($request->valor);

                    if ($forma_pagamento->sigla_tipo_pagamento == "NU") {
                        $valor_cash = $request->valor;
                        $valor_multicaixa = 0;
                    } else if ($forma_pagamento->sigla_tipo_pagamento == "MB") {
                        $valor_cash = 0;
                        $valor_multicaixa = $request->valor;
                    }

                    $createP = Pagamento::create([
                        "pago_at" => strtolower($servico_operacional->servico),
                        "servicos_id" => $servico_operacional->id,
                        "caixa_at" => $servico_operacional->contas,
                        "ficha" => $code,
                        'tipo_servico_detalhe' => 'unico',
                        "status" => 'Confirmado',
                        "desconto" => 0,
                        "valor" => $request->valor,
                        "valor2" => $request->valor,
                        "multa" => 0,
                        "data_at" => $this->data_sistema(),
                        "mensal" => $this->mesecompleto(),
                        "funcionarios_id" => Auth::user()->id,
                        "estudantes_id" => $estudante->id,
                        'valor_entregue' => $request->valor_entregue,
                        "numero_factura" => $numeroFactura,
                        'troco' => $request->valor_entregue - $request->valor,
                        'data_vencimento' => date("Y-m-d"),
                        'data_disponibilizacao' => date("Y-m-d"),
                        'factura_ano' => $ano,
                        'prazo' => 0,
                        'data_vencimento' => date("Y-m-d"),
                        "model" => 'estudante',
                        "ano_lectivos_id" => $this->anolectivoActivo(),
                        "tipo_factura" =>  $request->documento,
                        "pagamento_id" => $forma_pagamento->id ?? 1,
                        "tipo_pagamento" => $forma_pagamento->sigla_tipo_pagamento,
                        'next_factura' => "{$request->documento} {$codigo_designacao_factura}{$ano_lectivo_activo->serie}/{$numeroFactura}",
                        'observacao' => "",
                        'referencia' => $code,
                        'shcools_id' => $this->escolarLogada(),
                        'retificado' => 'N',
                        'convertido_factura' => 'N',
                        'factura_divida' => 'N',
                        'anulado' => 'N',
                        'moeda' => 'AOA',
                        'valor_extenso' => $valor_extenso,
                        'valor_cash' => $valor_cash,
                        'valor_multicaixa' => $valor_multicaixa,
                        'texto_hash' => $plaintext,
                        'hash' => base64_encode($signaturePlaintext),
                        'nif_cliente' => $estudante->bilheite,
                        'total_iva' => 0,
                        'total_incidencia' => $request->valor,
                        'quantidade' => 1,
                        'conta_corrente_cliente' => $estudante->conta_corrente,
                    ]);

                    // calcudo do total de incidencia
                    // ________________ valor total _____________
                    $valorBase = $request->valor * 1;
                    // calculo do iva
                    $valorIva = ($servico_operacional->taxa / 100) * $valorBase;

                    $desconto = ($request->valor * 1) * (0 / 100);

                    DetalhesPagamentoPropina::create([
                        'valor_incidencia' => $valorBase,
                        'total_pagar' => $valorBase + $valorIva,
                        'desconto_valor' => $desconto,
                        'valor_iva' => 0,
                        'taxa_id' => $servico_operacional->taxa,

                        'code' => $code,
                        'mes_id' => "NULL",
                        'mes' => date("M"),
                        'multa' => 0,
                        'model_id' => $estudante->id,
                        'quantidade' => 1,
                        'funcionarios_id' => Auth::user()->id,
                        'preco' => $valorBase,
                        'status' => 'Pago',
                        'servicos_id' => $servico_operacional->id,
                        'date_att' => $this->data_sistema(),
                        'ano_lectivos_id' => $this->anolectivoActivo(),
                        'shcools_id' => $this->escolarLogada(),
                        'pagamentos_id' => $createP->id,
                    ]);

                    if ($escola->modulo != "Básico") {
                        $updateCaixaAberto = MovimentoCaixa::findOrFail($caixaAberto);
                        if ($request->tipo_pagamento == "NU") {
                            $updateCaixaAberto->valor_cache = $updateCaixaAberto->valor_cache + $request->valor;
                        }
                        if ($request->tipo_pagamento == "MB") {
                            $updateCaixaAberto->valor_tpa = $updateCaixaAberto->valor_tpa + $request->valor;
                        }
                        $updateCaixaAberto->valor_fecha = $updateCaixaAberto->valor_fecha + $request->valor;
                        $updateCaixaAberto->qtd_itens = $updateCaixaAberto->qtd_itens + 1;
                        $updateCaixaAberto->update();
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

        return response()->json(["message" => "Dados salvos com sucesso", "redirect" => route("ficha-matricula2", $createM->ficha)], 200);
    }
}
