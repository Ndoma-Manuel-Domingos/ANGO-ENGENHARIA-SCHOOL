<?php

namespace App\Http\Controllers;

use App\Models\Shcool;
use App\Models\User;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\calendarios\Matricula;
use App\Models\web\estudantes\Estudante;
use App\Models\web\funcionarios\Funcionarios;
use App\Models\web\funcionarios\FuncionariosControto;

use App\Charts\MatriculaChart;
use App\Charts\PagamentoChart;
use App\Models\web\anolectivo\EscolaFilhar;
use App\Models\web\calendarios\Pagamento;
use App\Models\web\calendarios\Servico;
use App\Models\web\classes\AnoLectivoClasse;
use App\Models\web\cursos\AnoLectivoCurso;
use App\Models\web\disciplinas\CandidaturaAnoLectivo;
use App\Models\web\disciplinas\DisciplinaAnoLectivo;
use App\Models\web\disciplinas\FaculdadeAnoLectivo;
use App\Models\web\encarregados\Encarregado;
use App\Models\web\salas\Sala;
use App\Models\web\turmas\Turma;
use App\Models\web\turnos\AnoLectivoTurno;
use Khill\Lavacharts\Lavacharts;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PainelController extends Controller
{
    //
    use TraitHelpers;
    use TraitHeader;
    
        
    public function __construct()
    {
        $this->middleware('auth');
    }
        
    //home
    public function home(Request $request)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        $this->controlo();
        
        $verAnoLectivoActivo = AnoLectivo::find($this->anolectivoActivo());
        
        $totalEstudantes = Estudante::where('shcools_id', $this->escolarLogada())
            ->where('status', 'activo')
            ->selectRaw('
                COUNT(CASE WHEN registro != "nao_confirmado" THEN 1 END) as total_estudantes,
                COUNT(CASE WHEN registro = "nao_confirmado" THEN 1 END) as total_estudantes_nao_confirmado
            ')
        ->first();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "totalEstudantesConfirmados" => $totalEstudantes->total_estudantes,
            "totalEstudantesNaoConfirmados" => $totalEstudantes->total_estudantes_nao_confirmado,
            "totalprofessores" => FuncionariosControto::where('shcools_id', $this->escolarLogada())
                ->where('level', '4')
                ->where('cargo_geral', 'professor')
            ->count(), 
            "verAnoLectivoActivo" => $verAnoLectivoActivo,
        ];

        return view('admin.paineis.home', $headers);
      
    }

    // inicio
    public function inicio()
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor
        
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());
        
        $headers = [ 
            "escola" => $escola,
            "usuario" => User::findOrFail(Auth::user()->id),
            "totalcursos" => AnoLectivoCurso::where('shcools_id', $escola->id)
                ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->count(),
            
            "totalclasses" => AnoLectivoClasse::where('shcools_id', $escola->id)
                ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->count(),

            "totalturnos" => AnoLectivoTurno::where('shcools_id', $escola->id)
                ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->count(),
            
            "totalescolasafilhares" => EscolaFilhar::where('shcools_id', $this->escolarLogada())->count(),

            "totalturmas" => Turma::where('status', 'activo')
                ->where('shcools_id', $escola->id)
                ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->count(),
            
            "totalProfessores" => FuncionariosControto::where('shcools_id', $this->escolarLogada())
                ->where('level', '4')
                ->where('cargo_geral', 'professor')
                ->count(), 
            
            "totaloutroFuncionarios" => Funcionarios::where('shcools_id', $this->escolarLogada())
                ->where('level', '4')
                ->count(), 

            "totalencarregados" => Encarregado::where('shcools_id', $escola->id)->count(), 

            "totaldisciplinas" => DisciplinaAnoLectivo::where('shcools_id', $escola->id)
                ->where('ano_lectivos_id', $this->anolectivoActivo())->count(),
            
            "totalfaculdades" => FaculdadeAnoLectivo::where('shcools_id', $escola->id)
                ->where('ano_lectivos_id', $this->anolectivoActivo())->count(),
            
            "totalcandidaturas" => CandidaturaAnoLectivo::where('shcools_id', $escola->id)
                ->where('ano_lectivos_id', $this->anolectivoActivo())->count(),

            "totalsalas" => Sala::where('shcools_id', $escola->id)->count(),

            "totalanolectivos" => AnoLectivo::where('shcools_id', $escola->id)->count(),

            "totalServicos" => Servico::where('status', 'activo')->where('shcools_id', $escola->id)->count(),

            "totalmatriculaseconfirmadoproximoano" => Matricula::where('ano_lectivos_id', $this->anolectivoProximo($this->anolectivoActivo()))
                ->whereIn('status_inscricao', ['Nao Admitido', 'Admitido'])
                ->whereIn('tipo', ['matricula', 'confirmacao', 'inscricao', 'candidatura'])
                ->where('shcools_id', $this->escolarLogada())
            ->count(),

            "totalmatriculas" => Matricula::where('ano_lectivos_id', $this->anolectivoActivo())
                ->where('status_inscricao', 'Admitido')
                ->where('shcools_id', $this->escolarLogada())
            ->count(),
            
            "totalestudantes" => Matricula::where('ano_lectivos_id', $this->anolectivoActivo())
                ->where('status_inscricao', 'Admitido')
                ->where('status_matricula', 'confirmado')
                ->where('shcools_id', $this->escolarLogada())
            ->count(),
    
            "totalmatriculasEscola" => Matricula::where('status_matricula', '!=', 'nao_confirmado')
                ->where('shcools_id', $escola->id)
                ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->count(),

            "totalinscritos" => Matricula::where('shcools_id', $escola->id)
                ->where('ano_lectivos_id', $this->anolectivoActivo())
                ->whereIn('tipo', ['inscricao', 'candidatura'])
                ->whereIn('status_inscricao', ['Nao Admitido', 'Admitido'])
                ->whereIn('status_matricula', ['rejeitado', 'nao_confirmado'])
            ->count(),

            "totalinscritosAceites" => Matricula::where('shcools_id', $escola->id)
                ->where('ano_lectivos_id', $this->anolectivoActivo())
                ->where('status_inscricao', 'Admitido')
                ->whereIn('tipo', ['inscricao', 'candidatura'])
            ->count(),

            "totalinscritosConfirmados" => Matricula::where('status_inscricao', 'Admitido')
                ->where('shcools_id', $escola->id)
                ->where('ano_lectivos_id', $this->anolectivoActivo())
                ->whereIn('tipo', ['inscricao', 'candidatura'])
            ->count(),

            "totalconfirmacao" => Matricula::where('shcools_id', $escola->id)
                ->where('ano_lectivos_id', $this->anolectivoActivo())
                ->where('tipo', 'confirmacao')
            ->count(),
            
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo())
        ];
        
      
        return view('admin.paineis.inicio', $headers);
        
    }

    public function validadeDaLicenca()
    {
        $controlo = Shcool::findOrFail($this->escolarLogada());

        if ($controlo->dias_licencas($controlo->id) <= 30) {
            return response()->json(['success' => true, 'dias_restantes' => $controlo->dias_licencas($controlo->id)]);
        }
    }

}
