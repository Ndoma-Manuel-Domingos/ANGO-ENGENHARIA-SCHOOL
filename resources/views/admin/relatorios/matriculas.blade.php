@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Relatórios Matriculas</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.administrativo') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Painel Administrativo</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<div class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12 col-md-12">
                <form method="get" action="{{ route('web.relatorios-matriculas') }}">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-3">
                                    <label>Curso</label>
                                    <select class="form-control" name="cursos_id">
                                        <option value="">Todas</option>
                                        @foreach ($cursos_list as $item)
                                        <option value="{{ $item->curso->id }}" {{ $requests['cursos_id'] == $item->curso->id ? 'selected' : ''}}>{{ $item->curso->curso }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label>Classes</label>
                                    <select class="form-control" name="classes_id">
                                        <option value="">Todas</option>
                                        @foreach ($classes_list as $item)
                                        <option value="{{ $item->classe->id }}" {{ $requests['classes_id'] == $item->classe->id ? 'selected' : ''}}>{{ $item->classe->classes }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label>Turnos</label>
                                    <select class="form-control" name="turnos_id">
                                        <option value="">Todas</option>
                                        @foreach ($turnos_list as $item)
                                        <option value="{{ $item->turno->id }}" {{ $requests['turnos_id'] == $item->turno->id ? 'selected' : ''}}>{{ $item->turno->turno }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label>Estado Matricula</label>
                                    <select class="form-control" name="status">
                                        <option value="">Todos</option>
                                        <option value="confirmado" {{ $requests['status'] == "confirmado" ? 'selected' : ''}}>Confirmado</option>
                                        <option value="nao_confirmado" {{ $requests['status'] == "nao_confirmado" ? 'selected' : ''}}>Não Confirmado</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-primary"> Filtrar</button>
                            <span class="float-right">Total Resultados: {{ count($estudantes) }}</span>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                      <a href="{{ route('web.relatorios-matriculas-excel', ['cursos_id' => $requests['cursos_id'] ?? "", 'classes_id' => $requests['classes_id'] ?? "", 'turnos_id' => $requests['turnos_id'] ?? "", 'status' => $requests['status'] ?? ""]) }}" target="_blink" class="btn btn-success float-end mx-2"><i class="fas fa-file-excel"></i> Imprimir Excel</a>
                      <a href="{{ route('estudantes-matriculas-imprmir', ['status' => $requests['status'], 'cursos_id'=>$requests['cursos_id'], 'classes_id' => $requests['classes_id'], 'turnos_id' => $requests['turnos_id'] ]) }}" class="btn-danger btn float-end mx-2" target="_blink"><i class="fas fa-file-pdf"></i> Imprimir</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="carregarTabelaTurmas" style="width: 100%" class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th>Cod</th>
                                    <th>Nome</th>
                                    <th>Idade</th>
                                    <th>Genero</th>
                                    <th>Nascimento</th>
                                    <th>Estado Candidatura</th>
                                    <th>Classe</th>
                                    <th>Curso</th>
                                    <th>Turno</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($estudantes as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}</td>
                                    <td>{{ $item->estudante->idade($item->estudante->nascimento) }}</td>
                                    <td>{{ $item->estudante->genero }}</td>
                                    <td>{{ $item->estudante->nascimento }}</td>
                                    @if ($item->status_matricula == "confirmado")
                                    <td class="text-success">Confirmado</td>
                                    @else
                                    <td class="text-danger">Não Confirmado</td>
                                    @endif
                                    <td>{{ $item->classe->classes }}</td>
                                    <td>{{ $item->curso->curso }}</td>
                                    <td>{{ $item->turno->turno }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                    </div>
                </div>
            </div>
        </div>

    </div><!-- /.container-fluid -->
</div>
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
