@extends('layouts.provinciais')

@section('content')

  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-9">
          <h1 class="m-0 text-dark">Estudantes da(o) Escola: <a href="{{ route('listagem-escola-provincial', Crypt::encrypt($escola->id)) }}" class="text-secondary">{{ $escola->nome }}</a></h1>
        </div><!-- /.col -->
        <div class="col-sm-3">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('web.informacao-escola-provincial', Crypt::encrypt($escola->id)) }}">Voltar a estudantes</a></li>
            <li class="breadcrumb-item active">estudantes</li>
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
                    <a href="{{ route('print.listagem-estudantes-escola-imprmir', ['escola_id' => $escola->id ]) }}" class="btn btn-primary float-end mx-2" target="_blink">Imprimir</a>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive">
                    <table id="carregarTabelaMatricula"  style="width: 100%" class="table  table-bordered table-striped table-striped">
                        <thead>
                            <tr>
                                <th>Nº</th>
                                <th>Nome</th>
                                <th>Genero</th>
                                <th>Bilhete</th>
                                <th>Nascimento</th>
                                <th>Status</th>
                                <th>Acções</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if (count($matriculas) != 0)
                            @foreach ($matriculas as $item)
                                <tr>
                                    <td><a href="{{ route('app.informacao-estudante-provincial', Crypt::encrypt($item->id)) }}" class="text-secondary">{{ $item->numero_processo }}</a></td>
                                    <td><a href="{{ route('app.informacao-estudante-provincial', Crypt::encrypt($item->id)) }}" class="text-secondary">{{ $item->nome }} {{ $item->sobre_nome }}</a></td>
                                    <td>{{ $item->genero }}</td>
                                    <td>{{ $item->bilheite }}</td>
                                    <td>{{ $item->nascimento }}</td>
                                    <td>{{ $item->status }}</td>
                                    <td>
                                        <a href="{{ route('app.informacao-estudante-provincial', Crypt::encrypt($item->id)) }}" title="Visualizar Estudante" id="{{ $item->id }}" class="btn btn-primary"><i class="fa fa-eye"></i> Detalhe </a>
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

