<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AnoLectivoGlobal;
use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\anolectivo\AnoLectivoUsuario;
use App\Models\web\calendarios\Matricula;
use App\Models\web\calendarios\ServicoTurma;
use App\Models\web\estudantes\CartaoEstudante;
use App\Models\web\financeiros\DetalhesPagamentoPropina;
use App\Models\web\turmas\DisciplinaTurma;
use App\Models\web\turmas\EstudantesTurma;
use App\Models\web\turmas\NotaPauta;
use App\Models\web\turmas\Turma;
use Illuminate\Support\Facades\Auth;

trait TraitHelpers
{


    public function inserir_turmas_pautas_anterior($estudante_id, $classe_id, $cursos_id, $ano_lectivo_id, $trimestres, $turma = null)
    {
        $escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->findOrFail($this->escolarLogada());

        // encontrar turma em causa para se fazer as inserções
        if ($turma != null) {
            $turma = Turma::findOrFail($turma);
        } else {
            $turma = Turma::where('classes_id', $classe_id)
                ->where('cursos_id', $cursos_id)
                ->where('ano_lectivos_id', $ano_lectivo_id)
                ->first();
        }

        // se exitir turma da 1o classe ano passado
        if ($turma) {

            // contar total de estudantes daquela turma
            $totalEstudanteTurma = EstudantesTurma::where('turmas_id', $turma->id)->count();

            if ($turma->numero_maximo > $totalEstudanteTurma) {
                // somar mais um agora porque ele será o ultimo aluno esta turma mesmo que for ano passado
                $novoNumero = $totalEstudanteTurma + 1;

                // verificar se por acaso esta aluno nunca esteve neste turma neste ano que estamos a filtar
                $verificar_estudantes_turma = EstudantesTurma::where("turmas_id", $turma->id)
                    ->where("ano_lectivos_id", $ano_lectivo_id)
                    ->where("estudantes_id", $estudante_id)
                    ->first();

                // se não estiver estão vamos adicioanr
                if (!$verificar_estudantes_turma) {
                    // adicionar estudante naquela turma
                    EstudantesTurma::create([
                        "ordem" => "EST Nº {$novoNumero}/{$turma->turma}",
                        "status" => "activo",
                        "ano_lectivos_id" => $ano_lectivo_id,
                        "turmas_id" => $turma->id,
                        "estudantes_id" => $estudante_id,
                    ]);

                    // carregar todas as disciplinas daquela turma
                    $disciplinas = DisciplinaTurma::where('turmas_id', $turma->id)->get();

                    // se não tiver notas naquela turma
                    if ($disciplinas) {
                        if ($trimestres) {
                            foreach ($trimestres as $trimestre) {
                                foreach ($disciplinas as $disciplina) {

                                    if ($escola->ensino->nome == "Ensino Superior") {
                                        if ($disciplina->trimestre_id === $trimestre->id) {
                                            // verificar se ainda não esta cadastrado
                                            $verificar_notas = NotaPauta::where('estudantes_id', $estudante_id)
                                                ->where('ano_lectivos_id', $ano_lectivo_id)
                                                ->where('shcools_id', $this->escolarLogada())
                                                ->where('controlo_trimestres_id', $trimestre->id)
                                                ->where('turmas_id', $turma->id)
                                                ->where('disciplinas_id', $disciplina->disciplinas_id)
                                                ->first();

                                            if (!$verificar_notas) {
                                                // salvar novo por não existe
                                                if (!$verificar_notas) {
                                                    $this->criar_pauta_curricular($turma->id, $estudante_id, $ano_lectivo_id, $trimestre->id, $disciplina->disciplinas_id);
                                                }
                                            }
                                        }
                                    } else {

                                        $verificar_notas = NotaPauta::where('estudantes_id', $estudante_id)
                                            ->where('ano_lectivos_id', $ano_lectivo_id)
                                            ->where('shcools_id', $this->escolarLogada())
                                            ->where('controlo_trimestres_id', $trimestre->id)
                                            ->where('turmas_id', $turma->id)
                                            ->where('disciplinas_id', $disciplina->disciplinas_id)
                                            ->first();

                                        if (!$verificar_notas) {
                                            $this->criar_pauta_curricular($turma->id, $estudante_id, $ano_lectivo_id, $trimestre->id, $disciplina->disciplinas_id);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                // excedeu o numero de estudante neste turma
            }
        }
    }

    public function criar_pauta_curricular($turma_id, $estudante_id, $ano_lectivo_id, $trimestre_id, $disciplina_id)
    {
        NotaPauta::create([
            "mf" => '0',
            "pt" => '0',
            "pap" => '0',

            "mac" => '0',
            "npt" => '0',
            "mt" => '0',
            "mt1" => '0',
            "mt2" => '0',
            "mt3" => '0',

            "mfd" => '0',
            "ne" => '0',
            "nr" => '0',
            "rf" => '0',

            "turmas_id" => $turma_id,
            "estudantes_id" => $estudante_id,
            "ano_lectivos_id" => $ano_lectivo_id,
            "controlo_trimestres_id" => $trimestre_id,
            "disciplinas_id" => $disciplina_id,

            "funcionarios_id" => Auth::user()->id,
            "shcools_id" => $this->escolarLogada(),
        ]);
    }

    // MESES DO CARTAO DO ESTUDANTES
    public function mes_periodico($string, $mes_id, $tipo)
    {
        $meses_anterior = 0;

        if ($mes_id == "M" && $tipo == "geral") {
            if ($string == "Sep" || $string == "Oct" || $string == "Nov" || $string == "Dec") {
                $meses_anterior = 1;
            }
            if ($string == "Jan" || $string == "Feb" || $string == "Mar" || $string == "Apr") {
                $meses_anterior = 2;
            }
            if ($string == "May" || $string == "Jun" || $string == "Jul" || $string == "Aug") {
                $meses_anterior = 3;
            }
        } else if ($mes_id == "U" && $tipo == "geral") {
            $meses_anterior = 4;
        } else if ($mes_id == "M" && $tipo == "universidade") {
            if ($string == "Sep" || $string == "Oct" || $string == "Nov" || $string == "Dec" || $string == "Jan" || $string == "Feb") {
                $meses_anterior = 5;
            }
            if ($string == "Mar" || $string == "Apr" || $string == "May" || $string == "Jun" || $string == "Jul" || $string == "Aug") {
                $meses_anterior = 6;
            }
        } else if ($mes_id == "U" && $tipo == "universidade") {
            $meses_anterior = 7;
        }

        return $meses_anterior;
    }

    // MESES DO CARTAO DO ESTUDANTES
    public function meses_anterior_ao_mes($string)
    {
        $meses_anterior = [];

        if ($string == "Jan") {
            $meses_anterior = ["Sep", "Oct", "Nov", "Dec"];
        }

        if ($string == "Feb") {
            $meses_anterior = ["Sep", "Oct",  "Nov", "Dec", "Jan"];
        }

        if ($string == "Mar") {
            $meses_anterior = ["Sep", "Oct", "Nov", "Dec", "Jan", "Feb"];
        }

        if ($string == "Apr") {
            $meses_anterior = ["Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar"];
        }

        if ($string == "May") {
            $meses_anterior = ["Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr"];
        }

        if ($string == "Jun") {
            $meses_anterior = ["Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May"];
        }

        if ($string == "Jul") {
            $meses_anterior = [
                "Sep",
                "Oct",
                "Nov",
                "Dec",
                "Jan",
                "Feb",
                "Mar",
                "Apr",
                "May",
                "Jun"
            ];
        }

        if ($string == "Aug") {
            $meses_anterior = ["Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"];
        }

        if ($string == "Sep") {
            $meses_anterior = [];
        }

        if ($string == "Oct") {
            $meses_anterior = ["Sep"];
        }

        if ($string == "Nov") {
            $meses_anterior = ["Sep", "Oct"];
        }

        if ($string == "Dec") {
            $meses_anterior =  ["mes", "Sep", "Oct", "Nov"];
        }

        return $meses_anterior;
    }

    public function cartao_estudantes_meses($ano_lectivo, $dia_inicio, $dia_final)
    {
        $ano = date("Y", strtotime($ano_lectivo));
        $mesInicial = (int) date("m", strtotime($ano_lectivo));

        $diaInicio = str_pad($dia_inicio, 2, '0', STR_PAD_LEFT);
        $diaFinal  = str_pad($dia_final, 2, '0', STR_PAD_LEFT);

        $pagamentos = [];
        $mesAtual = $mesInicial;
        $anoAtual = $ano;

        // Array com as siglas dos meses
        $mesesSiglas = [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'May',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Aug',
            9 => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Dec'
        ];

        for ($i = 0; $i < 12; $i++) {
            $mesFormatado = str_pad($mesAtual, 2, '0', STR_PAD_LEFT);
            $siglaMes = $mesesSiglas[$mesAtual];

            $dataInicioPagamento = "{$anoAtual}-{$mesFormatado}-{$diaInicio}";
            $dataFinalPagamento  = "{$anoAtual}-{$mesFormatado}-{$diaFinal}";

            $pagamentos[] = [
                'ano' => (int) $anoAtual,
                'mes' => $mesFormatado,
                'sigla' => $siglaMes,
                'inicio' => $dataInicioPagamento,
                'fim' => $dataFinalPagamento,
            ];

            // Atualiza mês e ano
            $mesAtual++;
            if ($mesAtual > 12) {
                $mesAtual = 1;
                $anoAtual++;
            }
        }

        return $pagamentos;
    }

    // ANO LECTIVO ACTIVO
    public function anolectivoActivo()
    {
        $admin = User::findOrFail(Auth::user()->id);
        $escola = Shcool::findOrFail($admin->shcools_id);

        $verificarAnoLectivoUsuario = AnoLectivoUsuario::where('usuario_id', Auth::user()->id)
            ->where('sessao', "usuariologadoAnoLectivo" . Auth::user()->id)
            ->where('status', "Activo")
            ->where('shcools_id', $this->escolarLogada())
            ->first();

        if ($verificarAnoLectivoUsuario) {
            $find = AnoLectivo::findOrFail($verificarAnoLectivoUsuario->ano_lectivos_id);
            return $find->id;
        } else {
            $anoLectivoAtivo = AnoLectivo::where('shcools_id', $escola->id)
                ->where('status', 'activo')
                ->first();

            if (!$anoLectivoAtivo) {
                return redirect()->route('web.ano-lectivo');
            }
            return $anoLectivoAtivo->id;
        }
    }

    public function anolectivoAnterior($id)
    {
        $admin = User::findOrFail(Auth::user()->id);
        $escola = Shcool::findOrFail($admin->shcools_id);

        $ordemAnoLectivo = AnoLectivo::findOrFail($id)->ordem;

        if (!$ordemAnoLectivo) {
            return 0;
        } else if ($ordemAnoLectivo >= 1) {
            $novaOrdem = $ordemAnoLectivo - 1;

            $anoLectivoAnterior = AnoLectivo::where('ordem', $novaOrdem)
                // ->where('status', 'desactivo')
                ->where('shcools_id', $escola->id)
                ->first();
            if (!$anoLectivoAnterior) {
                return 0;
            }
            return $anoLectivoAnterior->id;
        }
    }

    public function anolectivoProximo($id)
    {
        $admin = User::findOrFail(Auth::user()->id);
        $escola = Shcool::findOrFail($admin->shcools_id);

        $ano = AnoLectivo::findOrFail($id);

        if (!$ano) {
            return 0;
        } else {
            $novaOrdem = $ano->ordem + 1;

            $anoLectivoAnterior = AnoLectivo::where('ordem', $novaOrdem)
                ->where('shcools_id', $escola->id)
                ->first();

            if (!$anoLectivoAnterior) {
                return 0;
            }
            return $anoLectivoAnterior->id;
        }
    }

    public function anolectivoAnteriorId($id)
    {
        $admin = User::findOrFail(Auth::user()->id);
        $escola = Shcool::findOrFail($admin->shcools_id);

        $anoLectivoAtivo = AnoLectivo::find($id)->ordem;

        if (!$anoLectivoAtivo) {
            return 0;
        } else if ($anoLectivoAtivo >= 2) {
            $novaOrdem = $anoLectivoAtivo->ordem - 1;
            $anoLectivoAnterior = AnoLectivo::where('ordem', $novaOrdem)
                ->where('status', '=', 'desactivo')
                ->where('shcools_id', '=', $escola->id)
                ->first();

            if (!$anoLectivoAnterior) {
                return 0;
            }
            return $anoLectivoAnterior->id;
        }
    }

    public function anolectivoAntesAnterior($id)
    {
        $admin = User::findOrFail(Auth::user()->id);
        $escola = Shcool::findOrFail($admin->shcools_id);

        $ordemAnoLectivo = AnoLectivo::findOrFail($id)->ordem;

        if (!$ordemAnoLectivo) {
            return 0;
        } else if ($ordemAnoLectivo >= 2) {
            $novaOrdem = $ordemAnoLectivo - 2;
            $anoLectivoAnterior = AnoLectivo::where('ordem', $novaOrdem)
                // ->where('status', 'desactivo')
                ->where('shcools_id', $escola->id)
                ->first();

            if (!$anoLectivoAnterior) {
                return 0;
            }
            return $anoLectivoAnterior->id;
        }
    }


    public function anolectivoAntesAnteriorId($id)
    {
        $admin = User::findOrFail(Auth::user()->id);
        $escola = Shcool::findOrFail($admin->shcools_id);

        $anoLectivoAtivo = AnoLectivo::find($id);

        $ordemAnoLectivo = $anoLectivoAtivo->ordem;

        if ($ordemAnoLectivo <= 2) {
            return 0;
        } else if ($ordemAnoLectivo >= 3) {
            $novaOrdem = $anoLectivoAtivo->ordem - 2;
            $anoLectivoAnterior = AnoLectivo::where('ordem', $novaOrdem)->where('status', '=', 'desactivo')->where('shcools_id', '=', $escola->id)->first();

            if (!$anoLectivoAnterior) {
                return 0;
            }
            return $anoLectivoAnterior->id;
        }
    }

    public function anolectivoAntesAntesAnterior()
    {
        $admin = User::findOrFail(Auth::user()->id);
        $escola = Shcool::findOrFail($admin->shcools_id);

        $anoLectivoAtivo = AnoLectivo::where([
            ['shcools_id', '=', $escola->id],
            ['status', '=', 'activo'],
        ])->first();

        $ordemAnoLectivo = $anoLectivoAtivo->ordem;

        if ($ordemAnoLectivo <= 2) {
            return 0;
        } else if ($ordemAnoLectivo >= 3) {
            $novaOrdem = $anoLectivoAtivo->ordem - 3;
            $anoLectivoAnterior = AnoLectivo::where('ordem', $novaOrdem)->where('status', '=', 'desactivo')->where('shcools_id', '=', $escola->id)->first();

            if (!$anoLectivoAnterior) {
                return 0;
            }
            return $anoLectivoAnterior->id;
        }
    }
    // ANO LECTIVO ACTIVO
    public function anolectivoActivoGlobal()
    {
        $anoLectivoAtivo = AnoLectivoGlobal::where([
            ['status', '=', 'activo'],
        ])->first();

        if (!$anoLectivoAtivo) {
            return redirect()->route('ano-lectivo-global');
        }
        return $anoLectivoAtivo->id;
    }

    // ANO LECTIVO ACTIVO
    public function escolarLogada()
    {
        $admin = User::findOrFail(Auth::user()->id);
        $escola = Shcool::findOrFail($admin->shcools_id);
        return $escola->id;
    }

    public function total_estudantes($escola, $ano_lectivo)
    {
        $total = Matricula::where('shcools_id', $escola)->where('status_matricula', 'confirmado')->where('ano_lectivos_id', $ano_lectivo)->count();
        return $total;
    }


    // ANO LECTIVO ACTIVO
    public function anoLectivoActivoEscola($id)
    {
        $escola = Shcool::findOrFail($id);

        $anoLectivoAtivo = AnoLectivo::where([
            ['shcools_id', '=', $escola->id],
            ['status', '=', 'activo'],
        ])->first();

        return $anoLectivoAtivo->id;
    }


    /*sistema de datas*/
    public function data_sistema()
    {
        date_default_timezone_set('Africa/Luanda');
        /*sistema de datas*/
        $dia = @date("d");
        $mes = @date("m");
        $ano = @date("Y");
        return $ano . "-" . $mes . "-" . $dia;
        // return date("Y-m-d");
    }

    /*sistema de horas*/
    public function hora_sistema()
    {
        date_default_timezone_set('Africa/Luanda');
        /*sistema de horas*/
        $hora = @date("H"); //h:12horas & H:24horas
        $minuto = @date("i");
        $segundo = @date("s");
        return $hora . ":" . $minuto . ":" . $segundo;
        // return date("H:i:s");
    }

    // hora e data do sistema
    public function hora_data_sistema()
    {
        return $this->data_sistema() . " " . $this->hora_sistema();
    }

    public function mesecompleto()
    {
        $string = date("M");

        if ($string == "Jan") {
            return " January";
        }

        if ($string == "Feb") {
            return "February";
        }

        if ($string == "Mar") {
            return "March";
        }

        if ($string == "Apr") {
            return "April";
        }

        if ($string == "May") {
            return "May";
        }

        if ($string == "Jun") {
            return "June";
        }

        if ($string == "Jul") {
            return "July";
        }

        if ($string == "Aug") {
            return "August";
        }

        if ($string == "Sept") {
            return "September";
        }

        if ($string == "Oct") {
            return "October";
        }

        if ($string == "Nov") {
            return "November";
        }

        if ($string == "Dec") {
            return "December";
        }
    }

    public function mmes($string)
    {
        if ($string == "todas") {
            return "todas";
        } else if ($string == "January") {
            return "Jan";
        } else
        if ($string == "February") {
            return "Feb";
        } else if ($string == "March") {
            return "Mar";
        } else if ($string == "April") {
            return "Apr";
        } else if ($string == "May") {
            return "May";
        } else if ($string == "June") {
            return "Jun";
        } else if ($string == "July") {
            return "Jul";
        } else if ($string == "August") {
            return "Aug";
        } else if ($string == "September") {
            return "Sep";
        } else if ($string == "October") {
            return "Oct";
        } else if ($string == "November") {
            return "Nov";
        } else if ($string == "December") {
            return "Dec";
        }
    }

    public function carregarMesePago($mes, $servico,  $ano)
    {
        $pagamentosDetalhe = DetalhesPagamentoPropina::where([
            ['status', '=', 'Pago'],
            ['mes', '=', $mes],
            ['servicos_id', '=', $servico],
            ['ano_lectivos_id', '=', $ano]
        ])
            // ->limit(5)
            ->get();

        if ($pagamentosDetalhe) {
            return $pagamentosDetalhe;
        } else {
            return [];
        }
    }


    function valor_por_extenso($v)
    {

        $v = filter_var($v, FILTER_SANITIZE_NUMBER_INT);

        $sin = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
        $plu = array("centavos", "", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");

        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");

        $z = 0;

        $v = number_format($v, 2, ",", ".");
        $int = explode(".", $v);

        for ($i = 0; $i < count($int); $i++) {
            for ($ii = mb_strlen($int[$i]); $ii < 3; $ii++) {
                $int[$i] = "0" . $int[$i];
            }
        }

        $rt = null;
        $fim = count($int) - ($int[count($int) - 1] > 0 ? 1 : 2);
        for ($i = 0; $i < count($int); $i++) {
            $v = $int[$i];
            $rc = (($v > 100) && ($v < 200)) ? "cento" : $c[$v[0]];
            $rd = ($v[1] < 2) ? "" : $d[$v[1]];
            $ru = ($v > 0) ? (($v[1] == 1) ? $d10[$v[2]] : $u[$v[2]]) : "";

            $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
            $t = count($int) - 1 - $i;
            $r .= $r ? " " . ($v > 1 ? $plu[$t] : $sin[$t]) : "";
            if ($v == "000")
                $z++;
            elseif ($z > 0)
                $z--;

            if (($t == 1) && ($z > 0) && ($int[0] > 0))
                $r .= (($z > 1) ? " de " : "") . $plu[$t];

            if ($r)
                $rt = $rt . ((($i > 0) && ($i <= $fim) && ($int[0] > 0) && ($z < 1)) ? (($i < $fim) ? ", " : " e ") : " ") . $r;
        }

        $rt = mb_substr($rt, 1);

        return ($rt ? trim($rt) : "zero");
    }

    public function actualizarCartao()
    {
        ini_set('max_execution_time', '120'); // Aumenta para 120 segundos
        ini_set('memory_limit', '4096M');

        $estudantesTurma = EstudantesTurma::with('turma')->where('ano_lectivos_id', '=', $this->anolectivoActivo())->get();

        if ($estudantesTurma) {
            foreach ($estudantesTurma as $estudanteTurma) {
                $cartoes = CartaoEstudante::with('servico')
                    ->where('estudantes_id', $estudanteTurma->estudantes_id)
                    ->where('mes_id', 'M')
                    ->whereIn('status', ['divida', 'Nao Pago'])
                    ->where('ano_lectivos_id', $this->anolectivoActivo())
                    ->get();

                if ($cartoes) {
                    foreach ($cartoes as $cartao) {
                        $servico_turma = ServicoTurma::where('turmas_id', $estudanteTurma->turmas_id)->where('servicos_id', $cartao->servicos_id)->where('ano_lectivos_id', '=', $this->anolectivoActivo())->first();

                        if (date('Y-m-d') > $cartao->data_at) {

                            $data_primeira_taxa = date('Y-m-d', strtotime($cartao->data_at . "+{$estudanteTurma->turma->taxa_multa1_dia}days"));
                            $data_segunda_taxa = date('Y-m-d', strtotime($cartao->data_at . "+{$estudanteTurma->turma->taxa_multa2_dia}days"));
                            $data_terceira_taxa = date('Y-m-d', strtotime($cartao->data_at . "+{$estudanteTurma->turma->taxa_multa3_dia}days"));

                            $multa1 = 0;
                            $multa2 = 0;
                            $multa3 = 0;

                            if ($cartao->multa1 == 'N') {
                                if (date('Y-m-d') > $data_primeira_taxa) {
                                    $multa1 = $servico_turma->preco * ($servico_turma->taxa_multa1 / 100);
                                }
                                $status_multa1 = 'Y';
                            } else {
                                $status_multa1 = 'Y';
                            }


                            if ($cartao->multa2 == 'N') {
                                if (date('Y-m-d') > $data_segunda_taxa) {
                                    $multa2 = $servico_turma->preco * ($servico_turma->taxa_multa2 / 100);
                                }
                                $status_multa2 = 'Y';
                            } else {
                                $status_multa2 = 'Y';
                            }

                            if ($cartao->multa3 == 'N') {
                                if (date('Y-m-d') > $data_terceira_taxa) {
                                    $multa3 = $servico_turma->preco * ($servico_turma->taxa_multa3 / 100);
                                }
                                $status_multa3 = 'Y';
                            } else {
                                $status_multa3 = 'Y';
                            }

                            $multa_final = $multa1 + $multa2 + $multa3;

                            $update = CartaoEstudante::findOrFail($cartao->id);
                            $update->status = 'divida';
                            $update->multa1 = $status_multa1;
                            $update->multa2 = $status_multa2;
                            $update->multa3 = $status_multa3;
                            $update->multa = $update->multa + $multa_final;

                            $update->update();
                        }
                    }
                }
            }
        }

        dd("stop");
    }
}
