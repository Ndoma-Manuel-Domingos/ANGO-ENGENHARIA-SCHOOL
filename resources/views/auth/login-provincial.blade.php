<!DOCTYPE html>
<html lang="pt-pt">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestão Escolar | Portal do Provincial</title>
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

<body>

  <div class="video-container">
    <video autoplay muted loop id="background-video">
      <source src="{{ asset('assets/images/GESTAO ESCOLAR.mp4') }}" type="video/mp4">
    </video>
  </div>

  <div class="login-form">
    <h2><a href="{{ route('site.home-principal') }}" style="text-decoration: none;color: #303235">{{ env('APP_NAME') }}</a></h2>
    <form action="{{route('login-provincial-post')}}" method="post">
      @csrf
      @if(session()->has('danger'))
      <div class="alert alert-danger">
        {{ session()->get('danger') }}
      </div>
      @endif
      {{-- <span class="error-message">3423523</span> --}}
      <div class="form-group">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control @error('user') is-invalid @enderror" id="username" name="user"
          placeholder="Enter username">
        @error('user')
        <span class="error-message">{{ $message }}</span>
        @enderror
      </div>
      <div class="form-group">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
          name="password" placeholder="Enter password">
        @error('password')
        <span class="error-message">{{ $message }}</span>
        @enderror
      </div>
      <div class="form-group">
        <input type="submit" class="btn btn-primary" value="Login">
      </div>
    </form>
  </div>
  <script src="{{asset('plugins/jquery/jquery.min.js')}} "></script>
  <script src=" {{ asset('assets/bootstrap/bootstrap.min.js') }} "></script>

  @include('sweetalert::alert')
</body>

</html>