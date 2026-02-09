<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gestão Escolar | LISTA DE PRENSENÇA</title>
    <style>
        
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: calibri;
        }ul,ol{
            list-style: none;
        }
        a{
            text-decoration: none;
        }
        body{
            padding: 30px;
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
            font-size: 10pt;
            text-align: center;
        }

        table{
            width: 100%;
            text-align: left;
            border-spacing: 0;  
            margin-bottom: 10px;
            border: 1px solid #fff;
        }
        thead{
            background-color: #eaeaea;
            border-bottom: 1px solid #006699;

        }
        th, td{
            padding: 10px;
            border: 1px solid #000;
            font-size: 9pt;
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
        ul,ol{
            list-style: none;
        }
        a{
            text-decoration: none;
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

    <header>
        <div class="logo">
            <img src="{{ $logotipo }}" alt="" style="text-align: center;height: 60px;width: 60px;margin-bottom: 40px;">
        </div> 

        <h1 class="fs-5 text-center"><strong>República de Angola</strong></h1>
        <h1 class="fs-5 text-center"><strong>Governo Provincial de Luanda</strong></h1>
        <h1 class="fs-5 text-center"><strong>Gabinete Provincial da Educação</strong></h1>
        <h1 class="fs-5 text-center"><strong>{{ $escola->nome }}</strong></h1>
        
        <h1 class="fs-5 text-center" style="color: red;"><strong>LISTA DE PRENSENÇA</strong></h1>
        
        <br>
        <p>
            Disciplina: <strong style="border-bottom: 1px solid rgba(0,0,0,1);">{{ $results->disciplina }}</strong> , 
            Turma: <strong style="border-bottom: 1px solid rgba(0,0,0,1);">{{ $turma->turma }}</strong> , 
            classe: <strong style="border-bottom: 1px solid rgba(0,0,0,1);">{{ $classe->classes }}</strong> , 
            Sala <strong style="border-bottom: 1px solid rgba(0,0,0,1);">{{ $sala->salas }}</strong> ,
            Curso <strong style="border-bottom: 1px solid rgba(0,0,0,1);">{{ $curso->curso }}</strong> , 
            Turno: <strong style="border-bottom: 1px solid rgba(0,0,0,1);">{{ $turno->turno }} </strong> 
            Ano Lectivo: <strong style="border-bottom: 1px solid rgba(0,0,0,1);">{{ $ano->ano }}</strong> , 
        </p>
        <p style="float: right;">Dia de Semana {{ $results->semana }} / {{ date('d-m-Y')}}.</p>
    </header>

    <table class="table">
        <thead>
            <tr>
                <th>Cod</th>
                <th>Nome</th>
                <th>Genero</th>
                <th>Controle</th>
            </tr>
        </thead>

        <tbody>
            @if ($estudantes)
                @php
                    $soma = 0;
                @endphp
                @foreach ($estudantes as $item)
                    @php
                        $soma ++;
                    @endphp
                    <tr>
                        <td style="text-align: center;">00{{ $soma }}</td>
                        <td>{{ $item->nome }} {{ $item->sobre_nome }}</td>
                        <td>{{ $item->genero }}</td>
                        <td>{{ $item->status }}</td>
                    </tr>
                @endforeach
            @endif
          
        </tbody>
    </table>

    <div >
        <p>Professor <br>----------------------------------</p>
        <p>{{ $results->nome }} {{ $results->sobre_nome }}</p>
    </div>

</body>
</html>





