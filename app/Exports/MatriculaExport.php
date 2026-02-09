<?php

namespace App\Exports;

use App\Http\Controllers\TraitHelpers;
use App\Models\Shcool;
use App\Models\web\calendarios\Matricula;
use App\Models\web\classes\Classe;
use App\Models\web\cursos\Curso;
use App\Models\web\turnos\Turno;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class MatriculaExport implements FromView, WithDrawings, WithTitle, WithCustomStartCell
{
    use TraitHelpers;
    
    private $status, $cursos_id, $classes_id, $turnos_id;
    
    public function __construct($request) {
        $this->status = $request->status;
        $this->cursos_id = $request->cursos_id;
        $this->classes_id = $request->classes_id;
        $this->turnos_id = $request->turnos_id;
    }
    
    public function title(): string
    {
        return "LISTAGEM DE ESTUDANTES MATRICULADOS";
    }

    public function startCell(): String
    {
        return "A11";
    }
    
    public function drawings()
    {
        $escola = Shcool::findOrFail($this->escolarLogada());
        
        // if($escola->logotipo){
        //     $img = $escola->logotipo;
        // }else {
            $img = "insigna.png";
        // }
        
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Este é o logotipo da Instituição');
        $drawing->setPath(public_path("assets/images/{$img}"));
        $drawing->setHeight(90);
        $drawing->setCoordinates('A1');

        return $drawing;
    }
    
    public function view(): View
    {
    
        $matriculas = Matricula::when($this->status, function($query, $value){
            $query->where('status_matricula', $value);
        })->when($this->cursos_id, function($query, $value){
            $query->where('cursos_id', $value);
        })->when($this->classes_id, function($query, $value){
            $query->where('classes_id', $value);
        })->when($this->turnos_id, function($query, $value){
            $query->where('turnos_id', $value);
        })
        ->with(
            'ano_lectivo',
            'classe_at',
            'classe',
            'turno',
            'curso',
            'estudante'
        )
        ->where([
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['shcools_id', '=', $this->escolarLogada()],
        ])
        ->where('status_inscricao', 'Admitido')
        ->get()
        ->sortBy(function($matricula) {
            return $matricula->estudante->nome;
        });
        
        return view('exports.estudantes-matriculados', [
        
            "titulo" => "LISTA DOS ESTUDANTES MATRICULADOS",
            "escola" => Shcool::find($this->escolarLogada()),
            
            "classe" => Classe::find($this->classes_id),
            "curso" => Curso::find($this->cursos_id),
            "turno" => Turno::find($this->turnos_id),
            
            "status" => $this->status,
            "matriculas" => $matriculas
           
        ]);
    }
}