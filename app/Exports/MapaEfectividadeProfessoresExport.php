<?php

namespace App\Exports;

use App\Http\Controllers\TraitHelpers;
use App\Models\Shcool;
use App\Models\web\anolectivo\ControlePeriodico;
use App\Models\web\classes\Classe;
use App\Models\web\cursos\Curso;
use App\Models\web\disciplinas\Disciplina;
use App\Models\web\funcionarios\FuncionariosControto;
use App\Models\web\salas\Sala;
use App\Models\web\turmas\DisciplinaTurma;
use App\Models\web\turmas\EstudantesTurma;
use App\Models\web\turmas\NotaPauta;
use App\Models\web\turmas\Turma;
use App\Models\web\turnos\Turno;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class MapaEfectividadeProfessoresExport implements FromView, WithDrawings, WithTitle, WithCustomStartCell
{
    use TraitHelpers;
    
    private $data_inicio, $data_final;
    
    public function __construct($data_inicio, $data_final) {
        $this->data_inicio = $data_inicio;
        $this->data_final = $data_final;
    }
    
    public function title(): string
    {
        return "MAPA DE EFECTIVIDADE DE PROFESSORES";
    }

    public function startCell(): String
    {
        return "A11";
    }
    
    public function drawings()
    {
        $escola = Shcool::findOrFail($this->escolarLogada());

        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Este é o logotipo da Instituição');
        $drawing->setPath(public_path("assets/images/{$escola->logotipo}"));
        $drawing->setHeight(90);
        $drawing->setCoordinates('A1');

        return $drawing;
    }
    
    public function view(): View
    {
        
        $professores = FuncionariosControto::with(['funcionario'])->where('shcools_id', '=', $this->escolarLogada())
        ->where('level', '4')
        ->where('cargo_geral', 'professor')
        ->where('status', 'activo')
        ->get();
                
        
        return view('exports.mapa-efectividade-professores', [
            "professores" => $professores,
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "requests" => [ 
                'data_inicio' => $this->data_inicio,
                'data_final' => $this->data_final,
            ], 
            "pesquisa_ano" => $this->anolectivoActivo(),
        ]);
    }
}