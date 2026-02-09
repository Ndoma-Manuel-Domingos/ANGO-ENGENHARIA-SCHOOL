@extends('layouts.provinciais')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Editar Departamento</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('web.departamento-provincial') }}">Voltar em departamentos</a></li>
                    <li class="breadcrumb-item active">Editar</li>
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
                    <form action="{{ route('web.update-departamento-provincial', $departamento->id) }}" method="post">
                        @csrf
                        @method('put')
                        <div class="card-body">
                            <div class="row">
                                @csrf
                                @method('put')
                                <input type="hidden" class="editar_ano_id" name="editar_ano_id" value="">
                                <div class="form-group col-md-6">
                                    <label for="departamento">Departamento</label>
                                    <input type="text" name="departamento" value="{{ $departamento->departamento }}" class="form-control" id="departamento" >
                                    @error('departamento')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
    
                                <div class="form-group col-md-6">
                                    <label for="status">Status </label>
                                    <select name="status" class="form-control status" id="status">
                                        <option value="activo" {{ $departamento->status == "activo" ? 'selected' : '' }}>Activo</option>
                                        <option value="desactivo" {{ $departamento->status == "desactivo" ? 'selected' : '' }}>Desactivo</option>
                                    </select>
                                    @error('status')
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