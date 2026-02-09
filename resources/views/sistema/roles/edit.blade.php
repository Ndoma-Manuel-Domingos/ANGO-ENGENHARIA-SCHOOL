@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Editar Perfil</h1>
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
                <form action="{{ route('roles.update', $role->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="card">
                        <div class="card-header">

                        </div>
                        <div class="card-body">
                            <div class="row">

                                <div class="form-group col-md-6 col-12">
                                    <label for="role">Perfil</label>
                                    <input type="text" name="role" value="{{ $role->name }}" placeholder="Novo Perfil" class="form-control">
                                    @error('role')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6 col-12">
                                    <label for="permission_id">Permissões</label>
                                    <select name="permission_id[]" value="{{ $role->name }}" class="form-control select2" multiple>
                                        @foreach ($permissions_list as $item)
                                            @foreach ($permissions->permissions as $item2)
                                                @if ($item2->id == $item->id)
                                                    <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                                @endif
                                            @endforeach 
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('permission_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                                
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header bg-light">
                      <a href="{{ route('permissions.create') }}" class="btn btn-primary float-end">Permissão</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table  style="width: 100%" class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th style="width: 5%">Id</th>
                                    <th width="">Nome</th>
                                    <th width="">Criação</th>
                                    <th width="">Actualização</th>
                                    <th width="10%" class="text-end">Acções</th>
                                </tr>
                            </thead>
                            <tbody id="">
                                @foreach ($permissions->permissions as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td>{{ $item->updated_at }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('roles.edit', $item->id) }}" class="btn btn-primary">Editar</a>
                                            <a href="{{ route('app.roles.delete', $item->id) }}" class="btn btn-danger">Apagar</a>
                                        </td>
                                    </tr>   
                                @endforeach                          
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