@extends('layouts.municipal')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Graficos Funcionários</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Painel Administrativo</a></li>
                        <li class="breadcrumb-item active">Inicio</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            
            <div class="row">
            
                <div class="col-12 col-md-8 mb-4">
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <div class="card">
                                <div class="card-body text-center">
                                    <div id="poll_div"></div>
                                    {!! $lava->render('ColumnChart', 'Funcionarios', 'poll_div') !!}
                                    
                                    <p>
                                        <span style="background-color: #00BFFF;padding: 1px 15px;margin-right: 10px;"></span> Masculinos <strong>{{ number_format($resultado->percentual_masculino, 2, ',', '.')  }} % </strong> 
                                        
                                        <span style="background-color:#D2691E;padding: 1px 15px;margin-left: 50px;margin-right: 10px;"></span> Femeninos <strong>{{ number_format($resultado->percentual_feminino, 2, ',', '.')  }} %</strong> 
                                    </p>
                                    
                                    <p>
                                        <span style="background-color: #00BFFF;padding: 1px 15px;margin-right: 10px;"></span> Masculinos <strong>{{ number_format($resultado->total_masculino, 1, ',', '.')  }}</strong> 
                                        
                                        <span style="background-color:#D2691E;padding: 1px 15px;margin-left: 50px;margin-right: 10px;"></span> Femeninos <strong>{{ number_format($resultado->total_feminino, 1, ',', '.')  }}</strong> 
                                    </p>
                                </div>
                            </div>
                        </div>
                     
                    </div>                    
                </div>
                
                <div class="col-12 col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <div id="poll_div_especifico"></div>
                            {!! $lava->render('BarChart', 'FuncionariosEspecifico', 'poll_div_especifico') !!}
                        </div>
                    </div>
                </div>
                
            </div>

        </div><!-- /.container-fluid -->
    </div>

@endsection


