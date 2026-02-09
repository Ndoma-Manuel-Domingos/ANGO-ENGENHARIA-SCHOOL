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

class EstudanteExport implements FromView, WithDrawings, WithTitle, WithCustomStartCell
{
    use TraitHelpers;
    
    private $status, $cursos_id, $classes_id, $turnos_id, $genero, $finalista;
    
    public function __construct($request) {
        $this->status = $request->status;
        $this->cursos_id = $request->curso_id;
        $this->classes_id = $request->classes_id;
        $this->turnos_id = $request->turnos_id;
        $this->genero = $request->genero;
        $this->finalista = $request->finalista;
    }
    
    public function title(): string
    {
        return "LISTAGEM DOS ESTUDANTES";
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
        $drawing->setPath(public_path("/assets/images/{$img}"));
        $drawing->setHeight(90);
        $drawing->setCoordinates('A1');

        return $drawing;
    }
    
    public function view(): View
    {
        $genero = $this->genero;
        $finalista = $this->finalista;
    
        $matriculas = Matricula::where('status_matricula', '!=', 'nao_confirmado')
        ->where('status_matricula', '!=', 'rejeitado')
        ->where('status_inscricao', '=', 'Admitido')
        ->when($this->status, function($query, $value){
            $query->where('status_matricula', $value);
        })->when($this->cursos_id, function($query, $value){
            $query->where('cursos_id', $value);
        })->when($this->classes_id, function($query, $value){
            $query->where('classes_id', $value);
        })->when($this->turnos_id, function($query, $value){
            $query->where('turnos_id', $value);
        })
        ->whereHas('estudante', function($query) use ($genero, $finalista){
            $query->when($genero, function ($q, $v) {
                $q->where('genero', $v);
            });
            $query->when($finalista, function ($q, $v) {
                $q->where('finalista', $v);
            });
        })
        ->where('ano_lectivos_id', '=', $this->anolectivoActivo())
        ->where('shcools_id', '=', $this->escolarLogada())
        ->with(['escolas', 'ano_lectivo', 'classe_at', 'estudante', 'classe', 'turno', 'curso'])
        ->get()
        ->sortBy(function($matricula) {
            return $matricula->estudante->nome;
        });
                
        if ($this->finalista){ 
            if ($this->finalista == "Y"){
                $titulo = "LISTA DOS ESTUDANTES FINALISTAS";
            }
            $titulo = "LISTA DOS ESTUDANTES";
        }else{
            $titulo = "LISTA DOS ESTUDANTES";
        }
        
        return view('exports.estudantes', [
        
            "titulo" =>  $titulo,
            "escola" => Shcool::find($this->escolarLogada()),
            
            "classe" => Classe::find($this->classes_id),
            "curso" => Curso::find($this->cursos_id),
            "turno" => Turno::find($this->turnos_id),
            
            "genero" => $this->genero,
            
            "status" => $this->status,
            "matriculas" => $matriculas
           
        ]);
    }
}