<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $titulo }} | Gestão Escolar</title>

    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: arial;
        }

        ul,
        ol {
            list-style: none;
            font-family: arial;
        }

        a {
            text-decoration: none;
            font-family: arial;
        }

        body {
            padding: 20px;
            font-family: Arial, Helvetica, sans-serif;
        }

        h1 {
            font-size: 10pt;
            margin-bottom: 4px;
        }

        h2 {
            font-size: 9pt;
        }

        .titulo {
            font-size: 10pt;
            text-align: center;
        }

        p {
            margin-bottom: 20px;
            line-height: 20px;
        }

        table {
            width: 100%;
            text-align: left;
            border-spacing: 0;
            margin-bottom: 10px;
            border: 1px solid #fff;
            font-size: 10pt;
        }

        thead {
            background-color: #eaeaea;
            border-bottom: 1px solid #006699;

        }

        th,
        td {
            padding: 5px;
            border: 1px solid #000;
            text-align: left;

        }

        .border {
            border: 1px solid #eaeaea;
        }

        .flex {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-content: center;
            align-items: center;
        }

        .col {
            width: 25%;
            padding-left: 2px;
            padding-right: 2px;
        }

        .cols {
            width: 50%;
            padding-left: 2px;
            padding-right: 2px;
        }

        .col-2 {
            width: 10%;
            padding-left: 2px;
            padding-right: 2px;
        }

        .col-8 {
            width: 80%;
            padding-left: 2px;
            padding-right: 2px;
        }

        .logo {
            height: 80px;
            width: 80px;
            /*border-radius: 300px;*/
            /*padding: 30px; */
        }

        .ml {
            margin-left: 80px;
        }

        .text-center {
            text-align: center;
        }

        #header {
            width: 100%;
            float: left;
            text-align: center;
        }

        .logo {
            width: 100%;
            height: 70px;
            text-align: center;
        }

        .texto-header {
            width: 100%;
            height: 170px;
        }

    </style>
</head>
<body class="hold-transition sidebar-mini">

    <div id="header">
        <div class="logo">
            <img src="{{ $logotipo ?? 'assets/images/insigna.png' }}" alt="" style="text-align: center;height: 60px;width: 60px;margin-bottom: 40px;">
        </div>

        <div class="texto-header">
            <div>
                <h1 class="fs-5"><strong style="text-transform: uppercase">República de Angola</strong></h1>
                <h1 class="fs-5"><strong style="text-transform: uppercase">Ministério da Educação</strong></h1>
                <h1 class="fs-5"><strong style="text-transform: uppercase">{{ $escola->nome }}</strong></h1>
                <h1 class="fs-5"><strong style="text-transform: uppercase">{{ $titulo }}</strong></h1>
            </div>
        </div>
    </div>


    @if ($escola->ensino && $escola->ensino->nome == "Ensino Superior")
    <!-- Table row -->
    <div class="row" style="margin-top: 170px">
        <table>
            <thead>
                <tr>
                    <th colspan="3">PERÍODO: {{ $trimestre ? $trimestre->trimestre : 'TODOS' }}</th>
                    <th colspan="6">DISCIPLINA: {{ $disciplina ? $disciplina->disciplina : 'TODAS' }}</th>
                    <th colspan="4">TURMA: {{ $turma ? $turma->turma : 'TODOS' }}</th>
                </tr>

                <tr>
                    <th>Nº</th>
                    <th>Nome Completo</th>
                    <th>Sexo</th>

                    <th>P1</th>
                    <th>P2</th>
                    <th>P3</th>
                    <th>P4</th>
                    <th>Média</th>
                    <th>Obs</th>
                    <th>Exame</th>
                    <th>Resultado</th>
                    <th>Recurso</th>
                    <th>Exame Especial</th>
                    <th>NF</th>
                    <th>Estado</th>

                </tr>

            </thead>

            <tbody class="text-center">
                @if ($notas)
                @foreach ($notas as $key => $item1)
                <tr>
                    <td> {{ $key + 1 }}</td>
                    <td> {{ $item1->estudante->nome }} {{ $item1->estudante->sobre_nome }}</td>
                    <td> {{ $item1->estudante->genero }}</td>

                    <td>{{ $item1->p1 }}</td>
                    <td>{{ $item1->p2 }}</td>
                    <td>{{ $item1->p3 }}</td>
                    <td>{{ $item1->p4 }}</td>
                    <td>{{ $item1->med }}</td>
                    <td>{{ $item1->obs1 }}</td>
                    <td>{{ $item1->exame_1_especial }}</td>
                    <td>{{ $item1->obs2 }}</td>
                    <td>{{ $item1->recurso }}</td>
                    <td>{{ $item1->exame_especial }}</td>
                    <td>{{ $item1->resultado_final }}</td>
                    @if ($item1->obs3 == 'Apto')
                    <td style="color: green;text-transform: uppercase">{{ $item1->obs3 }}</td>
                    @else
                    <td style="color: red;text-transform: uppercase">{{ $item1->obs3 }}</td>
                    @endif
                </tr>
                @endforeach
                @endif

            </tbody>
        </table>
        <h5>Total Registro: {{ count($notas) }}</h5>
    </div>
    @else
    <!-- Table row -->
    <div style="margin-top: 170px">
        <table>
            <thead>
                <tr>
                    <th colspan="3">PERÍODO</th>
                    <th colspan="5">{{ $trimestre ? $trimestre->trimestre : 'TODOS' }}</th>
                </tr>
                <tr>
                    <th colspan="3">DISCIPLINA</th>
                    <th colspan="5">{{ $disciplina ? $disciplina->disciplina : 'TODAS' }}</th>
                </tr>
                <tr>
                    <th colspan="3">TURMA</th>
                    <th colspan="5">{{ $turma ? $turma->turma : 'TODOS' }}</th>
                </tr>

                <tr>
                    <th>Nº</th>
                    <th>Nome</th>
                    <th>Sexo</th>
        
                    @if($trimestre->trimestre == "IIIª Trimestre")
                    <th>EN</th>
                    <th>PT</th>
                    <th>PAP</th>
                    <th>NR</th>
                    @endif
                    
                    
                    @if ($turma->curso->tipo === "Técnico")
                        <th style="text-align: center;">MAC</th>
                        <th style="text-align: center;">NPP</th>
                        <th style="text-align: center;">NPT</th>
                    @endif 
                    
                    @if ($turma->curso->tipo === "Punível")
                        <th style="text-align: center;">P1</th>
                        <th style="text-align: center;">P2</th>
                        <th style="text-align: center;">PT</th>
                    @endif 
                    
                    @if ($turma->curso->tipo === "Outros")
                        <th style="text-align: center;">MAC</th>
                        <th style="text-align: center;">NPT</th>
                    @endif
        
                    <th>MT</th>
                    <th>Resultado</th>
                </tr>
            </thead>
            <tbody>
                @if ($notas)
                @php $contador = 0; @endphp
                @foreach ($notas as $key => $nota)
                @php $contador ++; @endphp
                <tr>
                    <td> {{ $contador }}</td>
                    <td> {{ $nota->estudante->nome }} {{ $nota->estudante->sobre_nome }}</td>
                    <td> {{ $nota->estudante->genero }}</td>
                    
                    @if($trimestre->trimestre == "IIIª Trimestre")
                        <td>{{ $nota->arredondar($nota->ne) }}</td>
                        <td>{{ $nota->arredondar($nota->pt) }}</td>
                        <td>{{ $nota->arredondar($nota->pap) }}</td>
                        <td>{{ $nota->arredondar($nota->nr) }}</td>
                    @endif  

                    @if ($turma->curso->tipo === "Técnico")
                        <td>{{ $nota->arredondar($nota->mac) }}</td>
                        <td>{{ $nota->arredondar($nota->npp) }}</td>
                        <td>{{ $nota->arredondar($nota->npt) }}</td>
                    @endif 
                    
                    @if ($turma->curso->tipo === "Punível")
                        <td>{{ $nota->arredondar($nota->mac) }}</td>
                        <td>{{ $nota->arredondar($nota->npp) }}</td>
                        <td>{{ $nota->arredondar($nota->npt) }}</td>
                    @endif 
                    
                    @if ($turma->curso->tipo === "Outros")
                        <td>{{ $nota->arredondar($nota->mac) }}</td>
                        <td>{{ $nota->arredondar($nota->npt) }}</td>
                    @endif

                    <td>{{ $nota->arredondar($nota->mt) }}</td>
                    
                    @if ($nota->obs == "Apto")
                        <td style="color: #006699;">TRANSITA</td>
                    @else
                        <td style="color: #ff0000;">N/TRANSITA</td>
                    @endif
                    
                    
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
    @endif

</body>
</html>
