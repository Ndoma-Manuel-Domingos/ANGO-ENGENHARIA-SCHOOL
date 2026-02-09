<?php

namespace App\Exports;

use App\Http\Controllers\TraitHelpers;
use App\Models\Escolaridade;
use App\Models\Shcool;
use App\Models\web\calendarios\Matricula;
use App\Models\web\estudantes\Estudante;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ListaCandidaturaExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithDrawings, WithCustomStartCell, WithStyles, WithTitle

{

    use TraitHelpers;

    public $curso, $classe, $turno, $status;

    public function __construct($curso, $classe, $turno, $status)
    {
        $this->curso = $curso;
        $this->classe = $classe;
        $this->turno = $turno;
        $this->status = $status;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Matricula::where('shcools_id', $this->escolarLogada())
        ->when($this->curso, function($query, $value){
            $query->where('cursos_id', $value);
        })
        ->when($this->turno, function($query, $value){
            $query->where('turnos_id', $value);
        })
        ->when($this->classe, function($query, $value){
            $query->where('classes_id', $value);
        })
        ->when($this->status, function($query, $value){
            $query->where('status_matricula', $value);
        })
        ->where('tipo', 'candidatura')
        ->with('classe')
        ->with('turno')
        ->with('curso')
        ->with('estudante')
        ->get();
    }

    
    public function headings():array
    {
        return[
            'Nº',
            'Nome',
            'Idade',
            'Genero',
            'Nascimento',
            'Estado Candidatura',
            'Classe',
            'Curso',
            'Turno',
        ];
    }


    public function map($item):array
    {
        return[
            $item->id,
            $item->estudante->nome . " " . $item->estudante->sobre_nome,
            $item->estudante->idade($item->estudante->nascimento),
            $item->estudante->genero,
            $item->estudante->nascimento,
            $item->estudante->nascimento,
            $item->classe->classes,
            $item->curso->curso,
            $item->turno->turno
        ];
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Definir o título na célula A1
                $event->sheet->getDelegate()->mergeCells('A7', 'B7');
                $event->sheet->getDelegate()->setCellValue('A7', 'Meu Título');

                // Estilizar o título (opcional)
                $event->sheet->getDelegate()->getStyle('A7')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
            },
        ];
    }


    public function drawings()
    {
        $escola = Shcool::find($this->escolarLogada());

        if($escola->logotipo == null){
            $img = 'insigna.png';
        }else{
            $img = $escola->logotipo;
        }

        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('ESCOLARIDADE');
        $drawing->setPath(public_path("assets/images/$img"));
        $drawing->setHeight(90);
        $drawing->setCoordinates('A1');

        return $drawing;
    }

    public function title(): string
    {
        return "LISTAGEM DOS ESTUDANTES";
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
