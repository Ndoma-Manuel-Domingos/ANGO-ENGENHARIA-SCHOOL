<?php

namespace App\Http\Controllers;

use App\Exports\EstudanteExport;
use App\Exports\EstudanteBolseiroExport;
use App\Exports\EstudanteEstagiarioExport;
use App\Exports\EstudanteTurmaExport;
use App\Exports\MatriculaExport;
use App\Models\web\turmas\Turma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;

class PrinterExcelController extends Controller
{
    use TraitHelpers;
    // TODAS AS IMPRESSOES

    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function estudantesMatriculasImprimirExcel(Request $request)
    {
        $codigo = date("Y-m-d");

        return Excel::download(new MatriculaExport($request), "listagem-estudantes-matriculados-{$codigo}.xlsx");     
    }
    
    public function estudantesImprimirExcel(Request $request)
    {
        $codigo = date("Y-m-d");

        return Excel::download(new EstudanteExport($request), "listagem-dos-estudantes-{$codigo}.xlsx");  
    }
    
    public function estudantesBolseiroImprimirExcel(Request $request)
    {
        $codigo = date("Y-m-d");

        return Excel::download(new EstudanteBolseiroExport($request), "listagem-dos-estudantes-bolseiros-{$codigo}.xlsx");  
    }
    
    public function estudantesEstagiarioImprimirExcel(Request $request)
    {
        $codigo = date("Y-m-d");

        return Excel::download(new EstudanteEstagiarioExport($request), "listagem-dos-estudantes-estagiarios-{$codigo}.xlsx");  
    }

    public function estudanteTurma($id)
    {
        $turma = Turma::findOrFail(Crypt::decrypt($id));
        
        return Excel::download(new EstudanteTurmaExport($id), "listagem-dos-estudantes-turma-{$turma->turma}.xlsx");  
    }   
}
