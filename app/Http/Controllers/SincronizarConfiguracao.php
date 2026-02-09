<?php

namespace App\Http\Controllers;

use App\Models\Shcool;
use App\Models\User;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\calendarios\Servico;
use App\Models\web\calendarios\ServicoTurma;
use App\Models\web\classes\AnoLectivoClasse;
use App\Models\web\cursos\AnoLectivoCurso;
use App\Models\web\cursos\DisciplinaCurso;
use App\Models\web\disciplinas\DisciplinaAnoLectivo;
use App\Models\web\estudantes\CartaoEstudante;
use App\Models\web\salas\AnoLectivoSala;
use App\Models\web\turmas\DisciplinaTurma;
use App\Models\web\turmas\FuncionariosTurma;
use App\Models\web\turmas\Horario;
use App\Models\web\turmas\Turma;
use App\Models\web\turnos\AnoLectivoTurno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class SincronizarConfiguracao extends Controller
{
    use TraitHelpers;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function configuracao(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: sincronizacao')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $headers = [
            "titulo" => "Sincronização de configurações",
            "descricao" => env('APP_NAME'),
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "anos" => AnoLectivo::where('shcools_id', $this->escolarLogada())->get(),
            
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.sincronizacao.configuracao', $headers);
    }


    public function configuracaoPost(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: sincronizacao')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            "ano_lectivo_de" => "required",
            "ano_lectivo_para" => "required",
            "sincronizar" => "required"
        ]);


        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            if ($request->sincronizar == "classes") {
                $classes = AnoLectivoClasse::where('shcools_id', $this->escolarLogada())->where('ano_lectivos_id', $request->ano_lectivo_de)->get();

                foreach ($classes as $classe) {

                    $verificar = AnoLectivoClasse::where('shcools_id', $this->escolarLogada())
                        ->where('ano_lectivos_id', $request->ano_lectivo_para)
                        ->where('classes_id', $classe->classes_id)
                        ->first();

                    if (!$verificar) {
                        AnoLectivoClasse::create([
                            'ano_lectivos_id' => $request->ano_lectivo_para,
                            'classes_id' => $classe->classes_id,
                            'shcools_id' => $this->escolarLogada(),
                            'total_vagas' => $classe->total_vagas,
                        ]);
                    }
                }
            }

            if ($request->sincronizar == "cursos") {

                $cursos = AnoLectivoCurso::where('shcools_id', $this->escolarLogada())->where('ano_lectivos_id', $request->ano_lectivo_de)->get();

                foreach ($cursos as $item) {

                    $verificar = AnoLectivoCurso::where('shcools_id', $this->escolarLogada())
                        ->where('ano_lectivos_id', $request->ano_lectivo_para)
                        ->where('cursos_id', $item->cursos_id)
                        ->first();

                    if (!$verificar) {
                        $create = AnoLectivoCurso::create([
                            'ano_lectivos_id' => $request->ano_lectivo_para,
                            'cursos_id' => $item->cursos_id,
                            'shcools_id' => $this->escolarLogada(),
                            'total_vagas' => $item->total_vagas,
                        ]);
                    }

                    $disciplinas = DisciplinaCurso::where('shcools_id', $this->escolarLogada())->where('cursos_id', $item->cursos_id)->where('ano_lectivos_id', $request->ano_lectivo_de)->get();

                    foreach ($disciplinas as $item3) {

                        $verificar3 = DisciplinaCurso::where('shcools_id', $this->escolarLogada())->where('ano_lectivos_id', $request->ano_lectivo_para)->where('disciplinas_id', $item3->disciplinas_id)->where('cursos_id', $item->cursos_id)->first();

                        if (!$verificar3) {
                            DisciplinaCurso::create([
                                'disciplinas_id' => $item3->disciplinas_id,
                                'categoria_id' => $item3->categoria_id,
                                'cursos_id' => $item->cursos_id,
                                'shcools_id' => $this->escolarLogada(),
                                'ano_lectivos_id' => $request->ano_lectivo_para,
                            ]);
                        }
                    }
                }
            }

            if ($request->sincronizar == "salas") {
                $salas = AnoLectivoSala::where('shcools_id', $this->escolarLogada())->where('ano_lectivos_id', $request->ano_lectivo_de)->get();

                foreach ($salas as $item) {

                    $verificar = AnoLectivoSala::where('shcools_id', $this->escolarLogada())
                        ->where('ano_lectivos_id', $request->ano_lectivo_para)
                        ->where('salas_id', $item->salas_id)
                        ->first();

                    if (!$verificar) {
                        AnoLectivoSala::create([
                            'ano_lectivos_id' => $request->ano_lectivo_para,
                            'salas_id' => $item->salas_id,
                            'shcools_id' => $this->escolarLogada(),
                            'total_vagas' => $item->total_vagas,
                        ]);
                    }
                }
            }

            if ($request->sincronizar == "disciplinas") {
                $disciplinas = DisciplinaAnoLectivo::where('shcools_id', $this->escolarLogada())->where('ano_lectivos_id', $request->ano_lectivo_de)->get();

                foreach ($disciplinas as $item) {

                    $verificar = DisciplinaAnoLectivo::where('shcools_id', $this->escolarLogada())
                        ->where('ano_lectivos_id', $request->ano_lectivo_para)
                        ->where('disciplinas_id', $item->disciplinas_id)
                        ->first();

                    if (!$verificar) {
                        DisciplinaAnoLectivo::create([
                            'ano_lectivos_id' => $request->ano_lectivo_para,
                            'disciplinas_id' => $item->disciplinas_id,
                            'shcools_id' => $this->escolarLogada(),
                            'total_vagas' => $item->total_vagas,
                        ]);
                    }
                }
            }

            if ($request->sincronizar == "turmas") {
                $turmas = Turma::where('shcools_id', $this->escolarLogada())->where('ano_lectivos_id', $request->ano_lectivo_de)->get();

                foreach ($turmas as $item) {

                    $verificar = Turma::where('shcools_id', $this->escolarLogada())
                        ->where('ano_lectivos_id', $request->ano_lectivo_para)
                        ->where('classes_id', $item->classes_id)
                        ->where('turnos_id', $item->turnos_id)
                        ->where('cursos_id', $item->cursos_id)
                        ->where('salas_id', $item->salas_id)
                        ->first();

                    if (!$verificar) {
                        $create = Turma::create([
                            'turma' => $item->turma,
                            'numero_maximo' => $item->numero_maximo,
                            'status' => $item->status,
                            'shcools_id' => $this->escolarLogada(),
                            'classes_id' => $item->classes_id,
                            'turnos_id' => $item->turnos_id,
                            'cursos_id' => $item->cursos_id,
                            'salas_id' => $item->salas_id,

                            "valor_propina" => $item->valor_propina,
                            "valor_confirmacao" => $item->valor_confirmacao,
                            "valor_matricula" => $item->valor_matricula,
                            "numero_maximo" => $item->numero_maximo,

                            'intervalo_pagamento_inicio' => $item->intervalo_pagamento_inicio,
                            'intervalo_pagamento_final' => $item->intervalo_pagamento_final,

                            'taxa_multa1' => $item->taxa_multa1,
                            'taxa_multa1_dia' => $item->taxa_multa1_dia,
                            'taxa_multa2' => $item->taxa_multa2,
                            'taxa_multa2_dia' => $item->taxa_multa2_dia,
                            'taxa_multa3' => $item->taxa_multa3,
                            'taxa_multa3_dia' => $item->taxa_multa3_dia,

                            'ano_lectivos_id' => $request->ano_lectivo_para,
                        ]);


                        $matricula = Servico::where([
                            ['shcools_id', $this->escolarLogada()],
                            ['servico', "Matricula"],
                        ])->first()->id;

                        $confirmacao = Servico::where([
                            ['shcools_id', $this->escolarLogada()],
                            ['servico', "Confirmação"],
                        ])->first()->id;

                        $propina = Servico::where([
                            ['shcools_id', $this->escolarLogada()],
                            ['servico', "Propinas"],
                        ])->first()->id;

                        $servico_turma_matricula = ServicoTurma::where("model", "turmas")->where("ano_lectivos_id", $request->ano_lectivo_de)->where("shcools_id", $this->escolarLogada())->where("turmas_id", $item->id)->where("servicos_id", $matricula)->first();
                        $servico_turma_confirmacao = ServicoTurma::where("model", "turmas")->where("ano_lectivos_id", $request->ano_lectivo_de)->where("shcools_id", $this->escolarLogada())->where("turmas_id", $item->id)->where("servicos_id", $confirmacao)->first();
                        $servico_turma_popina = ServicoTurma::where("model", "turmas")->where("ano_lectivos_id", $request->ano_lectivo_de)->where("shcools_id", $this->escolarLogada())->where("turmas_id", $item->id)->where("servicos_id", $propina)->first();

                        /** 
                         * CAdastro de servico de matricula Turma
                         */
                        $ser_matricula = ServicoTurma::create([
                            "servicos_id" => $matricula,
                            "turmas_id" =>  $create->id,
                            "model" => "turmas",
                            "preco" => $item->valor_matricula,
                            "preco_sem_iva" => $servico_turma_matricula->preco_sem_iva,
                            "multa" => 0,
                            "data_inicio" => $servico_turma_matricula->data_inicio ?? '',
                            "data_final" => $servico_turma_matricula->data_final ?? '',
                            "total_vezes" => NULL,
                            "desconto" => 0,

                            'intervalo_pagamento_inicio' => $item->intervalo_pagamento_inicio,
                            'intervalo_pagamento_final' => $item->intervalo_pagamento_final,

                            'taxa_multa1' => $item->taxa_multa1,
                            'taxa_multa1_dia' => $item->taxa_multa1_dia,
                            'taxa_multa2' => $item->taxa_multa2,
                            'taxa_multa2_dia' => $item->taxa_multa2_dia,
                            'taxa_multa3' => $item->taxa_multa3,
                            'taxa_multa3_dia' => $item->taxa_multa3_dia,

                            "status" => 'activo',
                            "pagamento" => "unico",
                            "ano_lectivos_id" => $request->ano_lectivo_para,
                            "shcools_id" => $this->escolarLogada(),
                        ]);

                        $ser_matricula->save();

                        /** 
                         * CAdastro de servico de confirmacao Turma
                         */
                        $ser_confirmacao = ServicoTurma::create([
                            "servicos_id" => $confirmacao,
                            "turmas_id" =>  $create->id,
                            "model" => "turmas",
                            "preco" => $item->valor_confirmacao,
                            "preco_sem_iva" => $servico_turma_confirmacao->preco_sem_iva,
                            "multa" => 0,
                            'intervalo_pagamento_inicio' => $servico_turma_confirmacao->intervalo_pagamento_inicio ?? '',
                            'intervalo_pagamento_final' => $servico_turma_confirmacao->intervalo_pagamento_final ?? '',

                            'taxa_multa1' => $item->taxa_multa1,
                            'taxa_multa1_dia' => $item->taxa_multa1_dia,
                            'taxa_multa2' => $item->taxa_multa2,
                            'taxa_multa2_dia' => $item->taxa_multa2_dia,
                            'taxa_multa3' => $item->taxa_multa3,
                            'taxa_multa3_dia' => $item->taxa_multa3_dia,
                            "data_inicio" => $servico_turma_confirmacao->data_inicio ?? '',
                            "data_final" => $servico_turma_confirmacao->data_final ?? '',
                            "total_vezes" => NULL,
                            "desconto" => 0,
                            "status" => 'activo',
                            "pagamento" => "unico",
                            "ano_lectivos_id" => $request->ano_lectivo_para,
                            "shcools_id" => $this->escolarLogada(),
                        ]);
                        $ser_confirmacao->save();

                        /** 
                         * CAdastro de servico de propina Turma
                         */

                        $ser_propinas = ServicoTurma::create([
                            "servicos_id" => $propina,
                            "turmas_id" =>  $create->id,
                            "model" => "turmas",
                            "preco" => $item->valor_propina,
                            "preco_sem_iva" => $servico_turma_popina->preco_sem_iva,
                            "multa" => 0,
                            "data_inicio" => $servico_turma_popina->data_inicio ?? '',
                            "data_final" => $servico_turma_popina->data_final ?? '',
                            "total_vezes" => 12,
                            'intervalo_pagamento_inicio' => $item->intervalo_pagamento_inicio ?? '',
                            'intervalo_pagamento_final' => $item->intervalo_pagamento_final ?? '',

                            'taxa_multa1' => $item->taxa_multa1,
                            'taxa_multa1_dia' => $item->taxa_multa1_dia,
                            'taxa_multa2' => $item->taxa_multa2,
                            'taxa_multa2_dia' => $item->taxa_multa2_dia,
                            'taxa_multa3' => $item->taxa_multa3,
                            'taxa_multa3_dia' => $item->taxa_multa3_dia,
                            "desconto" => 0,
                            "status" => 'activo',
                            "pagamento" => "mensal",
                            "ano_lectivos_id" =>  $request->ano_lectivo_para,
                            "shcools_id" => $this->escolarLogada(),
                        ]);
                        $ser_propinas->save();


                        $disciplinas = DisciplinaTurma::where('turmas_id', $item->id)->get();

                        foreach ($disciplinas as $item2) {

                            $verificar2 = DisciplinaTurma::where('disciplinas_id', $item2->disciplinas_id)->where('turmas_id', $create->id)->first();

                            if (!$verificar2) {
                                DisciplinaTurma::create([
                                    'status' => $item2->status,
                                    'turmas_id' => $create->id,
                                    'disciplinas_id' => $item2->disciplinas_id,
                                ]);
                            }
                        }

                        $horarios = Horario::where('turmas_id', $item->id)->get();

                        foreach ($horarios as $item3) {

                            $verificar3 = Horario::where('disciplinas_id', $item3->disciplinas_id)->where('turmas_id', $create->id)->first();

                            if (!$verificar3) {
                                Horario::create([
                                    'turmas_id' => $create->id,
                                    'disciplinas_id' => $item3->disciplinas_id,
                                    'semanas_id' => $item3->semanas_id,
                                    'tempos_id' => $item3->tempos_id,
                                    'hora_inicio' => $item3->hora_inicio,
                                    'hora_final' => $item3->hora_final,
                                    'shcools_id' => $this->escolarLogada(),
                                    'ano_lectivos_id' => $request->ano_lectivo_para,
                                ]);
                            }
                        }


                        $professores = FuncionariosTurma::where('shcools_id', $this->escolarLogada())->where('ano_lectivos_id', $request->ano_lectivo_de)->where('turmas_id', $item->id)->get();

                        foreach ($professores as $professor) {
                            $verificar5 = FuncionariosTurma::where('ano_lectivos_id', $request->ano_lectivo_para)->where('shcools_id', $this->escolarLogada())->where('funcionarios_id', $professor->funcionarios_id)->where('disciplinas_id', $professor->disciplinas_id)->where('turmas_id', $create->id)->first();

                            if (!$verificar5) {
                                FuncionariosTurma::create([
                                    'turmas_id' => $professor->turmas_id,
                                    'funcionarios_id' => $professor->funcionarios_id,
                                    'disciplinas_id' => $professor->disciplinas_id,
                                    'cargo_turma' => $professor->cargo_turma,
                                    'tempo_edicao' => $professor->tempo_edicao,
                                    'ano_lectivos_id' => $request->ano_lectivo_para,
                                    'shcools_id' => $this->escolarLogada(),
                                ]);
                            }
                        }
                    }
                }
            }

            if ($request->sincronizar == "turnos") {
                $turnos = AnoLectivoTurno::where('shcools_id', $this->escolarLogada())->where('ano_lectivos_id', $request->ano_lectivo_de)->get();

                foreach ($turnos as $item) {

                    $verificar = AnoLectivoTurno::where('shcools_id', $this->escolarLogada())
                        ->where('ano_lectivos_id', $request->ano_lectivo_para)
                        ->where('turnos_id', $item->turnos_id)
                        ->first();

                    if (!$verificar) {
                        AnoLectivoTurno::create([
                            'ano_lectivos_id' => $request->ano_lectivo_para,
                            'turnos_id' => $item->turnos_id,
                            'shcools_id' => $this->escolarLogada(),
                            'total_vagas' => $item->total_vagas,
                        ]);
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

        Alert::success("Bom Trabalho", "Dados Sincronizados com sucesso");
        return redirect()->back();
    }

    public function bancoDados(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: sincronizacao')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $headers = [
            "titulo" => "Sincronização de configurações",
            "descricao" => env('APP_NAME'),
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "usuario" => User::findOrFail(Auth::user()->id),
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "anos" => AnoLectivo::where('shcools_id', $this->escolarLogada())->get(),

        ];

        return view('admin.sincronizacao.configuracao', $headers);
    }

    public function actualizar_calendario(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: sincronizacao')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $headers = [
            "titulo" => "Actualizar calendario de pagamentos",
            "descricao" => env('APP_NAME'),
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "anos" => AnoLectivo::where('shcools_id', $this->escolarLogada())->get(),
            "servicos" => Servico::where('shcools_id', $this->escolarLogada())->get(),
            
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.sincronizacao.actualizar-calendario', $headers);
    }


    public function actualizarCalendarioPost(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: sincronizacao')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            "ano_lectivos_id" => "required",
            "servico_id" => "required",
            "mes_id" => "required",
            "data_inicio" => "required",
            "data_final" => "required",
        ], [
            "ano_lectivos_id.required" => "Campo Obrigatório",
            "servico_id.required" => "Campo Obrigatório",
            "mes_id.required" => "Campo Obrigatório",
            "data_inicio.required" => "Campo Obrigatório",
            "data_final.required" => "Campo Obrigatório",
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $cartoes = CartaoEstudante::where('servicos_id', $request->servico_id)
                ->where('ano_lectivos_id', $request->ano_lectivos_id)
                ->where('month_name', $request->mes_id)
                // ->where('shcools_id', $this->escolarLogada())
                ->get();

            if (!empty($cartoes)) {
                foreach ($cartoes as $cartao) {
                    $update = CartaoEstudante::findOrFail($cartao->id);
                    $update->data_at = $request->data_inicio;
                    $update->data_exp = $request->data_final;
                    $update->update();
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


        Alert::success("Bom Trabalho", "Dados Sincronizados com sucesso");
        return redirect()->back();
    }
}
