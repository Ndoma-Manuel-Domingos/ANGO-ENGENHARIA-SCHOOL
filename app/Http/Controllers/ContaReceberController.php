<?php

namespace App\Http\Controllers;

use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\calendarios\Servico;
use App\Models\web\financeiros\DetalhesPagamentoPropina;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\FormaPagamento;
use App\Models\Shcool;
use App\Models\web\calendarios\Pagamento;
use App\Models\web\estudantes\CartaoEstudante;
use App\Models\web\estudantes\Estudante;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ContaReceberController extends Controller
{
    use TraitChavesSaft;
    use TraitHelpers;


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function home()
    {
        $user = auth()->user();

        if (!$user->can('read: pagamento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
   
        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Contas a Receber",
            "servicos" => Servico::where('contas', 'receita')->where('shcools_id', $this->escolarLogada())->get(),
            "anos_lectivos" => AnoLectivo::where('shcools_id', $this->escolarLogada())->get(),
            "forma_pagamentos" => FormaPagamento::where('status_id', 1)->get(),
        ];

        return view('admin.financeiros.contas-receber.home', $headers);
    }

    public function index(Request $request)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '2048M'); // ou mais se necessário

        if (!$request->ano_lectivo) {
            $request->ano_lectivo = $this->anolectivoActivo();
        }

        $user = auth()->user();

        if (!$user->can('read: pagamento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $paginacao = $request->paginacao ?? 5;

        $query = DetalhesPagamentoPropina::when($request->designacao_geral, function ($query, $value) {
            $query->where(function ($q) use ($value) {
                // campo da própria tabela
                //$q->where('total_vagas', 'like', "%{$value}%")
        
                // campo da tabela classe
                $q->orWhereHas('pagamento', function ($qc) use ($value) {
                    $qc->where('next_factura', 'like', "%{$value}%");
                })
                ->orWhereHas('servico', function ($qc) use ($value) {
                    $qc->where('servico', 'like', "%{$value}%");
                })
                // campo da tabela ensino (classe->ensino)
                ->orWhereHas('pagamento.estudante', function ($qe) use ($value) {
                    $qe->where('nome', 'like', "%{$value}%");
                })
                // campo da tabela ensino (classe->ensino)
                ->orWhereHas('pagamento.estudante', function ($qe) use ($value) {
                    $qe->where('sobre_nome', 'like', "%{$value}%");
                });
            });
        })->with(["pagamento.estudante", "pagamento.operador", "servico"])
            ->when($request->ano_lectivo_id, function ($query, $value) {
                $query->where('ano_lectivos_id', $value);
            })
            ->whereHas('pagamento', function ($q) use ($request) {
                $q->where('caixa_at', 'receita')
                    ->where('status', 'Confirmado');

                $q->when($request->forma_pagamento_id, function ($query, $value) {
                    $query->where('pagamento_id', $value);
                });
            })
            ->when($request->servico_id, function ($query, $value) {
                $query->where('servicos_id', $value);
            })
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate('date_att', '>=', Carbon::createFromDate($value));
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('date_att', '<=', Carbon::createFromDate($value));
            })
            ->where('shcools_id', $this->escolarLogada());

        return response()->json(
            $query->orderByDesc('id')->paginate($paginacao)
        );
    }
   
    public function show($id)
    {
        return Pagamento::with(["operador", "ano", "estudante", "items.servico"])->findOrFail($id);
    }
    
    // pagamento de propina estudanets
    public function destroy($id)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário
        $user = auth()->user();

        if (!$user->can('delete: pagamento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $pagamento = Pagamento::with(['servico'])->findOrFail($id);

            if ($pagamento->model == "estudante") {
                $estudante = Estudante::findOrFail($pagamento->estudantes_id);
                $items = DetalhesPagamentoPropina::where('pagamentos_id', $pagamento->id)->get();

                foreach ($items as $item) {
                    $cartao_estudantil = CartaoEstudante::where('status', 'Pago')
                        ->where('estudantes_id', $estudante->id)
                        ->where('servicos_id', $item->servicos_id)
                        ->where('ano_lectivos_id', $pagamento->ano_lectivos_id)
                        ->first();

                    if ($cartao_estudantil) {
                        $cartao = CartaoEstudante::findOrFail($cartao_estudantil->id);
                        $cartao->status = "Nao Pago";
                        $cartao->update();
                    }

                    $detalhe = DetalhesPagamentoPropina::findOrFail($item->id);
                    $detalhe->delete();
                }
            }else {
                $items = DetalhesPagamentoPropina::where('pagamentos_id', $pagamento->id)->get();
                
                foreach ($items as $item) {
                    $detalhe = DetalhesPagamentoPropina::findOrFail($item->id);
                    $detalhe->delete();
                }
            }   

            $pagamento->delete();

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
            'message' => 'Dados Actualizados com sucesso!',
        ]);
    }

    public function export(Request $request)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // 4 GB

        if (!$request->ano_lectivo_id) {
            $request->ano_lectivo_id = $this->anolectivoActivo();
        }

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
        $pagamentos = DetalhesPagamentoPropina::with(["pagamento.operador", "servico"])
            ->when($request->ano_lectivo_id, function ($query, $value) {
                $query->where('ano_lectivos_id', $value);
            })
            ->whereHas('pagamento', function ($q) use ($request) {
                $q->where('caixa_at', 'receita')->where('status', 'Confirmado');
                $q->when($request->forma_pagamento_id, function ($query, $value) {
                    $query->where('pagamento_id', $value);
                });
                $q->when($request->caixa_id, function ($query, $value) {
                    $query->where('caixa_id', $value);
                })
                    ->when($request->user_id, function ($query, $value) {
                        $query->where('funcionarios_id', $value);
                    })
                    ->when($request->type, function ($query, $value) {
                        $query->where('caixa_at', $value);
                    });
            })
            ->when($request->servico_id, function ($query, $value) {
                $query->where('servicos_id', $value);
            })
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate('date_att', '>=', Carbon::createFromDate($value));
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('date_att', '<=', Carbon::createFromDate($value));
            })
            ->where('shcools_id', $escola->id)
        ->get();

        $titulo = "Pagamentos";

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);
        
        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "titulo" => $titulo,
            "verAnoLectivoActivo" => AnoLectivo::find($request->ano_lectivo_id),
            "pagamentos" => $pagamentos,
            "servico" => Servico::find($request->servico_id),
            "pagamentos" => $pagamentos,
            "requests" => $request->all('data_inicio', 'data_final', 'all'),
        ];
                
        $documentType = $request->documentType;
        
        if($documentType === 'excel') {
            //return Excel::download(new SalaExport, 'salas.xlsx');
        } else {
            $pdf = \PDF::loadView('downloads.financeiros.ficha-pagamentos-receber', $headers)->setPaper('A4', 'portrait');
            return $pdf->stream('turmas.financeiros.ficha-pagamentos-receber.pdf');
        }
    }
    
    
}
