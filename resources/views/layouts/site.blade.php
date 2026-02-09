<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $shcools->cabecalho1 }}</title>
    <link rel="icon" type="image/png" href="/uploads/logos/{{$shcools->logotipo}}">
    <link rel="stylesheet" href="{{ asset('package/dist/sweetalert2.min.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/aosmaster/aos.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
        .top-bar {
            height: 90px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
        }
        .navbar {
            background-color: #006699;
        }
        .navbar a {
            color: white;
        }
        .carousel-inner img {
            height: 600px;
            object-fit: cover;
        }
        .gallery img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .gallery img:hover {
            transform: scale(1.2);
            z-index: 10;
            position: relative;
        }
        .footer {
            background-color: #343a40;
            color: white;
            padding: 20px 0;
            text-align: center;
        }
        .footer a {
            color: #f8f9fa;
            text-decoration: none;
        }
        .footer .col-md-3 {
            margin-bottom: 20px;
        }
        .modal img {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">

        <div class="top-bar">
            <a href="{{ route('site.home-principal') }}">
                @if ($shcools->logotipo)
                <img src="/uploads/logos/{{$shcools->logotipo}}" alt="Logotipo" height="70">
                @endif
            </a>
            <h5 class="text-primary">Aprender, Crescer, Vencer</h5>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand text-uppercase" href="{{ route('site.home-principal') }}">{{ $shcools->cabecalho1 }}</a>
            <div class="flex justify-between">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item"><a href="{{ route('site.home-principal') }}" class="nav-link">Início</a></li>
                        <li class="nav-item"><a href="{{ route('site.cursos-disponiveis') }}" class="nav-link">Cursos</a></li>
                        <li class="nav-item"><a href="{{ route('site.candidatura-inscricoes') }}" class="nav-link">Candidaturas</a></li>
                        <li class="nav-item"><a href="{{ route('app.login-estudante') }}" class="nav-link"><i class="fas fa-user-graduate"></i> Aluno</a></li>
                        <li class="nav-item"><a href="{{ route('portal-professor') }}" class="nav-link"><i class="fas fa-user"></i> Professor</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    
    @yield('content')
            
    <footer class="footer mt-5">
        <div class="container">
            <div class="row text-left">
                <div class="col-md-3">
                    <h5>Contato</h5>
                    <p>Telefone: (XX) XXXX-XXXX</p>
                    <p>Email: contato@escola.com</p>
                    <p>Endereço: Rua Exemplo, 123 - Cidade, Estado</p>
                </div>
                <div class="col-md-3">
                    <h5>Cursos</h5>
                    <ul class="list-unstyled">
                        @if (count($cursos) != 0)
                            @foreach ($cursos as $curso)
                                <li><a href="{{ route('site.cursos-disponiveis-detalhe', ['req_id' => $shcools->req_id, 'curso_id' => $curso->id]) }}">{{ $curso->curso ? $curso->curso->curso : "" }}</a></li>
                            @endforeach 
                        @endif
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Links Úteis</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('site.home-principal') }}">História da Escola</a></li>
                        <li><a href="{{ route('site.cursos-disponiveis') }}">Lista de Cursos</a></li>
                        <li><a href="{{ route('app.login-estudante') }}">Área do Aluno</a></li>
                        <li><a href="{{ route('portal-professor') }}">Área do Professor</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Redes Sociais</h5>
                    <p><a href="#">Facebook</a></p>
                    <p><a href="#">Instagram</a></p>
                    <p><a href="#">LinkedIn</a></p>
                </div>
            </div>
            <div class="text-center mt-3">
                <p>© 2025 Escola. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    {{-- modal erro and success --}}
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('package/dist/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/aosmaster/aos.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.gallery img').click(function(){
                $('#imagemModalSrc').attr('src', $(this).attr('src'));
                $('#imagemModal').modal('show');
            });
        });

        document.addEventListener("keydown", function(event) {
            if (event.ctrlKey && (event.key === "a" || event.key === "A" || event.key === "q" || event.key === "Q")) { 
                event.preventDefault();
                window.location.href = "{{ route('login') }}"; // Abre na mesma aba
            }
        });
        
        document.addEventListener("keydown", function(event) {
            if (event.ctrlKey && event.shiftKey && (event.key === "M" || event.key === "m")) {
                event.preventDefault();
                window.location.href = "{{ route('login-admin') }}";
            }
        });
        
        document.addEventListener("keydown", function(event) {
            if (event.ctrlKey && event.shiftKey && (event.key === "P" || event.key === "p")) {
                event.preventDefault();
                window.location.href = "{{ route('login-provincial') }}";
            }
        });
        
        document.addEventListener("keydown", function(event) {
            if (event.ctrlKey && event.shiftKey && (event.key === "k" || event.key === "K")) {
                event.preventDefault();
                window.location.href = "{{ route('login-municipal') }}";
            }
        });
        
        document.addEventListener("keydown", function(event) {
            if (event.ctrlKey && event.shiftKey && (event.key === "E" || event.key === "e")) {
                event.preventDefault();
                window.location.href = "{{ route('app.login-estudante') }}";
            }
        });
        
        document.addEventListener("keydown", function(event) {
            if (event.ctrlKey && event.shiftKey && (event.key === "T" || event.key === "t" || event.key === "L" || event.key === "l")) {
                event.preventDefault();
                window.location.href = "{{ route('portal-professor') }}";
            }
        });

    </script>
    
    @yield('scripts')
    
    <script>
        AOS.init();
    </script>
    
    @include('sweetalert::alert')
</body>
</html>