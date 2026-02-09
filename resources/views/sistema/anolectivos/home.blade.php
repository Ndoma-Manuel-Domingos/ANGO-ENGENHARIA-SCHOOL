@extends('layouts.municipal')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Anos Lectivos</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home-municipal') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Anos lectivos</li>
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
                        @if (Auth::user()->can('create: ano lectivo'))
                        <a href="{{ route('web.create-ano-lectivo-global') }}" class="btn btn-primary float-end">Novo Ano Lectivo</a>
                        @endif
                        <a href="{{ route('ano-lectivo-imprmir') }}" class="btn-danger btn float-end mx-1" target="_blink">Imprimir PDF</a>
                        <a href="{{ route('ano-lectivo-excel') }}" class="btn-success btn float-end mx-1" target="_blink">Imprimir Excel</a>
                    </div>

                    <div class="card-body">
                        <table id="carregarTabela" style="width: 100%" class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th>Cod</th>
                                    <th>Ano Lectivo</th>
                                    <th>Status</th>
                                    <th>Inicio</th>
                                    <th>Final</th>
                                    <th nowrap class="text-right">Acções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($listAnos as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->ano }}</td>
                                    <td>{{ $item->status }}</td>
                                    <td>{{ $item->inicio }}</td>
                                    <td>{{ $item->final }}</td>
                                    <td class="text-right">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info">Opções</button>
                                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                @if (Auth::user()->can('update: estado'))
                                                <a href="{{ route('web.route-desactivar-ano-lectivo-global', $item->id) }}" title="Activar ou desactivar o ano Lectivo" class="dropdown-item"><i class="fa fa-check-square-o"></i> Activar e Desactivar</a>
                                                @endif
                                                @if (Auth::user()->can('update: ano lectivo'))
                                                <a href="{{  route('web.edit-ano-lectivo-global', $item->id ) }}" title="Editar o Ano Lectivo" value="{{ $item->id }}" class="dropdown-item  editar_ano_lectivo_id"><i class="fa fa-edit"></i> Editar</a>
                                                @endif
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
                    
                    <div class="card-footer"></div>
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection


@section('scripts')
<script>
    const tabelas = [
        "#carregarTabela"
    , ];
    tabelas.forEach(inicializarTabela);

</script>
@endsection
