<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $titulo }} | Gestão Escolar</title>

    <style type="text/css">
        *{
          margin: 0;
          padding: 0;
          box-sizing: border-box;
          font-family: Arial, Helvetica, sans-serif;
        }
        ul,ol{
          list-style: none;
          font-family: arial;
        }
        a{
          text-decoration: none;
          font-family: arial;
        }
        body{
          padding: 20px;
          font-family: Arial, Helvetica, sans-serif;
        }
        h1{
          font-size: 10pt;
          margin-bottom: 4px;
        }
        h2{
          font-size: 9pt;
        }
    
        .titulo{
          font-size: 10pt;
          text-align: center;
        }
    
        p{
          margin-bottom: 20px;
          line-height: 20px;
          font-family: Arial, Helvetica, sans-serif;
          font-size: 10pt;
        }
    
        table{
          width: 100%;
          text-align: left;
          border-spacing: 0;	
          margin-bottom: 10px;
          border: 1px solid #fff;
          font-size: 10pt;
        }
        thead{
          /* border-bottom: 1px solid #006699; */
    
        }
        th, td{
          padding: 10px;
          text-align: left;
          
        }
        .text-right{
            text-align: right;
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
<body class="hold-transition sidebar-mini">
 
  <div id="header">
    <div class="logo">
        <img src="{{ public_path('assets/images/'. $escola->logotipo ?? '') }}" alt="" style="text-align: center;height: 60px;width: 60px;margin-bottom: 40px;">
    </div>   
    
    <div class="texto-header">
        <div>
          @if ($escola->categoria == 'Privado')
          <h1 class="fs-5"><strong style="text-transform: uppercase">{{ $escola->nome }}</strong></h1>
          <h1 class="fs-5"><strong style="text-transform: uppercase">{{ $escola->sigla }}</strong></h1>
          {{-- <h1 class="fs-5"><strong class="text-uppercase" style="text-transform: uppercase">{{ $titulo }}</strong></h1> --}}
          <br>
          @else
          <h1 class="fs-5"><strong style="text-transform: uppercase">República de Angola</strong></h1>
          <h1 class="fs-5"><strong style="text-transform: uppercase">Ministério da Educação</strong></h1>
          <h1 class="fs-5"><strong style="text-transform: uppercase">{{ $escola->nome }}</strong></h1>
          <h1 class="fs-5"><strong style="text-transform: uppercase">{{ $escola->sigla }}</strong></h1>
          <h1 class="fs-5"><strong class="text-uppercase" style="text-transform: uppercase">{{ $titulo }}</strong></h1>
          <br>
          @endif
        </div>
    </div>
      
   </div> 
    <!-- Table row -->
    <div class="row" style="margin-top: 170px">
       <table>
            <thead>
                <tr>
                    <th>Assunto: {{ $titulo }}</th>
                    <th class="text-right"></th>
                </tr>
            </thead>
       </table>
       <table>
            <thead>
                <tr>
                    <th>Data: {{ date("d M. Y H:i", strtotime($comunicado->created_at)) }}</th>
                    {{-- <th>Para: {{ $comunicado->to_escola }}</th> --}}
                    <th class="text-right">Tipo Comunicado: {{ $comunicado->tipo_comunicado }}</th>
                </tr>
            </thead>
       </table>
       
       <table>
            <thead>
                <tr>
                    <td><p>@php echo $comunicado->descricao @endphp Lorem ipsum dolor, sit amet consectetur adipisicing elit. Veritatis mollitia optio id quam blanditiis voluptatem necessitatibus ullam laborum aut nobis magni perferendis officiis repellat, corporis labore asperiores quis quos commodi? Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusamus id dolorem voluptatem, facilis repellat sunt molestiae? Exercitationem ratione cum ab doloribus perspiciatis magnam sequi? Ducimus illum voluptatem necessitatibus architecto minima!</p></td>
                </tr>
            </thead>
       </table>
        
        <h5 style="padding: 10px">Assinaturas</h5>
        <hr style="margin-bottom: 20px">
       <table style="">
            <thead>
                <tr>
                    <th style="text-align: center">Director</th>
                    <th style="text-align: center">Secretário</th>
                </tr>
                <tr>
                    <th style="text-align: center">_______________________________</th>
                    <th style="text-align: center">_______________________________</th>
                </tr>
        
                <tr>
                    <th style="text-align: center">Pedagógico</th>
                    <th style="text-align: center">Director Turma</th>
                </tr>
                <tr>
                    <th style="text-align: center">_______________________________</th>
                    <th style="text-align: center">_______________________________</th>
                </tr>
              
            </thead>
       </table>
    </div>

</body>
</html>









