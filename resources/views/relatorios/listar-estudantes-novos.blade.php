@extends('layouts.escolas')

@section('content')

  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Lista dos Estudantes Novos</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Estudantes</a></li>
            <li class="breadcrumb-item active">Listar</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content-fluid">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12 col-md-12">
            <div class="card">

                <div class="card-body">
                  @if ($matriculas)
                    <table  style="width: 100%" class="table table-bordered  ">
                      <thead>
                      <tr>
                        <th>Cod</th>
                        <th>Estudante</th>
                        <th>Genero</th>
                        <th>Nascimento</th>
                        <th>Curso</th>
                        <th>Classes</th>
                        <th>Turno</th>
                        <th>Telefone</th>
                      </tr>
                      </thead>
                      <tbody>
                          @foreach ($matriculas as $matricula)
                            @if ($matricula)
                              @php
                                $estudantes = (new App\Models\web\estudantes\Estudante())->find($matricula->estudantes_id);
                                $cursos = (new App\Models\web\cursos\Curso())->find($matricula->cursos_id);
                                $classes = (new App\Models\web\classes\Classe())->find($matricula->classes_id);
                                $turnos = (new App\Models\web\turnos\Turno())->find($matricula->turnos_id);
                              @endphp
                            @endif
                            <tr>
                                <td>{{ $matricula->documento }}</td>
                                <td>{{ $estudantes->nome }} {{ $estudantes->sobre_nome }}</td>
                                <td>{{ $estudantes->genero }}</td>
                                <td>{{ $estudantes->nascimento }}</td>
                                <td>{{ $cursos->curso }}</td>
                                <td>{{ $classes->classes }}</td>
                                <td>{{ $turnos->turno }}</td>
                                <td>{{ $estudantes->telefone_estudante }}</td>
                            </tr>
                          @endforeach
                        
                      </tbody>
                    </table>    
                    
                    <div class="col-12 p-2">
                      <a href="{{ route('listar-estudantes-novos') }}" class="btn btn-primary "><i class="fas fa-print"></i> Imprimir</a>
                    </div>
                  @endif
                
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
