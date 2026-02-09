@extends('layouts.escolas')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Mini Pautas do Estudante</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($estudantes_id)) }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Perfil</li>
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
                    <form action="{{ route('web.pesquisa-turmas-mini-pauta-estudante') }}" method="GET" id="formulario">
                        <div class="card">
                            <div class="card-header"></div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        @if ($ano_lectivos)
                                        <select name="ano_lectivos_id" id="ano_lectivos_id" class="select2 form-control ano_lectivos_id" style="width: 100%">
                                            <option value="">Ano Lectivo</option>
                                            @foreach ($ano_lectivos as $item)
                                            <option value="{{ $item->id }}">{{ $item->ano }}</option>
                                            @endforeach
                                        </select>
                                        @endif
                                    </div>

                                    <div class="form-group col-md-4">
                                        @if ($trimestres)
                                        <select name="trimestre_id" id="trimestre_id" class="select2 form-control trimestre_id" style="width: 100%">
                                            <option value="">Trimestre</option>
                                            @foreach ($trimestres as $item)
                                            <option value="{{ $item->id }}">{{ $item->trimestre }}</option>
                                            @endforeach
                                        </select>
                                        @endif
                                    </div>

                                    <input type="hidden" name="estudantes_id" value="{{ $estudantes_id }}" id="estudantes_id" class="estudantes_id">

                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" form="formulario" class="btn btn-primary"><i class="fas fa-search"></i> Pesquisa</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


            @if (isset($resultados) AND $resultados)
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <ul class="d-flex py-2 px-0">
                                <li><strong>Turma: </strong> <span class="span_turma">{{ $turma->turma }}</span>. &nbsp; </li>
                                <li><strong>Classe: </strong> <span class="span_classe">{{ $classe->classes }}</span>. &nbsp; </li>
                                <li><strong>Curso: </strong> <span class="span_curso">{{ $curso->curso }}</span>. &nbsp; </li>
                                <li><strong>Área de Formação: </strong> <span class="span_area">{{ $curso->area_formacao }}</span>. &nbsp; </li>
                                <li><strong>Turno: </strong> <span class="span_turno">{{ $turno->turno }}</span>. &nbsp; </li>
                                <li><strong>Sala Nº: </strong> <span class="span_sala">{{ $sala->salas }}</span>. &nbsp; </li>
                                <li><strong>Período: </strong> <span class="span_trimestre">{{ $estudante->nome }} {{ $estudante->sobre_nome }}</span>. &nbsp; </li>
                                <li><strong>Ano Lectivo </strong> <span class="span_ano_lectivo">{{ $trimestre->trimestre }}</span>. </li>
                                <li><strong>Estudante: </strong><span class="span_estudante">{{ $anoLectivo->ano }}</span>. &nbsp; </li>
                            </ul>
                        </div>

                        <div class="card-body table-responsive">
                            <table style="width: 100%" class="table  table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Nº</th>
                                        <th>Disciplinas</th>
                                        <th>MAC</th>
                                        <th>NPT</th>
                                        <th>MT</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($resultados as $result)
                                    <tr>
                                        <td> {{ $result->id }}</td>
                                        <td> {{ $result->disciplina->disciplina }}</td>
                                        <td> {{ $result->mac }}</td>
                                        <td> {{ $result->npt }}</td>
                                        <td> {{ $result->mt }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>Media Final {{ $mediaFinal }} </th>
                                    <th>Resultado Final</th>
                                </tfoot>
                            </table>
                        </div>

                        <div class="card-footer">
                            @if (Auth::user()->can('read: nota'))
                            <div class="col-12 col-md-12">
                                <a href="{{ route('dow.boletin-estudante', [ 'code'=> $_GET['estudantes_id'],  'ano'=> $_GET['ano_lectivos_id'],  'trimestre'=> $_GET['trimestre_id'],  ]) }}" target="_blank" class="btn btn-primary"><i class="fas fa-file-pdf"></i> Imprimir</a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div><!-- /.col -->
            </div><!-- /.row -->
            @endif
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

@endsection
