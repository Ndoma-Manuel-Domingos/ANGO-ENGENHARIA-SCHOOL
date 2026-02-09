@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Cadastrar Utilizadores</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                {{-- <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Escola</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol> --}}
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
            <div class="col-12 mb-3">
                <form action="{{ route('app.utilizadores-store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-header">

                        </div>
                        <div class="card-body">
                            <div class="row">
                            
                                <div class="form-group col-md-6 col-12">
                                    <label for="nome">Nome</label>
                                    <input type="text" name="nome" value="" placeholder="Nome Completo" class="form-control">
                                    @error('nome')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                                
                                
                                <div class="form-group col-md-3 col-12">
                                    <label for="email">E-mail</label>
                                    <input type="text" name="email" value="" placeholder="E-mail" class="form-control">
                                    @error('email')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-3 col-12">
                                    <label for="telefone">Telefone</label>
                                    <input type="text" name="telefone" value="" placeholder="Telefone" class="form-control">
                                    @error('telefone')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6 col-12">
                                    <label for="user">Usuário</label>
                                    <input type="text" name="user" value="" placeholder="Novo usuário" class="form-control">
                                    @error('user')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6 col-12">
                                    <label for="password_2">Senha</label>
                                    <input type="password" name="password_2" placeholder="Informe a Senha"
                                        class="form-control">
                                    @error('password_2')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6 col-12">
                                    <label for="password_3">Confirmar Senha</label>
                                    <input type="password" name="password_3" placeholder="Repetir Senha" class="form-control">
                                    @error('password_3')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="password_3">Perfil</label>
                                    <select name="role_id" class="form-control">
                                        @foreach ($roles as $item)
                                           <option value="{{ $item->id }}">{{ $item->name }}</option>  
                                        @endforeach
                                    </select>
                                    @error('role_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-3 col-12">
                                    <label for="status">Conta</label>
                                    <select name="status" class="form-control" id="status">
                                        <option value="Bloqueado">Activa</option>  
                                        <option value="Desbloqueado">Desactiva</option>  
                                    </select>
                                    @error('status')
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