<?php

namespace App\Exports;

use App\Http\Controllers\TraitHelpers;
use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\anolectivo\ControlePeriodico;
use App\Models\web\disciplinas\Disciplina;
use App\Models\web\turmas\NotaPauta;
use App\Models\web\turmas\Turma;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class MiniPautaExport implements FromView, WithTitle, WithCustomStartCell
{
    use TraitHelpers;

    private $disciplina, $trimestre, $turma;

    public function __construct($turma, $disciplina, $trimestre)
    {
        $this->disciplina = $disciplina->id;
        $this->trimestre = $trimestre->id;
        $this->turma = $turma->id;
    }

    public function title(): string
    {
        $turma = Turma::findOrFail($this->turma);
        $disciplina = Disciplina::findOrFail($this->disciplina);

        return "PAUTAS - {$turma->turma}-{$disciplina->disciplina}";
    }

    public function startCell(): String
    {
        return "A11";
    }

    public function view(): View
    {
        
        $anoLectivo = AnoLectivo::findOrFail($this->anolectivoActivo());
    
        $notas =  NotaPauta::with(['estudante'])->where('tb_notas_pautas.disciplinas_id', $this->disciplina)
            ->where('tb_notas_pautas.controlo_trimestres_id', $this->trimestre)
            // ->where('tb_notas_pautas.ano_lectivos_id', $anoLectivo->id)
            ->where('tb_notas_pautas.turmas_id', $this->turma)
            ->with(['estudante'])
           ->get()
            ->sortBy(function($estudante) {
                return $estudante->estudante->nome;
            });

        $turma = Turma::with(['classe', 'turno', 'sala', 'curso'])->findOrFail($this->turma);
        $disciplina = Disciplina::findOrFail($this->disciplina);
        $trimestre = ControlePeriodico::findOrFail($this->trimestre);
        $escola = Shcool::findOrFail($this->escolarLogada());

        $trimestre1 = ControlePeriodico::where('trimestre', 'Iª Trimestre')->first();
        $trimestre2 = ControlePeriodico::where('trimestre', 'IIª Trimestre')->first();
        $trimestre3 = ControlePeriodico::where('trimestre', 'IIIª Trimestre')->first();

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        return view('exports.mini-pautas', [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            'notas' => $notas,
            'disciplina' => $disciplina,
            'turma' => $turma,
            'anoLectivo' => $anoLectivo,
            'trimestre' => $trimestre,
            'trimestre1' => $trimestre1,
            'trimestre2' => $trimestre2,
            'trimestre3' => $trimestre3,
        ]);
    }
}
