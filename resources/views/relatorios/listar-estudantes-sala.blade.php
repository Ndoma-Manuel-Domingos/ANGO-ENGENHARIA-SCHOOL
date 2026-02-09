<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="pt-pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gestão Escolar | Principal</title>

    {{-- icono logotipo --}}
    <link rel="shortcut icon" href="{{asset('assets/images/eaviegas.png')}}">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}} ">
    <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.css')}} ">
    {{-- modal error and success --}}
    <link rel="stylesheet" href="{{asset('package/dist/sweetalert2.min.css')}} ">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}} ">

    <!-- BS Stepper -->
    <link rel="stylesheet" href="{{ asset('plugins/bs-stepper/css/bs-stepper.min.css') }}">

    {{-- libs  --}}
    <link rel="stylesheet" href="{{ asset('assets/aosmaster/aos.css') }} ">
    <link rel="stylesheet" href="{{ asset('assets/fontawesome/font-awesome.min.css') }} ">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/bootstrap.min.css.map') }}">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/bootstrap.min.css') }}">

    <style>
        ul,ol{
            list-style: none;
        }
        a{
            text-decoration: none;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini">

  <section class="content-fluid">
    <div class="container-fluid">
      <div class="row mt-5">
        <div class="col-12 col-md-12">
          <!-- Main content -->
          <div class="invoice p-3 mb-3">
            <div class="row invoice-info">
                <!-- /.col -->
                <div class="col-sm-6 invoice-col">
                  <h1 class="fs-4"><strong>Complexo Escolar Privado John Locke</strong></h1>
                  <ul class="fs-5">
                      <li><strong>NIF: </strong>89703678034</li>
                      <li><strong>E-mail: </strong> complexoescolarprivadojohnlocke@gmail.com</li>
                      <li><strong>Endereço: </strong> LUANDA-ANGOLA</li>
                  </ul>
                </div>
                <!-- /.col -->
            </div>
            <!-- title row -->
            <div class="row bg-dark p-4">
              <div class="col-12 col-md-12">
                <h4>
                  <i class="fas fa-globe"></i> Lista dos Estudantes da Sala Nº 5243
                  <small class="float-right">Date: 2/10/2014</small>
                </h4>
              </div>
              <!-- /.col -->
            </div>
            
            <!-- Table row -->
            <div class="row">
              <div class="col-12 col-md-12">
                <table  style="width: 100%" class="table projects  ">
                  <thead>
                  <tr>
                    <th>Cod</th>
                    <th>Estudante</th>
                    <th>Genero</th>
                    <th>Nascimento</th>
                    <th>Turno</th>
                    <th>Telefone</th>
                    <th>Data Cadastro</th>
                  </tr>
                  </thead>
                  <tbody>
                      @for ($i = 0; $i < 100; $i++)
                        <tr>
                            <td>{{ $i }}0054368</td>
                            <td>Ndoma MAnuel Domingos</td>
                            <td>Masculino</td>
                            <td>20-12-2000</td>
                            <td>Manhã</td>
                            <td>965-463-643</td>
                            <td>12-12-2021</td>
                        </tr>
                      @endfor
                    
                  </tbody>
                </table>
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
            <div class="row no-print">
                <div class="col-12 col-md-12">
                    <a href="invoice-print.html" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
                    
                </div>
            </div>

          </div>
          <!-- /.invoice -->
        </div><!-- /.col -->

    
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="{{asset('plugins/jquery/jquery.min.js')}} "></script>
    <!-- Bootstrap 4 -->
    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}} "></script>
    <script src="{{asset('plugins/toastr/toastr.min.js')}} "></script>
    {{-- modal erro and success --}}
    {{-- <script src="{{asset('package/dist/sweetalert2.all.min.js')}} "></script> --}}
    <script src="{{ asset('package/dist/sweetalert2.min.js') }} "></script>
    <!-- AdminLTE App -->
    <!-- BS-Stepper -->
    <script src="{{ asset('plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
    {{--  --}}
    <script src=" {{ asset('dist/js/adminlte.min.js') }} "></script>

    @yield('scripts')

    {{-- libs --}}
    <script src=" {{ asset('assets/aosmaster/aos.js') }} "></script>
    <script src=" {{ asset('assets/bootstrap/bootstrap.min.js') }} "></script>
    <script src=" {{ asset('assets/bootstrap/bootstrap.bundle.min.js') }} "></script>
    <script src=" {{ asset('assets/bootstrap/popper.min.js') }} "></script>

    <script>
        // BS-Stepper Init
        document.addEventListener('DOMContentLoaded', function () {
            window.stepper = new Stepper(document.querySelector('.bs-stepper'))
        })
    </script>
</body>
</html>









