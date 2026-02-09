@extends('layouts.professores')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Datalhe Solicitações</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{ route('prof.home-profs') }}">Voltar</a></li>
                  <li class="breadcrumb-item active">Solicitações</li>
                </ol>
            </div>
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row mt-3">
            <div class="col-md-9">
                <div class="list-group">
                    <div class="list-group-item pb-4 mb-1">
                        <div class="row">
                            <div class="col px-1">
                                <div>
                                    <h4>Solicitação de {{ $item->solicitacao ?? 'N/A' }}.</h4>
                                    
                                    <p class="float-lefts  fs-6">Enviada(o) Para:  <strong>{{ $item->instituicao_destino($item->level_destino, $item->instituicao_id) }}</strong>. </p>
                                    <p class="float-lefts fs-6">Data Envio: <strong>{{ date('d-m-Y', strtotime($item->created_at)) }}.</strong> </p>
                                    <p class="mb-1 fs-6  text-justify">O Srº <span class="text-decoration-underline">{{ $item->professor->nome ?? 'N/A' }}</span> <span class="text-decoration-underline">{{ $item->professor->sobre_nome ?? 'N/A' }}</span>   fez uma solicitação de <span class="text-decoration-underline">{{ $item->solicitacao ?? 'N/A' }}</span>  no curso de <span class="text-decoration-underline">{{ $item->curso->curso ?? 'N/A' }}</span> , 
                                    na disciplina de <span class="text-decoration-underline">{{ $item->disciplina->disciplina ?? 'N/A' }}</span>  e na classe de <span class="text-decoration-underline">{{ $item->classe->classes ?? 'N/A' }}</span> na instituição <span class="text-decoration-underline">{{ $item->instituicao1->nome ?? 'N/A' }}</span>.</p>
                                    <p class=" text-justify">{{ $item->descricao ?? 'N/A' }}</p>
                                    @if ($item->status == '1')
                                    <h6 class="mt-3 ml-5">Resposta:  <span class="float-right">Data Resposta: {{ date('d-m-Y', strtotime($item->updated_at)) }}</span> </h6>
                                    <p class="mb-0 ml-5 mt-3 fs-6 text-secondary text-justify">{{ $item->resposta_descricao ?? 'N/A' }}.</p>
                                    <a href="{{ route('prof.baixar-solicitacoes', $item->id) }}" target="_blink" class="btn btn-primary mt-5 d-inline-block">Baixar Documento</a>
                                    @endif
                                    
                                    <h5>Situação do seu Processo: <span class="text-success">{{ $item->processo }}</span> </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection