<?php

namespace App\Http\Controllers;

use App\Models\AnoLectivoGlobal;
use App\Models\DireccaoMunicipal;
use App\Models\DireccaoProvincia;
use App\Models\Distrito;
use App\Models\Municipio;
use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\calendarios\Matricula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\web\funcionarios\Funcionarios;

//graficos
use Khill\Lavacharts\Lavacharts;
use RealRashid\SweetAlert\Facades\Alert;

class GraficoController extends Controller
{
    //
    use TraitHelpers;
    use TraitHeader;


    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function graficoTurma(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('read: graficos turma') && !$user->can('read: graficos estudante') ){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
         
        $ano = AnoLectivo::findOrFail($this->anolectivoActivo());
                
        if(!$request->ano_lectivo_id){
            $request->ano_lectivo_id = $ano->id;
        } else {
            $ano = AnoLectivo::findOrFail($request->ano_lectivo_id);
        } 
           
        $data = new Lavacharts;
        $datatable = $data->DataTable();
        $datatable_desistentes = $data->DataTable();
        $datatable_falecidos = $data->DataTable();
        $datatable_especifico = $data->DataTable();
        
        $resultado = Matricula::select(
            // entre 00 aos 05
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 1 AND 5 THEN 1 ELSE 0 END) AS total_masculino_0_5'),
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 1 AND 5 THEN 1 ELSE 0 END) AS total_feminino_0_5'),
            
            //entre 06 aos 11
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 6 AND 11 THEN 1 ELSE 0 END) AS total_masculino_6_11'),
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 6 AND 11 THEN 1 ELSE 0 END) AS total_feminino_6_11'),
        
            // entre 12 aos 15
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 12 AND 15 THEN 1 ELSE 0 END) AS total_masculino_12_15'),
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 12 AND 15 THEN 1 ELSE 0 END) AS total_feminino_12_15'),
            
            // entre 16 oas 18
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 16 AND 18 THEN 1 ELSE 0 END) AS total_masculino_16_18'),
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 16 AND 18 THEN 1 ELSE 0 END) AS total_feminino_16_18'),
            
            //entre 19 25
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 19 AND 25 THEN 1 ELSE 0 END) AS total_masculino_19_25'),
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 19 AND 25 THEN 1 ELSE 0 END) AS total_feminino_19_25'),
            
            // maior que 25
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) > 26 THEN 1 ELSE 0 END) AS total_masculino_maior_26'),
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) > 26 THEN 1 ELSE 0 END) AS total_feminino_maior_26'),
            
            DB::raw('COUNT(*) AS total'),
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" THEN 1 ELSE 0 END) AS total_masculino'),
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" THEN 1 ELSE 0 END) AS total_feminino'),
            DB::raw('(SUM(CASE WHEN tb_estudantes.genero = "Masculino" THEN 1 ELSE 0 END) * 100 / COUNT(*)) AS percentual_masculino'),
            DB::raw('(SUM(CASE WHEN tb_estudantes.genero = "Femenino" THEN 1 ELSE 0 END) * 100 / COUNT(*)) AS percentual_feminino'),
            
            // estudantes desistentes por generos
            DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "desistente" THEN 1 ELSE 0 END) AS total_desistentes'),
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND tb_matriculas.status_matricula = "desistente" THEN 1 ELSE 0 END) AS total_masculino_desistentes'),
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND tb_matriculas.status_matricula = "desistente" THEN 1 ELSE 0 END) AS total_feminino_desistentes'),
            DB::raw('(SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND tb_matriculas.status_matricula = "desistente" THEN 1 ELSE 0 END) * 100 / COUNT(*)) AS percentual_masculino_desistentes'),
            DB::raw('(SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND tb_matriculas.status_matricula = "desistente" THEN 1 ELSE 0 END) * 100 / COUNT(*)) AS percentual_feminino_desistentes'),
            
            // estudantes falecidos
            DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "falecido" THEN 1 ELSE 0 END) AS total_falecidos'),
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND tb_matriculas.status_matricula = "falecido" THEN 1 ELSE 0 END) AS total_masculino_falecidos'),
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND tb_matriculas.status_matricula = "falecido" THEN 1 ELSE 0 END) AS total_feminino_falecidos'),
            DB::raw('(SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND tb_matriculas.status_matricula = "falecido" THEN 1 ELSE 0 END) * 100 / COUNT(*)) AS percentual_masculino_falecidos'),
            DB::raw('(SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND tb_matriculas.status_matricula = "falecido" THEN 1 ELSE 0 END) * 100 / COUNT(*)) AS percentual_feminino_falecidos'),
        )
        ->whereBetween(DB::raw('TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE())'), [1, 100])
        ->join('tb_estudantes', 'tb_matriculas.estudantes_id' , '=', 'tb_estudantes.id')
        ->where('tb_estudantes.shcools_id', $this->escolarLogada())
        ->when($request->ano_lectivo_id, function($query, $value){
            $query->where('tb_estudantes.ano_lectivos_id', $value);
        })
        ->first();
        
        $datatable->addStringColumn('Faixa Etária')
        ->addNumberColumn('Masculino')
        ->addNumberColumn('Femenino')
        ->addRow(['1-05', $resultado->total_masculino_0_5, $resultado->total_feminino_0_5])
        ->addRow(['06-11', $resultado->total_masculino_6_11, $resultado->total_feminino_6_11])
        ->addRow(['12-15', $resultado->total_masculino_12_15, $resultado->total_feminino_12_15])
        ->addRow(['16-18', $resultado->total_masculino_16_18, $resultado->total_feminino_16_18])
        ->addRow(['19-25', $resultado->total_masculino_19_25, $resultado->total_feminino_19_25])
        ->addRow(['26+', $resultado->total_masculino_maior_26, $resultado->total_feminino_maior_26]);
            
            
        $datatable_desistentes->addStringColumn('Desistentes')
        ->addNumberColumn('Masculino')
        ->addNumberColumn('Femenino')
        ->addRow(['Generos', $resultado->total_masculino_desistentes, $resultado->total_feminino_desistentes]);
        
        $datatable_falecidos->addStringColumn('Falecidos')
        ->addNumberColumn('Masculino')
        ->addNumberColumn('Femenino')
        ->addRow(['Generos', $resultado->total_masculino_falecidos, $resultado->total_feminino_falecidos]);
        
        $datatable_especifico->addStringColumn('Estudantes')
        ->addNumberColumn('Geral')
        ->addRow(['Total Masculinos', $resultado->total_masculino])
        ->addRow(['Total Femeninos', $resultado->total_feminino])
        ->addRow(['Total Desistentes', $resultado->total_desistentes])
        ->addRow(['Total Falecidos', $resultado->total_falecidos])
        ->addRow(['Total', $resultado->total]);
                    
        
        $options = [
            'title' => 'Estatísticas de Estudantes por Idade e género',
            'height' => 290,
            'colors' => ['DeepSkyBlue', 'Chocolate'],
            'titleTextStyle' => [
                'color'    => 'rgb(123, 65, 89)',
                'fontSize' => 14
            ],
            'legend' => [
                'position' => 'right'
            ],
            'is3D'   => true,
            'slices' => [
                ['offset' => 0.2],
                ['offset' => 0.25],
                ['offset' => 0.3]
            ]
        ];
        
        $options_desistentes = [
            'title' => 'Estatísticas de Estudantes Desistentes por Genero',
            'height' => 290,
            'colors' => ['DeepSkyBlue', 'Chocolate'],
            'titleTextStyle' => [
                'color'    => 'rgb(123, 65, 89)',
                'fontSize' => 14
            ],
            'legend' => [
                'position' => 'right'
            ],
            'is3D'   => true,
            'slices' => [
                ['offset' => 0.2],
                ['offset' => 0.25],
                ['offset' => 0.3]
            ]
        ];
        
        $options_falecidos = [
            'title' => 'Estatísticas de Estudantes Falecidos por Genero',
            'height' => 290,
            'colors' => ['DeepSkyBlue', 'Chocolate'],
            'titleTextStyle' => [
                'color'    => 'rgb(123, 65, 89)',
                'fontSize' => 14
            ],
            'legend' => [
                'position' => 'right'
            ],
            'is3D'   => true,
            'slices' => [
                ['offset' => 0.2],
                ['offset' => 0.25],
                ['offset' => 0.3]
            ]
        ];
    
        $options_especifico = [
            'title' => 'Estatísticas Geral dos Estudantes',
            'height' => 370,
            // 'colors' => ['DeepSkyBlue', 'Chocolate'],
            'titleTextStyle' => [
                'color'    => 'rgb(123, 65, 89)',
                'fontSize' => 14
            ],
            'legend' => [
                'position' => 'right'
            ],
            'is3D'   => true,
            'slices' => [
                ['offset' => 0.2],
                ['offset' => 0.25],
                ['offset' => 0.3]
            ]
        ];
            
        $data->ColumnChart('Estudantes', $datatable, $options);
        $data->ColumnChart('EstudantesDesistentes', $datatable_desistentes, $options_desistentes);
        $data->ColumnChart('EstudantesFalecidos', $datatable_falecidos, $options_falecidos);
        $data->BarChart('EstudantesEspecifico', $datatable_especifico, $options_especifico);
                
        
        
        $headers = [ 
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "ano_lectivos" => AnoLectivo::where('shcools_id', $this->escolarLogada())->get(),
            
            "lava" => $data,
            // "lava2" => $lava,
            "resultado" => $resultado,
            "requests" => $request->all('ano_lectivo_id'),
        ];

        return view('admin.graficos.turmas', $headers);

    }

    // provinciaç
    public function provincialGraficoEstatistica(Request $request)
    {
        $user = auth()->user();
        
        /* if(!$user->can('read: graficos turma') && !$user->can('read: graficos estudante') ){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }*/
         
        $direccao = DireccaoProvincia::findOrFail($user->shcools_id);
  
        $ano = AnoLectivoGlobal::findOrFail($this->anolectivoActivoGlobal());
                
        if(!$request->ano_lectivo_id){
            $request->ano_lectivo_id = $ano->id;
        } else {
            $ano = AnoLectivoGlobal::findOrFail($request->ano_lectivo_id);
        } 
           
        $data = new Lavacharts;
        $datatable = $data->DataTable();
        $datatable_desistentes = $data->DataTable();
        $datatable_falecidos = $data->DataTable();
        $datatable_especifico = $data->DataTable();
        
        $resultado = Matricula::select(
            // entre 00 aos 05
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 1 AND 5 THEN 1 ELSE 0 END) AS total_masculino_0_5'),
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 1 AND 5 THEN 1 ELSE 0 END) AS total_feminino_0_5'),
            
            //entre 06 aos 11
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 6 AND 11 THEN 1 ELSE 0 END) AS total_masculino_6_11'),
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 6 AND 11 THEN 1 ELSE 0 END) AS total_feminino_6_11'),
        
            // entre 12 aos 15
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 12 AND 15 THEN 1 ELSE 0 END) AS total_masculino_12_15'),
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 12 AND 15 THEN 1 ELSE 0 END) AS total_feminino_12_15'),
            
            // entre 16 oas 18
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 16 AND 18 THEN 1 ELSE 0 END) AS total_masculino_16_18'),
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 16 AND 18 THEN 1 ELSE 0 END) AS total_feminino_16_18'),
            
            //entre 19 25
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 19 AND 25 THEN 1 ELSE 0 END) AS total_masculino_19_25'),
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 19 AND 25 THEN 1 ELSE 0 END) AS total_feminino_19_25'),
            
            // maior que 25
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) > 26 THEN 1 ELSE 0 END) AS total_masculino_maior_26'),
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) > 26 THEN 1 ELSE 0 END) AS total_feminino_maior_26'),
            
            DB::raw('COUNT(*) AS total'),
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" THEN 1 ELSE 0 END) AS total_masculino'),
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" THEN 1 ELSE 0 END) AS total_feminino'),
            DB::raw('(SUM(CASE WHEN tb_estudantes.genero = "Masculino" THEN 1 ELSE 0 END) * 100 / COUNT(*)) AS percentual_masculino'),
            DB::raw('(SUM(CASE WHEN tb_estudantes.genero = "Femenino" THEN 1 ELSE 0 END) * 100 / COUNT(*)) AS percentual_feminino'),
            
            // estudantes desistentes por generos
            DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "desistente" THEN 1 ELSE 0 END) AS total_desistentes'),
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND tb_matriculas.status_matricula = "desistente" THEN 1 ELSE 0 END) AS total_masculino_desistentes'),
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND tb_matriculas.status_matricula = "desistente" THEN 1 ELSE 0 END) AS total_feminino_desistentes'),
            DB::raw('(SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND tb_matriculas.status_matricula = "desistente" THEN 1 ELSE 0 END) * 100 / COUNT(*)) AS percentual_masculino_desistentes'),
            DB::raw('(SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND tb_matriculas.status_matricula = "desistente" THEN 1 ELSE 0 END) * 100 / COUNT(*)) AS percentual_feminino_desistentes'),
            
            // estudantes falecidos
            DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "falecido" THEN 1 ELSE 0 END) AS total_falecidos'),
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND tb_matriculas.status_matricula = "falecido" THEN 1 ELSE 0 END) AS total_masculino_falecidos'),
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND tb_matriculas.status_matricula = "falecido" THEN 1 ELSE 0 END) AS total_feminino_falecidos'),
            DB::raw('(SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND tb_matriculas.status_matricula = "falecido" THEN 1 ELSE 0 END) * 100 / COUNT(*)) AS percentual_masculino_falecidos'),
            DB::raw('(SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND tb_matriculas.status_matricula = "falecido" THEN 1 ELSE 0 END) * 100 / COUNT(*)) AS percentual_feminino_falecidos'),
        )
        ->whereBetween(DB::raw('TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE())'), [1, 100])
        ->join('tb_estudantes', 'tb_matriculas.estudantes_id' , '=', 'tb_estudantes.id')
        // ->where('tb_estudantes.shcools_id', $this->escolarLogada())
        ->when($request->ano_lectivo_id, function($query, $value){
            $query->where('tb_matriculas.ano_lectivo_global_id', $value);
        })
        ->when($request->municipio_id, function($query, $value){
            $query->where('tb_matriculas.municipio_id', $value);
        })
        ->when($request->distrito_id, function($query, $value){
            $query->where('tb_matriculas.distrito_id', $value);
        })
        ->when($request->shcools_id, function($query, $value){
            $query->where('tb_matriculas.shcools_id', $value);
        })
        ->first();
        
        $datatable->addStringColumn('Faixa Etária')
        ->addNumberColumn('Masculino')
        ->addNumberColumn('Femenino')
        ->addRow(['1-05', $resultado->total_masculino_0_5, $resultado->total_feminino_0_5])
        ->addRow(['06-11', $resultado->total_masculino_6_11, $resultado->total_feminino_6_11])
        ->addRow(['12-15', $resultado->total_masculino_12_15, $resultado->total_feminino_12_15])
        ->addRow(['16-18', $resultado->total_masculino_16_18, $resultado->total_feminino_16_18])
        ->addRow(['19-25', $resultado->total_masculino_19_25, $resultado->total_feminino_19_25])
        ->addRow(['26+', $resultado->total_masculino_maior_26, $resultado->total_feminino_maior_26]);
            
        $datatable_desistentes->addStringColumn('Desistentes')
        ->addNumberColumn('Masculino')
        ->addNumberColumn('Femenino')
        ->addRow(['Generos', $resultado->total_masculino_desistentes, $resultado->total_feminino_desistentes]);
        
        $datatable_falecidos->addStringColumn('Falecidos')
        ->addNumberColumn('Masculino')
        ->addNumberColumn('Femenino')
        ->addRow(['Generos', $resultado->total_masculino_falecidos, $resultado->total_feminino_falecidos]);
        
        $datatable_especifico->addStringColumn('Estudantes')
        ->addNumberColumn('Geral')
        ->addRow(['Total Masculinos', $resultado->total_masculino])
        ->addRow(['Total Femeninos', $resultado->total_feminino])
        ->addRow(['Total Desistentes', $resultado->total_desistentes])
        ->addRow(['Total Falecidos', $resultado->total_falecidos])
        ->addRow(['Total', $resultado->total]);
                    
        
        $options = [
            'title' => 'Estatísticas de Estudantes por Idade e género',
            'height' => 290,
            'colors' => ['DeepSkyBlue', 'Chocolate'],
            'titleTextStyle' => [
                'color'    => 'rgb(123, 65, 89)',
                'fontSize' => 14
            ],
            'legend' => [
                'position' => 'right'
            ],
            'is3D'   => true,
            'slices' => [
                ['offset' => 0.2],
                ['offset' => 0.25],
                ['offset' => 0.3]
            ]
        ];
        
        $options_desistentes = [
            'title' => 'Estatísticas de Estudantes Desistentes por Genero',
            'height' => 290,
            'colors' => ['DeepSkyBlue', 'Chocolate'],
            'titleTextStyle' => [
                'color'    => 'rgb(123, 65, 89)',
                'fontSize' => 14
            ],
            'legend' => [
                'position' => 'right'
            ],
            'is3D'   => true,
            'slices' => [
                ['offset' => 0.2],
                ['offset' => 0.25],
                ['offset' => 0.3]
            ]
        ];
        
        $options_falecidos = [
            'title' => 'Estatísticas de Estudantes Falecidos por Genero',
            'height' => 290,
            'colors' => ['DeepSkyBlue', 'Chocolate'],
            'titleTextStyle' => [
                'color'    => 'rgb(123, 65, 89)',
                'fontSize' => 14
            ],
            'legend' => [
                'position' => 'right'
            ],
            'is3D'   => true,
            'slices' => [
                ['offset' => 0.2],
                ['offset' => 0.25],
                ['offset' => 0.3]
            ]
        ];
    
        $options_especifico = [
            'title' => 'Estatísticas Geral dos Estudantes',
            'height' => 370,
            // 'colors' => ['DeepSkyBlue', 'Chocolate'],
            'titleTextStyle' => [
                'color'    => 'rgb(123, 65, 89)',
                'fontSize' => 14
            ],
            'legend' => [
                'position' => 'right'
            ],
            'is3D'   => true,
            'slices' => [
                ['offset' => 0.2],
                ['offset' => 0.25],
                ['offset' => 0.3]
            ]
        ];
            
        $data->ColumnChart('Estudantes', $datatable, $options);
        $data->ColumnChart('EstudantesDesistentes', $datatable_desistentes, $options_desistentes);
        $data->ColumnChart('EstudantesFalecidos', $datatable_falecidos, $options_falecidos);
        $data->BarChart('EstudantesEspecifico', $datatable_especifico, $options_especifico);
        
        $headers = [ 
            "ano_lectivos" => AnoLectivoGlobal::get(),
            "municipios" => Municipio::where('provincia_id', $direccao->provincia_id)->get(),
            "distritos" => Distrito::get(),
            "escolas" => Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->where('provincia_id', $direccao->provincia_id)->get(),
            "lava" => $data,
            "resultado" => $resultado,
            "requests" => $request->all('ano_lectivo_id', 'municipio_id', 'distrito_id', 'shcools_id'),
        ];

        return view('sistema.direccao-provincial.estatistica.index', $headers);

    }

    // provinciaç
    public function municipalGraficoEstatistica(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('read: graficos turma') && !$user->can('read: graficos estudante') ){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
         
         
        $direccao = DireccaoMunicipal::findOrFail($user->shcools_id);
  
        $ano = AnoLectivoGlobal::findOrFail($this->anolectivoActivoGlobal());
                
        if(!$request->ano_lectivo_id){
            $request->ano_lectivo_id = $ano->id;
        } else {
            $ano = AnoLectivoGlobal::findOrFail($request->ano_lectivo_id);
        } 
           
        $data = new Lavacharts;
        $datatable = $data->DataTable();
        $datatable_desistentes = $data->DataTable();
        $datatable_falecidos = $data->DataTable();
        $datatable_especifico = $data->DataTable();
        
        $resultado = Matricula::select(
            // entre 00 aos 05
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 1 AND 5 THEN 1 ELSE 0 END) AS total_masculino_0_5'),
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 1 AND 5 THEN 1 ELSE 0 END) AS total_feminino_0_5'),
            
            //entre 06 aos 11
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 6 AND 11 THEN 1 ELSE 0 END) AS total_masculino_6_11'),
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 6 AND 11 THEN 1 ELSE 0 END) AS total_feminino_6_11'),
        
            // entre 12 aos 15
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 12 AND 15 THEN 1 ELSE 0 END) AS total_masculino_12_15'),
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 12 AND 15 THEN 1 ELSE 0 END) AS total_feminino_12_15'),
            
            // entre 16 oas 18
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 16 AND 18 THEN 1 ELSE 0 END) AS total_masculino_16_18'),
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 16 AND 18 THEN 1 ELSE 0 END) AS total_feminino_16_18'),
            
            //entre 19 25
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 19 AND 25 THEN 1 ELSE 0 END) AS total_masculino_19_25'),
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 19 AND 25 THEN 1 ELSE 0 END) AS total_feminino_19_25'),
            
            // maior que 25
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) > 26 THEN 1 ELSE 0 END) AS total_masculino_maior_26'),
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) > 26 THEN 1 ELSE 0 END) AS total_feminino_maior_26'),
            
            DB::raw('COUNT(*) AS total'),
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" THEN 1 ELSE 0 END) AS total_masculino'),
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" THEN 1 ELSE 0 END) AS total_feminino'),
            DB::raw('(SUM(CASE WHEN tb_estudantes.genero = "Masculino" THEN 1 ELSE 0 END) * 100 / COUNT(*)) AS percentual_masculino'),
            DB::raw('(SUM(CASE WHEN tb_estudantes.genero = "Femenino" THEN 1 ELSE 0 END) * 100 / COUNT(*)) AS percentual_feminino'),
            
            // estudantes desistentes por generos
            DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "desistente" THEN 1 ELSE 0 END) AS total_desistentes'),
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND tb_matriculas.status_matricula = "desistente" THEN 1 ELSE 0 END) AS total_masculino_desistentes'),
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND tb_matriculas.status_matricula = "desistente" THEN 1 ELSE 0 END) AS total_feminino_desistentes'),
            DB::raw('(SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND tb_matriculas.status_matricula = "desistente" THEN 1 ELSE 0 END) * 100 / COUNT(*)) AS percentual_masculino_desistentes'),
            DB::raw('(SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND tb_matriculas.status_matricula = "desistente" THEN 1 ELSE 0 END) * 100 / COUNT(*)) AS percentual_feminino_desistentes'),
            
            // estudantes falecidos
            DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "falecido" THEN 1 ELSE 0 END) AS total_falecidos'),
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND tb_matriculas.status_matricula = "falecido" THEN 1 ELSE 0 END) AS total_masculino_falecidos'),
            DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND tb_matriculas.status_matricula = "falecido" THEN 1 ELSE 0 END) AS total_feminino_falecidos'),
            DB::raw('(SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND tb_matriculas.status_matricula = "falecido" THEN 1 ELSE 0 END) * 100 / COUNT(*)) AS percentual_masculino_falecidos'),
            DB::raw('(SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND tb_matriculas.status_matricula = "falecido" THEN 1 ELSE 0 END) * 100 / COUNT(*)) AS percentual_feminino_falecidos'),
        )
        ->whereBetween(DB::raw('TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE())'), [1, 100])
        ->join('tb_estudantes', 'tb_matriculas.estudantes_id' , '=', 'tb_estudantes.id')
        // ->where('tb_estudantes.shcools_id', $this->escolarLogada())
        ->when($request->ano_lectivo_id, function($query, $value){
            $query->where('tb_matriculas.ano_lectivo_global_id', $value);
        })
        ->when($request->municipio_id, function($query, $value){
            $query->where('tb_matriculas.municipio_id', $value);
        })
        ->when($request->distrito_id, function($query, $value){
            $query->where('tb_matriculas.distrito_id', $value);
        })
        ->when($request->shcools_id, function($query, $value){
            $query->where('tb_matriculas.shcools_id', $value);
        })
        ->first();
        
        $datatable->addStringColumn('Faixa Etária')
        ->addNumberColumn('Masculino')
        ->addNumberColumn('Femenino')
        ->addRow(['1-05', $resultado->total_masculino_0_5, $resultado->total_feminino_0_5])
        ->addRow(['06-11', $resultado->total_masculino_6_11, $resultado->total_feminino_6_11])
        ->addRow(['12-15', $resultado->total_masculino_12_15, $resultado->total_feminino_12_15])
        ->addRow(['16-18', $resultado->total_masculino_16_18, $resultado->total_feminino_16_18])
        ->addRow(['19-25', $resultado->total_masculino_19_25, $resultado->total_feminino_19_25])
        ->addRow(['26+', $resultado->total_masculino_maior_26, $resultado->total_feminino_maior_26]);
            
        $datatable_desistentes->addStringColumn('Desistentes')
        ->addNumberColumn('Masculino')
        ->addNumberColumn('Femenino')
        ->addRow(['Generos', $resultado->total_masculino_desistentes, $resultado->total_feminino_desistentes]);
        
        $datatable_falecidos->addStringColumn('Falecidos')
        ->addNumberColumn('Masculino')
        ->addNumberColumn('Femenino')
        ->addRow(['Generos', $resultado->total_masculino_falecidos, $resultado->total_feminino_falecidos]);
        
        $datatable_especifico->addStringColumn('Estudantes')
        ->addNumberColumn('Geral')
        ->addRow(['Total Masculinos', $resultado->total_masculino])
        ->addRow(['Total Femeninos', $resultado->total_feminino])
        ->addRow(['Total Desistentes', $resultado->total_desistentes])
        ->addRow(['Total Falecidos', $resultado->total_falecidos])
        ->addRow(['Total', $resultado->total]);
                    
        
        $options = [
            'title' => 'Estatísticas de Estudantes por Idade e género',
            'height' => 290,
            'colors' => ['DeepSkyBlue', 'Chocolate'],
            'titleTextStyle' => [
                'color'    => 'rgb(123, 65, 89)',
                'fontSize' => 14
            ],
            'legend' => [
                'position' => 'right'
            ],
            'is3D'   => true,
            'slices' => [
                ['offset' => 0.2],
                ['offset' => 0.25],
                ['offset' => 0.3]
            ]
        ];
        
        $options_desistentes = [
            'title' => 'Estatísticas de Estudantes Desistentes por Genero',
            'height' => 290,
            'colors' => ['DeepSkyBlue', 'Chocolate'],
            'titleTextStyle' => [
                'color'    => 'rgb(123, 65, 89)',
                'fontSize' => 14
            ],
            'legend' => [
                'position' => 'right'
            ],
            'is3D'   => true,
            'slices' => [
                ['offset' => 0.2],
                ['offset' => 0.25],
                ['offset' => 0.3]
            ]
        ];
        
        $options_falecidos = [
            'title' => 'Estatísticas de Estudantes Falecidos por Genero',
            'height' => 290,
            'colors' => ['DeepSkyBlue', 'Chocolate'],
            'titleTextStyle' => [
                'color'    => 'rgb(123, 65, 89)',
                'fontSize' => 14
            ],
            'legend' => [
                'position' => 'right'
            ],
            'is3D'   => true,
            'slices' => [
                ['offset' => 0.2],
                ['offset' => 0.25],
                ['offset' => 0.3]
            ]
        ];
    
        $options_especifico = [
            'title' => 'Estatísticas Geral dos Estudantes',
            'height' => 370,
            // 'colors' => ['DeepSkyBlue', 'Chocolate'],
            'titleTextStyle' => [
                'color'    => 'rgb(123, 65, 89)',
                'fontSize' => 14
            ],
            'legend' => [
                'position' => 'right'
            ],
            'is3D'   => true,
            'slices' => [
                ['offset' => 0.2],
                ['offset' => 0.25],
                ['offset' => 0.3]
            ]
        ];
            
        $data->ColumnChart('Estudantes', $datatable, $options);
        $data->ColumnChart('EstudantesDesistentes', $datatable_desistentes, $options_desistentes);
        $data->ColumnChart('EstudantesFalecidos', $datatable_falecidos, $options_falecidos);
        $data->BarChart('EstudantesEspecifico', $datatable_especifico, $options_especifico);
        
        $headers = [ 
            "ano_lectivos" => AnoLectivoGlobal::get(),
            "municipios" => Municipio::where('id', $direccao->municipio_id)->get(),
            "distritos" => Distrito::get('municipio_id', $direccao->municipio_id),
            "escolas" => Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->where('municipio_id', $direccao->municipio_id)->get(),
            "lava" => $data,
            "resultado" => $resultado,
            "requests" => $request->all('ano_lectivo_id', 'municipio_id', 'distrito_id', 'shcools_id'),
        ];

        return view('sistema.direccao-municipal.estatistica.index', $headers);

    }
    
    public function graficoFuncionarios(Request $request)
    {
    
        $user = auth()->user();
        
        if(!$user->can('read: graficos funcionario')){
           Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
           return redirect()->back();
        }
         

        $data = new Lavacharts;
        $datatable = $data->DataTable();
        $datatable_especifico = $data->DataTable();
        
        $resultado = Funcionarios::select(
            // entre 00 aos 05
            DB::raw('SUM(CASE WHEN genero = "Masculino" AND TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) BETWEEN 18 AND 20 THEN 1 ELSE 0 END) AS total_masculino_18_20'),
            DB::raw('SUM(CASE WHEN genero = "Femenino" AND TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) BETWEEN 18 AND 20 THEN 1 ELSE 0 END) AS total_feminino_18_20'),
            
            //entre 06 aos 11
            DB::raw('SUM(CASE WHEN genero = "Masculino" AND TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) BETWEEN 21 AND 25 THEN 1 ELSE 0 END) AS total_masculino_21_25'),
            DB::raw('SUM(CASE WHEN genero = "Femenino" AND TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) BETWEEN 21 AND 25 THEN 1 ELSE 0 END) AS total_feminino_21_25'),
        
            // entre 12 aos 15
            DB::raw('SUM(CASE WHEN genero = "Masculino" AND TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) BETWEEN 26 AND 30 THEN 1 ELSE 0 END) AS total_masculino_26_30'),
            DB::raw('SUM(CASE WHEN genero = "Femenino" AND TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) BETWEEN 26 AND 30 THEN 1 ELSE 0 END) AS total_feminino_26_30'),
            
            // entre 16 oas 18
            DB::raw('SUM(CASE WHEN genero = "Masculino" AND TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) BETWEEN 31 AND 35 THEN 1 ELSE 0 END) AS total_masculino_31_35'),
            DB::raw('SUM(CASE WHEN genero = "Femenino" AND TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) BETWEEN 31 AND 35 THEN 1 ELSE 0 END) AS total_feminino_31_35'),
            
            //entre 19 25
            DB::raw('SUM(CASE WHEN genero = "Masculino" AND TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) BETWEEN 36 AND 45 THEN 1 ELSE 0 END) AS total_masculino_36_45'),
            DB::raw('SUM(CASE WHEN genero = "Femenino" AND TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) BETWEEN 36 AND 45 THEN 1 ELSE 0 END) AS total_feminino_36_45'),
            
            // maior que 25
            DB::raw('SUM(CASE WHEN genero = "Masculino" AND TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) > 46 THEN 1 ELSE 0 END) AS total_masculino_maior_46'),
            DB::raw('SUM(CASE WHEN genero = "Femenino" AND TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) > 46 THEN 1 ELSE 0 END) AS total_feminino_maior_46'),
            
            DB::raw('COUNT(*) AS total'),
            DB::raw('SUM(CASE WHEN genero = "Masculino" THEN 1 ELSE 0 END) AS total_masculino'),
            DB::raw('SUM(CASE WHEN genero = "Masculino" AND status = "desactivo" THEN 1 ELSE 0 END) AS total_masculino_desactivo'),
            DB::raw('SUM(CASE WHEN genero = "Masculino" AND status = "activo" THEN 1 ELSE 0 END) AS total_masculino_activo'),
            DB::raw('SUM(CASE WHEN genero = "Femenino" THEN 1 ELSE 0 END) AS total_feminino'),
            DB::raw('SUM(CASE WHEN genero = "Femenino" AND status = "desactivo" THEN 1 ELSE 0 END) AS total_feminino_desactivo'),
            DB::raw('SUM(CASE WHEN genero = "Femenino" AND status = "activo" THEN 1 ELSE 0 END) AS total_feminino_activo'),
            DB::raw('(SUM(CASE WHEN genero = "Masculino" THEN 1 ELSE 0 END) * 100 / COUNT(*)) AS percentual_masculino'),
            DB::raw('(SUM(CASE WHEN genero = "Femenino" THEN 1 ELSE 0 END) * 100 / COUNT(*)) AS percentual_feminino'),
            
        )
        ->whereBetween(DB::raw('TIMESTAMPDIFF(YEAR, nascimento, CURDATE())'), [1, 100])
        ->where('shcools_id', $this->escolarLogada())
        ->where('level', '4')
        ->first(); 
        
        $datatable->addStringColumn('Faixa Etária')
        ->addNumberColumn('Masculino')
        ->addNumberColumn('Femenino')
        ->addRow(['18-20', $resultado->total_masculino_18_20, $resultado->total_feminino_18_20])
        ->addRow(['21-25', $resultado->total_masculino_21_25, $resultado->total_feminino_21_25])
        ->addRow(['26-30', $resultado->total_masculino_26_30, $resultado->total_feminino_26_30])
        ->addRow(['31-35', $resultado->total_masculino_31_35, $resultado->total_feminino_31_35])
        ->addRow(['26-45', $resultado->total_masculino_36_45, $resultado->total_feminino_36_45])
        ->addRow(['46+', $resultado->total_masculino_maior_46, $resultado->total_feminino_maior_46]);


        $datatable_especifico->addStringColumn('Funcionários')
        ->addNumberColumn('Geral')
        ->addRow(['Total Masculinos', $resultado->total_masculino])
        ->addRow(['Total Femeninos', $resultado->total_feminino])
        
        ->addRow(['Total Masculinos Activos', $resultado->total_masculino_activo])
        ->addRow(['Total Femeninos Activos', $resultado->total_feminino_activo])
        
        ->addRow(['Total Masculinos Desactivos', $resultado->total_masculino_desactivo])
        ->addRow(['Total Femeninos Desactivos', $resultado->total_feminino_desactivo])
        
        ->addRow(['Total', $resultado->total]);
        
        $options = [
            'title' => 'Estatísticas de Funcionários por Idade e género',
            'height' => 290,
            'colors' => ['DeepSkyBlue', 'Chocolate'],
            'titleTextStyle' => [
                'color'    => 'rgb(123, 65, 89)',
                'fontSize' => 14
            ],
            'legend' => [
                'position' => 'right'
            ],
            'is3D'   => true,
            'slices' => [
                ['offset' => 0.2],
                ['offset' => 0.25],
                ['offset' => 0.3]
            ]
        ];
    
        $options_especifico = [
            'title' => 'Estatísticas Geral dos Funcionários',
            'height' => 370,
            // 'colors' => ['DeepSkyBlue', 'Chocolate'],
            'titleTextStyle' => [
                'color'    => 'rgb(123, 65, 89)',
                'fontSize' => 14
            ],
            'legend' => [
                'position' => 'right'
            ],
            'is3D'   => true,
            'slices' => [
                ['offset' => 0.2],
                ['offset' => 0.25],
                ['offset' => 0.3]
            ]
        ];
            
        $data->ColumnChart('Funcionarios', $datatable, $options);
        $data->BarChart('FuncionariosEspecifico', $datatable_especifico, $options_especifico);
                
        
        
        $headers = [ 
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "ano_lectivos" => AnoLectivo::where('shcools_id', $this->escolarLogada())->get(),
            
            "lava" => $data,
            // "lava2" => $lava,
            "resultado" => $resultado,
            "requests" => $request->all('ano_lectivo_id'),
        ];

        return view('admin.graficos.funcionarios', $headers);

    }

    public function provincialGraficoFuncionarios(Request $request)
    {
        $user = auth()->user();
        
        /* if(!$user->can('read: graficos funcionario')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        } */
         
         
        $data = new Lavacharts;
        $datatable = $data->DataTable();
        $datatable_especifico = $data->DataTable();
        
        $resultado = Funcionarios::select(
            // entre 00 aos 05
            DB::raw('SUM(CASE WHEN genero = "Masculino" AND TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) BETWEEN 18 AND 20 THEN 1 ELSE 0 END) AS total_masculino_18_20'),
            DB::raw('SUM(CASE WHEN genero = "Femenino" AND TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) BETWEEN 18 AND 20 THEN 1 ELSE 0 END) AS total_feminino_18_20'),
            
            //entre 06 aos 11
            DB::raw('SUM(CASE WHEN genero = "Masculino" AND TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) BETWEEN 21 AND 25 THEN 1 ELSE 0 END) AS total_masculino_21_25'),
            DB::raw('SUM(CASE WHEN genero = "Femenino" AND TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) BETWEEN 21 AND 25 THEN 1 ELSE 0 END) AS total_feminino_21_25'),
        
            // entre 12 aos 15
            DB::raw('SUM(CASE WHEN genero = "Masculino" AND TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) BETWEEN 26 AND 30 THEN 1 ELSE 0 END) AS total_masculino_26_30'),
            DB::raw('SUM(CASE WHEN genero = "Femenino" AND TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) BETWEEN 26 AND 30 THEN 1 ELSE 0 END) AS total_feminino_26_30'),
            
            // entre 16 oas 18
            DB::raw('SUM(CASE WHEN genero = "Masculino" AND TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) BETWEEN 31 AND 35 THEN 1 ELSE 0 END) AS total_masculino_31_35'),
            DB::raw('SUM(CASE WHEN genero = "Femenino" AND TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) BETWEEN 31 AND 35 THEN 1 ELSE 0 END) AS total_feminino_31_35'),
            
            //entre 19 25
            DB::raw('SUM(CASE WHEN genero = "Masculino" AND TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) BETWEEN 36 AND 45 THEN 1 ELSE 0 END) AS total_masculino_36_45'),
            DB::raw('SUM(CASE WHEN genero = "Femenino" AND TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) BETWEEN 36 AND 45 THEN 1 ELSE 0 END) AS total_feminino_36_45'),
            
            // maior que 25
            DB::raw('SUM(CASE WHEN genero = "Masculino" AND TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) > 46 THEN 1 ELSE 0 END) AS total_masculino_maior_46'),
            DB::raw('SUM(CASE WHEN genero = "Femenino" AND TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) > 46 THEN 1 ELSE 0 END) AS total_feminino_maior_46'),
            
            DB::raw('COUNT(*) AS total'),
            DB::raw('SUM(CASE WHEN genero = "Masculino" THEN 1 ELSE 0 END) AS total_masculino'),
            DB::raw('SUM(CASE WHEN genero = "Masculino" AND status = "desactivo" THEN 1 ELSE 0 END) AS total_masculino_desactivo'),
            DB::raw('SUM(CASE WHEN genero = "Masculino" AND status = "activo" THEN 1 ELSE 0 END) AS total_masculino_activo'),
            DB::raw('SUM(CASE WHEN genero = "Femenino" THEN 1 ELSE 0 END) AS total_feminino'),
            DB::raw('SUM(CASE WHEN genero = "Femenino" AND status = "desactivo" THEN 1 ELSE 0 END) AS total_feminino_desactivo'),
            DB::raw('SUM(CASE WHEN genero = "Femenino" AND status = "activo" THEN 1 ELSE 0 END) AS total_feminino_activo'),
            DB::raw('(SUM(CASE WHEN genero = "Masculino" THEN 1 ELSE 0 END) * 100 / COUNT(*)) AS percentual_masculino'),
            DB::raw('(SUM(CASE WHEN genero = "Femenino" THEN 1 ELSE 0 END) * 100 / COUNT(*)) AS percentual_feminino'),
            
        )
        ->whereBetween(DB::raw('TIMESTAMPDIFF(YEAR, nascimento, CURDATE())'), [1, 100])
        ->where('shcools_id', $this->escolarLogada())
        ->where('level', '2')
        ->first(); 
        
        $datatable->addStringColumn('Faixa Etária')
        ->addNumberColumn('Masculino')
        ->addNumberColumn('Femenino')
        ->addRow(['18-20', $resultado->total_masculino_18_20, $resultado->total_feminino_18_20])
        ->addRow(['21-25', $resultado->total_masculino_21_25, $resultado->total_feminino_21_25])
        ->addRow(['26-30', $resultado->total_masculino_26_30, $resultado->total_feminino_26_30])
        ->addRow(['31-35', $resultado->total_masculino_31_35, $resultado->total_feminino_31_35])
        ->addRow(['26-45', $resultado->total_masculino_36_45, $resultado->total_feminino_36_45])
        ->addRow(['46+', $resultado->total_masculino_maior_46, $resultado->total_feminino_maior_46]);


        $datatable_especifico->addStringColumn('Funcionários')
        ->addNumberColumn('Geral')
        ->addRow(['Total Masculinos', $resultado->total_masculino])
        ->addRow(['Total Femeninos', $resultado->total_feminino])
        
        ->addRow(['Total Masculinos Activos', $resultado->total_masculino_activo])
        ->addRow(['Total Femeninos Activos', $resultado->total_feminino_activo])
        
        ->addRow(['Total Masculinos Desactivos', $resultado->total_masculino_desactivo])
        ->addRow(['Total Femeninos Desactivos', $resultado->total_feminino_desactivo])
        
        ->addRow(['Total', $resultado->total]);
        
        $options = [
            'title' => 'Estatísticas de Funcionários por Idade e género',
            'height' => 290,
            'colors' => ['DeepSkyBlue', 'Chocolate'],
            'titleTextStyle' => [
                'color'    => 'rgb(123, 65, 89)',
                'fontSize' => 14
            ],
            'legend' => [
                'position' => 'right'
            ],
            'is3D'   => true,
            'slices' => [
                ['offset' => 0.2],
                ['offset' => 0.25],
                ['offset' => 0.3]
            ]
        ];
    
        $options_especifico = [
            'title' => 'Estatísticas Geral dos Funcionários',
            'height' => 370,
            // 'colors' => ['DeepSkyBlue', 'Chocolate'],
            'titleTextStyle' => [
                'color'    => 'rgb(123, 65, 89)',
                'fontSize' => 14
            ],
            'legend' => [
                'position' => 'right'
            ],
            'is3D'   => true,
            'slices' => [
                ['offset' => 0.2],
                ['offset' => 0.25],
                ['offset' => 0.3]
            ]
        ];
            
        $data->ColumnChart('Funcionarios', $datatable, $options);
        $data->BarChart('FuncionariosEspecifico', $datatable_especifico, $options_especifico);
                
        
        $headers = [ 
            "ano_lectivos" => AnoLectivo::where('shcools_id', $this->escolarLogada())->get(),
            "lava" => $data,
            "resultado" => $resultado,
            "requests" => $request->all('ano_lectivo_id'),
        ];

        return view('sistema.direccao-provincial.estatistica.graficos', $headers);

    }
    
    public function municipalGraficoFuncionarios(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('read: graficos funcionario')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
         
        
        $data = new Lavacharts;
        $datatable = $data->DataTable();
        $datatable_especifico = $data->DataTable();
        
        $resultado = Funcionarios::select(
            // entre 00 aos 05
            DB::raw('SUM(CASE WHEN genero = "Masculino" AND TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) BETWEEN 18 AND 20 THEN 1 ELSE 0 END) AS total_masculino_18_20'),
            DB::raw('SUM(CASE WHEN genero = "Femenino" AND TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) BETWEEN 18 AND 20 THEN 1 ELSE 0 END) AS total_feminino_18_20'),
            
            //entre 06 aos 11
            DB::raw('SUM(CASE WHEN genero = "Masculino" AND TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) BETWEEN 21 AND 25 THEN 1 ELSE 0 END) AS total_masculino_21_25'),
            DB::raw('SUM(CASE WHEN genero = "Femenino" AND TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) BETWEEN 21 AND 25 THEN 1 ELSE 0 END) AS total_feminino_21_25'),
        
            // entre 12 aos 15
            DB::raw('SUM(CASE WHEN genero = "Masculino" AND TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) BETWEEN 26 AND 30 THEN 1 ELSE 0 END) AS total_masculino_26_30'),
            DB::raw('SUM(CASE WHEN genero = "Femenino" AND TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) BETWEEN 26 AND 30 THEN 1 ELSE 0 END) AS total_feminino_26_30'),
            
            // entre 16 oas 18
            DB::raw('SUM(CASE WHEN genero = "Masculino" AND TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) BETWEEN 31 AND 35 THEN 1 ELSE 0 END) AS total_masculino_31_35'),
            DB::raw('SUM(CASE WHEN genero = "Femenino" AND TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) BETWEEN 31 AND 35 THEN 1 ELSE 0 END) AS total_feminino_31_35'),
            
            //entre 19 25
            DB::raw('SUM(CASE WHEN genero = "Masculino" AND TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) BETWEEN 36 AND 45 THEN 1 ELSE 0 END) AS total_masculino_36_45'),
            DB::raw('SUM(CASE WHEN genero = "Femenino" AND TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) BETWEEN 36 AND 45 THEN 1 ELSE 0 END) AS total_feminino_36_45'),
            
            // maior que 25
            DB::raw('SUM(CASE WHEN genero = "Masculino" AND TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) > 46 THEN 1 ELSE 0 END) AS total_masculino_maior_46'),
            DB::raw('SUM(CASE WHEN genero = "Femenino" AND TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) > 46 THEN 1 ELSE 0 END) AS total_feminino_maior_46'),
            
            DB::raw('COUNT(*) AS total'),
            DB::raw('SUM(CASE WHEN genero = "Masculino" THEN 1 ELSE 0 END) AS total_masculino'),
            DB::raw('SUM(CASE WHEN genero = "Masculino" AND status = "desactivo" THEN 1 ELSE 0 END) AS total_masculino_desactivo'),
            DB::raw('SUM(CASE WHEN genero = "Masculino" AND status = "activo" THEN 1 ELSE 0 END) AS total_masculino_activo'),
            DB::raw('SUM(CASE WHEN genero = "Femenino" THEN 1 ELSE 0 END) AS total_feminino'),
            DB::raw('SUM(CASE WHEN genero = "Femenino" AND status = "desactivo" THEN 1 ELSE 0 END) AS total_feminino_desactivo'),
            DB::raw('SUM(CASE WHEN genero = "Femenino" AND status = "activo" THEN 1 ELSE 0 END) AS total_feminino_activo'),
            DB::raw('(SUM(CASE WHEN genero = "Masculino" THEN 1 ELSE 0 END) * 100 / COUNT(*)) AS percentual_masculino'),
            DB::raw('(SUM(CASE WHEN genero = "Femenino" THEN 1 ELSE 0 END) * 100 / COUNT(*)) AS percentual_feminino'),
            
        )
        ->whereBetween(DB::raw('TIMESTAMPDIFF(YEAR, nascimento, CURDATE())'), [1, 100])
        ->where('shcools_id', $this->escolarLogada())
        ->where('level', '3')
        ->first(); 
        
        $datatable->addStringColumn('Faixa Etária')
        ->addNumberColumn('Masculino')
        ->addNumberColumn('Femenino')
        ->addRow(['18-20', $resultado->total_masculino_18_20, $resultado->total_feminino_18_20])
        ->addRow(['21-25', $resultado->total_masculino_21_25, $resultado->total_feminino_21_25])
        ->addRow(['26-30', $resultado->total_masculino_26_30, $resultado->total_feminino_26_30])
        ->addRow(['31-35', $resultado->total_masculino_31_35, $resultado->total_feminino_31_35])
        ->addRow(['26-45', $resultado->total_masculino_36_45, $resultado->total_feminino_36_45])
        ->addRow(['46+', $resultado->total_masculino_maior_46, $resultado->total_feminino_maior_46]);


        $datatable_especifico->addStringColumn('Funcionários')
        ->addNumberColumn('Geral')
        ->addRow(['Total Masculinos', $resultado->total_masculino])
        ->addRow(['Total Femeninos', $resultado->total_feminino])
        
        ->addRow(['Total Masculinos Activos', $resultado->total_masculino_activo])
        ->addRow(['Total Femeninos Activos', $resultado->total_feminino_activo])
        
        ->addRow(['Total Masculinos Desactivos', $resultado->total_masculino_desactivo])
        ->addRow(['Total Femeninos Desactivos', $resultado->total_feminino_desactivo])
        
        ->addRow(['Total', $resultado->total]);
        
        $options = [
            'title' => 'Estatísticas de Funcionários por Idade e género',
            'height' => 290,
            'colors' => ['DeepSkyBlue', 'Chocolate'],
            'titleTextStyle' => [
                'color'    => 'rgb(123, 65, 89)',
                'fontSize' => 14
            ],
            'legend' => [
                'position' => 'right'
            ],
            'is3D'   => true,
            'slices' => [
                ['offset' => 0.2],
                ['offset' => 0.25],
                ['offset' => 0.3]
            ]
        ];
    
        $options_especifico = [
            'title' => 'Estatísticas Geral dos Funcionários',
            'height' => 370,
            // 'colors' => ['DeepSkyBlue', 'Chocolate'],
            'titleTextStyle' => [
                'color'    => 'rgb(123, 65, 89)',
                'fontSize' => 14
            ],
            'legend' => [
                'position' => 'right'
            ],
            'is3D'   => true,
            'slices' => [
                ['offset' => 0.2],
                ['offset' => 0.25],
                ['offset' => 0.3]
            ]
        ];
            
        $data->ColumnChart('Funcionarios', $datatable, $options);
        $data->BarChart('FuncionariosEspecifico', $datatable_especifico, $options_especifico);
                
        
        $headers = [ 
            "ano_lectivos" => AnoLectivo::where('shcools_id', $this->escolarLogada())->get(),
            "lava" => $data,
            "resultado" => $resultado,
            "requests" => $request->all('ano_lectivo_id'),
        ];

        return view('sistema.direccao-municipal.estatistica.graficos', $headers);

    }
    
    
}
