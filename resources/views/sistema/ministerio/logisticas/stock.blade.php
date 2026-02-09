@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Stock de Mercadorias</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('logisticas.index') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Distritos</li>
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
                  <div class="card-header">
                    <h5>Controle de entradas e saídas de mercadorias</h5>
                  </div>
                    <form action="{{ route('web.stock-mercadorias') }}" method="get">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-3 col-12">
                                    <label for="data_inicio">Data Inicio</label>
                                    <input type="date" name="data_inicio" class="form-control">
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="data_final">Data Final</label>
                                    <input type="date" name="data_final" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{-- @if (Auth::user()->can('create: departamento')) --}}
                        <a href="{{ route('web.stock-mercadorias-create') }}" class="btn btn-primary float-end">Actualizar Stock</a>
                        {{-- @endif --}}
                        <a href="{{ route('web.fornecedores-pdf') }}" class="btn-danger btn float-end mx-1" target="_blink">Imprimir PDF</a>
                        <a href="{{ route('web.fornecedores-excel') }}" class="btn-success btn float-end mx-1" target="_blink">Imprimir Excel</a>
                    </div>

                    <div class="card-body">
                        <table id="carregarMercadorias" style="width: 100%" class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th>Nº</th>
                                    <th>Mercadoria</th>
                                    <th>Quantidade</th>
                                    <th>Unidade</th>
                                    <th>Status</th>
                                    <th>Fornecedor</th>
                                    <th>Operador</th>
                                    <th>Data</th>
                                    <th nowrap class="text-right">Acções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stocks as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->mercadoria->designacao }}</td>
                                    <td>{{ $item->quantidade }}</td>
                                    <td class="text-capitalize">{{ $item->unidade }}</td>
                                    @if ($item->status == "entrada")
                                    <td class="text-success text-capitalize">{{ $item->status }} <i class="float-right fas fa-caret-down"></i></i></td>
                                    <td>{{ $item->entrada($item->fornecedor->id)  }}</td>
                                    @endif
                                    @if ($item->status == "saida")
                                    <td class="text-danger text-capitalize">{{ $item->status }} <i class="float-right fas fa-caret-up"></i></td>
                                    <td>{{ $item->saida($item->level, $item->shcools_id) }}</td>
                                    @endif
                                    <td>{{ $item->user->nome }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td class="text-right">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info">Opções</button>
                                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                {{-- @if (Auth::user()->can('update: departamento')) --}}
                                                @if ($item->status == "entrada")
                                                <a href="{{  route('web.stock-mercadorias-edit', $item->id ) }}" title="Editar Tipo de Mercadoria" value="{{ $item->id }}" class="dropdown-item"><i class="fa fa-edit"></i> Editar</a>
                                                @endif
                                                @if ($item->status == "saida")
                                                <a href="{{  route('web.stock-mercadorias-distribuicao-edit', $item->id ) }}" title="Editar Tipo de Mercadoria" value="{{ $item->id }}" class="dropdown-item"><i class="fa fa-edit"></i> Editar</a>
                                                @endif
                                                {{-- @endif --}}

                                                {{-- @if (Auth::user()->can('update: departamento')) --}}
                                                <a href="{{  route('web.edit-fornecedores', $item->id ) }}" title="Editar Tipo de Mercadoria" value="{{ $item->id }}" class="dropdown-item"><i class="fa fa-edit"></i> Detalhe</a>
                                                {{-- @endif --}}
                                                {{-- @if (Auth::user()->can('delete: departamento')) --}}
                                                <a href="{{  route('web.delete-fornecedores', $item->id ) }}" title="Eliminar Tipo de Mercadoria" value="{{ $item->id }}" class="dropdown-item"><i class="fa fa-trash"></i> Excluir</a>
                                                {{-- @endif --}}
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="#">Outros</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection
