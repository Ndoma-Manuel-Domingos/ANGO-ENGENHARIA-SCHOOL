<?php

namespace App\Http\Controllers;

use App\Exports\FuncionariosCargoExport;
use App\Exports\FuncionariosDepartamentoExport;
use App\Exports\FuncionariosExport;
use App\Exports\ServicoExport;
use App\Exports\TurmaExport;
use App\Models\AnoLectivoGlobal;
use App\Models\Cargo;
use App\Models\Categoria;
use App\Models\Departamento;
use App\Models\DireccaoMunicipal;
use App\Models\DireccaoProvincia;
use App\Models\Distrito;
use App\Models\Escolaridade;
use App\Models\Especialidade;
use App\Models\FormacaoAcedemico;
use App\Models\Municipio;
use App\Models\Professor;
use App\Models\Provincia;
use App\Models\Shcool;
use App\Models\Universidade;
use App\Models\User;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\calendarios\Matricula;
use App\Models\web\calendarios\Mes;
use App\Models\web\calendarios\Semana;
use App\Models\web\calendarios\Servico;
use App\Models\web\calendarios\ServicoTurma;
use App\Models\web\calendarios\Tempo;
use App\Models\web\classes\Classe;
use App\Models\web\cursos\Curso;
use App\Models\web\cursos\DisciplinaCurso;
use App\Models\web\encarregados\Encarregado;
use App\Models\web\estudantes\CartaoEstudante;
use App\Models\web\estudantes\Estudante;
use App\Models\web\estudantes\EstudanteDesconto;
use App\Models\web\funcionarios\Funcionarios;
use App\Models\web\funcionarios\FuncionariosControto;
use App\Models\web\salas\Sala;
use App\Models\web\turmas\DisciplinaTurma;
use App\Models\web\turmas\EstudantesTurma;
use App\Models\web\turmas\FuncionariosTurma;
use App\Models\web\turmas\Turma;
use App\Models\web\turnos\Turno;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;


use App\Models\web\turmas\Estagiario;
use App\Models\web\turmas\Bolsa;
use App\Models\web\turmas\Bolseiro;
use App\Models\web\turmas\Desconto;
use App\Models\web\turmas\InstituicaoEducacional;
use Illuminate\Support\Facades\File;

class PrinterController extends Controller
{
    use TraitHelpers;
    // TODAS AS IMPRESSOES


    public function __construct()
    {
        $this->middleware('auth');
    }

    // retificado
    public function turmasImprimir()
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            
            "titulo" => "LISTA DAS TURMAS",
            "escola" => Shcool::find($this->escolarLogada()),
            "turmas" => Turma::where('ano_lectivos_id', $this->anolectivoActivo())
                ->with(['escola', 'anolectivo', 'turno', 'classe', 'sala', 'curso'])
                ->get(),
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-turmas', $headers);
        return $pdf->stream('lista-turmas.pdf');
    }

    public function turmasExcel()
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        return Excel::download(new TurmaExport, 'turmas.xlsx');
    }

    public function calendariosExcel()
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        return Excel::download(new ServicoExport, 'servicos.xlsx');
    }

    public function turmaEstudantesImprimir($id)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        // CONTROLAR A SESSÃo INICIALIZADA OU NAO

        $turma = Turma::findOrFail(Crypt::decrypt($id));
        $classe = Classe::findOrFail($turma->classes_id);
        $curso = Curso::findOrFail($turma->cursos_id);

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            
            "titulo" => "LISTA DOS ESTUDANTES DA TURMA {$turma->turma} --- CURSO {$curso->curso} {$classe->classes}",
            "escola" => Shcool::find($this->escolarLogada()),
            "turma" => $turma,
            "estudantes" => EstudantesTurma::where([
                ['tb_turmas_estudantes.status', '=', 'Activo'],
                ['tb_turmas_estudantes.turmas_id', '=', $turma->id],
            ])
                ->join('tb_estudantes', 'tb_turmas_estudantes.estudantes_id', '=', 'tb_estudantes.id')
                ->get()
        ];


        $pdf = \PDF::loadView('downloads.relatorios.lista-turma-estudantes', $headers);
        return $pdf->stream('lista-turma-estudantes.pdf');
    }

    public function turmaProfessoresImprimir($id)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        // CONTROLAR A SESSÃo INICIALIZADA OU NAO

        $turma = Turma::findOrFail($id);
        $classe = Classe::findOrFail($turma->classes_id);
        $curso = Curso::findOrFail($turma->cursos_id);

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            
            "titulo" => "LISTA DOS PROFESSORES DA TURMA {$turma->turma} --- CURSO {$curso->curso} {$classe->classes}",
            "escola" => Shcool::find($this->escolarLogada()),
            "turma" => $turma,
            "funcionarios" => FuncionariosTurma::where([
                ['tb_turmas_funcionarios.turmas_id', '=', $turma->id],
            ])
                ->join('tb_professores', 'tb_turmas_funcionarios.funcionarios_id', '=', 'tb_professores.id')
                ->get()
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-turma-funcionarios', $headers);
        return $pdf->stream('lista-turma-funcionarios.pdf');
    }


    // retificado
    public function disciplinasCursosImprimir($id)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
        $curso = Curso::findOrFail($id);

        $disciplinas = DisciplinaCurso::where('cursos_id', $curso->id)
                ->where('shcools_id', $this->escolarLogada())
                ->where('ano_lectivos_id', $this->anolectivoActivo())
                ->with(['categoria', 'escola', 'ano', 'curso', 'disciplina'])
            ->get()
            ->sortBy(function($item) {
                return $item->curso->curso;
            });

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            
            "titulo" => "LISTA DAS DISCIPLINAS DO CURSO DE {$curso->curso}",
            "escola" => Shcool::find($this->escolarLogada()),
            "curso" => $curso,
            "disciplinas" => $disciplinas,
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-disciplinas-curso', $headers);
        return $pdf->stream('lista-disciplinas-curso.pdf');
    }


    //retificado
    public function turmaDisciplinasImprimir($id)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO

        $turma = Turma::findOrFail(Crypt::decrypt($id));
        $classe = Classe::findOrFail($turma->classes_id);
        $curso = Curso::findOrFail($turma->cursos_id);
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            
            "titulo" => "LISTA DAS DISCIPLINAS DA TURMA {$turma->turma} --- CURSO {$curso->curso} {$classe->classes}",
            "escola" => Shcool::find($this->escolarLogada()),
            "turma" => $turma,

            "curso" => $curso,
            "classe" => $classe,
            "turno" => Turno::findOrFail($turma->turnos_id),
            "sala" => Sala::findOrFail($turma->salas_id),
            "ano" => AnoLectivo::findOrFail($turma->ano_lectivos_id),

            "disciplinas" => DisciplinaTurma::where([
                ['status', 'Activo'],
                ['turmas_id', $turma->id],
            ])
                ->with(['disciplina'])
                ->get()
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-disciplinas-turma', $headers);
        return $pdf->stream('lista-disciplinas-turma.pdf');
    }

    //retificado
    public function turmaHorarioImprimir($id)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        $turma = Turma::findOrFail(Crypt::decrypt($id));
        $classe = Classe::findOrFail($turma->classes_id);
        $curso = Curso::findOrFail($turma->cursos_id);

        $tempos = Tempo::get();
        $semanas = Semana::where('status', 'activo')->get();

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);
        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            
            "titulo" => "LISTA DO HORÁRIO DA TURMA {$turma->turma} --- CURSO {$curso->curso} {$classe->classes}",
            "escola" => Shcool::find($this->escolarLogada()),
            "turma" => $turma,
            "tempos" => $tempos,
            "semanas" => $semanas,

            "curso" => $curso,
            "classe" => $classe,
            "turno" => Turno::findOrFail($turma->turnos_id),
            "sala" => Sala::findOrFail($turma->salas_id),
            "ano" => AnoLectivo::findOrFail($turma->ano_lectivos_id),
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-horarios-turma', $headers)
            ->setPaper('A4', 'landscape');
        return $pdf->stream('lista-horarios-turma.pdf');
    }


    //retificado
    public function turmaServicoImprimir($id)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
        $turma = Turma::findOrFail(Crypt::decrypt($id));
        $classe = Classe::findOrFail($turma->classes_id);
        $curso = Curso::findOrFail($turma->cursos_id);

        $servicos_turma = ServicoTurma::with(['turma', 'servico'])->where('turmas_id', $turma->id)->get();

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            
            "titulo" => "LISTA DOS SERVIÇOS DA TURMA {$turma->turma} --- CURSO {$curso->curso} {$classe->classes}",
            "escola" => Shcool::find($this->escolarLogada()),
            "turma" => $turma,

            "curso" => $curso,
            "classe" => $classe,
            "turno" => Turno::findOrFail($turma->turnos_id),
            "sala" => Sala::findOrFail($turma->salas_id),
            "ano" => AnoLectivo::findOrFail($turma->ano_lectivos_id),

            "servicos" => $servicos_turma,
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-servicos-turma', $headers)
            ->setPaper('A4', 'landscape');
        return $pdf->stream('lista-servicos-turma.pdf');
    }

    // retificado
    public function downloadEstudantesTurmas($id)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor
        $turma = Turma::findOrFail(Crypt::decrypt($id));

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "titulo" => "Relatório de Estudantes",
            "estudantes" => EstudantesTurma::with(["estudante", "turma"])->where('turmas_id', $turma->id)
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->get()
            ->sortBy(function ($estudante) {
                return $estudante->estudante->nome ?? '';
            }),
            "curso" => Curso::findOrFail($turma->cursos_id),
            "classe" => Classe::findOrFail($turma->classes_id),
            "turno" => Turno::findOrFail($turma->turnos_id),
            "sala" => Sala::findOrFail($turma->salas_id),
            "ano" => AnoLectivo::findOrFail($this->anolectivoActivo()),
            "turma" => $turma,
        ];

        $pdf = \PDF::loadView('downloads.turmas.ficha-estudantes', $headers)->setPaper('A4', 'portrait');
        return $pdf->stream('turma.ficha-estudantes.pdf');
    }

    // retificado
    public function downloadEstudantesTurmasGeneroNascimento($id)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        $turma = Turma::findOrFail(Crypt::decrypt($id));
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "titulo" => "Relatório de Estudantes por Gênero e Data de Nascimento",
            "estudantes" => EstudantesTurma::with(["estudante", "turma"])
                ->where('turmas_id', $turma->id)
                ->where('ano_lectivos_id', $this->anolectivoActivo())
                ->get()
            ->sortBy(function ($estudante) {
                return $estudante->estudante->nome ?? '';
            }),

            "curso" => Curso::findOrFail($turma->cursos_id),
            "classe" => Classe::findOrFail($turma->classes_id),
            "turno" => Turno::findOrFail($turma->turnos_id),
            "sala" => Sala::findOrFail($turma->salas_id),
            "ano" => AnoLectivo::findOrFail($this->anolectivoActivo()),
            "turma" => $turma,
        ];

        $pdf = \PDF::loadView('downloads.turmas.ficha-estudantes-genero-nascimento', $headers)->setPaper('A4', 'portrait');
        return $pdf->stream('turma.ficha-estudantes-genero-nascimento.pdf');
    }

    // retificado
    public function downloadProfessoresTurmas($id)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        $turma = Turma::findOrFail(Crypt::decrypt($id));
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "titulo" => "Relatório dos Professores",
            

            "funcionarios" => FuncionariosTurma::with(["professor", "turma", "disciplina"])
                ->where('turmas_id', $turma->id)
                ->get()
                ->sortBy(function ($funcionarioTurma) {
                    return $funcionarioTurma->professor->nome ?? '';
                }),

            "curso" => Curso::findOrFail($turma->cursos_id),
            "classe" => Classe::findOrFail($turma->classes_id),
            "turno" => Turno::findOrFail($turma->turnos_id),
            "sala" => Sala::findOrFail($turma->salas_id),
            "ano" => AnoLectivo::findOrFail($this->anolectivoActivo()),
            "turma" => $turma,
        ];

        $pdf = \PDF::loadView('downloads.turmas.ficha-professores', $headers)->setPaper('A4', 'portrait');
        return $pdf->stream('turma.ficha-professores.pdf');
    }

    // retificado
    public function downloadMatriculasTurmas($id)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        $turma = Turma::findOrFail(Crypt::decrypt($id));

        $curso = Curso::findOrFail($turma->cursos_id);
        $classe = Classe::findOrFail($turma->classes_id);
        $turno = Turno::findOrFail($turma->turnos_id);

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "titulo" => "Relatório de Estudantes Matriculados",
            

            "matriculas" => Matricula::with(['estudante', 'ano_lectivo'])
                ->where('ano_lectivos_id', $this->anolectivoActivo())
                ->where('cursos_id', $curso->id)
                ->where('classes_id', $classe->id)
                ->where('turnos_id', $turno->id)
                ->where('tipo', 'matricula')
                ->get()
                ->sortBy(function ($matricula) {
                    return $matricula->estudante->nome ?? '';
                }),

            "curso" => $curso,
            "classe" => $classe,
            "turno" => $turno,
            "sala" => Sala::findOrFail($turma->salas_id),
            "ano" => AnoLectivo::findOrFail($this->anolectivoActivo()),
            "turma" => $turma,
        ];

        $pdf = \PDF::loadView('downloads.turmas.ficha-estudantes-matriculados', $headers)->setPaper('A4', 'portrait');
        return $pdf->stream('turma.ficha-estudantes-matriculados.pdf');
    }

    // retificado
    public function downloadConfirmacoesTurmas($id)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        $turma = Turma::findOrFail(Crypt::decrypt($id));

        $curso = Curso::findOrFail($turma->cursos_id);
        $classe = Classe::findOrFail($turma->classes_id);
        $turno = Turno::findOrFail($turma->turnos_id);

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "titulo" => "Relatório de Estudantes Confirmados",
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            

            "matriculas" => Matricula::with(['estudante', 'ano_lectivo'])
                ->where('ano_lectivos_id', $this->anolectivoActivo())
                ->where('cursos_id', $curso->id)
                ->where('classes_id', $classe->id)
                ->where('turnos_id', $turno->id)
                ->where('tipo', 'confirmacao')
                ->get()
                ->sortBy(function ($matricula) {
                    return $matricula->estudante->nome ?? '';
                }),

            "curso" => $curso,
            "classe" => $classe,
            "turno" => $turno,
            "sala" => Sala::findOrFail($turma->salas_id),
            "ano" => AnoLectivo::findOrFail($this->anolectivoActivo()),
            "turma" => $turma,
        ];

        $pdf = \PDF::loadView('downloads.turmas.ficha-estudantes-confirmados', $headers)->setPaper('A4', 'portrait');
        return $pdf->stream('turma.ficha-estudantes-confirmados.pdf');
    }

    // retificado
    public function downloadControloPropinasTurmas($id)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        $turma = Turma::findOrFail(Crypt::decrypt($id));
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());
            
        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);
    
        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "titulo" => "Relatório de controle de propinas",
            
            "estudantes" => EstudantesTurma::where("turmas_id", $turma->id)
                ->with(['estudante'])
                ->where('ano_lectivos_id', $this->anolectivoActivo())
                ->get()
                ->sortBy(function ($estudante) {
                    return $estudante->estudante->nome ?? '';
                }),

            "servico" => Servico::where('shcools_id', $this->escolarLogada())->where('servico', 'Propinas')->first(),
            "meses" => Mes::select('id', 'meses', 'abreviacao', 'abreviacao2')->get(),
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "curso" => Curso::findOrFail($turma->cursos_id),
            "classe" => Classe::findOrFail($turma->classes_id),
            "turno" => Turno::findOrFail($turma->turnos_id),
            "sala" => Sala::findOrFail($turma->salas_id),
            "ano" => AnoLectivo::findOrFail($this->anolectivoActivo()),
            "turma" => $turma,
        ];

        $pdf = \PDF::loadView('downloads.turmas.ficha-controlo-propinas', $headers)->setPaper('A4', 'landscape');
        return $pdf->stream("ficha-de-controlo-propinas-{$turma->turma}.pdf");
        // rapido
    }


    // listagem de devedores
    public function listagemServicosImprimir(Request $request)
    {

        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        $servicosTurmas = ServicoTurma::when($request->ano_lectivos_id, function ($query, $value) {
            $query->where('ano_lectivos_id', $value);
        })->when($request->turma_id, function ($query, $value) {
            $query->where('turmas_id', $value);
        })->when($request->servico_id, function ($query, $value) {
            $query->where('servicos_id', $value);
        })->where([
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['shcools_id', '=', $this->escolarLogada()]
        ])
            ->with(['ano_lectivo', 'turma.classe', 'turma.curso', 'servico'])
            ->get();



        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            
            "titulo" => "LISTAGEM DOS SERVIÇOS",

            "servicosTurmas" => $servicosTurmas,

            "ano_lectivo" => AnoLectivo::find($request->ano_lectivos_id),
            "servico" => Servico::find($request->servico_id),
            "turma" => Turma::find($request->turma_id),

            "requests" => $request->all('ano_lectivos_id', 'servico_id', 'turma_id')
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-servicos-pdf', $headers)->setPaper('A4', 'landscape');
        return $pdf->stream('lista-estudantes-devedores.pdf');
    }

    //imprimir servicos
    // retificado
    public function calendariosImprimir()
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "titulo" => "LISTA DOS SERIVIÇOS",
            "servicos" => Servico::where('shcools_id', $this->escolarLogada())->get(),
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-servicos', $headers);
        return $pdf->stream('lista-servicos.pdf');
    }


    /***
#########################################################################################
    MUNICIPAL START
#########################################################################################
     */
    public function funcionariosImprimirMunicipalPDF(Request $request)
    {

        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        if ($request->instituicao == 1) {
            $instituicaos = NULL;
        }
        if ($request->instituicao == 2) {
            $instituicaos = DireccaoProvincia::findOrFail(Auth::user()->shcools_id);
        }
        if ($request->instituicao == 3) {
            $instituicaos = DireccaoMunicipal::findOrFail(Auth::user()->shcools_id);
        }
        if ($request->instituicao == 4) {
            $instituicaos = Shcool::findOrFail(Auth::user()->shcools_id);
        }

        $universidade_id = $request->universidade_id;
        $escolaridade_id = $request->escolaridade_id;
        $formacao_id = $request->formacao_id;
        $especialidade_id = $request->especialidade_id;
        $categora_id = $request->categora_id;

        $funcionarios = Funcionarios::where('level', $request->instituicao)->with('academico.especialidade', 'academico.categoria', 'academico.escolaridade', 'academico.universidade', 'nacionalidade', 'provincia', 'municipio', 'distrito')
            ->whereHas('academico', function ($query) use ($universidade_id, $escolaridade_id, $formacao_id, $especialidade_id, $categora_id) {
                $query->when($universidade_id, function ($query) use ($universidade_id) {
                    $query->where('universidade_id', $universidade_id);
                });

                $query->when($escolaridade_id, function ($query) use ($escolaridade_id) {
                    $query->where('escolaridade_id', $escolaridade_id);
                });

                $query->when($formacao_id, function ($query) use ($formacao_id) {
                    $query->where('formacao_academica_id', $formacao_id);
                });

                $query->when($especialidade_id, function ($query) use ($especialidade_id) {
                    $query->where('especialidade_id', $especialidade_id);
                });

                $query->when($categora_id, function ($query) use ($categora_id) {
                    $query->where('categoria_id', $categora_id);
                });
            })
            ->when($request->status, function ($query, $value) {
                $query->where('status', $value);
            })
            ->where('shcools_id', Auth::user()->shcools_id)
            ->get();


        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        #voltar
        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "titulo" => "LISTA DOS FUNCIONÁRIOS",
            "instituicao" => $instituicaos,
            "funcionarios" => $funcionarios,
            "especialidades" => Especialidade::find($especialidade_id),
            "categorias" => Categoria::find($categora_id),
            "universidades" => Universidade::find($universidade_id),
            "escolaridade" => Escolaridade::find($escolaridade_id),
            "formacao_academicos" => FormacaoAcedemico::find($formacao_id),
            "status" => $request->status
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-funcionarios-municipal', $headers)->setPaper('A4', 'landscape');;
        return $pdf->stream('lista-funcionarios.pdf');
    }

    public function funcionariosImprimirMunicipalEXCEL(Request $request)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        return Excel::download(new FuncionariosExport($request->instituicao, $request->universidade_id, $request->escolaridade_id, $request->formacao_id, $request->especialidade_id, $request->categora_id, $request->status), 'funcionarios.xlsx');
    }


    public function funcionariosImprimirDepartamentoMunicipalPDF(Request $request)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        $user = auth()->user();

        if ($request->instituicao == 1) {
            $instituicaos = NULL;
        }
        if ($request->instituicao == 2) {
            $instituicaos = DireccaoProvincia::findOrFail(Auth::user()->shcools_id);
        }
        if ($request->instituicao == 3) {
            $instituicaos = DireccaoMunicipal::findOrFail(Auth::user()->shcools_id);
        }
        if ($request->instituicao == 4) {
            $instituicaos = Shcool::findOrFail(Auth::user()->shcools_id);
        }

        $direccao = DireccaoMunicipal::findOrFail($user->shcools_id);

        $tempo_trabalho = $request->tempo_trabalho;
        $id = $request->departamento_id;

        $funcionarios = Funcionarios::with('academico.especialidade', 'academico.categoria', 'academico.escolaridade', 'academico.universidade', 'nacionalidade', 'provincia', 'municipio', 'distrito', 'contrato.departamento', 'contrato.cargos')
            ->whereHas('academico', function ($query) use ($tempo_trabalho) {
                $query->when($tempo_trabalho, function ($query) use ($tempo_trabalho) {
                    $query->where('ano_trabalho', '=', $tempo_trabalho);
                });
            })
            ->whereHas('contrato', function ($query) use ($id) {
                $query->when($id, function ($query) use ($id) {
                    $query->where('departamento_id', $id);
                });
            })
            ->when($request->status, function ($query, $value) {
                $query->where('status', $value);
            })
            ->when($request->genero, function ($query, $value) {
                $query->where('genero', $value);
            })
            ->where('level', $request->instituicao)->where([
                ['shcools_id', '=', $user->shcools_id],
            ])
            ->orderBy('created_at', 'asc')
            ->get();

        $departamento = Departamento::find($id);

        $headers = [
            "titulo" => "LISTA DOS FUNCIONÁRIOS POR DEPARTAMENTOS",
            "descricao" => env('APP_NAME'),
            "funcionarios" => $funcionarios,
            "usuario" => User::findOrFail(Auth::user()->id),
            "departamento" => $departamento,
            "status" => $request->status,
            "genero" => $request->genero,
            "tempo_trabalho" => $request->tempo_trabalho,
            "instituicao" => $instituicaos
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-funcionarios-departamento-municipal', $headers)->setPaper('A4', 'landscape');;
        return $pdf->stream('lista-funcionarios-departamento-municipal.pdf');
    }

    public function funcionariosImprimirDepartamentoMunicipalEXCEL(Request $request)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        return Excel::download(new FuncionariosDepartamentoExport($request->instituicao, $request->tempo_trabalho, $request->genero, $request->status, $request->departamento_id), 'funcionarios-departamentos.xlsx');
    }

    public function funcionariosImprimirCargoMunicipalPDF(Request $request)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        $user = auth()->user();

        if ($request->instituicao == 1) {
            $instituicaos = NULL;
        }
        if ($request->instituicao == 2) {
            $instituicaos = DireccaoProvincia::findOrFail(Auth::user()->shcools_id);
        }
        if ($request->instituicao == 3) {
            $instituicaos = DireccaoMunicipal::findOrFail(Auth::user()->shcools_id);
        }
        if ($request->instituicao == 4) {
            $instituicaos = Shcool::findOrFail(Auth::user()->shcools_id);
        }

        $direccao = DireccaoMunicipal::findOrFail($user->shcools_id);

        $tempo_trabalho = $request->tempo_trabalho;
        $id = $request->cargo_id;

        $funcionarios = Funcionarios::with('academico.especialidade', 'academico.categoria', 'academico.escolaridade', 'academico.universidade', 'nacionalidade', 'provincia', 'municipio', 'distrito', 'contrato.departamento', 'contrato.cargos')
            ->whereHas('academico', function ($query) use ($tempo_trabalho) {
                $query->when($tempo_trabalho, function ($query) use ($tempo_trabalho) {
                    $query->where('ano_trabalho', '=', $tempo_trabalho);
                });
            })
            ->whereHas('contrato', function ($query) use ($id) {
                $query->when($id, function ($query) use ($id) {
                    $query->where('cargo_id', $id);
                });
            })
            ->when($request->status, function ($query, $value) {
                $query->where('status', $value);
            })
            ->when($request->genero, function ($query, $value) {
                $query->where('genero', $value);
            })
            ->where('level', $request->instituicao)->where([
                ['shcools_id', '=', $user->shcools_id],
            ])
            ->orderBy('created_at', 'asc')
            ->get();

        $cargo = Cargo::find($id);

        $headers = [
            "titulo" => "LISTA DOS FUNCIONÁRIOS POR CARGOS",
            "descricao" => env('APP_NAME'),
            "funcionarios" => $funcionarios,
            "usuario" => User::findOrFail(Auth::user()->id),
            "cargo" => $cargo,
            "status" => $request->status,
            "genero" => $request->genero,
            "tempo_trabalho" => $request->tempo_trabalho,
            "instituicao" => $instituicaos
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-funcionarios-cargos-municipal', $headers)->setPaper('A4', 'landscape');;
        return $pdf->stream('lista-funcionarios-cargos-municipal.pdf');
    }

    public function funcionariosImprimirCargoMunicipalEXCEL(Request $request)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        return Excel::download(new FuncionariosCargoExport($request->instituicao, $request->tempo_trabalho, $request->genero, $request->status, $request->cargo_id), 'funcionarios-cargos.xlsx');
    }
    /***
    #########################################################################################
    MUNICIPAL END
    #########################################################################################
     */

    public function funcionariosImprimir()
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            
            "titulo" => "LISTA DOS FUNCIONÁRIOS",
            "funcionarios" => FuncionariosControto::where('tb_contratos.ano_lectivos_id', $this->anolectivoActivo())
                ->where('tb_contratos.status', 'activo')
                ->with(['funcionario'])
            ->get(),
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-funcionarios', $headers);
        return $pdf->stream('lista-funcionarios.pdf');
    }

    public function estudantesBolseiroImprimir(Request $request)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
        $bolseiros = Bolseiro::with(['instituicao', 'bolsa', 'instituicao_bolsa', 'ano', 'periodo', 'estudante', 'escola'])
            ->when($request->instituicao_id, function ($query, $value) {
                $query->where('instituicao_id', $value);
            })
            ->when($request->bolsa_id, function ($query, $value) {
                $query->where('bolsa_id', $value);
            })
            ->where('shcools_id', $this->escolarLogada())->get();

        $bolsa = Bolsa::find($request->bolsa_id);
        $instituicao = InstituicaoEducacional::find($request->instituicao_id);

        $titulo = "LISTA DOS ESTUDANTES BOLSEIROS";

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);
        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            
            "titulo" => $titulo,
            "escola" => Shcool::find($this->escolarLogada()),
            "bolsa" => $bolsa,
            "instituicao" => $instituicao,
            "bolseiros" => $bolseiros,
            "filtros" => $request->all('bolsa_id', 'instituicao_id'),
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-estudantes-bolseiros', $headers);
        return $pdf->stream('listagem-estudantes-bolseiros.pdf');
    }


    public function estudantesEstagiarioImprimir(Request $request)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        // CONTROLAR A SESSÃo INICIALIZADA OU NAO

        $estagiarios = Estagiario::with(['instituicao', 'estagio', 'instituicao_estagio', 'ano', 'estudante', 'escola'])
            ->when($request->instituicao_id, function ($query, $value) {
                $query->where('instituicao_id', $value);
            })
            ->when($request->estagio_id, function ($query, $value) {
                $query->where('estagio_id', $value);
            })
            ->where('shcools_id', $this->escolarLogada())
            ->get();

        $estagiario = Bolsa::find($request->estagio_id);
        $instituicao = InstituicaoEducacional::find($request->instituicao_id);

        $titulo = "LISTA DOS ESTUDANTES ESTAGIARIOS";

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            
            "titulo" => $titulo,
            "estagiario" => $estagiario,
            "instituicao" => $instituicao,
            "estagiarios" => $estagiarios,
            "filtros" => $request->all('estagio_id', 'instituicao_id'),
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-estudantes-estagiarios', $headers);
        return $pdf->stream('listagem-estudantes-estagiarios.pdf');
    }

    // retificado
    public function estudantesImprimir(Request $request)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
        $matriculas = Matricula::where('status_matricula', '!=', 'nao_confirmado')
            ->where('status_matricula', '!=', 'rejeitado')
            ->where('status_inscricao', '=', 'Admitido')
            ->when($request->status, function ($query, $value) {
                $query->where('status_matricula', $value);
            })->when($request->cursos_id, function ($query, $value) {
                $query->where('cursos_id', $value);
            })->when($request->classes_id, function ($query, $value) {
                $query->where('classes_id', $value);
            })->when($request->turnos_id, function ($query, $value) {
                $query->where('turnos_id', $value);
            })
            ->whereHas('estudante', function ($query) use ($request) {
                $query->when($request->genero, function ($q, $v) {
                    $q->where('genero', $v);
                });
                $query->when($request->finalista, function ($q, $v) {
                    $q->where('finalista', $v);
                });
            })
            ->where('ano_lectivos_id', '=', $this->anolectivoActivo())
            ->where('shcools_id', '=', $this->escolarLogada())
            ->with(['escolas', 'ano_lectivo', 'classe_at', 'estudante', 'classe', 'turno', 'curso'])
            ->get()
            ->sortBy(function ($matricula) {
                return $matricula->estudante->nome;
            });

        $curso = Curso::find($request->cursos_id);
        $classe = Classe::find($request->classes_id);
        $turno = Turno::find($request->turnos_id);

        if ($request->finalista) {
            if ($request->finalista == "Y") {
                $titulo = "ESTUDANTES FINALISTAS";
            }
            $titulo = "LISTA DOS ESTUDANTES";
        } else {
            $titulo = "LISTA DOS ESTUDANTES";
        }

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            
            "titulo" => $titulo,
            "matriculas" => $matriculas,
            "curso" => $curso,
            "classe" => $classe,
            "turno" => $turno,
            "filtros" => $request->all('status', 'cursos_id', 'classes_id', 'turnos_id', 'genero', 'finalista'),
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-estudantes', $headers);
        return $pdf->stream('lista-estudantes.pdf');
    }

    // refifado
    public function estudantesMatriculasImprimir(Request $request)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
        $matriculas = Matricula::when($request->status, function ($query, $value) {
            $query->where('status_matricula', $value);
        })->when($request->cursos_id, function ($query, $value) {
            $query->where('cursos_id', $value);
        })->when($request->classes_id, function ($query, $value) {
            $query->where('classes_id', $value);
        })->when($request->turnos_id, function ($query, $value) {
            $query->where('turnos_id', $value);
        })
        ->with(['ano_lectivo', 'classe_at', 'classe', 'turno', 'curso', 'estudante'])
        ->where('ano_lectivos_id', $this->anolectivoActivo())
        ->where('shcools_id', $this->escolarLogada())
        ->where('status_inscricao', 'Admitido')
        ->get()
        ->sortBy(function ($matricula) {
            return $matricula->estudante->nome;
        });

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            
            "titulo" => "LISTA DOS ESTUDANTES COM MATRICULAS",

            "classe" => Classe::find($request->classes_id),
            "curso" => Curso::find($request->cursos_id),
            "turno" => Turno::find($request->turnos_id),
            "status" => $request->status,
            "matriculas" => $matriculas
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-estudantes-matriculas', $headers);
        return $pdf->stream('lista-estudantes-matriculas.pdf');
    }

    // refifado
    public function estudantesMatriculadoConfirmadoImprimir(Request $request)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
        $matriculas = Matricula::when($request->status, function ($query, $value) {
            $query->where('status_matricula', $value);
        })->when($request->cursos_id, function ($query, $value) {
            $query->where('cursos_id', $value);
        })->when($request->classes_id, function ($query, $value) {
            $query->where('classes_id', $value);
        })->when($request->turnos_id, function ($query, $value) {
            $query->where('turnos_id', $value);
        })
        ->with(['ano_lectivo', 'classe_at', 'classe', 'turno', 'curso', 'estudante'])
        ->where('ano_lectivos_id', $this->anolectivoProximo($this->anolectivoActivo()))
        ->where('shcools_id', $this->escolarLogada())
        ->where('status_inscricao', 'Admitido')
        ->get()
        ->sortBy(function ($matricula) {
            return $matricula->estudante->nome;
        });

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            
            "titulo" => "Listagem dos estudantes matriculados e confirmados para o proximo ano",
            "classe" => Classe::find($request->classes_id),
            "curso" => Curso::find($request->cursos_id),
            "turno" => Turno::find($request->turnos_id),
            "status" => $request->status,
            "matriculas" => $matriculas
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-estudantes-matriculas', $headers);
        return $pdf->stream('lista-estudantes-matriculas.pdf');
    }

    public function estudantesInscricoesExameAcessoImprimir(Request $request)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
        $idade = $request->idade;

        $matriculas = Matricula::when($request->media, function ($query, $value) {
            $query->where('media', $value);
        })
            ->when($request->cursos_id, function ($query, $value) {
                $query->where('cursos_id', $value);
            })
            ->when($request->classes_id, function ($query, $value) {
                $query->where('classes_id', $value);
            })
            ->when($request->turnos_id, function ($query, $value) {
                $query->where('turnos_id', $value);
            })
            ->when($request->status, function ($query, $value) {
                $query->where('status_inscricao', $value);
            })
            ->whereHas('estudante', function ($query) use ($idade) {
                $query->when($idade, function ($query) use ($idade) {
                    $query->whereRaw("TIMESTAMPDIFF(YEAR, nascimento, NOW()) = ?", [$idade]);
                });
            })
            ->whereIn('tipo', ['inscricao', 'candidatura'])
            ->where('prova_acesso', 'Y')
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('shcools_id', $this->escolarLogada())
            ->with(['escolas', 'ano_lectivo', 'classe_at', 'estudante', 'classe', 'turno', 'curso'])
            ->get()
            ->sortBy(function ($matricula) {
                return $matricula->estudante->nome;
            });


        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            
            "titulo" => "PROVAS DE EXAMES DE ACESSO",
            "matriculas" => $matriculas,
            "status" => $request->status,
            "media" => $request->media,
            "idade" => $request->idade,

            "curso" => Curso::find($request->cursos_id),
            "classe" => Classe::find($request->classes_id),
            "turno" => Turno::find($request->turnos_id),
        ];

        $pdf = \PDF::loadView('downloads.relatorios.listas-estudantes-inscritos-exame-acesso', $headers)->setPaper('A4', 'landscape');
        return $pdf->stream('listas-estudantes-inscritos-exame-acesso.pdf');
    }


    // retificado
    public function estudantesInscricoesImprimir(Request $request)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
        $idade = $request->idade;

        if ($request->ano_lectivos_id) {
            $request->ano_lectivos_id = $this->anolectivoActivo();
        }

        $matriculas = Matricula::when($request->media, function ($query, $value) {
            $query->where('media', $value);
        })
            ->when($request->ano_lectivos_id, function ($query, $value) {
                $query->where('ano_lectivos_id', $value);
            })
            ->when($request->cursos_id, function ($query, $value) {
                $query->where('cursos_id', $value);
            })
            ->when($request->classes_id, function ($query, $value) {
                $query->where('classes_id', $value);
            })
            ->when($request->turnos_id, function ($query, $value) {
                $query->where('turnos_id', $value);
            })
            ->when($request->status, function ($query, $value) {
                $query->where('status_inscricao', $value);
            })
            ->whereHas('estudante', function ($query) use ($idade) {
                $query->when($idade, function ($query) use ($idade) {
                    $query->whereRaw("TIMESTAMPDIFF(YEAR, nascimento, NOW()) = ?", [$idade]);
                });
            })
            ->whereIn('tb_matriculas.status_inscricao', ['Nao Admitido', 'Admitido'])
            ->whereIn('tb_matriculas.tipo', ['inscricao', 'candidatura'])
            ->where('tb_matriculas.ano_lectivos_id', $this->anolectivoActivo())
            ->where('tb_matriculas.shcools_id', $this->escolarLogada())
            ->with(['escolas', 'ano_lectivo', 'classe_at', 'estudante', 'classe', 'turno', 'curso'])
            ->get()
            ->sortBy(function ($matricula) {
                return $matricula->estudante->nome;
            });


        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            
            "titulo" => "LISTA DOS ESTUDANTES INSCRITOS",
            "matriculas" => $matriculas,
            "status" => $request->status,
            "media" => $request->media,
            "idade" => $request->idade,

            "curso" => Curso::find($request->cursos_id),
            "classe" => Classe::find($request->classes_id),
            "turno" => Turno::find($request->turnos_id),
            "ano_lectivo" => AnoLectivo::find($request->ano_lectivos_id),
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-estudantes-inscritos', $headers)
            // ->setPaper('A4', 'landscape')
        ;
        return $pdf->stream('lista-estudantes-inscritos.pdf');
    }

    // retificado
    public function estudantesInscricoesAceiteImprimir(Request $request)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
        $matriculas = Matricula::where('status_inscricao', 'Admitido')
            ->whereIn('tipo', ['inscricao', 'candidatura'])
            ->when($request->ano_lectivos_id, function ($query, $value) {
                $query->where('ano_lectivos_id', $value);
            })
            ->when($request->cursos_id, function ($query, $value) {
                $query->where('cursos_id', $value);
            })
            ->when($request->classes_id, function ($query, $value) {
                $query->where('classes_id', $value);
            })
            ->when($request->turnos_id, function ($query, $value) {
                $query->where('turnos_id', $value);
            })
            ->where('tb_matriculas.shcools_id', $this->escolarLogada())
            ->with(['escolas', 'ano_lectivo', 'classe_at', 'estudante', 'classe', 'turno', 'curso'])
            ->get()
            ->sortBy(function ($matricula) {
                return $matricula->estudante->nome;
            });

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            
            "titulo" => "LISTA DOS ESTUDANTES INSCRITOS ADMITIDOS",
            "matriculas" => $matriculas,

            "status" => "Admitido",
            "media" => "TODAS",
            "idade" => "TODAS",

            "curso" => Curso::find($request->cursos_id),
            "classe" => Classe::find($request->classes_id),
            "turno" => Turno::find($request->turnos_id),
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-estudantes-inscritos', $headers);
        return $pdf->stream('lista-estudantes-inscritos.pdf');
    }

    public function encarregadosImprimir()
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            
            "titulo" => "LISTA DOS ENCARREGADOS",
            "encarregados" => Encarregado::where([
                ['shcools_id', '=', $this->escolarLogada()]
            ])->get(),
        ];

        $pdf = \PDF::loadView('downloads.relatorios.lista-encarregados', $headers);
        return $pdf->stream('lista-encarregados.pdf');
    }

    public function listagemEscolaImprimir(Request $request)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        $ano_lectivo = AnoLectivoGlobal::find($request->ano_lectivo);

        $provincia = Provincia::find($request->provincia_id);

        $escolas = Shcool::when($request->ano_lectivo, function ($query, $value) {
            $query->where('ano_lectivo_global_id', $value);
        })
            ->when($request->provincia_id, function ($query, $value) {
                $query->where('provincia_id', $value);
            })
            ->when($request->municipio_id, function ($query, $value) {
                $query->where('municipio_id', $value);
            })
            ->when($request->distrito_id, function ($query, $value) {
                $query->where('distrito_id', $value);
            })
            ->when($request->ensino_id, function ($query, $value) {
                $query->where('ensino_id', $value);
            })
            ->with(['ensino', 'pais', 'provincia', 'municipio'])
            ->get();

        if ($provincia) {
            $title = "LISTA DAS ESCOLAS DA PROVÍNCIA DE {$provincia->nome}";
        } else {
            $title = "LISTA DAS ESCOLAS DO PAÍS";
        }

        // $orintacao = "";

        // if ($condicao == "trimestre4") {
        $orintacao = 'landscape';
        // }else{
        // $orintacao = 'portrait';
        // }  

        $headers = [
            "titulo" => $title,
            "escolas" => $escolas,
            "ano_lectivo" => $ano_lectivo,
            "provincia" => $provincia,
        ];

        $pdf = \PDF::loadView('downloads.relatorios.todas-escolas', $headers)->setPaper('A4', $orintacao);
        return $pdf->stream('lista-todas-escolas.pdf');
    }


    public function municipiolistagemEscolaImprimir(Request $request)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        $user = auth()->user();

        $direccao = DireccaoMunicipal::findOrFail($user->shcools_id);

        $provincia = Municipio::find($direccao->municipio_id);
        $distrito = Distrito::find($request->distrito_id);

        $escolas = Shcool::when($request->ensino_id, function ($query, $value) {
            $query->where('ensino_id', $value);
        })
            ->when($request->distrito_id, function ($query, $value) {
                $query->where('distrito_id', $value);
            })
            ->when($request->categoria, function ($query, $value) {
                $query->where('categoria', $value);
            })
            ->where('municipio_id', $direccao->municipio_id)
            ->where('status', 'activo')
            ->with(['ensino', 'pais', 'provincia', 'municipio'])
            ->get();

        if ($provincia) {
            if ($distrito) {
                $title = "LISTA DAS ESCOLAS DO MUNICIPIO DE {$provincia->nome} {$distrito->nome} ";
            } else {
                $title = "LISTA DAS ESCOLAS DO MUNICIPIO DE {$provincia->nome}";
            }
        } else {
            $title = "LISTA DAS ESCOLAS DO {$distrito->nome}";
        }

        // $orintacao = "";

        // if ($condicao == "trimestre4") {
        $orintacao = 'landscape';
        // }else{
        // $orintacao = 'portrait';
        // }  


        $headers = [
            "titulo" => $title,
            "escolas" => $escolas,
            "provincia" => $provincia,
        ];

        $pdf = \PDF::loadView('downloads.relatorios.todas-escolas', $headers)->setPaper('A4', $orintacao);
        return $pdf->stream('lista-todas-escolas.pdf');
    }

    public function provinciallistagemEscolaImprimir(Request $request)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        $user = auth()->user();

        $direccao = DireccaoProvincia::findOrFail($user->shcools_id);

        $provincia = Provincia::find($direccao->provincia_id);
        $munucipio = Distrito::find($request->municipio_id);

        $escolas = Shcool::when($request->ensino_id, function ($query, $value) {
            $query->where('ensino_id', $value);
        })
            ->when($request->distrito_id, function ($query, $value) {
                $query->where('distrito_id', $value);
            })
            ->when($request->status, function ($query, $value) {
                $query->where('status', $value);
            })
            ->when($request->municipio_id, function ($query, $value) {
                $query->where('municipio_id', $value);
            })
            ->when($request->categoria, function ($query, $value) {
                $query->where('categoria', $value);
            })
            ->where('provincia_id', $direccao->provincia_id)
            ->where('status', 'activo')
            ->with(['ensino', 'pais', 'provincia', 'municipio'])
            ->get();

        if ($provincia) {
            if ($munucipio) {
                $title = "LISTA DAS ESCOLAS DA PROVINCIA DE {$provincia->nome} MUNICÍPIO {$munucipio->nome} ";
            } else {
                $title = "LISTA DAS ESCOLAS DA PROVINCIA DE {$provincia->nome}";
            }
        } else {
            $title = "LISTA DAS ESCOLAS DO MUNICIPIO {$munucipio->nome}";
        }

        // $orintacao = "";

        // if ($condicao == "trimestre4") {
        $orintacao = 'landscape';
        // }else{
        // $orintacao = 'portrait';
        // }  


        $headers = [
            "titulo" => $title,
            "escolas" => $escolas,
            "provincia" => $provincia,
        ];

        $pdf = \PDF::loadView('downloads.relatorios.todas-escolas', $headers)->setPaper('A4', $orintacao);
        return $pdf->stream('lista-todas-escolas.pdf');
    }



    public function listagemProfessorEscolaImprimir(Request $request)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        $escola = Shcool::findOrFail($request->escola_id);
        $professores = FuncionariosControto::with('funcionario.academico')->where('shcools_id', $escola->id)->get();


        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "titulo" => "LISTA DOS PROFESSORES DA(O) {$escola->nome}",
            "escola" => $escola,
            "professores" => $professores,
        ];

        $pdf = \PDF::loadView('downloads.relatorios.listagem-professores-escolas', $headers);
        return $pdf->stream('listagem-professores-escolas.pdf');
    }

    public function listagemTodosProfessorImprimir(Request $request)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        if (isset($request->status)) {
            $request->status = $request->status;
        } else {
            $request->status = "";
        }

        if (isset($request->genero)) {
            $request->genero = $request->genero;
        } else {
            $request->genero = "";
        }

        if (isset($request->provincia_id)) {
            $request->provincia_id = $request->provincia_id;
        } else {
            $request->provincia_id = "";
        }

        $provincia = Provincia::find($request->provincia_id);

        if ($provincia) {
            $title = "LISTA DOS PROFESSORES DA PROVÍNCIA DE {$provincia->name}";
        } else {
            $title = "LISTA DOS PROFESSORES DO PAÍS";
        }

        $professores = Professor::when($request->status, function ($query, $value) {
            $query->where('status', $value);
        })
            ->when($request->genero, function ($query, $value) {
                $query->where('genero', $value);
            })
            ->when($request->provincia_id, function ($query, $value) {
                $query->where('provincia_id', $value);
            })
            ->with('provincia')
            ->get();

        $headers = [
            "titulo" => $title,
            "professores" => $professores,
            "provincia" => $provincia,
            "genero" => $request->genero,
        ];

        $orintacao = 'landscape';

        $pdf = \PDF::loadView('downloads.funcionarios.listagem-todos-professores', $headers)->setPaper('A4', $orintacao);
        return $pdf->stream('listagem-todos-professores.pdf');
    }


    public function listagemProfessorProvincialImprimir(Request $request)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        // dd("snlkgnsdngjl");

        $especialidade_id = $request->especialidade_id;
        $categoria_id = $request->categoria_id;

        $professores = Professor::with('academico.especialidade', 'academico.categoria')
            ->whereHas('academico', function ($query) use ($especialidade_id, $categoria_id) {
                $query->when($especialidade_id, function ($query) use ($especialidade_id) {
                    $query->where('especialidade_id', $especialidade_id);
                });

                $query->when($categoria_id, function ($query) use ($categoria_id) {
                    $query->where('categoria_id', $categoria_id);
                });
            })
            ->when($request->status, function ($query, $value) {
                $query->where('status', $value);
            })
            ->when($request->provincia_id, function ($query, $value) {
                $query->where('provincia_id', $value);
            })
            ->when($request->genero, function ($query, $value) {
                $query->where('genero', $value);
            })
            ->when($request->ano_nascimento_maior, function ($query, $value) {
                $query->where('nascimento', '>=', Carbon::createFromFormat('Y', $value)->endOfYear());
            })
            ->when($request->ano_nascimento_menor, function ($query, $value) {
                $query->where('nascimento', '<=', Carbon::createFromFormat('Y', $value)->endOfYear());
            })
            ->with('provincia')
            ->get();

        $title = "Lista dos professores";

        $headers = [
            "titulo" => $title,
            "professores" => $professores,
        ];

        $orintacao = 'landscape';

        $pdf = \PDF::loadView('downloads.funcionarios.listagem-todos-professores-provincial', $headers)->setPaper('A4', $orintacao);
        return $pdf->stream('listagem-todos-professores-provincial.pdf');
    }

    public function listagemTodosEstudantesImprimir(Request $request)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor


        $provincia = Provincia::find($request->provincia_id);
        $municipio = Municipio::find($request->municipio_id);
        $distrito = Distrito::find($request->distrito_id);
        $ano_lectivo = AnoLectivoGlobal::find($request->ano_lectivos_id);

        $title = "LISTA DE TODOS OS ESTUDANTES";

        $search_municipio_id = $request->municipio_id;
        $search_distrito = $request->distrito_id;
        $search_ano_lectivos_id = $request->ano_lectivos_id;
        $search_provincia_id = $request->provincia_id;


        $estudantes = Estudante::with(['escola.ano', 'escola.municipio', 'escola.provincia', 'distrito', 'provincia', 'municipio', 'ano'])
            ->whereHas('escola', function ($query) use ($search_municipio_id, $search_distrito, $search_provincia_id, $search_ano_lectivos_id) {
                $query->when($search_provincia_id, function ($query, $value) {
                    $query->where('provincia_id', $value);
                });

                $query->when($search_municipio_id, function ($query, $value) {
                    $query->where('municipio_id', $value);
                });

                $query->when($search_distrito, function ($query, $value) {
                    $query->where('distrito_id', $value);
                });

                $query->when($search_ano_lectivos_id, function ($query, $value) {
                    $query->where('ano_lectivo_global_id', $value);
                });
            })
            ->when($request->shcools_id, function ($query, $value) {
                $query->where('shcools_id', $value);
            })
            ->when($request->genero, function ($query, $value) {
                $query->where('genero', $value);
            })
            ->where('registro', '=', 'confirmado')
            ->get();


        $headers = [
            "titulo" => $title,
            "estudantes" => $estudantes,
            "provincia" => $provincia,
            "ano" => $ano_lectivo,
            'genero' => $request->genero,
            "municipio" => $municipio,
            "distrito" => $distrito
        ];

        $pdf = \PDF::loadView('downloads.relatorios.listagem-todos-estudantes', $headers);
        return $pdf->stream('listagem-todos-estudantes.pdf');
    }

    public function listagemEstudanteEscolaImprimir(Request $request)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        $escola = Shcool::findOrFail($request->escola_id);
        $estudantes = Estudante::with('provincia')->where('shcools_id', $escola->id)->get();

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "titulo" => "LISTA DOS ESTUDANTES DA(O) {$escola->nome}",
            "estudantes" => $estudantes,
        ];

        $pdf = \PDF::loadView('downloads.relatorios.listagem-estudantes-escolas', $headers);
        return $pdf->stream('listagem-estudantes-escolas.pdf');
    }

    public function estatisticaEstudanteImprimir(Request $request)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        $curso = Curso::find($request->cursos_id);
        $classe = Classe::find($request->classes_id);
        $turno = Turno::find($request->turnos_id);
        $provincia = Provincia::find($request->provincia_id);
        $ano = AnoLectivoGlobal::find($request->ano_lectivos_id);

        $estudantes = Matricula::when($request->provincia_id, function ($query, $value) {
            $query->where('tb_estudantes.provincia_id', $value);
        })
            ->when($request->ano_lectivos_id, function ($query, $value) {
                $query->where('tb_matriculas.ano_lectivo_global_id', $value);
            })
            ->when($request->classes_id, function ($query, $value) {
                $query->where('classes_id', $value);
            })
            ->when($request->cursos_id, function ($query, $value) {
                $query->where('cursos_id', $value);
            })
            ->when($request->turnos_id, function ($query, $value) {
                $query->where('turnos_id', $value);
            })
            ->when($request->estado, function ($query, $value) {
                $query->where('tb_matriculas.status_matricula', $value);
            })
            ->when($request->genero, function ($query, $value) {
                $query->where('genero', $value);
            })
            ->join('tb_estudantes', 'tb_matriculas.estudantes_id', '=', 'tb_estudantes.id')
            ->join('tb_classes', 'tb_matriculas.classes_id', '=', 'tb_classes.id')
            ->join('tb_cursos', 'tb_matriculas.cursos_id', '=', 'tb_cursos.id')
            ->join('tb_turnos', 'tb_matriculas.turnos_id', '=', 'tb_turnos.id')
            ->join('states', 'tb_estudantes.provincia_id', '=', 'states.id')
            ->join('tb_ano_lectivos_global', 'tb_estudantes.ano_lectivo_global_id', '=', 'tb_ano_lectivos_global.id')
            ->orderBy('nome', 'asc')
            ->select('nome', 'sobre_nome', 'genero', 'status_matricula', 'bilheite', 'tb_estudantes.status', 'tb_estudantes.id', 'numero_processo', 'states.name', 'ano', 'classes', 'curso', 'turno')
            ->get();

        $headers = [
            "titulo" => "RELATÓRIO DOS ESTUDANTES",
            "estudantes" => $estudantes,
            'curso' => $curso,
            'classe' => $classe,
            'turno' => $turno,
            'provincia' => $provincia,
            'ano' => $ano,
            'estado' => $request->estado,
        ];

        $pdf = \PDF::loadView('downloads.relatorios.estatistica-estudantes', $headers);
        return $pdf->stream('estatistica-estudantes.pdf');
    }
}
