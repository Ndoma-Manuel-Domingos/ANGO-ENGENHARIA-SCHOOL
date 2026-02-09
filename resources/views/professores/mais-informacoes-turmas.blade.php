@extends('layouts.professores')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Turmas na escola: <span class="text-secondary">{{ $shcool->nome }}</span> </h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('prof.home-profs') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Turmas</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">

      <div class="row">
        <div class="col-12 col-md-12">
          <div class="callout callout-info">
            <h5><i class="fas fa-info"></i> Mais informações sobre do Professor</h5>
          </div>
        </div>
      </div>

      <div class="row">

        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="card card-primary card-outline">
            <div class="card-body box-profile">
              <div class="text-center">
                @if (empty($professor->image))
                <img class="profile-user-img img-fluid img-circle" src="{{ asset('assets/images/user.png') }}"
                  alt="User profile picture">
                @else
                <img class="profile-user-img img-fluid img-circle" src="{{ asset("
                  assets/images/recursosHumanos/$professor->image") }}" alt="User profile picture">
                @endif
              </div>

              <h3 class="profile-username text-center">{{ $professor->nome }} {{ $professor->sobre_nome }}</h3>

              <p class="text-muted text-center">{{ $professor->academico->curso }}</p>

              <ul class="list-group list-group-unbordered mb-3">
                <li class="list-group-item">
                  <b>Nascimento</b> <a class="float-right">{{ $professor->nascimento }}</a>
                </li>
                <li class="list-group-item">
                  <b>Genero</b> <a class="float-right">{{ $professor->genero }}</a>
                </li>

                <li class="list-group-item">
                  <b>Total Escolas</b> <a class="float-right" href="">{{ count($escolas) }}</a>
                </li>
              </ul>

            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>

        <!-- /.col -->
        <div class="col-md-9">
    
          <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link" href="#dados_pessoas_mae" data-toggle="tab">Turmas</a>
                    </li>
                </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
              <div class="tab-content">
             
                <div class="active tab-pane" id="dados_pessoas_mae">
                    @if ($turmas)
                        @foreach ($turmas as $item)
                            <div class="col-12 py-0 px-0">
                                <h1 class="bg-dark p-1 fs-5">Turma: {{ $item->turma }}
                                  @if ($item->categoria_escola($item->shcools_id) == "Privado")
                                    @if ($item->tempo_edicao >= date("Y-m-d"))
                                      @if (Auth::user()->can('read: nota'))
                                      <a class="float-right" href="{{ route('prof.informacao-professores-lancamento-nota', ['professor_id' => Crypt::encrypt($professor->id), 'turma_id' => Crypt::encrypt($item->idTurma)]) }}"><i class="fa fa-edit"></i> Notas</a> 
                                      @endif
                                    @endif
                                  @else 
                                    @if ($item->tempo_lancamento_notas($item->ano_lectivos_id, $item->shcools_id))
                                      @if (Auth::user()->can('read: nota'))
                                      <a class="float-right" href="{{ route('prof.informacao-professores-lancamento-nota', ['professor_id' => Crypt::encrypt($professor->id), 'turma_id' => Crypt::encrypt($item->idTurma)]) }}"><i class="fa fa-edit"></i> Notas</a> 
                                      @endif
                                    @endif
                                  @endif
                                </h1>
                                
                                <ul class="py-0 px-0">
                                    <li>Disciplina: <span class="text-primary fs-5">{{ $item->disciplina }}</span></li>
                                    <li>Cargo na Turma: <span class="text-primary fs-5">{{ $item->cargo_turma }}</span></li>
                                </ul>
                                @php
                                    $horario = (new App\Models\web\turmas\Horario)::where([
                                        ['turmas_id', '=', $item->idTurma],
                                        ['disciplinas_id','=', $item->idDis],
                                    ])
                                    ->get();
                                @endphp
                                @if ($horario)
                                    <table  style="width: 100%" class="table table-bordered  ">
                                        <thead>
                                            <tr>
                                                <th>Dias Semanas</th>
                                                <th>Tempos</th>
                                                <th>Hora Entrada</th>
                                                <th>Hora Saida</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @php $totalTempo = 0; @endphp
                                            @foreach ($horario as $items)
                                                @php $totalTempo++; @endphp
                                                <tr>
                                                    <td>{{ $items->semana->nome }}</td>
                                                    <td>{{ $items->tempo->nome }} º</td>
                                                    <td>{{ $items->hora_inicio }} </td>
                                                    <td>{{ $items->hora_final }} </td>
                                                </tr>
                                            @endforeach

                                            <tr>
                                                <td colspan="4"> Total de Tempos Semanal nesta Turma: {{ $totalTempo }}</td>
                                            </tr>
                                        </tbody>
                                    
                                    </table>
                                @endif
                            </div>   
                        @endforeach
                    @endif
                </div>

              </div>
              <!-- /.tab-content -->
            </div><!-- /.card-body -->
          </div>

          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>

<!-- /.content-wrapper -->
<!-- /.content -->
@endsection