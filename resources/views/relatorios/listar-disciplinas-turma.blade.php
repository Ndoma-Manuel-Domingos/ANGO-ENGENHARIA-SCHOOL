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
    <div class="container">
        <section class="content">
            <div class="container-fluid">
              <div class="row mt-5">
                <div class="col-12 col-md-12">
                  <!-- Main content -->
                  <div class="invoice p-3 mb-3">
                    
                    <div class="row invoice-info">
                      <!-- /.col -->
                      <div class="col-sm-6 invoice-col">
                        <h1 class="fs-4"><strong>{{ $escola->nome   }}</strong></h1>
                        <ul class="fs-5">
                            <li><strong>NIF: </strong>{{ $escola->documento }}</li>
                            <li><strong>E-mail: </strong>{{ $escola->site }}</li>
                            <li><strong>Endereço: </strong> LUANDA-ANGOLA</li>
                        </ul>
                      </div>
                      <!-- /.col -->
                    </div>
                    <!-- title row -->
                    <div class="row bg-light p-1">
                      <div class="col-12 col-md-12">
                        <h4>
                          <i class="fas fa-globe"></i> Lista de Disciplinas
                          <small class="float-right">Date: {{ date('d-m-Y') }}</small>
                          <p class="mt-2 mb-2" style="border-top: 1px solid rgb(201, 201, 201)">
                            <span class="fs-6">Curso: <strong style="border-bottom: 1px solid #fff">{{ $curso->curso }}</strong> - </span>
                            <span class="fs-6">Classe: <strong style="border-bottom: 1px solid #fff">{{ $classe->classes }}</strong> - </span>
                            <span class="fs-6">Sala: <strong style="border-bottom: 1px solid #fff">{{ $sala->salas }}</strong> - </span>
                            <span class="fs-6">Turno: <strong style="border-bottom: 1px solid #fff">{{ $turno->turno }}</strong> - </span>
                            <span class="fs-6">Turma: <strong style="border-bottom: 1px solid #fff">{{ $turma->turma }}</strong> - </span>
                            <span class="fs-6">Ano Lectivo: <strong style="border-bottom: 1px solid #fff">{{ $anolectivo->ano }}</strong></span>
                          </p>
                        </h4>
                      </div>
                      <!-- /.col -->
                    </div>
                    
                    <!-- Table row -->
                    <div class="row">
                      
                      @if ($disciplinas)
                        <div class="col-12 col-md-12">
                          <table  style="width: 100%" class="table projects  ">
                            <thead>
                            <tr>
                              <th>Cod</th>
                              <th>Disciplinas</th>
                              <th>Carga Horaria</th>
                              <th>Inicio</th>
                              <th>Final</th>
                              <th>Dias de Semanas</th>
                              <th>Pagamento Por Tempo</th>
                              <th>Total Tempo</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($disciplinas as $disciplina)
                                  @php
                                    $dis = (new App\Models\web\disciplinas\Disciplina())->findOrFail($disciplina->disciplinas_id);
                                  @endphp
                                  <tr>
                                      <td>{{ $dis->code }}</td>
                                      <td>{{ $dis->disciplina }}</td>
                                      <td>{{ $disciplina->carga }} min</td>
                                      <td>{{ $disciplina->inicio }}</td>
                                      <td>{{ $disciplina->final }}</td>
                                      <td>{{ $disciplina->dias_semana }}</td>
                                      <td>{{ $disciplina->pagamento_por_tempo }} kz</td>
                                      <td>{{ $disciplina->total_tempo }} t</td>
                                  </tr>
                                @endforeach
                              
                            </tbody>
                          </table>
                        </div>  
                      @endif
                      

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
    </div>
    <!-- ./wrapper -->

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









