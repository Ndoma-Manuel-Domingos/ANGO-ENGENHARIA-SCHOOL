<?php

namespace App\Exports;

use App\Http\Controllers\TraitHelpers;
use App\Models\Shcool;

use App\Models\web\turmas\Bolsa;
use App\Models\web\turmas\Bolseiro;
use App\Models\web\turmas\InstituicaoEducacional;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class EstudanteBolseiroExport implements FromView, WithDrawings, WithTitle, WithCustomStartCell
{
    use TraitHelpers;
    
    private $instituicao_id, $bolsa_id;
    
    public function __construct($request) {
        $this->instituicao_id = $request->instituicao_id;
        $this->bolsa_id = $request->bolsa_id;
    }
    
    public function title(): string
    {
        return "LISTAGEM DOS ESTUDANTES BOLSEIROS";
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
        $instituicao_id = $this->instituicao_id;
        $bolsa_id = $this->bolsa_id;
    
        $bolseiros = Bolseiro::with(['instituicao','bolsa', 'instituicao_bolsa', 'ano', 'periodo', 'estudante', 'escola'])
        ->when($instituicao_id, function($query, $value){
            $query->where('instituicao_id', $value);
        })
        ->when($bolsa_id, function($query, $value){
            $query->where('bolsa_id', $value);
        })
        ->where('shcools_id', $this->escolarLogada())->get();
        
        $bolsa = Bolsa::find($bolsa_id); 
        $instituicao = InstituicaoEducacional::find($instituicao_id); 
        
        $titulo = "LISTA DOS ESTUDANTES BOLSEIROS";
        
        return view('exports.bolseiros', [
        
            "titulo" =>  $titulo,
            "escola" => Shcool::find($this->escolarLogada()),
            
            "bolsa" => $bolsa,
            "instituicao" => $instituicao,
            "bolseiros" => $bolseiros
           
        ]);
    }
}