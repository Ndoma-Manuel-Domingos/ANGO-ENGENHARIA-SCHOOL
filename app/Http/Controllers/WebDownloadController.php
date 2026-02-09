<?php

namespace App\Http\Controllers;

use App\Exports\MapaAproveitamentoExport;
use App\Exports\MapaEfectividadeProfessoresExport;
use App\Exports\ExcelTestExport;
use App\Models\Director;
use App\Models\Efeito;
use App\Models\Professor;
use App\Models\User;
use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\calendarios\Matricula;
use App\Models\web\calendarios\Pagamento;
use App\Models\web\estudantes\CartaoEstudante;
use App\Models\web\classes\Classe;
use App\Models\web\anolectivo\ControlePeriodico;
use App\Models\web\calendarios\ListaPresenca;
use App\Models\web\calendarios\Mes;
use App\Models\web\cursos\Curso;
use App\Models\web\disciplinas\Disciplina;
use App\Models\web\estudantes\Estudante;
use App\Models\web\financeiros\DetalhesPagamentoPropina;
use App\Models\web\funcionarios\CartaoFuncionario;
use App\Models\web\funcionarios\Funcionarios;
use App\Models\web\funcionarios\FuncionariosAcademico;
use App\Models\web\funcionarios\FuncionariosControto;
use App\Models\web\salas\Sala;
use App\Models\web\turmas\EstudantesTurma;
use App\Models\web\turmas\DisciplinaTurma;
use App\Models\web\turmas\FuncionariosTurma;
use App\Models\web\turmas\NotaPauta;
use App\Models\web\turmas\Turma;
use App\Models\web\turnos\Turno;
use App\Models\web\calendarios\Servico;
use App\Models\web\calendarios\ServicoTurma;
use App\Models\web\encarregados\EncarregadoEstudantes;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MiniPautaExport;
use App\Exports\MiniPautaGeralExport;
use App\Exports\MiniPautaTodasDisciplinasExport;
use App\Models\web\anolectivo\Trimestre;
use App\Models\web\cursos\AnoLectivoCurso;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class WebDownloadController extends Controller
{
    use TraitHelpers;
    use TraitHeader;


    public function __construct()
    {
        $this->middleware('auth');
    }

    // listar estudantes novos
    public function teste_download()
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "anolectivo" => AnoLectivo::find($this->anolectivoActivo())
        ];

        $pdf = \PDF::loadView('downloads.estudantes.teste', $headers)->setPaper('A3', 'landscape');
        return $pdf->stream('lista-estudantes-novo.pdf');
    }

    // listar estudantes novos
    public function teste_download_excel()
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());
     
        return Excel::download(new ExcelTestExport(), "MINI-PAUTA.xlsx");
  
    }

    // listar estudantes novos
    public function listarEstudantesNovos()
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,

            "matriculas" => Matricula::where([
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['status', '=', 'Novo']
            ])->get(),
            "anolectivo" => AnoLectivo::find($this->anolectivoActivo())
        ];

        $pdf = \PDF::loadView('downloads.estudantes.listar-estudantes-novos', $headers);
        return $pdf->stream('lista-estudantes-novo.pdf');
        // return $pdf->stream();
    }

    // listar estudantes antigos
    public function listarEstudantesAntigos()
    {

        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,

            "matriculas" => Matricula::where([
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['status', '=', 'Repitente']
            ])->get(),
            "anolectivo" => AnoLectivo::find($this->anolectivoActivo())
        ];

        $pdf = \PDF::loadView('downloads.estudantes.listar-estudantes-antigos', $headers);
        return $pdf->stream('lista-estudantes-antigos.pdf');
        // return $pdf->stream();
    }

    // listar estudantes por curso
    public function listarEstudantesCurso($id)
    {

        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário
        $curso = Curso::findOrFail($id);

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,

            "matriculas" => Matricula::where([
                ['cursos_id', '=', $curso->id]
            ])->get(),
            "curso" => $curso,
            "anolectivo" => AnoLectivo::find($this->anolectivoActivo())
        ];

        $pdf = \PDF::loadView('downloads.estudantes.listar-estudantes-curso', $headers);
        return $pdf->stream('lista-estudantes-curso-.pdf');
    }

    // listar por turno
    public function listarEstudantesTurno($id)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário
        $turno = Turno::findOrFail($id);
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);


        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,

            "matriculas" => Matricula::where([
                ['turnos_id', '=', $turno->id]
            ])->get(),
            "turno" => $turno,
            "anolectivo" => AnoLectivo::find($this->anolectivoActivo())
        ];

        $pdf = \PDF::loadView('downloads.estudantes.listar-estudantes-turno', $headers);
        return $pdf->stream('lista-estudantes-turno.pdf');
    }

    // listar estudantes por classe
    public function listarEstudantesClasse($id)
    {

        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário
        $classe = Classe::findOrFail($id);
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,

            "matriculas" => Matricula::where([
                ['classes_id', '=', $classe->id]
            ])->get(),
            "classe" => $classe,
            "anolectivo" => AnoLectivo::find($this->anolectivoActivo())
        ];

        $pdf = \PDF::loadView('downloads.estudantes.listar-estudantes-classe', $headers);
        return $pdf->stream('lista-estudantes-classe.pdf');
    }

    public function distribuicaoRotas(Request $request)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário
        $id = Crypt::decrypt($request->id);
        $ano = Crypt::decrypt($request->ano);
        $condicao = Crypt::decrypt($request->condicao);
        $condicao2 = Crypt::decrypt($request->condicao2);

        if ($condicao == "Ficha-Tecnica" || $condicao == "Ficha-Matricula" || $condicao == "Ficha-inscricao") {
            return redirect()->route('dow.ficha-tecnica-estudante', ['code' => Crypt::encrypt($id), 'ano' => Crypt::encrypt($ano)]);
        } else {
            return redirect()->route('ficha-pauta-estudante', ['id' => Crypt::encrypt($id), 'condicao' => Crypt::encrypt($condicao), 'condicao2' => Crypt::encrypt($condicao2), 'ano' => Crypt::encrypt($ano)]);
        }
    }

    // ficha tecnica do estudante
    // retificado
    public function downloadFichaTecnicaEstudante(Request $request)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $estudante = Estudante::findOrFail(Crypt::decrypt($request->code));

        if (Crypt::decrypt($request->ano)) {
            $anolectivo = AnoLectivo::findOrFail(Crypt::decrypt($request->ano));
        } else {
            $anolectivo = AnoLectivo::findOrFail($this->anolectivoActivo());
        }

        $matriculas = Matricula::where([
            ['ano_lectivos_id', $anolectivo->id],
            ['estudantes_id', $estudante->id],
            ['status_matricula', 'confirmado'],
            ['shcools_id', $this->escolarLogada()],
        ])
            ->with(['classe', 'turno', 'curso', 'estudante'])
            ->whereHas('estudante', function ($query) {
                $query->where('registro', 'confirmado')->orderBy('nome', 'ASC');
            })
            ->first();

        if (!$matriculas) {
            Alert::warning("Informação", "Este estudante não tem nenhuma confirmação/matricula para o ano Lectivo selecionado!");
            return redirect()->back();
        }

        $pagamento = Pagamento::where('ficha', $matriculas->ficha)->first();

        $turmasEstudante = EstudantesTurma::where([
            ['estudantes_id', $estudante->id],
            ['ano_lectivos_id', $anolectivo->id],
        ])->first();

        $turma = Turma::findOrFail($turmasEstudante->turmas_id);

        $encarregado = EncarregadoEstudantes::where([
            ['estudantes_id', $estudante->id],
        ])
            ->join('tb_encarregados', 'tb_encarregado_estudantes.encarregados_id', '=', 'tb_encarregados.id')
            ->first();



        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [

            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            'matricula' => $matriculas,
            'estudante' => $estudante,
            'encarregado' => $encarregado,
            'turma' => $turma,
            'pagamento' => $pagamento,
            'ano_lectivo' => $anolectivo,
        ];

        $pdf = \PDF::loadView('downloads.estudantes.ficha-tecnica-estudante', $headers);
        return $pdf->stream('ficha-matricula.pdf');
    }

    // ficha matricula
    public function fichaMatricula($ficha)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $pagamento = Pagamento::where([
            ['ficha', '=', $ficha],
            ['ano_lectivos_id', '=', $this->anolectivoActivo()]
        ])->first();

        $dados = NULL;
        if ($pagamento) {
            if ($pagamento->model == "estudante") {
                $dados = Estudante::findOrFail($pagamento->estudantes_id);

                $matricula = Matricula::where([
                    ['estudantes_id', '=', $dados->id],
                    ['ano_lectivos_id', '=', $this->anolectivoActivo()]
                ])->first();
            } else {
                $dados = Funcionarios::findOrFail($pagamento->estudantes_id);
            }
        }



        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,

            'matricula' => isset($matricula),
            'dados' => $dados,
            'pagamento' => $pagamento,
            'funcionarioAtendente' => User::findOrFail($pagamento->funcionarios_id),
            'ano_lectivo' => AnoLectivo::findOrFail($this->anolectivoActivo()),
        ];

        $pdf = \PDF::loadView('downloads.estudantes.ficha-matricula', $headers)->setPaper('A4', 'portrait');
        return $pdf->stream('ficha-matricula.pdf');
    }

    public function fichaMatricula2($ficha)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $matricula = Matricula::with(
            'ano_lectivo',
            'classe_at',
            'classe',
            'turno',
            'curso',
            'estudante'
        )->where([
            ['ficha', '=', $ficha],
        ])->first();

        $pagamento = Pagamento::where('ficha', $ficha)
            ->with('servico')
            ->first();

        if ($pagamento) {
            $detalhesPagamento = DetalhesPagamentoPropina::with('servico')->where('pagamentos_id', $pagamento->id)->get();
        } else {
            $detalhesPagamento = null;
        }



        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,

            'matricula' => $matricula,
            'pagamento' => $pagamento,
            'detalhesPagamento' => $detalhesPagamento,
            'funcionarioAtendente' => User::findOrFail($matricula->funcionarios_id),
            'ano_lectivo' => AnoLectivo::findOrFail($this->anolectivoActivo()),
        ];

        $pdf = \PDF::loadView('downloads.estudantes.ficha-matricula-novo', $headers)->setPaper('A4', 'portrait');
        return $pdf->stream('ficha-matricula.pdf');
    }

    public function fichaMatriculaSegundaVia($ficha)
    {

        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário
        $matricula = Matricula::with(
            'ano_lectivo',
            'classe_at',
            'classe',
            'turno',
            'curso',
            'estudante'
        )->where([
            ['ficha', Crypt::decrypt($ficha)],
        ])->first();

        $turma = Turma::where([
            ['cursos_id', $matricula->curso->id],
            ['turnos_id', $matricula->turno->id],
            ['classes_id', $matricula->classe->id],
        ])->first();

        $sala = Sala::findOrFail($turma->salas_id);



        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,

            'matricula' => $matricula,
            'turma' => $turma,
            'sala' => $sala,
        ];

        $pdf = \PDF::loadView('downloads.estudantes.ficha-matricula-seguna-via', $headers)->setPaper('A4', 'portrait');
        return $pdf->stream(' ficha-matricula-seguna-via.pdf');
    }

    // pauta boletin estudante
    public function downloadBoletinEstudante($id, $ano, $trimestre)
    {

        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $estudantes = Estudante::findOrFail($id);
        $matricula = Matricula::where('estudantes_id', $estudantes->id)
            ->where('ano_lectivos_id', $ano)
            ->first();

        $turma = Turma::where('turnos_id', $matricula->turnos_id)
            ->where('cursos_id', $matricula->cursos_id)
            ->where('classes_id', $matricula->classes_id)
            ->where('ano_lectivos_id', $matricula->ano_lectivos_id)
            ->first();

        $totalDisciplinas = DisciplinaTurma::where('turmas_id', $turma->id)->count('id');

        $somaDasMediaTrimestral = NotaPauta::where('tb_notas_pautas.estudantes_id', $estudantes->id)
            ->where('tb_notas_pautas.controlo_trimestres_id', $trimestre)
            ->where('tb_notas_pautas.ano_lectivos_id', $ano)
            ->where('tb_notas_pautas.turmas_id', $turma->id)
            ->sum('mt');

        $notas = NotaPauta::where('tb_notas_pautas.estudantes_id', $estudantes->id)
            ->where('tb_notas_pautas.controlo_trimestres_id', $trimestre)
            ->where('tb_notas_pautas.ano_lectivos_id', $ano)
            ->where('tb_notas_pautas.turmas_id', $turma->id)
            ->with(['disciplina'])
            ->get();

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "titulo" => "BOLETIM DE NOTAS DO ESTUDANTE",
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            'resultados' => $notas,
            'curso' => Curso::findOrFail($turma->cursos_id),
            'estudante' => Estudante::findOrFail($estudantes->id),
            'sala' => Sala::findOrFail($turma->salas_id),
            'classe' => Classe::findOrFail($turma->classes_id),
            'turno' => Turno::findOrFail($turma->turnos_id),
            'turma' => $turma,
            'estudante' => $estudantes,
            'notas' => $notas,
            'mediaFinal' => $somaDasMediaTrimestral / $totalDisciplinas,
            'anoLectivo' => AnoLectivo::findOrFail($ano),
            'trimestre' => ControlePeriodico::findOrFail($trimestre),
        ];

        $pdf = \PDF::loadView('downloads.estudantes.pauta-estudante', $headers)
            ->setPaper('A4', 'portrait');
        return $pdf->stream('Mini-pauta-geral.pdf');
    }

    public function pautaEstudante(Request $request)
    {

        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário
        // $id, $ano, $condicao, $condicao2 = null
        $estudante = Estudante::with(["provincia", "municipio"])->findOrFail(Crypt::decrypt($request->id));

        $anolectivo = AnoLectivo::findOrFail(Crypt::decrypt($request->ano) ?? $this->anolectivoActivo());

        $turmasEstudante = EstudantesTurma::where("estudantes_id", $estudante->id)
            ->where("ano_lectivos_id", $anolectivo->id)
            ->first();

        if (!$turmasEstudante) {
            return redirect()->route("web.declaracao-estudantes", Crypt::encrypt($request->id));
        }

        $turma = Turma::findOrFail($turmasEstudante->turmas_id);

        $totalDisciplinas = DisciplinaTurma::where([
            ["turmas_id", $turma->id]
        ])->count("id");

        $trimestre1 = ControlePeriodico::where("trimestre", "Iª Trimestre")->first();
        $trimestre2 = ControlePeriodico::where("trimestre", "IIª Trimestre")->first();
        $trimestre3 = ControlePeriodico::where("trimestre", "IIIª Trimestre")->first();
        $trimestre4 = ControlePeriodico::where("trimestre", "Geral")->first();

        $semestre1 = ControlePeriodico::where("trimestre", "Iª Simestre")->first();
        $semestre2 = ControlePeriodico::where("trimestre", "IIª Simestre")->first();
        $anual = ControlePeriodico::where("trimestre", "Anual")->first();

        $notasSomaMdf = NotaPauta::where("estudantes_id", $estudante->id)
            ->where("controlo_trimestres_id", $trimestre4->id)
            ->where("ano_lectivos_id", $anolectivo->id)
            ->sum("mfd");

        $notasSomaNe = NotaPauta::where("estudantes_id", $estudante->id)
            ->where("controlo_trimestres_id", $trimestre4->id)
            ->where("ano_lectivos_id", $anolectivo->id)
            ->sum("ne");

        if ($request->condicao2) {
            $request->condicao2 = Crypt::decrypt($request->condicao2);
        } else {
            $request->condicao2 =  $request->condicao2;
        }

        $escola = Shcool::with("ensino")->findOrFail($this->escolarLogada());
        $director = Director::where("level", "4")->where("instituicao_id", $escola->id)->first();


        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $cond = $request->condicao ?  Crypt::decrypt($request->condicao) : $request->condicao;

        if ($cond == "trimestre1" || $cond == "trimestre2" || $cond == "trimestre3" || $cond == "classificacao-final") {
            $titulo = "BOLETIN DE NOTAS DO ESTUDANTE";
        } else {
            $titulo = ($cond == "declarcao-sem-nota"  ? "DECLARAÇÃO" : ($cond == "declaracao-nota" ? "DECLARAÇÃO COM NOTAS" : ""));
        }

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,

            "titulo" => $titulo,
            "descricao" => env('APP_NAME'),

            "estudantes" => $estudante,
            "turma" => $turma,
            "turmaDisciplinas" => DisciplinaTurma::where("turmas_id", $turma->id)
                ->join("tb_disciplinas", "tb_discplinas_turmas.disciplinas_id", "tb_disciplinas.id")
                ->join("tb_turmas", "tb_discplinas_turmas.turmas_id", "tb_turmas.id")
                ->select("tb_disciplinas.id")
                ->get(),

            "curso" => Curso::findOrFail($turma->cursos_id),
            "sala" => Sala::findOrFail($turma->salas_id),
            "classe" => Classe::findOrFail($turma->classes_id),
            "turno" => Turno::findOrFail($turma->turnos_id),
            "anoLectivo" => AnoLectivo::findOrFail($anolectivo->id),
            "ano_lectivos" => AnoLectivo::where("shcools_id", $this->escolarLogada())->get(),
            // "estudantes_id" => $estudante->id,
            "somaMFD" => $notasSomaMdf,
            "somaNE" => $notasSomaNe,
            "totalDisciplinas" => $totalDisciplinas,
            "trimestre1" => $trimestre1,
            "trimestre2" => $trimestre2,
            "trimestre3" => $trimestre3,
            "trimestre4" => $trimestre4,

            "semestre1" => $semestre1,
            "semestre2" => $semestre2,
            "anual" => $anual,

            "director" => $director,

            "condicao" => $cond,
            "efeito" => Efeito::find($request->condicao2),
        ];

        $orintacao = 'portrait';

        $pdf = \PDF::loadView('downloads.estudantes.pauta-final-estudante', $headers)->setPaper('A4', $orintacao);
        return $pdf->stream('estudantes.pauta-final-estudante.estudante.pdf');
    }

    // mini pautas geral
    public function miniPautaGeral($turma, $disciplina)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário
        $turma = Turma::with(['curso', 'sala', 'classe', 'turno'])->findOrFail(Crypt::decrypt($turma));
        $disciplina = Disciplina::findOrFail(Crypt::decrypt($disciplina));

        $estudantes = EstudantesTurma::with(['estudante'])->where('turmas_id', $turma->id)
        ->get()
        ->sortBy(function($estudante) {
            return $estudante->estudante->nome;
        });

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $trimestre1 = Trimestre::where("trimestre", "Iª Trimestre")->first();
        $trimestre2 = Trimestre::where("trimestre", "IIª Trimestre")->first();
        $trimestre3 = Trimestre::where("trimestre", "IIIª Trimestre")->first();
        $trimestre4 = Trimestre::where("trimestre", "Geral")->first();

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "titulo" => "MINI PAUTA",
            "estudantes" => $estudantes,
            "turma" => $turma,
            'curso' => Curso::findOrFail($turma->cursos_id),
            'sala' => Sala::findOrFail($turma->salas_id),
            'classe' => Classe::findOrFail($turma->classes_id),
            'turno' => Turno::findOrFail($turma->turnos_id),
            'anoLectivo' => AnoLectivo::findOrFail($this->anolectivoActivo()),
            "disciplina" => $disciplina,
            "trimestre1" => $trimestre1 ?? 0,
            "trimestre2" => $trimestre2 ?? 0,
            "trimestre3" => $trimestre3 ?? 0,
            "trimestre4" => $trimestre4 ?? 0,
            "turmas" => Turma::where('ano_lectivos_id', '=', $this->anolectivoActivo())->get(),
        ];

        $codigo = date("Y-m-d");

        $pdf = \PDF::loadView('downloads.turmas.ficha-mini-pauta-geral', $headers)->setPaper('A3', 'landscape');
        return $pdf->stream("MINI-PAUTA-{$turma->turma}-{$codigo}.pdf");
    }

    // mini pautas geral
    public function miniPautaGeralExcel($turma, $disciplina)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $turma = Turma::findOrFail(Crypt::decrypt($turma));
        $disciplina = Disciplina::findOrFail(Crypt::decrypt($disciplina));

        $codigo = date("Y-m-d");

        return Excel::download(new MiniPautaGeralExport($turma, $disciplina), "MINI-PAUTA-{$turma->turma}-{$codigo}.xlsx");
    }


    // mini pauta
    public function miniPauta($turma, $disciplina, $trimestre)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);
        
        
        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário
        $turma = Turma::with(['curso', 'turno', 'sala', 'classe'])->findOrFail(Crypt::decrypt($turma));
        $disciplina = Disciplina::findOrFail(Crypt::decrypt($disciplina));
        $trimestre = ControlePeriodico::findOrFail(Crypt::decrypt($trimestre));
        
        $anoLectivo = AnoLectivo::findOrFail($this->anolectivoActivo());
        
        $notas = NotaPauta::where('turmas_id', $turma->id)
            // ->where('ano_lectivos_id', $anoLectivo->id)
            ->where('controlo_trimestres_id', $trimestre->id)
            ->where('disciplinas_id', $disciplina->id)
            ->with(['estudante'])
        ->get()
        ->sortBy(function ($nota) {
            return $nota->estudante->nome;
        });
        
        $codigo = date("Y-m-d");

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            'status' => 200,
            'titulo' => "Pauta - {$disciplina->disciplina}",
            'turma' => $turma,
            'notas' => $notas,
            'disciplina' => $disciplina,
            'anoLectivo' => $anoLectivo,
            'trimestre' => $trimestre,
           
            "trimestre1" => ControlePeriodico::where("trimestre", "Iª Trimestre")->first(),
            "trimestre2" => ControlePeriodico::where("trimestre", "IIª Trimestre")->first(),
            "trimestre3" => ControlePeriodico::where("trimestre", "IIIª Trimestre")->first(),
        ];

        $pdf = \PDF::loadView('downloads.turmas.ficha-mini-pauta', $headers)->setPaper('A4', 'landscape');
        return $pdf->stream("Pauta-{$turma->turma}-{$disciplina->disciplina}-{$codigo}.pdf");
    }

    // mini pauta
    public function miniPautaExcel($turma, $disciplina, $trimestre)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário
        $turma = Turma::findOrFail(Crypt::decrypt($turma));
        $disciplina = Disciplina::findOrFail(Crypt::decrypt($disciplina));
        $trimestre = ControlePeriodico::findOrFail(Crypt::decrypt($trimestre));

        $codigo = date("Y-m-d");

        return Excel::download(new MiniPautaExport($turma, $disciplina, $trimestre), "Pauta-{$turma->turma}-{$disciplina->disciplina}-{$codigo}.xlsx");
    }

    // mini pauta todas
    public function miniPautaTodas($turma, $trimestre)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $turma = Turma::with(['curso','classe','turno','sala'])->findOrFail(Crypt::decrypt($turma));
        $trimestre = ControlePeriodico::findOrFail(Crypt::decrypt($trimestre));
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $codigo = date("Y-m-d");
        
        $disciplinasTurma = DisciplinaTurma::with(['disciplina'])->where('turmas_id', $turma->id)->get();
        $estudantesTurma = EstudantesTurma::with(['estudante'])->where('turmas_id', $turma->id)->get()
        ->sortBy(function ($estudante) {
            return $estudante->estudante->nome;
        });
            

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            'status' => 200,
            "titulo" => "PAUTA - TODAS DISCIPLINA",
            "disciplinasTurma" => $disciplinasTurma,
            "estudantesTurma" => $estudantesTurma,
            "turma" => $turma,
            'anoLectivo' => AnoLectivo::findOrFail($this->anolectivoActivo()),
            'trimestre' => $trimestre,
        ];

        $pdf = \PDF::loadView('downloads.turmas.ficha-mini-pauta-todas', $headers)->setPaper('A2', 'landscape');
        return $pdf->stream("Pauta-{$turma->turma}-{$codigo}.pdf");
    }

    // mini pauta todas
    public function miniPautaTodasExcel($turma, $trimestre)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $turma = Turma::findOrFail(Crypt::decrypt($turma));
        $trimestre = ControlePeriodico::findOrFail(Crypt::decrypt($trimestre));

        $codigo = date("Y-m-d");

        return Excel::download(new MiniPautaTodasDisciplinasExport($turma, $trimestre), "Pauta-{$turma->turma}-{$codigo}.xlsx");
    }

    // financeiro pagamento
    public function financeiroPagamento($data1 = NULL, $data2 = NULL, $filtro = NULL)
    {

        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário
        $servico = Servico::find($filtro);

        if ($filtro != "todas") {
            $pagamentos = Pagamento::whereBetween('data_at', [$data1, $data2])
                ->where([
                    ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                    ['servicos_id', '=', $filtro],
                ])
                ->join('users', 'tb_pagamentos.funcionarios_id', '=', 'users.id')
                ->join('tb_servicos', 'tb_pagamentos.servicos_id', '=', 'tb_servicos.id')
                ->select('tb_servicos.servico', 'users.usuario', 'tb_pagamentos.ficha', 'tb_pagamentos.quantidade', 'tb_pagamentos.data_at', 'tb_pagamentos.funcionarios_id', 'tb_pagamentos.multa', 'tb_pagamentos.desconto', 'tb_pagamentos.model', 'tb_pagamentos.estudantes_id', 'tb_pagamentos.valor', 'tb_pagamentos.id')
                ->get();

            $pagamentosValores = Pagamento::whereBetween('data_at', [$data1, $data2])
                ->where([
                    ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                    ['servicos_id', '=', $filtro],
                ])
                ->sum('valor');

            $pagamentosQuantidade = Pagamento::whereBetween('data_at', [$data1, $data2])
                ->where([
                    ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                    ['servicos_id', '=', $filtro],
                ])
                ->sum('quantidade');

            $pagamentosDesconto = Pagamento::whereBetween('data_at', [$data1, $data2])
                ->where([
                    ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                    ['servicos_id', '=', $filtro],
                ])
                ->sum('desconto');

            $pagamentosMulta = Pagamento::whereBetween('data_at', [$data1, $data2])
                ->where([
                    ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                    ['servicos_id', '=', $filtro],
                ])
                ->sum('multa');
        }
        if (isset($data1) and isset($data2) and isset($filtro)) {
            $pagamentos = Pagamento::whereBetween('data_at', [$data1, $data2])
                ->where([
                    ['ano_lectivos_id', '=', $this->anolectivoActivo()]
                ])
                ->join('users', 'tb_pagamentos.funcionarios_id', '=', 'users.id')
                ->join('tb_servicos', 'tb_pagamentos.servicos_id', '=', 'tb_servicos.id')
                ->select('tb_servicos.servico', 'tb_pagamentos.ficha', 'users.usuario', 'tb_pagamentos.quantidade', 'tb_pagamentos.data_at', 'tb_pagamentos.funcionarios_id', 'tb_pagamentos.multa', 'tb_pagamentos.desconto', 'tb_pagamentos.model', 'tb_pagamentos.estudantes_id', 'tb_pagamentos.valor', 'tb_pagamentos.id')
                ->get();

            $pagamentosValores = Pagamento::whereBetween('data_at', [$data1, $data2])
                ->where([
                    ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ])
                ->sum('valor');

            $pagamentosQuantidade = Pagamento::whereBetween('data_at', [$data1, $data2])
                ->where([
                    ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ])
                ->sum('quantidade');

            $pagamentosDesconto = Pagamento::whereBetween('data_at', [$data1, $data2])
                ->where([
                    ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ])
                ->sum('desconto');

            $pagamentosMulta = Pagamento::whereBetween('data_at', [$data1, $data2])
                ->where([
                    ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ])
                ->sum('multa');
        } else {
            $pagamentos = Pagamento::where([
                ['ano_lectivos_id', '=', $this->anolectivoActivo()]
            ])
                ->join('users', 'tb_pagamentos.funcionarios_id', '=', 'users.id')
                ->join('tb_servicos', 'tb_pagamentos.servicos_id', '=', 'tb_servicos.id')
                ->select('tb_servicos.servico', 'tb_pagamentos.ficha', 'users.usuario', 'tb_pagamentos.quantidade', 'tb_pagamentos.data_at', 'tb_pagamentos.funcionarios_id', 'tb_pagamentos.multa', 'tb_pagamentos.desconto', 'tb_pagamentos.model', 'tb_pagamentos.estudantes_id', 'tb_pagamentos.valor', 'tb_pagamentos.id')
                ->get();

            $pagamentosValores = Pagamento::where([
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ])->sum('valor');

            $pagamentosQuantidade = Pagamento::where([
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ])->sum('quantidade');

            $pagamentosDesconto = Pagamento::where([
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ])->sum('desconto');

            $pagamentosMulta = Pagamento::where([
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ])->sum('multa');
        }



        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,

            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            'pagamentos' => $pagamentos,
            'pagamentosValores' => $pagamentosValores,
            'pagamentosQuantidade' => $pagamentosQuantidade,
            'pagamentosDesconto' => $pagamentosDesconto,
            'pagamentosMulta' => $pagamentosMulta,
            'verAnoLectivoActivo' => AnoLectivo::find($this->anolectivoActivo()),
            'servico' => $servico,
            'data1' => $data1,
            'data2' => $data2,
        ];

        $pdf = \PDF::loadView('downloads.financeiros.ficha-pagamentos', $headers)->setPaper('A4', 'landscape');
        return $pdf->stream('turmas.financeiros.ficha-pagamentos.pdf');
    }

    // financeiro pagamento
    public function facturaAliquidarPagamento(Request $request)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário
        $user = auth()->user();

        if (!$user->can('read: factura')  && !$user->can('update: factura')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }


        $servico = Servico::find($request->filtro);

        $pagamentos = Pagamento::whereIn('tipo_factura', ["FT", "FP"])
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->with('operador', 'servico')
            ->get();




        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,


            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            'pagamentos' => $pagamentos,
            'verAnoLectivoActivo' => AnoLectivo::find($this->anolectivoActivo()),

            "servico" => $servico,
            "data1" => $request->data1,
            "data2" => $request->data2,
        ];

        $pdf = \PDF::loadView('downloads.financeiros.ficha-pagamentos', $headers)
            // ->setPaper('A4', 'landscape')
        ;
        return $pdf->stream('turmas.financeiros.ficha-pagamentos.pdf');
    }


    // financeiro pagamento
    public function financeiroPagamentoCancelado()
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $pagamentos = Pagamento::where([
            ['tb_pagamentos.ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['tb_pagamentos.status', '=', "cancelado"],
        ])
            ->join('users', 'tb_pagamentos.funcionarios_id', '=', 'users.id')
            ->join('tb_servicos', 'tb_pagamentos.servicos_id', '=', 'tb_servicos.id')
            ->select('tb_servicos.servico', 'tb_pagamentos.ficha', 'tb_pagamentos.status', 'users.usuario', 'tb_pagamentos.quantidade', 'tb_pagamentos.data_at', 'tb_pagamentos.funcionarios_id', 'tb_pagamentos.multa', 'tb_pagamentos.desconto', 'tb_pagamentos.model', 'tb_pagamentos.estudantes_id', 'tb_pagamentos.valor', 'tb_pagamentos.id')
            ->get();

        $pagamentosValores = Pagamento::where([
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['tb_pagamentos.status', '=', "cancelado"],
        ])->sum('valor');

        $pagamentosQuantidade = Pagamento::where([
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['tb_pagamentos.status', '=', "cancelado"],
        ])->sum('quantidade');

        $pagamentosDesconto = Pagamento::where([
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['tb_pagamentos.status', '=', "cancelado"],
        ])->sum('desconto');

        $pagamentosMulta = Pagamento::where([
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['tb_pagamentos.status', '=', "cancelado"],
        ])->sum('multa');




        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,

            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            'pagamentos' => $pagamentos,
            'pagamentosValores' => $pagamentosValores,
            'pagamentosQuantidade' => $pagamentosQuantidade,
            'pagamentosDesconto' => $pagamentosDesconto,
            'pagamentosMulta' => $pagamentosMulta,
            'verAnoLectivoActivo' => AnoLectivo::find($this->anolectivoActivo()),
        ];

        $pdf = \PDF::loadView('downloads.financeiros.ficha-pagamentos-cancelados', $headers)->setPaper('A4', 'landscape');
        return $pdf->stream('turmas.financeiros.ficha-pagamentos-cancelados.pdf');
    }

    // financeiro pagamento
    public function outrasBuscasBaixa(Request $request)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $pagamentosDetalhes = [];
        $servico = Servico::find($request->servico_id);

        if ($servico) {
            if (strtolower($servico->servico) == "propinas" || strtolower($servico->servico) == "propina") {
                $pagamentosDetalhes = $this->carregarMesePago($this->mmes($request->mensals), $servico->id, $request->ano_lectivo_id);
            }
        } else {
            // dd("gfhjkjdsdf");
        }

        $pagamentos = Pagamento::when($request->ano_lectivo_id, function ($query, $value) {
            $query->where('ano_lectivos_id',  $value);
        })
            ->when($request->servico_id, function ($query, $value) {
                $query->where('servicos_id',  $value);
            })
            ->when($request->mensals, function ($query, $value) {
                $query->where('mensal',  $value);
            })
            ->with(['operador', 'servico'])
            ->get();

        $pagamentosValores = Pagamento::when($request->ano_lectivo_id, function ($query, $value) {
            $query->where('ano_lectivos_id',  $value);
        })
            ->when($request->servico_id, function ($query, $value) {
                $query->where('servicos_id',  $value);
            })
            ->when($request->mensals, function ($query, $value) {
                $query->where('mensal',  $value);
            })
            ->sum('valor');

        $pagamentosQuantidade = Pagamento::when($request->ano_lectivo_id, function ($query, $value) {
            $query->where('ano_lectivos_id',  $value);
        })
            ->when($request->servico_id, function ($query, $value) {
                $query->where('servicos_id',  $value);
            })
            ->when($request->mensals, function ($query, $value) {
                $query->where('mensal',  $value);
            })
            ->sum('quantidade');

        $pagamentosDesconto = Pagamento::when($request->ano_lectivo_id, function ($query, $value) {
            $query->where('ano_lectivos_id',  $value);
        })
            ->when($request->servico_id, function ($query, $value) {
                $query->where('servicos_id',  $value);
            })
            ->when($request->mensals, function ($query, $value) {
                $query->where('mensal',  $value);
            })
            ->sum('desconto');

        $pagamentosMulta = Pagamento::when($request->ano_lectivo_id, function ($query, $value) {
            $query->where('ano_lectivos_id',  $value);
        })
            ->when($request->servico_id, function ($query, $value) {
                $query->where('servicos_id',  $value);
            })
            ->when($request->mensals, function ($query, $value) {
                $query->where('mensal',  $value);
            })
            ->sum('multa');



        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,

            'pagamentos' => $pagamentos,
            'pagamentosDetalhes' => $pagamentosDetalhes,
            'pagamentosValores' => $pagamentosValores,
            'pagamentosQuantidade' => $pagamentosQuantidade,
            'pagamentosDesconto' => $pagamentosDesconto,
            'pagamentosMulta' => $pagamentosMulta,
            'verAnoLectivoActivo' => AnoLectivo::find($request->ano_lectivo_id),
            'servico' => $servico,
            'mes_mensal ' => $request->mensals,
        ];

        // , 'landscape'

        $pdf = \PDF::loadView('downloads.financeiros.ficha-outras-bucas', $headers)->setPaper('A4');
        return $pdf->stream('turmas.financeiros.ficha-outras-bucas.pdf');
    }

    public function mapaEfectividadePrint(Request $request)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $professores = FuncionariosControto::with(['funcionario'])->where('shcools_id', '=', $this->escolarLogada())
            ->where('level', '4')
            ->where('cargo_geral', 'professor')
            ->where('status', 'activo')
            ->get();

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,

            "usuario" => User::findOrFail(Auth::user()->id),
            "professores" => $professores,
            "requests" => $request->all("data_inicio", "data_final"),
        ];

        $pdf = \PDF::loadView('downloads.turmas.mapa-efectividade', $headers)->setPaper('A3', 'landscape');
        return $pdf->stream('ficha-efectividade-efectividade.pdf');
    }


    public function mapaEfectividadePrintExcel(Request $request)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário
        $codigo = date("Y-m-d");

        return Excel::download(new MapaEfectividadeProfessoresExport($request->data_inicio, $request->data_final), "mapa-de-efectividade-professores{$codigo}.xlsx");
    }

    // retificado
    public function propinasPorCursoPdf(Request $request)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // 4 GB


        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);


        if (!$request->anos_lectivos_id) {
            $request->anos_lectivos_id = $this->anolectivoActivo();
        }

        if (!$request->mes_id) {
            $request->mes_id = date("M");
        }

        $ano_lectivos = AnoLectivo::where('shcools_id', $this->escolarLogada())->get();

        $anolectivo = AnoLectivo::findOrFail($request->anos_lectivos_id);
        $servico = Servico::where('servico', 'Propinas')->where('shcools_id', $this->escolarLogada())->first();

        $mes = $request->mes_id;

        $cursos = DB::table('tb_ano_lectivo_cursos as alc')
            ->join('tb_cursos as c', 'c.id', '=', 'alc.cursos_id')
            ->select('alc.id as id_ano_curso', 'c.id as curso_id', 'c.curso', 'alc.ano_lectivos_id')
            ->where('alc.shcools_id', $this->escolarLogada())
            ->where('alc.ano_lectivos_id', $anolectivo->id)
            ->get()
            ->map(function ($curso) use ($anolectivo, $mes, $escola, $servico) {

                $classes = DB::table('tb_ano_lectivo_classes as alcl')
                    ->join('tb_classes as cl', 'cl.id', '=', 'alcl.classes_id')
                    ->leftJoin('tb_matriculas as m', function ($join) use ($curso, $anolectivo) {
                        $join->on('m.classes_id', '=', 'alcl.classes_id')
                            ->where('m.cursos_id', '=', $curso->curso_id)
                            ->where('m.ano_lectivos_id', '=', $anolectivo->id);
                    })
                    ->leftJoin('tb_cartao_estudantes as ce', 'ce.estudantes_id', '=', 'm.estudantes_id')
                    // 🔹 Adiciona o JOIN na tabela turmas para pegar o valor da propina
                    ->leftJoin('tb_turmas as t', function ($join) use ($curso, $anolectivo, $escola) {
                        $join->on('t.classes_id', '=', 'alcl.classes_id')
                            ->where('t.cursos_id', '=', $curso->curso_id)
                            ->where('t.shcools_id', '=', $escola->id)
                            ->where('t.ano_lectivos_id', '=', $anolectivo->id);
                    })
                    ->where('alcl.ano_lectivos_id', $anolectivo->id)
                    ->where('ce.month_name', $mes)
                    ->where('ce.servicos_id', $servico->id)
                    ->select(
                        'cl.classes',
                        DB::raw('IFNULL(t.valor_propina, 0) as valor_propina'), // 🔹 valor da mensalidade
                        DB::raw('COUNT(DISTINCT m.estudantes_id) as total_estudantes'),
                        DB::raw("COUNT(DISTINCT CASE WHEN ce.status IN ('Pago', 'Isento') AND ce.mes_id = 'M' THEN m.estudantes_id END) as total_pago"),
                        DB::raw("COUNT(DISTINCT CASE WHEN ce.status IN ('Nao Pago', 'divida') AND ce.mes_id = 'M' THEN m.estudantes_id END) as total_nao_pago")
                    )
                    ->groupBy('cl.id', 'cl.classes', 't.valor_propina')
                    ->orderBy('cl.id') // 🔹 garante que as classes não fiquem duplicadas
                    ->get();

                // 🔹 Soma total de estudantes e totais gerais
                $curso->classes = $classes;
                $curso->total_geral = $classes->sum('total_estudantes');
                $curso->total_pago = $classes->sum('total_pago');
                $curso->total_nao_pago = $classes->sum('total_nao_pago');

                return $curso;
            });


        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "titulo" => "Mapas de pagamentos de Propinas Referente ao Mês {$request->mes_id} de 2025",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "cursos" => $cursos,
            "anolectivo" => $anolectivo,
            "ano_lectivos" => $ano_lectivos,
            "requests" => ['anos_lectivos_id' => $request->anos_lectivos_id, 'mes_id' => $request->mes_id]
        ];

        $pdf = \PDF::loadView('downloads.financeiros.mapa-pagamentos-por-curso', $headers)->setPaper('A4', 'landscape');
        return $pdf->stream('mapa-pagamentos-por-curso.pdf');
    }

    // retificado
    public function financeiroPagamentoReceber(Request $request)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // 4 GB

        if (!$request->ano_lectivo) {
            $request->ano_lectivo = $this->anolectivoActivo();
        }

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

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

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "titulo" => "Pagamentos",
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            "pagamentos" => $pagamentos,
            "servico" => Servico::find($request->servico),
            "requests" => $request->all('data_inicio', 'data_final', 'all'),
        ];

        $pdf = \PDF::loadView('downloads.financeiros.ficha-pagamentos-receber', $headers)->setPaper('A4', 'portrait');
        return $pdf->stream('turmas.financeiros.ficha-pagamentos-receber.pdf');
    }

    public function financeiroPagamentoPagar(Request $request)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        if (!$request->ano_lectivo) {
            $request->ano_lectivo = $this->anolectivoActivo();
        }

        $pagamentos = Pagamento::when($request->data_inicio, function ($query, $value) {
            $query->whereDate('data_at', '>=', Carbon::createFromDate($value));
        })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('data_at', '<=', Carbon::createFromDate($value));
            })
            ->when($request->ano_lectivo_id, function ($query, $value) {
                $query->where('ano_lectivos_id', $value);
            })
            ->when($request->forma_pagamento_id, function ($query, $value) {
                $query->where('pagamento_id', $value);
            })
            ->when($request->servico_id, function ($query, $value) {
                $query->where('servicos_id', $value);
            })
            ->when($request->type, function ($query, $value) {
                $query->where('caixa_at', $value);
            })
            ->with(['servico', 'operador'])
            ->get();



        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "titulo" => "LISTA DE PAGAMENTOS A PAGAR",

            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            'pagamentos' => $pagamentos,
            "servico" => Servico::find($request->servico),
            "requests" => $request->all('data_inicio', 'data_final'),
        ];

        $pdf = \PDF::loadView('downloads.financeiros.ficha-pagamentos-pagar', $headers); //->setPaper('A4', 'landscape');
        return $pdf->stream('turmas.financeiros.ficha-pagamentos-pagar.pdf');
    }

    // ficha ou recibo de pagamento de propinas
    public function fichaPagamentoPropina($code)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $user = auth()->user();

        $pagamento = Pagamento::where('ficha', $code)
            ->where('model', 'estudante')
            ->with(['servico', 'operador', 'factura_recibo'])
            ->first();

        $matricula = Matricula::where('estudantes_id', $pagamento->estudantes_id)
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('status_matricula', 'confirmado')
            ->first();

        $estudante = Estudante::find($pagamento->estudantes_id);

        $detalhe = DetalhesPagamentoPropina::where('code', $pagamento->ficha)
            ->with(['servico'])
            ->get();

        $total_incidencia_ise = 0;
        $total_iva_ise = 0;

        $total_incidencia_nor = 0;
        $total_iva_nor = 0;

        $total_incidencia_out = 0;
        $total_iva_out = 0;

        foreach ($detalhe as $item) {

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
            'turno' => $turno,
            'turma' => $turma,

            "total_incidencia_nor" => $total_incidencia_nor,
            "total_iva_nor" => $total_iva_nor,

            "total_incidencia_ise" => $total_incidencia_ise,
            "total_iva_ise" => $total_iva_ise,

            "total_incidencia_out" => $total_incidencia_out,
            "total_iva_out" => $total_iva_out,

            'sala' => Sala::findOrFail($turma->salas_id),
            'funcionarioAtendente' => User::findOrFail($pagamento->funcionarios_id),
        ];

        $pdf = \PDF::loadView('downloads.financeiros.ficha-pagamento-propina', $headers)->setPaper('A4', 'portrait');
        return $pdf->stream('ficha-pagamento-propina.pdf');
    }

    // ficha ou recibo de pagamento de propinas
    public function fichaPagamentoPropinaRecibo($code)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário
        $user = auth()->user();

        if (!$user->can('create: pagamento')  && !$user->can('read: pagamento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }


        $pagamento = Pagamento::where([
            ['ficha', '=', $code],
            ['model', '=', 'estudante'],
        ])
            ->with('servico')
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
            ->with('servico')
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
            'funcionarioAtendente' => User::findOrFail($pagamento->funcionarios_id),
        ];

        // // return view('relatorios.ficha-pagamento-propina', $headers);

        $pdf = \PDF::loadView('downloads.financeiros.ficha-pagamento-propina-recibo', $headers)->setPaper('A4', 'portrait');
        return $pdf->stream('ficha-pagamento-propina-recibo.pdf');
    }

    // ficha ou recibo de pagamento de propinas
    public function fichaPagamentoPropinaNotaCredito($code)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário
        $user = auth()->user();

        if (!$user->can('read: factura')  && !$user->can('read: pagamento')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }


        $pagamento = Pagamento::where([
            ['ficha', '=', $code],
            ['model', '=', 'estudante'],
        ])
            ->with('servico')
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
            ->with('servico')
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
            'funcionarioAtendente' => User::findOrFail($pagamento->funcionarios_id),

        ];

        // // return view('relatorios.ficha-pagamento-propina', $headers);

        $pdf = \PDF::loadView('downloads.financeiros.ficha-pagamento-propina-nota-credito', $headers)->setPaper('A4', 'portrait');
        return $pdf->stream('ficha-pagamento-propina-nota-credito.pdf');
    }


    // ficha ou recibo de pagamento de propinas
    public function fichaPagamentoServico($code)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $pagamento = Pagamento::where([
            ['ficha', '=', $code],
            ['model', '=', 'escola'],
        ])
            ->with('servico')
            ->first();

        $escola = Shcool::findOrFail($pagamento->estudantes_id);

        $detalhe = DetalhesPagamentoPropina::where('pagamentos_id', '=', $pagamento->id)->get();



        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,

            'pagamento' => $pagamento,
            'escolaId' => $escola,
            'detalhes' => $detalhe,
            'funcionario' => User::findOrFail($pagamento->funcionarios_id),
        ];

        $pdf = \PDF::loadView('downloads.financeiros.ficha-pagamento-servico', $headers)->setPaper('A4', 'portrait');
        return $pdf->stream('ficha-ficha-pagamento-servico.pdf');
    }

    // ficha ou recibo de pagamento de propinas
    public function fichaPagamentoOutros($code)
    {

        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $pagamento = Pagamento::where([
            ['ficha', '=', $code],
            ['model', '=', 'estudante'],
        ])->first();

        $matricula = Matricula::where([
            ['estudantes_id', '=', $pagamento->estudantes_id],
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
        ])->first();

        $estudante = Estudante::find($pagamento->estudantes_id);

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
            'curso' => $curso,
            'classe' => $classe,
            'turno' => $turno,
            'turma' => $turma,
            'sala' => Sala::findOrFail($turma->salas_id),
            'funcionario' => User::findOrFail($pagamento->funcionarios_id),
        ];

        $pdf = \PDF::loadView('downloads.financeiros.ficha-pagamento-outros', $headers)->setPaper('A4', 'portrait');
        return $pdf->stream('ficha-pagamento-outros.pdf');
    }

    // ficha ou recibo de pagamento de propinas
    public function fichaPagamentoSalario($code)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $pagamento = Pagamento::with('forma_pagamento')->where([
            ['ficha', '=', $code],
            ['model', '=', 'professor'],
        ])->first();

        $contrato = FuncionariosControto::with(['departamento', 'cargos'])->where('funcionarios_id', $pagamento->estudantes_id)
            ->where('level', '4')
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->first();

        $funcionario = Professor::find($pagamento->estudantes_id);

        $detalhe = DetalhesPagamentoPropina::where('code', '=', $pagamento->ficha)->get();



        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,

            "contrato" => $contrato,
            'pagamento' => $pagamento,
            'funcionario' => $funcionario,
            'detalhes' => $detalhe,
            // 'irt' => $tabelaIrt,
            'operador' => User::findOrFail($pagamento->funcionarios_id),
        ];

        $pdf = \PDF::loadView('downloads.financeiros.ficha-pagamento-salario', $headers)->setPaper('A4', 'portrait');
        return $pdf->stream('ficha-pagamento-salario.pdf');
    }

    // extrato do estudante
    // retificado
    public function extratoEstudante(Request $request)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $estudantes = Estudante::findOrFail(Crypt::decrypt($request->id));
        $servico = Servico::findOrFail(Crypt::decrypt($request->servico));
        $ano_lectivo = AnoLectivo::findOrFail(Crypt::decrypt($request->ano));

        if ($request->cod ? Crypt::decrypt($request->cod) : $request->cod == "Meses_Obrigatorios") {
            $cartao = CartaoEstudante::with('servico')->where([
                ['estudantes_id', '=', $estudantes->id],
                ['servicos_id', '=', $servico->id],
                ['status', '<>', "excepto"],
                ['ano_lectivos_id', '=', $ano_lectivo->id],
            ])
                ->get();
        } else if ($request->cod ? Crypt::decrypt($request->cod) : $request->cod == "Meses_Bloqueado") {
            $cartao = CartaoEstudante::with('servico')->where([
                ['estudantes_id', '=', $estudantes->id],
                ['servicos_id', '=', $servico->id],
                ['status', '=', "excepto"],
                ['ano_lectivos_id', '=', $ano_lectivo->id],
            ])
                ->get();
        } else if ($request->cod ? Crypt::decrypt($request->cod) : $request->cod == "Meses_Devendo") {
            $cartao = CartaoEstudante::with('servico')->where([
                ['estudantes_id', '=', $estudantes->id],
                ['servicos_id', '=', $servico->id],
                ['status', '=', "divida"],
                ['ano_lectivos_id', '=', $ano_lectivo->id],
            ])
                ->get();
        } else if ($request->cod ? Crypt::decrypt($request->cod) : $request->cod == "Meses_Nao_Pago") {
            $cartao = CartaoEstudante::with('servico')->where([
                ['estudantes_id', '=', $estudantes->id],
                ['servicos_id', '=', $servico->id],
                ['status', '=', "Nao Pago"],
                ['ano_lectivos_id', '=', $ano_lectivo->id],
            ])
                ->get();
        } else if ($request->cod ? Crypt::decrypt($request->cod) : $request->cod == "Meses_Pago") {
            $cartao = CartaoEstudante::with('servico')->where([
                ['estudantes_id', '=', $estudantes->id],
                ['servicos_id', '=', $servico->id],
                ['status', '=', "Pago"],
                ['ano_lectivos_id', '=', $ano_lectivo->id],
            ])
                ->get();
        } else {
            $cartao = CartaoEstudante::with('servico')->where([
                ['estudantes_id', '=', $estudantes->id],
                ['servicos_id', '=', $servico->id],
                ['ano_lectivos_id', '=', $ano_lectivo->id],
            ])
                ->get();
        }

        $matricula = Matricula::where([
            ['estudantes_id', '=', $estudantes->id],
            ['ano_lectivos_id', '=', $ano_lectivo->id],
        ])->first();

        if (!$matricula) {
            Alert::warning('Informação', 'Este estudante não tem nenhuma matricula/confirmação no ano lectivo selecionado!');
            return redirect()->back();
        }

        $turma = Turma::where([
            ['classes_id', '=', $matricula->classes_id],
            ['cursos_id', '=', $matricula->cursos_id],
            ['turnos_id', '=', $matricula->turnos_id],
            ['ano_lectivos_id', '=', $ano_lectivo->id],
        ])->first();



        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,

            "estudante" => $estudantes,
            "matricula" => $matricula,
            "cartao" => $cartao,
            "curso" => Curso::findOrFail($turma->cursos_id),
            "classe" => Classe::findOrFail($turma->classes_id),
            "turno" => Turno::findOrFail($turma->turnos_id),
            "sala" => Sala::findOrFail($turma->salas_id),
            "ano" => $ano_lectivo,
            "turma" => $turma,
            "total_multa" => CartaoEstudante::where([
                ['estudantes_id', '=', $estudantes->id],
                ['servicos_id', '=', $servico->id],
                ['ano_lectivos_id', '=', $ano_lectivo->id],
            ])->sum('multa'),
            "mesesPago" => CartaoEstudante::where([
                ['estudantes_id', '=', $estudantes->id],
                ['servicos_id', '=', $servico->id],
                ['ano_lectivos_id', '=', $ano_lectivo->id],
                ['status', '=', 'Pago'],
            ])->count(),
            "mesesExcepto" => CartaoEstudante::where([
                ['estudantes_id', '=', $estudantes->id],
                ['servicos_id', '=', $servico->id],
                ['ano_lectivos_id', '=', $ano_lectivo->id],
                ['status', '=', 'excepto'],
            ])->count(),
            "mesesNaoPago" => CartaoEstudante::where([
                ['estudantes_id', '=', $estudantes->id],
                ['servicos_id', '=', $servico->id],
                ['ano_lectivos_id', '=', $ano_lectivo->id],
                ['status', '=', 'Nao Pago'],
            ])->count(),
            "mesesDividas" => CartaoEstudante::where([
                ['estudantes_id', '=', $estudantes->id],
                ['servicos_id', '=', $servico->id],
                ['ano_lectivos_id', '=', $ano_lectivo->id],
                ['status', '=', 'divida'],
            ])->count(),
            "calendario" => ServicoTurma::where([
                ['servicos_id', '=', $servico->id],
                ['turmas_id', '=', $turma->id],
                ['ano_lectivos_id', '=', $ano_lectivo->id],
            ])->first(),
            "condicao" => $request->cod ? Crypt::decrypt($request->cod) : $request->cod,
            "servico" => $servico,
        ];

        $pdf = \PDF::loadView('downloads.estudantes.extrato-estudante', $headers)->setPaper('A4', 'portrait');
        return $pdf->stream('extrato-estudante.pdf');
    }

    public function folhaSalario()
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário
        $contratos = FuncionariosControto::where([
            ['tb_contratos.ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['tb_contratos.shcools_id', '=', $this->escolarLogada()],
        ])
            ->join('tb_professores', 'tb_contratos.funcionarios_id', '=', 'tb_professores.id')
            ->get();

        $pagamentos = Pagamento::where([
            ['tb_pagamentos.ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['tb_pagamentos.caixa_at', '=', 'despesa'],
            ['tb_pagamentos.model', '=', 'funcionario'],
        ])->join('users', 'tb_pagamentos.funcionarios_id', '=', 'users.id')
            ->join('tb_professores', 'tb_pagamentos.estudantes_id', '=', 'tb_professores.id')
            ->select(
                'tb_professores.nome',
                'tb_professores.sobre_nome',
                'tb_professores.id',
                'tb_pagamentos.subcidio',
                'tb_pagamentos.subcidio_transporte',
                'tb_pagamentos.subcidio_alimentacao',
                'tb_pagamentos.irt',
                'tb_pagamentos.inss',
                'tb_pagamentos.faltas',
                'tb_pagamentos.desconto',
                'tb_pagamentos.valor'
            )
            ->get();



        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,

            "pagamentos" => $pagamentos,
            "contratos" => $contratos,
            "ano_lectivo" => $this->anolectivoActivo(),
        ];

        $pdf = \PDF::loadView('downloads.financeiros.ficha-salario', $headers)->setPaper('A3', 'landscape');
        return $pdf->stream('ficha-salario.pdf');
    }

    public function folhaSalarioMensal($mes)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário
        $meses = Mes::findOrFail($mes);

        $detalhes = DetalhesPagamentoPropina::where([
            ['model', '=', 'salario'],
            ['mes_id', '=', $meses->id],
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
        ])->get();

        $contratos = FuncionariosControto::where([
            ['tb_contratos.ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['tb_contratos.shcools_id', '=', $this->escolarLogada()],
        ])
            ->join('tb_professores', 'tb_contratos.funcionarios_id', '=', 'tb_professores.id')
            ->get();



        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [

            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "mes" => $meses,
            "detalhes" => $detalhes,
            "contratos" => $contratos,
            "ano_lectivo" => $this->anolectivoActivo(),
        ];

        $pdf = \PDF::loadView('downloads.financeiros.ficha-salario-mensal', $headers)->setPaper('A3', 'landscape');
        return $pdf->stream('ficha-salario-mensal.pdf');
    }

    // FUNCIONARIO
    // extratos
    public function funcionarioExtrato($id)
    {

        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $funcionario = Professor::findOrFail($id);


        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,

            "funcionario" => $funcionario,
            "extratoFinaceiro" => CartaoFuncionario::where([
                ['funcionarios_id', '=', $funcionario->id],
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ])
                ->join('tb_meses', 'tb_cartoes_funcionarios.mes_id', '=', 'tb_meses.id')
                ->get(),
            "contratos" => FuncionariosControto::where([
                ['funcionarios_id', '=', $funcionario->id],
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ])->first(),
            "mesesPagos" => CartaoFuncionario::where([
                ['funcionarios_id', '=', $funcionario->id],
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['status', '=', 'Pago'],
            ])->count(),
            "mesesNPagos" => CartaoFuncionario::where([
                ['funcionarios_id', '=', $funcionario->id],
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['status', '=', 'Nao pago'],
            ])->count(),

            "dividas" => CartaoFuncionario::where([
                ['funcionarios_id', '=', $funcionario->id],
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['status', '=', 'divida'],
            ])->count(),
        ];

        $pdf = \PDF::loadView('downloads.funcionarios.ficha-extrato', $headers)->setPaper('A4', 'portrait');
        return $pdf->stream('funcionarios.ficha-extrato.pdf');
    }

    // contrato
    public function funcionarioContrato($id)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário
        $contrato = FuncionariosControto::find(Crypt::decrypt($id));

        if ($contrato->cargo_geral == "professor") {
            $funcionario = Professor::find($contrato->funcionarios_id);
        } else {
            $funcionario = Funcionarios::find($contrato->funcionarios_id);
        }



        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,

            "funcionario" => $funcionario,
            "contrato" => $contrato,
        ];

        $pdf = \PDF::loadView('downloads.funcionarios.ficha-contrato', $headers)->setPaper('A4', 'portrait');
        $pdf->setOptions([
            "margin-top",
            20
        ]);
        return $pdf->stream('funcionarios.ficha-contrato.pdf');
    }

    // turmas
    public function funcionarioTurmas($id)
    {

        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $funcionario = Professor::findOrFail(Crypt::decrypt($id));

        $turmas = FuncionariosTurma::with(["professor", "turma", "disciplina"])
            ->select('turmas_id', 'shcools_id', 'ano_lectivos_id', 'funcionarios_id')
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('funcionarios_id', $funcionario->id)
            ->groupBy('turmas_id', 'shcools_id', 'ano_lectivos_id', 'funcionarios_id')
            ->get();

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,

            "funcionario" => $funcionario,
            "turmas" => $turmas,
            "contratos" => FuncionariosControto::where([
                ['funcionarios_id', '=', $funcionario->id],
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ])->first(),
        ];

        $pdf = \PDF::loadView('downloads.funcionarios.ficha-turmas', $headers)->setPaper('A4', 'portrait');
        return $pdf->stream('funcionarios.ficha-turmas.pdf');
    }

    // gerais
    public function funcionarioGeral($id)
    {        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário
        $funcionario = Professor::findOrFail($id);
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,

            "funcionario" => $funcionario,
            "academicos" => FuncionariosAcademico::where([
                ['funcionarios_id', '=', $funcionario->id]
            ])->first(),
            "contratos" => FuncionariosControto::where([
                ['funcionarios_id', '=', $funcionario->id],
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ])->first(),
            "turmas" => FuncionariosTurma::where([
                ['tb_turmas_funcionarios.funcionarios_id', '=', $funcionario->id],
                ['tb_turmas_funcionarios.ano_lectivos_id', '=', $this->anolectivoActivo()],
            ])
                ->join('tb_disciplinas', 'tb_turmas_funcionarios.disciplinas_id', '=', 'tb_disciplinas.id')
                ->join('tb_turmas', 'tb_turmas_funcionarios.turmas_id', '=', 'tb_turmas.id')
                ->get(),
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            "extratoFinaceiro" => CartaoFuncionario::where([
                ['funcionarios_id', '=', $funcionario->id],
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ])
                ->join('tb_meses', 'tb_cartoes_funcionarios.mes_id', '=', 'tb_meses.id')
                ->get(),
            "mesesPagos" => CartaoFuncionario::where([
                ['funcionarios_id', '=', $funcionario->id],
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['status', '=', 'Pago'],
            ])->count(),

            "mesesNPagos" => CartaoFuncionario::where([
                ['funcionarios_id', '=', $funcionario->id],
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['status', '=', 'Nao pago'],
            ])->count(),

            "dividas" => CartaoFuncionario::where([
                ['funcionarios_id', '=', $funcionario->id],
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['status', '=', 'divida'],
            ])->count(),
        ];

        $pdf = \PDF::loadView('downloads.funcionarios.ficha-geral', $headers)->setPaper('A4', 'portrait');
        return $pdf->stream('funcionarios.ficha-geral.pdf');
    }

    public function downloadListaPresencaTurmas($id)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $turma = Turma::findOrFail(Crypt::decrypt($id));
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);


        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,

            "estudantes" => ListaPresenca::where([
                ['turmas_id', '=', $turma->id],
                ['data_at', '=', $this->data_sistema()],
            ])
                ->join('tb_estudantes', 'tb_turma_presencas.estudantes_id', '=', 'tb_estudantes.id')
                ->join('tb_turmas', 'tb_turma_presencas.turmas_id', '=', 'tb_turmas.id')
                ->select('tb_estudantes.id', 'tb_estudantes.nome', 'tb_estudantes.sobre_nome', 'tb_estudantes.genero', 'tb_turma_presencas.status')
                ->orderByRaw('tb_estudantes.nome ASC ')
                ->get(),

            "results" => ListaPresenca::where([
                ['turmas_id', '=', $turma->id]
            ])
                ->join('tb_professores', 'tb_turma_presencas.funcionarios_id', '=', 'tb_professores.id')
                ->join('tb_disciplinas', 'tb_turma_presencas.disciplinas_id', '=', 'tb_disciplinas.id')
                ->join('tb_semanas', 'tb_turma_presencas.semanas_id', '=', 'tb_semanas.id')
                ->select('tb_semanas.semana', 'tb_professores.nome', 'tb_professores.sobre_nome', 'tb_disciplinas.disciplina')
                ->first(),

            "curso" => Curso::findOrFail($turma->cursos_id),
            "classe" => Classe::findOrFail($turma->classes_id),
            "turno" => Turno::findOrFail($turma->turnos_id),
            "sala" => Sala::findOrFail($turma->salas_id),
            "ano" => AnoLectivo::findOrFail($this->anolectivoActivo()),
            "turma" => $turma,
        ];

        $pdf = \PDF::loadView('downloads.turmas.lista-presenca', $headers)->setPaper('A4', 'portrait');
        return $pdf->stream('turma.lista-presenca.pdf');
    }


    public function carregarMesePago($mes, $servico,  $ano)
    {

        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $pagamentosDetalhe = DetalhesPagamentoPropina::where([
            ['status', '=', 'Pago'],
            ['mes', '=', $mes],
            ['servicos_id', '=', $servico],
            ['ano_lectivos_id', '=', $ano]
        ])
            ->get();

        if ($pagamentosDetalhe) {
            return $pagamentosDetalhe;
        } else {
            return [];
        }
    }
}
