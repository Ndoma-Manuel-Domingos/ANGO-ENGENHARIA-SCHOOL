<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão Escolar | Portal da Escola</title>
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
        
      .spinner {
            margin: 20px auto;
            width: 40px;
            height: 40px;
            border: 4px solid #ccc;
            border-top-color: #007bff;
            border-radius: 50%;
            animation: spin 1s infinite linear;
        }

        @keyframes spin {
           to { transform: rotate(360deg); }
        }

    </style>
</head>

<body>

    <div class="video-container">
        <video autoplay muted loop id="background-video">
            <source src="{{ asset('assets/images/GESTAO ESCOLAR.mp4') }}" type="video/mp4">
        </video>
    </div>

    <div class="login-form">
        <h2 class="text-center my-4">{{ env('APP_NAME') }}</h2>
        
        <h3>Verifique seu E-mail 📩</h3>
        <p>Sua conta foi criada com sucesso!</p>
        <p>Enviamos um link de verificação para o seu e-mail.</p>
        <p>Por favor, confirme sua conta para continuar.</p>
    
        <div class="spinner"></div>
        <p>Estamos aguardando a confirmação...</p>
        
    </div>
    <script src="{{asset('plugins/jquery/jquery.min.js')}} "></script>
    <script src=" {{ asset('assets/bootstrap/bootstrap.min.js') }} "></script>

</body>

</html>
