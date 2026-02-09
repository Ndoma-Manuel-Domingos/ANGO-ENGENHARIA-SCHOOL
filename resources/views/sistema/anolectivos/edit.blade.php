@extends('layouts.municipal')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Editar Ano Lectivo</h1>
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
                    <form action="{{ route('web.update-ano-lectivo-global', $anoLectivo->id) }}" method="post">
                        @csrf
                        @method('put')
                        <div class="card-body">
                            <div class="row">
                                @csrf
                                @method('put')
                                <input type="hidden" class="editar_ano_id" name="editar_ano_id" value="">
                                <div class="form-group col-md-6">
                                    <label for="ano_lectivo">Ano Lectivo</label>
                                    <input type="text" name="ano_lectivo" value="{{ $anoLectivo->ano }}" class="form-control" id="ano_lectivo" placeholder="Ex: 2020/2022">
                                    @error('ano_lectivo')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="status_ano">Status </label>
                                    <select name="status_ano" class="form-control editar_status_ano" id="status_ano">
                                        <option value="activo" {{ $anoLectivo->status == "activo" ? 'selected' : '' }}>Activo</option>
                                        <option value="desactivo" {{ $anoLectivo->status == "desactivo" ? 'selected' : '' }}>Desactivo</option>
                                    </select>
                                    @error('status_ano')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="data_inicio">Data Inicio</label>
                                    <input type="date" value="{{ $anoLectivo->inicio }}" name="data_inicio" class="form-control" id="data_inicio">
                                    @error('data_inicio')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="data_final">Data Final</label>
                                    <input type="date" value="{{ $anoLectivo->final }}" name="data_final" class="form-control" id="data_final">
                                    @error('data_final')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>
                        <div class="card-footer justify-content-between">
                            <button type="submit" class="btn btn-success">Actualizar Dados</button>
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
