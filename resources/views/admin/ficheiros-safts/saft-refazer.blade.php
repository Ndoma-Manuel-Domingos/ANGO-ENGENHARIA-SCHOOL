@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Refazer o Documento SAFT</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('financeiros.financeiro-novos-pagamentos') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Saft</li>
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
                        <form action="{{ route('ficheiros-safts.refazer-store') }}" method="POST" class="row" id="formulario">
                            @csrf
                            <div class="col-12 col-md-6 mb3">
                                <label for="tipo_documento" class="form-label">Selecione O Tipo de documento</label>
                                <select name="tipo_documento" id="tipo_documento" class="form-control">
                                    <option value="FR">Factura Recibo</option>
                                    <option value="FP">Factura Pró-forma</option>
                                    <option value="FT">Factura</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6 mb3">
                                <label for="ano_lectivo_id" class="form-label">Ano Lectivos</label>
                                <select name="ano_lectivo_id" id="ano_lectivo_id" class="form-control">
                                    @foreach ($anos_lectivos as $item)
                                    <option value="{{ $item->id }}" {{ $ano_lectivo_activo->id == $item->id ? "selected" : "" }}>{{ $item->ano }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>

                    <div class="card-footer">
                        <button form="formulario" class="btn btn-primary float-right" type="submit">
                            Refazer Ficheiro SAF-T
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
