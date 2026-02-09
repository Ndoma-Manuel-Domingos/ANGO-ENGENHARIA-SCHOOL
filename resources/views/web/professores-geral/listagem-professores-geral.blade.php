@extends('layouts.admin')

@section('content')

  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Professores</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          {{-- <ol class="breadcrumb float-sm-right">
           <li class="breadcrumb-item"><a href="">Voltar</a></li>
            <li class="breadcrumb-item active">Listagem</li>
          </ol> --}}
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
            <div class="callout callout-info">
                <h5><i class="fas fa-info"></i>Listagem geral dos Professores </h5>
            </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('estudantes-matriculas-imprmir') }}" class="btn btn-primary float-end mx-2" target="_blink">Imprimir</a>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive">
                    <table id="carregarTabelaMatricula"  style="width: 100%" class="table  table-bordered table-striped table-striped">
                        <thead>
                            <tr>
                                <th>Nº</th>
                                <th>Nome</th>
                                <th>Nascido</th>
                                <th>Bilhete</th>
                                <th>Genero</th>
                                <th>Status</th>
                                <th style="width: 7%">Acções</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if (count($professores) != 0)
                            @foreach ($professores as $item)
                                <tr>
                                    <td><a href="{{ route('app.informacao-professores', $item->id) }}" class="text-secondary">{{ $item->id }}</a></td>
                                    <td><a href="{{ route('app.informacao-professores', $item->id) }}" class="text-secondary">{{ $item->nome }} {{ $item->sobre_nome }}</a></td>
                                    <td>{{ $item->nascimento }}</td>
                                    <td>{{ $item->bilheite }}</td>
                                    <td>{{ $item->genero }}</td>
                                    <td>{{ $item->status }}</td>
                                    <td>
                                        <a href="{{ route('app.informacao-professores', $item->id) }}" title="Visualizar Professor" id="{{ $item->id }}" class="btn btn-primary"><i class="fa fa-eye"></i> Visualizar </a>
                                    </td>
                                </tr>    
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
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
      $("#carregarTabelaMatricula").DataTable({
        language: {
            url: "{{ asset('plugins/datatables/pt_br.json') }}"
        },
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    });
  </script>
@endsection

