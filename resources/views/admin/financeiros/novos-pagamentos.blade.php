@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Painel Financeiro</h1>
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
        <!-- Small boxes (Stat box) -->

        <div class="row">
            {{-- GERAL --}}
            @if (Auth::user()->can('painel financeiro'))
            <div class="col-lg-3 col-12 col-md-12">
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>{{ number_format($pagamentosValoresReceber, 2, ',', '.') }} <sub>Kzs</sub> </h3>

                        <p>Contas da Receber Geral [ENTRADAS]</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-check-alt"></i>
                    </div>
                    <a href="{{ route('financeiros.financeiro-contas-receber') }}" class="small-box-footer bg-info">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif

            @if (Auth::user()->can('painel financeiro'))
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>{{ number_format($pagamentosValoresPagar, 2, ',', '.') }} <sub>Kzs</sub></h3>

                        <p>Contas a Pagar Geral [SAÍDAS]</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-check-alt"></i>
                    </div>
                    <a href="{{ route('financeiros.financeiro-contas-pagar') }}" class="small-box-footer bg-danger">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif

            @if (Auth::user()->can('painel financeiro'))
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>{{ number_format($pagamentosValoresReceber - $pagamentosValoresPagar, 2, ',', '.') }}
                            <sub>Kzs</sub></h3>

                        <p>SALDO ACTUAL NA CONTA</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-check-alt"></i>
                    </div>
                    <a href="" class="small-box-footer bg-success">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif


            @if (Auth::user()->can('painel financeiro'))
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>
                            {{ number_format($multaAcumuladasPagas, 2, ',', '.') }} <sub>Kzs</sub>
                        </h3>

                        <p>MULTAS ACUMULADAS PAGAS</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-check-alt"></i>
                    </div>
                    <a href="{{ route('financeiros.financeiro-gestao-dividas') }}" class="small-box-footer bg-info">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif

            @if (Auth::user()->can('painel financeiro'))
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>
                            {{ number_format($multaAcumuladasNaoPagas, 2, ',', '.') }} <sub>Kzs</sub>
                        </h3>

                        <p>MULTAS ACUMULADAS NÃO PAGAS</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-check-alt"></i>
                    </div>
                    <a href="{{ route('financeiros.financeiro-gestao-dividas') }}" class="small-box-footer bg-danger">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif

            @if (Auth::user()->can('painel financeiro'))
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>
                            {{ number_format($multasAcumulada, 2, ',', '.') }} <sub>Kzs</sub>
                        </h3>

                        <p>MULTAS ACUMULADAS</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-check-alt"></i>
                    </div>
                    <a href="{{ route('financeiros.financeiro-gestao-dividas') }}" class="small-box-footer bg-success">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif

            @if (Auth::user()->can('painel financeiro'))
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>
                            {{ number_format($dividaAcumuladas, 2, ',', '.') }} <sub>Kzs</sub>
                        </h3>

                        <p>DIVÍDAS GERAIS</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-check-alt"></i>
                    </div>
                    <a href="{{ route('financeiros.financeiro-gestao-dividas') }}" class="small-box-footer bg-danger">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif



            @if (Auth::user()->can('read: pagamento'))
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>:</h3>

                        <p>Relatório de Pagamentos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <a href="{{ route('web.financeiro-buscas-gerais') }}" class="small-box-footer">Mais Informação
                        <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif

            @if (Auth::user()->can('read: pagamento'))
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>:</h3>

                        <p>Buscas Mensais</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <a href="{{ route('web.financeiro-outras-buascas') }}" class="small-box-footer">Mais Informação
                        <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif

            @if (Auth::user()->can('read: estudante'))
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>Estudantes</h3>

                        <p>Listagem dos estudantes com valores da propina</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-check-alt"></i>
                    </div>
                    <a href="{{ route('financeiros.estudantes') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif
        </div>
    </div><!-- /.container-fluid -->
</div>

@endsection
