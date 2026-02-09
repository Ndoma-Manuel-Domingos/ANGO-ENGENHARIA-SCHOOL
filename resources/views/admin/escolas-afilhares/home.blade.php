@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Escolas</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.painel-informativo-administrativo') }}">Painel de controle</a></li>
                    <li class="breadcrumb-item active">Escola</li>
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
                        <a href="{{ route('web.escolas-afilhares.create') }}" class="btn btn-primary float-end">Nova Escola</a>
                        @endif
                        <a href="{{ route('ano-lectivo-imprmir') }}" class="btn-danger btn float-end mx-1" target="_blink">Imprimir PDF</a>
                        <a href="{{ route('ano-lectivo-excel') }}" class="btn-success btn float-end mx-1" target="_blink">Imprimir Excel</a>
                    </div>

                    <div class="card-body">
                        <table id="tebelaEscolas" style="width: 100%" class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th>Cod</th>
                                    <th>Nome</th>
                                    <th>Sector</th>
                                    <th>Estado</th>
                                    <th nowrap class="text-right">Acções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($escolas as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->nome }}</td>
                                    <td>{{ $item->sector }}</td>
                                    <td>{{ $item->status }}</td>
                                    <td class="text-right">
                                      <div class="btn-group">
                                        <button type="button" class="btn btn-info">Opções</button>
                                        <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                          <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu" role="menu">
                                          @if (Auth::user()->can('read: ano lectivo'))
                                          <a href="{{ route('web.escolas-afilhares.show', Crypt::encrypt($item->id)) }}" title="Visualizar o Ano Lectivo" class="dropdown-item"><i class="fa fa-eye"></i> Visualizar</a>
                                          @endif

                                          @if (Auth::user()->can('update: ano lectivo'))
                                          <a href="{{  route('web.escolas-afilhares.edit', Crypt::encrypt($item->id) ) }}" title="Editar o Ano Lectivo" class="dropdown-item"><i class="fa fa-edit"></i> Editar</a>
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
    $(function () {
      $("#tebelaEscolas").DataTable({
        language: {
            url: "{{ asset('plugins/datatables/pt_br.json') }}"
        },
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    });
  </script>
@endsection

