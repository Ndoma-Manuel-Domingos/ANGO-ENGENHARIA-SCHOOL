@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Formulário Pré-Escolar(Iniciação)</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Iniciação</a></li>
                    <li class="breadcrumb-item active">Formulário</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="callout callout-info">
                    <h5><i class="fas fa-info"></i> Painel Pedagógico</h5>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- table 01--}}
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <table id="example1" style="width: 100%"
                            class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th colspan="24" class="text-center bg-info">Quadro 1. Total de Alunos Matriculados por classe e idade <span>(incluindo os repetentes e os alunos com deficiência)</span></th>
                                </tr>
                                <tr>
                                    <th colspan="24" class="text-center bg-primary">Total alunos por idade</th>
                                </tr>
                                <tr>
                                    <th rowspan="2">Idades/Classes</th>
                                    <th colspan="2">5 Anos</th>
                                    <th colspan="2">6 Anos</th>
                                    <th colspan="2">7 Anos</th>
                                    <th colspan="2">8 Anos</th>
                                    <th colspan="2">9 Anos</th>
                                    <th colspan="2">10 ou mais Anos</th>
                                    <th colspan="2">Total</th>
                                </tr>
                                
                                <tr>
                                    <th>MF</th>
                                    <th>F</th>
                                    
                                    <th>MF</th>
                                    <th>F</th>
                                    
                                    <th>MF</th>
                                    <th>F</th>
                                    
                                    <th>MF</th>
                                    <th>F</th>
                                    
                                    <th>MF</th>
                                    <th>F</th>
                                    
                                    <th>MF</th>
                                    <th>F</th>
                                    
                                    <th>MF</th>
                                    <th>F</th>
                                </tr>
                            </thead>
                            <tbody id="">
                                <tr>
                                    <td>Iniciação</td>
                                    
                                    <td>{{ $result->matriculados_masculino_5_anos ?? 0 }}</td>
                                    <td>{{ $result->matriculados_feminino_5_anos ?? 0 }}</td>
                                    
                                    <td>{{ $result->matriculados_masculino_6_anos ?? 0 }}</td>
                                    <td>{{ $result->matriculados_feminino_6_anos ?? 0 }}</td>
                                    
                                    <td>{{ $result->matriculados_masculino_7_anos ?? 0 }}</td>
                                    <td>{{ $result->matriculados_feminino_7_anos ?? 0 }}</td>
                                    
                                    <td>{{ $result->matriculados_masculino_8_anos ?? 0 }}</td>
                                    <td>{{ $result->matriculados_feminino_8_anos ?? 0 }}</td>
                                    
                                    <td>{{ $result->matriculados_masculino_9_anos ?? 0 }}</td>
                                    <td>{{ $result->matriculados_feminino_9_anos ?? 0 }}</td>
                                    
                                    <td>{{ $result->matriculados_masculino_10_anos ?? 0 }}</td>
                                    <td>{{ $result->matriculados_feminino_10_anos ?? 0 }}</td>
                                    
                                    <td>{{ $result->matriculados_masculino ?? 0 }}</td>
                                    <td>{{ $result->matriculados_feminino ?? 0 }}</td>
                                </tr>
                             
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            {{-- table 02 --}}
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <table id="example1" style="width: 100%"
                            class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th colspan="24" class="text-center bg-info">Quadro 2. Total de Alunos Repetentes por classe e idade <span>(que frequentam a mesma classes pela segunda ou mais vezes)</span></th>
                                </tr>
                                <tr>
                                    <th colspan="24" class="text-center bg-primary">Total de alunos por idade</th>
                                </tr>
                                <tr>
                                    <th rowspan="2">Idades/Classes</th>
                                    <th colspan="2">6 Anos</th>
                                    <th colspan="2">7 Anos</th>
                                    <th colspan="2">8 Anos</th>
                                    <th colspan="2">9 Anos</th>
                                    <th colspan="2">10 ou mais Anos</th>
                                    <th colspan="2">Total</th>
                                </tr>
                                
                                <tr>
                                    <th>MF</th>
                                    <th>F</th>
                                    
                                    <th>MF</th>
                                    <th>F</th>
                                    
                                    <th>MF</th>
                                    <th>F</th>
                                    
                                    <th>MF</th>
                                    <th>F</th>
                                    
                                    <th>MF</th>
                                    <th>F</th>
                                    
                                    <th>MF</th>
                                    <th>F</th>
                                </tr>
                            </thead>
                            <tbody id="">
                                <tr>
                                    <td>Inicição</td>
                                    
                                    <td>{{ $result->repitentes_masculino_6_anos ?? 0 }}</td>
                                    <td>{{ $result->repitentes_feminino_6_anos ?? 0 }}</td>
                                    
                                    <td>{{ $result->repitentes_masculino_7_anos ?? 0 }}</td>
                                    <td>{{ $result->repitentes_feminino_7_anos ?? 0 }}</td>
                                    
                                    <td>{{ $result->repitentes_masculino_8_anos ?? 0 }}</td>
                                    <td>{{ $result->repitentes_feminino_8_anos ?? 0 }}</td>
                                    
                                    <td>{{ $result->repitentes_masculino_9_anos ?? 0 }}</td>
                                    <td>{{ $result->repitentes_feminino_9_anos ?? 0 }}</td>
                                    
                                    <td>{{ $result->repitentes_masculino_10_anos ?? 0 }}</td>
                                    <td>{{ $result->repitentes_feminino_10_anos ?? 0 }}</td>
                                    
                                    <td>{{ $result->repitentes_masculino ?? 0 }}</td>
                                    <td>{{ $result->repitentes_feminino ?? 0 }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            {{-- table 03 --}}
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <table id="example1" style="width: 100%"
                            class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th colspan="8" class="text-center bg-info">Quadro 3. Turnos, Turma por classe</th>
                                </tr>
                                <tr>
                                    <th rowspan="2"></th>
                                    @foreach ($turnos as $item)
                                    <th colspan="2" class="text-center">{{ $item->turno->turno }}</th>
                                    @endforeach
                                    <th rowspan="2" class="text-center">Total Turmas</th>
                                </tr>
                                
                                <tr>
                                    @foreach ($turnos as $item)
                                    <th>Turmas</th>
                                    <th>Turnos</th>
                                    @endforeach
                                </tr>
                          
                            </thead>
                            <tbody id="">
                                @php
                                    $total_geral = 0;
                                @endphp
                                @foreach ($classes as $classe)
                                <tr>
                                    @php
                                        $total_turma_final = 0;
                                    @endphp
                                    <td>{{ $classe->classes }}</td>  
                                    @foreach ($turnos as $turno)
                                    @php
                                    
                                        $total_turno = App\Models\web\turmas\Turma::where('shcools_id', $escola->id)->where('ano_lectivos_id', $anolectivoactual)
                                        ->where('turnos_id', $turno->turno->id)
                                        ->where('classes_id', $classe->id)
                                        ->distinct('turnos_id')
                                        ->count();
                                        
                                        $total_turma = App\Models\web\turmas\Turma::where('shcools_id', $escola->id)->where('ano_lectivos_id', $anolectivoactual)
                                        ->where('turnos_id', $turno->turno->id)
                                        ->where('classes_id', $classe->id)
                                        ->distinct('id')
                                        ->count();
                                        
                                        $total_turma_final += $total_turma;
                                    @endphp
                                    <td>{{ $total_turma }}</td>
                                    <td>{{ $total_turno }}</td>
                                    @endforeach
                                    <td>{{ $total_turma_final }}</td>
                                </tr>
                                @php
                                    $total_geral += $total_turma_final;
                                @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            {{-- table 03 --}}
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <table id="example1" style="width: 100%"
                            class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th colspan="12" class="text-center bg-info">Quadro 4. Alunos com deficiências</th>
                                </tr>
                                <tr>
                                    <th rowspan="2"></th>
                                    <th colspan="2" class="text-center">Visual</th>
                                    <th colspan="2" class="text-center">Auditivo</th>
                                    <th colspan="2" class="text-center">Motora</th>
                                    <th colspan="2" class="text-center">Outras</th>
                                    <th colspan="2" class="text-center">Total</th>
                                </tr>
                                
                                <tr>
                                    <th>{{ $result->estudante_dificiencia_visual_masculino ?? 0 }}</th>
                                    <th>{{ $result->estudante_dificiencia_visual_feminino ?? 0 }}</th>
                                    
                                    <th>{{ $result->estudante_dificiencia_auditiva_masculino ?? 0 }}</th>
                                    <th>{{ $result->estudante_dificiencia_auditiva_feminino ?? 0 }}</th>
                                    
                                    <th>{{ $result->estudante_dificiencia_motora_masculino ?? 0 }}</th>
                                    <th>{{ $result->estudante_dificiencia_motora_feminino ?? 0 }}</th>
                                    
                                    <th>{{ $result->estudante_dificiencia_outras_masculino ?? 0 }}</th>
                                    <th>{{ $result->estudante_dificiencia_outras_feminino ?? 0 }}</th>
                                    
                                    <th>{{ $result->estudante_dificiencia_visual_masculino ?? 0 + $result->estudante_dificiencia_auditiva_masculino ?? 0 + $result->estudante_dificiencia_motora_masculino ?? 0 + $result->estudante_dificiencia_outras_masculino ?? 0 }}</th>
                                    <th>{{ $result->estudante_dificiencia_visual_feminino ?? 0 + $result->estudante_dificiencia_auditiva_feminino ?? 0 + $result->estudante_dificiencia_motora_feminino ?? 0 + $result->estudante_dificiencia_outras_feminino ?? 0 }}</th>
                                </tr>
                            </thead>
                            <tbody id="">
                                <tr>
                                    <td>Iniciação</td>
                                    
                                    <td>0</td>
                                    <td>0</td>
                                    
                                    <td>0</td>
                                    <td>0</td>
                                    
                                    <td>0</td>
                                    <td>0</td>
                                    
                                    <td>0</td>
                                    <td>0</td>
                                    
                                    <td>0</td>
                                    <td>0</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            {{-- professores por formação 03 --}}
            @include('admin.formularios.quadros.professores-formacao')
            
             {{-- professores pode idade 03 --}}
            @include('admin.formularios.quadros.professores-idade')
            
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <table id="example1" style="width: 100%"
                            class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th colspan="3" class="text-center bg-info">Quadro 7. Manuais Escolares recebidos</th>
                                </tr>
                                <tr>
                                    <th rowspan=""></th>
                                    <th colspan="" class="text-center">Professor</th>
                                    <th colspan="" class="text-center">Alunos</th>
                                </tr>
                            </thead>
                            <tbody id="">
                                <tr>
                                    <td>Ficha de Inscricão</td>
                                    
                                    <td class="text-center">0</td>
                                    
                                    <td class="text-center">0</td>
                                 
                                </tr>
                              
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
<!-- /.content -->
@endsection