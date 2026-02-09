@extends('layouts.escolas')

@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Detalhe Ano Lectivo</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Voltar</a></li>
            <li class="breadcrumb-item active">Ano lectivo</li>
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
            <div class="callout callout-info">
                <h5><i class="fas fa-info"></i> Cadastrar, editar, eliminar, visualizar e configurar o ano lectivos, pesquisar avançadas, para melhor navegação.</h5>
            </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12 col-md-12">
            <div class="card">
              <div class="card-body">
                <table  style="width: 100%" class="table projects  ">
                    <thead class="bg-light">
                      <th>Ano Lectivo</th>
                      <th>Data Incio</th>
                      <th>Data Final</th>
                      <th>Estado</th>
                    </thead>
                    <tbody class="table_ano">
                        <tr>
                            <td>{{ $anoLectivo->ano }}</td>
                            <td>{{ $anoLectivo->inicio }}</td>
                            <td>{{ $anoLectivo->final }}</td>
                            <td>{{ $anoLectivo->status }}</td>
                        </tr>
                    </tbody>
                </table>
              </div> 
            </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->

        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <table  style="width: 100%" class="table projects  ">
                        <thead class="bg-light">
                          <th>Cursos</th>
                          <th>Tipo</th>
                          <th>Área de Formação</th>
                          <th>Abreviação</th>
                        </thead>
                        <tbody class="table_curso">
                            @foreach ($cursos as $item)
                                <tr>
                                    <td>{{ $item->curso }}</td>
                                    <td>{{ $item->tipo }}</td>
                                    <td>{{ $item->area_formacao }}</td>
                                    <td>{{ $item->abreviacao }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <table  style="width: 100%" class="table projects  ">
                        <thead class="bg-light">
                            <th>##</th>
                            <th>Trimestre</th>
                            <th>Inicio</th>
                            <th>Final</th>
                            <th>Estado</th>
                        </thead>
                        <tbody class="table_trimestre">
                            @foreach ($trimestres as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->trimestre }} </td>
                                    <td>{{ $item->inicio }}</td>
                                    <td>{{ $item->final }}</td>
                                    <td>{{ $item->status }}</td>
                                </tr> 
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-4">
            <div class="card">
                <div class="card-body">
                    <table  style="width: 100%" class="table projects  ">
                        <thead class="bg-light">
                          <th colspan="2">Classes</th>
                          <th colspan="2">Categoria</th>
                        </thead>
                        <tbody class="table_classe">
                            @foreach ($classes as $item)
                                <tr>
                                    <td colspan="2">{{ $item->classes }}</td>
                                    <td colspan="2">{{ $item->categoria }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-4">
            <div class="card">
                <div class="card-body">
                    <table  style="width: 100%" class="table projects  ">
                        <thead class="bg-light">
                            <th colspan="2">Sala</th>
                            <th colspan="2">Categoria</th>
                        </thead>
                        <tbody class="table_turnos">
                            @foreach ($salas as $item)
                                <tr>
                                    <td colspan="2">{{ $item->tipo }}</td>
                                    <td colspan="2">{{ $item->salas }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-4">
            <div class="card">
                <div class="card-body">
                    <table  style="width: 100%" class="table projects  ">
                        <thead class="bg-light">
                            <th colspan="2">Turno</th>
                            <th colspan="2">Categoria</th>
                        </thead>
                        <tbody class="table_salas">
                            @foreach ($turnos as $item)
                                <tr>
                                    <td colspan="2">{{ $item->turno }}</td>
                                    <td colspan="2">{{ $item->turno }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
@endsection
