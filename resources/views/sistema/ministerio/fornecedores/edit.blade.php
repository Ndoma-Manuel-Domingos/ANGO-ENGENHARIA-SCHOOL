@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Editar Fornecedor</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('web.fornecedores') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Fornecedores</li>
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
                    <form action="{{ route('web.update-fornecedores', $fornecedor->id) }}" method="post">
                        @csrf
                        @method('put')
                        <div class="card-body">
                            <div class="row">
                                @csrf
                                @method('put')
                                <div class="form-group col-md-4 col-12">
                                    <label for="nome">Nome</label>
                                    <input type="text" name="nome" value="{{ $fornecedor->nome }}" class="form-control" id="nome" placeholder="Nome do Fornecedor">
                                    @error('nome')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-4 col-12">
                                    <label for="nif">NIF</label>
                                    <input type="text" name="nif" value="{{ $fornecedor->nif }}" class="form-control" id="nif" placeholder="NIF">
                                    @error('nif')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
    
                                <div class="form-group col-md-4 col-12">
                                    <label for="status">Status </label>
                                    <select name="status" class="form-control status" id="status">
                                        <option value="activo" {{ $fornecedor->status == "activo" ? 'selected' : '' }}>Activo</option>
                                        <option value="desactivo" {{ $fornecedor->status == "desactivo" ? 'selected' : '' }}>Desactivo</option>
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