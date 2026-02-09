<?php

namespace App\Exports;

use App\Http\Controllers\TraitHelpers;
use App\Models\LaboratorioEscola;
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

class LaboratorioEscolaExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithDrawings, WithCustomStartCell, WithStyles, WithTitle
{
    use TraitHelpers;
    /**
    * @return \Illuminate\Support\Collection
    */
    
    // Propriedades da classe
    public $id;

    // Construtor da classe
    public function __construct($request)
    {
        $this->id = $request->id;
    }
    
    public function collection()
    {
        return LaboratorioEscola::where('shcools_id', '=', $this->id)
        ->with(['laboratorio', 'escola'])
        ->get();
    }

    public function headings():array
    {
        return[
            'Codigo',
            'Designação',
        ];
    }


    public function map($item):array
    {
        return[
            $item->laboratorio->id,
            $item->laboratorio->nome,
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('CLASSES');
        $drawing->setPath(public_path("assets/images/insigna.png"));
        $drawing->setHeight(90);
        $drawing->setCoordinates('A1');

        return $drawing;
    }

    public function title(): string
    {
        return "LISTAGEM DOS LABORATÓRIOS DAS ESCOLAS";
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
