<!DOCTYPE html>

<html  lang="pt-pt">
<head>
    @include('headers')
    <style>
        ul,ol{
            list-style: none;
        }
        a{
            text-decoration: none;
        }
        /* Ondas rodape */
        .waves {
            position: relative;
            width: 100%;
            height: 10vh;
            min-height: 80px;
            max-height: 150px;
            margin-bottom: -7px;
        }
        .parallax>use {
            -webkit-animation: move-forever 25s cubic-bezier(.55,.5,.45,.5) infinite;
            animation: move-forever 25s cubic-bezier(.55,.5,.45,.5) infinite;
        }
        .parallax>use:first-child {
            -webkit-animation-delay: -2s;
            animation-delay: -2s;
            -webkit-animation-duration: 7s;
            animation-duration: 7s;
        }
        .parallax>use:nth-child(2) {
            -webkit-animation-delay: -3s;
            animation-delay: -3s;
            -webkit-animation-duration: 10s;
            animation-duration: 10s;
        }
        .parallax>use:nth-child(3) {
            -webkit-animation-delay: -4s;
            animation-delay: -4s;
            -webkit-animation-duration: 13s;
            animation-duration: 13s;
        }
        .parallax>use:nth-child(4) {
            -webkit-animation-delay: -5s;
            animation-delay: -5s;
            -webkit-animation-duration: 20s;
            animation-duration: 20s;
        }
        @keyframes move-forever {
            0% {
                -webkit-transform: translate3d(-90px, 0, 0);
                -ms-transform: translate3d(-90px, 0, 0);
                transform: translate3d(-90px, 0, 0);
            }
            100% {
                -webkit-transform: translate3d(85px, 0, 0);
                -ms-transform: translate3d(85px, 0, 0);
                transform: translate3d(85px, 0, 0);
            }
        }

    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">

    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('home-provincial') }}" class="nav-link">Inicio</a>
                </li>
            </ul>
            @php
                $notificacaoes = App\Models\Notificacao::with('user')->where('status', '0')->where('type_destino', 'ministerio')->orderBy('created_at', 'DESC')->limit(5)->get();
                $total_notificacaoes = App\Models\Notificacao::with('user')->where('status', '0')->where('type_destino', 'ministerio')->orderBy('created_at', 'DESC')->count();
            @endphp
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Messages Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#" title="NOTIFICAÇÕES">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-warning navbar-badge">{{ $total_notificacaoes }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        @foreach ($notificacaoes as $item)
                            <a href="{{ route('web.admin.notificacoes', ['notification' => $item->id]) }}" class="dropdown-item">
                                <!-- Message Start -->
                                <div class="media">
                                    <img src="{{ asset('assets/images/user.png') }}" alt="User Avatar" class="img-size-50 img-circle mr-3">
                                    <div class="media-body">
                                        <h3 class="dropdown-item-title text-capitalize">
                                            {{ Str::limit($item->enviador($item->type_enviado, $item->user_id), 25, ' (...)') }}
                                            <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                                        </h3>
                                        <p class="text-sm">{{ Str::limit($item->notificacao, 20, ' (...)')  }}</p>
                                        <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> {{ date('d-m-Y', strtotime($item->created_at)) }} ás {{ date('H:i:s', strtotime($item->created_at)) }}</p>
                                    </div>
                                </div>
                                <!-- Message End -->
                            </a>
                            <div class="dropdown-divider"></div>
                        @endforeach
                        <a href="{{ route('web.admin.notificacoes') }}" class="dropdown-item dropdown-footer">Ver todas notificações</a>
                    </div>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('web.logout-provincial') }}" class="nav-link">Sair</a>
                </li>
            </ul>
        </nav>
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="{{route('home-provincial')}}" class="brand-link">
                <img src="{{ asset('assets/images/eaviegas.png') }} " alt="{{ env('APP_NAME') }}" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">{{ env('APP_NAME') }}</span>
            </a>
            <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="{{ public_path('assets/images/user.png') }}" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="{{ route('home-provincial') }}" class="d-block"> {{ Auth::user()->nome }}</a>
                </div>
            </div>

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    {{-- MENU GERAL --}}
                    <li class="nav-item">
                        <a href="{{route('home-provincial')}}" class="nav-link">
                            <i class="nav-icon fas fa-home"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                Entidades
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('listagem-escola-provincial', Crypt::encrypt(null)) }}" class="nav-link">
                                    <i class="fas fa-school nav-icon"></i>
                                    <p>Escolas</p>
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a href="{{ route('app.listagem-estudantes-provincial-geral') }}" class="nav-link">
                                    <i class="fas fa-user-graduate nav-icon"></i>
                                    <p>Estudantes</p>
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a href="{{ route('app.professores-provincial') }}" class="nav-link">
                                    <i class="fas fa-user-tie nav-icon"></i>
                                    <p>Professores</p>
                                </a>
                            </li>
                            
                        </ul>
                    </li> 
                    @if (Auth::user()->can('create: professores') && Auth::user()->can('read: professores'))
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-folder"></i>
                            <p>
                                Recursos Humanos
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                        
                            <li class="nav-item">
                                <a href="{{ route('web.funcionarios-provincial-controlo') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Controle</p>
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a href="{{ route('web.funcionarios-provincial') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Funcionários</p>
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a href="{{ route('web.departamento-provincial') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Departamentos</p>
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a href="{{ route('web.cargos-provincial') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Cargos</p>
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a href="{{ route('grafico.provincial-funcionarios') }}"
                                    class="nav-link">
                                    <i class="fas fa-chart-line nav-icon"></i>
                                    <p>Estatística Funcionários</p>
                                </a>
                            </li>
                        </ul>
                    </li> 
                    @endif
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>
                                Tabela de Apoio
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                        
                            <li class="nav-item">
                                <a href="{{ route('web.ensinos', ['loyout' => 'provinciais']) }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Ensinos</p>
                                </a>
                            </li> 
                            
                            <li class="nav-item">
                                <a href="{{ route('web.ensinos-classes', ['loyout' => 'provinciais']) }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Ensinos Classes</p>
                                </a>
                            </li> 
                            
                            <li class="nav-item">
                                <a href="{{ route('web.especialidades', ['loyout' => 'provinciais']) }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Especialidades</p>
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a href="{{ route('web.universidades', ['loyout' => 'provinciais']) }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Universidades</p>
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a href="{{ route('web.categorias', ['loyout' => 'provinciais']) }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Categorias</p>
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a href="{{ route('web.laboratorios', ['loyout' => 'provinciais']) }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Laboratórios</p>
                                </a>
                            </li>
                             
                            <li class="nav-item">
                                <a href="{{ route('web.municipios-provincial') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                    <p>Municípios</p>
                                </a>
                            </li> 
                            
                            <li class="nav-item">
                                <a href="{{ route('web.distrito-provincial') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                    <p>Distritos</p>
                                </a>
                            </li> 
                        </ul>
                    </li> 
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>
                                Configurações
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('app.privacidade-provincial') }}" class="nav-link">
                                    <i class="fas fa-lock nav-icon"></i>
                                    <p>Privacidade</p>
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a href="{{ route('app.provincial-utilizadores-index') }}" class="nav-link">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>Utilizadores</p>
                                </a>
                            </li>
                        </ul>
                    </li> 
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p>
                                Planificação Estatísticas
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                        
                            <li class="nav-item">
                                <a href="{{ route('app.planificacao-provincial-controlo') }}" class="nav-link">
                                    <i class="nav-icon fas fa-book"></i>
                                    <p>Controlo Geral</p>
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a href="{{ route('app.planificacao-provincial-mini-pauta') }}" class="nav-link">
                                    <i class="nav-icon fas fa-book"></i>
                                    <p>Mini Pautas</p>
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a href="{{ route('app.planificacao-provincial-mini-pauta-geral') }}" class="nav-link">
                                    <i class="fas fa-book nav-icon"></i>
                                    <p>Pautas geral</p>
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a href="{{ route('app.planificacao-provincial-mapa-aproveitamento') }}" class="nav-link">
                                    <i class="nav-icon fas fa-book"></i>
                                    <p>Mapas Aproveitamentos</p>
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a href="{{ route('provincial-grafico-turma') }}"
                                    class="nav-link">
                                    <i class="fas fa-chart-line nav-icon"></i>
                                    <p>Estatística Estudantes</p>
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a href="{{ route('app.provincial-estatistica-turmas-unica') }}"
                                    class="nav-link">
                                    <i class="fas fa-chart-line nav-icon"></i>
                                    <p>Estatística Pautas</p>
                                </a>
                            </li>
                            
                              
                            <li class="nav-item">
                                <a href="{{ route('app.provincial-fornulario-ficha') }}"
                                    class="nav-link">
                                    <i class="fas fa-chart-line nav-icon"></i>
                                    <p>Formulários e Fichas</p>
                                </a>
                            </li>
                            
                        </ul>
                    </li> 
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-user-tie"></i>
                            <p>
                                Gestão de Professores
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('app.provincial-gestao-professores-index') }}" class="nav-link">
                                <i class="fas fa-circle nav-icon"></i>
                                <p>Lista de Professores</p>
                                </a>
                            </li> 

                            <li class="nav-item">
                                <a href="{{ route('web.transferencia-escolares-provincial-professores') }}" class="nav-link">
                                <i class="fas fa-circle nav-icon"></i>
                                <p>Lista Transferências</p>
                                </a>
                            </li> 

                            <li class="nav-item">
                                <a href="{{ route('app.dispanho-professores-provincial-index') }}" class="nav-link">
                                    <i class="fas fa-circle nav-icon"></i>
                                    <p>Distribuição de Professores</p>
                                </a>
                            </li> 

                            <li class="nav-item">
                                <a href="{{ route('web.transferencia-escola-provincial-professores') }}" class="nav-link">
                                    <i class="fas fa-circle nav-icon"></i>
                                    <p>Transferir Professores</p>
                                </a>
                            </li>
                        </ul>
                    </li> 
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-home"></i>
                            <p>
                                Direcções
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('direccoes-municipais.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Direcção Municipal</p>
                                </a>
                            </li>
                        </ul>
                    </li> 
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-key"></i>
                            <p>
                                Activadores
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('web.activadores-candidatura-professores') }}" class="nav-link">
                                    <i class="fas fa-user nav-icon"></i>
                                    <p>Candidatura professores</p>
                                </a>
                                
                                <a href="{{ route('web.activadores-candidatura-estudantes') }}" class="nav-link">
                                    <i class="fas fa-user nav-icon"></i>
                                    <p>Candidatura Estudantes</p>
                                </a>
                                
                                <a href="{{ route('web.controlo-lancamento-notas.index') }}" class="nav-link">
                                    <i class="fas fa-book nav-icon"></i>
                                    <p>Lançamento de Notas</p>
                                </a>
                            </li>
                        </ul>
                    </li> 
                </ul>
            </nav>
            </div>
        </aside>

        <div class="content-wrapper" style="background-color: none;background-attachment: fixed; background-repeat: no-repeat; background-size: cover; background-image: url('{{ asset('dist/img/aluno1.jpg') }}');">
            @yield('content')
        </div>

        <footer class="main-footer bg-dark">
            <svg viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto" class="waves px-0 mx-0" style="height: 90px">
                <defs>
                    <path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z"></path>
                </defs>
                <g class="parallax">
                    <use href="#gentle-wave" x="48" y="0" fill="rgba(255,255,255,0.7"></use>
                    <use href="#gentle-wave" x="48" y="3" fill="rgba(255,255,255,0.5)"></use>
                    <use href="#gentle-wave" x="48" y="5" fill="rgba(255,255,255,0.3)"></use>
                    <use href="#gentle-wave" x="48" y="7" fill="#fff"></use>
                </g>
            </svg>
        </footer>
    </div>

    @include('footer')

    @yield('scripts')

    <script>
        var timerID = null;
        var timerRunning = false;

        function stopclock (){
            if (timerRunning) {
                clearTimeout(timerID);
                timerRunning = false;
            }
        }

        function showtime () {
            var now = new Date();
            var hours = now.getHours();
            var minutes = now.getMinutes();
            var seconds = now.getSeconds()
            
            var timeValue = "" + ((hours >24) ? hours -24 :hours)

            if (timeValue == "0") timeValue = 24;
                timeValue += ((minutes < 10) ? ":0" : ":") + minutes
                timeValue += ((seconds < 10) ? ":0" : ":") + seconds
                timeValue += (hours >= 24) ? "" : ""
                document.clock.face.value = timeValue;
                timerID = setTimeout("showtime()",1000);
                timerRunning = true;
        }

        function startclock() {
            stopclock();
            showtime();
        }
        window.onload=startclock;
        // End -->
    </script>  

    <script>

        // BS-Stepper Init
        $(function (){
            $('.select2').select2()
        });
        
        document.addEventListener('DOMContentLoaded', function () {
            window.stepper = new Stepper(document.querySelector('.bs-stepper'))
        })
    </script>
    @include('sweetalert::alert')
</body>
</html>
