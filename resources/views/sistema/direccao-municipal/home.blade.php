@extends('layouts.municipal')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-10">
                <h1 class="m-0 text-dark">Painel geral <span class="text-dark">{{ $direccao->nome }}</span></h1>
            </div><!-- /.col -->
            <div class="col-sm-2">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home-municipal') }}">Painel inicial</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
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
                <div class="callout callout-info">
                    <h5><i class="fas fa-info"></i> Seja bem-vindo ao portal <span class="text-primary">{{ Auth::user()->nome
              }}</span></h5>
                </div>
            </div>
            @if (count($solicitacoes) > 0)
            <div class="col-12 col-md-12">
                <a href="{{ route('web.solicitacoes-dos-professores') }}">
                    <div class="callout callout-warning bg-warning">
                        <h5><i class="fas fa-kiss-wink-heart"></i> Tens {{ count($solicitacoes) }} solicitação(ões) do(s) professor(es) de outra(s) ou mesma escola(s). Clica sobre a notificação para poder visualizar.</h5>
                    </div>
                </a>
            </div>
            @endif

            @if (Auth::user()->login == "Y")
            <div class="col-12 col-md-12">
                <div class="callout callout-warning bg-warning">
                    <h5><i class="fas fa-kiss-wink-heart"></i> Como é pela primeira vez que usa o sistema, pedimos que actualiza as tuas credências de modo ajudar-te o voltar no sistema, caso não pode ignorar. <a href="{{ route('app.privacidade-municipal') }}">Clicar aqui.</a></h5>
                </div>
            </div>
            @endif
        </div>

        <div class="row">
            <div class="col-12 col-md-4 mb3">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>{{ number_format($total_escola, 2, ',', '.') }}</h3>
                        <p>Total de Escolas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-university"></i>
                    </div>
                    <a href="{{ route('listagem-escola-municipal') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-12 col-md-4 mb3">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>{{ number_format($total_estudante, 2, ',', '.') }}</h3>

                        <p>Total de Estudantes</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <a href="{{ route('app.listagem-estudantes-municipal-geral') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-12 col-md-4 mb3">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>{{ number_format($nossos_funcionarios, 2, ',', '.') }}</h3>
                        <p>Nossos Funcionários</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <a href="{{ route('web.funcionarios-municipal-controlo') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <h3 class="m-0 text-white mb-3">Total de Escola, Estudantes e Professores por Provincia Urbanos.</h3>
            </div><!-- /.col -->
        </div>

        <div class="row">
            @foreach ($provincias as $item)
            <div class="col-12 col-md-3 mb3">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h5><a href="{{ route('listagem-escola-municipal', [$item->id]) }}" class="text-decoration-none">{{ $item->nome }}</a></h5>
                        <p>Escolas - {{ $item->total_escola_provincia($item->id) }}</p>
                        <p>Estudantes - {{ $item->total_estudante_provincia($item->id) }}</p>
                        <p>Professores - {{ $item->total_professores_provincia($item->id) }}</p>
                    </div>
                    <a href="#" class="small-box-footer"></a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
