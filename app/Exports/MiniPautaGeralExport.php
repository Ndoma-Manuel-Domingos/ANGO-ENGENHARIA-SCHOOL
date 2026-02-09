<?php

namespace App\Exports;

use App\Http\Controllers\TraitHelpers;
use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\anolectivo\ControlePeriodico;
use App\Models\web\anolectivo\Trimestre;
use App\Models\web\classes\Classe;
use App\Models\web\cursos\Curso;
use App\Models\web\disciplinas\Disciplina;
use App\Models\web\salas\Sala;
use App\Models\web\turmas\EstudantesTurma;
use App\Models\web\turmas\NotaPauta;
use App\Models\web\turmas\Turma;
use App\Models\web\turnos\Turno;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class MiniPautaGeralExport implements FromView, WithTitle, WithCustomStartCell
{
    use TraitHelpers;

    private $disciplina, $turma;

    public function __construct($turma, $disciplina)
    {
        $this->disciplina = $disciplina->id;
        $this->turma = $turma->id;
    }

    public function title(): string
    {
        $turma = Turma::findOrFail($this->turma);

        return "MINI PAUTAS - {$turma->turma}";
    }

    public function startCell(): String
    {
        return "A11";
    }

    public function view(): View
    {
        $estudantes = EstudantesTurma::with(['estudante'])
        ->where('turmas_id', $this->turma)
        ->get()
        ->sortBy(function($estudante) {
            return $estudante->estudante->nome;
        });

        $turma = Turma::findOrFail($this->turma);
        $disciplina = Disciplina::findOrFail($this->disciplina);
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $trimestre1 = Trimestre::where("trimestre", "Iª Trimestre")->first();
        $trimestre2 = Trimestre::where("trimestre", "IIª Trimestre")->first();
        $trimestre3 = Trimestre::where("trimestre", "IIIª Trimestre")->first();
        $trimestre4 = Trimestre::where("trimestre", "Geral")->first();

        return view('exports.mini-pautas-geral', [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            'disciplina' => $disciplina,
            'turma' => $turma,
            "trimestre1" => $trimestre1 ?? 0,
            "trimestre2" => $trimestre2 ?? 0,
            "trimestre3" => $trimestre3 ?? 0,
            "trimestre4" => $trimestre4 ?? 0,
            "estudantes" => $estudantes,
            'curso' => Curso::findOrFail($turma->cursos_id),
            'sala' => Sala::findOrFail($turma->salas_id),
            'classe' => Classe::findOrFail($turma->classes_id),
            'turno' => Turno::findOrFail($turma->turnos_id),
            'anoLectivo' => AnoLectivo::findOrFail($this->anolectivoActivo()),
        ]);
    }
}
