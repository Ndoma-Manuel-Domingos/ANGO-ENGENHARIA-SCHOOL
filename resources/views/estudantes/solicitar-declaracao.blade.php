@extends('layouts.estudantes')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Solicitação de Documentos</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route("est.home-estudante") }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Documentos</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- /.content -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12 col-md-12">
                @if(session()->has('danger'))
                <div class="alert alert-warning">
                    {{ session()->get('danger') }}
                </div>
                @endif

                @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
                @endif
            </div>
            <div class="col-12 mb-3">
                <form action="{{ route('est.solicitacoes-declaracao-store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">

                                <div class="form-group col-md-3 col-12">
                                    <label for="efeito_id" class="form-label">Para que efeito</label>
                                    <select name="efeito_id" class="form-control select2 @error('efeito_id') is-invalid @enderror" style="width: 100%;">
                                        <option value="">Selecione</option>
                                        @foreach ($efeitos as $item)
                                        <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('efeito_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="tipo_documento" class="form-label">Tipo Documento</label>
                                    <select name="tipo_documento" class="form-control select2 @error('tipo_documento') is-invalid @enderror" style="width: 100%;">
                                        <option value="">Selecione</option>
                                        <option value="declaracao com nota">Declaração Com Notas</option>
                                        <option value="declaracao sem nota">Declaração Sem Notas</option>
                                        <option value="declaracao">Declaração</option>
                                        <option value="boletin de nota">Boletin de Notas</option>
                                    </select>
                                    @error('tipo_documento')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="trimestre_id" class="form-label">Trimestres</label>
                                    <select name="trimestre_id" class="form-control select2 @error('trimestre_id') is-invalid @enderror" style="width: 100%;">
                                        <option value="">Selecione</option>
                                        @foreach ($trimestres as $item)
                                        <option value="{{ $item->id }}">{{ $item->trimestre }}</option>
                                        @endforeach
                                    </select>
                                    @error('trimestre_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="ano_lectivos_id" class="form-label">Ano Lectivo</label>
                                    <select name="ano_lectivos_id" class="form-control select2 @error('ano_lectivos_id') is-invalid @enderror" style="width: 100%;">
                                        <option value="">Selecione</option>
                                        @foreach ($anos as $item)
                                        <option value="{{ $item->id }}">{{ $item->ano }}</option>
                                        @endforeach
                                    </select>
                                    @error('ano_lectivos_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-12 col-12">
                                    <label for="descricao" class="form-label">Descrição</label>
                                    <textarea name="descricao" cols="30" rows="5" class="form-control @error('descricao') is-invalid @enderror" placeholder="Escreve uma descrição da sua solicitação"></textarea>
                                    @error('descricao')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>
                        <div class="card-footer">
                            @if (Auth::user()->can('create: documento'))
                            <button type="submit" class="btn btn-primary">Solicitar Documento</button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if (count($minhas_solicitacoes) > 0)
        <div class="row mt-3">
            <div class="col-md-9">
                <div class="list-group">
                    @foreach ($minhas_solicitacoes as $item)
                    <div class="list-group-item pb-2 mb-1">
                        <div class="row">
                            <div class="col px-1">
                                <div>
                                    <div class="float-right">{{ date('d-m-Y', strtotime($item->created_at)) }} ás {{ date('H:i:s', strtotime($item->created_at)) }}</div>
                                    <p class="mb-1 fs-5  text-capitalize"><strong>{{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}</strong></p>
                                    <p class="mb-0 fs-6">{{ $item->descricao }}.</p>
                                    <p class="mb-0 fs-5">PROCESSO: {{ $item->processo }}.</p>
                                    @if ($item->processo == "CONCLUIDO")
                                    <p>O Processo foi finalizado e autorizador por Sr(a) <span class="text-secondary">{{ $item->finalizador->nome }}</span>.</p>
                                    <a href="{{ $item->links }}" target="_blink">User link</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @else
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="callout callout-info">
                    <h5><i class="fas fa-info"></i> Sem registro de notificações.</h5>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
<!-- /.content-header -->

@endsection
