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
          font-family: arial;
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
        }
    
        table{
          width: 100%;
          text-align: left;
          border-spacing: 0;	
          margin-bottom: 10px;
          border: 1px solid #fff;
          font-size: 9px;
        }
        thead{
          background-color: #eaeaea;
          border-bottom: 1px solid #006699;
    
        }
        th, td{
          padding: 9px;
          border: 1px solid #000;
        }
        .titulo{
          font-size: 7pt;
          font-family: arial;
          text-transform: lowercase;
        }
    
        .border{
          border: 1px solid #eaeaea;
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
            <img src="{{ public_path('assets/images/insigna.png') }}" alt="" style="text-align: center;height: 60px;width: 60px;margin-bottom: 40px;">
        </div>  
    
        <div class="texto-header">
            <div>
                <h1 class="fs-5"><strong style="text-transform: uppercase">República de Angola</strong></h1>
                <h1 class="fs-5"><strong style="text-transform: uppercase">Ministério da Educação</strong></h1>
                <h1 class="fs-5"><strong style="text-transform: uppercase">{{ $titulo }}</strong></h1>
                <br>
            </div>
        </div>
    </div>  
    <!-- Table row -->
    <div class="row" style="margin-top: 170px">
       <table>
            <thead>
                <tr>
                  <th style="text-align: left;font-size: 7pt;text-transform: uppercase" colspan="4">ANO LECTIVO: @if ($ano) {{ $ano->ano }} @else Geral @endif</th>
                  <th style="text-align: left;font-size: 7pt;text-transform: uppercase" colspan="6">GENERO: @if ($genero)  {{ $genero }}@else Geral @endif</th>
                </tr>
                <tr>
                  <th style="text-align: left;font-size: 7pt;text-transform: uppercase" colspan="4">PROVÍNCIAS: @if ($provincia) {{ $provincia->nome }}@else Geral @endif</th>
                  <th style="text-align: left;font-size: 7pt;text-transform: uppercase" colspan="3">MUNICIPIO: @if ($municipio) {{ $municipio->nome }}@else Geral @endif</th>
                  <th style="text-align: left;font-size: 7pt;text-transform: uppercase" colspan="3">DISTRITOS: @if ($distrito) {{ $distrito->nome }}@else Geral @endif</th>
                </tr>
                
                <tr>
                    <th>Nº</th>
                    {{--<th>Nº</th>--}}
                    <th width="70%">Nome Completo</th>
                    <th>Bilhete</th>
                    <th>Genero</th>
                    <th>Idade</th>
                    <th>Nascimento</th>
                    <th>Status</th>
                    <th>Província</th>
                    <th width="13%">AnoLectivo</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @if (count($estudantes) != 0)
                    @foreach ($estudantes as $key => $item)
                        <tr>
                            <td style="text-align: left">{{ $key + 1 }}</td>
                           {{--<td style="text-align: left">{{ $item->numero_processo }}</td>--}} 
                            <td style="text-align: left">{{ $item->nome }} {{ $item->sobre_nome }}</td>
                            <td>{{ $item->bilheite }}</td>
                            <td>{{ $item->genero }}</td>
                            <td>{{ $item->idade($item->nascimento) }}</td>
                            <td>{{ $item->nascimento }}</td>
                            <td>{{ $item->status }}</td>
                            <td>{{ $item->provincia->nome }}</td>
                            <td>{{ $item->ano->ano }}</td>
                        </tr>    
                    @endforeach
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="10" style="text-align: left">TOTAL DE REGISTROS: {{ count($estudantes) }}</th>
                </tr>
            </tfoot>
       </table>
    </div>

</body>
</html>









