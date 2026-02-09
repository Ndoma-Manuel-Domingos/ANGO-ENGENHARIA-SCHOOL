@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Painel do Recursos Humanos</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.painel-informativo-administrativo') }}">Painel de controle</a></li>
                    <li class="breadcrumb-item active">Recursos Humanos</li>
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
            <div class="col-12 col-md-12">
                <div class="callout callout-info">
                    <h5><i class="fas fa-kiss-wink-heart"></i> Holla Srº(ª) {{ $usuario->nome }}, seja Bem-vindo ao software {{ env('APP_NAME') }}. <span class="float-right text-warning">Módulo {{ $escola->modulo }}</span></h5>
                </div>
            </div>
        </div>

        {{--carregar o submenu ou cards recursos humanos  --}}
        <div class="row">

            @if (Auth::user()->can('read: funcionario'))
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>{{ $total_professores }}</h3>

                        <p>Professores</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <a href="{{ route('web.funcionarios') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif

            @if (Auth::user()->can('read: professores'))
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>{{ $total_funcionario }}</h3>

                        <p>Funcionários</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <a href="{{ route('web.outro-funcionarios') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif

            @if (Auth::user()->can('read: salario'))
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>::</h3>

                        <p>Pagamento de Salário</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <a href="{{ route('web.financeiro-pagamentos-salario') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif

            @if (Auth::user()->can('read: efectividade'))
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h2>:</h2>
                        <p>Gestão de Efectividade</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                    <a href="{{ route('web.mapa-efectividade') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif

            @if (Auth::user()->can('read: falta'))
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h2>:</h2>
                        <p>Faltas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-excel"></i>
                    </div>
                    <a href="{{ route('web.faltas-turmas') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif

            {{-- @if (Auth::user()->can('read: salario')) --}}
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>::</h3>

                        <p>Biométrico</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-fingerprint"></i>
                    </div>
                    <a href="{{ route('web.biometrico-index') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            {{-- @endif --}}


            @if (Auth::user()->can('read: salario'))
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>::</h3>

                        <p>Folhas de Salário</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <a href="{{ route('web.financeiro-mes-folha-salario') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif

            @if (Auth::user()->can('read: departamento'))
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>{{ $total_departamentos }}</h3>

                        <p>Departamentos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <a href="{{ route('web.departamento') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif

            @if (Auth::user()->can('read: cargo'))
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>{{ $total_cargos }}</h3>

                        <p>Cargos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-tag"></i>
                    </div>
                    <a href="{{ route('web.cargos') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif

        </div>

    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
@endsection
