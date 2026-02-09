<?php

namespace App\Exports;

use App\Http\Controllers\TraitHelpers;
use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
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

class AnoLectivoExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithDrawings, WithCustomStartCell, WithStyles, WithTitle

{

    use TraitHelpers;
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return AnoLectivo::where([
            ['shcools_id', '=', $this->escolarLogada()]
        ])->get();
    }

    
    public function headings():array
    {
        return[
            'Nº',
            'Ano Lectivo',
            'Estado',
            'Inicio',
            'Final',
        ];
    }


    public function map($item):array
    {
        return[
            $item->id,
            $item->ano,
            $item->status,
            $item->inicio,
            $item->final,
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
        $drawing->setDescription('ANO LECTIVOS');
        $drawing->setPath(public_path("assets/images/$img"));
        $drawing->setHeight(90);
        $drawing->setCoordinates('A1');

        return $drawing;
    }

    public function title(): string
    {
        return "LISTAGEM DOS ANOS LECTIVOS";
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
