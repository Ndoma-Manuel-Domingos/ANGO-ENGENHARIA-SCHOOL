<!DOCTYPE html>

<html lang="pt-pt">
<head>
    @include('headers')

    <style>
        ul,
        ol {
            list-style: none;
        }

        a {
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
            -webkit-animation: move-forever 25s cubic-bezier(.55, .5, .45, .5) infinite;
            animation: move-forever 25s cubic-bezier(.55, .5, .45, .5) infinite;
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

    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('prof.home-profs') }}" class="nav-link">Inicio</a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link h4" data-toggle="dropdown" href="#">
                        <i class="far fa-user"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right text-center">
                        <div class="bg-info">
                            <img src="{{ asset('assets/images/user.png') }}" alt="Logotipo" class="img-size-64 ml-auto img-circle m-4" style="text-align: center;" />
                        </div>
                        <div>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('prof.privacidade') }}" class="dropdown-item">
                                <i class="fas fa-user-edit mr-2"></i> <span>Actualizar Dados</span>
                            </a>

                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="{{ route('web.logout-professor') }}">
                                <i class="fas fa-sign-out-alt"></i> Termissão Sessão
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        </nav>
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="{{route('prof.home-profs')}}" class="brand-link">
                <img src="{{ asset('assets/images/logo.png') }} " alt="{{ env('APP_NAME') }}" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">{{ env('APP_NAME') }}</span>
            </a>
            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="{{ asset('assets/images/user.png') }}" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="{{ route('prof.home-profs') }}" class="d-block"> {{ Auth::user()->nome }}</a>
                    </div>
                </div>

                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        {{-- MENU GERAL --}}
                        <li class="nav-item">
                            <a href="{{ route('prof.home-profs') }}" class="nav-link">
                                <i class="nav-icon fas fa-home"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('prof.privacidade') }}" class="nav-link">
                                <i class="nav-icon fas fa-lock"></i>
                                <p>Privacidade</p>
                            </a>
                        </li>

                        @if (Auth::user()->can('read: escola'))
                        <li class="nav-item">
                            <a href="{{ route('prof.escolas') }}" class="nav-link">
                                <i class="nav-icon fas fa-university"></i>
                                <p>Escolas</p>
                            </a>
                        </li>
                        @endif

                        @if (Auth::user()->can('read: turma'))
                        <li class="nav-item">
                            <a href="{{ route('prof.turmas') }}" class="nav-link">
                                <i class="nav-icon fas fa-digital-tachograph"></i>
                                <p>Turmas</p>
                            </a>
                        </li>
                        @endif

                        @if (Auth::user()->can('read: horario'))
                        <li class="nav-item">
                            <a href="{{ route('prof.horarios') }}" class="nav-link">
                                <i class="nav-icon fas fa-digital-tachograph"></i>
                                <p>Horários</p>
                            </a>
                        </li>
                        @endif

                        @if (Auth::user()->can('read: estudante'))
                        <li class="nav-item">
                            <a href="{{ route('prof.estudantes') }}" class="nav-link">
                                <i class="nav-icon fas fa-user-graduate"></i>
                                <p>Estudantes</p>
                            </a>
                        </li>
                        @endif

                        @if (Auth::user()->can('read: materias'))
                        <li class="nav-item">
                            <a href="{{ route('portal-professor-minhas-materias.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-book"></i>
                                <p>Minhas Matérias</p>
                            </a>
                        </li>
                        @endif

                        @if (Auth::user()->can('read: documento'))
                        <li class="nav-item">
                            <a href="{{ route('prof.minhas-solicitacoes') }}" class="nav-link">
                                <i class="nav-icon fas fa-book"></i>
                                <p>Minhas Solicitações</p>
                            </a>
                        </li>
                        @endif

                        @if (Auth::user()->can('read: comunicados'))
                        <li class="nav-item">
                            <a href="{{ route('prof.meus-comunicados') }}" class="nav-link">
                                <i class="nav-icon fas fa-envelope"></i>
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
        function progressBeforeSend(title = "Processando...", text = "Por favor, aguarde.", icon = 'info') {
            Swal.fire({
                title: title
                , text: text
                , icon: icon
                , allowOutsideClick: false
                , showConfirmButton: false
                , didOpen: () => {
                    Swal.showLoading();
                }
            , });
        }

        function showMessage(title, text, icon) {
            Swal.fire({
                icon: icon
                , title: title
                , text: text
                , toast: true
                , position: 'top-end'
                , showConfirmButton: false
                , timer: 5000
            , });
        }

        var timerID = null;
        var timerRunning = false;

        function stopclock() {
            if (timerRunning)
                clearTimeout(timerID);
            timerRunning = false;
        }

        function showtime() {
            var now = new Date();
            var hours = now.getHours();
            var minutes = now.getMinutes();
            var seconds = now.getSeconds()

            var timeValue = "" + ((hours > 24) ? hours - 24 : hours)

            if (timeValue == "0") timeValue = 24;
            timeValue += ((minutes < 10) ? ":0" : ":") + minutes
            timeValue += ((seconds < 10) ? ":0" : ":") + seconds
            timeValue += (hours >= 24) ? "" : ""
            document.clock.face.value = timeValue;
            timerID = setTimeout("showtime()", 1000);
            timerRunning = true;
        }

        function startclock() {
            stopclock();
            showtime();
        }
        window.onload = startclock;
        // End -->

    </script>

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
