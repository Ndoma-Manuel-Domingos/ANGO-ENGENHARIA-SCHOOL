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
    <title>Lista Estudantes Curso | Gestão Escolar</title>

    <style type="text/css">
        *{
          margin: 0;
          padding: 0;
          box-sizing: border-box;
        }
        ul,ol{
          list-style: none;
        }
        a{
          text-decoration: none;
        }

        body{
          border: 20px solid rgb(162, 170, 6);
          padding: 30px;
          font-family: Arial, Helvetica, sans-serif;
        }
        h1{
          font-size: 10pt;
          margin-bottom: 4px;
        }


        h2{
          font-size: 9pt;
          font-family: arial;
        }
    
        .titulo{
          font-size: 10pt;
          text-align: center;
          font-family: arial;
        }
    
        p{
          margin-bottom: 20px;
          line-height: 20px;
          font-family: arial;
        }
    
        table{
          width: 100%;
          text-align: left;
          border-spacing: 0;	
          margin-bottom: 10px;
          border: 1px solid #fff;
          font-size: 10pt;
          
          font-family: arial;
        }
        thead{
          background-color: #eaeaea;
          border-bottom: 1px solid #006699;
    
        }
        th, td{
          padding: 10px;
          border: 1px solid #000;
          
          font-family: arial;
        }
    
        .border{
          border: 1px solid #eaeaea;
        }
    
        .flex{
          display: flex;
          flex-direction: row;
          justify-content: space-between;
          align-content: center;
          align-items: center;
        }
    
        .col{
          width: 25%;
          padding-left: 2px;
          padding-right: 2px;
        }
    
        .cols{
          width: 50%;
          padding-left: 2px;
          padding-right: 2px;
        }
    
        .col-2{
          width: 10%;
          padding-left: 2px;
          padding-right: 2px;
        }
    
        .col-8{
          width: 80%;
          padding-left: 2px;
          padding-right: 2px;
        }
    
        .logo{
          height: 80px;
          width: 80px;
          /*border-radius: 300px;*/
          /*padding: 30px; */
        }
        .ml{
          margin-left: 80px;
        }
        .text-center{
          text-align: center;
          
          font-family: arial;
        }

        #header{
            width: 100%;
            float: left;
            text-align: center;
        }

        .logo{
            width: 100%;
            height: 70px;
            text-align: center;
        }
        .texto-header{
            width: 100%;
            height: 170px;
        }


    </style>
</head>
<body class="hold-transition sidebar-mini" style='background-position: center;background-repeat: no-repeat;background-attachment: 
fixed; background-size: cover; margin: 20px; background-image: url({{ public_path('assets/images/'. $escola->logotipo_documentos ?? '') }});'>

  <div id="header">
    <div class="logo">
      <img src="{{ public_path('assets/images/'. $escola->logotipo ?? '') }}" alt="" style="text-align: center;height: 60px;width: 60px;margin-bottom: 40px;">
    </div>   

    <div class="texto-header">
      <div>
        @if ($escola->categoria == 'Privado')
        <h1 class="fs-2 text-center"><strong>{{ $escola->nome }}</strong></h1>    
        <h1 class="fs-5"><strong>LISTA DOS ESTUDANTES DO CURSO DE {{ $curso->curso }}  {{ $anolectivo->ano }}</strong></h1>
        @else
        <h1 class="fs-5 text-center"><strong>República de Angola</strong></h1>
        <h1 class="fs-5 text-center"><strong>Governo Provincial de Luanda</strong></h1>
        <h1 class="fs-5 text-center"><strong>Gabinete Provincial da Educação</strong></h1>
        <h1 class="fs-5 text-center"><strong>{{ $escola->nome }}</strong></h1> 
        <h1 class="fs-5"><strong>LISTA DOS ESTUDANTES DO CURSO DE {{ $curso->curso }}  {{ $anolectivo->ano }}</strong></h1>
        @endif
      </div>
    </div>

  </div>    
  
    <div class="row" style="margin-top: 170px">
      <div class="col-12 table-responsive">
        @if ($matriculas)
          <table  style="width: 100%" class="table table-stripeds">
            <thead>
            <tr>
              <th>Cod</th>
              <th>Estudante</th>
              <th>Genero</th>
              <th class="bg-dark">Curso</th>
              <th>Classes</th>
              <th>Turno</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($matriculas as $matricula)
                  @if ($matricula)
                    @php
                      $estudantes = (new App\Models\web\estudantes\Estudante())->find($matricula->estudantes_id);
                      $cursos = (new App\Models\web\cursos\Curso())->find($matricula->cursos_id);
                      $classes = (new App\Models\web\classes\Classe())->find($matricula->classes_id);
                      $turnos = (new App\Models\web\turnos\Turno())->find($matricula->turnos_id);
                    @endphp
                  @endif
                  <tr>
                      <td>{{ $matricula->documento }}</td>
                      <td>{{ $estudantes->nome }} {{ $estudantes->sobre_nome }}</td>
                      <td>{{ $estudantes->genero }}</td>
                      <td class="bg-dark">{{ $cursos->curso }}</td>
                      <td>{{ $classes->classes }}</td>
                      <td>{{ $turnos->turno }}</td>
                  </tr>
                @endforeach
              
            </tbody>
          </table>                          
        @endif
      </div>
      <!-- /.col -->
    </div>

</body>
</html>









