@extends('layouts.municipal')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Editar Utilizadores</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('app.municipal-utilizadores-index') }}">Escola</a></li>
                    <li class="breadcrumb-item active">Editar</li>
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
            <div class="col-12 mb-3">
                <form action="{{ route('app.municipal-utilizadores-update', $usuario->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="card">
                        <div class="card-header">
                        </div>
                        <div class="card-body">
                            <div class="row">
                            
                                <div class="form-group col-md-6 col-12">
                                    <label for="nome">Nome <span class="text-danger">*</span></label>
                                    <input type="text" name="nome" value="{{ $usuario->nome }}" placeholder="Nome Completo" class="form-control">
                                    @error('nome')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                                
                                
                                <div class="form-group col-md-6 col-12">
                                    <label for="email">E-mail <span class="text-danger">*</span></label>
                                    <input type="text" name="email" value="{{ $usuario->email }}" placeholder="E-mail" class="form-control">
                                    @error('email')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-3 col-12">
                                    <label for="telefone">Telefone <span class="text-danger">*</span></label>
                                    <input type="text" name="telefone" value="{{ $usuario->telefone }}" placeholder="Telefone" class="form-control">
                                    @error('telefone')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                                    
                                <div class="form-group col-md-3 col-12">
                                    <label for="numero_avaliacoes">Número de Avaliações <span class="text-danger">*</span></label>
                                    <select name="numero_avaliacoes" class="form-control" id="numero_avaliacoes">
                                        <option value="1" {{ $usuario->numero_avaliacoes == '1' ? 'selected' : '' }}>1</option>  
                                        <option value="2" {{ $usuario->numero_avaliacoes == '2' ? 'selected' : '' }}>2</option>  
                                        <option value="3" {{ $usuario->numero_avaliacoes == '3' ? 'selected' : '' }}>3</option>  
                                    </select>
                                    @error('numero_avaliacoes')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                        
                                <div class="form-group col-md-3 col-12">
                                    <label for="password_3">Perfil <span class="text-danger">*</span></label>
                                    <select name="role_id" class="form-control">
                                        @foreach ($roles as $item)
                                            @if ($role)
                                                <option value="{{ $item->id }}" {{ $role->id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                            @else
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>   
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('role_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-3 col-12">
                                    <label for="status">Conta <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control" id="status">
                                        <option value="Bloqueado" {{ $usuario->status == 'Bloqueado' ? 'selected' : '' }}>Activa</option>  
                                        <option value="Desbloqueado" {{ $usuario->status == 'Desbloqueado' ? 'selected' : '' }}>Desactiva</option>  
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