@extends('layouts.admin')

@section('content')

  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-9">
          <h1 class="m-0 text-dark">Professores da(o) <span class="text-secondary">{{ $escola->nome }}</span> </h1>
        </div><!-- /.col -->
        <div class="col-sm-3">
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
                <h5><i class="fas fa-info"></i>Listagem dos Professores </h5>
            </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('print.listagem-professores-escola-imprmir', ['escola_id' => $escola->id ]) }}" class="btn btn-primary float-end mx-2" target="_blink">Imprimir</a>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive">
                    <table id="carregarTabelaMatricula"  style="width: 100%" class="table  table-bordered table-striped table-striped">
                        <thead>
                            <tr>
                                <th>Nº</th>
                                <th>Nome</th>
                                <th>Idade</th>
                                <th>Nascimento</th>
                                <th>Bilhete</th>
                                <th>Genero</th>
                                <th>Status</th>
                                <th style="width: 10%">Acções</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if (count($professores) != 0)
                            @foreach ($professores as $item)
                                <tr>
                                    <td>
                                      @if (Auth::user()->can('read: professores'))
                                        <a href="{{ route('app.informacao-professores', $item->funcionario->id) }}" class="text-secondary">{{ $item->funcionario->id }}</a>
                                      @else
                                        {{ $item->funcionario->id }}
                                      @endif
                                    </td>
                                    
                                    <td>
                                      @if (Auth::user()->can('read: professores'))
                                        <a href="{{ route('app.informacao-professores', $item->funcionario->id) }}" class="text-secondary">{{ $item->funcionario->nome }} {{ $item->funcionario->sobre_nome }}</a>
                                      @else
                                        {{ $item->funcionario->nome }} {{ $item->funcionario->sobre_nome }}
                                      @endif
                                    </td>
                                    
                                    <td>{{ $item->funcionario->idade($item->funcionario->nascimento) }}</td>
                                    <td>{{ $item->funcionario->nascimento }}</td>
                                    <td>{{ $item->funcionario->bilheite }}</td>
                                    <td>{{ $item->funcionario->genero }}</td>
                                    <td>{{ $item->funcionario->status }}</td>
                                    <td>
                                      @if (Auth::user()->can('read: professores'))
                                        <a href="{{ route('app.informacao-professores', $item->funcionario->id) }}" title="Visualizar Professor" id="{{ $item->funcionario->id }}" class="btn btn-primary"><i class="fa fa-eye"></i> Visualizar </a>
                                      @endif
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

