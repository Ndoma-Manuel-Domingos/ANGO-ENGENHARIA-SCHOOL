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
          font-size: 10pt;
        }
        thead{
          background-color: #eaeaea;
          border-bottom: 1px solid #006699;
    
        }
        th, td{
          padding: 10px;
          border: 1px solid #000;
          
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
 
  @include('downloads.relatorios.header')  
    
    <!-- Table row -->
    <div class="row" style="margin-top: 170px">
       <table>
            <thead>
                <tr>
                  <th colspan="3" style="text-align: left">TOTAL REGISTROS:</th>
                  <th colspan="5" style="text-align: left">{{ count($cargos) }}</th>
                </tr>
                <tr>
                    <th style="text-align: left">Nº</th>
                    <th style="text-align: left">Cargo</th>
                    <th style="text-align: left">Departamento</th>
                    <th style="text-align: left">Salário</th>
                    <th style="text-align: left">Status</th>
                </tr>
            </thead>

            <tbody class="text-center">
                @if ($cargos)
                    @foreach ($cargos as $key => $item)
                        <tr>
                            <td style="text-align: left">{{ $key + 1 }}</td>
                            <td style="text-align: left">{{ $item->cargo }}</td>
                            <td style="text-align: left">{{ $item->departamento->departamento }}</td>
                            <td style="text-align: left">{{ number_format($item->salario, 2, ',', '.') }} Kz</td>
                            <td style="text-align: left">{{ $item->status }}</td>
                        </tr>    
                    @endforeach
                    
                @endif
                
            </tbody>
       </table>
    </div>

</body>
</html>









