@extends('layouts.admin')

@section('content')

  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Estudantes</h1>
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
                <h5><i class="fas fa-info"></i> Listagem geral dos estudantes por províncias.</h5>
            </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12 col-md-12">
            <form action="{{ route('app.estatisticas-estudantes-geral') }}" method="get">
                @csrf
                <div class="card">
                    <div class="card-body row">

                        <div class="col-12 col-md-3">
                          <label for="" class="form-label">Ano Lectivo</label>
                          <select name="ano_lectivos_id" class="form-control">
                              <option value="">Todos</option>
                              @foreach ($anos_lectivos as $ano)
                                  <option value="{{ $ano->id }}" {{ $requests['ano_lectivos_id'] == $ano->id ? 'selected' : ''  }}>{{ $ano->ano }}</option>
                              @endforeach
                          </select>
                        </div>


                        <div class="col-12 col-md-3">
                          <label for="" class="form-label">Províncias</label>
                          <select name="provincia_id" class="form-control">
                              <option value="">Todos</option>
                              @foreach ($provincias as $item)
                                  <option value="{{ $item->id }}" {{ $requests['provincia_id'] == $item->id ? 'selected' : ''  }}>{{ $item->nome }}</option>
                              @endforeach
                          </select>
                        </div>

                        <div class="col-12 col-md-3">
                          <label for="" class="form-label">Generos</label>
                          <select name="genero" class="form-control">
                              <option value="">Todos</option>
                              <option value="Masculino" {{ $requests['genero'] == 'Masculino' ? 'selected' : ''  }}>Masculino</option>
                              <option value="Femenino" {{ $requests['genero'] == 'Femenino' ? 'selected' : ''  }}>Femenino</option>
                          </select>
                        </div>

                        <div class="col-12 col-md-3">
                            <label for="" class="form-label">Classes</label>
                            <select name="classes_id" class="form-control">
                                <option value="">Todos</option>
                                @foreach ($classes as $item)
                                    <option value="{{ $item->id }}" {{ $requests['classes_id'] == $item->id ? 'selected' : ''  }}>{{ $item->classes }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-3">
                            <label for="" class="form-label">Cursos</label>
                            <select name="cursos_id" class="form-control">
                                <option value="">Todos</option>
                                @foreach ($cursos as $item)
                                    <option value="{{ $item->id }}" {{ $requests['cursos_id'] == $item->id ? 'selected' : ''  }}>{{ $item->curso }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-3">
                            <label for="" class="form-label">Turnos</label>
                            <select name="turnos_id" class="form-control">
                                <option value="">Todos</option>
                                @foreach ($turnos as $item)
                                    <option value="{{ $item->id }}" {{ $requests['turnos_id'] == $item->id ? 'selected' : ''  }}>{{ $item->turno }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-3">
                            <label for="" class="form-label">Estados</label>
                            <select name="estado" class="form-control">
                                <option value="">Todos</option>
                                <option value="confirmado" {{ $requests['estado'] == 'confirmado' ? 'selected' : ''  }}>Admitidos</option>
                                <option value="nao_confirmado" {{ $requests['estado'] == 'nao_confirmado' ? 'selected' : ''  }}>Não Admitidos</option>
                            </select>
                          </div>

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


      <div class="row">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="float-start">Total registros ({{ count($estudantes) }})</h6>
                    <a href="{{ route('print.estatistica-estudantes-imprmir', ['estado' => $requests['estado'], 'classes_id' => $requests['classes_id'], 'turnos_id' => $requests['turnos_id'], 'cursos_id' => $requests['cursos_id'], 'ano_lectivos_id' => $requests['ano_lectivos_id'], 'provincia_id' => $requests['provincia_id'], 'genero' => $requests['genero']]) }}" class="btn btn-primary float-end mx-2" target="_blink">Imprimir</a>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive">
                    <table id="carregarTabelaMatricula"  style="width: 100%" class="table  table-bordered table-striped table-striped">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Bilhete</th>
                                <th>Genero</th>
                                <th>Classe</th>
                                <th>Curso</th>
                                <th>Turno</th>
                                <th>Estado</th>
                                <th>Status</th>
                                <th>Província</th>
                                <th>Ano Lectivo</th>
                                <th>Acções</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if (count($estudantes) != 0)
                            @foreach ($estudantes as $item)
                                <tr>
                                    <td>
                                      @if (Auth::user()->can('read: estudante'))
                                        <a href="{{ route('app.informacao-estudante', $item->id) }}" class="text-secondary">{{ $item->nome }} {{ $item->sobre_nome }}</a>
                                      @else  
                                        {{ $item->nome }} {{ $item->sobre_nome }}
                                      @endif
                                    </td>
                                    <td>{{ $item->bilheite }}</td>
                                    <td>{{ $item->genero }}</td>
                                    <td>{{ $item->classes }}</td>
                                    <td>{{ $item->curso }}</td>
                                    <td>{{ $item->turno }}</td>
                                    @if ($item->status_matricula == 'confirmado')
                                        <td class="text-success">Admitido</td>
                                    @else
                                        <td class="text-danger">Não Admitido</td>
                                    @endif
                                    <td>{{ $item->status }}</td>
                                    <td>{{ $item->provincia }}</td>
                                    <td>{{ $item->ano }}</td>
                                    <td>
                                      @if (Auth::user()->can('read: estudante'))
                                        <a href="{{ route('app.informacao-estudante', $item->id) }}" title="Visualizar Estudante" id="{{ $item->id }}" class="btn btn-primary"><i class="fa fa-eye"></i></a>
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

