@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Confirmação de estudantes</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.painel-informativo-administrativo') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Painel > Controle</li>
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
                  <h5><i class="fas fa-info"></i> Confirmação de estudantes já matriculados, para o novo ano lectivo. Informe o número da matricula do ano passado e confirma o estudante para novo ano.</h5>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('web.estudantes-confirmacao') }}" method="get" id="formulario_busca">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-md-3 mb3">
                                    <input type="text" name="numero_processo" placeholder="Informe o Nome, Bilhete o Número de Matricula do Estudante" class="form-control">
                                </div>
                                <div class="col-12 col-md-3 mb3">
                                    <select name="ano_lectivos_ids" id="ano_lectivos_ids" class="select2 form-control ano_lectivos_ids" style="width: 100%;">
                                        <option value="">Todas Anos Lectivos</option>
                                        @if ($ano_lectivos)
                                            @foreach ($ano_lectivos as $ano)
                                            <option value="{{ $ano->id }}" {{ $requests['ano_lectivos_ids'] == $ano->id  ? 'selected' : '' }}>{{ $ano->ano }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-12 col-md-3 mb3">
                                    <select name="cursos_id" id="cursos_id" class="select2 form-control cursos_id" style="width: 100%;">
                                        <option value="">Todas Cursos</option>
                                        @if ($cursos)
                                            @foreach ($cursos as $curso)
                                            <option value="{{ $curso->curso->id }}" {{ $requests['cursos_id'] == $curso->curso->id  ? 'selected' : '' }}>{{ $curso->curso->curso }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-12 col-md-3 mb3">
                                    <select name="classes_id" id="classes_id" class="select2 form-control classes_id" style="width: 100%;">
                                        <option value="">Todas Classes</option>
                                        @if ($classes)
                                            @foreach ($classes as $classe)
                                            <option value="{{ $classe->classe->id }}" {{ $requests['classes_id'] == $classe->classe->id  ? 'selected' : '' }}>{{ $classe->classe->classes }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary pesquisarEstudantes" form="formulario_busca"><i class="fas fa-search"></i> Pesquisar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="carregarTabelaMatricula" style="width: 100%" class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th>Nº Proc.</th>
                                    <th>Nome Completo</th>
                                    <th>Genero</th>
                                    <th>Bilhete</th>
                                    <th>Curso</th>
                                    <th>Classe</th>
                                    <th>Turno</th>
                                    <th>Estado</th>
                                    <th>Ano Lectivo</th>
                                    <th>Data</th>
                                    <th>Acções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($matriculas as $key => $item)
                                <tr>
                                    <td><a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}">{{ $item->numero_estudante }}</a></td>
                                    <td><a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}">{{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}</a></td>
                                    <td>{{ $item->estudante->genero }}</td>
                                    <td>{{ $item->estudante->bilheite }}</td>
                                    <td>{{ $item->curso->curso }}</td>
                                    <td>{{ $item->classe->classes }}</td>
                                    <td>{{ $item->turno->turno }}</td>
                                    <td>
                                        @if ($item->status_matricula == "confirmado")
                                        <span>Admitido</span>
                                        @endif

                                        @if ($item->status_matricula == "falecido")
                                        <span>Falecido</span>
                                        @endif

                                        @if ($item->status_matricula == "desistente")
                                        <span>Desistente</span>
                                        @endif

                                        @if ($item->status_matricula == "nao_confirmado")
                                        <span>Não Admitido</span>
                                        @endif
                                    <td>{{ $item->ano_lectivo->ano }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>
                                        @if ($item->status_matricula == "confirmado" && $item->estudante->finalista != "finalista")
                                        <a href="{{ route('web.estudantes-confirmacao-novo-ano', [Crypt::encrypt($item->estudante->id), Crypt::encrypt($item->ano_lectivos_id)]) }}" title="Confirmar matricula do estudante" class="btn btn-warning">Confirmar</a>
                                        @endif

                                        @if ($item->status_matricula == "falecido")
                                        <button type="button" disabled title="" class="btn btn-danger">Inactivo</button>
                                        @endif

                                        @if ($item->status_matricula == "desistente")
                                        <button type="button" disabled title="" class="btn btn-danger">Inactivo</button>
                                        @endif

                                        @if ($item->status_matricula == "nao_confirmado")
                                        <button type="button" disabled title="" class="btn btn-danger">Inactivo</button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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
