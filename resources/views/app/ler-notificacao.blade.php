@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Notificações Enviadas</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Notificação</a></li>
                    <li class="breadcrumb-item active">Enviadas</li>
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
            <div class="col-md-3">
                @if (auth()->user()->hasRole(['encarregado']))
                <a href="{{ route('web.enviadas-notificao') }}" class="btn btn-primary btn-block mb-3">Entradas</a>
                @else
                <a href="{{ route('web.entradas-notificao') }}" class="btn btn-primary btn-block mb-3">Entradas</a>
                @endif
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Arquivos</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <ul class="nav nav-pills flex-column">
                            @if (auth()->user()->hasRole(['encarregado']))
                            <li class="nav-item">
                                <a href="{{ route('web.enviadas-notificao') }}" class="nav-link">
                                    <i class="far fa-envelope"></i> Entradas
                                    <span class="badge bg-primary float-right">{{ $notificacaoEnviadas }}</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('web.reciclagem-notificao') }}" class="nav-link">
                                    <i class="far fa-envelope"></i> Reciclagem
                                    <span class="badge bg-primary float-right">{{ $notificacaoReciclagem }}</span>
                                </a>
                            </li>
                            @else
                            <li class="nav-item">
                                <a href="{{ route('web.enviadas-notificao') }}" class="nav-link">
                                    <i class="far fa-envelope"></i> Enviadas
                                    <span class="badge bg-primary float-right">{{ $notificacaoEnviadas }}</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('web.reciclagem-notificao') }}" class="nav-link">
                                    <i class="far fa-envelope"></i> Reciclagem
                                    <span class="badge bg-primary float-right">{{ $notificacaoReciclagem }}</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Ler Notificação</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <div class="mailbox-read-info">
                            <h3> &bull; {{ $notificacao->nomeEscola }} <span class="mailbox-read-time float-right">{{ $notificacao->created_at }}</span></h3>
                            <h5> &bull; {{ $notificacao->titulo }}</h5>
                            <h5><span class="mailbox-read-time float-left"> &bull; {{ $notificacao->nome }} {{ $notificacao->sobre_nome }}</span></h5><br>
                        </div>
                        <!-- /.mailbox-controls -->
                        <div class="mailbox-read-message">
                            <p>{{ $notificacao->descricao }}</p>
                        </div>

                        @if ($notificacao->tipo == 'notas')
                        <div class="col-sm-12 col-md-12 text-center bg-dark">
                            <h1 class="fs-5"><strong>BOLETIN DE NOTAS </strong></h1>
                        </div>

                        <div class="col-sm-12 bg-light">

                            <ul class="fs-6 d-flex ">
                                <li><strong>Turma: </strong> <span class="span_turma">{{ $turma->turma }}</span>. &nbsp; </li>
                                <li><strong>Classe: </strong> <span class="span_classe">{{ $classe->classes }}</span>. &nbsp; </li>
                                <li><strong>Curso: </strong> <span class="span_curso">{{ $curso->curso }}</span>. &nbsp; </li>
                                <li><strong>Área de Formação: </strong> <span class="span_area">{{ $curso->area_formacao }}</span>. &nbsp; </li>
                                <li><strong>Turno: </strong> <span class="span_turno">{{ $turno->turno }}</span>. &nbsp; </li>
                                <li><strong>Sala Nº: </strong> <span class="span_sala">{{ $sala->salas }}</span>. &nbsp; </li>
                                <li><strong>Período: </strong> <span class="span_trimestre">{{ $trimestre->trimestre }}</span>. &nbsp; </li>
                                <li><strong>Ano Lectivo </strong> <span class="span_ano_lectivo">{{ $anoLectivo->ano }}</span>. </li>
                                <li><strong>Estudante: </strong><span class="span_estudante">{{ $estudante->nome }} {{ $estudante->sobre_nome }}</span>. &nbsp; </li>
                            </ul>
                        </div>

                        <div class="col-sm-12">
                            <table style="width: 100%" class="table projects  ">
                                <thead>

                                    <tr>
                                        <th>Nº</th>
                                        <th>Disciplinas</th>
                                        <th>MAC</th>
                                        <th>NPT</th>
                                        <th>MT</th>

                                        <th>Observação</th>
                                    </tr>

                                </thead>

                                <tbody>
                                    @foreach ($resultados as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->disciplina }}</td>
                                        <td>{{ $item->mac }}</td>
                                        <td>{{ $item->npt }}</td>
                                        <td>{{ $item->mt }}</td>
                                        @if ($item->mt >= 10)
                                        <td class="text-success"> Transita</td>
                                        @else
                                        <td class="text-danger"> N/Transita</td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>

                                <tfoot>
                                    <th> - </th>
                                    <th> - </th>
                                    <th> - </th>
                                    <th> - </th>
                                    <th>Media Final {{ $mediaFinal }}</th>
                                    @if ($mediaFinal >= 10)
                                    <th class="text-success"> Aprovado</th>
                                    @else
                                    <th class="text-danger"> Reprovado</th>
                                    @endif
                                </tfoot>
                            </table>
                        </div>
                        @endif


                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

@endsection
