@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Inscrições Aceites</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.painel-informativo-administrativo') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Inscrições</li>
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
            <form action="{{ route('web.estudantes-inscricao-aceites') }}" method="get">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-2 col-12">
                                <label for="ano_lectivo_id">Anos Lectivos</label>
                                <select name="ano_lectivo_id" class="form-control select2">
                                    <option value="">Selecione o ano</option>
                                    @foreach ($anolectivos as $item)
                                    <option value="{{ $item->id }}">{{ $item->ano }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-2 col-12">
                                <label for="cursos_id">Cursos</label>
                                <select name="cursos_id" id="cursos_id" class="form-control select2">
                                    <option value="">Todos</option>
                                    @foreach ($cursos as $item)
                                    <option value="{{ $item->curso->id }}" {{ $filtros['cursos_id'] == $item->curso->id ? 'selected' : '' }}>{{ $item->curso->curso }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-2 col-12">
                                <label for="classes_id">Classes</label>
                                <select name="classes_id" id="classes_id" class="form-control select2">
                                    <option value="">Todos</option>
                                    @foreach ($classes as $item)
                                    <option value="{{ $item->classe->id }}" {{ $filtros['classes_id'] == $item->classe->id ? 'selected' : '' }}>{{ $item->classe->classes }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-2 col-12">
                                <label for="turnos_id">Turnos</label>
                                <select name="turnos_id" id="turnos_id" class="form-control select2">
                                    <option value="">Todos</option>
                                    @foreach ($turnos as $item)
                                    <option value="{{ $item->turno->id }}" {{ $filtros['turnos_id'] == $item->turno->id ? 'selected' : '' }}>{{ $item->turno->turno }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-2 col-12">
                                <label for="media">Média</label>
                                <select name="media" id="media" class="form-control select2">
                                    <option value="">Todos</option>
                                    @for ($i = 14; $i <= 20; $i++) <option value="{{ $i }}" {{ $filtros['media'] == $i ? 'selected' : '' }}>{{ $i }}</option> @endfor
                                </select>
                            </div>

                            <div class="form-group col-md-2 col-12">
                                <label for="idade">Idades</label>
                                <select name="idade" id="idade" class="form-control select2">
                                    <option value="">Todos</option>
                                    @for ($i = 14; $i <= 35; $i++) <option value="{{ $i }}" {{ $filtros['idade'] == $i ? 'selected' : '' }}>{{ $i }}</option> @endfor
                                </select>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary" type="submit">Pesquisar</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('estudantes-inscricoes-aceite-imprmir') }}" class="btn btn-danger float-end mx-2" target="_blink"><i class="fas fa-file-pdf"></i> Imprimir</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive">
                        <table id="carregarTabelaInscricao" style="width: 100%" class="table  table-bordered table-striped  ">
                            <thead>
                                <tr>
                                    <th>Cand. Nº</th>
                                    <th>Nome</th>
                                    <th>Bilhete</th>
                                    <th>Status</th>
                                    <th>Genero</th>
                                    <th>Curso</th>
                                    <th>Classe</th>
                                    <th>Turno</th>
                                    <th>Média</th>
                                    <th>Idade</th>
                                    <th>
                                        Acções
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="tableEstudanes">
                                @if (count($matriculas) != 0)
                                @foreach ($matriculas as $item)
                                <tr>
                                    <td>
                                        @if (Auth::user()->can('read: estudante') || Auth::user()->can('read: matricula'))
                                        <a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}">{{ $item->documento }} </a>
                                        @else
                                        {{ $item->numero_estudante }}
                                        @endif
                                    </td>
                                    <td>
                                        @if (Auth::user()->can('read: estudante') || Auth::user()->can('read: matricula'))
                                        <a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}">{{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }} </a>
                                        @else
                                        {{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}
                                        @endif
                                    </td>
                                    <td>{{ $item->estudante->bilheite }} </td>
                                    @if ($item->status_matricula == 'confirmado')
                                    <td class="text-success">Confirmado</td>
                                    @else
                                    <td class="text-danger">Não Confirmado</td>
                                    @endif
                                    <td>{{ $item->estudante->genero }}</td>
                                    <td>{{ $item->curso->curso }}</td>
                                    <td>{{ $item->classe->classes }}</td>
                                    <td>{{ $item->turno->turno }}</td>
                                    <td>{{ $item->media }}</td>
                                    <td>{{ $item->estudante->idade($item->estudante->nascimento) }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info">Opções</button>
                                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                @if (Auth::user()->can('read: estudante') || Auth::user()->can('read: matricula'))
                                                <a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}" title="Visualizar estudantes" class="dropdown-item"><i class="fa fa-eye"></i> Visualizar</a>
                                                @endif
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="#">Outros</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @endif

                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


@section('scripts')
<script>
    $(function() {
        $("#carregarTabelaInscricao").DataTable({
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
