@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Actualizar as credências</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.administrativo') }}">Painel Principal</a></li>
                    <li class="breadcrumb-item active">Privacidade</li>
                </ol>
            </div><!-- /.col -->
        </div>

        <div class="row">
            <div class="col-12 mb-3">
                <form action="{{ route('web.privacidade-update', $usuario->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="card">
                        <div class="card-body">
                            <div class="row">

                                <div class="form-group col-12 col-md-6">
                                    <label for="nome">Nome <span class="text-danger">*</span></label>
                                    <input type="text" name="nome" value="{{ $usuario->nome }}" placeholder="Nome" class="form-control">
                                    @error('nome')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-12 col-md-3">
                                    <label for="email">E-mail <span class="text-danger">*</span></label>
                                    <input type="text" name="email" value="{{ $usuario->email }}" placeholder="E-mail" class="form-control">
                                    @error('email')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-12 col-md-3">
                                    <label for="telefone">Telefone <span class="text-danger">*</span></label>
                                    <input type="text" name="telefone" value="{{ $usuario->telefone }}" placeholder="E-mail" class="form-control">
                                    @error('telefone')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-12 col-md-6">
                                    <label for="user">Usuário <span class="text-danger">*</span></label>
                                    <input type="text" name="user" value="{{ $usuario->usuario }}" placeholder="Novo usuário" class="form-control">
                                    @error('user')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-12 col-md-6">
                                    <label for="password_1">Senha Actual <span class="text-danger">*</span></label>
                                    <input type="password" name="password_1" placeholder="Informe a senha Actual" class="form-control">
                                    @error('password_1')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-12 col-md-6">
                                    <label for="password_2">Nova Senha <span class="text-danger">*</span></label>
                                    <input type="password" name="password_2" placeholder="Informe a Nova Senha"
                                    class="form-control">
                                    @error('password_2')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-12 col-md-6">
                                    <label for="password_3">Confirmar Senha <span class="text-danger">*</span></label>
                                    <input type="password" name="password_3" placeholder="Repetir Nova Senha" class="form-control">
                                    @error('password_3')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Actualizar as Credências</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /.content-header -->

@endsection