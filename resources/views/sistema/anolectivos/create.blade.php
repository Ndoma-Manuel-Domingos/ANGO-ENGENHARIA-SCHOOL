@extends('layouts.municipal')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Cadastrar Ano Lectivo</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('ano-lectivo-global') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Ano lectivo</li>
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
                    <form action="{{ route('web.store-ano-lectivo-global') }}" method="post">
                        <div class="card-body">
                            <div class="row">
                                @csrf
                                <div class="form-group col-md-6">
                                    <label for="ano_lectivo">Ano Lectivo</label>
                                    <input type="text" name="ano_lectivo" class="form-control" id="ano_lectivo" placeholder="Ex: 2020/2022">
                                    @error('ano_lectivo')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="status_ano">Status</label>
                                    <select name="status_ano" class="form-control editar_status_ano" id="status_ano">
                                        <option value="desactivo">Desactivo</option>
                                        <option value="activo">Activo</option>
                                    </select>
                                    @error('status_ano')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="data_inicio">Data Inicio</label>
                                    <input type="date" name="data_inicio" class="form-control" id="data_inicio">
                                    @error('data_inicio')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="data_final">Data Final</label>
                                    <input type="date" name="data_final" class="form-control" id="data_final">
                                    @error('data_final')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>
                        <div class="card-footer justify-content-between">
                            <button type="submit" class="btn btn-success">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection
