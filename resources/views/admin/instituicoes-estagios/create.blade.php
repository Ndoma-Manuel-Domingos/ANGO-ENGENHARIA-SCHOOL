@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Cadastrar Instituições Para Estagios</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('web.instituicao-estagio') }}">Listagem</a></li>
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
                <form action="{{ route('web.cadastrar-instituicao-store-estagio') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4 col-12">
                                    <label for="user">Nome instituição <span class="text-danger">*</span></label>
                                    <input type="text" name="nome" value="" placeholder="Nome da Instituição" class="form-control">
                                    @error('nome')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-4 col-12">
                                    <label for="user">Documento/NIF <span class="text-danger">*</span></label>
                                    <input type="text" name="nif" value="" placeholder="Documento" class="form-control">
                                    @error('nif')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-4 col-12">
                                    <label for="user">E-mail <span class="text-danger">*</span></label>
                                    <input type="text" name="email" value="" placeholder="E-mail" class="form-control">
                                    @error('email')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-4 col-12">
                                    <label for="director">Director <span class="text-danger">*</span></label>
                                    <input type="text" name="director" value="" placeholder="Director" class="form-control">
                                    @error('director')
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
                                
                                <div class="form-group col-md-4 col-12">
                                    <label for="user">Tipo <span class="text-danger">*</span></label>
                                    <select name="tipo" id="" class="form-control select2" style="width: 100%">
                                        <option value="">Selecionar Tipo</option>
                                        <option value="interna">Interna</option>
                                        <option value="externa">Externa</option>
                                    </select>
                                    @error('tipo')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                                
                                
                                <div class="form-group col-md-12 col-12">
                                    <label for="user">Endereço</label>
                                    <textarea name="endereco" id="" cols="30" rows="3" class="form-control"></textarea>
                                   
                                    @error('endereco')
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