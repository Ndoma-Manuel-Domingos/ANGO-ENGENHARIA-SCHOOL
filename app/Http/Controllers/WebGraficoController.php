<?php

namespace App\Http\Controllers;

use App\Models\web\calendarios\Matricula;

class WebGraficoController extends Controller
{
    use TraitHelpers;
    use TraitHeader;

    
    public function __construct()
    {
        $this->middleware('auth');
    }
    

    public function graficoMatriculasConfirmacoes()
    {
        $matriculas = Matricula::select(\DB::raw("COUNT(*) as matricula"))->where([
            ['tipo', '=', 'matricula'],
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
        ])->pluck('matricula');

        $confirmacoes = Matricula::select(\DB::raw("COUNT(*) as confirmacao"))->where([
            ['tipo', '=', 'confirmacao'],
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
        ])->pluck('confirmacao');

        return response()->json([
            'status' => 200,
            'matricula' => $matriculas,
            'confirmacao' => $confirmacoes,
        ]);
    }
}
