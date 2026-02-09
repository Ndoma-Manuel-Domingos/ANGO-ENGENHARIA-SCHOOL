<?php

namespace App\Exports;

use App\Http\Controllers\TraitHelpers;
use App\Models\Categoria;
use App\Models\Ensino;
use App\Models\Especialidade;
use App\Models\Provincia;
use App\Models\Universidade;
use App\Models\web\funcionarios\Funcionarios;
use Illuminate\Support\Facades\Auth;
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

class FuncionariosExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithDrawings, WithCustomStartCell, WithStyles, WithTitle
{
    use TraitHelpers;
    
    public $instituicao, $universidade_id, $escolaridade_id, $formacao_id, $especialidade_id, $categora_id, $status;

    public function __construct($instituicao, $universidade_id, $escolaridade_id, $formacao_id, $especialidade_id, $categora_id, $status)
    {
        $this->instituicao = $instituicao; 
        $this->universidade_id = $universidade_id; 
        $this->escolaridade_id = $escolaridade_id; 
        $this->formacao_id = $formacao_id; 
        $this->especialidade_id = $especialidade_id; 
        $this->categora_id = $categora_id;
        $this->status = $status;
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $instituicao = $this->instituicao; 
        $universidade_id = $this->universidade_id; 
        $escolaridade_id = $this->escolaridade_id; 
        $formacao_id = $this->formacao_id; 
        $especialidade_id = $this->especialidade_id; 
        $categora_id = $this->categora_id;
    
        return Funcionarios::where('level', $instituicao)->with('academico.especialidade', 'academico.categoria', 'academico.escolaridade', 'academico.universidade', 'nacionalidade', 'provincia' ,'municipio','distrito')
        ->whereHas('academico', function($query) use ($universidade_id, $escolaridade_id, $formacao_id, $especialidade_id, $categora_id){
            $query->when($universidade_id, function ($query) use ($universidade_id){
                $query->where('universidade_id', $universidade_id);
            });
            
            $query->when($escolaridade_id, function ($query) use ($escolaridade_id){
                $query->where('escolaridade_id', $escolaridade_id);
            });
            
            $query->when($formacao_id, function ($query) use ($formacao_id){
                $query->where('formacao_academica_id', $formacao_id);
            });
            
            $query->when($especialidade_id, function ($query) use ($especialidade_id){
                $query->where('especialidade_id', $especialidade_id);
            });
            
            $query->when($categora_id, function ($query) use ($categora_id){
                $query->where('categoria_id', $categora_id);
            });
        })
        ->when($this->status, function($query, $value){
            $query->where('status', $value);
        })
        ->where('shcools_id', Auth::user()->shcools_id)
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
