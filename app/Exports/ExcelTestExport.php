<?php

namespace App\Exports;

use App\Http\Controllers\TraitHelpers;
use App\Models\Shcool;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class ExcelTestExport implements FromView, WithTitle, WithCustomStartCell
{
    use TraitHelpers;

    public function __construct()
    {
 
    }

    public function title(): string
    {
        return "MINI PAUTA";
    }

    public function startCell(): String
    {
        return "A11";
    }

    public function view(): View
    {
        return view('exports.test', [
            "escola" => Shcool::with(['ensino'])->findOrFail($this->escolarLogada()),
        ]);
    }
}
