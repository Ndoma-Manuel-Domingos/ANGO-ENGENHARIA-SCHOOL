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
            padding: 30px;
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
            padding: 10px;
            border: 1px solid #000;

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

    @include('downloads.relatorios.header')

    <div style="margin-top: 170px">
        <table id="example1" style="width: 100%"
            class="table table-bordered  ">
            <thead>
                <tr>
                    <th colspan="8" class="text-center bg-info">Quadro 3. Turnos, Turma por classe. ({{ $ensino->nome }})</th>
                </tr>
                <tr>
                    <th rowspan="2"></th>
                    @foreach ($turnos as $item)
                    <th colspan="2" class="text-center">{{ $item->turno->turno }}</th>
                    @endforeach
                    <th rowspan="2" class="text-center">Total Turmas</th>
                </tr>

                <tr>
                    @foreach ($turnos as $item)
                    <th>Turmas</th>
                    <th>Turnos</th>
                    @endforeach
                </tr>

            </thead>
            <tbody id="">
                @php
                $total_geral = 0;
                @endphp
                @foreach ($classes as $classe)
                <tr>
                    @php
                    $total_turma_final = 0;
                    @endphp
                    <td>{{ $classe->classes }}</td>
                    @foreach ($turnos as $turno)
                    @php

                    $total_turno = App\Models\web\turmas\Turma::where('shcools_id',
                    $escola->id)->where('ano_lectivos_id', $anolectivoactual)
                    ->where('turnos_id', $turno->turno->id)
                    ->where('classes_id', $classe->id)
                    ->distinct('turnos_id')
                    ->count();

                    $total_turma = App\Models\web\turmas\Turma::where('shcools_id',
                    $escola->id)->where('ano_lectivos_id', $anolectivoactual)
                    ->where('turnos_id', $turno->turno->id)
                    ->where('classes_id', $classe->id)
                    ->distinct('id')
                    ->count();

                    $total_turma_final += $total_turma;
                    @endphp
                    <td>{{ $total_turma }}</td>
                    <td>{{ $total_turno }}</td>
                    @endforeach
                    <td>{{ $total_turma_final }}</td>
                </tr>
                @php
                $total_geral += $total_turma_final;
                @endphp
                @endforeach
            </tbody>
        </table>
    </div>

</body>

</html>