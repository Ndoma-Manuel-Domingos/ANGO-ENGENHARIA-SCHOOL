<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestão Escolar | Criar conta</title>
    <link rel="shortcut icon" href="{{public_path('assets/images/eaviegas.png')}}">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fontawesome/font-awesome.min.css') }} ">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/bootstrap.min.css.map') }}">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/bootstrap.min.css') }}">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-form {
            width: 400px;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .login-form h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            margin-top: 30px;
        }

        .form-group input[type="submit"]:hover {
            background-color: #0069d9;
        }

        .error-message {
            color: red;
            font-size: 14px;
            font-weight: bold;
        }

        .video-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            overflow: hidden;
            opacity: 0.3;
            z-index: -1;
        }

        #background-video {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            background-color: rgba(255, 255, 255, 0.8);
        }

    </style>
</head>
<body class="hold-transition login-page bg-light">
    <div class="video-container">
        <video autoplay muted loop id="background-video">
            <source src="{{ asset('assets/images/GESTAO ESCOLAR.mp4') }}" type="video/mp4">
        </video>
    </div>

    <div class="login-form">
        <h2><a href="{{ route('site.home-principal') }}" style="text-decoration: none;color: #303235">{{ env('APP_NAME') }}</a></h2>
        <form action="{{route('criar-conta-store')}}" method="post">
            @csrf
            @if(session()->has('danger'))
            <div class="alert alert-danger">
                {{ session()->get('danger') }}
            </div>
            @endif
            <div class="row">
                <div class="col-md-12 col-12">
                    <label for="nome_escola" class="form-label">Nome da Escola</label>
                    <div class="input-group mb-3">
                        <input type="text" id="nome_escola" class="form-control form-control" placeholder="Nome da Escola" name="nome_escola">
                    </div>
                    @error('nome_escola')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-12 col-12">
                    <label for="nif_escola" class="form-label">NIF</label>
                    <div class="input-group mb-3">
                        <input id="nif_escola" type="text" class="form-control form-control" placeholder="NIF da Escola" name="nif_escola">
                    </div>
                    @error('nif_escola')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-12 col-12">
                    <label for="numero_telefonico" class="form-label">Telefone</label>
                    <div class="input-group mb-3">
                        <input id="numero_telefonico" type="text" class="form-control form-control" placeholder="Número Telefonico" name="numero_telefonico">
                    </div>
                    @error('numero_telefonico')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-12 col-12">
                    <label for="email" class="form-label">E-mail</label>
                    <div class="input-group mb-3">
                        <input type="email" id="email" class="form-control form-control" placeholder="E-mail" name="email">
                    </div>
                    @error('email')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-12 col-12">
                    <label for="password" class="form-label">Senha</label>
                    <div class="input-group mb-3">
                        <input id="password" type="password" class="form-control form-control" placeholder="Senha" name="password">
                    </div>
                    @error('password')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-12 col-12">
                    <label for="" class="form-label">Conferir Senha</label>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control form-control" placeholder="Confirmar a Senha" name="password2">
                    </div>
                    @error('password2')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <div class="row">
                <!-- /.col -->
                <div class="col-12 mb-3 mt-3 text-center ">
                    <button type="submit" class="btn btn-primary w-100">Criar Conta</button>
                </div>

                <div class="text-center">
                    <p class=" my-2"><a href="{{ route('login') }}" class="text-center text-decoration-none">Usar Minha Conta</a></p>
                    <p class="text-sm"> Desenvolvido pela {{ env('APP_NAME') }}</p>
                </div>
                <!-- /.col -->
            </div>
        </form>
    </div>

    <script src="{{asset('plugins/jquery/jquery.min.js')}} "></script>
    <script src=" {{ asset('assets/bootstrap/bootstrap.min.js') }} "></script>
    @include('sweetalert::alert')
</body>
</html>
