@extends('layouts.municipal')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Graficos</h1>
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
                <div class="col-12-col-md-12">
                    <form action="{{ route('municipal-grafico-turma') }}" method="get">
                    @csrf
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    
                                    <div class="form-group pt-4 col-md-2 col-12">
                                        <label for="ano_lectivo_id" class="form-label">Ano Lectivo</label>
                                        <select name="ano_lectivo_id" id="ano_lectivo_id" class="form-control ano_lectivo_id select2">
                                            <option value="">Selecione Ano Lectivo</option>
                                            @foreach ($ano_lectivos as $item)
                                                <option value="{{ $item->id }}" {{ $requests['ano_lectivo_id'] == $item->id ? 'selected' : '' }}>{{ $item->ano }}</option>
                                            @endforeach
                                        </select>
                                        @error('ano_lectivo_id')
                                            <span class="text-danger error-text">{{ $message }}</span>
                                        @enderror
                                    </div>  
                                    
                                    <div class="form-group pt-4 col-md-3 col-12">
                                        <label for="shcools_id" class="form-label">Escolas</label>
                                        <select name="shcools_id" id="shcools_id" class="form-control shcools_id select2">
                                            <option value="">Escola</option>
                                            @foreach ($escolas as $item)
                                                <option value="{{ $item->id }}" {{ $requests['shcools_id'] == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                        @error('shcools_id')
                                            <span class="text-danger error-text">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group pt-4 col-md-2 col-12">
                                        <label for="municipio_id" class="form-label">Municípios</label>
                                        <select name="municipio_id" id="municipio_id" class="form-control municipio_id select2">
                                            <option value="">Selecione Município</option>
                                            @foreach ($municipios as $item)
                                                <option value="{{ $item->id }}" {{ $requests['municipio_id'] == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                        @error('municipio_id')
                                            <span class="text-danger error-text">{{ $message }}</span>
                                        @enderror
                                    </div> 
                                    
                                    <div class="form-group pt-4 col-md-2 col-12">
                                        <label for="distrito_id" class="form-label">Distritos</label>
                                        <select name="distrito_id" id="distrito_id" class="form-control distrito_id select2">
                                            <option value="">Selecione Distritos</option>
                                            @foreach ($distritos as $item)
                                                <option value="{{ $item->id }}" {{ $requests['distrito_id'] == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                        @error('distrito_id')
                                            <span class="text-danger error-text">{{ $message }}</span>
                                        @enderror
                                    </div> 
                                </div>
                                
                            </div>
                            <div class="card-footer pt-4">
                                <button type="submit" class="btn btn-primary"> Filtrar</button>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-6 h-6" style="width: 20px">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                                </svg>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="row">
                
                {{-- <div class="col-12 col-md-8 mb-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <div id="chart_div"></div>
                            {!! $lava2->render('BarChart', 'Funcionarios', 'chart_div') !!}
                        </div>
                    </div>
                </div> --}}
            
            
                <div class="col-12 col-md-8 mb-4">
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <div class="card">
                                <div class="card-body text-center">
                                    <div id="poll_div"></div>
                                    {!! $lava->render('ColumnChart', 'Estudantes', 'poll_div') !!}
                                    
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
                        
                        <div class="col-12 col-md-6 mb-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <div id="poll_div_desistentes"></div>
                                    {!! $lava->render('ColumnChart', 'EstudantesDesistentes', 'poll_div_desistentes') !!}
                                    
                                    <p>
                                        <span style="background-color: #00BFFF;padding: 1px 15px;margin-right: 10px;"></span> Masculinos <strong>{{ number_format($resultado->percentual_masculino_desistentes, 2, ',', '.')  }} % </strong> 
                                        
                                        <span style="background-color:#D2691E;padding: 1px 15px;margin-left: 50px;margin-right: 10px;"></span> Femeninos <strong>{{ number_format($resultado->percentual_feminino_desistentes, 2, ',', '.')  }} %</strong> 
                                    </p>
                                    
                                    <p>
                                        <span style="background-color: #00BFFF;padding: 1px 15px;margin-right: 10px;"></span> Masculinos <strong>{{ number_format($resultado->total_masculino_desistentes, 1, ',', '.')  }}</strong> 
                                        
                                        <span style="background-color:#D2691E;padding: 1px 15px;margin-left: 50px;margin-right: 10px;"></span> Femeninos <strong>{{ number_format($resultado->total_feminino_desistentes, 1, ',', '.')  }}</strong> 
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-6 mb-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <div id="poll_div_falecidos"></div>
                                    {!! $lava->render('ColumnChart', 'EstudantesFalecidos', 'poll_div_falecidos') !!}
                                    
                                    <p>
                                        <span style="background-color: #00BFFF;padding: 1px 15px;margin-right: 10px;"></span> Masculinos <strong>{{ number_format($resultado->percentual_masculino_falecidos, 2, ',', '.')  }} % </strong> 
                                        
                                        <span style="background-color:#D2691E;padding: 1px 15px;margin-left: 50px;margin-right: 10px;"></span> Femeninos <strong>{{ number_format($resultado->percentual_feminino_falecidos, 2, ',', '.')  }} %</strong> 
                                    </p>
                                    
                                    <p>
                                        <span style="background-color: #00BFFF;padding: 1px 15px;margin-right: 10px;"></span> Masculinos <strong>{{ number_format($resultado->total_masculino_falecidos, 1, ',', '.')  }}</strong> 
                                        
                                        <span style="background-color:#D2691E;padding: 1px 15px;margin-left: 50px;margin-right: 10px;"></span> Femeninos <strong>{{ number_format($resultado->total_feminino_falecidos, 1, ',', '.')  }}</strong> 
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
                            {!! $lava->render('BarChart', 'EstudantesEspecifico', 'poll_div_especifico') !!}
                        </div>
                    </div>
                </div>
                
                
                
            </div>

        </div><!-- /.container-fluid -->
    </div>

@endsection


@section('scripts')
  <script>
    // Eventos
    $("#provincia_id").change(function () {
      carregarDados({
        origem: "#provincia_id",
        destino: "#municipio_id",
        rota: rotas.carregarMunicipios,
        mensagemSucesso: "Municípios carregados"
      });
    });
    
    $("#municipio_id").change(function () {
      carregarDados({
        origem: "#municipio_id",
        destino: "#distrito_id",
        rota: rotas.carregarDistritos,
        mensagemSucesso: "Distritos carregados"
      });
    });

  </script>
@endsection