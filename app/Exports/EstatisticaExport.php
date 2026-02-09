<?php

namespace App\Exports;

use App\Http\Controllers\TraitHelpers;
use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\anolectivo\Trimestre;
use App\Models\web\classes\Classe;
use App\Models\web\turmas\DisciplinaTurma;
use App\Models\web\turmas\EstudantesTurma;
use App\Models\web\turmas\Turma;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class EstatisticaExport implements FromView, WithTitle, WithCustomStartCell
{
    use TraitHelpers;
    
    private $ano_lectivos_id, $turma;
    
    public function __construct($turma, $ano_lectivos_id) {
        $this->ano_lectivos_id = $ano_lectivos_id;
        $this->turma = $turma;
    }
    
    public function title(): string
    {
        $turma = Turma::findOrFail($this->turma);
        
        return "PAUTAS FINAL- {$turma->turma}";
    }

    public function startCell(): String
    {
        return "A11";
    }
    
    // public function drawings()
    // {
    //     $escola = Shcool::findOrFail($this->escolarLogada());

    //     $drawing = new Drawing();
    //     $drawing->setName('Logo');
    //     $drawing->setDescription('Este é o logotipo da Instituição');
    //     $drawing->setPath(public_path("assets/images/{$escola->logotipo}"));
    //     $drawing->setHeight(90);
    //     $drawing->setCoordinates('A1');

    //     return $drawing;
    // }
    
    public function view(): View
    {
        $user = auth()->user();
                
        $turma = Turma::with(['curso', 'turno', 'classe', 'anolectivo'])->find($this->turma);
        $ano_lectivo = AnoLectivo::findOrFail($this->ano_lectivos_id);
      
        $ano_lectivo = AnoLectivo::find($ano_lectivo->id);

        $trimestre1 = Trimestre::where('trimestre', 'Iª Trimestre')->first();
        $trimestre2 = Trimestre::where('trimestre', 'IIª Trimestre')->first();
        $trimestre3 = Trimestre::where('trimestre', 'IIIª Trimestre')->first();
        $trimestre4 = Trimestre::where('trimestre', 'Geral')->first();
        
        $disciplinas = null;
        $estudantes = null;
        
        if ($turma) {
            $disciplinas = DisciplinaTurma::with(['disciplina'])
                ->where('turmas_id', $turma->id)
                ->get();
                
            $estudantes = EstudantesTurma::with(['estudante'])
                ->where('turmas_id', $turma->id)
                ->get()
                ->sortBy(function ($estudante) {
                    return $estudante->estudante->nome; // Ordena pela propriedade 'nome' do estudante
                });
        }
        
        return view('exports.estatistica', [
            "turma" => $turma,
            "ano_lectivo" => $ano_lectivo,
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "usuario" => $user,
            "titulo" => "PAUTA FINAL",
            // classe actual
            "disciplinas" => $disciplinas,
            "estudantes" => $estudantes,
            "turma" => $turma,
            
            "trimestre1" => $trimestre1,
            "trimestre2" => $trimestre2,
            "trimestre3" => $trimestre3,
            "trimestre4" => $trimestre4,
                        
            "anolectivos" => AnoLectivo::where('shcools_id', $this->escolarLogada())->get(),
            
            "escola" => Shcool::with(["ensino"])->findOrFail($this->escolarLogada()),
        ]);
    }
}