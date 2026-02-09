<?php

namespace App\Exports;

use App\Http\Controllers\TraitHelpers;
use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\calendarios\Matricula;
use App\Models\web\classes\Classe;
use App\Models\web\cursos\Curso;
use App\Models\web\salas\Sala;
use App\Models\web\turmas\EstudantesTurma;
use App\Models\web\turmas\Turma;
use App\Models\web\turnos\Turno;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class EstudanteTurmaExport implements FromView, WithDrawings, WithTitle, WithCustomStartCell
{
    use TraitHelpers;
    
    private $id;
    
    public function __construct($request) {
        
        $this->id = Crypt::decrypt($request);
        
    }
    
    public function title(): string
    {
        $turma = Turma::findOrFail($this->id);
        
        return "LISTAGEM DOS ESTUDANTES DA TURMA - {$turma->turma}";
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
    
        $estudantes = EstudantesTurma::where([
            ['turmas_id', '=', $this->id],
            ['tb_matriculas.ano_lectivos_id', '=', $this->anolectivoActivo()],
        ])
        ->join('tb_estudantes', 'tb_turmas_estudantes.estudantes_id', '=', 'tb_estudantes.id')
        ->join('tb_matriculas', 'tb_turmas_estudantes.estudantes_id', '=', 'tb_matriculas.estudantes_id')
        ->select('tb_matriculas.numero_estudante','tb_matriculas.documento','tb_estudantes.id','tb_estudantes.nome', 'tb_estudantes.sobre_nome', 'tb_estudantes.genero', 'tb_estudantes.telefone_estudante')
        ->orderBy('tb_estudantes.nome') 
        ->get();
        
        $turma = Turma::findOrFail($this->id);
        $curso = Curso::findOrFail($turma->cursos_id);
        $classe = Classe::findOrFail($turma->classes_id);
        $turno = Turno::findOrFail($turma->turnos_id);
        $sala = Sala::findOrFail($turma->salas_id);
        $ano = AnoLectivo::findOrFail($this->anolectivoActivo());
            
            
        return view('exports.estudantes-turmas', [
        
            "titulo" =>  "LISTAGEM DO ESTUDANTES DA TURMA - {$turma->turma}",
            "escola" =>  Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "estudantes" => $estudantes,
            
            "curso" => $curso,
            "classe" => $classe,
            "turno" => $turno,
            "sala" => $sala,
            "ano" => $ano,
            "turma" => $turma,
           
        ]);
    }
}