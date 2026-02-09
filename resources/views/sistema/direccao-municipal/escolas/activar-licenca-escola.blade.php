@extends('layouts.municipal')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Activar Licença da Escola</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('listagem-escola-municipal') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Licença</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->

        <div class="row">
            <div class="col-12 col-md-12">
                <form action="{{ route('web.activar-licenca-escola-municipal-post') }}" method="post">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                        </div>
                        <div class="card-body">
                            <div class="row">
                             
                                <div class="form-group col-md-6 col-12">
                                    <label for="data_inicio">Data Inicio</label>
                                    <input type="date" name="data_inicio" class="form-control" id="data_inicio" value="{{ $contrato->inicio }}">
                                    @error('data_inicio')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6 col-12">
                                    <label for="data_final">Data Final</label>
                                    <input type="date" name="data_final" class="form-control" id="data_final" value="{{ $contrato->final }}">
                                    @error('data_final')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <input type="hidden" value="{{ $contrato->id }}" name="licenca_id">

                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-primary">Activar Licença</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
