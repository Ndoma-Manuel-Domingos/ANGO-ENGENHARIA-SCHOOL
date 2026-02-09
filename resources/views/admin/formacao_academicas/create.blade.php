@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Cadastrar Formação Académica</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('web.edit-formacao-academico') }}">Formação academica</a></li>
                    <li class="breadcrumb-item active">Cadastrar</li>
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
                    <form action="{{ route('web.store-formacao-academico') }}" method="post">
                        <div class="card-body">
                            <div class="row">
                                @csrf
                                <div class="form-group col-md-6">
                                    <label for="nome">Nome <span class="text-danger">*</span></label>
                                    <input type="text" name="nome" class="form-control" id="nome" placeholder="Mestrado">
                                    @error('nome')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="status">Status <span class="text-danger">*</span></label>
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
                            @if (Auth::user()->can('create: formacao academico'))
                            <button type="submit" class="btn btn-success">Salvar</button>
                            @endif
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