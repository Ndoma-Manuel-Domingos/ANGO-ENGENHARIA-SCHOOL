@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Biométrico</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('recursos_humanos') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Pontos</li>
                </ol>

            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="callout callout-info">
                    <h5><i class="fas fa-info"></i> Controle geral de assuidade.</h5>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('web.biometrico-store') }}" method="POST" class="row" id="formulario">
                            @csrf
                            <div class="col-12 col-md-3 mb3">
                                <label for="start_date" class="form-label">Data Inicio</label>
                                <input type="date" class="form-control" name="start_date" id="start_date" value="">
                            </div>
                            <div class="col-12 col-md-3 mb3">
                                <label for="end_date" class="form-label">Data Final</label>
                                <input type="date" class="form-control" name="end_date" id="end_date" value="">
                            </div>
                        </form>
                    </div>

                    <div class="card-footer">
                        <button form="formulario" class="btn btn-primary float-right" type="submit">
                            Filtrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection
