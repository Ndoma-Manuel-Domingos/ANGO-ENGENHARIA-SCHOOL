@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Painel Pedagógico</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.painel-informativo-administrativo') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Turmas</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
    <div class="container-fluid">
        <!-- /.row -->
        <div class="row">
            @if (Auth::user()->can('read: estudante'))
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>@if ($totalestudantes)
                            {{ $totalestudantes }}
                            @else
                            0
                            @endif</h3>
                        <p>Estudantes</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <a href="{{ route('web.estudantes') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            @endif

            @if (Auth::user()->can('read: turma'))
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>@if ($totalturmas)
                            {{ $totalturmas }}
                            @else
                            0
                            @endif</h3>

                        <p>Turmas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chalkboard"></i>
                    </div>
                    <a href="{{ route('web.turmas') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            @endif

            @if (Auth::user()->can('read: horario'))
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>.</h3>

                        <p>Cadastrar Horários</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chalkboard"></i>
                    </div>
                    <a href="{{ route('web.turmas-horarios') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            @endif

            @if (Auth::user()->can('read: professores'))
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>@if ($totalfuncionarios)
                            {{ $totalfuncionarios }}
                            @else
                            0
                            @endif</h3>

                        <p>Professores</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <a href="{{ route('web.funcionarios') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif

            @if (Auth::user()->can('create: estatistica'))
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h2>:</h2>
                        <p>Estatísticas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <a href="{{ route('web.estatistica-turmas') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
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
                    <a href="{{ route('tempos-lecionados.index') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            {{-- <div class="col-lg-3 col-12 col-md-12">
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
    </div> --}}
    @endif

    @if (Auth::user()->can('read: documento'))
    <div class="col-lg-3 col-12 col-md-12">
        <!-- small box -->
        <div class="small-box bg-light">
            <div class="inner">
                <h2>:</h2>
                <p>Documentação</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <a href="{{ route('web.documentacao-estudantes') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    @endif

    @if (Auth::user()->can('read: transeferencia estudante'))
    <div class="col-lg-3 col-12 col-md-12">
        <!-- small box -->
        <div class="small-box bg-light">
            <div class="inner">
                <h3>:</h3>

                <p>Transferências</p>
            </div>
            <div class="icon">
                <i class="ion fas fa-book"></i>
            </div>
            <a href="{{ route('web.transferencia-turmas') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    @endif

    @if ($escola->categoria == 'Privado')

    @if (Auth::user()->can('create: nota'))
    <div class="col-lg-3 col-12 col-md-12">
        <!-- small box -->
        <div class="small-box bg-light">
            <div class="inner">
                <h3>:</h3>
                <p>Avaliações & Provas</p>
            </div>
            <div class="icon">
                <i class="ion fas fa-book"></i>
            </div>
            <a href="{{ route('pedagogicos.lancamento-notas') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    @endif
    @else

    @if ($lancamento)
    @if (Auth::user()->can('create: nota'))
    <div class="col-lg-3 col-12 col-md-12">
        <!-- small box -->
        <div class="small-box bg-light">
            <div class="inner">
                <h5 class="mb-3">Avaliações & Provas</h5>
                <p class="text-success">Periodo do lançamento das notas activa</p>
            </div>
            <div class="icon">
                <i class="ion fas fa-book"></i>
            </div>
            <a href="{{ route('pedagogicos.lancamento-notas') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    @endif
    @endif
    @endif

    {{-- @if (Auth::user()->can('create: nota')) --}}
    <div class="col-lg-3 col-12 col-md-12">
        <!-- small box -->
        <div class="small-box bg-light">
            <div class="inner">
                <h3>:</h3>
                <p>Gera Boletins</p>
            </div>
            <div class="icon">
                <i class="ion fas fa-book"></i>
            </div>
            <a href="{{ route('web.turmas-boletins-estudantes') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    {{-- @endif --}}

    @if (Auth::user()->can('read: pautas'))

    <div class="col-lg-3 col-12 col-md-12">
        <!-- small box -->
        <div class="small-box bg-light">
            <div class="inner">
                <h2>:</h2>
                <p>Mini-Pautas Gerais.</p>
            </div>
            <div class="icon">
                <i class="fas fa-list"></i>
            </div>
            <a href="{{ route('web.mini-pauta-geral') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    @endif

    @if ($escola->ensino && $escola->ensino->nome != "Ensino Superior")

    @if (Auth::user()->can('read: mini pautas'))
    <div class="col-lg-3 col-12 col-md-12">
        <div class="small-box bg-light">
            <div class="inner">
                <h2>:</h2>
                <p>Mini-pautas.</p>
            </div>
            <div class="icon">
                <i class="fas fa-table"></i>
            </div>
            <a href="{{ route('web.mini-pauta') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    @endif

    {{--@if (Auth::user()->can('formulario iniciacao'))
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h2>:</h2>
                        <p>Formulário Iniciação</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-excel"></i>
                    </div>
                    <a href="{{ route('web.formulario.iniciacao') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
</div>
</div>
@endif

@if (Auth::user()->can('formulario primário regular'))
<div class="col-lg-3 col-12 col-md-12">
    <!-- small box -->
    <div class="small-box bg-light">
        <div class="inner">
            <h2>:</h2>
            <p>Formulário Primário Regular</p>
        </div>
        <div class="icon">
            <i class="fas fa-file-excel"></i>
        </div>
        <a href="{{ route('web.formulario.primario.regular') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
    </div>
</div>
@endif

@if (Auth::user()->can('formulario iº cliclo regular'))
<div class="col-lg-3 col-12 col-md-12">
    <!-- small box -->
    <div class="small-box bg-light">
        <div class="inner">
            <h2>:</h2>
            <p>Formulário Iº Cliclo Regular</p>
        </div>
        <div class="icon">
            <i class="fas fa-file-excel"></i>
        </div>
        <a href="{{ route('web.formulario.primario-ciclo.regular') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
    </div>
</div>
@endif

@if (Auth::user()->can('formulario iiº cliclo regular'))
<div class="col-lg-3 col-12 col-md-12">
    <!-- small box -->
    <div class="small-box bg-light">
        <div class="inner">
            <h2>:</h2>
            <p>Formulário IIº Cliclo Regular</p>
        </div>
        <div class="icon">
            <i class="fas fa-file-excel"></i>
        </div>
        <a href="{{ route('web.formulario.segundo-ciclo.regular') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
    </div>
</div>
@endif


@if (Auth::user()->can('ficha iniciacao'))
<div class="col-lg-3 col-12 col-md-12">
    <!-- small box -->
    <div class="small-box bg-light">
        <div class="inner">
            <h2>:</h2>
            <p>Ficha AFEP - Iniciação</p>
        </div>
        <div class="icon">
            <i class="fas fa-file-excel"></i>
        </div>
        <a href="{{ route('web.formulario.ficha-afep-iniciacao') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
    </div>
</div>
@endif

@if (Auth::user()->can('ficha ensino primario regular'))
<div class="col-lg-3 col-12 col-md-12">
    <!-- small box -->
    <div class="small-box bg-light">
        <div class="inner">
            <h2>:</h2>
            <p>Ficha AFEP Ensino Primário Regular</p>
        </div>
        <div class="icon">
            <i class="fas fa-file-excel"></i>
        </div>
        <a href="{{ route('web.formulario.ficha-afep-ensino-primario.regular') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
    </div>
</div>
@endif

@if (Auth::user()->can('ficha ensino iº ciclo regular'))
<div class="col-lg-3 col-12 col-md-12">
    <!-- small box -->
    <div class="small-box bg-light">
        <div class="inner">
            <h2>:</h2>
            <p>Ficha AFEP Ensino Iº Ciclo Regular</p>
        </div>
        <div class="icon">
            <i class="fas fa-file-excel"></i>
        </div>
        <a href="{{ route('web.formulario.ficha-afep-ensino-primario-ciclo.regular') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
    </div>
</div>
@endif

@if (Auth::user()->can('ficha ensino iiº ciclo regular'))
<div class="col-lg-3 col-12 col-md-12">
    <!-- small box -->
    <div class="small-box bg-light">
        <div class="inner">
            <h2>:</h2>
            <p>Ficha AFEP Ensino IIº Ciclo Regular</p>
        </div>
        <div class="icon">
            <i class="fas fa-file-excel"></i>
        </div>
        <a href="{{ route('web.formulario.ficha-afep-ensino-segundo-ciclo.regular') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
    </div>
</div>
@endif --}}
@endif
</div>

<div class="row">
    <div class="col-12 col-sm-12 col-md-5">
        <div class="card">
            <div class="card-body" style="height: 400px">
                {!! $chartEstudantesCursos->container() !!}
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-12 col-md-7">
        <div class="card">
            <div class="card-body" style="height: 400px">
                {!! $chartEstudantesClasse->container() !!}
            </div>
        </div>
    </div>
</div>

</div><!-- /.container-fluid -->
</div>
<!-- /.content -->
<!-- /.content -->

{!! $chartEstudantesCursos->script() !!}
{!! $chartEstudantesClasse->script() !!}

@endsection
