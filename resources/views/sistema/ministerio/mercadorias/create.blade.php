@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Cadastrar Mercadoria</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('web.mercadorias') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Mercadorias</li>
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
                    <form action="{{ route('web.store-mercadorias') }}" method="post">
                        <div class="card-body">
                            <div class="row">
                                @csrf
                                <div class="form-group col-md-4 col-12">
                                    <label for="designacao">Designação</label>
                                    <input type="text" name="designacao" class="form-control" id="designacao" placeholder="Nome da Mercadoria">
                                    @error('designacao')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-4 col-12">
                                    <label for="tipo_mercadoria_id">Tipo de Mercadoria</label>
                                    <select name="tipo_mercadoria_id" class="form-control tipo_mercadoria_id" id="tipo_mercadoria_id">
                                        <option value="">Selecionar</option>
                                        @foreach ($tipos_mercadorias as $item)
                                        <option value="{{ $item->id }}">{{ $item->designacao }}</option>
                                        @endforeach
                                    </select>
                                    @error('tipo_mercadoria_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4 col-12">
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