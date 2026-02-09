@extends('layouts.professores')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Lista dos meus estudantes</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('prof.home-profs') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Estudantes</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      @if(session()->has('message'))
      <div class="alert alert-success">
        {{ session()->get('message') }}
      </div>
      @endif

      <div class="row">
        <div class="col-12 col-md-12">
          <form action="{{ route('prof.estudantes') }}" method="get">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="form-group col-6">
                    <label for="" class="form-label">Escola</label>
                    <select type="text" class="form-control select2" placeholder="Escola" name="escola">
                      <option value="">TODAS</option>
                      @foreach ($escolas as $item)
                        <option value="{{ $item->id }}" {{ $item->id == $requests['escola'] ? 'selected' : '' }}>{{ $item->nome }}</option>  
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <button type="submit" class="btn btn-primary">Filtrar</button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <div class="row mt-3">
        <div class="col-12 col-md-12">
          <div class="card">
            <div class="card-body">
              <table id="carregarTabelaMatricula"
                 style="width: 100%"  style="width: 100%" class="table table-bordered  ">
                <thead>
                  <tr>
                    <th style="width: 30px">Nº</th>
                    <th style="width: 300px">Nome</th>
                    <th>Genero</th>
                    <th>Classe</th>
                    <th>Curso</th>
                    <th>Turno</th>
                    <th>Escola</th>
                  </tr>
                </thead>

                <tbody>
                  @foreach ($estudantes as $key => $estudante)
                  <tr>
                    <td> {{ $key + 1 }}</td>
                    @if (Auth::user()->can('read: estudante'))
                    <td><a href="{{ route('prof.estudantes-informacoes', Crypt::encrypt($estudante->id)) }}" class="text-uppercase">{{ $estudante->nome }} {{ $estudante->sobre_nome }}</a> </td>
                    @else
                    <td>{{ $estudante->nome }} {{ $estudante->sobre_nome }}</td>
                    @endif
                    <td> {{ $estudante->genero }}</td>
                    <td> {{ $estudante->matricula->classe->classes }}</td>
                    <td> {{ $estudante->matricula->curso->curso }}</td>
                    <td> {{ $estudante->matricula->turno->turno }}</td>
                    <td> {{ $estudante->escola->nome }} </td>
                  </tr>
                  @endforeach
                </tbody>

              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>

<!-- /.content-wrapper -->
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