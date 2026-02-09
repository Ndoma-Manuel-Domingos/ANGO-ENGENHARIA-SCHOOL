<?php

namespace App\Services;

use App\Models\web\calendarios\Servico;
use App\Models\web\calendarios\ServicoTurma;
use App\Models\web\estudantes\CartaoEstudante;
use App\Models\web\estudantes\Estudante;
use App\Models\web\financeiros\DetalhesPagamentoPropina;
use App\Models\web\turmas\EstudantesTurma;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class MultaService
{

    public function adicionarMulta($id, $multa, $operacao)
    {
        // Realizar operações de banco de dados aqui
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            // CONTROLAR A SESSÃo INICIALIZADA OU NAO
            $cartao_estudante = CartaoEstudante::findOrFail($id);
            
            $estudante = Estudante::findOrFail($cartao_estudante->estudantes_id);
            $servico = Servico::join('tb_taxas', 'tb_servicos.taxa_id', 'tb_taxas.id')
                ->select('tb_servicos.id', 'taxa', 'contas', 'tb_servicos.servico', 'tb_servicos.contas')
                ->findOrFail($cartao_estudante->servicos_id);
                    
            $estudanteTurma = EstudantesTurma::where([
                ['estudantes_id', $estudante->id],
                ['ano_lectivos_id', $cartao_estudante->ano_lectivos_id],
            ])->first();
                
            $servicoTurma = ServicoTurma::where([
                ['servicos_id', $servico->id],
                ['turmas_id', $estudanteTurma->turmas_id],
                ['ano_lectivos_id', $cartao_estudante->ano_lectivos_id],
                ['model', 'turmas'],
            ])->first();
                
            if($multa == 3 && $operacao == 'adicionar'){
                $multa3 = $servicoTurma->preco * ($servicoTurma->taxa_multa3 / 100);
                if($cartao_estudante->multa3 == "N"){
                    $cartao_estudante->multa = $cartao_estudante->multa + $multa3; 
                    $cartao_estudante->multa3 = 'Y'; 
                    $cartao_estudante->update(); 
                }
            }
            
            if($multa == 3 && $operacao == 'remover'){
                $multa3 = $servicoTurma->preco * ($servicoTurma->taxa_multa3 / 100);
                if($cartao_estudante->multa3 == 'Y'){
                    $cartao_estudante->multa = $cartao_estudante->multa - $multa3; 
                    $cartao_estudante->multa3 = 'N'; 
                    $cartao_estudante->update(); 
                }
            }
                
            if($multa == 2 && $operacao == 'adicionar'){
                $multa2 = $servicoTurma->preco * ($servicoTurma->taxa_multa2 / 100);
                if($cartao_estudante->multa2 == "N"){
                    $cartao_estudante->multa = $cartao_estudante->multa + $multa2; 
                    $cartao_estudante->multa2 = 'Y'; 
                    $cartao_estudante->update(); 
                }
            }
            
            if($multa == 2 && $operacao == 'remover'){
                $multa2 = $servicoTurma->preco * ($servicoTurma->taxa_multa2 / 100);
                if($cartao_estudante->multa2 == 'Y'){
                    $cartao_estudante->multa = $cartao_estudante->multa - $multa2; 
                    $cartao_estudante->multa2 = 'N'; 
                    $cartao_estudante->update(); 
                }
            }
                
            if($multa == 1 && $operacao == 'adicionar'){
                $multa1 = $servicoTurma->preco * ($servicoTurma->taxa_multa1 / 100);
                if($cartao_estudante->multa1 == "N"){
                    $cartao_estudante->multa = $cartao_estudante->multa + $multa1; 
                    $cartao_estudante->multa1 = 'Y'; 
                    $cartao_estudante->update(); 
                }
            }
                
            
            if($multa == 1 && $operacao == 'remover'){
                $multa1 = $servicoTurma->preco * ($servicoTurma->taxa_multa1 / 100);
                if($cartao_estudante->multa1 == 'Y'){
                    $cartao_estudante->multa = $cartao_estudante->multa - $multa1; 
                    $cartao_estudante->multa1 = 'N'; 
                    $cartao_estudante->update(); 
                }
            }
                    
            if($estudante->bolseiro($estudante->id) && $estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto == 100){
                $cartao = CartaoEstudante::where([
                    ['estudantes_id', $estudante->id],
                    ['servicos_id', $servico->id],
                    ['ano_lectivos_id', $cartao_estudante->ano_lectivos_id],
                ])
                ->whereIn('status', ['Nao Pago', 'divida', 'processo'])
                ->get();
            }
            if($estudante->bolseiro($estudante->id) && $estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto != 100){
                $cartao = CartaoEstudante::where([
                    ['estudantes_id', $estudante->id],
                    ['servicos_id', $servico->id],
                    ['ano_lectivos_id', $cartao_estudante->ano_lectivos_id],
                ])
                ->whereIn('status', ['Pago', 'Nao Pago'])
                ->where('cobertura', 'N')
                ->get();
            }else{
                $cartao = CartaoEstudante::where([
                    ['estudantes_id', $estudante->id],
                    ['servicos_id', $servico->id],
                    ['ano_lectivos_id', $cartao_estudante->ano_lectivos_id],
                ])         
                ->whereIn('status', ['Nao Pago', 'divida', 'processo'])
                ->select('tb_cartao_estudantes.id','tb_cartao_estudantes.month_name', 'tb_cartao_estudantes.multa1', 'tb_cartao_estudantes.multa2',  'tb_cartao_estudantes.multa3','tb_cartao_estudantes.data_at', 'tb_cartao_estudantes.data_exp','tb_cartao_estudantes.cobertura', 'tb_cartao_estudantes.status_2','tb_cartao_estudantes.semestral', 'tb_cartao_estudantes.trimestral', 'tb_cartao_estudantes.status', 'tb_cartao_estudantes.multa')
                ->get();
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
        
        
        return [
            'status' => 200,
            'servico' => $servico,
            'servico_turma' => $servicoTurma,
            "bolseiro" => $estudante->bolseiro($estudante->id),
            
            "mesesAdd" => DetalhesPagamentoPropina::where([
                ['status', 'processo'],
                ['funcionarios_id', Auth::user()->id],
                ['model_id', $estudante->id],
                ['ano_lectivos_id', $cartao_estudante->ano_lectivos_id],
            ])
            ->with(['servico'])
            ->get(),
            
            "totalAPagar" => DetalhesPagamentoPropina::where([
                ['status', 'processo'],
                ['funcionarios_id', Auth::user()->id],
                ['model_id', $estudante->id],
                ['ano_lectivos_id', $cartao_estudante->ano_lectivos_id],
            ])->sum('total_pagar'),
            
            "totalDesconto" => DetalhesPagamentoPropina::where([
                ['status', 'processo'],
                ['funcionarios_id', Auth::user()->id],
                ['model_id', $estudante->id],
                ['ano_lectivos_id', $cartao_estudante->ano_lectivos_id],
            ])->sum('desconto_valor'),

            "somaVolores" => DetalhesPagamentoPropina::where([
                ['status', 'processo'],
                ['funcionarios_id', Auth::user()->id],
                ['model_id', $estudante->id],
                ['ano_lectivos_id', $cartao_estudante->ano_lectivos_id],
            ])->sum('preco') + DetalhesPagamentoPropina::where([
                ['status', 'processo'],
                ['funcionarios_id', Auth::user()->id],
                ['model_id', $estudante->id],
                ['ano_lectivos_id', $cartao_estudante->ano_lectivos_id],
            ])->sum('multa'),

            "somaMulta" => DetalhesPagamentoPropina::where([
                ['status', 'processo'],
                ['funcionarios_id', Auth::user()->id],
                ['model_id', $estudante->id],
                ['ano_lectivos_id', $cartao_estudante->ano_lectivos_id],
            ])->sum('multa'),

            "somaQuantidade" => DetalhesPagamentoPropina::where([
                ['status', 'processo'],
                ['funcionarios_id', Auth::user()->id],
                ['model_id', $estudante->id],
                ['ano_lectivos_id', $cartao_estudante->ano_lectivos_id],
            ])->sum('quantidade'),

            "cartao" => $cartao,
        ];
        
    }

    public function adicionarMulta3($id)
    {
        try {
            DB::beginTransaction();

            $cartaoEstudante = CartaoEstudante::findOrFail($id);
            $estudante = Estudante::findOrFail($cartaoEstudante->estudantes_id);
            $servico = $this->getServico($cartaoEstudante->servicos_id);
            $servicoTurma = $this->getServicoTurma($estudante, $cartaoEstudante);

            $this->aplicarMulta($cartaoEstudante, $servicoTurma);

            $cartao = $this->getCartao($estudante, $servico, $cartaoEstudante);

            DB::commit();

            return $this->formatarResposta($cartaoEstudante, $estudante, $servico, $servicoTurma, $cartao);
        } catch (\Exception $e) {
            DB::rollback();
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    private function getServico($servicoId)
    {
        return Servico::join('tb_taxas', 'tb_servicos.taxa_id', 'tb_taxas.id')
        
            ->select('tb_servicos.id', 'taxa', 'contas', 'tb_servicos.servico', 'tb_servicos.contas')
            ->findOrFail($servicoId);
    }

    private function getServicoTurma($estudante, $cartaoEstudante)
    {
        $estudanteTurma = EstudantesTurma::where([
            ['estudantes_id', $estudante->id],
            ['ano_lectivos_id', $cartaoEstudante->ano_lectivos_id],
        ])->first();

        return ServicoTurma::where([
            ['servicos_id', $cartaoEstudante->servicos_id],
            ['turmas_id', $estudanteTurma->turmas_id],
            ['ano_lectivos_id', $cartaoEstudante->ano_lectivos_id],
            ['model', 'turmas'],
        ])->first();
    }

    private function aplicarMulta($cartaoEstudante, $servicoTurma)
    {
        $multa3 = $servicoTurma->preco * ($servicoTurma->taxa_multa3 / 100);
        if ($cartaoEstudante->multa3 == "N") {
            $cartaoEstudante->multa += $multa3;
            $cartaoEstudante->multa3 = 'Y';
            $cartaoEstudante->update();
        }
    }

    private function getCartao($estudante, $servico, $cartaoEstudante)
    {
        $desconto = $estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto ?? 0;

        if ($desconto == 100) {
            return CartaoEstudante::where([
                ['estudantes_id', $estudante->id],
                ['servicos_id', $servico->id],
                ['ano_lectivos_id', $cartaoEstudante->ano_lectivos_id],
            ])
            ->whereIn('status', ['Nao Pago', 'divida', 'processo'])
            ->get();
        }

        return CartaoEstudante::where([
            ['estudantes_id', $estudante->id],
            ['servicos_id', $servico->id],
            ['ano_lectivos_id', $cartaoEstudante->ano_lectivos_id],
        ])
        ->whereIn('status', ['Pago', 'Nao Pago'])
        ->where('cobertura', 'N')
        ->get();
    }

    private function formatarResposta($cartaoEstudante, $estudante, $servico, $servicoTurma, $cartao)
    {
        $detalhesPagamento = DetalhesPagamentoPropina::where([
            ['status', 'processo'],
            ['funcionarios_id', Auth::user()->id],
            ['model_id', $estudante->id],
            ['ano_lectivos_id', $cartaoEstudante->ano_lectivos_id],
        ]);

        return [
            'status' => 200,
            'servico' => $servico,
            'servico_turma' => $servicoTurma,
            "bolseiro" => $estudante->bolseiro($estudante->id),
            "mesesAdd" => $detalhesPagamento->with(['servico'])->get(),
            "totalAPagar" => $detalhesPagamento->sum('total_pagar'),
            "totalDesconto" => $detalhesPagamento->sum('desconto_valor'),
            "somaValores" => $detalhesPagamento->sum('preco') + $detalhesPagamento->sum('multa'),
            "somaMulta" => $detalhesPagamento->sum('multa'),
            "somaQuantidade" => $detalhesPagamento->sum('quantidade'),
            "cartao" => $cartao,
        ];
    }
}
