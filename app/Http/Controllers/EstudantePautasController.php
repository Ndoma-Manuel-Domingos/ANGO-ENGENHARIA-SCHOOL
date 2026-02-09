<?php

namespace App\Http\Controllers;

use App\Models\Director;
use App\Models\Shcool;
use App\Models\User;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\anolectivo\ControlePeriodico;
use App\Models\web\anolectivo\Trimestre;
use App\Models\web\calendarios\Matricula;
use App\Models\web\classes\Classe;
use App\Models\web\cursos\Curso;
use App\Models\web\cursos\DisciplinaCurso;
use App\Models\web\estudantes\Estudante;
use App\Models\web\salas\Sala;
use App\Models\web\turmas\DisciplinaTurma;
use App\Models\web\turmas\EstudantesTurma;
use App\Models\web\turmas\NotaPauta;
use App\Models\web\turmas\Turma;
use App\Models\web\turnos\Turno;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;

class EstudantePautasController extends Controller
{
    use TraitHelpers;

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function certificadoEstudantes(Request $request)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
        $estudante = Estudante::with(['provincia', 'municipio'])->findOrFail(Crypt::decrypt($request->id));

        if ($estudante->finalista == "N") {
            Alert::warning("Informação", "Estudante, Não é um finalista");
            return redirect()->route('shcools.mais-informacao-estudante', Crypt::encrypt($estudante->id));
        }

        if ($estudante->medio_tecnico == "Y") {
            $ano_actual = AnoLectivo::findOrFail($estudante->ano_lectivos_final_id);
            $ano = AnoLectivo::findOrFail($this->anolectivoAnteriorId($ano_actual->id));
        } else {
            $ano = AnoLectivo::findOrFail($estudante->ano_lectivos_final_id);
        }

        $turmasEstudante = EstudantesTurma::where([
            ['estudantes_id', '=', $estudante->id],
            ['ano_lectivos_id', '=', $estudante->ano_lectivos_final_id],
        ])->first();

        if (!$turmasEstudante) {
            return redirect()->route('web.declaracao-estudantes', Crypt::encrypt($request->id));
        }

        $turmas_estudante = EstudantesTurma::with(['turma.classe', 'turma.disciplinas'])->where([
            ['estudantes_id', '=', $estudante->id],
        ])->get();

        $turma = Turma::findOrFail($turmasEstudante->turmas_id);

        $matricula = Matricula::with(['classe'])->where('estudantes_id', $estudante->id)->where('ano_lectivos_id', $estudante->ano_lectivos_final_id)->first();

        $trimestre4 = ControlePeriodico::where('trimestre', '=', 'Geral')->first();



        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());
        $director = Director::where('level', '4')->where('instituicao_id', $escola->id)->first();

        $todos_anos_lectivos_estudante = Matricula::with(['ano_lectivo', 'classe'])->whereIn('estudantes_id', [$estudante->id])->get();
        $disciplinas_cursos = DisciplinaCurso::with(['disciplina'])->where('ano_lectivos_id', $estudante->ano_lectivos_final_id)->where('cursos_id', $turma->cursos_id)->where('shcools_id', $this->escolarLogada())->get();

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            'escola' =>  $escola,

            "turma" => $turma,
            "director" => $director,
            'curso' => Curso::findOrFail($turma->cursos_id),
            'sala' => Sala::findOrFail($turma->salas_id),
            'classe' => Classe::findOrFail($turma->classes_id),
            'turno' => Turno::findOrFail($turma->turnos_id),
            'anoLectivo' => $ano,
            "turmas_estudante" => $turmas_estudante,
            "estudante" => $estudante,
            "trimestre4" => $trimestre4,
            "matricula" => $matricula,

            "todos_anos_lectivos_estudante" => $todos_anos_lectivos_estudante,
            "disciplinas_cursos" => $disciplinas_cursos,
            "turmasEstudante" => $turmasEstudante,
        ];

        $orintacao = 'portrait';

        $pdf = \PDF::loadView('downloads.estudantes.certificado', $headers)->setPaper('A4', $orintacao);
        return $pdf->stream('certificado.pdf');
    }

    // mini pauta estuadntes
    public function miniPautaEstudantes($id)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
        $estudante = Estudante::findOrFail(Crypt::decrypt($id));

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        if ($escola->ensino->nome == "Ensino Superior") {
            $trimestres = ControlePeriodico::where('ensino_status', '2')->get();
        } else {
            $trimestres = ControlePeriodico::where('trimestre', '<>', 'Geral')->where('ensino_status', '1')->get();
        }

        $headers = [
            "escola" => $escola,
            "titulo" => "Resultado das Mini Pautas",
            "descricao" => env('APP_NAME'),
            'trimestres' => $trimestres,
            'ano_lectivos' => AnoLectivo::where('shcools_id', '=', $this->escolarLogada())->get(),
            'estudantes_id' => $estudante->id,
            "usuario" => User::findOrFail(Auth::user()->id),
        ];
        // venha
        return view('admin.estudantes.mini-pauta', $headers);
    }

    // pesquisar MIni PAutas para todas as turmas estudasnte particular
    public function pesquisarTurmaMiniPautaEstudante(Request $request)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
        $request->validate([
            "ano_lectivos_id" => 'required',
            "estudantes_id" => 'required',
            "trimestre_id" => 'required',
        ]);

        $estudante = Estudante::findOrFail($request->input('estudantes_id'));

        $turmasEstudante = EstudantesTurma::where('estudantes_id', $estudante->id)
            ->where('ano_lectivos_id', $request->ano_lectivos_id)
            ->first();

        if (!$turmasEstudante) {
            Alert::warning("Informação", "Estudante sem turmas ou sem dados no ano lectivo selecionado");
            return redirect()->back();
        }

        $estudantes = Estudante::findOrFail($request->input('estudantes_id'));


        $matricula = Matricula::where('estudantes_id', $estudantes->id)
            ->where('ano_lectivos_id', $request->input('ano_lectivos_id'))
            ->where('status_matricula', 'confirmado')
            ->where('shcools_id', $this->escolarLogada())
            ->first();

        if (!$matricula) {
            Alert::warning("Informação", "Estudante sem matricula para este ano lectivo selecionado");
            return redirect()->back();
        }

        $turmasEstudante = EstudantesTurma::where('estudantes_id', $estudante->id)
            ->where('ano_lectivos_id', $request->ano_lectivos_id)
            ->first();

        if (!$turmasEstudante) {
            Alert::warning("Informação", "Estudante sem turmas ou sem dados no ano lectivo selecionado");
            return redirect()->back();
        }

        $turma = Turma::where('turnos_id', $matricula->turnos_id)
            ->where('cursos_id', $matricula->cursos_id)
            ->where('classes_id', $matricula->classes_id)
            ->where('ano_lectivos_id', $matricula->ano_lectivos_id)
            ->first();

        $totalDisciplinas = DisciplinaTurma::where('turmas_id', $turma->id)->count('id');

        $somaDasMediaTrimestral = NotaPauta::where('estudantes_id', $request->estudantes_id)
            ->where('controlo_trimestres_id', $request->trimestre_id)
            ->where('ano_lectivos_id', $request->ano_lectivos_id)
            ->where('turmas_id', $turma->id)
            ->sum('mt');

        $notas = NotaPauta::where('estudantes_id', $request->estudantes_id)
            ->where('controlo_trimestres_id', $request->trimestre_id)
            ->where('ano_lectivos_id', $request->ano_lectivos_id)
            ->where('turmas_id', $turma->id)
            ->with(['disciplina'])
            ->get();

        if (!$notas) {
            return response()->json([
                'status' => 300,
                'errors' => "Sem Exito a Pesquisa!, tenta uma outra pesquisa!",
            ]);
        }

        if ($somaDasMediaTrimestral == 0 || $totalDisciplinas == 0) {
            $media = 0;
        } else {
            $media = $somaDasMediaTrimestral / $totalDisciplinas;
        }

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        if ($escola->ensino->nome == "Ensino Superior") {
            $trimestres = ControlePeriodico::where('ensino_status', '2')->get();
        } else {
            $trimestres = ControlePeriodico::where('ensino_status', '1')->get();
        }

        $headers = [
            "escola" => $escola,
            "titulo" => "Resultado das Mini Pautas",
            "descricao" => env('APP_NAME'),
            'resultados' => $notas,
            'curso' => Curso::findOrFail($turma->cursos_id),
            'sala' => Sala::findOrFail($turma->salas_id),
            'classe' => Classe::findOrFail($turma->classes_id),
            'turno' => Turno::findOrFail($turma->turnos_id),
            'turma' => $turma,
            'estudante' => $estudantes,
            'mediaFinal' => $media,
            'anoLectivo' => AnoLectivo::findOrFail($request->input('ano_lectivos_id')),
            'trimestre' => ControlePeriodico::findOrFail($request->input('trimestre_id')),
            "usuario" => User::findOrFail(Auth::user()->id),
            'trimestres' => $trimestres,
            'ano_lectivos' => AnoLectivo::where('shcools_id', $this->escolarLogada())->get(),
            'estudantes_id' => $estudante->id,
        ];
        // venha2
        return view('admin.estudantes.mini-pauta', $headers);
    }


    // pautas estudantes
    public function pautaEstudantes($id)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
        $estudante = Estudante::findOrFail(Crypt::decrypt($id));




        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            'ano_lectivos' => AnoLectivo::where([
                ['shcools_id', '=', $this->escolarLogada()]
            ])->get(),
            'anoLectivo' => AnoLectivo::findOrFail($this->anolectivoActivo()),
            'estudantes_id' => $estudante->id,
            "usuario" => User::findOrFail(Auth::user()->id),
            "totalDisciplinas" => 0,
            "somaMFD" => 0,
        ];

        return view('admin.estudantes.pauta', $headers);
    }


    public function mapaAproveitamentoGeralEstudante(Request $request)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO

        $estudante = Estudante::findOrFail($request->input('estudantes_id'));
        $ano = AnoLectivo::findOrFail($request->input('ano_lectivos_id'));
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        $turmasEstudante = EstudantesTurma::where('estudantes_id', $estudante->id)
            ->where('ano_lectivos_id', $ano->id)
            ->first();

        if (!$turmasEstudante) {
            Alert::warning("Informação", "Estudante sem turmas ou sem dados no ano lectivo selecionado");
            return redirect()->back();
        }

        $turma = Turma::findOrFail($turmasEstudante->turmas_id);
        $totalDisciplinas = DisciplinaTurma::where('turmas_id', $turma->id)->count('id');

        if ($escola->ensino->nome == "Ensino Superior") {
            $simestre1 = ControlePeriodico::where('trimestre', 'Iª Simestre')->first();
            $simestre2 = ControlePeriodico::where('trimestre', 'IIª Simestre')->first();
            $anual = ControlePeriodico::where('trimestre', 'Anual')->first();

            $notasSomaMdf = NotaPauta::where('estudantes_id', $estudante->id)->whereIn('controlo_trimestres_id', [$simestre1->id, $simestre2->id, $anual->id])->where('ano_lectivos_id',  $this->anolectivoActivo())->sum('resultado_final');
            $notasSomaNe = NotaPauta::where('estudantes_id', $estudante->id)->whereIn('controlo_trimestres_id', [$simestre1->id, $simestre2->id, $anual->id])->where('ano_lectivos_id',  $this->anolectivoActivo())->sum('resultado_final');

            $notas = NotaPauta::where('estudantes_id', $estudante->id)
                ->where('ano_lectivos_id', $ano->id)
                ->with(['disciplina'])
                ->get();
        } else {
            $trimestre1 = ControlePeriodico::where('trimestre', 'Iª Trimestre')->first();
            $trimestre2 = ControlePeriodico::where('trimestre', 'IIª Trimestre')->first();
            $trimestre3 = ControlePeriodico::where('trimestre', 'IIIª Trimestre')->first();
            $trimestre4 = ControlePeriodico::where('trimestre', 'Geral')->first();

            $notasSomaMdf = NotaPauta::where('estudantes_id', $estudante->id)
                ->where('controlo_trimestres_id', $trimestre4->id)
                ->where('ano_lectivos_id', $ano->id)
                ->sum('mfd');

            $notasSomaNe = NotaPauta::where('estudantes_id', $estudante->id)
                ->where('controlo_trimestres_id', $trimestre4->id)
                ->where('ano_lectivos_id', $ano->id)
                ->sum('ne');

            $notas = NotaPauta::where('estudantes_id', $estudante->id)
                ->where('controlo_trimestres_id', $trimestre4->id)
                ->where('ano_lectivos_id', $ano->id)
                ->with(['disciplina'])
                ->get();
        }


        $headers = [
            "escola" => $escola,
            "titulo" => "Notas do Estudante",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "estudantes" => $estudante,
            "turma" => $turma,
            "turmaDisciplinas" => DisciplinaTurma::where('turmas_id', $turma->id)
                ->with(['disciplina', 'turma'])
                ->get(),
            'curso' => Curso::findOrFail($turma->cursos_id),
            'sala' => Sala::findOrFail($turma->salas_id),
            'classe' => Classe::findOrFail($turma->classes_id),
            'turno' => Turno::findOrFail($turma->turnos_id),
            'anoLectivo' => AnoLectivo::findOrFail($ano->id),
            'ano_lectivos' => AnoLectivo::where('shcools_id', $this->escolarLogada())->get(),
            'estudantes_id' => $estudante->id,
            "notas" => $notas,
            "somaMFD" => $notasSomaMdf ?? 0,
            "somaNE" => $notasSomaNe ?? 0,
            'totalDisciplinas' => $totalDisciplinas,
            'trimestre1' => $trimestre1 ?? 0,
            'trimestre2' => $trimestre2 ?? 0,
            'trimestre3' => $trimestre3 ?? 0,
            'trimestre4' => $trimestre4 ?? 0,

            "requests" => $request->all('ano_lectivos_id')
        ];


        return view('admin.estudantes.pauta', $headers);
    }

    public function mapaAproveitamentoGeralEstudanteCreate(Request $request)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
        $request->validate([
            "ano_lectivos_id" => 'required',
            "estudantes_id" => 'required',
        ], [
            "ano_lectivos_id.required" => "Campo Obrigatório",
            "estudantes_id.required" => "Campo Obrigatório",
        ]);

        $estudante = Estudante::findOrFail($request->input('estudantes_id'));
        $ano = AnoLectivo::findOrFail($request->input('ano_lectivos_id'));

        $turmasEstudante = EstudantesTurma::where([
            ['estudantes_id', '=', $estudante->id],
            ['ano_lectivos_id', '=', $ano->id],
        ])->first();

        if (!$turmasEstudante) {
            Alert::warning("Informação", "Estudante sem turmas ou sem dados no ano lectivo selecionado");
            return redirect()->back();
        }


        $turma = Turma::findOrFail($turmasEstudante->turmas_id);
        $totalDisciplinas = DisciplinaTurma::where([
            ['turmas_id', '=', $turma->id]
        ])->count('id');

        $trimestre1 = ControlePeriodico::where('trimestre', '=', 'Iª Trimestre')->first();

        $trimestre2 = ControlePeriodico::where('trimestre', '=', 'IIª Trimestre')->first();

        $trimestre3 = ControlePeriodico::where('trimestre', '=', 'IIIª Trimestre')->first();

        $trimestre4 = ControlePeriodico::where('trimestre', '=', 'Geral')->first();

        $notasSomaMdf = NotaPauta::where([
            ['estudantes_id', '=', $estudante->id],
            ['controlo_trimestres_id', '=', $trimestre4->id],
            ['ano_lectivos_id', '=', $ano->id],
        ])->sum('mfd');

        $notasSomaNe = NotaPauta::where([
            ['estudantes_id', '=', $estudante->id],
            ['controlo_trimestres_id', '=', $trimestre4->id],
            ['ano_lectivos_id', '=', $ano->id],
        ])->sum('ne');

        $notas = NotaPauta::where([
            ['estudantes_id', '=', $estudante->id],
            ['controlo_trimestres_id', '=', $trimestre4->id],
            ['ano_lectivos_id', '=', $ano->id],
        ])
            ->join('tb_disciplinas', 'tb_notas_pautas.disciplinas_id', '=', 'tb_disciplinas.id')
            ->select('tb_notas_pautas.mfd', 'tb_notas_pautas.ne', 'tb_disciplinas.disciplina', 'tb_notas_pautas.id')
            ->get();



        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "estudantes" => $estudante,
            "turma" => $turma,
            "turmaDisciplinas" => DisciplinaTurma::where([
                ['turmas_id', '=', $turma->id],
            ])
                ->join('tb_disciplinas', 'tb_discplinas_turmas.disciplinas_id', '=', 'tb_disciplinas.id')
                ->join('tb_turmas', 'tb_discplinas_turmas.turmas_id', '=', 'tb_turmas.id')
                ->select('tb_disciplinas.id')
                ->get(),
            'curso' => Curso::findOrFail($turma->cursos_id),
            'sala' => Sala::findOrFail($turma->salas_id),
            'classe' => Classe::findOrFail($turma->classes_id),
            'turno' => Turno::findOrFail($turma->turnos_id),
            'anoLectivo' => AnoLectivo::findOrFail($ano->id),
            'ano_lectivos' => AnoLectivo::where([
                ['shcools_id', '=', $this->escolarLogada()]
            ])->get(),
            'estudantes_id' => $estudante->id,
            "notas" => $notas,
            "somaMFD" => $notasSomaMdf,
            "somaNE" => $notasSomaNe,
            'totalDisciplinas' => $totalDisciplinas,
            'trimestre1' => $trimestre1,
            'trimestre2' => $trimestre2,
            'trimestre3' => $trimestre3,
            'trimestre4' => $trimestre4,
        ];

        return view('admin.estudantes.pauta', $headers);
    }
}
