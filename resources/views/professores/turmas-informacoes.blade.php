@extends('layouts.professores')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark"> Mais informações  da Turmas {{ $turma->turma }}</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route("prof.turmas") }}">Voltar</a></li>
            <li class="breadcrumb-item active">Turmas</li>
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

        <div class="row mt-3">
            <div class="col-12 col-md-7">
                <div class="card">
                    <div class="card-body">
                        <table id="carregarTabelaMatricula" style="width: 100%" class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Genero</th>
                                    <th>Idade</th>
                                    <th>Contacto Pai</th>
                                    <th>Contacto Mãe</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($estudantes as $estudante)
                                <tr>
                                    @if (Auth::user()->can('read: estudante'))
                                    <td class="text-uppercase"><a href="{{ route('prof.estudantes-informacoes', Crypt::encrypt($estudante->estudante->id)) }}">{{ $estudante->estudante->nome }} {{ $estudante->estudante->sobre_nome }}</a></td>
                                    @else
                                    <td class="text-uppercase">{{ $estudante->estudante->nome }} {{ $estudante->estudante->sobre_nome }}</td>
                                    @endif
                                    <td>{{ $estudante->estudante->genero }}</td>
                                    <td>{{ $estudante->estudante->idade($estudante->estudante->nascimento) ?? '--- --- ---' }}</td>
                                    <td>{{ $estudante->estudante->telefone_pai ?? '--- --- ---' }}</td>
                                    <td>{{ $estudante->estudante->telefone_mae ?? '--- --- ---' }}</td>
                                </tr>
                                @endforeach
                            </tbody>

                        </table>                    
                    </div>

                    <div class="card-footer">
                        Listagem das turmas
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-5">
                    
                <div class="card">
                    <div class="card-header">
                    @if ($turma->categoria_escola($turma->shcools_id) == 'Privado')
                        @if ($tempo_edicao->tempo_edicao >= date("Y-m-d"))
                            <p class="text-success">Periodo de lançamento de notas activada com successo, ou seja já podes fazer o lançamento de notas nesta turma de momento!</p>
                            @if (Auth::user()->can('create: nota'))
                            <a class="float-right" href="{{ route('prof.informacao-professores-lancamento-nota', ['professor_id' =>  Crypt::encrypt($professor->id), 'turma_id' =>  Crypt::encrypt($turma->id)]) }}"><i class="fa fa-edit"></i> Fazer lançamento de Notas</a> 
                            @endif
                        @else 
                            <p class="text-warning">Periodo de lançamento de notas desactivadas, ou seja não podes fazer o lançamento de notas nesta turma de momento!</p>
                        @endif
                    @else
                        @if ($lancamento)
                            <p class="text-success">Periodo de lançamento de notas activada com successo, ou seja já podes fazer o lançamento de notas nesta turma de momento!</p>
                            @if (Auth::user()->can('create: nota'))
                            <a class="float-right" href="{{ route('prof.informacao-professores-lancamento-nota', ['professor_id' =>  Crypt::encrypt($professor->id), 'turma_id' =>  Crypt::encrypt($turma->id)]) }}"><i class="fa fa-edit"></i> Fazer lançamento de Notas</a> 
                            @endif
                        @else
                            <p class="text-warning">Periodo de lançamento de notas desactivadas, ou seja não podes fazer o lançamento de notas nesta turma de momento!</p>
                        @endif
                    @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        Listagem das Disciplinas e Horários
                    </div>
                    <div class="card-body">
                        <table id="carregarTabelaMatricula1"  style="width: 100%" class="table table-bordered">
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
                                          ->where("professor_id", $professor->id)
                                          ->where("turmas_id", $turma->id)
                                          ->where("semanas_id", $semana->id)
                                          ->where("tempos_id", $tempo->id)
                                        ->first();
                                        @endphp
                                        @if ($horario)
                                        <td>
                                            <div>
                                                <h4 class="h6">{{ $horario->disciplina->disciplina ?? "" }}</h4>
                                                <p><small>{{ $horario->hora_inicio }} até {{ $horario->hora_final }}</small></p>
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
  
  <script>
    $(function () {
      $("#carregarTabelaMatricula1").DataTable({
        language: {
            url: "{{ asset('plugins/datatables/pt_br.json') }}"
        },
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    });
  </script>
@endsection