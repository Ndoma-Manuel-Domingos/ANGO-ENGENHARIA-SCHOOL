@extends('layouts.provinciais')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Painel de controle Recursos Humanos - Provincial</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{ route('home-provincial') }}">Voltar ao painel</a></li>
                  <li class="breadcrumb-item active">Funcionários</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<section class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12 col-md-8 mb-4">

                <div id="poll_div"></div>

                @if($filtros['tipo_graficos'] == 'ColumnChart')
                {!! $lava->render('ColumnChart', 'Funcionarios', 'poll_div') !!}
                @else

                @if($filtros['tipo_graficos'] == 'BarChart')
                {!! $lava->render('BarChart', 'Funcionarios', 'poll_div') !!}
                @else

                @if($filtros['tipo_graficos'] == 'AreaChart')
                {!! $lava->render('AreaChart', 'Funcionarios', 'poll_div') !!}
                @else

                @if($filtros['tipo_graficos'] == 'DonutChart')
                {!! $lava->render('DonutChart', 'Funcionarios', 'poll_div') !!}
                @else

                @if($filtros['tipo_graficos'] == 'PieChart')
                {!! $lava->render('PieChart', 'Funcionarios', 'poll_div') !!}
                @else

                @if($filtros['tipo_graficos'] == 'LineChart')
                {!! $lava->render('LineChart', 'Funcionarios', 'poll_div') !!}
                @else

                {!! $lava->render('ColumnChart', 'Funcionarios', 'poll_div') !!}

                @endif
                @endif
                @endif
                @endif
                @endif
                @endif


            </div>

            <div class="col-12 col-md-4 mb-4">

                <form action="{{ route('web.funcionarios-provincial-controlo') }}" method="get">
                    @csrf

                    <div class="card">

                        <div class="card-body">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6" style="width: 70px">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                            </svg>

                            <div class="form-group pt-4 col-md-12 col-12">
                                <label for="tipo_graficos" class="form-label">Tipos Gráficos</label>
                                <select name="tipo_graficos" id="tipo_graficos"
                                    class="form-control tipo_graficos select2">
                                    <option value="">Selecione Tipo Grafico</option>
                                    <option value="ColumnChart" {{ $filtros['tipo_graficos']=='ColumnChart' ? 'selected'
                                        : '' }}>Gráfico de Colunas</option>
                                    <option value="BarChart" {{ $filtros['tipo_graficos']=='BarChart' ? 'selected' : ''
                                        }}>Gráfico de barras</option>
                                    <option value="AreaChart" {{ $filtros['tipo_graficos']=='AreaChart' ? 'selected'
                                        : '' }}>Gráfico de área</option>
                                    <option value="DonutChart" {{ $filtros['tipo_graficos']=='DonutChart' ? 'selected'
                                        : '' }}>Gráfico de Rosca</option>
                                    <option value="PieChart" {{ $filtros['tipo_graficos']=='PieChart' ? 'selected' : ''
                                        }}>Gráfico de Pizza</option>
                                    <option value="LineChart" {{ $filtros['tipo_graficos']=='LineChart' ? 'selected'
                                        : '' }}>Gráfico de Linhas</option>
                                </select>
                                @error('tipo_graficos')
                                <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        <div class="card-footer pt-4">

                            <button type="submit" class="btn btn-primary"> Filtrar</button>

                        </div>

                    </div>

                </form>

            </div>

            <div class="col-12 col-md-12">
                <div class="callout callout-info bg-info">
                    <h5><i class="fas fa-info"></i> Departamentos <span class="float-right">{{ count($departamentos)
                            }}</span></h5>
                </div>
            </div>

        </div>

        <div class="row">
            @foreach ($departamentos as $item)
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h5><a
                                href="{{ route('web.funcionarios-provincial-departamentos',  ['departamento_id' => $item->id, 'departamento' => $item->departamento]) }}">{{
                                $item->departamento }}</a></h5>
                        <p>
                            <span>Activo: {{ $item->total_funcionarios_departamento_activo(2, $item->id, $direccao->id)
                                }}</span>
                        </p>

                        <p>
                            <span>Desactivo: {{ $item->total_funcionarios_departamento_desactivo(2, $item->id,
                                $direccao->id) }}</span>
                        </p>

                        <h5>
                            <span>Total: {{ $item->total_funcionarios_departamento_desactivo(2, $item->id,
                                $direccao->id) + $item->total_funcionarios_departamento_activo(2, $item->id,
                                $direccao->id) }}</span>
                        </h5>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <a href="{{ route('web.funcionarios-provincial-departamentos', ['departamento_id' => ""]) }}"
                        class="small-box-footer">Listar Todos <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endforeach
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="callout callout-info bg-info">
                    <h5><i class="fas fa-info"></i> Cargos <span class="float-right">{{ count($cargos) }}</span></h5>
                </div>
            </div>
        </div>

        <div class="row">
            @foreach ($cargos as $item)
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h5><a
                                href="{{ route('web.funcionarios-provincial-cargos',  ['cargo_id' => $item->id, 'cargo' => $item->cargo]) }}">{{
                                $item->cargo }}</a></h5>
                        <p>
                            <span>Activo: {{ $item->total_funcionarios_cargo_activo(2, $item->id, $direccao->id)
                                }}</span>
                        </p>

                        <p>
                            <span>Desactivo: {{ $item->total_funcionarios_cargo_desactivo(2, $item->id, $direccao->id)
                                }}</span>
                        </p>

                        <h5>
                            <span>Total: {{ $item->total_funcionarios_cargo_desactivo(2, $item->id, $direccao->id,
                                $direccao->id) + $item->total_funcionarios_cargo_activo(2, $item->id, $direccao->id)
                                }}</span>
                        </h5>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <a href="{{ route('web.funcionarios-provincial-cargos', ['cargo_id' => ""]) }}"
                        class="small-box-footer">Listar Todos <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>
<!-- /.content -->
@endsection