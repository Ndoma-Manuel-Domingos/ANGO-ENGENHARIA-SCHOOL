@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Cadastrar Tipo de Estagio</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('instituicoes_estagios.tipo-estagio') }}">Listagem</a></li>
                    <li class="breadcrumb-item active">Cadastrar</li>
                </ol>
            </div><!-- /.col -->
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                @if(session()->has('danger'))
                    <div class="alert alert-warning">
                        {{ session()->get('danger') }}
                    </div>
                @endif

                @if(session()->has('message'))
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                    </div>
                @endif
            </div>
            <div class="col-12 col-md-12 mb-3">
                <form action="{{ route('instituicoes_estagios.cadastrar-tipo-estagio-store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4 col-12">
                                    <label for="user">Nome <span class="text-danger">*</span></label>
                                    <input type="text" name="nome" value="" placeholder="Nome do Tipo de Estagio" class="form-control">
                                    @error('nome')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-4 col-12">
                                    <label for="user">Codigo <span class="text-danger">*</span></label>
                                    <input type="text" name="codigo" value="" placeholder="Codigo" class="form-control">
                                    @error('codigo')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-4 col-12">
                                    <label for="user">Estado <span class="text-danger">*</span></label>
                                    <select name="status" id="" class="form-control select2" style="width: 100%">
                                        <option value="">Selecionar estado</option>
                                        <option value="activo">Activo</option>
                                        <option value="desactivo">Desactivo</option>
                                    </select>
                                    @error('status')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                               
                                <div class="form-group col-md-12 col-12">
                                    <label for="user">Endereço</label>
                                    <textarea name="descricao" id="" cols="30" rows="3" class="form-control"></textarea>
                                   
                                    @error('descricao')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                                
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /.content-header -->

@endsection