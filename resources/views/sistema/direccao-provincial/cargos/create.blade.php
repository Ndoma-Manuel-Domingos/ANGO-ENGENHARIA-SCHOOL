@extends('layouts.provinciais')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Cadastrar Departamento</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('web.cargos-provincial') }}">Voltar em cargos</a></li>
                    <li class="breadcrumb-item active">Criar</li>
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
                    <form action="{{ route('web.store-cargos-provincial') }}" method="post">
                        <div class="card-body">
                            <div class="row">
                                @csrf
                                <div class="form-group col-md-6">
                                    <label for="cargo">Cargo</label>
                                    <input type="text" name="cargo" class="form-control" id="cargo" placeholder="Professor">
                                    @error('cargo')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="departamento_id">Departamento</label>
                                    <select name="departamento_id" class="form-control departamento_id" id="departamento_id">
                                        <option value="">Selecione</option>
                                        @foreach ($departamentos as $departamento)
                                            <option value="{{ $departamento->id }}">{{ $departamento->departamento }}</option>
                                        @endforeach
                                    </select>
                                    @error('departamento_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="salario">Salário</label>
                                    <input type="text" name="salario" class="form-control" id="salario" placeholder="Salário">
                                    @error('salario')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="status">Status</label>
                                    <select name="status" class="form-control status" id="status">
                                        <option value="activo">Activo</option>
                                        <option value="desactivo">Desactivo</option>
                                    </select>
                                    @error('status')
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