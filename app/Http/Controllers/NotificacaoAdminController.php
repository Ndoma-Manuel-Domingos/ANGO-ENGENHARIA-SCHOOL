<?php

namespace App\Http\Controllers;

use App\Models\Shcool;
use App\Models\User;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\anolectivo\ControlePeriodico;
use App\Models\web\classes\Classe;
use App\Models\web\cursos\Curso;
use App\Models\web\encarregados\Encarregado;
use App\Models\web\encarregados\EncarregadoEstudantes;
use App\Models\web\estudantes\Estudante;
use App\Models\web\salas\Sala;
use App\Models\web\turmas\DisciplinaTurma;
use App\Models\web\turmas\EstudantesTurma;
use App\Models\web\turmas\NotaPauta;
use App\Models\web\turmas\NotificacaoEncarregado;
use App\Models\web\turmas\Turma;
use App\Models\web\turnos\Turno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

use Illuminate\Support\Facades\Validator;

class NotificacaoAdminController extends Controller
{
    //

    use TraitHelpers;
    use TraitHeader;


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function enviarBoletinsEncarregado()
    {
        $user = auth()->user();

        if (!$user->can('create: notificacao')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->findOrFail($this->escolarLogada());

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        if ($escola->ensino->nome == "Ensino Superior") {
            $trimestres = ControlePeriodico::where('ensino_status', '2')->get();
        } else {
            $trimestres = ControlePeriodico::where('ensino_status', '1')->get();
        }

        $headers = [
            "escola" => $escola,

            "titulo" => "Enviar Boletin",
            "descricao" => env('APP_NAME'),
            "escola" => $escola,
            "usuario" => $user,
            "encarregados" => Encarregado::where([
                ['shcools_id', '=', $this->escolarLogada()],
            ])->get(),
            "turmas" => Turma::where([
                ['shcools_id', '=', $this->escolarLogada()],
                ['status', '=', 'activo'],
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ])->get(),
            "anoLectivos" => AnoLectivo::where([
                ['shcools_id', '=', $this->escolarLogada()],
                ['status', '=', 'activo'],
            ])->get(),
            "trimestre" => $trimestres,
            "estudantes" => Estudante::where([
                ['shcools_id', '=', $this->escolarLogada()],
                ['registro', '=', 'confirmado'],
            ])->get(),

            "notificacaoEnviadas" => NotificacaoEncarregado::where([
                ['shcools_id', '=', $this->escolarLogada()],
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['tb_notificacoes_encarregados_notas.visto', '=', 'nao'],
            ])
                ->count(),
            "notificacaoReciclagem" => NotificacaoEncarregado::where([
                ['shcools_id', '=', $this->escolarLogada()],
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['tb_notificacoes_encarregados_notas.visto', '=', 'sim'],
            ])
                ->count(),
        ];

        return view('app.enviar-boletins-encarregados', $headers);
    }


    public function enviarBoletinsEncarregadoPost(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('create: notificacao')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $validate = Validator::make($request->all(), [
            "encarregados" => 'required',
            "estudantes" => 'required',
            "turma" => 'required',
            "trimestre" => 'required',
            "anoLectivos" => 'required',
        ], [
            "encarregados.required" => "Campo Obrigatório",
            "estudantes.required" => "Campo Obrigatório",
            "turma.required" => "Campo Obrigatório",
            "trimestre.required" => "Campo Obrigatório",
            "anoLectivos.required" => "Campo Obrigatório",
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        } else {

            $ano = AnoLectivo::findOrFail($request->anoLectivos);
            $trimestre = ControlePeriodico::findOrFail($request->trimestre);
            $turma = Turma::findOrFail($request->turma);

            foreach ($request->encarregados as $key) {
                $encarregado = Encarregado::findOrFail($key);

                if ($encarregado) {
                    foreach ($request->estudantes as $value) {
                        $estudante = Estudante::findOrFail($value);

                        if ($estudante) {
                            $verificarRelacao = EncarregadoEstudantes::where([
                                ['estudantes_id', '=', $estudante->id],
                                ['encarregados_id', '=', $encarregado->id],
                            ])->first();

                            if (!$verificarRelacao) {
                                return response()->json([
                                    'status' => 300,
                                    'message' => "Não Existe nenhum grau de parentesco entre {$encarregado->nome} {$encarregado->sobre_nome} e {$estudante->nome} {$estudante->sobre_nome}",
                                ]);
                            }

                            $turmaEstudante = EstudantesTurma::where([
                                ['turmas_id', '=', $turma->id],
                                ['estudantes_id', '=', $estudante->id],
                            ])->first();

                            if (!$turmaEstudante) {
                                return response()->json([
                                    'status' => 300,
                                    'message' => "Este estudante {$estudante->nome} {$estudante->sobre_nome}, esta sem uma turma!",
                                ]);
                            }

                            $verificarNotas = NotaPauta::where([
                                ['turmas_id', '=', $turma->id],
                                ['estudantes_id', '=', $estudante->id],
                                ['controlo_trimestres_id', '=', $trimestre->id],
                                ['ano_lectivos_id', '=', $ano->id],
                                ['conf_ped', '=', 'sim'],
                                ['conf_pro', '=', 'sim'],
                            ])->get();

                            if (!$verificarNotas) {
                                return response()->json([
                                    'status' => 300,
                                    'message' => 'Verifica se as notas já foram confirmadas pelo Professor das disciplinas ou pelo pelo pedagógico!',
                                ]);
                            }

                            $verificar = NotificacaoEncarregado::where([
                                ['turmas_id', '=', $turma->id],
                                ['estudantes_id', '=', $estudante->id],
                                ['trimestres_id', '=', $trimestre->id],
                                ['ano_lectivos_id', '=', $ano->id],
                                ['encarregados_id', '=', $encarregado->id],
                            ])->first();

                            if (!$verificar) {
                                $save = new NotificacaoEncarregado();
                                $save->turmas_id = $turma->id;
                                $save->estudantes_id = $estudante->id;
                                $save->trimestres_id = $trimestre->id;
                                $save->encarregados_id = $encarregado->id;
                                $save->ano_lectivos_id = $ano->id;
                                $save->descricao = $request->descricao;
                                $save->shcools_id = $this->escolarLogada();
                                $save->data_at = $this->data_sistema();
                                $save->titulo = "Envio de Notas";
                                $save->tipo = "notas";
                                $save->save();
                            }
                        }
                    }
                }
            }

            return response()->json([
                'status' => 200,
                'message' => 'Notas enviadas com sucesso!',
            ]);
        }
    }



    public function enviarNotificacao()
    {
        $user = auth()->user();

        if (!$user->can('create: notificacao')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "Enviar Notificações",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "encarregados" => Encarregado::where([
                ['shcools_id', '=', $this->escolarLogada()],
            ])->get(),

            "notificacaoEnviadas" => NotificacaoEncarregado::where([
                ['shcools_id', '=', $this->escolarLogada()],
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['tb_notificacoes_encarregados_notas.visto', '=', 'nao'],
            ])
                ->count(),
            "notificacaoReciclagem" => NotificacaoEncarregado::where([
                ['shcools_id', '=', $this->escolarLogada()],
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['tb_notificacoes_encarregados_notas.visto', '=', 'sim'],
            ])
                ->count(),

        ];

        return view('app.enviar-notificacao', $headers);
    }

    public function enviarNotificacaoPost(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('create: notificacao')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $validate = Validator::make($request->all(), [
            "encarregados" => 'required',
            "titulo" => 'required',
            "descricao" => 'required',
        ], [
            "encarregados.required" => "Campo Obrigatório",
            "titulo.required" => "Campo Obrigatório",
            "descricao.required" => "Campo Obrigatório",

        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        } else {

            foreach ($request->encarregados as $key) {
                $encarregado = Encarregado::findOrFail($key);
                if ($encarregado) {
                    $save = new NotificacaoEncarregado();
                    $save->turmas_id = NULL;
                    $save->estudantes_id = NULL;
                    $save->trimestres_id = NULL;
                    $save->encarregados_id = $encarregado->id;
                    $save->ano_lectivos_id = $this->anolectivoActivo();
                    $save->descricao = $request->descricao;
                    $save->shcools_id = $this->escolarLogada();
                    $save->titulo = $request->titulo;
                    $save->tipo = "notifcacao";
                    $save->save();
                }
            }
        }

        return response()->json([
            'status' => 200,
            'message' => 'Notificação enviada com sucesso!',
        ]);
    }

    public function enviarNotificacaoSmsPost(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('create: notificacao')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $validate = Validator::make($request->all(), [
            "encarregados" => 'required',
            "titulo" => 'required',
            "descricao" => 'required',
        ], [
            "encarregados.required" => "Campo Obrigatório",
            "titulo.required" => "Campo Obrigatório",
            "descricao.required" => "Campo Obrigatório",

        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        } else {

            foreach ($request->encarregados as $key) {
                $encarregado = Encarregado::findOrFail($key);
                if ($encarregado) {

                    $telefone_enviar = $encarregado->telefone;

                    $this->enviarSMS($telefone_enviar, "$request->titulo " . " $request->descricao");
                }
            }
        }

        return response()->json([
            'status' => 200,
            'message' => 'Notificação enviada com sucesso!',
        ]);
    }


    public function entradasNofificacao()
    {
        $user = auth()->user();

        if (!$user->can('read: notificacao')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "Notificações",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "notificacaoEnviadas" => NotificacaoEncarregado::where([
                ['shcools_id', '=', $this->escolarLogada()],
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['tb_notificacoes_encarregados_notas.visto', '=', 'nao'],
            ])
                ->count(),
            "notificacaoReciclagem" => NotificacaoEncarregado::where([
                ['shcools_id', '=', $this->escolarLogada()],
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['tb_notificacoes_encarregados_notas.visto', '=', 'sim'],
            ])
                ->count(),
        ];

        return view('app.entradas-notificacao', $headers);
    }

    public function enviadasNofificacao()
    {
        $user = auth()->user();

        if (!$user->can('read: notificacao')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "Notificações Enviadas",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "notificacao" => NotificacaoEncarregado::where([
                ['shcools_id', '=', $this->escolarLogada()],
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['tb_notificacoes_encarregados_notas.visto', '=', 'nao'],
            ])
                ->limit(6)
                ->get(),

            "notificacaoEnviadas" => NotificacaoEncarregado::where([
                ['shcools_id', '=', $this->escolarLogada()],
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['tb_notificacoes_encarregados_notas.visto', '=', 'nao'],
            ])
                ->count(),
            "notificacaoReciclagem" => NotificacaoEncarregado::where([
                ['shcools_id', '=', $this->escolarLogada()],
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['tb_notificacoes_encarregados_notas.visto', '=', 'sim'],
            ])
                ->count(),
        ];

        return view('app.enviadas-notificacao', $headers);
    }

    public function reciclagemNofificacao()
    {
        $user = auth()->user();

        if (!$user->can('read: notificacao')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }




        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "Reciclagem de Notificações",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "notificacao" => NotificacaoEncarregado::where([
                ['shcools_id', '=', $this->escolarLogada()],
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['tb_notificacoes_encarregados_notas.visto', '=', 'sim'],
            ])
                ->limit(20)
                ->get(),
            "notificacaoEnviadas" => NotificacaoEncarregado::where([
                ['shcools_id', '=', $this->escolarLogada()],
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['tb_notificacoes_encarregados_notas.visto', '=', 'nao'],
            ])
                ->count(),
            "notificacaoReciclagem" => NotificacaoEncarregado::where([
                ['shcools_id', '=', $this->escolarLogada()],
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['tb_notificacoes_encarregados_notas.visto', '=', 'sim'],
            ])
                ->count(),
        ];

        return view('app.reciclagem-notificacao', $headers);
    }

    public function lerNotifacacao($id)
    {
        $user = auth()->user();

        if (!$user->can('read: notificacao')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $notificacao = NotificacaoEncarregado::where('id', $id)
            ->with(['escola', 'encarregado'])
            ->first();

        $update = NotificacaoEncarregado::findOrFail($id);
        $update->visto = 'sim';
        $update->save();


        $notificacaoEnviadas = NotificacaoEncarregado::where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('visto', 'nao')
            ->count();

        $notificacaoReciclagem = NotificacaoEncarregado::where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('visto', 'sim')
            ->count();

        if ($notificacao->tipo == 'notas') {
            $estudantes = Estudante::findOrFail($notificacao->estudantes_id);

            $turma = Turma::findOrFail($notificacao->turmas_id);

            $totalDisciplinas = DisciplinaTurma::where('turmas_id', $turma->id)->count('id');

            $somaDasMediaTrimestral = NotaPauta::where('estudantes_id', $estudantes->id)
                ->where('controlo_trimestres_id', $notificacao->trimestres_id)
                ->where('ano_lectivos_id', $notificacao->ano_lectivos_id)
                ->where('turmas_id', $turma->id)
                ->sum('mt');

            $notas = NotaPauta::where('estudantes_id', $estudantes->id)
                ->where('controlo_trimestres_id', $notificacao->trimestres_id)
                ->where('ano_lectivos_id', $notificacao->ano_lectivos_id)
                ->where('turmas_id', $turma->id)
                ->with(['disciplina'])
                ->get();

            $headers = [
                "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

                'resultados' => $notas,
                'curso' => Curso::findOrFail($turma->cursos_id),
                'sala' => Sala::findOrFail($turma->salas_id),
                'classe' => Classe::findOrFail($turma->classes_id),
                'turno' => Turno::findOrFail($turma->turnos_id),
                'turma' => $turma,
                'estudante' => $estudantes,
                'mediaFinal' => $somaDasMediaTrimestral / $totalDisciplinas,
                'anoLectivo' => AnoLectivo::findOrFail($notificacao->ano_lectivos_id),
                'trimestre' => ControlePeriodico::findOrFail($notificacao->trimestres_id),
                "usuario" => User::findOrFail(Auth::user()->id),
                "notificacao" => $notificacao,

                "notificacaoEnviadas" => $notificacaoEnviadas,
                "notificacaoReciclagem" => $notificacaoReciclagem,
            ];
        } else {

            $headers = [
                "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

                "titulo" => "Notificações",
                "descricao" => env('APP_NAME'),
                "usuario" => User::findOrFail(Auth::user()->id),
                "notificacao" => $notificacao,

                "notificacaoEnviadas" => $notificacaoEnviadas,
                "notificacaoReciclagem" => $notificacaoReciclagem,
            ];
        }

        return view('app.ler-notificacao', $headers);
    }
}
