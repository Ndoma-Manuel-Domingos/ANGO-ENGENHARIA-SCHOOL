<?php

namespace App\Http\Controllers;

use App\Models\Arquivo;
use App\Models\Comunicado;
use App\Models\ControloLancamentoNotas;
use App\Models\ControloLancamentoNotasEscolas;
use App\Models\DireccaoMunicipal;
use App\Models\DireccaoProvincia;
use App\Models\Instituicao;
use App\Models\Notificacao;
use App\Models\Professor;
use App\Models\Shcool;
use App\Models\SolicitacaoProfessor;
use App\Models\User;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\anolectivo\ControlePeriodico;
use App\Models\web\anolectivo\Trimestre;
use App\Models\web\calendarios\Matricula;
use App\Models\web\calendarios\Semana;
use App\Models\web\calendarios\Tempo;
use App\Models\web\classes\Classe;
use App\Models\web\cursos\Curso;
use App\Models\web\disciplinas\Disciplina;
use App\Models\web\encarregados\EncarregadoEstudantes;
use App\Models\web\estudantes\Estudante;
use App\Models\web\funcionarios\FuncionariosControto;
use App\Models\web\salas\Sala;
use App\Models\web\turmas\DisciplinaTurma;
use App\Models\web\turmas\EstudantesTurma;
use App\Models\web\turmas\FuncionariosTurma;
use App\Models\web\turmas\NotaPauta;
use App\Models\web\turmas\Turma;
use App\Models\web\turnos\Turno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class ProfessorController extends Controller
{
    use TraitHelpers;
    use TraitHeader;
    //meu controlador

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function home()
    {
        $professor = Professor::with(['nacionalidade', 'provincia', 'academico.escolaridade', 'academico.formacao'])
            ->findOrFail(Auth::user()->funcionarios_id);
        
        
        $escolas = FuncionariosControto::where('level', '4')->whereIn('funcionarios_id', [$professor->id])->distinct()->orderBy('shcools_id', "desc")->get(['shcools_id']);

        $infor_escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->whereIn('id', $escolas)->get();

        $arquivo = Arquivo::where('level', $professor->level)->where('model_type', 'professor')->where('model_id', $professor->id)->first();

        $contrato = FuncionariosControto::where('level', '4')->with('departamento', 'cargos')->where('funcionarios_id', $professor->id)->first();

        $headers = [
            "titulo" => "Informações geral do Professor",
            "descricao" => "gestão de discipinas",
            'professor' => $professor,
            'escolas' => $escolas,
            "documentos" => $arquivo,
            'infor_escola' => $infor_escola,
            'contrato' => $contrato,
        ];

        return view('professores.home', $headers);
    }

    public function informacaoTurmaProfessores($id, $escola = null)
    {
        $professor = Professor::with('nacionalidade')->with('provincia')->with('academico.escolaridade')->with('academico.formacao')->findOrFail(Crypt::decrypt($id));
        $escolas = FuncionariosControto::where('level', '4')->whereIn('funcionarios_id', [$professor->id])->distinct()->orderBy('shcools_id', "desc")->get(['shcools_id']);

        $infor_escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->whereIn('id', $escolas)->get();
        $shcool = Shcool::findOrFail(Crypt::decrypt($escola));

        // recuperar ano lecto da escola
        $ano_lectivo_escola = AnoLectivo::where('status', 'activo')->where('shcools_id', $shcool->id)->first();


        $turmas = FuncionariosTurma::where([
            ['tb_turmas_funcionarios.funcionarios_id', '=', $professor->id],
            ['tb_turmas_funcionarios.shcools_id', '=', $shcool->id],
            ['tb_turmas_funcionarios.ano_lectivos_id', '=', $ano_lectivo_escola->id],
        ])
            ->join('tb_disciplinas', 'tb_turmas_funcionarios.disciplinas_id', '=', 'tb_disciplinas.id')
            ->join('tb_turmas', 'tb_turmas_funcionarios.turmas_id', '=', 'tb_turmas.id')
            ->select('tb_disciplinas.disciplina', 'tb_disciplinas.id AS idDis', 'tb_turmas_funcionarios.tempo_edicao', 'tb_turmas_funcionarios.cargo_turma', 'tb_turmas.shcools_id',  'tb_turmas.ano_lectivos_id',  'tb_turmas.turma', 'tb_turmas.id AS idTurma')
            ->get();

        $headers = [
            "titulo" => "Informações geral do Professor",
            "descricao" => "gestão de discipinas",
            'professor' => $professor,
            'escolas' => $escolas,
            'shcool' => $shcool,
            'infor_escola' => $infor_escola,
            'turmas' => $turmas,
        ];

        return view('professores.mais-informacoes-turmas', $headers);
    }

    public function imprimirProfessoresLancamentoNota(Request $request)
    {

        $usuario = auth()->user();
        $turma = Turma::find(Crypt::decrypt($request->turma_id));
        $escola = Shcool::with('ensino')->findOrFail($turma->shcools_id);
        $professor = Professor::with(['nacionalidade', 'provincia', 'academico.escolaridade', 'academico.formacao'])->findOrFail($usuario->funcionarios_id);

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $notas = NotaPauta::where('disciplinas_id', Crypt::decrypt($request->disciplina_id))
            ->where('controlo_trimestres_id', Crypt::decrypt($request->trimestre_id))
            ->where('turmas_id', $turma->id)
            ->with(['estudante', 'trimestre', 'disciplina'])
            ->get()
            ->sortBy(function ($nota) {
                return $nota->estudante->nome;
            });

        $headers = [
            "titulo" => "NINI PAUTAS",
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "notas" => $notas,
            "trimestre" => Trimestre::find(Crypt::decrypt($request->trimestre_id)),
            "disciplina" => Disciplina::find(Crypt::decrypt($request->disciplina_id)),
            "turma" => $turma,
            'professor' => $professor,
            'usuario' => $usuario,
        ];

        $pdf = \PDF::loadView('downloads.relatorios.imprimir-lista-aproveitamento', $headers)
            // ->setPaper('A4', 'landscape')
            ;
        return $pdf->stream('Mapa-aproveitamento.pdf');
    }

    public function informacaoProfessoresLancamentoNota(Request $request)
    {

        $usuario = auth()->user();

        $professor = Professor::with(['nacionalidade', 'provincia', 'academico.escolaridade', 'academico.formacao'])->findOrFail($usuario->funcionarios_id);

        $turma = Turma::with(['escola', 'curso', 'classe', 'sala'])->findOrFail(Crypt::decrypt($request->turma_id));
        $escola = Shcool::with('ensino')->findOrFail($turma->shcools_id);

        if ($escola->ensino->nome == "Ensino Superior") {
            $trimestres = Trimestre::where('ensino_status', '2')->get();
        } else {
            $trimestres = Trimestre::where('ensino_status', '1')->where('trimestre', '<>', 'Geral')->get();
        }

        // $disciplinas = DisciplinaTurma::where('turmas_id', $turma->id)->with('disciplina')->get();
        $disciplinas = FuncionariosTurma::where('turmas_id', $turma->id)
            ->with('disciplina')
            ->where('funcionarios_id', $usuario->funcionarios_id)
            ->get();

        $notas = null;
        $disciplina = null;
        $trimestre = null;

        if (isset($request->disciplina_id) != null || isset($request->trimestre_id) != null) {
            $notas = NotaPauta::where('disciplinas_id', Crypt::decrypt($request->disciplina_id))
                ->where('controlo_trimestres_id', Crypt::decrypt($request->trimestre_id))
                ->where('turmas_id', $turma->id ?? '')
                ->with(['estudante'])
                ->get()
                ->sortBy(function ($nota) {
                    return $nota->estudante->nome;
                });

            $disciplina = Disciplina::find(Crypt::decrypt($request->disciplina_id));
            $trimestre = Trimestre::find(Crypt::decrypt($request->trimestre_id));
        }

        $headers = [
            "titulo" => "Lançamento de Notas",
            "descricao" => ENV('APP_NAME'),
            'professor' => $professor,
            'turma' => $turma,
            'escola' => $escola,
            'disciplina' => $disciplina,
            'trimestre' => $trimestre,
            'classe' => Classe::find($turma->classes_id ?? ''),
            'curso' => Curso::find($turma->cursos_id ?? ''),
            'turno' => Turno::find($turma->turnos_id ?? ''),
            'sala' => Sala::find($turma->salas_id ?? ''),
            'ano' => AnoLectivo::find($turma->ano_lectivos_id ?? ''),
            'lista_trimestres' => $trimestres,
            'disciplinas' => $disciplinas,
            'notas' => $notas,
            'usuario' => $usuario,
        ];

        return view('professores.lancamento-notas', $headers);
    }

    public function professoresLancamentoNotaEstudante(Request $request, $prof = null, $notas = null)
    {
        $usuario = auth()->user();
        $professor = Professor::with('nacionalidade')->with('provincia')->with('academico.escolaridade')->with('academico.formacao')->findOrFail(Crypt::decrypt($request->professor_id));

        $nota = NotaPauta::findOrFail(Crypt::decrypt($request->nota_id));
        $estudante = Estudante::findOrFail($nota->estudantes_id);
        $escola = Shcool::findOrFail($nota->shcools_id);

        $controlo = ControloLancamentoNotasEscolas::where('ano_lectivo_id', $nota->ano_lectivos_id)->where('shcools_id', $nota->shcools_id)->first();
        $lancamento = null;
        if ($controlo) {
            $lancamento = ControloLancamentoNotas::where('status', 'activo')->where('id', $controlo->lancamento_id)->first();
        }

        $headers = [
            "titulo" => "Informações geral do Professor",
            "descricao" => "gestão de discipinas",
            'professor' => $professor,
            'usuario' => $usuario,
            'escola' => $escola,
            'nota' => $nota,
            'estudante' => $estudante,
            'lancamento' => $lancamento,
        ];

        return view('professores.lancamento-notas-index', $headers);
    }

    public function professoresLancamentoNotaEstudanteStore(Request $request)
    {
        $usuario = auth()->user();

        $updateNota = NotaPauta::findOrFail($request->input('nota_id'));
        $turma = Turma::findOrFail($updateNota->turmas_id);
        $escola = Shcool::with('ensino')->findOrFail($updateNota->shcools_id);
        $usuario = auth()->user();

        $professor = Professor::with(['nacionalidade', 'provincia', 'academico.escolaridade', 'academico.formacao'])->findOrFail($usuario->funcionarios_id);

        if ($escola->ensino->nome == "Ensino Superior") {
            if (
                (((int)$request->input('p1') >= 21) or ((int)$request->input('p1') <= -1) and !filter_var((int)$request->input('p1'), FILTER_VALIDATE_INT)) or
                (((int)$request->input('p2') >= 21) or ((int)$request->input('p2') <= -1) and !filter_var((int)$request->input('p2'), FILTER_VALIDATE_INT)) or
                (((int)$request->input('p3') >= 21) or ((int)$request->input('p3') <= -1) and !filter_var((int)$request->input('p3'), FILTER_VALIDATE_INT)) or
                (((int)$request->input('p4') >= 21) or ((int)$request->input('p4') <= -1) and !filter_var((int)$request->input('p4'), FILTER_VALIDATE_INT)) or
                (((int)$request->input('nr') >= 21) or ((int)$request->input('nr') <= -1) and !filter_var((int)$request->input('nr'), FILTER_VALIDATE_INT)) or
                (((int)$request->input('exame_especial') >= 21) or ((int)$request->input('exame_especial') <= -1) and !filter_var((int)$request->input('exame_especial'), FILTER_VALIDATE_INT)) or
                (((int)$request->input('exame_1_especial') >= 21) or ((int)$request->input('exame_1_especial') <= -1) and !filter_var((int)$request->input('exame_1_especial'), FILTER_VALIDATE_INT))
            ) {
                return redirect()->back('message', "Os Valores devem ser Inteiros ou Decimais, e deve manter-se no intervalo de 0 à 20");
            }
        } else {
            if (
                (((int)$request->input('pt') >= 21) or ((int)$request->input('pt') <= -1) and !filter_var((int)$request->input('pt'), FILTER_VALIDATE_INT)) or
                (((int)$request->input('pap') >= 21) or ((int)$request->input('pap') <= -1) and !filter_var((int)$request->input('pap'), FILTER_VALIDATE_INT)) or
                (((int)$request->input('ne') >= 21) or ((int)$request->input('ne') <= -1) and !filter_var((int)$request->input('ne'), FILTER_VALIDATE_INT)) or
                (((int)$request->input('nr') >= 21) or ((int)$request->input('nr') <= -1) and !filter_var((int)$request->input('nr'), FILTER_VALIDATE_INT)) or
                (((int)$request->input('npt') >= 21) or ((int)$request->input('npt') <= -1) and !filter_var((int)$request->input('npt'), FILTER_VALIDATE_INT)) or
                (((int)$request->input('npp') >= 21) or ((int)$request->input('npp') <= -1) and !filter_var((int)$request->input('npp'), FILTER_VALIDATE_INT)) or
                (((int)$request->input('mac') >= 21) or ((int)$request->input('mac') <= -1) and !filter_var((int)$request->input('mac'), FILTER_VALIDATE_INT))
            ) {
                return redirect()->back('message', "Os Valores devem ser Inteiros ou Decimais, e deve manter-se no intervalo de 0 à 20");
            }
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

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

                $updateNota->p1 = $request->input('p1');
                $updateNota->p2 = $request->input('p2');
                $updateNota->p3 = $request->input('p3');
                $updateNota->p4 = $request->input('p4');

                $updateNota->exame_1_especial = $request->input('exame_1_especial');
                $updateNota->exame_especial = $request->input('exame_especial');
                $updateNota->nr = $request->nr;

                $primiero_media = ($request->input('p1') + $request->input('p2')) / 2;

                if ($primiero_media >= 14) {
                    $updateNota->obs1 = "Dispensado";
                    $updateNota->resultado_final = $primiero_media;
                    $updateNota->med = $primiero_media;
                    $updateNota->obs3 = "Apto";
                }

                if ($primiero_media < 14) {
                    $updateNota->resultado_final = $primiero_media;
                    $updateNota->med = $primiero_media;
                    $updateNota->obs1 = "Exame";
                    $updateNota->obs3 = "Não Apto";

                    if ($request->input('exame_1_especial') != '0') {
                        $media_exame = ($updateNota->med + $request->input('exame_1_especial')) / 2;

                        if ($media_exame >= 10) {
                            $updateNota->obs2 = "Apto";
                            $updateNota->resultado_final = $media_exame;
                            $updateNota->media_final = $media_exame;
                            $updateNota->obs3 = "Apto";
                        } else {
                            $updateNota->obs2 = "Recurso";
                            $updateNota->resultado_final = $media_exame;
                            $updateNota->media_final = $media_exame;
                            $updateNota->obs3 = "Não Apto";


                            if ($request->input('recurso') != '0') {
                                $media_exame_recurso = ($updateNota->media_final + $request->input('recurso')) / 2;

                                if ($media_exame_recurso >= 10) {
                                    $updateNota->obs3 = "Apto";
                                    $updateNota->resultado_final = $media_exame_recurso;
                                    $updateNota->media_final = $media_exame_recurso;
                                } else {
                                    $updateNota->obs3 = "Não Apto";
                                    $updateNota->resultado_final = $media_exame_recurso;
                                    $updateNota->media_final = $media_exame_recurso;

                                    if ($request->input('exame_especial') != '0') {
                                        $media_exame_especial = ($updateNota->resultado_final + $request->input('exame_especial')) / 2;

                                        if ($media_exame_especial >= 10) {
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

                $updateNota->descricao = $request->input('descricao_estudante');

                $updateNota->update();
            } else {

                $updateNota->funcionarios_id = $professor->id;
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


            /** dodos do professor logado */
            $professor = Professor::findOrFail(Auth::user()->funcionarios_id);
            $ano_lectivo = AnoLectivo::findOrFail($updateNota->ano_lectivos_id);
            $turma = Turma::findOrFail($updateNota->turmas_id);
            $disciplina = Disciplina::findOrFail($updateNota->disciplinas_id);
            $estudante = Estudante::findOrFail($updateNota->estudantes_id);

            /** dodos do professor logado */
            $text = "O Professor {$professor->nome} {$professor->sobre_nome} fez uma actualização nas notas da turma {$turma->turma} na disciplina de {$disciplina->disciplina} para o estudante {$estudante->nome} {$estudante->sobre_nome}";

            Notificacao::create([
                'user_id' => Auth::user()->id,
                'destino' => $ano_lectivo->shcools_id,
                'type_destino' => 'escola',
                'type_enviado' => 'professor',
                'notificacao' => $text,
                'notificacao_user' => $text,
                'status' => '0',
                'model_id' => $updateNota->id,
                'model_type' => "Notas",
                'shcools_id' => $ano_lectivo->shcools_id
            ]);

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
            'message' => 'Notas Lançadaas com sucesso!',
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }

    public function privacidade()
    {
        $usuario = User::findOrFail(Auth::user()->id);

        $headers = [
            "titulo" => "Privacidade",
            "descricao" => env('APP_NAME'),
            "usuario" => $usuario,
        ];

        return view('professores.privacidade', $headers);
    }

    public function privacidadeUpdate(Request $request, $id)
    {

        $request->validate([
            'password_1' => 'required',
            'password_2' => 'required',
            'password_3' => 'required',
            'user' => 'required',
        ]);

        $usuario = User::findOrFail($id);
        /** dodos do professor logado */
        $professor = Professor::with('nacionalidade')->with('provincia')->with('academico.escolaridade')->with('academico.formacao')->findOrFail(Auth::user()->funcionarios_id);
        $escolas = FuncionariosControto::where('level', '4')->whereIn('funcionarios_id', [$professor->id])->distinct()->orderBy('shcools_id', "desc")->get(['shcools_id']);

        $infor_escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->whereIn('id', $escolas)->get();
        /** dodos do professor logado */

        if (!Hash::check($request->password_1, $usuario->password)) {
            Alert::warning('Atenção', 'Senha Actual Incorrecta');
            return redirect()->route('prof.privacidade')->with('danger', 'Senha Actual Incorrecta');
        }

        if ($request->password_2 != $request->password_3) {
            Alert::warning('Atenção', 'As duas novas senhas não podem ser diferentes');
            return redirect()->route('prof.privacidade')->with('danger', 'As duas novas senhas não podem ser diferentes');
        }

        $usuario->password = Hash::make($request->password_2);
        $usuario->usuario = $request->user;
        $usuario->numero_avaliacoes = $request->numero_avaliacoes;
        $usuario->email = $request->email;
        $usuario->nome = $request->nome;
        $usuario->telefone = $request->telefone;
        $usuario->update();


        $text = "O Professor {$professor->nome} {$professor->sobre_nome} fez uma actualizações no se dados";

        foreach ($infor_escola as $escola) {
            Notificacao::create([
                'user_id' => Auth::user()->id,
                'destino' => $escola->id,
                'type_destino' => 'escola',
                'type_enviado' => 'professor',
                'notificacao' => $text,
                'notificacao_user' => $text,
                'status' => '0',
                'model_id' => $usuario->id,
                'model_type' => "actulizações",
                'shcools_id' => $escola->id
            ]);
        }

        if ($usuario->update()) {
            Alert::success('Bom Trabalho', 'Credências actualizados com sucesso, Usar da Proxíma vez que Entrar no sistema');
            return redirect()->route('prof.privacidade')->with('message', 'Credências actualizados com sucesso, Usar da Proxíma vez que Entrar no sistema');
        }
    }

    public function escolas()
    {

        $user = auth()->user();

        if (!$user->can('read: escola')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $professor = Professor::with('nacionalidade')->with('provincia')->with('academico.escolaridade')->with('academico.formacao')->findOrFail(Auth::user()->funcionarios_id);
        $escolas = FuncionariosControto::where('level', '4')->whereIn('funcionarios_id', [$professor->id])->distinct()->orderBy('shcools_id', "desc")->get(['shcools_id']);

        $infor_escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->whereIn('id', $escolas)->get();

        $headers = [
            "titulo" => "Informações geral do Professor",
            "descricao" => "gestão de discipinas",
            'professor' => $professor,
            'escolas' => $infor_escola,
        ];

        return view('professores.escolas', $headers);
    }

    public function horarios(Request $request)
    {

        $user = auth()->user();

        if (!$user->can('read: turma')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $professor = Professor::with('nacionalidade')
            ->with('provincia')
            ->with('academico.escolaridade')
            ->with('academico.formacao')
            ->findOrFail(Auth::user()->funcionarios_id);

        $turmas = FuncionariosTurma::with(["professor", "turma", "disciplina"])
            ->select('turmas_id', 'shcools_id', 'ano_lectivos_id', 'funcionarios_id')
            ->where('funcionarios_id', $professor->id)
            ->groupBy('turmas_id', 'shcools_id', 'ano_lectivos_id', 'funcionarios_id')
            ->get();


        // ->groupBy('turmas_id')
        $escolas = FuncionariosControto::where('level', '4')
            ->whereIn('funcionarios_id', [$professor->id])->distinct()
            ->orderBy('shcools_id', "desc")->get(['shcools_id']);

        $infor_escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->whereIn('id', $escolas)->get();

        $tempos = Tempo::get();
        $semanas = Semana::where('status', 'activo')->get();

        $headers = [
            "titulo" => "Informações geral do Professor",
            "descricao" => "gestão de discipinas",
            "professor" => $professor,
            "turmas" => $turmas,
            "tempos" => $tempos,
            "semanas" => $semanas,
            "escolas" => $infor_escola,
            "requests" => $request->all('escola')
        ];

        return view('professores.horarios', $headers);
    }

    public function turmas(Request $request)
    {

        $user = auth()->user();

        if (!$user->can('read: turma')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $professor = Professor::with('nacionalidade')
            ->with('provincia')
            ->with('academico.escolaridade')
            ->with('academico.formacao')
            ->findOrFail(Auth::user()->funcionarios_id);

        $turmas = FuncionariosTurma::when($request->escola, function ($query, $value) {
            $query->where('shcools_id', Crypt::decrypt($value));
        })
            ->where('funcionarios_id', $professor->id)
            ->select('turmas_id', DB::raw('MAX(id) as id')) // Seleciona o máximo id para cada turmas_id
            ->groupBy('turmas_id') // Agrupa por turmas_id
            ->with(['turma.curso', 'turma.classe', 'turma.turno', 'turma.escola'])
            ->get();

        // ->groupBy('turmas_id')
        $escolas = FuncionariosControto::where('level', '4')
            ->whereIn('funcionarios_id', [$professor->id])->distinct()
            ->orderBy('shcools_id', "desc")->get(['shcools_id']);

        $infor_escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->whereIn('id', $escolas)->get();

        $headers = [
            "titulo" => "Informações geral do Professor",
            "descricao" => "gestão de discipinas",
            'professor' => $professor,
            'turmas' => $turmas,
            'escolas' => $infor_escola,
            "requests" => $request->all('escola')
        ];


        return view('professores.turmas', $headers);
    }

    public function turmasInformacoes($id)
    {
        $user = auth()->user();

        if (!$user->can('read: turma')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $professor = Professor::with('nacionalidade')->with('provincia')->with('academico.escolaridade')->with('academico.formacao')->findOrFail(Auth::user()->funcionarios_id);
        $turma = Turma::findOrFail(Crypt::decrypt($id));

        // controle lancamento de notas se esta activo ou não
        $controlo = ControloLancamentoNotasEscolas::where('ano_lectivo_id', $turma->ano_lectivos_id)
            ->where('shcools_id', $turma->shcools_id)
            ->first();

        $lancamento = ControloLancamentoNotas::where('status', 'activo')->where('id', $controlo->lancamento_id ?? '')->first();
        $estudantes = EstudantesTurma::with('estudante')->where('turmas_id', $turma->id)->get();
        $disciplinas = FuncionariosTurma::where('turmas_id', $turma->id)->where('funcionarios_id', $professor->id)->with('disciplina.horarios')->get();
        $tempo_edicao = FuncionariosTurma::where('turmas_id', $turma->id)->where('funcionarios_id', $professor->id)->with('disciplina.horarios')->first();

        $tempos = Tempo::get();
        $semanas = Semana::where('status', 'activo')->get();

        $headers = [
            "titulo" => "Informações geral do Professor",
            "descricao" => "gestão de discipinas",
            "professor" => $professor,
            "turma" => $turma,
            "estudantes" => $estudantes,
            "disciplinas" => $disciplinas,
            "lancamento" => $lancamento,
            "tempo_edicao" => $tempo_edicao,

            "tempos" => $tempos,
            "semanas" => $semanas,
        ];

        return view('professores.turmas-informacoes', $headers);
    }

    public function estudantes(Request $request)
    {

        $user = auth()->user();

        if (!$user->can('read: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $professor = Professor::with('nacionalidade')->with('provincia')->with('academico.escolaridade')->with('academico.formacao')->findOrFail(Auth::user()->funcionarios_id);

        $turmas = FuncionariosTurma::where([
            ['tb_turmas_funcionarios.funcionarios_id', '=', $professor->id],
        ])
            ->join('tb_turmas', 'tb_turmas_funcionarios.turmas_id', '=', 'tb_turmas.id')
            ->select('tb_turmas.id')
            ->get();

        $escolas = FuncionariosControto::where('level', '4')->whereIn('funcionarios_id', [$professor->id])->distinct()->orderBy('shcools_id', "desc")->get(['shcools_id']);
        $infor_escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->whereIn('id', $escolas)->get();


        $estus = EstudantesTurma::whereIn('tb_turmas_estudantes.turmas_id', $turmas)
            ->select('tb_turmas_estudantes.estudantes_id')
            ->get();

        $estudantes = Estudante::when($request->escola, function ($query, $value) {
            $query->where('shcools_id', $value);
        })->with('escola')->with('matricula.turno')->with('matricula.classe')->with('matricula.curso')->whereIn('id', $estus)->get();

        $headers = [
            "titulo" => "Informações geral do Professor",
            "descricao" => "gestão de discipinas",
            'professor' => $professor,
            'estudantes' => $estudantes,
            'escolas' => $infor_escola,
            "requests" => $request->all('escola')
        ];

        return view('professores.estudantes', $headers);
    }

    public function estudantesInformacoes($id)
    {

        $user = auth()->user();

        if (!$user->can('read: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $professor = Professor::with(['nacionalidade', 'provincia', 'academico.escolaridade', 'academico.formacao'])->findOrFail(Auth::user()->funcionarios_id);
        $estudante = Estudante::with('provincia', 'municipio')->findOrFail(Crypt::decrypt($id));
        $escola = Shcool::with('ensino')->findOrFail($estudante->shcools_id);

        $anoLectivoActivo = AnoLectivo::where('shcools_id', $escola->id)->where('status', 'activo')->first();

        $matricula = Matricula::where('estudantes_id', $estudante->id)
            ->where('status_matricula', 'confirmado')
            ->with(['curso', 'classe', 'turno'])
            ->first();

        $turma = Turma::where('cursos_id', $matricula->cursos_id)
            ->where('classes_id', $matricula->classes_id)
            ->where('turnos_id', $matricula->turnos_id)
            ->first();

        if ($turma) {
            $sala = Sala::findOrFail($turma->salas_id);
        } else {
            $turma = null;
            $sala = null;
        }

        $encarregado = EncarregadoEstudantes::with(['estudante', 'encarregado'])->where('estudantes_id', $estudante->id)
            ->first();

        // notas turma do estudante
        $turmasEstudante = EstudantesTurma::where('estudantes_id', $estudante->id)
            ->where('ano_lectivos_id', $anoLectivoActivo->id)
            ->first();
        //turma dele
        $turma = Turma::findOrFail($turmasEstudante->turmas_id);
        //total disciplnas turma
        $totalDisciplinas = DisciplinaTurma::where('turmas_id', $turma->id)->count('id');

        $trimestre1 = ControlePeriodico::where('trimestre', 'Iª Trimestre')->first();
        $trimestre2 = ControlePeriodico::where('trimestre', 'IIª Trimestre')->first();
        $trimestre3 = ControlePeriodico::where('trimestre', 'IIIª Trimestre')->first();
        $trimestre4 = ControlePeriodico::where('trimestre', 'Geral')->first();

        $notasSomaMdf = NotaPauta::where('estudantes_id', $estudante->id)
            ->where('controlo_trimestres_id', $trimestre4->id)
            ->where('ano_lectivos_id', $anoLectivoActivo->id)
            ->sum('mfd');

        $notasSomaNe = NotaPauta::where('estudantes_id', $estudante->id)
            ->where('controlo_trimestres_id', $trimestre4->id)
            ->where('ano_lectivos_id', $anoLectivoActivo->id)
            ->sum('ne');

        $headers = [
            "titulo" => "Informações do Professor",
            "descricao" => ENV('APP_NAME'),
            'professor' => $professor,
            'estudante' => $estudante,

            'curso' => Curso::findOrFail($matricula->cursos_id),
            'turno' => Turno::findOrFail($matricula->turnos_id),
            'classe' => Classe::findOrFail($matricula->classes_id),
            'escola' => Shcool::findOrFail($matricula->shcools_id),

            'sala' => $sala,
            'turma' => $turma,
            'matricula' => $matricula,
            'encarregado' => $encarregado,

            //notas
            "turmaDisciplinas" => DisciplinaTurma::where('turmas_id', $turma->id)
                ->with(['disciplina', 'turma'])
                ->get(),

            "anoLectivo" => $anoLectivoActivo,
            "somaMFD" => $notasSomaMdf,
            "somaNE" => $notasSomaNe,
            'totalDisciplinas' => $totalDisciplinas,
            'trimestre1' => $trimestre1,
            'trimestre2' => $trimestre2,
            'trimestre3' => $trimestre3,
            'trimestre4' => $trimestre4,
        ];

        return view('professores.estudantes-informacoes', $headers);
    }

    public function FotoPerfil(Request $request)
    {
        $validacao = $request->validate([
            'professor_id' => 'required',
            'foto' => 'required|mimes:jpg,jpeg,png',
        ], [
            'professor_id.required' => "***",
            'foto.required' => "Deves Selecionar uma imagem"
        ]);

        $professor = Professor::findOrFail($request->input('professor_id'));

        /** dodos do professor logado */
        $professor = Professor::with('nacionalidade')->with('provincia')->with('academico.escolaridade')->with('academico.formacao')->findOrFail(Auth::user()->funcionarios_id);
        $escolas = FuncionariosControto::where('level', '4')->whereIn('funcionarios_id', [$professor->id])->distinct()->orderBy('shcools_id', "desc")->get(['shcools_id']);

        $infor_escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->whereIn('id', $escolas)->get();
        /** dodos do professor logado */


        if (!empty($request->file('foto'))) {
            $image = $request->file('foto');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('assets/images/professores'), $imageName);
        } else {
            $imageName = Null;
        }

        $professor->image = $imageName;
        $professor->update();

        $text = "O Professor {$professor->nome} {$professor->sobre_nome} fez uma actualizações na sua foto do perfil";

        foreach ($infor_escola as $escola) {
            Notificacao::create([
                'user_id' => Auth::user()->id,
                'destino' => $escola->id,
                'type_destino' => 'escola',
                'type_enviado' => 'professor',
                'notificacao' => $text,
                'notificacao_user' => $text,
                'status' => '0',
                'model_id' => $professor->id,
                'model_type' => "actulizações",
                'shcools_id' => $escola->id
            ]);
        }

        return redirect()->back()->with('message', 'Foto Editada com sucesso!');
    }

    public function minhaSolicitacoes(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: documento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }


        $professor = Professor::with('nacionalidade')->with('provincia')->with('academico.escolaridade')->with('academico.formacao')->findOrFail(Auth::user()->funcionarios_id);

        $documentos = SolicitacaoProfessor::when($request->processo, function ($query, $value) {
            $query->where('processo', $value);
        })
            ->with(['professor', 'instituicao1', 'disciplina', 'curso', 'classe'])
            ->where('professor_id', '=', $professor->id)
            ->where('level_origem', '=', '4')
            ->get();

        $headers = [
            "titulo" => "Lista dos Solicitações",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "documentos" => $documentos,
        ];

        return view('professores.solicitacoes.index', $headers);
    }

    public function detalheMinhaSolicitacoes($id)
    {
        $user = auth()->user();

        if (!$user->can('read: documento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }


        $data = SolicitacaoProfessor::with('professor', 'disciplina', 'classe', 'instituicao1', 'curso', 'user', 'instituicao_resposta')->findOrFail($id);

        $headers = [
            "titulo" => "Detalhe das Solicitações",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "item" => $data,
        ];

        return view('professores.solicitacoes.show', $headers);
    }


    public function baixarSolicitacoes($id)
    {
        $user = auth()->user();

        if (!$user->can('read: documento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }


        $data = SolicitacaoProfessor::with('professor', 'disciplina', 'classe', 'instituicao1', 'curso', 'user')->findOrFail($id);


        $headers = [
            "titulo" => "Detalhe das Solicitações",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "item" => $data,
        ];

        // $orintacao = 'landscape';
        $orintacao = 'portrait';

        $time = time();

        $pdf = \PDF::loadView('professores.solicitacoes.arquivo-download', $headers)->setPaper('A4', $orintacao);
        return $pdf->stream("solicitacao{{ $time }}.pdf");
    }

    public function solicitacaoProcesso(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('create: documento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }


        $professor = Professor::with('nacionalidade')->with('provincia')->with('academico.escolaridade')->with('academico.formacao')->findOrFail(Auth::user()->funcionarios_id);

        $headers = [
            "titulo" => "Solicitação de Processo",
            "descricao" => env('APP_NAME'),
            "professor" => $professor,
            "instituicoes" => Instituicao::where('nome', '!=', 'MINISTERIO')->get(),
            "disciplinas" => Disciplina::get(),
            "cursos" => Curso::get(),
            "classes" => Classe::get(),
            "escolas" => Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->where('status', 'activo')->get(),
        ];

        return view('professores.solicitacoes.create', $headers);
    }

    public function solicitacaoProcessoStore(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('create: documento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate(
            [
                'tipo_documento' => 'required',
                'classes_id' => 'required',
                'cursos_id' => 'required',
                'disciplinas_id' => 'required',
                'instituicoes_destino' => 'required',
                'instituicao_id' => 'required',
                'escola_transferencia_id' => 'required',
            ],
            [
                'tipo_documento.required' => "Obrigatório",
                'classes_id.required' => "Obrigatório",
                'cursos_id.required' => "Obrigatório",
                'disciplinas_id.required' => "Obrigatório",
                'instituicoes_destino.required' => "Obrigatório",
                'instituicao_id.required' => "Obrigatório",
                'escola_transferencia_id.required' => "Obrigatório"
            ]
        );


        if ($request->tipo_documento == 'transferencia') {

            $request->validate(
                [
                    'documento' => 'required',
                ],
                [
                    'documento.required' => "Senha Obrigatória",
                ]
            );
        }


        if (!empty($request->file('documento'))) {
            $image = $request->file('documento');
            $imageName = time() . '1.' . $image->extension();
            $image->move(public_path('assets/arquivos'), $imageName);
        } else {
            $imageName = Null;
        }

        $professor = Professor::with('nacionalidade')->with('provincia')->with('academico.escolaridade')->with('academico.formacao')->findOrFail(Auth::user()->funcionarios_id);

        if ($request->instituicao_id == '2') {
            $level = 2;
            $insti = DireccaoProvincia::find($request->instituicoes_destino);
            $type = "provincial";
        }

        if ($request->instituicao_id == '3') {
            $level = 3;
            $insti = DireccaoMunicipal::find($request->instituicoes_destino);
            $type = "municipal";
        }

        if ($request->instituicao_id == '4') {
            $level = 4;
            $insti = Shcool::find($request->instituicoes_destino);
            $type = "escola";
        }

        $create = SolicitacaoProfessor::create([
            'professor_id' => $professor->id,
            'classes_id' => $request->classes_id,
            'cursos_id' => $request->cursos_id,
            'disciplinas_id' => $request->disciplinas_id,
            'instituicao_id' => $insti->id,
            'solicitacao' => $request->tipo_documento,
            "escola_transferencia_id" => $request->escola_transferencia_id,
            'escola_destino_level' => '4',
            'level_origem' => '4',
            'level_destino' => $level,
            'descricao' => $request->descricao,
            'status' => '0',
            'documento_pdf' => $imageName,
        ]);

        $text = "O professor {$professor->nome} {$professor->sobre_nome} fez uma solicitação de {$request->tipo_documento}";
        $text2 = "";

        Notificacao::create([
            'user_id' => Auth::user()->id,
            'destino' => $insti->id,
            'type_destino' => $type,
            'type_enviado' => 'professor',
            'notificacao' => $text,
            'notificacao_user' => $text2,
            'status' => '0',
            'model_id' => $create->id,
            'model_type' => "Documentos",
            'shcools_id' => $insti->id,
        ]);

        Alert::success("Bom Trabalho", "solicitação enviada com sucesso");
        return redirect()->back();
    }


    public function meusComunicados()
    {
        $user = auth()->user();

        if (!$user->can('read: documentos')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $professor = Professor::with('nacionalidade')->with('provincia')->with('academico.escolaridade')->with('academico.formacao')->findOrFail(Auth::user()->funcionarios_id);
        $escolas = FuncionariosControto::where('level', '4')->whereIn('funcionarios_id', [$professor->id])->distinct()->orderBy('shcools_id', "desc")->get(['shcools_id']);

        $infor_escola = Shcool::whereIn('id', $escolas)->get();

        $comunicados = Comunicado::whereIn('shcools_id', $escolas)->where('level', '4')->where('to_escola', 'Professores')->orWhere('to_escola', 'Todos')->with(['user', 'escola', 'ano'])->orderBy('id', 'desc')->get();

        $headers = [
            "titulo" => "Informações geral do Professor",
            "descricao" => "gestão de discipinas",
            'professor' => $professor,
            'escolas' => $infor_escola,
            "comunicados" => $comunicados,
        ];

        return view('professores.comunicados', $headers);
    }

    public function detalheComunicados($id)
    {
        $user = auth()->user();

        if (!$user->can('read: documentos')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }


        $professor = Professor::with('nacionalidade')->with('provincia')->with('academico.escolaridade')->with('academico.formacao')->findOrFail(Auth::user()->funcionarios_id);
        $escolas = FuncionariosControto::where('level', '4')->whereIn('funcionarios_id', [$professor->id])->distinct()->orderBy('shcools_id', "desc")->get(['shcools_id']);

        $infor_escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->whereIn('id', $escolas)->get();

        $comunicado = Comunicado::whereIn('shcools_id', $escolas)->where('level', '4')->where('to_escola', 'Professores')->orWhere('to_escola', 'Todos')->with(['user', 'escola', 'ano'])->findOrFail($id);

        $headers = [
            "titulo" => "Informações geral do Professor",
            "descricao" => "gestão de discipinas",
            'professor' => $professor,
            'escolas' => $infor_escola,
            "comunicado" => $comunicado,
        ];

        return view('professores.detalhe-comunicados', $headers);
    }
}
