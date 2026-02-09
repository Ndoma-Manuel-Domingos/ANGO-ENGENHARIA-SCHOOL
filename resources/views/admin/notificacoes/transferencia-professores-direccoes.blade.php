@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Notifacações</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
            </div><!-- /.col -->
        </div>

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
        </div>
        @if (count($solicitacoes) > 0)
        @foreach ($solicitacoes as $item)
        <div class="row mt-3">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body">
                        <div class="list-group">
                            <div class="list-group-item pb-4 mb-1">
                                <div class="row">
                                    <div class="col px-1">
                                        <div>
                                            <h4>Solicitação de {{ $item->solicitacao }} para o professor <span
                                                    class="text-decoration-underline">{{ $item->professor->nome }} {{
                                                    $item->professor->sobre_nome }}</span>.</h4>
                                                    
                                            <h6 class="float-righta">Avaliado pela(o): {{ $item->instituicao_destino($item->level_respondido, $item->resposta_instituicao_id) }}.</h6>
                                            <h6 class="float-righta">Usuário Avaliador(o): {{ $item->user->nome ?? ''}}.</h6>
                                            <h6 class="float-righta">Data de apresentação do professor: {{ date('d-m-Y', strtotime($item->updated_at))
                                            }}.</h6>     
                                            <h6 class="float-righta">Data Envio: {{ date('d-m-Y', strtotime($item->created_at))
                                                }}.</h6>
                                                
                                                
                                                
                                            <p class="mb-1 fs-6 ">O Professor {{ $item->professor->nome }} {{
                                                $item->professor->sobre_nome }} fez uma solicitação de {{ $item->solicitacao }}
                                                no curso de {{ $item->curso->curso }}, na disciplina de {{
                                                $item->disciplina->disciplina }} e na classe de {{ $item->classe->classes }}.
                                            </p>
                                            <p class="mb-0 mt-3 fs-6">{{ $item->descricao }}.</p>
                                            @if ($item->status == '1')
                                            <h6 class="mt-3 ml-5">Resposta: <span class="float-right">Data Resposta: {{
                                                    date('d-m-Y', strtotime($item->updated_at)) }}</span> </h6>
                                            <p class="mb-0 ml-5 mt-3 fs-6 text-secondary">{{ $item->resposta_descricao }}.</p>
                                            @endif
        
                                            @if ($item->status == '0')
                                            <a href="" class="btn btn-primary mt-5 d-inline-block">Responder</a>
                                            <a href="{{ route('shcools.painel-benvindo-administrativo') }}"
                                                class="btn btn-danger mt-5 d-inline-block">Cancelar</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('web.transferincias-professores-pela-direccao-aprovacao-escola', $item->id) }}" class="btn btn-primary">Aprovar apresentação do Professor</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6" style="width: 70px">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div class="form-control mb-1">
                            Nome: {{ $item->professor->nome }} {{ $item->professor->sobre_nome }}
                        </div>

                        <div class="form-control mb-1">
                            Genero: {{ $item->professor->genero }}
                        </div>
                        
                        <div class="form-control mb-1">
                            Genero: {{ $item->professor->bilheite }}
                        </div>

                        <div class="form-control mb-1">
                            Telefone: {{ $item->professor->telefone }}
                        </div>

                        <div class="form-control mb-1">
                            Especialidade: {{ $item->professor->academico->especialidade->nome ?? '' }}
                        </div>

                        <div class="form-control mb-1">
                            Categoria: {{ $item->professor->academico->categoria->nome ?? '' }}
                        </div>

                        <div class="form-control mb-1">
                            Formação: {{ $item->professor->academico->formacao_academica->nome ?? '' }}
                        </div>

                        <div class="form-control mb-1">
                            Formação: {{ $item->professor->academico->escolaridade->nome ?? '' }}
                        </div>
                    </div>
                    <div class="card-footer p-3"></div>
                </div>

            </div>
        </div>
        @endforeach
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
</div>
<!-- /.content-header -->

@endsection