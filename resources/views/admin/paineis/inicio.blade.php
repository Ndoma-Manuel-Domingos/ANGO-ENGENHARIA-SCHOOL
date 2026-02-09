@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Painel de controle</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.painel-informativo-administrativo') }}">Painel de controle</a></li>
                    <li class="breadcrumb-item active">Inicio</li>
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
        {{--carregar o submenu ou cards dashboard  --}}
        <div class="row">

            @if (Auth::user()->can('read: ano lectivo'))
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light" style="border: 4px solid  {{ Auth::user()->color_fundo }}">
                    <div class="inner">
                        <h3>{{ $totalanolectivos }}</h3>
                        <p class="text-uppercase">Ano lectivo</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <a href="{{ route('web.ano-lectivo') }}" class="small-box-footer {{ Auth::user()->color_fundo }}">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif

            @if (Auth::user()->can('read: turno'))
            <!-- ./col -->
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light" style="border: 4px solid  {{ Auth::user()->color_fundo }}">
                    <div class="inner">
                        <h3>{{ $totalturnos }}</h3>
                        <p class="text-uppercase">Turnos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-border-all"></i>
                    </div>
                    <a href="{{ route('web.turnos-index-ano-lectivo') }}" class="small-box-footer {{ Auth::user()->color_fundo }}">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif

            @if (Auth::user()->can('read: classe'))
            <!-- ./col -->
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light" style="border: 4px solid  {{ Auth::user()->color_fundo }}">
                    <div class="inner">
                        <h3>{{ $totalclasses }}</h3>
                        <p class="text-uppercase">Classes</p>
                    </div>
                    <div class="icon">
                        <i class="fab fa-buffer"></i>
                    </div>
                    <a href="{{ route('web.classes-index-ano-lectivo') }}" class="small-box-footer {{ Auth::user()->color_fundo }}">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif


            @if ($escola->ensino && $escola->ensino->nome == "Ensino Superior")

            @if (Auth::user()->can('read: candidatura'))
            <!-- ./col -->
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light" style="border: 4px solid  {{ Auth::user()->color_fundo }}">
                    <div class="inner">
                        <h3>{{ $totalcandidaturas }}</h3>
                        <p class="text-uppercase">Candidaturas</p>
                    </div>
                    <div class="icon">
                        <i class="fab fa-buffer"></i>
                    </div>
                    <a href="{{ route('web.candidaturas-index-ano-lectivo') }}" class="small-box-footer {{ Auth::user()->color_fundo }}">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif


            @if (Auth::user()->can('read: faculdade'))
            <!-- ./col -->
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light" style="border: 4px solid  {{ Auth::user()->color_fundo }}">
                    <div class="inner">
                        <h3>{{ $totalfaculdades }}</h3>

                        <p class="text-uppercase">Faculdades</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-map"></i>
                    </div>
                    <a href="{{ route('web.faculdades-index-ano-lectivo') }}" class="small-box-footer {{ Auth::user()->color_fundo }}">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif
            @endif

            @if (Auth::user()->can('read: curso'))
            <!-- ./col -->
            <div class="col-lg-3 text-white">
                <!-- small box -->
                <div class="small-box bg-light" style="border: 4px solid  {{ Auth::user()->color_fundo }}">
                    <div class="inner">
                        <h3>{{ $totalcursos }}</h3>

                        <p class="text-uppercase">Cursos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-map"></i>
                    </div>
                    <a href="{{ route('web.cursos-index-ano-lectivo') }}" class="small-box-footer {{ Auth::user()->color_fundo }}">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif

            @if (Auth::user()->can('read: sala'))

            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light" style="border: 4px solid  {{ Auth::user()->color_fundo }}">
                    <div class="inner">
                        <h3>{{ $totalsalas }}</h3>
                        <p class="text-uppercase">Salas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <a href="{{ route('web.salas') }}" class="small-box-footer {{ Auth::user()->color_fundo }}">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            @endif

            @if (Auth::user()->can('read: disciplina'))

            <!-- ./col -->
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light" style="border: 4px solid  {{ Auth::user()->color_fundo }}">
                    <div class="inner">
                        <h3>{{ $totaldisciplinas }}</h3>

                        <p class="text-uppercase">Disciplinas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <a href="{{ route('web.disciplinas-index-ano-lectivo') }}" class="small-box-footer {{ Auth::user()->color_fundo }}">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif

            @if (Auth::user()->can('read: turma'))
            <!-- ./col -->
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light" style="border: 4px solid  {{ Auth::user()->color_fundo }}">
                    <div class="inner">
                        <h3>{{ $totalturmas }}</h3>

                        <p class="text-uppercase">Turmas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chalkboard"></i>
                    </div>
                    <a href="{{ route('web.turmas') }}" class="small-box-footer {{ Auth::user()->color_fundo }}">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif

            @if (Auth::user()->can('read: servicos'))
            <!-- ./col -->
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light" style="border: 4px solid  {{ Auth::user()->color_fundo }}">
                    <div class="inner">
                        <h3>@if ($totalServicos)
                            {{ $totalServicos }}
                            @else
                            0
                            @endif</h3>

                        <p class="text-uppercase">Serviços</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <a href="{{ route('web.calendarios') }}" class="small-box-footer {{ Auth::user()->color_fundo }}">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif


            @if (Auth::user()->can('read: matricula'))
            <!-- ./col -->
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light" style="border: 4px solid  {{ Auth::user()->color_fundo }}">
                    <div class="inner">
                        <h3>@if ($totalmatriculas)
                            {{ $totalmatriculas }}
                            @else
                            0
                            @endif</h3>
                        <p class="text-uppercase">Matriculas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <a href="{{ route('web.estudantes-matricula') }}" class="small-box-footer {{ Auth::user()->color_fundo }}">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            @endif

            @if (Auth::user()->can('read: matricula'))
            <!-- ./col -->
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light" style="border: 4px solid  {{ Auth::user()->color_fundo }}">
                    <div class="inner">
                        <h3>@if ($totalmatriculaseconfirmadoproximoano)
                            {{ $totalmatriculaseconfirmadoproximoano }}
                            @else
                            0
                            @endif</h3>
                        <p class="text-uppercase">Estudantes Matrículados e confirmados proxímo ano</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <a href="{{ route('web.estudantes-matriculados-confirmados') }}" class="small-box-footer {{ Auth::user()->color_fundo }}">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            @endif
        
            @if ($escola->modulo != 'Basico' && $escola->modulo == 'Avancado')
                @if (Auth::user()->can('read: inscricao'))
                <div class="col-lg-3 col-12 col-md-12">
                    <!-- small box -->
                    <div class="small-box bg-light" style="border: 4px solid  {{ Auth::user()->color_fundo }}">
                        <div class="inner">
                            <h3>@if ($totalinscritos)
                                {{ $totalinscritos }}
                                @else
                                0
                                @endif</h3>
    
                            <p class="text-uppercase">Candidatura/Inscrições</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <a href="{{ route('web.estudantes-inscricao') }}" class="small-box-footer {{ Auth::user()->color_fundo }}">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                @endif
            @endif

            @if ($escola->modulo != 'Basico' && $escola->modulo == 'Avancado')
                @if (Auth::user()->can('read: inscricao'))
                <div class="col-lg-3 col-12 col-md-12">
                    <!-- small box -->
                    <div class="small-box bg-light" style="border: 4px solid  {{ Auth::user()->color_fundo }}">
                        <div class="inner">
                            <h3>{{ $totalinscritosConfirmados }}</h3>
    
                            <p class="text-uppercase">Validar Candidaturas/Inscrição</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <a href="{{ route('web.estudantes-confirmacao-inscricao') }}" class="small-box-footer {{ Auth::user()->color_fundo }}">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                @endif
            @endif
            
            @if ($escola->modulo != 'Basico' && $escola->modulo == 'Avancado')
                @if (Auth::user()->can('read: inscricao'))
                <div class="col-lg-3 col-12 col-md-12">
                    <!-- small box -->
                    <div class="small-box bg-light" style="border: 4px solid  {{ Auth::user()->color_fundo }}">
                        <div class="inner">
                            <h3>@if ($totalinscritosAceites)
                                {{ $totalinscritosAceites }}
                                @else
                                0
                                @endif</h3>
    
                            <p class="text-uppercase">Candidatura/Inscrições aceites</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <a href="{{ route('web.estudantes-inscricao-aceites') }}" class="small-box-footer {{ Auth::user()->color_fundo }}">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                @endif
            @endif


            @if (Auth::user()->can('read: estudante'))

                <div class="col-lg-3 col-12 col-md-12">
                    <!-- small box -->
                    <div class="small-box bg-light" style="border: 4px solid  {{ Auth::user()->color_fundo }}">
                        <div class="inner">
                            <h3>@if ($totalestudantes)
                                {{ $totalestudantes }}
                                @else
                                0
                                @endif</h3>
    
                            <p class="text-uppercase">Estudantes</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <a href="{{ route('web.estudantes') }}" class="small-box-footer {{ Auth::user()->color_fundo }}">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
            @endif

            @if (Auth::user()->can('read: confirmacao'))
                <div class="col-lg-3 col-12 col-md-12">
                    <!-- small box -->
                    <div class="small-box bg-light" style="border: 4px solid  {{ Auth::user()->color_fundo }}">
                        <div class="inner">
                            <h3>{{ $totalconfirmacao }}</h3>
                            <p class="text-uppercase">Confirmações</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <a href="{{ route('web.estudantes-confirmacao') }}" class="small-box-footer {{ Auth::user()->color_fundo }}">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
            @endif           
            
            @if ($escola->processo_admissao_estudante == 'Prova' && $escola->modulo != 'Basico' && $escola->modulo == 'Avancado')
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light" style="border: 4px solid  {{ Auth::user()->color_fundo }}">
                    <div class="inner">
                        <h3 class="text-uppercase">Exame acesso</h3>

                        <p class="text-uppercase">Lista estudantes para Provas de Exames de Acesso</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <a href="{{ route('web.exames-acesso.index') }}" class="small-box-footer {{ Auth::user()->color_fundo }}">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            @endif
            
            @if ($escola->modulo != "Basico")
                @if (Auth::user()->can('create: cartao'))
                <div class="col-lg-3 col-12 col-md-12">
                    <!-- small box -->
                    <div class="small-box bg-light" style="border: 4px solid  {{ Auth::user()->color_fundo }}">
                        <div class="inner">
                            <h3 class="text-uppercase">Gerar</h3>
    
                            <p class="text-uppercase">Cartões</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <a href="{{ route('web.index.cartao') }}" class="small-box-footer {{ Auth::user()->color_fundo }}">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                @endif
                
                @if (Auth::user()->can('create: cartao'))
                <div class="col-lg-3 col-12 col-md-12">
                    <!-- small box -->
                    <div class="small-box bg-light" style="border: 4px solid  {{ Auth::user()->color_fundo }}">
                        <div class="inner">
                            <h3 class="text-uppercase">Emissão de Cartões</h3>
    
                            <p class="text-uppercase">Começar</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <a href="{{ route('web.emissao.cartao') }}" class="small-box-footer {{ Auth::user()->color_fundo }}">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                @endif
            @endif
            
            @if ($escola->modulo != 'Basico' && $escola->modulo == 'Avancado')
                <div class="col-lg-3 col-12 col-md-12">
                    <!-- small box -->
                    <div class="small-box bg-light" style="border: 4px solid  {{ Auth::user()->color_fundo }}">
                        <div class="inner">
                            <h3 class="text-uppercase">Controle</h3>
    
                            <p class="text-uppercase">Entrada e Saída de Estudantes</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <a href="{{ route('web.controle-entrada-saida-estudantes') }}" class="small-box-footer {{ Auth::user()->color_fundo }}">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            @endif

            @if ($escola->categoria == 'Privado' && $escola->modulo != "Basico")
                @if (Auth::user()->can('read: encarregado'))
                <div class="col-lg-3 col-12 col-md-12">
                    <!-- small box -->
                    <div class="small-box bg-light" style="border: 4px solid  {{ Auth::user()->color_fundo }}">
                        <div class="inner">
                            <h3>{{ $totalencarregados }}</h3>
    
                            <p class="text-uppercase">Encarregados</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <a href="{{ route('encarregados.index') }}" class="small-box-footer {{ Auth::user()->color_fundo }}">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                @endif
            @endif

            
            @if (Auth::user()->can('read: matricula'))
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light" style="border: 4px solid  {{ Auth::user()->color_fundo }}">
                    <div class="inner">
                        <h3>{{ $totalmatriculasEscola }}</h3>

                        <p class="text-uppercase">Processos dos estudantes</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <a href="{{ route('web.processos-estudantes') }}" class="small-box-footer {{ Auth::user()->color_fundo }}">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            @endif

            @if ($escola->categoria == 'Publico' && $escola->modulo != 'Basico' && $escola->modulo == 'Avancado')
                @if (Auth::user()->can('read: matricula'))
                <div class="col-lg-3 col-12 col-md-12">
                    <!-- small box -->
                    <div class="small-box bg-light" style="border: 4px solid  {{ Auth::user()->color_fundo }}">
                        <div class="inner">
                            <h3>{{ $totalescolasafilhares }}</h3>
    
                            <p class="text-uppercase">Escolas Afilhares</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <a href="{{ route('web.escolas-afilhares.index') }}" class="small-box-footer {{ Auth::user()->color_fundo }}">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                @endif
    
    
                @if (Auth::user()->can('read: matricula'))
                <div class="col-lg-3 col-12 col-md-12">
                    <!-- small box -->
                    <div class="small-box bg-light" style="border: 4px solid  {{ Auth::user()->color_fundo }}">
                        <div class="inner">
                            <h3>TODOS OS RUPES</h3>
    
                            <p class="text-uppercase">VALIADAÇÃO</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <a href="{{ route('web.rupes.index') }}" class="small-box-footer {{ Auth::user()->color_fundo }}">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                @endif
            @endif

        </div>

    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
@endsection
