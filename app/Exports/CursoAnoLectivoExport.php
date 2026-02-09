<?php

namespace App\Exports;

use App\Http\Controllers\TraitHelpers;
use App\Models\web\cursos\AnoLectivoCurso;
use App\Models\web\cursos\Curso;
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

class CursoAnoLectivoExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithDrawings, WithCustomStartCell, WithStyles, WithTitle
{
    use TraitHelpers;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return AnoLectivoCurso::where([
            ['shcools_id', '=', $this->escolarLogada()],
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
        ])
        ->with('curso')
        ->get();
    }

    public function headings():array
    {
        return[
            'Nº',
            'Curso',
            'Abreviação',
            'Área de Farmação',
            'Categoria/Tipo',
            'Estado'
        ];
    }


    public function map($item):array
    {
        return[
            $item->id,
            $item->curso->curso,
            $item->curso->abreviacao,
            $item->curso->area_formacao,
            $item->curso->tipo,
            $item->curso->status,
            // $caixa->AnoLectivoPagamento,
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('CURSOS');
        $drawing->setPath(public_path("assets/images/insigna.png"));
        $drawing->setHeight(90);
        $drawing->setCoordinates('A1');

        return $drawing;
    }

    public function title(): string
    {
        return "LISTAGEM DOS CURSOS";
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
