<?php

namespace App\Http\Controllers;

use App\Jobs\ActualizarCartaoJob;
use App\Models\Cargo;
use App\Models\Departamento;
use App\Models\Shcool;
use App\Models\SolicitacaoProfessor;
use App\Models\User;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\calendarios\ServicoTurma;
use App\Models\web\estudantes\CartaoEstudante;
use App\Models\web\estudantes\Estudante;
use App\Models\web\financeiros\DetalhesPagamentoPropina;
use App\Models\web\funcionarios\Funcionarios;
use App\Models\web\funcionarios\FuncionariosControto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class HomeController extends Controller
{
    use TraitHelpers;
    use TraitHeader;
    use TraitChavesSaft;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function actualizacoes_recentes()
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor
        
        // esta funcção ser para actualizar ou adicionar um novo servico a todas as escola que já usam o software (SERVICO DIVERSO) 
        //  e diferenciar os meses serviços mensais (mensalidade, transporte) e serivcos unicos (CArtão de estudante)
        
        try {
            DB::beginTransaction();
            
            // quero actualizar todos os cartões dos estudantes o campo controle_periodico_id que vai subistituir os campos trimestral e semestral
            // primiero trimestre
            $cartoes_estudantes_01 = CartaoEstudante::where("trimestral", "1º Trimestre")
            // ->where("controle_periodico_id", 0)
                ->get();
            
            if($cartoes_estudantes_01 && count($cartoes_estudantes_01) != 0){
                foreach($cartoes_estudantes_01 as $item)
                {
                    $update_cartao = CartaoEstudante::findOrFail($item->id);
                    $update_cartao->controle_periodico_id = 1;
                    $update_cartao->update();
                }
            }
            // segundo trimestre
            $cartoes_estudantes_02 = CartaoEstudante::where("trimestral", "2º Trimestre")
            // ->where("controle_periodico_id", 0)
            ->get();
            
            if($cartoes_estudantes_02 && count($cartoes_estudantes_02) != 0){
                foreach($cartoes_estudantes_02 as $item)
                {
                    $update_cartao = CartaoEstudante::findOrFail($item->id);
                    $update_cartao->controle_periodico_id = 2;
                    $update_cartao->update();
                }
            }
            // terceiro trimestre
            $cartoes_estudantes_03 = CartaoEstudante::where("trimestral", "3º Trimestre")
            // ->where("controle_periodico_id", 0)
            ->get();
            
            if($cartoes_estudantes_03 && count($cartoes_estudantes_03) != 0){
                foreach($cartoes_estudantes_03 as $item)
                {
                    $update_cartao = CartaoEstudante::findOrFail($item->id);
                    $update_cartao->controle_periodico_id = 3;
                    $update_cartao->update();
                }
            }
            // geral trimestre
            $cartoes_estudantes_04 = CartaoEstudante::where("trimestral", "Normal")
            // ->where("controle_periodico_id", 0)
            ->get();
            
            if($cartoes_estudantes_04 && count($cartoes_estudantes_04) != 0){
                foreach($cartoes_estudantes_04 as $item)
                {
                    $update_cartao = CartaoEstudante::findOrFail($item->id);
                    $update_cartao->controle_periodico_id = 4;
                    $update_cartao->update();
                }
            }
            
            
            // 1 semestre
            $cartoes_estudantes_11 = CartaoEstudante::where("semestral", "1º Semestre")
            // ->where("controle_periodico_id", 0)
            ->get();
            
            if($cartoes_estudantes_11 && count($cartoes_estudantes_11) != 0){
                foreach($cartoes_estudantes_11 as $item)
                {
                    $update_cartao = CartaoEstudante::findOrFail($item->id);
                    $update_cartao->controle_periodico_id = 5;
                    $update_cartao->update();
                }
            }
            // 2 semestre
            $cartoes_estudantes_22 = CartaoEstudante::where("semestral", "2º Semestre")
            // ->where("controle_periodico_id", 0)
            ->get();
            
            if($cartoes_estudantes_22 && count($cartoes_estudantes_22) != 0){
                foreach($cartoes_estudantes_22 as $item)
                {
                    $update_cartao = CartaoEstudante::findOrFail($item->id);
                    $update_cartao->controle_periodico_id = 6;
                    $update_cartao->update();
                }
            }
            
            
            // adicionar serviços diversos
            /**$verificar_servico = Servico::where('shcools_id', $this->escolarLogada())->where('servico', "Diversos")->first();
            
            if( !$verificar_servico ){
                
                $verifica_conta_contabilidade = Servico::where('shcools_id', $this->escolarLogada())
                ->where('conta', 'like', "62.1.%")
                ->count();
            
                $nova_conta = "62.1." . $verifica_conta_contabilidade + 1;
            
                $create = Servico::create([
                    "servico" => "Diversos",
                    "tipo" => "S",
                    "unidade" => "uni",
                    "contas" => "receita",
                    "status" => 'activo',
                    "ordem" => $verifica_conta_contabilidade + 1,
                    "conta" => $nova_conta,
                    "shcools_id" => $this->escolarLogada(),
                    "taxa_id" => 1,
                    "motivo_id" => 4,
                ]);
                
                $turmas = Turma::where('shcools_id', $this->escolarLogada())->get();
                
                foreach($turmas as $turma){
                
                    $servTurm = ServicoTurma::where('turmas_id', $turma->id)->where('shcools_id', $this->escolarLogada())->first();
                    
                    ServicoTurma::create([
                        "servicos_id" => $create->id,
                        "turmas_id" =>  $turma->id,
                        "model" => "turmas",
                        "preco" => 0,
                        "preco_sem_iva" => 0,
                        "multa" => 0,
                        "data_inicio" => $servTurm->data_inicio,
                        "data_final" => $servTurm->data_final,
                        "total_vezes" => NULL,
                        "desconto" => 0,
    
                        'intervalo_pagamento_inicio' => $servTurm->intervalo_pagamento_inicio,
                        'intervalo_pagamento_final' => $servTurm->intervalo_pagamento_final,
    
                        'taxa_multa1' => 0,
                        'taxa_multa1_dia' => 0,
                        'taxa_multa2' => 0,
                        'taxa_multa2_dia' => 0,
                        'taxa_multa3' => 0,
                        'taxa_multa3_dia' => 0,
    
                        "status" => 'activo',
                        "pagamento" => "unico",
                        "ano_lectivos_id" => $servTurm->ano_lectivos_id,
                        "shcools_id" => $this->escolarLogada(),
                    ]);
                
                }
            }
            
            $servico =  Servico::where('shcools_id', $this->escolarLogada())->where('servico', "Propinas")->first();
            
            $cartao_estudantes_mensal = CartaoEstudante::where('servicos_id', '=', $servico->id)
                ->where('ano_lectivos_id', $this->anolectivoActivo())
                ->get();
                
            $cartao_estudantes_unicos = CartaoEstudante::where('servicos_id', '!=', $servico->id)
                ->where('ano_lectivos_id', $this->anolectivoActivo())
                ->get();
            
            foreach($cartao_estudantes_mensal as $cartao){
                $update = CartaoEstudante::findOrFail($cartao->id);
                $update->mes_id = "M";
                $update->update();
            }
            
            foreach($cartao_estudantes_unicos as $cartao){
                $update = CartaoEstudante::findOrFail($cartao->id);
                $update->mes_id = "U";
                $update->update();
            }
              ***/
            
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
      
        dd("SUCCESS");
    
    }

    public function actualizacoes_dos_nomes()
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor
        
        try {
            DB::beginTransaction();
            
            // adicionar serviços diversos
            $estudantes = Estudante::where('shcools_id', $this->escolarLogada())->get();
            
            foreach ($estudantes as $item) {
                $estudante = Estudante::findOrFail($item->id);
                $estudante->nome_completo = $estudante->nome . " " . $estudante->sobre_nome;
                $estudante->update();
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
        
        dd("NOME ACTUALIZADOS COM SUCESSO");
    
    }
    
    public function actualizacoes_cartao()
    {
        ini_set('memory_limit', '2024M');  // Ajuste para 1024 MB ou outro valor
        
        try {
            DB::beginTransaction();
            
            // adicionar serviços diversos
            $pagamentos = DetalhesPagamentoPropina::where('status', 'Pago')->where('ano_lectivos_id', $this->anolectivoActivo())->where('shcools_id', $this->escolarLogada())->get();
                        
            foreach ($pagamentos as $item) {
                
                $estudantes = CartaoEstudante::where('estudantes_id', $item->model_id)
                    ->where('month_name', $item->mes)
                    ->where('servicos_id', $item->servicos_id)
                    ->where('mes_id', "M")
                    ->where('status', "!=", 'Pago')
                    ->where('ano_lectivos_id', $this->anolectivoActivo())
                    ->get();
                    
                foreach($estudantes as $item2){
                    $updat = CartaoEstudante::findOrFail($item2->id);
                    $updat->status = "Pago";
                    $updat->update();
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
        
        dd("CARTÕES ACTUALIZADOS COM SUCESSO");
    
    }
    
    public function verificar_actualizacoes_cartao()
    {
         
        $headers = [ 
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "usuario" => User::findOrFail(Auth::user()->id),
        ];
        
        return view('admin.sincronizacao.sincronizar-cartoes', $headers);

    }
    
    public function verificar_actualizacoes_cartao_primeira_taxa()
    {
        $taxa = 1;
        ActualizarCartaoJob::dispatch($taxa);
        
        Alert::success('Informação', 'Cortões actualizado com successo!');
        return redirect()->route('web.verificar-actualizacoes-cartao');
    }
    
    public function verificar_actualizacoes_cartao_segunda_taxa()
    {   
        $taxa = 2;
        ActualizarCartaoJob::dispatch($taxa);
                
        Alert::success('Informação', 'Cortões actualizado com successo!');
        return redirect()->route('web.verificar-actualizacoes-cartao');
    }
    
    public function verificar_actualizacoes_cartao_terceira_taxa()
    {
        $taxa = 3;
        ActualizarCartaoJob::dispatch($taxa);
                        
        Alert::success('Informação', 'Cortões actualizado com successo!');
        return redirect()->route('web.verificar-actualizacoes-cartao');
    }
        
    public function bemVindo()
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor
        
        // $this->actualizarCartao();
        // ActualizarCartaoJob::dispatch(); // Envia o Job para a fila
        $this->controlo();
        
        $solicitacoes = SolicitacaoProfessor::where('status', '0')->with('professor', 'disciplina', 'classe', 'instituicao1', 'curso')->where('level_destino', '4')
        ->where('instituicao_id', $this->escolarLogada())
        ->get();
        
        // transferencia pemitidas ou validades lo pelo govervo e falta o aval da escola
        $transferincias_professores = SolicitacaoProfessor::where('status', '1')
        ->where('escola_destino_level', '4')
        ->where('resposta_opcao', 'Sim')
        ->where('resposta_escola', 'Nao')
        ->where('solicitacao', 'transferencia')
        ->where('escola_transferencia_id', $this->escolarLogada())
        ->with('professor', 'disciplina', 'classe', 'instituicao1', 'curso')
        ->get();

        $verAnoLectivoActivo = AnoLectivo::find($this->anolectivoActivo());

        

        $headers = [ 
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "usuario" => User::findOrFail(Auth::user()->id),
            
            'verAnoLectivoActivo' =>  $verAnoLectivoActivo,
            'solicitacoes' =>  $solicitacoes,
            'transferincias_professores' =>  $transferincias_professores,
        ];
        
        return view('app.bem-vindo', $headers);
    }

    // recursos humanos
    public function recursos_humanos()
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor
         
        $servicos = ServicoTurma::where([
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['model', '=', 'turmas'],
        ])->get();

        if ($servicos) {
           foreach ($servicos as $servico) {

               if($servico->status == "unico"){
                    if ($this->data_sistema() <= $servico->data_inicio || $this->data_sistema() >= $servico->data_final) {
                        $cartoes = CartaoEstudante::where([
                            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                            ['servicos_id', '=', $servico->id],
                            ['status', '=', 'Nao Pago'],
                        ])->get();

                        if ($cartoes) {
                            foreach ($cartoes as $cartao) {
                                $cartaoUpdate = (new CartaoEstudante())->findOrFail($cartao->id);
                                $cartaoUpdate->status = 'divida';
                                $cartaoUpdate->servicos_id = $servico->id;
                                $cartaoUpdate->update();
                            }
                        }
                    }
               }else if($servico->status == "mensal"){
                if ($this->data_sistema() <= $servico->data_inicio || $this->data_sistema() >= $servico->data_final) {
                    $cartoes = CartaoEstudante::where([
                        ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                        ['servicos_id', '=', $servico->id],
                        ['status', '=', 'Nao Pago'],
                    ])->get();

                    if ($cartoes) {
                        foreach ($cartoes as $cartao) {
                            $cartaoUpdate = (new CartaoEstudante())->findOrFail($cartao->id);
                            $cartaoUpdate->status = 'divida';
                            $cartaoUpdate->servicos_id = $servico->id;
                            $cartaoUpdate->update();
                        }
                    }
                }
               }
           }
        }

        
        
        $headers = [ 
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "usuario" => User::findOrFail(Auth::user()->id),
            
            "total_funcionario" => Funcionarios::where('shcools_id', '=', $this->escolarLogada())
                ->where('level', '4')
                ->count(), 
            
            "total_departamentos" => Departamento::where('level', '4')->where('shcools_id', $this->escolarLogada())->count(),
            "total_cargos" => Cargo::where('level', '4')->where('shcools_id', $this->escolarLogada())->count(),
            
            "total_professores" => FuncionariosControto::where([
                ['shcools_id', '=', $this->escolarLogada()], 
                ['cargo_geral', '=', 'professor'], 
                ['level', '=', '4'], 
                ['status', '=', 'activo']
            ])->count(), 
        ];
        
        return view('app.recursos_humanos', $headers);
        
    }
    
    public function actualizar_cor_fundo($cor_fundo)
    {
        $user = User::findOrFail(Auth::user()->id);
        
        $user->color_fundo = $cor_fundo;
        $user->update();
        
        Alert::success('Informação', 'Cor do fundo actualizado com successo!');
        return redirect()->back();
    }

}
