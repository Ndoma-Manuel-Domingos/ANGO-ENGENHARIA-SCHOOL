<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gestão Escolar | MAPA DE EFECTIVAIDADE DOS PROFESSORES</title>
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
        
        <h1 class="fs-5 text-center" style="color: red;"><strong>MAPA DE EFECTIVAIDADE DOS PROFESSORES</strong></h1>
        <br>
    </header>

    <table class="table">
        <thead>
            <tr>
                <th>Nº</th>
                <th style="text-align: left">Nome Completo</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($professores as $key => $item)
                @php
                    $mapas = App\Models\web\calendarios\MapaEfectividade::where('funcionarios_id', $item->funcionario->id)->when($requests['data_inicio'], function($query, $value){
                        $query->where('created_at', '>=', Carbon\Carbon::createFromDate($value));
                    })->when($requests['data_final'], function($query, $value){
                        $query->where('created_at', '<=', Carbon\Carbon::createFromDate($value));
                    })->get();
                @endphp
                
                <tr>
                    @php
                        $total_presenca = 0;
                        $total_ausencia = 0;
                        $total_justificada = 0;
                        $total_indefinida = 0;
                    @endphp
                
                    <td>{{ $key + 1 }}</td> 
                    <td>{{ $item->funcionario->nome }} {{ $item->funcionario->sobre_nome }}</td>
                    @foreach ($mapas as $map)
                        
                        @if ($map->status == 'Presente')
                            @php
                                ++$total_presenca;
                            @endphp                         
                        @endif
                        @if ($map->status == 'Ausente')
                            @php
                                ++$total_ausencia;
                            @endphp                         
                        @endif
                        @if ($map->status == 'Justitificado')
                            @php
                                ++$total_justificada;
                            @endphp                         
                        @endif
                        @if ($map->status == 'Indefinido')
                            @php
                                ++$total_indefinida;
                            @endphp                         
                        @endif
                    
                        @if ($map->status == 'Presente' )
                        <th class="bg-success" style="background-color: rgb(5, 141, 66);color: #e4e4e4"> <small><strong>{{ $map->dia }}</strong>/{{ $map->mes }}</small><br>
                            <small>{{ $map->dia_semana }}</small></th>
                        @endif
                        @if ($map->status == 'Ausente' )
                        <th class="bg-danger" style="background-color: red;color: #e4e4e4"> <small><strong>{{ $map->dia }}</strong>/{{ $map->mes }}</small><br>
                            <small>{{ $map->dia_semana }}</small></th>
                        @endif
                        @if ($map->status == 'Justitificado' )
                        <th class="bg-info" style="background-color: rgb(7, 68, 174);color: #e4e4e4"> <small><strong>{{ $map->dia }}</strong>/{{ $map->mes }}</small><br>
                            <small>{{ $map->dia_semana }}</small></th>
                        @endif
                        @if ($map->status == 'Indefinido' )
                        <th class="bg-warning" style="background-color: rgb(152, 139, 18);color: #e4e4e4"> <small><strong>{{ $map->dia }}</strong>/{{ $map->mes }}</small><br>
                            <small>{{ $map->dia_semana }}</small></th>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div >
        <p>Director Pedagógcio <br>----------------------------------</p>
        {{-- <p>{{ $results->nome }} {{ $results->sobre_nome }}</p> --}}
    </div>

</body>
</html>





