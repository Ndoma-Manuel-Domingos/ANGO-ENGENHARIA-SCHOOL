@extends('layouts.professores')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Horários</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('prof.home-profs') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Horários</li>
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
                @foreach ($turmas as $turma)
                <div class="col-md-12 col-12">
                    <div class="card">
                      <div class="card-header">
                        @if (Auth::user()->can('read: turma'))
                        <h6><a href="{{ route('prof.turmas-informacoes', Crypt::encrypt($turma->turma->id)) }}">Turma: {{ $turma->turma->turma }}</a></h6>
                        @else
                        <h6>Turma: {{ $turma->turma->turma }}</h6>
                        @endif
                      </div>
                      <div class="card-body">
                        <table style="width: 100%" class="table table-bordered" id="carregarTabela">
                          <thead>
                              <tr>
                                  <th>Tempo</th>
                                  @foreach ($semanas as $semana)
                                  <th>{{ $semana->nome }}</th>
                                  @endforeach
                              </tr>
                          </thead>
                          <tbody>
                              @foreach ($tempos as $tempo)
                              <tr>
                                  <td>{{ $tempo->nome }}ª T</td>
                                  @foreach ($semanas as $semana)
                                  @php
                                  $horario = App\Models\web\turmas\Horario::with(["disciplina", "turma", "professor", "tempo", "semana"])
                                    ->where("turmas_id", $turma->turma->id)
                                    ->where("professor_id", $professor->id)
                                    ->where("semanas_id", $semana->id)
                                    ->where("tempos_id", $tempo->id)
                                  ->first();
                                  @endphp
                                  @if ($horario)
                                  <td>
                                      <div>
                                          <h4 class="h5">{{ $horario->disciplina->disciplina ?? "" }}</h4>
                                          <p>
                                            <small>{{ $horario->hora_inicio }} até {{ $horario->hora_final }}</small>
                                          </p>
                                      </div>
                                  </td>
                                  @else
                                  <td><strong>...</strong></td>
                                  @endif
                                  @endforeach
                              </tr>
                              @endforeach
                          </tbody>
                      </table>
                      </div>
                    </div>
                </div>
                @endforeach
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
    $(function() {
        $("#carregarTabelaMatricula").DataTable({
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
