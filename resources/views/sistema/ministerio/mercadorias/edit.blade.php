@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Editar Tipo de Mercadoria</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('web.mercadorias') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Tipo de Mercadoria</li>
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
                    <form action="{{ route('web.update-mercadorias', $mercadoria->id) }}" method="post">
                        @csrf
                        @method('put')
                        <div class="card-body">
                            <div class="row">
                                @csrf
                                @method('put')
                                <input type="hidden" class="editar_ano_id" name="editar_ano_id" value="">
                                <div class="form-group col-md-4 col-12">
                                    <label for="designacao">Designação</label>
                                    <input type="text" name="designacao" value="{{ $mercadoria->designacao }}" class="form-control" id="designacao" placeholder="Nome da Mercadoria">
                                    @error('designacao')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-4 col-12">
                                    <label for="tipo_mercadoria_id">Tipo de Mercadoria</label>
                                    <select name="tipo_mercadoria_id" class="form-control tipo_mercadoria_id" id="tipo_mercadoria_id">
                                        <option value="">Selecionar</option>
                                        @foreach ($tipos_mercadorias as $item)
                                        <option value="{{ $item->id }}" {{ $mercadoria->tipo_mercadoria_id == $item->id ? 'selected' : '' }}>{{ $item->designacao }}</option>
                                        @endforeach
                                    </select>
                                    @error('tipo_mercadoria_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
    
                                <div class="form-group col-md-4 col-12">
                                    <label for="status">Status </label>
                                    <select name="status" class="form-control status" id="status">
                                        <option value="activo" {{ $mercadoria->status == "activo" ? 'selected' : '' }}>Activo</option>
                                        <option value="desactivo" {{ $mercadoria->status == "desactivo" ? 'selected' : '' }}>Desactivo</option>
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