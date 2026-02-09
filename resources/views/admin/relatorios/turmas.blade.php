@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Turmas</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.administrativo') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Turmas</li>
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
              <form action="{{ route('web.relatorios-turmas-app') }}" method="GET">
                @csrf
                  <div class="card">
                      <div class="card-body">
                          <div class="row">
                              <div class="col-12 col-md-4">
                                  <label for="ano_lectivos_id" class="form-label">Anos Lectivos</label>
                                  <select name="ano_lectivos_id" id="ano_lectivos_id" class="form-control select2">
                                      <option value="">Todos</option>
                                      @foreach ($anos as $item)
                                      <option value="{{ $item->id }}">{{ $item->ano }}</option>
                                      @endforeach
                                  </select>
                              </div>
                          </div>
                      </div>
                      <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filtar</button>
                      </div>
                  </div>
              </form>
          </div>
      </div>

      <div class="row">
          <div class="col-12 col-md-12">
              <div class="card">
                  <!-- /.card-header -->
                  <div class="card-body">
                      <table id="carregarTabelaTurmas" style="width: 100%" class="table table-bordered  ">
                          <thead>
                              <tr>
                                  <th>Cod</th>
                                  <th>Turma</th>
                                  <th>Status</th>
                                  <th>Classe</th>
                                  <th>Curso</th>
                                  <th>Turno</th>
                                  <th>Sala</th>
                                  <th>Acções</th>
                              </tr>
                          </thead>
                          <tbody>
                              @foreach ($turmas as $turma)
                              <tr>
                                  <td>{{ $turma->id }}</td>
                                  <td>{{ $turma->turma }}</td>
                                  <td>{{ $turma->status }}</td>
                                  <td>{{ $turma->classe->classes }}</td>
                                  <td>{{ $turma->curso->curso }}</td>
                                  <td>{{ $turma->turno->turno }}</td>
                                  <td>{{ $turma->sala->salas }}</td>
                                  <td>
                                      <a href="{{ route('web.lista-estudantes-turma', Crypt::encrypt($turma->id)) }}" title="Ver os estudantes" class="btn btn-success"><i class="fa fa-graduation-cap" aria-hidden="true"></i></a>
                                      <a href="{{ route('web.lista-disciplinas-turma', $turma->id) }}" title="ver as disciplinas" class="btn btn-success"><i class="fa fa-bars" aria-hidden="true"></i></a>
                                  </td>
                              </tr>
                              @endforeach
                          </tbody>
                          <tfoot>
                              {{-- ==================================== --}}
                          </tfoot>
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
    $(function() {
        $("#carregarTabelaTurmas").DataTable({
            language: {
                url: "{{ asset('plugins/datatables/pt_br.json') }}"
            }
            , "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    });

</script>
@endsection
