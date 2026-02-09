@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Listagem de estudantes com valores da mensalidade</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('financeiros.financeiro-novos-pagamentos') }}">Painel Financeiro</a></li>
                    <li class="breadcrumb-item active">Financeiro</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">

        <div class="row">

            <div class="col-12 col-md-12">
                <div class="card">
                    <form action="{{ route('financeiros.estudantes') }}" method="get">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-2 col-12">
                                    <label for="status">Estados Estudante</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Todos</option>
                                        <option value="confirmado" {{ $filtros['status'] == 'confirmado' ? 'selected' : '' }}>Confirmados
                                        </option>
                                        <option value="falecido" {{ $filtros['status'] == 'falecido' ? 'selected' : '' }}>Falecidos</option>
                                        <option value="desistente" {{ $filtros['status'] == 'desistente' ? 'selected' : '' }}>Desisentes
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group col-md-2 col-12">
                                    <label for="finalista">Estudantes</label>
                                    <select name="finalista" id="finalista" class="form-control">
                                        <option value="">Todos</option>
                                        <option value="Y" {{ $filtros['finalista'] == 'Y' ? 'selected' : '' }}>Finalistas </option>
                                        <option value="N" {{ $filtros['finalista'] == 'N' ? 'selected' : '' }}>Não Finalistas</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-2 col-12">
                                    <label for="cursos_id">Cursos</label>
                                    <select name="cursos_id" id="cursos_id" class="form-control">
                                        <option value="">Todos</option>
                                        @foreach ($cursos as $item)
                                        <option value="{{ $item->curso->id }}" {{ $filtros['cursos_id'] == $item->curso->id ? 'selected' : '' }}>
                                            {{ $item->curso->curso }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-2 col-12">
                                    <label for="classes_id">Classes</label>
                                    <select name="classes_id" id="classes_id" class="form-control">
                                        <option value="">Todos</option>
                                        @foreach ($classes as $item)
                                        <option value="{{ $item->classe->id }}" {{ $filtros['classes_id'] == $item->classe->id ? 'selected' : '' }}>
                                            {{ $item->classe->classes }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-2 col-12">
                                    <label for="turnos_id">Turnos</label>
                                    <select name="turnos_id" id="turnos_id" class="form-control">
                                        <option value="">Todos</option>
                                        @foreach ($turnos as $item)
                                        <option value="{{ $item->turno->id }}" {{ $filtros['turnos_id'] == $item->turno->id ? 'selected' : '' }}>
                                            {{ $item->turno->turno }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-2 col-12">
                                    <label for="genero">Generos</label>
                                    <select name="genero" id="genero" class="form-control">
                                        <option value="">Todos</option>
                                        <option value="Masculino" {{ $filtros['genero'] == 'Masculino' ? 'selected' : '' }}>Masculino
                                        </option>
                                        <option value="Femenino" {{ $filtros['genero'] == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                    </select>
                                </div>

                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-12 col-md-12">

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Listagem dos Estudantes </h3>
                        <a href="{{ route('estudantes-imprmir', ['finalista' => $filtros['finalista'], 'genero' => $filtros['genero'], 'status' => $filtros['status'], 'curso_id' => $filtros['cursos_id'], 'classes_id' => $filtros['classes_id'], 'turnos_id' => $filtros['turnos_id']]) }}" class="float-end btn-danger btn mx-1" target="_blink"><i class="fas fa-file-pdf"></i> Imprimir</a>
                        <a href="{{ route('estudantes-imprmir-excel', ['finalista' => $filtros['finalista'], 'genero' => $filtros['genero'], 'status' => $filtros['status'], 'curso_id' => $filtros['cursos_id'], 'classes_id' => $filtros['classes_id'], 'turnos_id' => $filtros['turnos_id']]) }}" class="float-end btn-success btn mx-1" target="_blink"><i class="fas fa-file-excel"></i> Imprimir</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive">
                        <table id="carregarTabelaEstudantes" style="width: 100%" class="table  table-bordered table-striped  ">
                            <thead>
                                <tr>
                                    <th>Nº</th>
                                    <th>Nome</th>
                                    <th>Bilhete</th>
                                    <th>Genero</th>
                                    <th>Curso</th>
                                    <th>Classe</th>
                                    <th>Turno</th>
                                    <th>Tipo Estudante</th>
                                    <th>Status</th>
                                    <th>Valor da Propina</th>
                                    <th style="width: 100px">Acções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($matriculas) != 0)
                                @foreach ($matriculas as $item)
                                <tr>
                                    <td>
                                        @if (Auth::user()->can('read: estudante'))
                                        <a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}">{{ $item->estudante->numero_processo }}</a>
                                        @else
                                        {{ $item->estudante->numero_processo }}
                                        @endif
                                    </td>
                                    <td>

                                        @if (Auth::user()->can('read: estudante'))
                                        <a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}">{{ $item->estudante->nome }}
                                            {{ $item->estudante->sobre_nome }}</a>
                                        @else
                                        {{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}
                                        @endif
                                    </td>
                                    <td>
                                        @if (Auth::user()->can('read: estudante'))
                                        <a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}">{{ $item->estudante->bilheite }}</a>
                                        @else
                                        {{ $item->estudante->bilheite }}
                                        @endif
                                    </td>

                                    <td>
                                        {{ $item->estudante->genero }}
                                    </td>

                                    </td>
                                    <td>{{ $item->curso->curso }}</td>
                                    <td>{{ $item->classe->classes }}</td>
                                    <td>{{ $item->turno->turno }}</td>
                                    <td>
                                        @if ($item->estudante->bolseiro($item->estudante->id))
                                        Bolseiro
                                        @else
                                        Normal
                                        @endif
                                    </td>

                                    @if ($item->status_matricula == 'confirmado')
                                    <td class="text-success">Confirmado</td>
                                    @endif

                                    @if ($item->status_matricula == 'desistente')
                                    <td class="text-warning">Desistente</td>
                                    @endif

                                    @if ($item->status_matricula == 'falecido')
                                    <td class="text-danger">Falecido</td>
                                    @endif

                                    @if ($item->status_matricula == 'nao_confirmado')
                                    <td class="text-danger">Não Confirmado</td>
                                    @endif

                                    @if ($item->status_matricula == 'inactivo')
                                    <td class="text-danger">Inactivo</td>
                                    @endif

                                    @if ($item->status_matricula == 'rejeitado')
                                    <td class="text-warning">Rejeitada</td>
                                    @endif
                                    <td>
                                        {{ number_format($item->estudante->valor_propinas($item->classes_id, $item->cursos_id, $item->ano_lectivos_id), 2, ',', '.')  }}
                                    </td>

                                    <td>
                                        @if (Auth::user()->can('read: pagamento'))
                                        <a href="{{ route('web.sistuacao-financeiro', Crypt::encrypt($item->estudante->id)) }}" title="Cartão Financeiro do estudante" class="btn btn-primary">Cartão Financ.</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>

                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->

@endsection


@section('scripts')
<script>
    $(function() {
        $("#carregarTabelaEstudantes").DataTable({
            language: {
                url: "{{ asset('plugins/datatables/pt_br.json') }}"
            }
            , "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#carregarTabelaEstudantes_wrapper .col-md-6:eq(0)');

    });

</script>
@endsection
