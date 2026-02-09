@extends('layouts.estudantes')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Horários da turma {{ $turma->turma }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route("est.home-estudante") }}">Voltar</a></li>
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
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h6>Listagem das Disciplinas e Horários</h6>
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
                                          ->where("turmas_id", $turma->id)
                                          ->where("semanas_id", $semana->id)
                                          ->where("tempos_id", $tempo->id)
                                        ->first();
                                        @endphp
                                        @if ($horario)
                                        <td>
                                            <div>
                                                <h4 class="h5">{{ $horario->disciplina->disciplina ?? "" }}</h4>
                                                <p>
                                                  <small>{{ $horario->hora_inicio }} até {{ $horario->hora_final }}</small> <br>
                                                  <small>Prof: </small><strong>{{ $horario->professor->nome }} {{ $horario->professor->sobre_nome }}</strong> <br>
                                                  <small>Contacto: </small><strong>{{ $horario->professor->telefone ?? "---" }}</strong>
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
        $("#carregarTabela").DataTable({
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
