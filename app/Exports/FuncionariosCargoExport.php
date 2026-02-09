<?php

namespace App\Exports;

use App\Http\Controllers\TraitHelpers;
use App\Models\DireccaoMunicipal;
use App\Models\web\funcionarios\Funcionarios;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FuncionariosCargoExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithDrawings, WithCustomStartCell, WithStyles, WithTitle
{
    use TraitHelpers;
    
    public $instituicao, $tempo_trabalho, $genero, $status, $cargo_id;

    public function __construct($instituicao, $tempo_trabalho, $genero, $status, $cargo_id)
    {
        $this->instituicao = $instituicao; 
        $this->tempo_trabalho = $tempo_trabalho; 
        $this->genero = $genero; 
        $this->status = $status;
        $this->cargo_id = $cargo_id;
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $instituicao = $this->instituicao; 
        $tempo_trabalho = $this->tempo_trabalho; 
        $id = $this->cargo_id; 
        
        $user = auth()->user();
        $direccao = DireccaoMunicipal::findOrFail($user->shcools_id);
    
        return Funcionarios::with('academico.especialidade', 'academico.categoria', 'academico.escolaridade', 'academico.universidade', 'nacionalidade', 'provincia' ,'municipio','distrito', 'contrato.departamento', 'contrato.cargos')
        ->whereHas('academico', function($query) use ($tempo_trabalho){
            $query->when($tempo_trabalho, function ($query) use ($tempo_trabalho){
                $query->where('ano_trabalho','=' , $tempo_trabalho);
            });
        })
        ->whereHas('contrato', function($query) use ($id, $instituicao){
            $query->when($id, function ($query) use ($id, $instituicao){
                $query->where('cargo_id', $id)->where('level', $instituicao);
            });
        })
        ->when($this->status, function($query, $value){
            $query->where('status', $value);
        })
        ->when($this->genero, function($query, $value){
            $query->where('genero', $value);
        })
        ->where('level', $instituicao)->where([
            ['shcools_id', '=', $user->shcools_id],
        ])
        ->orderBy('created_at', 'asc')
        ->get();
        
    }

    public function headings():array
    {
        return[
            "Nome",
            "Sexo",
            "Data Nascimento",
            "Estado Civil",
            "B.I",
            "Telefone",
            "Especialidade",
            "Categoria",
            "Nível Academico",
            "Universidade",
        ];
    }


    public function map($item):array
    {
        return[
            $item->nome ." ". $item->sobre_nome,
            $item->genero,
            $item->nascimento,
            $item->estado_civil,
            $item->bilheite,
            $item->telefone,
            $item->academico->especialidade->nome,
            $item->academico->categoria->nome,
            $item->academico->escolaridade->nome,
            $item->academico->universidade->nome,
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('FUNCIONARIOS');
        $drawing->setPath(public_path("assets/images/insigna.png"));
        $drawing->setHeight(90);
        $drawing->setCoordinates('A1');

        return $drawing;
    }

    public function title(): string
    {
        return "LISTAGEM DAS FUNCIONÁRIOS";
    }

    public function startCell(): String
    {
        return "A6";
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            6    => [
                'font' => ['bold' => false, 'color' => ['rgb' => 'FCFCFD']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => '2b5876']]

            ],

        ];
    }

}
