<?php

namespace App\Http\Controllers;

use App\Models\FormaPagamento;
use App\Models\User;
use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\calendarios\CartaoEscola;
use App\Models\web\calendarios\Matricula;
use App\Models\web\calendarios\Pagamento;
use App\Models\web\calendarios\PagamentoNotaCredito;
use App\Models\web\calendarios\PagamentoRecibo;
use App\Models\web\calendarios\Servico;
use App\Models\web\calendarios\ServicoTurma;
use App\Models\web\classes\Classe;
use App\Models\web\cursos\Curso;
use App\Models\web\estudantes\CartaoEstudante;
use App\Models\web\estudantes\Estudante;
use App\Models\web\financeiros\DetalhesPagamentoNotaCredito;
use App\Models\web\financeiros\DetalhesPagamentoPropina;
use App\Models\web\financeiros\DetalhesPagamentoRecibo;
use App\Models\web\funcionarios\Funcionarios;
use App\Models\web\salas\Banco;
use App\Models\web\salas\Caixa;
use App\Models\web\turmas\EstudantesTurma;
use App\Models\web\turmas\Turma;
use App\Models\web\turnos\Turno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\web\salas\MovimentoCaixa;
use App\Models\web\salas\Sala;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;
use phpseclib\Crypt\RSA;
use Illuminate\Support\Facades\DB;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use NumberFormatter;

class FacturaController extends Controller
{
    use TraitChavesSaft;
    use TraitHelpers;


    public function __construct()
    {
        $this->middleware('auth');
    }


    // pagamento de propina estudanets
    public function facturaPagamentoServico($id)
    {
        $user = auth()->user();

        if (!$user->can('create: factura') && !$user->can('read: factura')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $estudantes = Estudante::findOrFail($id);

        $turma = EstudantesTurma::where([
            ['estudantes_id', '=', $estudantes->id],
            ['status', '=', 'activo'],
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
        ])
            ->select('tb_turmas_estudantes.turmas_id')
            ->first();

        $caixas = Caixa::where([
            ['shcools_id', '=', $this->escolarLogada()],
        ])->get();

        $servicos = null;

        if ($turma) {
            $servicos = ServicoTurma::where([
                ['turmas_id', '=', $turma->turmas_id],
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['model', '=', 'turmas'],
            ])->join('tb_servicos', 'tb_servicos_turma.servicos_id', '=', 'tb_servicos.id')
                ->select('tb_servicos.servico', 'tb_servicos.id')
                ->get();
        }



        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            "servicos" => $servicos,
            "turma" => $turma,
            "estudantes" => $estudantes,
            "formas_pagamento" => FormaPagamento::get(),
            "caixas" => $caixas,
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.financeiros.facturar-servico', $headers);
    }

    public function facturaPagamentoServicoCreate(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('create: factura')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $estudantes = Estudante::findOrFail($request->input('estudantes_id'));
        $ano_lectivo_activo = AnoLectivo::findOrFail($this->anolectivoActivo());

        $request->validate([
            'factura' => 'required',
            'pagamento' => 'required',
            'data_vencimento' => 'required',
            'caixa' => 'required',
            'estudantes_id' => 'required',
        ]);

        try {

            // Inicia a transação
            DB::beginTransaction();

            $forma_pagamento = FormaPagamento::where('sigla_tipo_pagamento', $request->forma_pagamento)->first();

            $items = DetalhesPagamentoPropina::selectRaw('
                SUM(multa) as total_multa,
                SUM(preco) as total_preco,
                SUM(total_pagar) as total_a_pagar,
                SUM(quantidade) as total_quantidade,
                SUM(desconto_valor) as total_desconto_valor,
                SUM(valor_incidencia) as total_incidencia,
                SUM(valor_iva) as total_iva
            ')
                ->where('status', 'processo')
                ->where('funcionarios_id', Auth::user()->id)
                ->where('model_id', $estudantes->id)
            ->first();

            $contarFactura = Pagamento::where('tipo_factura', $request->factura)
                ->where('factura_ano', $ano_lectivo_activo->serie ?? date("Y"))
                ->where('shcools_id', $this->escolarLogada())
                ->count();

            $ultimoRecibo = Pagamento::where('tipo_factura', $request->factura)
                ->where('factura_ano', $ano_lectivo_activo->serie ?? date("Y"))
                ->where('shcools_id', $this->escolarLogada())
                ->latest()
                ->first();

            if (!$ultimoRecibo) {
                $hashAnterior = "";
            } else {
                $hashAnterior = $ultimoRecibo->hash;
            }

            //Manipulação de datas: data actual
            $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
            $numeroFactura = $contarFactura + 1;

            $rsa = new RSA(); //Algoritimo RSA

            $privatekey = $this->pegarChavePrivada();
            $publickey = $this->pegarChavePublica();

            // Lendo a private key
            $rsa->loadKey($privatekey);

            $codigo_designacao_factura = "EAV";
            $data_do_vencimento = date("Y-m-d");

            $dias = 0;
            if ($request->data_vencimento == 0) {
                $data_do_vencimento = date("Y-m-d");
                $dias = 0;
            } else if ($request->data_vencimento == 15) {
                $data_do_vencimento  = date("Y-m-d", strtotime($datactual . "+15days"));
                $dias = 15;
            } else if ($request->data_vencimento == 30) {
                $data_do_vencimento  = date("Y-m-d", strtotime($datactual . "+30days"));
                $dias = 30;
            } else if ($request->data_vencimento == 45) {
                $data_do_vencimento  = date("Y-m-d", strtotime($datactual . "+45days"));
                $dias = 45;
            } else if ($request->data_vencimento == 60) {
                $data_do_vencimento  = date("Y-m-d", strtotime($datactual . "+60days"));
                $dias = 60;
            } else if ($request->data_vencimento == 90) {
                $data_do_vencimento  = date("Y-m-d", strtotime($datactual . "+90days"));
                $dias = 90;
            }

            $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ';' . "{$request->factura} {$codigo_designacao_factura}{$ano_lectivo_activo->serie}/{$numeroFactura}" . ';' . number_format($items->total_a_pagar, 2, ".", "") . ';' . $hashAnterior;

            // HASH
            $hash = 'sha1'; // Tipo de Hash
            $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima

            //ASSINATURA
            $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
            $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)

            // Lendo a public key
            $rsa->loadKey($publickey);

            $valor_extenso = $this->valor_por_extenso($items->total_a_pagar);

            $code = time();

            $cartao_estudantil = CartaoEstudante::where('status', 'processo')
                ->where('estudantes_id', $estudantes->id)
                ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->get();

            $servicosDiferentes = DetalhesPagamentoPropina::where('status', 'processo')
                ->where('funcionarios_id', Auth::user()->id)
                ->where('model_id', $estudantes->id)
                ->distinct()
            ->count('servicos_id');

            if ($servicosDiferentes > 1) {
                $servico = Servico::where('shcools_id', $this->escolarLogada())
                    ->where('servico', "Diversos")
                    ->join('tb_taxas', 'tb_servicos.taxa_id', '=', 'tb_taxas.id')->select('tb_servicos.id', 'taxa', 'contas', 'tb_servicos.servico', 'tb_servicos.contas')
                ->first();
            } else {
                // Existe apenas um ou nenhum servico_id.
                $servico = Servico::join('tb_taxas', 'tb_servicos.taxa_id', '=', 'tb_taxas.id')->select('tb_servicos.id', 'taxa', 'contas', 'tb_servicos.servico', 'tb_servicos.contas')->findOrFail($request->servico);
            }

            $timestemps = DetalhesPagamentoPropina::where('status', 'processo')
                ->where('funcionarios_id', Auth::user()->id)
                ->where('model_id', $estudantes->id)
            ->get();

            if ($request->factura == "FT") {
                $retificado = 'N';
                $convertido_factura = 'N';
                $factura_divida = 'Y';
                $anulado = 'N';
                $status = "Pendente";
            } else {
                $retificado = 'N';
                $convertido_factura = 'N';
                $factura_divida = 'N';
                $anulado = 'N';
                $status = "Pendente";
            }

            $createPagamento = Pagamento::create([
                "pago_at" => strtolower($servico->servico),
                "quantidade" => $items->total_quantidade ?? 1,
                "servicos_id" => $servico->id,
                "caixa_at" => $servico->contas,
                "status" =>  $status,
                'tipo_servico_detalhe' => 'mensal',
                "ficha" => $code,
                "referencia" => $code,
                "valor" => $items->total_preco / $items->total_quantidade,
                "valor2" => $items->total_a_pagar,
                "desconto" => $items->total_desconto_valor,
                "multa" => $request->valor_multa,
                'valor_entregue' => 0,
                'troco' => 0,

                "banco_id" => $request->banco_id ? $request->banco_id : "",
                "caixa_id" => $request->caixa ? $request->caixa : "",

                "data_at" => $this->data_sistema(),
                "mensal" => $this->mesecompleto(),
                "tipo_pagamento" => $forma_pagamento->sigla_tipo_pagamento,
                "pagamento_id" => $forma_pagamento->id,
                "funcionarios_id" => Auth::user()->id,
                "estudantes_id" => $estudantes->id,
                "model" => $request->destino_factura,
                "ano_lectivos_id" => $this->anolectivoActivo(),
                "numero_factura" => $numeroFactura,
                "tipo_factura" => $request->factura,
                "next_factura" => "{$request->factura} {$codigo_designacao_factura}{$ano_lectivo_activo->serie}/{$numeroFactura}",
                "shcools_id" => $this->escolarLogada(),
                'data_vencimento' => $data_do_vencimento,
                'data_disponibilizacao' => $request->data_desponibilizacao ?? "",
                'conta_corrente_cliente' => $estudantes->conta_corrente ?? "",
                "valor_extenso" => $valor_extenso,
                'total_iva' => $items->total_iva,
                'factura_ano' => $ano_lectivo_activo->serie ?? date("Y"),
                'prazo' => $dias,

                'retificado' => $retificado,
                'convertido_factura' => $convertido_factura,
                'factura_divida' => $factura_divida,
                'anulado' => $anulado,

                'moeda' => 'AOA',
                'valor_cash' => 0,
                'valor_multicaixa' => 0,
                'texto_hash' => $plaintext,
                'hash' => base64_encode($signaturePlaintext),
                'nif_cliente' => $estudantes->bilheite,
                'total_incidencia' => $items->total_incidencia,
                'observacao' => $request->observacao ?? "",
                "numero_transacao" => NULL,
            ]);

            if ($timestemps) {
                foreach ($timestemps as $item) {
                    $upd = DetalhesPagamentoPropina::findOrFail($item->id);
                    if ($upd->multa > 0) {
                        $status = "divida";
                    } else {
                        $status = 'Nao Pago';
                    }
                    $upd->code = $code;
                    $upd->status = $status;
                    $upd->pagamentos_id = $createPagamento->id;
                    $upd->update();
                }
            }

            if ($cartao_estudantil) {
                foreach ($cartao_estudantil as $cartao) {
                    $upd = CartaoEstudante::findOrFail($cartao->id);
                    if ($upd->multa > 0) {
                        $status = "divida";
                    } else {
                        $status = 'Nao Pago';
                    }
                    $upd->status = $status;
                    $upd->update();
                }
            }

            // Comita a transação se tudo estiver correto
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            Alert::error('Error', $e->getMessage());
            return redirect()->back();
        }

        if ($createPagamento->tipo_factura == "FP") {
            return redirect()->route('comprovativo-factura-proforma', [$createPagamento->ficha, "ORGINAL"]);
        } else if ($createPagamento->tipo_factura == "FT") {
            return redirect()->route('comprovativo-factura-factura', [$createPagamento->ficha, "ORGINAL"]);
        }
    }

    // ficha ou recibo de pagamento de propinas
    public function ComprovativoFacturaPagamentoServico($code)
    {
        $user = auth()->user();

        if (!$user->can('read: factura')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $pagamento = Pagamento::where([
            ['ficha', '=', $code],
            ['model', '=', 'estudante'],
        ])
        ->join('tb_servicos', 'tb_pagamentos.servicos_id', '=', 'tb_servicos.id')
        ->first();

        $matricula = Matricula::where([
            ['estudantes_id', '=', $pagamento->estudantes_id],
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['status_matricula', '=', 'confirmado'],
        ])->first();

        $estudante = Estudante::find($pagamento->estudantes_id);

        $detalhe = DetalhesPagamentoPropina::where([
            ['code', '=', $pagamento->ficha],
        ])
        ->select('tb_detalhes_pagamentos.quantidade', 'tb_detalhes_pagamentos.status', 'tb_detalhes_pagamentos.preco', 'tb_detalhes_pagamentos.id', 'tb_detalhes_pagamentos.mes', 'tb_detalhes_pagamentos.multa')
        ->get();

        $curso = Curso::findOrFail($matricula->cursos_id);
        $classe = Classe::findOrFail($matricula->classes_id);
        $turno = Turno::findOrFail($matricula->turnos_id);

        $turma = Turma::where([
            ['cursos_id', '=', $curso->id],
            ['classes_id', '=', $classe->id],
            ['turnos_id', '=', $turno->id],
        ])->first();

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            'pagamento' => $pagamento,
            'matricula' => $matricula,
            'estudante' => $estudante,
            'detalhes' => $detalhe,
            'curso' => $curso,
            'classe' => $classe,
            'turno' => $turno,
            'turma' => $turma,
            'sala' => Sala::findOrFail($turma->salas_id),
            'funcionario' => User::findOrFail($pagamento->funcionarios_id),
            'funcionarioAtendente' => User::findOrFail($pagamento->funcionarios_id),
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada())
        ];

        $pdf = \PDF::loadView('downloads.financeiros.comprovativo-factura-pagamento-servico', $headers)->setPaper('A4', 'portrait');
        return $pdf->stream('comprovativo-factura-pagamento-servico.pdf');
    }

    public function fichaMatricula($ficha, $tipo_documento = null)
    {
        $user = auth()->user();

        if (!$user->can('read: matricula') && !$user->can('read: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        if ($tipo_documento == "RG") {
            $pagamento = PagamentoRecibo::where('ficha', Crypt::decrypt($ficha))
                ->where('ano_lectivos_id', $this->anolectivoActivo())
                ->with(['servico', 'operador'])
            ->first();

            $detalhesPagamento = DetalhesPagamentoRecibo::with(['servico'])->where('pagamentos_id', '=', $pagamento->id)->get();
        } else {
            $pagamento = Pagamento::where('ficha', Crypt::decrypt($ficha))
                ->where('ano_lectivos_id', $this->anolectivoActivo())
                ->with(['servico', 'operador'])
            ->first();
            $detalhesPagamento = DetalhesPagamentoPropina::with(['servico'])->where('pagamentos_id', '=', $pagamento->id)->get();
        }

        $dados = NULL;
        $curso = NULL;
        $classe = NULL;
        $classe_at = NULL;
        $turma = NULL;
        $turno = NULL;
        $matricula = NULL;

        if ($pagamento) {
            if ($pagamento->model == "estudante") {
                $dados = Estudante::findOrFail($pagamento->estudantes_id);

                $matricula = Matricula::where('estudantes_id', $dados->id)
                    ->where('ano_lectivos_id', $this->anolectivoActivo())
                    ->where('status_matricula', 'confirmado')
                    ->where('shcools_id', $this->escolarLogada())
                ->first();

                $curso = Curso::findOrFail($matricula->cursos_id);

                $classe = Classe::findOrFail($matricula->classes_id);
                $classe_at = Classe::findOrFail($matricula->at_classes_id);
                $turno = Turno::findOrFail($matricula->turnos_id);

                $turma = Turma::where('cursos_id', $curso->id)
                    ->where('turnos_id', $turno->id)
                    ->where('classes_id', $classe->id)
                ->first();
                
            } else if ($pagamento->model == "escola") {
                $dados = Shcool::findOrFail($pagamento->estudantes_id);
            } else {
                $dados = Funcionarios::findOrFail($pagamento->estudantes_id);
            }
        }

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "usuario" => User::findOrFail(Auth::user()->id),
            'matricula' => $matricula,
            'dados' => $dados,
            'curso' => $curso,
            'turma' => $turma,
            'classe' => $classe,
            'classe_at' => $classe_at,
            'turno' => $turno,
            'pagamento' => $pagamento,
            'detalhesPagamento' => $detalhesPagamento,
            'ano_lectivo' => AnoLectivo::findOrFail($this->anolectivoActivo()),
        ];

        return view('relatorios.ficha-matricula', $headers);
    }

    public function facturasReferente($ficha)
    {
        $user = auth()->user();

        if (!$user->can('read: factura')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $pagamento = Pagamento::where([
            ['numeracao_facturacao', 'like', $ficha],
            ['ano_lectivos_id', '=', $this->anolectivoActivo()]
        ])
            ->with('oparador')
            ->with('servico')
            ->first();

        $detalhesPagamento = DetalhesPagamentoPropina::where([
            ['code', '=', $pagamento->ficha],
        ])
            ->get();

        $dados = NULL;
        $curso = NULL;
        $classe = NULL;
        $classe_at = NULL;
        $turma = NULL;
        $turno = NULL;
        $matricula = NULL;

        if ($pagamento) {
            if ($pagamento->model == "estudante") {
                $dados = Estudante::findOrFail($pagamento->estudantes_id);

                $matricula = Matricula::where([
                    ['estudantes_id', '=', $dados->id],
                    ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                    ['status_matricula', '=', 'confirmado'],
                    ['shcools_id', '=', $this->escolarLogada()],
                ])->first();

                $curso = Curso::findOrFail($matricula->cursos_id);

                $classe = Classe::findOrFail($matricula->classes_id);
                $classe_at = Classe::findOrFail($matricula->at_classes_id);
                $turno = Turno::findOrFail($matricula->turnos_id);

                $turma = Turma::where([
                    ['cursos_id', '=', $curso->id],
                    ['turnos_id', '=', $turno->id],
                    ['classes_id', '=', $classe->id],
                ])->first();
            } else if ($pagamento->model == "escola") {
                $dados = Shcool::findOrFail($pagamento->estudantes_id);
            } else {
                $dados = Funcionarios::findOrFail($pagamento->estudantes_id);
            }
        }



        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "usuario" => User::findOrFail(Auth::user()->id),
            'matricula' => $matricula,
            'dados' => $dados,
            'curso' => $curso,
            'turma' => $turma,
            'classe' => $classe,
            'classe_at' => $classe_at,
            'turno' => $turno,
            'pagamento' => $pagamento,
            'detalhesPagamento' => $detalhesPagamento,
            'funcionarioAtendente' => User::findOrFail($pagamento->funcionarios_id),
            'ano_lectivo' => AnoLectivo::findOrFail($this->anolectivoActivo()),
        ];

        return view('relatorios.facturas-referente', $headers);
    }

    public function facturas(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: factura')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }


        $pagamentos = Pagamento::when($request->data1, function ($query, $value) {
            $query->where('data_at', '>=', Carbon::parse($value));
        })
            ->when($request->data2, function ($query, $value) {
                $query->where('data_at', '<=', Carbon::parse($value));
            })
            ->when($request->filtro, function ($query, $value) {
                $query->where('servicos_id', $value);
            })
            ->where('ano_lectivos_id', '=', $this->anolectivoActivo())
            ->where('shcools_id', '=', $this->escolarLogada())
            ->with('operador', 'servico')
        ->get();


        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Listagem das Facturas",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            'pagamentos' => $pagamentos,
            "servicos" => Servico::where([
                ['shcools_id', '=', $this->escolarLogada()]
            ])->get(),

            "filtro" => $request->all('data1', 'data2', 'factura', 'filtro')
        ];

        return view('admin.financeiros.facturas', $headers);
    }

    public function liquidarFacturas(Request $request)
    {
        $user = auth()->user();

        // if(!$user->can('liquidar factura')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $pagamentos = Pagamento::when($request->data1, function ($query, $value) {
            $query->where('data_at', '>=', Carbon::parse($value));
        })
            ->when($request->data2, function ($query, $value) {
                $query->where('data_at', '<=', Carbon::parse($value));
            })
            ->when($request->factura, function ($query, $value) {
                $query->where('tipo_factura', $value);
            })
            ->when($request->filtro, function ($query, $value) {
                $query->where('servicos_id', $value);
            })
            ->whereIn('tipo_factura', ["FT", "FP"])
            ->where('anulado', 'N')
            ->where('factura_divida', 'Y')
            ->where('ano_lectivos_id', '=', $this->anolectivoActivo())
            ->where('shcools_id', '=', $this->escolarLogada())
            ->with('operador', 'servico')
            ->get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Listagem das Facturas",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            'pagamentos' => $pagamentos,
            "servicos" => Servico::where([
                ['shcools_id', '=', $this->escolarLogada()]
            ])->get(),

            "requests" => $request->all('data1', 'data2', 'filtro', 'factura')
        ];

        return view('admin.financeiros.liquidar-facturas', $headers);
    }

    public function cancelarFacturas(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('update: factura')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $dados = NULL;
        $curso = NULL;
        $classe = NULL;
        $classe_at = NULL;
        $turma = NULL;
        $turno = NULL;
        $matricula = NULL;
        $pagamento = NULL;
        $detalhesPagamento = NULL;
        $funcionarioAtendente = NULL;

        $pagamentos = PagamentoNotaCredito::where([
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
        ])
            ->with('operador')
            ->with('servico')
            ->get();



        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Cancelar Facturas",
            "usuario" => User::findOrFail(Auth::user()->id),
            'matricula' => $matricula,
            'dados' => $dados,
            'curso' => $curso,
            'turma' => $turma,
            'classe' => $classe,
            'classe_at' => $classe_at,
            'turno' => $turno,
            'pagamento' => $pagamento,
            'detalhesPagamento' => $detalhesPagamento,
            "funcionarioAtendente" => $funcionarioAtendente,
            'ano_lectivo' => AnoLectivo::findOrFail($this->anolectivoActivo()),

            /** todas as factuas canceladas */
            'pagamentos' => $pagamentos,
            "servicos" => Servico::where([
                ['shcools_id', '=', $this->escolarLogada()]
            ])->get(),
            "status_imprimir" => false
        ];


        return view('relatorios.cancelar-factura', $headers);
    }

    public function cancelarFacturasCreate($ficha)
    {
        $user = auth()->user();

        if (!$user->can('update: factura')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $pagamento = Pagamento::where([
            ['ficha', '=', $ficha],
        ])
            ->first();

        if ($pagamento) {

            $detalhesPagamento = DetalhesPagamentoPropina::where([
                ['code', '=', $pagamento->ficha],
            ])
                ->get();

            if ($detalhesPagamento) {
                foreach ($detalhesPagamento as $key) {

                    if ($pagamento->model == "estudante") {

                        $cartao = CartaoEstudante::where([
                            ['estudantes_id', '=', $pagamento->estudantes_id],
                            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                            ['month_name', '=', $key->mes],
                        ])
                            ->get();

                        if ($cartao) {
                            foreach ($cartao as $cart) {

                                if ($cart) {
                                    $updateCar = CartaoEstudante::findOrFail($cart->id);
                                    $updateCar->status = "Nao Pago";
                                    $updateCar->update();
                                }
                            }
                        }
                    } else if ($pagamento->model == "escola") {
                        $cartao = CartaoEscola::where([
                            ['shcools_id', '=', $pagamento->estudantes_id],
                            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                            ['month_name', '=', $key->mes],
                        ])
                            ->get();

                        if ($cartao) {
                            foreach ($cartao as $cart) {
                                $updateCar = CartaoEscola::findOrFail($cart->id);
                                $updateCar->status = "Nao Pago";
                                $updateCar->save();
                            }
                        }
                    }

                    // cancelar o detalhe do pagameto pagamento
                    $updateDet = DetalhesPagamentoPropina::findOrFail($key->id);
                    $updateDet->status = "cancelado";
                    $updateDet->update();
                }
                // cancelar o pagamento
                $updatePag = Pagamento::findOrFail($pagamento->id);
                if ($updatePag) {
                    $updatePag->status = "cancelado";
                    $updatePag->anulado = "Y";
                    $updatePag->save();
                }
            }
            Alert::success("Bom Trabalho", "Factura Anulado com sucesso!");
            return redirect()->back(); //route('web.cancelar-facturas')->with("success", "Factura cancelada com sucesso");
        }

        return redirect()->back(); // route('web.cancelar-facturas');
    }

    public function recuperarFacturasCreate($ficha)
    {
        $user = auth()->user();

        if (!$user->can('update: factura') && !$user->can('read: factura')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $pagamento = Pagamento::where([
            ['ficha', '=', $ficha],
        ])
            ->select('id', 'ficha', 'estudantes_id', 'model')
            ->first();

        if ($pagamento) {

            $detalhesPagamento = DetalhesPagamentoPropina::where([
                ['code', '=', $pagamento->ficha],
            ])
                ->get();

            if ($detalhesPagamento) {
                foreach ($detalhesPagamento as $key) {

                    if ($pagamento->model == "estudante") {

                        $cartao = CartaoEstudante::where([
                            ['estudantes_id', '=', $pagamento->estudantes_id],
                            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                            ['month_name', '=', $key->mes],
                        ])
                            ->get();

                        if ($cartao) {
                            foreach ($cartao as $cart) {
                                if ($cart) {
                                    $updateCar = CartaoEstudante::findOrFail($cart->id);
                                    $updateCar->status = "Pago";
                                    $updateCar->update();
                                }
                            }
                        }
                    } else if ($pagamento->model == "escola") {
                        $cartao = CartaoEscola::where([
                            ['shcools_id', '=', $pagamento->estudantes_id],
                            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                            ['month_name', '=', $key->mes],
                        ])
                            ->get();
                        if ($cartao) {
                            foreach ($cartao as $cart) {
                                $updateCar = CartaoEscola::findOrFail($cart->id);
                                $updateCar->status = "Pago";
                                $updateCar->save();
                            }
                        }
                    }

                    // cancelar o detalhe do pagameto pagamento
                    $updateDet = DetalhesPagamentoPropina::findOrFail($key->id);
                    $updateDet->status = "confirmado";
                    $updateDet->update();
                }
                // cancelar o pagamento
                $updatePag = Pagamento::findOrFail($pagamento->id);
                if ($updatePag) {
                    $updatePag->status = "confirmado";
                    $updatePag->anulado = "N";
                    $updatePag->save();
                }
            }

            Alert::success("Bom Trabalho", "Factura Recuperada com sucesso!");
            return redirect()->back();
        }

        return redirect()->back();
    }


    public function conversaoFacturas(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('update: factura') && !$user->can('read: factura')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $dados = NULL;
        $curso = NULL;
        $classe = NULL;
        $classe_at = NULL;
        $turma = NULL;
        $turno = NULL;
        $matricula = NULL;
        $pagamento = NULL;
        $detalhesPagamento = NULL;
        $funcionarioAtendente = NULL;

        $pagamentos = Pagamento::where([
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['tb_pagamentos.convertido_factura', '=', "Y"],
            ['tb_pagamentos.numeracao_proforma', '!=', NULL],
        ])
            ->with('operador')
            ->with('servico')
            ->get();




        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Facturas convertidas",
            "usuario" => User::findOrFail(Auth::user()->id),
            'matricula' => $matricula,
            'dados' => $dados,
            'curso' => $curso,
            'turma' => $turma,
            'classe' => $classe,
            'classe_at' => $classe_at,
            'turno' => $turno,
            'pagamento' => $pagamento,
            'detalhesPagamento' => $detalhesPagamento,
            "funcionarioAtendente" => $funcionarioAtendente,
            'ano_lectivo' => AnoLectivo::findOrFail($this->anolectivoActivo()),

            /** todas as factuas canceladas */
            'pagamentos' => $pagamentos,
            "servicos" => Servico::where([
                ['shcools_id', '=', $this->escolarLogada()]
            ])->get(),
            "status_imprimir" => false
        ];

        return view('relatorios.conversao-factura', $headers);
    }

    public function cancelarFacturasSearch(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: factura')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $pagamento = Pagamento::where('next_factura', $request->factura)
            ->orWhere('ficha', $request->factura)
            ->where('anulado', '=', "N")
            ->first();

        if (!$pagamento) {
            Alert::error("Erro", "Factura não encontrada, Verifica Essa factura se já foi anulada uma vez");
            return redirect()->back();
        }

        return redirect()->route('web.documento-cancelar-facturas', $pagamento->ficha);
    }

    public function conversaoFacturasSearch(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: factura')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $pagamento = Pagamento::where('next_factura', $request->factura)
            ->first();

        if (!$pagamento) {
            Alert::error("Erro", "Factura não encontrada!");
            return redirect()->back();
        }

        return redirect()->route('web.converter-facturas', $pagamento->ficha);
    }

    public function converterFacturas($ficha)
    {
        $user = auth()->user();

        if (!$user->can('update: factura') && !$user->can('read: factura')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $pagamento = Pagamento::where([
            ['ficha', '=', $ficha],
        ])
            ->with('operador')
            ->with('servico')
            ->first();



        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Converter Facturas",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            'pagamento' => $pagamento,
        ];

        return view('admin.documentos.converter-facturas', $headers);
    }

    public function converterFacturasCreate(Request $request)
    {

        $user = auth()->user();

        if (!$user->can('update: factura') && !$user->can('read: factura')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        if ((!filter_var($request->valor_entregue, FILTER_VALIDATE_FLOAT) || !filter_var($request->valor_entregue, FILTER_VALIDATE_INT))) {
            Alert::warning('Atenção', "O Valor não podem ser Letras por favor");
            return redirect()->back();
        }

        if ($request->valor_entregue < $request->preco_factura) {
            Alert::warning('Atenção', "O valor Entregue para o pagamento deste serviço é insuficiente");
            return redirect()->back();
        }


        $pagamento = Pagamento::where('ficha', $request->ficha_factura)->where('convertido_factura', 'N')->first();


        if (!$pagamento) {
            Alert::warning('Atenção', "Esta factura não existe, provavelmente esta factura já foi convertida caso seja uma factura, emita o Recibo na listagem das facturas!");
            return redirect()->back();
        }

        if ($pagamento->tipo_factura == $request->tipo_factura) {
            Alert::warning('Atenção', "Não pode Converter uma factura do mesmo tipo!");
            return redirect()->back();
        }
        if ($pagamento->tipo_factura == "FR") {
            Alert::warning('Atenção', "Não é permitido converter uma factura Recibo!");
            return redirect()->back();
        }

        if ($pagamento->tipo_factura == "FT" && $request->tipo_factura == "FP") {
            Alert::warning('Atenção', "Não é permitido converter uma factura para factura pró-forma!");
            return redirect()->back();
        }

        $contarFacturas = Pagamento::where([
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['tipo_factura', '=', $request->tipo_factura],
        ])->count();

        $yearNow = Carbon::parse(Carbon::now())->format('Y');

        $ultimoRecibo = Pagamento::where('shcools_id', $this->escolarLogada())
            ->where('created_at', 'like', '%' . $yearNow . '%')
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->orderBy('id', 'DESC')
            ->limit(1)
            ->first();
        /**
         * hashAnterior inicia vazio
         */
        $hashAnterior = "";
        if ($ultimoRecibo) {
            $dataRecibo = Carbon::createFromFormat('Y-m-d H:i:s', $ultimoRecibo->created_at);
            $hashAnterior = $ultimoRecibo->hash;
        } else {
            $dataRecibo = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        }
        //Manipulação de datas: data actual
        $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        /**
         * Recupera a sequência numérica da última factura cadastrada no banco de dados e adiona sempre 1 na sequência caso o 
         * ano da afctura seja igual ao ano actual; 
         * E reinicia a sequência numérica caso se constate que o ano da factura é inferior ao ano actual.*/

        if ($dataRecibo->diffInYears($datactual) == 0) {
            if ($ultimoRecibo) {

                $dataRecibo = Carbon::createFromFormat('Y-m-d H:i:s', $ultimoRecibo->created_at);
                $numSequenciaRecibo = intval($ultimoRecibo->numSequenciaRecibo) + 1;
            } else {
                $dataRecibo = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
                $numSequenciaRecibo = 1;
            }
        } else if ($dataRecibo->diffInYears($datactual) > 0) {
            $numSequenciaRecibo = 1;
        }

        $numSequenciaRecibo = $contarFacturas + 1;

        $numeracaoRecibo = $request->tipo_factura . ' AGT' . '' . date('Y') . '/' . $numSequenciaRecibo; //retirar somente 3 primeiros caracteres na facturaSerie da factura: substr('abcdef', 0, 3);

        $rsa = new RSA(); //Algoritimo RSA

        $privatekey = $this->pegarChavePrivada();
        $publickey = $this->pegarChavePublica();

        // Lendo a private key
        $rsa->loadKey($privatekey);
        /**
         * Texto que deverá ser assinado com a assinatura RSA::SIGNATURE_PKCS1, e o Texto estará mais ou menos assim após as
         * Concatenações com os dados preliminares da factura: 2020-09-14;2020-09-14T20:34:09;FP PAT2020/1;457411.2238438; */
        $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ';' . $numeracaoRecibo . ';' . number_format(($pagamento->valor * $pagamento->quantidade) - $pagamento->desconto, 2, ".", "") . ';' . $hashAnterior;

        // HASH
        $hash = 'sha1'; // Tipo de Hash
        $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima

        //ASSINATURA
        $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
        $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)

        // Lendo a public key
        $rsa->loadKey($publickey);

        $detalhe_pagamentos = DetalhesPagamentoPropina::where('code', $pagamento->ficha)->get();

        if ($request->tipo_factura == 'FP') {
            $status = 'Pendente';
        } else {
            $status = 'Confirmado';
        }

        if ($request->tipo_factura == 'FP') {
            $status2 = 'Nao Pago';
        } else {
            $status2 = 'Pago';
        }

        /**
         * caso seja uma conversão para recibo o procedimento é outro porque se encontra em outra tabela os seus registros
         */
        if ($request->tipo_factura == 'RG') {
            $contarFacturas = PagamentoRecibo::where([
                ['ano_lectivos_id', $this->anolectivoActivo()],
                ['tipo_factura', 'RG'],
            ])->count();

            if ($request->forma_pagamento == "NUMERARIO") {
                $valor_cash = ($pagamento->valor * $pagamento->quantidade) - $pagamento->desconto;
                $valor_multicaixa = 0;
            } else if ($pagamento->forma_pagamento == "MULTICAIXA") {
                $valor_cash = 0;
                $valor_multicaixa = ($pagamento->valor * $pagamento->quantidade) - $pagamento->desconto;
            }

            $yearNow = Carbon::parse(Carbon::now())->format('Y');

            $ultimoRecibo = PagamentoRecibo::where('shcools_id', $this->escolarLogada())
                ->where('created_at', 'like', '%' . $yearNow . '%')
                ->where('tipo_factura', 'RG')
                ->where('ano_lectivos_id', $this->anolectivoActivo())
                ->orderBy('id', 'DESC')
                ->limit(1)
                ->first();

            /**
             * hashAnterior inicia vazio
             */
            $hashAnterior = "";
            if ($ultimoRecibo) {
                $dataRecibo = Carbon::createFromFormat('Y-m-d H:i:s', $ultimoRecibo->created_at);
                $hashAnterior = $ultimoRecibo->hash;
            } else {
                $dataRecibo = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
            }
            //Manipulação de datas: data actual
            $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
            /**
             * Recupera a sequência numérica da última factura cadastrada no banco de dados e adiona sempre 1 na sequência caso o 
             * ano da afctura seja igual ao ano actual; 
             * E reinicia a sequência numérica caso se constate que o ano da factura é inferior ao ano actual.*/

            if ($dataRecibo->diffInYears($datactual) == 0) {
                if ($ultimoRecibo) {

                    $dataRecibo = Carbon::createFromFormat('Y-m-d H:i:s', $ultimoRecibo->created_at);
                    $numSequenciaRecibo = intval($ultimoRecibo->numSequenciaRecibo) + 1;
                } else {
                    $dataRecibo = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
                    $numSequenciaRecibo = 1;
                }
            } else if ($dataRecibo->diffInYears($datactual) > 0) {
                $numSequenciaRecibo = 1;
            }

            $numSequenciaRecibo = $contarFacturas + 1;

            $numeracaoRecibo = "RG" . ' AGT' . '' . date('Y') . '/' . $numSequenciaRecibo; //retirar somente 3 primeiros caracteres na facturaSerie da factura: substr('abcdef', 0, 3);

            $rsa = new RSA(); //Algoritimo RSA

            $privatekey = $this->pegarChavePrivada();
            $publickey = $this->pegarChavePublica();

            // Lendo a private key
            $rsa->loadKey($privatekey);
            /**
             * Texto que deverá ser assinado com a assinatura RSA::SIGNATURE_PKCS1, e o Texto estará mais ou menos assim após as
             * Concatenações com os dados preliminares da factura: 2020-09-14;2020-09-14T20:34:09;FP PAT2020/1;457411.2238438; */

            $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ';' . $numeracaoRecibo . ';' . number_format(($pagamento->valor * $pagamento->quantidade) - $pagamento->desconto, 2, ".", "") . ';' . $hashAnterior;

            // HASH
            $hash = 'sha1'; // Tipo de Hash
            $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima

            //ASSINATURA
            $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
            $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)

            // Lendo a public key
            $rsa->loadKey($publickey);

            $detalhe_pagamentos = DetalhesPagamentoPropina::where('code', $pagamento->ficha)->get();

            if (!$detalhe_pagamentos) {

                $create = PagamentoRecibo::create([
                    "pago_at" => $pagamento->pago_at,
                    "quantidade" => $pagamento->quantidade,
                    "servicos_id" => $pagamento->servicos_id,
                    "status" =>  "Confirmado",
                    "caixa_at" => $pagamento->caixa_at,
                    "ficha" => time(),
                    "valor" => $pagamento->valor,
                    "valor2" => $request->valor_entregue,
                    "troco" => $request->valor_entregue - ($pagamento->valor * $pagamento->quantidade),
                    "valor_entregue" => $request->valor_entregue,
                    "desconto" => $pagamento->desconto,
                    "multa" => $pagamento->multa,
                    "banco" => $pagamento->banco,
                    "numero_transacao" => $pagamento->numero_transicao,
                    "tipo_pagamento" => $request->forma_pagamento,
                    "data_at" => $this->data_sistema(),
                    "mensal" => $pagamento->mensal,
                    "funcionarios_id" => Auth::user()->id,
                    "estudantes_id" => $pagamento->estudantes_id,
                    "numero_factura" => $numSequenciaRecibo,
                    "tipo_factura" => "RG",
                    "next_factura" => $numeracaoRecibo,
                    "shcools_id" => $this->escolarLogada(),
                    "model" => $pagamento->model,
                    "ano_lectivos_id" => $this->anolectivoActivo(),
                    'data_vencimento' => $pagamento->data_vencimento,
                    'data_disponibilizacao' => $pagamento->data_vencimento,
                    "numeracao_proforma" => $pagamento->next_factura,

                    "valor_extenso" => $pagamento->valor_extenso,
                    'total_iva' => 0,
                    'valor_cash' => $valor_cash,
                    'valor_multicaixa' => $valor_multicaixa,
                    'texto_hash' => $plaintext,
                    'hash' => base64_encode($signaturePlaintext),
                    'nif_cliente' => $pagamento->nif_cliente,
                    'total_incidencia' => ($pagamento->valor * $pagamento->quantidade) - $pagamento->desconto,
                ]);

                if ($create->save()) {
                    $cartao = CartaoEstudante::where([
                        ['estudantes_id', '=', $pagamento->estudantes_id],
                        ['servicos_id', '=', $pagamento->servicos_id],
                        ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                    ])->first();

                    $updateCartao = CartaoEstudante::find($cartao->id);
                    $updateCartao->status = "Pago";
                    $updateCartao->save();
                }
            } else {
                $ficha = DetalhesPagamentoPropina::where([
                    ['code', '=', $pagamento->ficha],
                ])->first();

                $code = time();

                $create = PagamentoRecibo::create([
                    "pago_at" => $pagamento->pago_at,
                    "quantidade" => $pagamento->quantidade,
                    "servicos_id" => $pagamento->servicos_id,
                    "status" => "Confirmado",
                    "caixa_at" => $pagamento->caixa_at,
                    "ficha" => $code,
                    "valor" => $pagamento->valor,

                    "troco" => $request->valor_entregue - ($pagamento->valor * $request->quantidade),
                    "valor_entregue" => $request->valor_entregue,
                    "desconto" => $pagamento->desconto,
                    "multa" => $pagamento->multa,
                    "banco" => $pagamento->banco,

                    "numero_transacao" => $pagamento->numero_transacao,
                    "tipo_pagamento" => $request->forma_pagamento,
                    "data_at" => $this->data_sistema(),
                    "mensal" => $pagamento->mensal,
                    "funcionarios_id" => Auth::user()->id,
                    "estudantes_id" => $pagamento->estudantes_id,
                    "model" => $pagamento->model,
                    "ano_lectivos_id" => $this->anolectivoActivo(),
                    "numero_factura" => $numSequenciaRecibo,
                    "tipo_factura" => "RG",
                    "next_factura" => $numeracaoRecibo,
                    "numeracao_proforma" => $pagamento->next_factura,
                    "shcools_id" => $this->escolarLogada(),

                    'data_vencimento' => $pagamento->data_vencimento,
                    'data_disponibilizacao' => $pagamento->data_disponibilizacao,
                    "numeracao_proforma" =>  $pagamento->next_factura,

                    "valor_extenso" => $pagamento->valor_extenso,
                    'total_iva' => 0,
                    'valor_cash' => $valor_cash,
                    'valor_multicaixa' => $valor_multicaixa,
                    'texto_hash' => $plaintext,
                    'hash' => base64_encode($signaturePlaintext),
                    'nif_cliente' => $pagamento->nif_cliente,
                    'total_incidencia' => ($pagamento->valor * $pagamento->quantidade) - $pagamento->desconto,
                ]);

                if (!$create->save()) {
                    Alert::warning("Atenção", "Ocorreu em erro ao salvar o pagamento, tenta novamente ou entrar em contacto o desenvovidor 
                    do sistema!");
                    return redirect()->back();
                } else {
                    $detalhePagamento = DetalhesPagamentoPropina::where([
                        ['code', '=', $pagamento->ficha],
                    ])->get();

                    if ($detalhePagamento) {
                        foreach ($detalhePagamento as $ficha) {
                            DetalhesPagamentoRecibo::create([
                                'status' => "Pago",
                                'code' => $code,
                                'mes_id' => "NULL",
                                'mes' => $ficha->mes,
                                'quantidade' => $ficha->quantidade,
                                'model_id' => $ficha->model_id,
                                'preco' => $ficha->preco,
                                'date_att' => $this->data_sistema(),
                                'servicos_id' => $ficha->servicos_id,
                                'funcionarios_id' => Auth::user()->id,
                                'ano_lectivos_id' => $this->anolectivoActivo(),
                                'shcools_id' => $this->escolarLogada(),
                            ]);
                        }
                    }

                    if ($detalhePagamento) {
                        foreach ($detalhePagamento as $ficha) {
                            $cartao = CartaoEstudante::where([
                                ['month_name', '=', $ficha->mes],
                                ['estudantes_id', '=', $ficha->model_id],
                                ['servicos_id', '=', $ficha->servicos_id],
                                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                            ])->get();

                            if ($cartao) {
                                if ($cartao) {
                                    foreach ($cartao as $carta) {
                                        $upd = CartaoEstudante::findOrFail($carta->id);
                                        $upd->status = "Pago";
                                        $upd->update();
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } else {

            if (!$detalhe_pagamentos) {

                $create = Pagamento::create([
                    "pago_at" => $pagamento->pago_at,
                    "quantidade" => $pagamento->quantidade,
                    "servicos_id" => $pagamento->servicos_id,
                    "status" =>  $status,
                    "caixa_at" => $pagamento->caixa_at,
                    "ficha" => time(),
                    "valor" => $pagamento->valor,
                    "valor2" => $pagamento->valor2,
                    "troco" => $request->valor_entregue - (($pagamento->valor * $pagamento->quantidade) - $request->desconto),
                    "valor_entregue" => $request->valor_entregue,
                    "desconto" => $pagamento->desconto,
                    "multa" => $pagamento->multa,
                    "banco" => $pagamento->banco,
                    "numero_transacao" => $pagamento->numero_transicao,
                    "tipo_pagamento" => $request->forma_pagamento,
                    "data_at" => $this->data_sistema(),
                    "mensal" => $pagamento->mensal,
                    "funcionarios_id" => Auth::user()->id,
                    "estudantes_id" => $pagamento->estudantes_id,
                    "numero_factura" => $numSequenciaRecibo,
                    "tipo_factura" => $request->tipo_factura,
                    "next_factura" => $numeracaoRecibo,
                    "shcools_id" => $this->escolarLogada(),
                    "model" => $pagamento->model,
                    "ano_lectivos_id" => $this->anolectivoActivo(),
                    'data_vencimento' => $pagamento->data_vencimento,
                    'data_disponibilizacao' => $pagamento->data_vencimento,
                    "numeracao_proforma" => $pagamento->next_factura,

                    "valor_extenso" => $pagamento->valor_extenso,
                    'total_iva' => 0,
                    'valor_cash' => $pagamento->valor_cash,
                    'valor_multicaixa' => $pagamento->valor_multicaixa,
                    'texto_hash' => $plaintext,
                    'hash' => base64_encode($signaturePlaintext),
                    'nif_cliente' => $pagamento->nif_cliente,
                    'total_incidencia' => $pagamento->total_incidencia,
                ]);

                if ($create->save()) {
                    $cartao = CartaoEstudante::where([
                        ['estudantes_id', '=', $pagamento->estudantes_id],
                        ['servicos_id', '=', $pagamento->servicos_id],
                        ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                    ])->first();

                    $updateCartao = CartaoEstudante::find($cartao->id);
                    $updateCartao->status = $status2;
                    $updateCartao->save();
                }
            } else {
                $ficha = DetalhesPagamentoPropina::where([
                    ['code', '=', $pagamento->ficha],
                ])->first();

                $code = time();

                $create = Pagamento::create([
                    "pago_at" => $pagamento->pago_at,
                    "quantidade" => $pagamento->quantidade,
                    "servicos_id" => $pagamento->servicos_id,
                    "status" =>  $status,
                    "caixa_at" => $pagamento->caixa_at,
                    "ficha" => $code,
                    "valor" => $pagamento->valor,
                    "valor2" => $pagamento->valor2,
                    "troco" => $request->valor_entregue - (($pagamento->valor * $pagamento->quantidade) - $request->desconto),
                    "valor_entregue" => $request->valor_entregue,
                    "desconto" => $pagamento->desconto,
                    "multa" => $pagamento->multa,
                    "banco" => $pagamento->banco,
                    "numero_transacao" => $pagamento->numero_transicao,

                    "data_at" => $this->data_sistema(),
                    "mensal" => $pagamento->mensal,
                    "funcionarios_id" => Auth::user()->id,
                    "estudantes_id" => $pagamento->estudantes_id,
                    "numero_factura" => $numSequenciaRecibo,
                    "tipo_factura" => $request->tipo_factura,
                    "next_factura" => $numeracaoRecibo,
                    "shcools_id" => $this->escolarLogada(),
                    "model" => $pagamento->model,
                    "ano_lectivos_id" => $this->anolectivoActivo(),
                    'data_vencimento' => $pagamento->data_vencimento,
                    'data_disponibilizacao' => $pagamento->data_vencimento,
                    "numeracao_proforma" => $pagamento->next_factura,

                    "tipo_pagamento" => $request->forma_pagamento,
                    "valor_extenso" => $pagamento->valor_extenso,
                    'total_iva' => 0,
                    'valor_cash' => $pagamento->valor_cash,
                    'valor_multicaixa' => $pagamento->valor_multicaixa,
                    'texto_hash' => $plaintext,
                    'hash' => base64_encode($signaturePlaintext),
                    'nif_cliente' => $pagamento->nif_cliente,
                    'total_incidencia' => $pagamento->total_incidencia,
                ]);

                if (!$create->save()) {
                    Alert::warning("Atenção", "Ocorreu um erro ao cadastrar as informações do pagamento para a matricula do estudante, tenta novamente ou entrar em contacto o desenvovidor 
                    do sistema!");
                    return redirect()->back();
                } else {
                    $detalhePagamento = DetalhesPagamentoPropina::where([
                        ['code', '=', $pagamento->ficha],
                    ])->get();

                    if ($detalhePagamento) {
                        foreach ($detalhePagamento as $ficha) {
                            DetalhesPagamentoPropina::create([
                                'status' => $status,
                                'code' => $code,
                                'mes_id' => "NULL",
                                'mes' => $ficha->mes,
                                'quantidade' => $ficha->quantidade,
                                'model_id' => $ficha->model_id,
                                'preco' => $ficha->preco,
                                'date_att' => $this->data_sistema(),
                                'servicos_id' => $ficha->servicos_id,
                                'funcionarios_id' => Auth::user()->id,
                                'ano_lectivos_id' => $this->anolectivoActivo(),
                                'shcools_id' => $this->escolarLogada(),
                            ]);
                        }
                    }

                    if ($detalhePagamento) {
                        foreach ($detalhePagamento as $ficha) {
                            $cartao = CartaoEstudante::where([
                                ['month_name', '=', $ficha->mes],
                                ['estudantes_id', '=', $pagamento->estudantes_id],
                                ['servicos_id', '=', $pagamento->servicos_id],
                                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                            ])->get();

                            if ($cartao) {
                                if ($cartao) {
                                    foreach ($cartao as $carta) {
                                        $upd = CartaoEstudante::findOrFail($carta->id);
                                        $upd->status = $status2;
                                        $upd->update();
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $pagamento->convertido_factura = "Y";
        $pagamento->status = $status;
        $pagamento->save();

        Alert::success("Bom Trabalho", "Factura Convertida com successo");

        if ($request->tipo_factura == "FR") {
            return redirect()->route("comprovativo-factura-recibo", $create->ficha);
        } else if ($request->tipo_factura == "FP") {
            return redirect()->route("comprovativo-factura-proforma", $create->ficha);
        } else if ($request->tipo_factura == "FT") {
            return redirect()->route("comprovativo-factura-factura", $create->ficha);
        } else if ($request->tipo_factura == "RG") {
            return redirect()->route("comprovativo-factura-recibo-recibo", $create->ficha);
        } else {
            return redirect()->route("ficha-pagamento-propina", $create->ficha);
        }
    }

    public function emitirReciboFacturas($ficha)
    {
        $user = auth()->user();

        if (!$user->can('read: factura')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $caixa = Caixa::where('status', '=', "activo")->where('shcools_id', '=', $this->escolarLogada())->where('usuario_id', '=', Auth::user()->id)->first();

        if (!$caixa) {
            Alert::error('Atenção', "Deves primeiramente fazer abertura do caixa, antes de fazer qualquer pagamento!");
            return redirect()->back();
        }

        $pagamento = Pagamento::where('ficha', $ficha)->first();

        $bancos = Banco::where('shcools_id', $this->escolarLogada())->get();
        $caixas = Caixa::where('shcools_id', $this->escolarLogada())->whereIn('id', [$caixa->id])->get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Emitir Recibo Facturas",
            "descricao" => env('APP_NAME'),
            "formas_pagamento" => FormaPagamento::where('status_id', 1)->get(),
            "usuario" => User::findOrFail(Auth::user()->id),
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            'pagamento' => $pagamento,
            "bancos" => $bancos,
            "caixas" => $caixas,
        ];

        return view('admin.documentos.emitir-recibo-facturas', $headers);
    }

    public function emitirReciboFacturasCreate(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: factura') && !$user->can('update: factura')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $valor_total_entregue = $request->valor_entregue_multicaixa + $request->valor_entregue;

        if (
            (!filter_var($request->valor_entregue, FILTER_VALIDATE_FLOAT) and
                !filter_var($request->valor_entregue, FILTER_VALIDATE_INT)
            )
        ) {
            Alert::warning('Atenção', "O Valor não podem ser Letras por favor");
            return redirect()->back();
        }

        if ($valor_total_entregue < $request->total_a_pagar) {
            $f = number_format($request->total_a_pagar, 2, ',', '.');
            Alert::warning('Atenção', "O valor Entregue para o pagamento deste serviço é insuficiente {$f} Kz");
            return redirect()->back();
        }

        $caixa = Caixa::where('status', '=', "activo")->where('shcools_id', '=', $this->escolarLogada())->where('usuario_id', '=', Auth::user()->id)->first();
        $ano_lectivo_activo = AnoLectivo::findOrFail($this->anolectivoActivo());

        if (!$caixa) {
            Alert::warning('Informação', 'Não há nenhum caixa aberto no momento. Por favor, abra um caixa para continuar o processo.');
            return redirect()->back();
        }

        $caixaAberto = MovimentoCaixa::where('caixa_id', $caixa->id)
            ->where('usuario_id', Auth::user()->id)
            ->where('status', "aberto")
        ->first()->id;

        if (!$caixaAberto) {
            Alert::warning('Informação', 'Não há nenhum caixa aberto no momento. Por favor, abra um caixa para continuar o processo.');
            return redirect()->back();
        }

        try {
            // Inicia a transação
            DB::beginTransaction();

            $pagamento = Pagamento::where('ficha', $request->ficha_factura)->where('status', 'Pendente')->first();
            $forma_pagamento = FormaPagamento::where('sigla_tipo_pagamento', $request->forma_pagamento)->first();

            if (!$pagamento) {
                Alert::warning('Atenção', "Esta factura não existe, Provalmente já foi emitado um recibo entra em contacto com o administrador do sistema...");
                return redirect()->back();
            }

            if ($pagamento->tipo_factura == $request->tipo_factura) {
                Alert::warning('Atenção', "Não pode Converter uma factura do mesmo tipo!");
                return redirect()->back();
            }

            if ($pagamento->tipo_factura == "FR") {
                Alert::warning('Atenção', "Não é permitido converter uma factura Recibo!");
                return redirect()->back();
            }

            if ($pagamento->tipo_factura == "FT" && $request->tipo_factura == "FP") {
                Alert::warning('Atenção', "Não é permitido converter uma factura para factura pró-forma!");
                return redirect()->back();
            }

            $contarFactura = PagamentoRecibo::where('tipo_factura', '=', $request->tipo_factura)
                ->where('factura_ano', '=', $ano_lectivo_activo->serie ?? date("Y"))
                ->where('shcools_id', '=', $this->escolarLogada())
                ->count();

            $ultimoRecibo = PagamentoRecibo::where('tipo_factura', '=', $request->tipo_factura)
                ->where('factura_ano', '=', $ano_lectivo_activo->serie ?? date("Y"))
                ->where('shcools_id', '=', $this->escolarLogada())
                ->latest()
                ->first();

            if (!$ultimoRecibo) {
                $hashAnterior = "";
            } else {
                $hashAnterior = $ultimoRecibo->hash;
            }

            //Manipulação de datas: data actual
            $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
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

            $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ';' . "{$request->tipo_factura} {$codigo_designacao_factura}{$ano_lectivo_activo->serie}/{$numeroFactura}" . ';' . number_format(($request->total_a_pagar), 2, ".", "") . ';' . $hashAnterior;
            // HASH
            $hash = 'sha1'; // Tipo de Hash
            $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima

            //ASSINATURA
            $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
            $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)

            // Lendo a public key
            $rsa->loadKey($publickey);

            $valor_extenso = $this->valor_por_extenso($request->total_a_pagar);

            if ($forma_pagamento->sigla_tipo_pagamento == "NU") {
                $valor_cash = $request->total_a_pagar;
                $valor_multicaixa = 0;
            } else if ($forma_pagamento->sigla_tipo_pagamento == "MB") {
                $valor_cash = 0;
                $valor_multicaixa = $request->total_a_pagar;
            } else if ($forma_pagamento->sigla_tipo_pagamento == "OU") {
                $valor_cash = $request->valor_entregue;
                $valor_multicaixa = $request->valor_entregue_multicaixa;
            } else if ($forma_pagamento->sigla_tipo_pagamento == "TT") {
                $valor_cash = 0;
                $valor_multicaixa = $request->valor_entregue;
            } else if ($forma_pagamento->sigla_tipo_pagamento == "DD") {
                $valor_cash = 0;
                $valor_multicaixa = $request->valor_entregue;
            }

            $timestemps = DetalhesPagamentoPropina::where('pagamentos_id', $pagamento->id)->get();

            $createRecibo = PagamentoRecibo::create([
                "pago_at" => $pagamento->pago_at,
                "quantidade" => $pagamento->quantidade,
                'tipo_servico_detalhe' => $pagamento->tipo_servico_detalhe,
                "servicos_id" => $pagamento->servicos_id,
                "status" =>  "Confirmado",
                "caixa_at" => $pagamento->caixa_at,
                "tipo_pagamento" => $request->forma_pagamento,
                'pagamento_id' => $forma_pagamento->id,
                "valor_entregue" => $request->valor_entregue ?? 0 + $request->valor_entregue_multicaixa ?? 0,
                "troco" => $valor_total_entregue - $request->total_a_pagar,
                'codigo_pagamento' => $pagamento->id,
                "ficha" => $pagamento->ficha,
                "valor" => $pagamento->valor,
                "valor2" => $pagamento->valor2,
                "desconto" => $pagamento->desconto,
                "multa" => $pagamento->multa,
                "banco" => $pagamento->banco,
                "valor_extenso" => $valor_extenso,
                "estudantes_id" => $pagamento->estudantes_id,
                "numero_transacao" => $pagamento->numero_transicao,
                "data_at" => $this->data_sistema(),
                "mensal" => $pagamento->mensal,
                "funcionarios_id" => Auth::user()->id,
                'convertido_factura' => "Y",
                'next_factura' => "{$request->tipo_factura} {$codigo_designacao_factura}{$ano_lectivo_activo->serie}/{$numeroFactura}",
                "numero_factura" => $numeroFactura,
                "tipo_factura" => $request->tipo_factura,
                "shcools_id" => $this->escolarLogada(),
                "model" => $pagamento->model,
                "ano_lectivos_id" => $this->anolectivoActivo(),
                'data_vencimento' => $pagamento->data_vencimento,
                'data_disponibilizacao' => $pagamento->data_vencimento,
                "numeracao_proforma" => $pagamento->next_factura,
                'valor_cash' => $valor_cash,
                'valor_multicaixa' => $valor_multicaixa,
                'referencia' => $pagamento->referencia,
                'conta_corrente_cliente' => $pagamento->conta_corrente_cliente,
                'texto_hash' => $plaintext,
                'hash' => base64_encode($signaturePlaintext),
                'nif_cliente' => $pagamento->nif_cliente,
                'total_incidencia' => $pagamento->total_incidencia,
                'total_iva' => $pagamento->total_iva,
            ]);

            foreach ($timestemps as $item) {
                DetalhesPagamentoRecibo::create([
                    "multa" => $item->multa,
                    "total_pagar" => $item->total_pagar,
                    "mes_id" => "NULL",
                    "pagamentos_id" => $createRecibo->id,
                    "desconto" => $item->desconto,
                    "desconto_valor" => $item->desconto_valor,
                    "valor_incidencia" => $item->valor_incidencia,
                    "valor_iva" => $item->valor_iva,
                    "taxa_id" => $item->taxa_id,
                    "preco" => $item->preco,
                    "mes" => $item->mes,
                    "model_id" => $item->model_id,
                    "quantidade" => $item->quantidade,
                    "funcionarios_id" => Auth::user()->id,
                    "status" => 'Pago',
                    "servicos_id" => $item->servicos_id,
                    "date_att" => $this->data_sistema(),
                    "ano_lectivos_id" => $this->anolectivoActivo(),
                    "shcools_id" => $this->escolarLogada(),
                    "code" => $pagamento->ficha,
                ]);
            }

            foreach ($timestemps as $item) {
                $update = DetalhesPagamentoPropina::findOrfail($item->id);
                $update->status = "Pago";
                $update->update();
            }

            foreach ($timestemps as $ficha) {
                $cartao_estudantil = CartaoEstudante::where('month_name', '=', $ficha->mes)
                    ->where('estudantes_id', '=', $ficha->model_id)
                    ->where('ano_lectivos_id', '=', $this->anolectivoActivo())
                ->get();

                if ($cartao_estudantil) {
                    if ($cartao_estudantil) {
                        foreach ($cartao_estudantil as $cartao) {
                            $upd = CartaoEstudante::findOrFail($cartao->id);
                            if ($cartao->mes_id == "M") {
                                $upd->status = "Pago";
                            } else {
                                $upd->status = "Nao Pago";
                            }
                            $upd->update();
                        }
                    }
                }
            }

            $pagamento->status = "Confirmado";
            $pagamento->convertido_factura  = 'Y';
            $pagamento->factura_divida  = 'N';
            $pagamento->numeracao_proforma = $createRecibo->next_factura;

            $pagamento->update();

            $updateCaixaAberto = MovimentoCaixa::findOrFail($caixaAberto);

            if ($forma_pagamento->sigla_tipo_pagamento == "NU") {
                $updateCaixaAberto->valor_cache = $updateCaixaAberto->valor_cache +  $request->valor_a_pagar;
            }

            if ($forma_pagamento->sigla_tipo_pagamento == "MB") {
                $updateCaixaAberto->valor_tpa = $updateCaixaAberto->valor_tpa +  $request->valor_a_pagar;
            }

            if ($forma_pagamento->sigla_tipo_pagamento == "OU") {
                $updateCaixaAberto->valor_cache = $request->valor_entregue;
                $updateCaixaAberto->valor_tpa = $request->valor_entregue_multicaixa;
            }

            if ($forma_pagamento->sigla_tipo_pagamento == "TT") {
                $updateCaixaAberto->valor_transferencia = $request->valor_entregue;
            }

            if ($forma_pagamento->sigla_tipo_pagamento == "DD") {
                $updateCaixaAberto->valor_depositado = $request->valor_entregue;
            }

            $updateCaixaAberto->valor_fecha = $updateCaixaAberto->valor_fecha + $request->valor_a_pagar;
            $updateCaixaAberto->qtd_itens = $updateCaixaAberto->qtd_itens + $pagamento->quantidade;
            $updateCaixaAberto->update();

            // Comita a transação se tudo estiver correto
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            Alert::error('Error', $e->getMessage());
            return redirect()->back();
        }


        Alert::success("Bom Trabalho", "Factura Convertida com successo");
        return redirect()->route("comprovativo-factura-recibo-recibo", $createRecibo->ficha);
    }

    public function documentoCancelarFacturas($ficha)
    {
        $user = auth()->user();

        if (!$user->can('read: factura') && !$user->can('update: factura')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $pagamento = Pagamento::where("ficha", $ficha)
            ->with(["estudante", "operador", "items", "detalhes", "forma_pagamento"])
        ->first();
                
        if ($pagamento->anulado == "Y") {
            Alert::warning('Atenção', "Esta factura já foi anulada, não é possível anular novamente!");
            return redirect()->back();
        }

        $headers = [
            "escola" => Shcool::with(["ensino"])->findOrFail($this->escolarLogada()),
            "titulo" => "Anular Factura",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            "pagamento" => $pagamento,
        ];

        return view('admin.documentos.cancelar-facturas', $headers);
    }

    public function documentoCancelarFacturasCreate(Request $request)
    {
        $request->validate(['motivo' => 'required']);

        $user = auth()->user();

        if (!$user->can('read: factura') && !$user->can('update: factura')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $ano_lectivo_activo = AnoLectivo::findOrFail($this->anolectivoActivo());
        $codigo_designacao_factura = "EAV";

        $pagamento = Pagamento::where('ficha', $request->ficha_factura)
            // ->where('status', '!=', 'Nota Credito')
            ->first();

        if (!$pagamento) {
            Alert::warning('Atenção', "Esta factura não existe, provalvemente já foi anulada entra em contacto com o administrador do sistema...");
            return redirect()->back();
        }

        try {
            // Inicia a transação
            DB::beginTransaction();
         
            $contarFactura = PagamentoNotaCredito::where('tipo_factura', 'NC')
                ->where('factura_ano', $ano_lectivo_activo->serie ?? date("Y"))
                ->where('shcools_id', $this->escolarLogada())
            ->count();

            $ultimoRecibo = PagamentoNotaCredito::where('tipo_factura', 'NC')
                ->where('factura_ano', $ano_lectivo_activo->serie ?? date("Y"))
                ->where('shcools_id', $this->escolarLogada())
                ->orderBy('id', 'DESC')
                ->limit(1)
            ->first();

            if (!$ultimoRecibo) {
                $hashAnterior = "";
            } else {
                $hashAnterior = $ultimoRecibo->hash;
            }

            //Manipulação de datas: data actual
            $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

            $ano = date("Y");
            $numeroFactura = $contarFactura + 1;

            if (!$ultimoRecibo) {
                $hashAnterior = "";
            } else {
                $hashAnterior = $ultimoRecibo->hash;
            }

            //Manipulação de datas: data actual
            $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));


            $ano = date("Y");
            $numeroFactura = $contarFactura + 1;

            $rsa = new RSA(); //Algoritimo RSA

            $privatekey = $this->pegarChavePrivada();
            $publickey = $this->pegarChavePublica();

            // Lendo a private key
            $rsa->loadKey($privatekey);

            /**
             * Texto que deverá ser assinado com a assinatura RSA::SIGNATURE_PKCS1, e o Texto estará mais ou menos assim após as
             * Concatenações com os dados preliminares da factura: 2020-09-14;2020-09-14T20:34:09;FP PAT2020/1;457411.2238438; */

            $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ';' . "NC {$codigo_designacao_factura}{$ano_lectivo_activo->serie}/{$numeroFactura}" . ';' . number_format(($pagamento->valor * $pagamento->quantidade) + $pagamento->multa, 2, ".", "") . ';' . $hashAnterior;
            // HASH
            $hash = 'sha1'; // Tipo de Hash
            $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima

            //ASSINATURA
            $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
            $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)

            // Lendo a public key
            $rsa->loadKey($publickey);
            
            $createNotaCredito = PagamentoNotaCredito::create([
                'pago_at' => $pagamento->pago_at,
                'servicos_id' => $pagamento->servicos_id,
                'quantidade' => $pagamento->quantidade,
                'status' => "Nota Credito",
                'caixa_at' => $pagamento->caixa_at,
                'ficha' => $pagamento->ficha,
                'valor' => $pagamento->valor,
                'troco' => $pagamento->troco,
                'valor_entregue' => $pagamento->valor_entregue,
                'valor2' => $pagamento->valor2,
                'desconto' => $pagamento->desconto,
                'multa' => $pagamento->multa,
                'inss' => $pagamento->inss,
                'irt' => $pagamento->irt,
                'faltas' => $pagamento->faltas,
                'subcidio' => $pagamento->subcidio,
                'subcidio_transporte' => $pagamento->subcidio_transporte,
                'subcidio_alimentacao' => $pagamento->subcidio_alimentacao,
                'subcidio_natal' => $pagamento->subcidio_natal,
                'subcidio_ferias' => $pagamento->subcidio_ferias,
                'subcidio_abono_familiar' => $pagamento->subcidio_abono_familiar,
                'banco' => $pagamento->banco,
                'numero_transacao' => $pagamento->numero_transacao,
                "tipo_pagamento" => "NOTA CREDITO",
                'pagamento_id' => 7,
                'model' => $pagamento->model,
                'data_at' => $pagamento->data_at,
                'data_vencimento' => $pagamento->data_vencimento,
                'data_disponibilizacao' => $pagamento->data_disponibilizacao,
                'mensal' => $pagamento->mensal,
                'next_factura' => "NC {$codigo_designacao_factura}{$ano_lectivo_activo->serie}/{$numeroFactura}",
                'factura_ano' => $pagamento->factura_ano,
                'numero_factura' => $numeroFactura,
                'tipo_factura' => "NC",
                'codigo' => $pagamento->codigo,
                'codigo_pagamento' => $pagamento->id,
                'estudantes_id' => $pagamento->estudantes_id,
                'referencia' => $pagamento->referencia,
                'total_iva' => $pagamento->total_iva,
                'retificado' => $pagamento->retificado,
                'convertido_factura' => $pagamento->convertido_factura,
                'factura_divida' => $pagamento->factura_divida,
                "anulado" => "Y",
                'moeda' => $pagamento->moeda,
                'prazo' => $pagamento->prazo,
                'valor_cash' => $pagamento->valor_cash,
                'valor_multicaixa' => $pagamento->valor_multicaixa,
                'valor_extenso' => $pagamento->valor_extenso,
                'texto_hash' => $plaintext,
                'hash' => base64_encode($signaturePlaintext),
                'nif_cliente' => $pagamento->nif_cliente,
                'total_incidencia' => $pagamento->total_incidencia,
                'conta_corrente_cliente' => $pagamento->conta_corrente_cliente,
                'numeracao_proforma' => $pagamento->next_factura,
                'motivo' => $request->motivo,
                'funcionarios_id' => Auth::user()->id,
                'ano_lectivos_id' => $this->anolectivoActivo(),
                'shcools_id' => $this->escolarLogada(),
            ]);

            $detalhePagamento = DetalhesPagamentoPropina::where('pagamentos_id',  $pagamento->id)->get();

            if ($detalhePagamento) {
                foreach ($detalhePagamento as $item) {
                    DetalhesPagamentoNotaCredito::create([
                        'status' => "Nota Credito",
                        'code' => $item->code,
                        'mes_id' => $item->mes_id,
                        'mes' => $item->mes,
                        'quantidade' => $item->quantidade,
                        'model' => $item->model,
                        'model_id' => $item->model_id,
                        'preco' => $item->preco,
                        'valor_iva' => $item->valor_iva,
                        'valor_incidencia' => $item->valor_incidencia,
                        'pagamentos_id' => $createNotaCredito->id,
                        'total_pagar' => $item->total_pagar,
                        'desconto' => $item->desconto,
                        'desconto_valor' => $item->desconto_valor,
                        'taxa_id' => $item->taxa_id,
                        'multa' => $item->multa,
                        'date_att' => $this->data_sistema(),
                        'servicos_id' => $item->servicos_id,
                        'funcionarios_id' => Auth::user()->id,
                        'ano_lectivos_id' => $this->anolectivoActivo(),
                        'shcools_id' => $this->escolarLogada(),
                    ]);
                }
            }

            if ($detalhePagamento) {
                foreach ($detalhePagamento as $item) {
                    $cartao_estudantil = CartaoEstudante::where('month_name', $item->mes)
                        ->where('estudantes_id', $item->estudantes_id)
                        ->where('servicos_id', $item->servicos_id)
                        ->where('ano_lectivos_id', $this->anolectivoActivo())
                    ->get();

                    if ($cartao_estudantil) {
                        foreach ($cartao_estudantil as $carta) {
                            $upd = CartaoEstudante::findOrFail($carta->id);
                            if ($upd->multa > 0) {
                                $status = 'divida';
                            } else {
                                $status = 'Nao Pago';
                            }
                            $upd->status = $status;
                            $upd->update();
                        }
                    }
                }
            }

            $pagamento->numeracao_proforma = $createNotaCredito->next_factura;
            $pagamento->anulado = "Y";
            $pagamento->status = "Nota Credito";
            $pagamento->save();

            // Comita a transação se tudo estiver correto
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            Alert::error('Error', $e->getMessage());
            return redirect()->back();
        }

        Alert::success("Bom Trabalho", "Factura Convertida com successo");
        return redirect()->route("comprovativo-factura-nota-credito", [$createNotaCredito->ficha, "ORGINAL"]);
    }

    public function liquidarFacturasIndex($ficha)
    {
        $user = auth()->user();

        if (!$user->can('read: factura') && !$user->can('update: factura')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $pagamento = Pagamento::where([
            ['ficha', '=', $ficha],
        ])
            ->join('tb_servicos', 'tb_pagamentos.servicos_id', '=', 'tb_servicos.id')
            ->select('tb_pagamentos.servicos_id', 'tb_servicos.servico', 'tb_pagamentos.status', 'tb_pagamentos.next_factura', 'tb_pagamentos.ficha', 'tb_pagamentos.tipo_factura', 'tb_pagamentos.quantidade', 'tb_pagamentos.data_at', 'tb_pagamentos.funcionarios_id', 'tb_pagamentos.multa', 'tb_pagamentos.desconto', 'tb_pagamentos.model', 'tb_pagamentos.estudantes_id', 'tb_pagamentos.valor', 'tb_pagamentos.id')
            ->first();

        $detalhe = DetalhesPagamentoPropina::where([
            ['code', '=', $pagamento->ficha],
        ])
            ->get();

        $estudantes = Estudante::find($pagamento->estudantes_id);

        $turma = EstudantesTurma::where([
            ['estudantes_id', '=', $estudantes->id],
            ['status', '=', 'activo'],
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
        ])
            ->select('tb_turmas_estudantes.turmas_id')
            ->first();

        $servico = Servico::findOrFail($pagamento->servicos_id);




        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            "servicos" => $servico,
            "turma" => $turma,
            "detalhes" => $detalhe,
            "pagamento" => $pagamento,
            "estudantes" => $estudantes,
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.financeiros.liquidar-factura-create', $headers);
    }

    public function liquidarFacturasStore(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: factura') && !$user->can('update: factura')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $caixa = Caixa::where('status', '=', "activo")->where('shcools_id', '=', $this->escolarLogada())->where('usuario_id', '=', Auth::user()->id)->first();

        if (!$caixa) {
            Alert::warning('Informação', 'Não há nenhum caixa aberto no momento. Por favor, abra um caixa para continuar o processo.');
            return redirect()->back();
        }

        $caixaAberto = MovimentoCaixa::where([
            ['caixa_id', $caixa->id],
            ['usuario_id', Auth::user()->id],
            ['status', "aberto"],
        ])->first()->id;

        if (!$caixaAberto) {
            Alert::warning('Informação', 'Não há nenhum caixa aberto no momento. Por favor, abra um caixa para continuar o processo.');
            return redirect()->back();
        }

        $request->validate([
            'total_a_pagar' => 'required',
            'valor_entregue' => 'required',
            'tipo_pagamento' => 'required',
            'servico' => 'required',
            'ficha_factura' => 'required',
        ], [
            "total_a_pagar.required" => "******",
            "valor_entregue.required" => "******",
            "tipo_pagamento.required" => "******",
            "servico.required" => "******",
            "ficha_factura.required" => "******",
        ]);

        try {
            // Inicia a transação
            DB::beginTransaction();


            if ((!filter_var($request->total_a_pagar, FILTER_VALIDATE_FLOAT) and !filter_var($request->total_a_pagar, FILTER_VALIDATE_INT)) and (!filter_var($request->valor_entregue, FILTER_VALIDATE_FLOAT) and !filter_var($request->valor_entregue, FILTER_VALIDATE_INT))) {
                Alert::warning('Informação', 'Os Valores não podem ser Letras por favor!');
                return redirect()->back();
            }

            if ($request->valor_entregue < $request->total_a_pagar) {
                Alert::warning('Informação', '"O NUMERARIO Entregue para o pagamento deste serviço é insuficiente!');
                return redirect()->back();
            }


            $pagamento = Pagamento::where('ficha', $request->ficha_factura)->where('status', 'Pendente')->first();

            if (!$pagamento) {
                Alert::warning('Atenção', "Esta factura não existe, Provalmente já foi emitado um recibo entra em contacto com o administrador do sistema...");
                return redirect()->back();
            }

            $forma_pagamento = FormaPagamento::where('sigla_tipo_pagamento', $request->tipo_pagamento)->first();

            $contarFactura = PagamentoRecibo::where('tipo_factura', 'RG')->where('factura_ano', date("Y"))->where('shcools_id', $this->escolarLogada())->count();

            $ultimoRecibo = PagamentoRecibo::where('tipo_factura', 'RG')->where('factura_ano', date("Y"))->where('shcools_id', $this->escolarLogada())->orderBy('id', 'DESC')->limit(1)->first();


            if (!$ultimoRecibo) {
                $hashAnterior = "";
            } else {
                $hashAnterior = $ultimoRecibo->hash;
            }

            //Manipulação de datas: data actual
            $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

            // $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

            $ano = date("Y");
            $numeroFactura = $contarFactura + 1;

            if (!$ultimoRecibo) {
                $hashAnterior = "";
            } else {
                $hashAnterior = $ultimoRecibo->hash;
            }

            //Manipulação de datas: data actual
            $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));


            $ano = date("Y");
            $numeroFactura = $contarFactura + 1;

            $rsa = new RSA(); //Algoritimo RSA

            $privatekey = $this->pegarChavePrivada();
            $publickey = $this->pegarChavePublica();

            // Lendo a private key
            $rsa->loadKey($privatekey);

            /**
             * Texto que deverá ser assinado com a assinatura RSA::SIGNATURE_PKCS1, e o Texto estará mais ou menos assim após as
             * Concatenações com os dados preliminares da factura: 2020-09-14;2020-09-14T20:34:09;FP PAT2020/1;457411.2238438; */

            $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ';' . "RG AGT{$ano}/{$numeroFactura}" . ';' . number_format(($request->total_a_pagar), 2, ".", "") . ';' . $hashAnterior;
            // HASH
            $hash = 'sha1'; // Tipo de Hash
            $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima

            //ASSINATURA
            $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
            $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)

            // Lendo a public key
            $rsa->loadKey($publickey);

            $valor_extenso = $this->valor_por_extenso($request->total_a_pagar);

            if ($forma_pagamento->sigla_tipo_pagamento == "NU") {
                $valor_cash = $request->total_a_pagar;
                $valor_multicaixa = 0;
            } else if ($forma_pagamento->sigla_tipo_pagamento  == "MB") {
                $valor_cash = 0;
                $valor_multicaixa = $request->total_a_pagar;
            }


            $timestemps = DetalhesPagamentoPropina::where('pagamentos_id', $pagamento->id)->get();


            $createRecibo = PagamentoRecibo::create([
                "pago_at" => $pagamento->pago_at,
                "quantidade" => $pagamento->quantidade,
                'tipo_servico_detalhe' => $pagamento->tipo_servico_detalhe,
                "servicos_id" => $pagamento->servicos_id,
                "status" =>  "Confirmado",
                "caixa_at" => $pagamento->caixa_at,
                "tipo_pagamento" => $forma_pagamento->descricao,
                'pagamento_id' => $forma_pagamento->id,
                "valor_entregue" => $request->valor_entregue,

                "troco" => $request->valor_entregue - (($pagamento->valor * $pagamento->quantidade) + $pagamento->multa),
                'codigo_pagamento' => $pagamento->id,
                "ficha" => $pagamento->ficha,
                "valor" => $pagamento->valor,
                "valor2" => $pagamento->valor2,
                "desconto" => $pagamento->desconto,
                "multa" => $pagamento->multa,
                "banco" => $pagamento->banco,
                "valor_extenso" => $valor_extenso,
                "estudantes_id" => $pagamento->estudantes_id,
                "numero_transacao" => $pagamento->numero_transicao,
                "data_at" => $this->data_sistema(),
                "mensal" => $pagamento->mensal,
                "funcionarios_id" => Auth::user()->id,

                'convertido_factura' => "Y",

                'next_factura' => "RG AGT{$ano}/{$numeroFactura}",
                "numero_factura" => $numeroFactura,
                "tipo_factura" => "RG",

                "shcools_id" => $this->escolarLogada(),
                "model" => $pagamento->model,
                "ano_lectivos_id" => $this->anolectivoActivo(),
                'data_vencimento' => $pagamento->data_vencimento,
                'data_disponibilizacao' => $pagamento->data_vencimento,
                "numeracao_proforma" => $pagamento->next_factura,

                'valor_cash' => $valor_cash,
                'valor_multicaixa' => $valor_multicaixa,

                'referencia' => $pagamento->referencia,
                'conta_corrente_cliente' => $pagamento->conta_corrente_cliente,

                'texto_hash' => $plaintext,
                'hash' => base64_encode($signaturePlaintext),
                'nif_cliente' => $pagamento->nif_cliente,
                'total_incidencia' => $pagamento->total_incidencia,
                'total_iva' => $pagamento->total_iva,
            ]);

            foreach ($timestemps as $item) {

                DetalhesPagamentoRecibo::create([
                    'status' => 'Pago',
                    'code' => $pagamento->ficha,
                    'mes_id' => NULL,
                    'mes' => $item->mes,
                    'quantidade' => $item->quantidade,
                    'model' => $item->model,
                    'model_id' => $item->model_id,
                    'preco' => $item->preco,
                    'valor_iva' => $item->valor_iva,
                    'valor_incidencia' => $item->valor_incidencia,
                    'pagamentos_id' => $createRecibo->id,
                    'total_pagar' => $item->total_pagar,
                    'desconto' => $item->desconto,
                    'desconto_valor' => $item->desconto_valor,
                    'taxa_id' => $item->taxa_id,
                    'multa' => $item->multa,
                    'date_att' => $item->date_att,
                    'servicos_id' => $item->servicos_id,
                    'funcionarios_id' => Auth::user()->id,
                    'ano_lectivos_id' => $this->anolectivoActivo(),
                    'shcools_id' => $this->escolarLogada(),
                ]);
            }

            foreach ($timestemps as $item) {
                $update = DetalhesPagamentoPropina::findOrfail($item->id);
                $update->status = "Pago";
                $update->update();
            }

            foreach ($timestemps as $ficha) {
                $cartao_estudantil = CartaoEstudante::where([
                    ['month_name', '=', $ficha->mes],
                    ['estudantes_id', '=', $ficha->model_id],
                    ['servicos_id', '=', $ficha->servicos_id],
                    ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ])->get();

                if ($cartao_estudantil) {
                    if ($cartao_estudantil) {
                        foreach ($cartao_estudantil as $cartao) {
                            $upd = CartaoEstudante::findOrFail($cartao->id);
                            $upd->status = "Pago";
                            $upd->update();
                        }
                    }
                }
            }


            $pagamento->status = "Confirmado";
            $pagamento->convertido_factura  = 'Y';
            $pagamento->factura_divida  = 'N';
            $pagamento->numeracao_proforma = $createRecibo->next_factura;

            $pagamento->update();

            $updateCaixaAberto = MovimentoCaixa::findOrFail($caixaAberto);

            if ($forma_pagamento->descricao == "NU") {
                $updateCaixaAberto->valor_cache = $updateCaixaAberto->valor_cache + $request->valor_a_pagar;
            }

            if ($forma_pagamento->descricao == "MB") {
                $updateCaixaAberto->valor_tpa = $updateCaixaAberto->valor_tpa + $request->valor_a_pagar;
            }

            $updateCaixaAberto->valor_fecha = $updateCaixaAberto->valor_fecha + $request->valor;
            $updateCaixaAberto->qtd_itens = $updateCaixaAberto->qtd_itens + $pagamento->quantidade;
            $updateCaixaAberto->update();

            // Comita a transação se tudo estiver correto
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            Alert::error('Error', $e->getMessage());
            return redirect()->back();
        }



        Alert::success("Bom Trabalho", "Factura Liquidada com successo");
        return redirect()->route("comprovativo-factura-recibo-recibo", $createRecibo->ficha);
    }

    /**
     * impressoes das facturas
     */
    public function ComprovativoFacturaProforma($factura, $opcao = "SEGUNDA VIA")
    {

        $pagamento = Pagamento::where([
            ['ficha', '=', $factura],
            ['model', '=', 'estudante'],
        ])
        ->join('tb_servicos', 'tb_pagamentos.servicos_id', '=', 'tb_servicos.id')
        ->first();

        if (!$pagamento) {
            Alert::error('Informação', 'Documento não seguro, verifica os dados do estudantes desta factura!');
            return redirect()->back();
        }


        $matricula = Matricula::where([
            ['estudantes_id', '=', $pagamento->estudantes_id],
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['status_matricula', '=', 'confirmado'],
        ])->first();

        $estudante = Estudante::find($pagamento->estudantes_id);

        $items = DetalhesPagamentoPropina::where([
            ['code', $pagamento->ficha],
        ])
        ->with(['servico'])
        ->get();


        $total_incidencia_ise = 0;
        $total_iva_ise = 0;

        $total_incidencia_nor = 0;
        $total_iva_nor = 0;

        $total_incidencia_out = 0;
        $total_iva_out = 0;

        foreach ($items as $item) {

            $servico = Servico::join('tb_taxas', 'tb_servicos.taxa_id', '=', 'tb_taxas.id')->select('tb_servicos.id', 'sigla', 'taxa', 'contas', 'tb_servicos.servico')->findOrFail($item->servicos_id);

            if ($servico->sigla == 'NOR') {
                $total_incidencia_nor = $total_incidencia_nor + $item->valor_incidencia;
                $total_iva_nor = $total_iva_nor + $item->valor_iva;
            }

            if ($servico->sigla == 'ISE') {
                $total_incidencia_ise = $total_incidencia_ise + $item->valor_incidencia;
                $total_iva_ise = $total_iva_ise + $item->valor_iva;
            }

            if ($servico->sigla == 'RED') {
                $total_incidencia_out = $total_incidencia_out + $item->valor_incidencia;
                $total_iva_out = $total_iva_out + $item->valor_iva;
            }
        }


        $curso = Curso::findOrFail($matricula->cursos_id);
        $classe = Classe::findOrFail($matricula->classes_id);
        $turno = Turno::findOrFail($matricula->turnos_id);

        $turma = Turma::where([
            ['cursos_id', '=', $curso->id],
            ['classes_id', '=', $classe->id],
            ['turnos_id', '=', $turno->id],
        ])->first();

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            
            'pagamento' => $pagamento,
            'matricula' => $matricula,
            'estudante' => $estudante,
            'detalhes' => $items,
            'curso' => $curso,
            'classe' => $classe,

            "total_incidencia_nor" => $total_incidencia_nor,
            "total_iva_nor" => $total_iva_nor,

            "total_incidencia_ise" => $total_incidencia_ise,
            "total_iva_ise" => $total_iva_ise,

            "total_incidencia_out" => $total_incidencia_out,
            "total_iva_out" => $total_iva_out,

            'opcao' => $opcao,
            'turno' => $turno,
            'turma' => $turma,
            'sala' => Sala::findOrFail($turma->salas_id),
            'funcionario' => User::findOrFail($pagamento->funcionarios_id),
            'funcionarioAtendente' => User::findOrFail($pagamento->funcionarios_id),
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada())
        ];

        $pdf = \PDF::loadView('admin.documentos.factura-proforma', $headers)->setPaper('A4', 'portrait');
        return $pdf->stream('factura-proforma.pdf');
    }

    public function ComprovativoFacturaFactura($factura, $opcao = "SEGUNDA VIA")
    {
        $user = auth()->user();

        $pagamento = Pagamento::where([
            ['ficha', '=', $factura],
            ['model', '=', 'estudante'],
        ])
            ->join('tb_servicos', 'tb_pagamentos.servicos_id', '=', 'tb_servicos.id')
            ->first();


        if (!$pagamento) {
            Alert::error('Informação', 'Documento não seguro, verifica os dados do estudantes desta factura!');
            return redirect()->back();
        }


        $matricula = Matricula::where('estudantes_id', $pagamento->estudantes_id)
            ->where('status_matricula', 'confirmado')
            ->first();

        $estudante = Estudante::find($pagamento->estudantes_id);

        $items = DetalhesPagamentoPropina::where('code', $pagamento->ficha)
            ->with(['servico'])
            ->get();

        $total_incidencia_ise = 0;
        $total_retencao_ise = 0;
        $total_iva_ise = 0;

        $total_incidencia_nor = 0;
        $total_retencao_nor = 0;
        $total_iva_nor = 0;

        $total_incidencia_out = 0;
        $total_retencao_out = 0;
        $total_iva_out = 0;

        foreach ($items as $item) {

            $servico = Servico::join('tb_taxas', 'tb_servicos.taxa_id', '=', 'tb_taxas.id')->select('tb_servicos.id', 'sigla', 'taxa', 'contas', 'tb_servicos.servico', 'tipo')->findOrFail($item->servicos_id);


            if ($servico->sigla == 'NOR') {
                if ($item->preco > 20000) {
                    if ($servico->tipo == 'S') {
                        $total_retencao_nor = $total_retencao_nor + ($item->valor_incidencia * (6.5 / 100));
                    }
                }
                $total_incidencia_nor = $total_incidencia_nor + $item->valor_incidencia;
                $total_iva_nor = $total_iva_nor + $item->valor_iva;
            }

            if ($servico->sigla == 'ISE') {
                if ($item->preco > 20000) {
                    if ($servico->tipo == 'S') {
                        $total_retencao_ise = $total_retencao_ise + ($item->valor_incidencia * (6.5 / 100));
                    }
                }
                $total_incidencia_ise = $total_incidencia_ise + $item->valor_incidencia;
                $total_iva_ise = $total_iva_ise + $item->valor_iva;
            }

            if ($servico->sigla == 'RED') {
                if ($item->preco > 20000) {
                    if ($servico->tipo == 'S') {
                        $total_retencao_out = $total_retencao_out + ($item->valor_base * (6.5 / 100));
                    }
                }

                $total_incidencia_out = $total_incidencia_out + $item->valor_incidencia;
                $total_iva_out = $total_iva_out + $item->valor_iva;
            }
        }

        $total_retencao = $total_retencao_ise + $total_retencao_out + $total_retencao_nor;

        $curso = Curso::findOrFail($matricula->cursos_id);
        $classe = Classe::findOrFail($matricula->classes_id);
        $turno = Turno::findOrFail($matricula->turnos_id);

        $turma = Turma::where('cursos_id', $curso->id)
            ->where('classes_id', $classe->id)
            ->where('turnos_id', $turno->id)
            ->first();

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            
            'pagamento' => $pagamento,
            'matricula' => $matricula,
            'estudante' => $estudante,
            'detalhes' => $items,
            'curso' => $curso,
            'classe' => $classe,

            "total_incidencia_nor" => $total_incidencia_nor,
            "total_iva_nor" => $total_iva_nor,
            "total_retencao_nor" => $total_retencao_nor,

            "total_incidencia_ise" => $total_incidencia_ise,
            "total_iva_ise" => $total_iva_ise,
            "total_retencao_ise" => $total_retencao_ise,

            "total_incidencia_out" => $total_incidencia_out,
            "total_iva_out" => $total_iva_out,
            "total_retencao_out" => $total_retencao_out,

            "total_retencao" => $total_retencao,

            'opcao' => $opcao,
            'turno' => $turno,
            'turma' => $turma,
            'sala' => Sala::findOrFail($turma->salas_id),
            'funcionario' => User::findOrFail($pagamento->funcionarios_id),
            'funcionarioAtendente' => User::findOrFail($pagamento->funcionarios_id),
        ];

        $pdf = \PDF::loadView('admin.documentos.factura-factura', $headers)->setPaper('A4', 'portrait');
        return $pdf->stream('factura-factura.pdf');
    }

    public function ComprovativoFacturaRecibo($factura, $opcao = "SEGUNDA VIA")
    {
        $pagamento = Pagamento::where('ficha', $factura)
            ->with(['servico', 'forma_pagamento', 'items'])
            ->first();
            
        if (!$pagamento) {
            Alert::error('Informação', 'Documento não seguro, verifica os dados do estudantes desta factura!');
            return redirect()->back();
        }

        $estudante = Estudante::find($pagamento->estudantes_id);

        $matricula = Matricula::where('estudantes_id', $pagamento->estudantes_id)
            ->latest()
        ->first();

        $items = DetalhesPagamentoPropina::where('code', $pagamento->ficha)
            ->with(['servico'])
            ->get();

        $total_incidencia_ise = 0;
        $total_retencao_ise = 0;
        $total_iva_ise = 0;

        $total_incidencia_nor = 0;
        $total_retencao_nor = 0;
        $total_iva_nor = 0;

        $total_incidencia_out = 0;
        $total_retencao_out = 0;
        $total_iva_out = 0;


        foreach ($items as $item) {

            $servico = Servico::join('tb_taxas', 'tb_servicos.taxa_id', '=', 'tb_taxas.id')
                ->select('tb_servicos.id', 'sigla', 'taxa', 'contas', 'tb_servicos.servico')
                ->findOrFail($item->servicos_id);

            if ($servico->sigla == 'NOR') {
                if ($item->preco > 20000) {
                    if ($servico->tipo == 'S') {
                        $total_retencao_nor = $total_retencao_nor + ($item->valor_incidencia * (6.5 / 100));
                    }
                }
                $total_incidencia_nor = $total_incidencia_nor + $item->valor_incidencia;
                $total_iva_nor = $total_iva_nor + $item->valor_iva;
            }

            if ($servico->sigla == 'ISE') {
                if ($item->preco > 20000) {
                    if ($servico->tipo == 'S') {
                        $total_retencao_ise = $total_retencao_ise + ($item->valor_incidencia * (6.5 / 100));
                    }
                }
                $total_incidencia_ise = $total_incidencia_ise + $item->valor_incidencia;
                $total_iva_ise = $total_iva_ise + $item->valor_iva;
            }

            if ($servico->sigla == 'RED') {
                if ($item->preco > 20000) {
                    if ($servico->tipo == 'S') {
                        $total_retencao_out = $total_retencao_out + ($item->valor_base * (6.5 / 100));
                    }
                }

                $total_incidencia_out = $total_incidencia_out + $item->valor_incidencia;
                $total_iva_out = $total_iva_out + $item->valor_iva;
            }
        }

        $total_retencao = $total_retencao_ise + $total_retencao_out + $total_retencao_nor;

        $curso = Curso::findOrFail($matricula->cursos_id);
        $classe = Classe::findOrFail($matricula->classes_id);
        $turno = Turno::findOrFail($matricula->turnos_id);


        $turma = Turma::where('cursos_id', $curso->id)
            ->where('classes_id', $classe->id)
            ->where('turnos_id', $turno->id)
        ->first();

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            
            'pagamento' => $pagamento,
            'matricula' => $matricula,
            'estudante' => $estudante,
            'detalhes' => $items,
            'curso' => $curso,
            'classe' => $classe,
            'turno' => $turno,
            'turma' => $turma,

            "total_incidencia_nor" => $total_incidencia_nor,
            "total_iva_nor" => $total_iva_nor,
            "total_retencao_nor" => $total_retencao_nor,

            "total_incidencia_ise" => $total_incidencia_ise,
            "total_iva_ise" => $total_iva_ise,
            "total_retencao_ise" => $total_retencao_ise,

            "total_incidencia_out" => $total_incidencia_out,
            "total_iva_out" => $total_iva_out,
            "total_retencao_out" => $total_retencao_out,

            "total_retencao" => $total_retencao,
            "opcao" => $opcao,

            'funcionario' => User::find($pagamento->funcionarios_id),
            'funcionarioAtendente' => User::find($pagamento->funcionarios_id),
        ];


        $pdf = \PDF::loadView('admin.documentos.factura-recibo', $headers)->setPaper('A4', 'portrait');
        return $pdf->stream('factura-recibo.pdf');
    }

    public function ComprovativoFacturaReciboRecibo($factura)
    {
        $user = auth()->user();

        $pagamento = PagamentoRecibo::where('ficha', $factura)
            ->with(['servico'])
            ->first();

        if (!$pagamento) {
            Alert::error('Informação', 'Documento não seguro, verifica os dados do estudantes desta factura!');
            return redirect()->back();
        }


        $estudante = Estudante::find($pagamento->estudantes_id);

        $matricula = Matricula::where('estudantes_id', $pagamento->estudantes_id)
            // ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('status_matricula', 'confirmado')
            ->latest()
            ->first();

        $detalhe = DetalhesPagamentoRecibo::where('code', $pagamento->ficha)
            ->with(['servico'])
            ->get();

        $total_incidencia_ise = 0;
        $total_retencao_ise = 0;
        $total_iva_ise = 0;

        $total_incidencia_nor = 0;
        $total_retencao_nor = 0;
        $total_iva_nor = 0;

        $total_incidencia_out = 0;
        $total_retencao_out = 0;
        $total_iva_out = 0;


        foreach ($detalhe as $item) {

            $servico = Servico::join('tb_taxas', 'tb_servicos.taxa_id', '=', 'tb_taxas.id')
                ->select('tb_servicos.id', 'sigla', 'taxa', 'contas', 'tb_servicos.servico')
                ->findOrFail($item->servicos_id);

            if ($servico->sigla == 'NOR') {
                if ($item->preco > 20000) {
                    if ($servico->tipo == 'S') {
                        $total_retencao_nor = $total_retencao_nor + ($item->valor_incidencia * (6.5 / 100));
                    }
                }
                $total_incidencia_nor = $total_incidencia_nor + $item->valor_incidencia;
                $total_iva_nor = $total_iva_nor + $item->valor_iva;
            }

            if ($servico->sigla == 'ISE') {
                if ($item->preco > 20000) {
                    if ($servico->tipo == 'S') {
                        $total_retencao_ise = $total_retencao_ise + ($item->valor_incidencia * (6.5 / 100));
                    }
                }
                $total_incidencia_ise = $total_incidencia_ise + $item->valor_incidencia;
                $total_iva_ise = $total_iva_ise + $item->valor_iva;
            }

            if ($servico->sigla == 'RED') {
                if ($item->preco > 20000) {
                    if ($servico->tipo == 'S') {
                        $total_retencao_out = $total_retencao_out + ($item->valor_base * (6.5 / 100));
                    }
                }

                $total_incidencia_out = $total_incidencia_out + $item->valor_incidencia;
                $total_iva_out = $total_iva_out + $item->valor_iva;
            }
        }

        $total_retencao = $total_retencao_ise + $total_retencao_out + $total_retencao_nor;


        $curso = Curso::findOrFail($matricula->cursos_id);
        $classe = Classe::findOrFail($matricula->classes_id);
        $turno = Turno::findOrFail($matricula->turnos_id);

        $turma = Turma::where('cursos_id', $curso->id)
            ->where('classes_id', $classe->id)
            ->where('turnos_id', $turno->id)
            ->first();



        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            
            'pagamento' => $pagamento,
            'matricula' => $matricula,
            'estudante' => $estudante,
            'detalhes' => $detalhe,
            'curso' => $curso,
            'classe' => $classe,
            'total_retencao' => $total_retencao,

            "total_incidencia_nor" => $total_incidencia_nor,
            "total_iva_nor" => $total_iva_nor,

            "total_incidencia_ise" => $total_incidencia_ise,
            "total_iva_ise" => $total_iva_ise,

            "total_incidencia_out" => $total_incidencia_out,
            "total_iva_out" => $total_iva_out,

            'turno' => $turno,
            'turma' => $turma,
            'sala' => Sala::findOrFail($turma->salas_id),
            'funcionario' => User::findOrFail($pagamento->funcionarios_id),
            'funcionarioAtendente' => User::findOrFail($pagamento->funcionarios_id),
        ];

        $pdf = \PDF::loadView('admin.documentos.factura-recibo-recibo', $headers)->setPaper('A4', 'portrait');
        return $pdf->stream('factura-recibo-recibo.pdf');
    }

    public function ComprovativoFacturaNotaCredito($factura, $opcao = "SEGUNDA VIA")
    {
        $pagamento = PagamentoNotaCredito::where('ficha', $factura)
            ->with(['servico', 'operador'])
            ->first();

        if (!$pagamento) {
            Alert::error('Informação', 'Documento não seguro, verifica os dados do estudantes desta factura!');
            return redirect()->back();
        }

        $matricula = Matricula::where('estudantes_id', $pagamento->estudantes_id)
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('status_matricula', 'confirmado')
        ->first();

        $estudante = Estudante::find($pagamento->estudantes_id);

        $detalhe = DetalhesPagamentoNotaCredito::where('pagamentos_id', $pagamento->id)->get();

        $total_incidencia_ise = 0;
        $total_retencao_ise = 0;
        $total_iva_ise = 0;

        $total_incidencia_nor = 0;
        $total_retencao_nor = 0;
        $total_iva_nor = 0;

        $total_incidencia_out = 0;
        $total_retencao_out = 0;
        $total_iva_out = 0;

        foreach ($detalhe as $item) {

            $servico = Servico::join('tb_taxas', 'tb_servicos.taxa_id', '=', 'tb_taxas.id')
                ->select('tb_servicos.id', 'sigla', 'taxa', 'contas', 'tb_servicos.servico')
                ->findOrFail($item->servicos_id);

            if ($servico->sigla == 'NOR') {
                if ($item->preco > 20000) {
                    if ($servico->tipo == 'S') {
                        $total_retencao_nor = $total_retencao_nor + ($item->valor_incidencia * (6.5 / 100));
                    }
                }
                $total_incidencia_nor = $total_incidencia_nor + $item->valor_incidencia;
                $total_iva_nor = $total_iva_nor + $item->valor_iva;
            }

            if ($servico->sigla == 'ISE') {
                if ($item->preco > 20000) {
                    if ($servico->tipo == 'S') {
                        $total_retencao_ise = $total_retencao_ise + ($item->valor_incidencia * (6.5 / 100));
                    }
                }
                $total_incidencia_ise = $total_incidencia_ise + $item->valor_incidencia;
                $total_iva_ise = $total_iva_ise + $item->valor_iva;
            }

            if ($servico->sigla == 'RED') {
                if ($item->preco > 20000) {
                    if ($servico->tipo == 'S') {
                        $total_retencao_out = $total_retencao_out + ($item->valor_base * (6.5 / 100));
                    }
                }

                $total_incidencia_out = $total_incidencia_out + $item->valor_incidencia;
                $total_iva_out = $total_iva_out + $item->valor_iva;
            }
        }

        $total_retencao = $total_retencao_ise + $total_retencao_out + $total_retencao_nor;

        $curso = Curso::findOrFail($matricula->cursos_id);
        $classe = Classe::findOrFail($matricula->classes_id);
        $turno = Turno::findOrFail($matricula->turnos_id);

        $turma = Turma::where('cursos_id', $curso->id)
            ->where('classes_id', $classe->id)
            ->where('turnos_id', $turno->id)
        ->first();

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            'pagamento' => $pagamento,
            'matricula' => $matricula,
            'estudante' => $estudante,
            'detalhes' => $detalhe,
            'curso' => $curso,
            'total_retencao' => $total_retencao,
            "total_incidencia_nor" => $total_incidencia_nor,
            "total_iva_nor" => $total_iva_nor,

            "total_incidencia_ise" => $total_incidencia_ise,
            "total_iva_ise" => $total_iva_ise,

            "total_incidencia_out" => $total_incidencia_out,
            "total_iva_out" => $total_iva_out,
            "opcao" => $opcao,
            'classe' => $classe,
            'turno' => $turno,
            'turma' => $turma,
            'sala' => Sala::findOrFail($turma->salas_id),
            'funcionario' => User::find($pagamento->funcionarios_id),
            'funcionarioAtendente' => User::find($pagamento->funcionarios_id),
        ];

        $pdf = \PDF::loadView('admin.documentos.factura-nota-credito', $headers)->setPaper('A4', 'portrait');
        return $pdf->stream('factura-recibo-recibo.pdf');
    }
}
