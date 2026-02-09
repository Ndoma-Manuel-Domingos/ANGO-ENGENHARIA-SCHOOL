@extends('layouts.admin')

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
   
        @if (count($notificacoes) > 0)
            <div class="row mt-3">
                <div class="col-md-9">
                    <div class="list-group">
                        @foreach ($notificacoes as $item)
                            <div class="list-group-item pb-4 mb-1">
                                <div class="row">
                                    <div class="col px-1">
                                        <div>
                                            <div class="float-right">{{ date('d-m-Y', strtotime($item->created_at)) }}</div>
                                            <p class="mb-1 fs-5  text-capitalize"><strong>{{ $item->enviador($item->type_enviado, $item->user_id) }}</strong></p>
                                            <p class="mb-0 fs-6">{{ $item->notificacao }}.</p>
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
</div>
<!-- /.content-header -->

@endsection