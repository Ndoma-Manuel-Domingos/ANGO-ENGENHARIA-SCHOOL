@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Relatórios Estudantes</h1>
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
                <form method="get" action="{{ route('web.relatorios-estudantes') }}">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">

                                <div class="col-12 col-md-3">
                                    <label>Curso</label>
                                    <select class="form-control select2" name="cursos_id">
                                        <option value="">Todas</option>
                                        @foreach ($cursos_list as $item)
                                        <option value="{{ $item->curso->id }}" {{ $requests['cursos_id'] == $item->curso->id ? 'selected' : ''}}>{{ $item->curso->curso }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label>Classes</label>
                                    <select class="form-control select2" name="classes_id">
                                        <option value="">Todas</option>
                                        @foreach ($classes_list as $item)
                                        <option value="{{ $item->classe->id }}" {{ $requests['classes_id'] == $item->classe->id ? 'selected' : ''}}>{{ $item->classe->classes }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label>Turnos</label>
                                    <select class="form-control select2" name="turnos_id">
                                        <option value="">Todas</option>
                                        @foreach ($turnos_list as $item)
                                        <option value="{{ $item->turno->id }}" {{ $requests['turnos_id'] == $item->turno->id ? 'selected' : ''}}>{{ $item->turno->turno }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label>Generos</label>
                                    <select class="form-control select2" name="genero">
                                        <option value="">Todos</option>
                                        <option value="Masculino" {{ $requests['genero'] == "Masculino" ? 'selected' : ''}}>Masculino</option>
                                        <option value="Femenino" {{ $requests['genero'] == "Femenino" ? 'selected' : ''}}>Femenino</option>
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
                      <a href="{{ route('estudantes-imprmir', ['genero' => $requests['genero'], 'cursos_id' => $requests['cursos_id'], 'classes_id' => $requests['classes_id'], 'turnos_id' => $requests['turnos_id']]) }}" class="float-end btn-danger btn mx-1" target="_blink"><i class="fas fa-file-pdf"></i> Imprimir</a>
                      <a href="{{ route('estudantes-imprmir-excel', ['genero' => $requests['genero'], 'cursos_id' => $requests['cursos_id'], 'classes_id' => $requests['classes_id'], 'turnos_id' => $requests['turnos_id']]) }}" class="float-end btn-success btn mx-1" target="_blink"><i class="fas fa-file-excel"></i> Imprimir</a>
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
                                    <th>Classe</th>
                                    <th>Curso</th>
                                    <th>Turno</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($estudantes as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->nome }} {{ $item->sobre_nome }}</td>
                                    <td>{{ $item->idade($item->nascimento) }}</td>
                                    <td>{{ $item->genero }}</td>
                                    <td>{{ $item->nascimento }}</td>
                                    <td>{{ $item->classes }}</td>
                                    <td>{{ $item->curso }}</td>
                                    <td>{{ $item->turno }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                {{-- ==================================== --}}
                            </tfoot>
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
