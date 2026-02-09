@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Documentos SAFTs</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('financeiros.financeiro-novos-pagamentos') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">documento</li>
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
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('ficheiros-safts.store') }}" method="POST" class="row" id="formulario">
                            @csrf
                            <div class="col-12 col-md-3 mb3">
                                <label for="" class="form-label">Data Inicio</label>
                                <input type="date" class="form-control" name="data_inicio" value="">
                            </div>
                            <div class="col-12 col-md-3 mb3">
                                <label for="" class="form-label">Data Final</label>
                                <input type="date" class="form-control" name="data_final" value="">
                            </div>
                        </form>
                    </div>

                    <div class="card-footer">
                        <button form="formulario" class="btn btn-primary float-right" type="submit">
                            Gerar Ficheiro SAF-T
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
