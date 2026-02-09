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

        /*DEFAULT LOAD*/
        .ajax_load {
            display: none;
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999999;
        }

        .ajax_load_box {
            margin: auto;
            text-align: center;
            color: #ffffff;
            font-weight: bold;
            text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.5);
        }

        .ajax_load_box_circle {
            border: 16px solid #e3e3e3;
            border-top: 16px solid rgb(19, 170, 225);
            border-radius: 50%;
            margin: auto;
            width: 100px;
            height: 100px;

            -webkit-animation: spin 1.2s linear infinite;
            -o-animation: spin 1.2s linear infinite;
            animation: spin 1.2s linear infinite;
        }

        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">

    @php
        // Recuperar a escola logada no momento
        $admin = App\Models\User::findOrFail(Auth::user()->id);
        $escola = App\Models\Shcool::findOrFail($admin->shcools_id);
        $notificacaoes = App\Models\Notificacao::with('user')->where('status', '0')->orderBy('created_at', 'DESC')->where('shcools_id', $escola->id)->get();
        $total_notificacaoes = App\Models\Notificacao::with('user')->where('status', '0')->orderBy('created_at', 'DESC')->where('shcools_id', $escola->id)->count();
    @endphp

    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white text-white">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('est.home-estudante') }}" class="nav-link">Inicio</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="{{ route('web.logout-estudante') }}" class="nav-link">Sair</a>
                </li>
            </ul>
        </nav>
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="{{route('est.home-estudante')}}" class="brand-link">
                <img src="{{ asset('assets/images/eaviegas.png') }} " alt="{{ env('APP_NAME') }}" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">{{ env('APP_NAME') }}</span>
            </a>
            <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="{{ asset('assets/images/user.png') }}" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="{{ route('est.home-estudante') }}" class="d-block"> {{ Auth::user()->nome }}</a>
                </div>
            </div>

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    {{-- MENU GERAL --}}

                    <li class="nav-item" title="Dashboard">
                        <a href="{{ route('est.home-estudante') }}" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li> 
                  
                    <li class="nav-item" title="Privacidade">
                        <a href="{{ route('est.privacidade') }}" class="nav-link">
                            <i class="nav-icon fas fa-user-shield"></i>
                            <p>Privacidade</p>
                        </a>
                    </li> 
                    @if (Auth::user()->can('read: horario'))
                    <li class="nav-item" title="Horários">
                        <a href="{{ route('est.horarios') }}" class="nav-link">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>Horários</p>
                        </a>
                    </li> 
                    @endif
                   
                    <li class="nav-item" title="Solicitar de Transferências">
                        <a href="{{ route('est.solicitacoes-vagas') }}" class="nav-link">
                            <i class="nav-icon fas fa-exchange-alt"></i>
                            <p>Solicitar de Transferê.</p>
                        </a>
                    </li> 
                     @if (Auth::user()->can('read: documento'))
                    <li class="nav-item" title="Solicitar de Documentos">
                        <a href="{{ route('est.solicitacoes-declaracao') }}" class="nav-link">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>Solicitar de Documentos</p>
                        </a>
                    </li>
                    @endif 
                    
                    @if (Auth::user()->can('read: pagamento'))
                    <li class="nav-item" title="Meus Pagamentos">
                        <a href="{{ route('est.meus-pagamento-estudante') }}" class="nav-link">
                            <i class="nav-icon fas fa-credit-card"></i>
                            <p>Meus Pagamentos</p>
                        </a>
                    </li> 
                    @endif
                    
                    @if (Auth::user()->can('read: deposito'))
                    <li class="nav-item" title="Meus Depositos">
                        <a href="{{ route('est.meus-depositos-estudante') }}" class="nav-link">
                            <i class="nav-icon fas fa-money-bill-wave"></i>
                            <p>Meus Depositos</p>
                        </a>
                    </li> 
                    @endif
                    
                    @if (Auth::user()->can('read: materias'))
                    <li class="nav-item" title="Minhas Matérias">
                        <a href="{{ route('est.minhas-materias-estudante') }}" class="nav-link">
                            <i class="nav-icon fas fa-book-open"></i>
                            <p>Minhas Matérias</p>
                        </a>
                    </li> 
                    @endif
                    
                    @if (Auth::user()->can('read: comunicados'))
                    <li class="nav-item" title="Comunicados">
                        <a href="{{ route('est.meus-comunicados') }}" class="nav-link">
                            <i class="nav-icon fas fa-bullhorn"></i>
                            <p>Comunicados</p>
                        </a>
                    </li>
                    @endif

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
        // BS-Stepper Init
        $(function() {
            $('.select2').select2()
        });

        document.addEventListener('DOMContentLoaded', function() {
            window.stepper = new Stepper(document.querySelector('.bs-stepper'))
        })
    </script>
    @include('sweetalert::alert')
</body>
</html>
