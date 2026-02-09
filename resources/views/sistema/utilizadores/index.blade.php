@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Utilizadores</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home-municipal') }}">Escola</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </div><!-- /.col -->
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header bg-light">
                      <h5 class="text-info  float-start">Registro Encontrados Total: {{ count($usuarios) }}</h5>
                      @if (Auth::user()->can('create: utilizador'))
                      <a href="{{ route('app.utilizadores-create') }}" class="btn btn-primary float-end">Novo Utilizador</a>
                      @endif
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table  style="width: 100%" class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th style="width: 5%">Id</th>
                                    <th width="">Nome</th>
                                    <th width="">E-mail</th>
                                    <th width="">Telefone</th>
                                    <th width="">Conta</th>
                                    <th width="">Perfil</th>
                                    <th width="5%">Acções</th>
                                </tr>
                            </thead>
                            <tbody id="">
                                @if ($usuarios)
                                    @foreach ($usuarios as $key => $usuario)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $usuario->nome ?? 'sem nome' }}</td>
                                            <td>{{ $usuario->email ?? 'sem email' }}</td>
                                            <td>{{ $usuario->telefone ?? '000-000-000' }}</td>
                                            @if ($usuario->status == 'Bloqueado')
                                                <td><span class="badge bg-success">Activa</span></td>
                                            @endif
                                            @if ($usuario->status == 'Desbloqueado')
                                                <td><span class="badge bg-danger">Desactiva</span></td>
                                            @endif
                                            <td>
                                                @foreach ($usuario->roles as $item)
                                                    <span>{{ $item->name }}</span>
                                                @endforeach
                                            </td>
                                            
                                            <td>
                                                @if (Auth::user()->can('update: utilizador'))
                                                <a href="{{ route('app.utilizadores-edit', $usuario->id) }}" class="btn btn-primary">Editar</a>
                                                @endif
                                            </td>
                                        </tr>   
                                    @endforeach                          
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">

                    </div>
              </div>
              <!-- /.card -->
            </div>
        </div>
    </div>
</div>
<!-- /.content-header -->

@endsection