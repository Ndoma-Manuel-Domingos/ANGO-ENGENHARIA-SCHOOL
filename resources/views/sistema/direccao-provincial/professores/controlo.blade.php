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
                    <li class="breadcrumb-item">Voltar</a></li>
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
            <div class="col-12 col-md-12">
                <div class="callout callout-info bg-info">
                    <h5><i class="fas fa-info"></i> Departamentos <span class="float-right">{{ count($departamentos) }}</span></h5>
                </div>
            </div>
        </div>

        <div class="row">
            @foreach ($departamentos as $item)
                <div class="col-lg-3 col-12 col-md-12">
                    <!-- small box -->
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h5><a href="{{ route('web.funcionarios-municipal-departamentos',  ['departamento_id' => $item->id, 'departamento' => $item->departamento]) }}">{{  $item->departamento }}</a></h5>
                            <p>
                                <span>Activo: {{ $item->total_funcionarios_departamento_activo(2, $item->id, $direccao->id) }}</span>
                            </p>
                            
                            <p>
                                <span>Desactivo: {{ $item->total_funcionarios_departamento_desactivo(2, $item->id, $direccao->id) }}</span>
                            </p>
                            
                            <h5>
                                <span>Total: {{ $item->total_funcionarios_departamento_desactivo(2, $item->id, $direccao->id) + $item->total_funcionarios_departamento_activo(2, $item->id, $direccao->id) }}</span>
                            </h5>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <a href="{{ route('web.funcionarios-municipal-departamentos', ['departamento_id' => ""]) }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
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
                            <h5><a href="{{ route('web.funcionarios-municipal-cargos',  ['cargo_id' => $item->id, 'cargo' => $item->cargo]) }}">{{  $item->cargo }}</a></h5>
                            <p>
                                <span>Activo: {{ $item->total_funcionarios_cargo_activo(2, $item->id, $direccao->id) }}</span>
                            </p>
                            
                            <p>
                                <span>Desactivo: {{ $item->total_funcionarios_cargo_desactivo(2, $item->id, $direccao->id) }}</span>
                            </p>
                            
                            <h5>
                                <span>Total: {{ $item->total_funcionarios_cargo_desactivo(2, $item->id, $direccao->id, $direccao->id) + $item->total_funcionarios_cargo_activo(2, $item->id, $direccao->id) }}</span>
                            </h5>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <a href="{{ route('web.funcionarios-municipal-cargos', ['cargo_id' => ""]) }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>
<!-- /.content -->
@endsection