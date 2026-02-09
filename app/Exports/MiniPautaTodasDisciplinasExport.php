<?php

namespace App\Exports;

use App\Http\Controllers\TraitHelpers;
use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\anolectivo\ControlePeriodico;
use App\Models\web\classes\Classe;
use App\Models\web\cursos\Curso;
use App\Models\web\disciplinas\Disciplina;
use App\Models\web\salas\Sala;
use App\Models\web\turmas\DisciplinaTurma;
use App\Models\web\turmas\EstudantesTurma;
use App\Models\web\turmas\NotaPauta;
use App\Models\web\turmas\Turma;
use App\Models\web\turnos\Turno;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class MiniPautaTodasDisciplinasExport implements FromView, WithTitle, WithCustomStartCell
{
    use TraitHelpers;

    private $trimestre, $turma;

    public function __construct($turma, $trimestre)
    {
        $this->trimestre = $trimestre->id;
        $this->turma = $turma->id;
    }

    public function title(): string
    {
        $turma = Turma::findOrFail($this->turma);

        return "PAUTAS - TODAS DISCIPLINAS - {$turma->turma}";
    }

    public function startCell(): String
    {
        return "A11";
    }

    public function view(): View
    {
        $turma = Turma::findOrFail($this->turma);
        $trimestre = ControlePeriodico::findOrFail($this->trimestre);

        $disciplinasTurma = DisciplinaTurma::with(['disciplina'])->where('turmas_id', $turma->id)->get();
        $estudantesTurma = EstudantesTurma::with(['estudante'])
            ->where('turmas_id', $turma->id)
            ->get()
            ->sortBy(function($estudante) {
                return $estudante->estudante->nome;
            });
            
        $escola = Shcool::findOrFail($this->escolarLogada());
        $anoLectivo = AnoLectivo::findOrFail($this->anolectivoActivo());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        return view('exports.mini-pauta-todas-disciplinas', [
            'turma' => $turma,
            'trimestre' => $trimestre,
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,

            'curso' => Curso::findOrFail($turma->cursos_id),
            'sala' => Sala::findOrFail($turma->salas_id),
            'classe' => Classe::findOrFail($turma->classes_id),
            'turno' => Turno::findOrFail($turma->turnos_id),

            'disciplinasTurma' => $disciplinasTurma,
            'estudantesTurma' => $estudantesTurma,
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "anoLectivo" => $anoLectivo,
        ]);
    }
}
