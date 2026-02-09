<?php

namespace App\Jobs;

use App\Http\Controllers\TraitHeader;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\calendarios\ServicoTurma;
use App\Models\web\estudantes\CartaoEstudante;
use App\Models\web\turmas\EstudantesTurma;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ActualizarCartaoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use TraitHeader;

    public $timeout = 120; // Timeout máximo para o Job
    public $taxa;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($taxa)
    {
        //
        $this->taxa = $taxa;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ini_set('memory_limit', '4096M');

        // Registro de início
        Log::info('Job iniciado: ' . now());

        $ano_lectivo = AnoLectivo::findOrFail($this->anolectivoActivo());

        $inicioAnoLectivo = Carbon::parse($ano_lectivo->inicio)->startOfMonth();
        $mesAtual = Carbon::now()->startOfMonth();
        $mesAnterior = Carbon::now()->subMonth()->startOfMonth();

        // Processo de atualização
        $estudantesTurma = EstudantesTurma::with('turma')->where('ano_lectivos_id', $ano_lectivo->id)->get();

        foreach ($estudantesTurma as $estudanteTurma) {
            $cartoes = CartaoEstudante::with('servico')
                ->where('estudantes_id', $estudanteTurma->estudantes_id)
                ->where('mes_id', 'M') // ← ajuste se "M" for simbólico
                ->whereIn('status', ['divida', 'Nao Pago'])
                ->where('ano_lectivos_id', $ano_lectivo->id)
                ->get();

            foreach ($cartoes as $cartao) {
                $servico_turma = ServicoTurma::with('turma')
                    ->whereHas('turma', function ($query) {
                        $query->where('status', 'activo');
                    })
                    ->where('turmas_id', $estudanteTurma->turmas_id)
                    ->where('servicos_id', $cartao->servicos_id)
                    ->where('ano_lectivos_id', $ano_lectivo->id)
                    ->first();

                if (!$servico_turma) continue;

                $preco = $servico_turma->preco ?? 0;
                $hoje = Carbon::now();



                $dataExp = Carbon::parse($cartao->data_exp)->startOfDay();
                $dataAt = Carbon::parse($cartao->data_at)->startOfDay();

                $data_primeira_taxa = $dataAt->copy()->addDays($estudanteTurma->turma->taxa_multa1_dia - 1);

                $data_segunda_taxa = $dataAt->copy()->addDays($estudanteTurma->turma->taxa_multa2_dia - 1);
                $data_terceira_taxa = $dataAt->copy()->addDays($estudanteTurma->turma->taxa_multa3_dia - 1);

                $mesCartao = $dataExp->copy()->startOfMonth();

                // 1. Mês atual → aplicar multas progressivamente
                if ($mesCartao->equalTo($mesAtual)) {

                    $multa1 = $multa2 = $multa3 = 0;
                    $multa1_status = $multa2_status = $multa3_status = "N";

                    if ($this->taxa == 1) {
                        if ($hoje->gt($data_primeira_taxa)) {
                            $multa1 = $preco * ($servico_turma->taxa_multa1 / 100);
                            $multa1_status = "Y";
                        }
                    } else if ($this->taxa == 2) {
                        if ($hoje->gt($data_segunda_taxa)) {
                            $multa2 = $preco * ($servico_turma->taxa_multa2 / 100);
                            $multa2_status = "Y";
                        }
                    } else if ($this->taxa == 3) {
                        if ($hoje->gt($data_terceira_taxa)) {
                            $multa3 = $preco * ($servico_turma->taxa_multa3 / 100);
                            $multa3_status = "Y";
                        }
                    }

                    $cartao->update([
                        "status" => "divida",
                        "multa" => $multa1 + $multa2 + $multa3,
                        "multa1" => $multa1_status,
                        "multa2" => $multa2_status,
                        "multa3" => $multa3_status,
                        "preco_unitario" => $preco,
                    ]);
                }
                // 2. Meses anteriores entre início do ano letivo e mês anterior → aplicar só a 3ª multa
                elseif ($mesCartao->gte($inicioAnoLectivo) && $mesCartao->lte($mesAnterior)) {
                    $multa3 = $preco * ($servico_turma->taxa_multa3 / 100);
                    $cartao->update([
                        "status" => "divida",
                        "multa" => $multa3,
                        "multa1" => "Y",
                        "multa2" => "Y",
                        "multa3" => "Y",
                        "preco_unitario" => $preco,
                    ]);
                }
            }
        }

        // Registro de término
        Log::info("Job concluído: " . now());
    }
}
