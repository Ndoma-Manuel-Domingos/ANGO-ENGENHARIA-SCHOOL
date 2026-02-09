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
    <title>{{ $titulo }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: calibri;
        }

        ul,
        ol {
            list-style: none;
        }

        a {
            text-decoration: none;
        }

        body {
            padding: 20px;
            /* border: 20px solid rgb(162, 170, 6); */
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
            font-size: 10pt;
            text-align: center;
        }

        table {
            width: 100%;
            text-align: left;
            border-spacing: 0;
            margin-bottom: 10px;
            border: 1px solid #fff;
        }

        thead {
            background-color: #eaeaea;
            border-bottom: 1px solid #006699;

        }

        th,
        td {
            padding: 10px;
            border: 1px solid #000;
            font-size: 9pt;
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

        ul,
        ol {
            list-style: none;
        }

        a {
            text-decoration: none;
        }

        #header {
            width: 100%;
            float: left;
            text-align: center;
            margin-bottom: 10px;
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
    <div style="height: 950px;">
        <header>
            <div class="logo">
                <img src="{{ $logotipo?? 'assets/images/insigna.png' }}" alt="" style="text-align: center;height: 60px;width: 60px;margin-bottom: 40px;">
            </div>

            <div class="texto-header">

                @if ($escola->categoria == 'Privado')
                <h1 class="fs-5 text-center"><strong>República de Angola</strong></h1>
                <h1 class="fs-5 text-center"><strong>{{ $escola->nome }}</strong></h1>
                @if ( isset($classe) && $classe->tipo == 'Transição')
                <h1 class="fs-5 text-center" style="color: red;"><strong>BOLETIM GERAL PARA CLASSE DE TRANSIÇÃO</strong></h1>
                @else
                @if (isset($classe) && $classe->tipo == 'Exame')
                <h1 class="fs-5 text-center" style="color: red;"><strong>BOLETIM GERAL PARA CLASSE DE EXAMES</strong></h1>
                @else
                <h1 class="fs-5 text-center" style="color: red;"><strong>BOLETIM GERAL</strong></h1>
                @endif
                @endif
                <br>
                <p>
                    Nome: <strong style="border-bottom: 1px solid rgba(0,0,0,1);font-size: 12pt;">{{ $estudante->nome}} {{ $estudante->sobre_nome }}</strong> ,
                    Turma: <strong style="border-bottom: 1px solid rgba(0,0,0,1);">{{ $turma->turma }}</strong> ,
                    classe: <strong style="border-bottom: 1px solid rgba(0,0,0,1);">{{ $classe->classes }}</strong> ,
                    Sala <strong style="border-bottom: 1px solid rgba(0,0,0,1);">{{ $sala->salas }}</strong> ,
                    Turno: <strong style="border-bottom: 1px solid rgba(0,0,0,1);">{{ $turno->turno }} </strong>
                    Ano Lectivo: <strong style="border-bottom: 1px solid rgba(0,0,0,1);">{{ $anoLectivo->ano }}</strong> ,
                    Trimstre: <strong style="border-bottom: 1px solid rgba(0,0,0,1);">{{ $trimestre->trimestre }}</strong>
                </p>
                @else
                <h1 class="fs-5 text-center"><strong>República de Angola</strong></h1>
                <h1 class="fs-5 text-center"><strong>Governo Provincial de Luanda</strong></h1>
                <h1 class="fs-5 text-center"><strong>Gabinete Provincial da Educação</strong></h1>
                <h1 class="fs-5 text-center"><strong>{{ $escola->nome }}</strong></h1>
                @if ( isset($classe) && $classe->tipo == 'Transição')
                <h1 class="fs-5 text-center" style="color: red;"><strong>BOLETIM GERAL PARA CLASSE DE TRANSIÇÃO</strong></h1>
                @else
                @if (isset($classe) && $classe->tipo == 'Exame')
                <h1 class="fs-5 text-center" style="color: red;"><strong>BOLETIM GERAL PARA CLASSE DE EXAMES</strong></h1>
                @else
                <h1 class="fs-5 text-center" style="color: red;"><strong>BOLETIM GERAL</strong></h1>
                @endif
                @endif
                <br>
                <p>
                    Nome: <strong style="border-bottom: 1px solid rgba(0,0,0,1);font-size: 12pt;">{{ $estudante->nome}} {{ $estudante->sobre_nome }}</strong> ,
                    Turma: <strong style="border-bottom: 1px solid rgba(0,0,0,1);">{{ $turma->turma }}</strong> ,
                    classe: <strong style="border-bottom: 1px solid rgba(0,0,0,1);">{{ $classe->classes }}</strong> ,
                    Sala <strong style="border-bottom: 1px solid rgba(0,0,0,1);">{{ $sala->salas }}</strong> ,
                    Turno: <strong style="border-bottom: 1px solid rgba(0,0,0,1);">{{ $turno->turno }} </strong>
                    Ano Lectivo: <strong style="border-bottom: 1px solid rgba(0,0,0,1);">{{ $anoLectivo->ano }}</strong> ,
                    Trimstre: <strong style="border-bottom: 1px solid rgba(0,0,0,1);">{{ $trimestre->trimestre }}</strong>
                </p>
                @endif

            </div>

        </header>

        <table class="table">
            <thead>
                <tr>
                    <th>Disciplinas</th>
                    <th>MAC</th>
                    <th>NPT</th>
                    <th>MT</th>
                </tr>
            </thead>

            <tbody>
                @if ($notas)
                @foreach ($notas as $item)
                <tr>
                    <td>{{ $item->disciplina->disciplina }}</td>
                    <td>{{ round($item->mac) }}</td>
                    <td>{{ round($item->npt) }}</td>
                    <td>{{ round($item->mt) }}</td>
                </tr>
                @endforeach
                @endif
            </tbody>

            <tfoot>
                <tr>
                    <td></td>
                    <td>Media Final: </td>
                    <td>( {{ round($mediaFinal) }}) </td>
                    <td>Resultado Final:
                        @if ($mediaFinal >= ($classe->tipo_avaliacao_nota / 2))
                        <span colspan="2" style="color: blue;">Transita</span>
                        @else
                        <span colspan="2" style="color: red;">N/Transita</span>
                        @endif
                    </td>
                </tr>
            </tfoot>
        </table>

    </div>
</body>
</html>
