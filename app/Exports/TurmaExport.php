<?php

namespace App\Exports;

use App\Http\Controllers\TraitHelpers;
use App\Models\Shcool;
use App\Models\web\turmas\Turma;
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

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class TurmaExport implements FromCollection , WithHeadings, WithMapping, ShouldAutoSize, WithDrawings, WithCustomStartCell, WithStyles, WithTitle, WithEvents
{
    use TraitHelpers;
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Turma::join('tb_cursos', 'tb_turmas.cursos_id', '=', 'tb_cursos.id')
        ->join('tb_classes', 'tb_turmas.classes_id', '=', 'tb_classes.id')
        ->join('tb_turnos', 'tb_turmas.turnos_id', '=', 'tb_turnos.id')
        ->join('tb_salas', 'tb_turmas.salas_id', '=', 'tb_salas.id')
        ->join('tb_ano_lectivos', 'tb_turmas.ano_lectivos_id', '=', 'tb_ano_lectivos.id')
        ->select('tb_turmas.id', 'tb_turmas.turma', 'tb_turmas.status','tb_ano_lectivos.id AS ids', 'tb_ano_lectivos.ano', 'tb_classes.classes','tb_salas.salas', 'tb_cursos.curso', 'tb_turnos.turno',)
        ->get(); 
    }


    public function headings():array
    {
        return[
            'Nº',
            'Turma',
            'Classe',
            'Curso',
            'Turno',
            'Sala',
            'Estado',
        ];
    }


    public function map($item):array
    {
        return[
            $item->id,
            $item->turma,
            $item->classes,
            $item->curso,
            $item->turno,
            $item->salas,
            $item->status,
            // $caixa->AnoLectivoPagamento,
        ];
    }

    public function drawings()
    {
        $escola = Shcool::find($this->escolarLogada());

        if($escola->logotipo == null || $escola->logotipo){
            $img = 'insigna.png';
        }else{
            $img = $escola->logotipo;
        }
        
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Turmas');
        $drawing->setPath(public_path("assets/images/$img"));
        $drawing->setHeight(90);
        $drawing->setCoordinates('A1');

        return $drawing;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Definir o título na célula A1
                $event->sheet->getDelegate()->mergeCells('A7', 'E7');
                $event->sheet->getDelegate()->setCellValue('A7', 'LISTAGEM DAS TURMAS');

                // Estilizar o título (opcional)
                $event->sheet->getDelegate()->getStyle('A7')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                        'color' => ['rgb' => 'FCFCFD']
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => '2b5876']]
                ]);
            },
        ];
    }


    public function title(): string
    {
        return "LISTAGEM DAS TURMAS";
    }

    public function startCell(): String
    {
        return "A8";
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            8    => [
                'font' => ['bold' => false, 'color' => ['rgb' => 'FCFCFD']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => '2b5876']]
            ],
        ];
    }

}
