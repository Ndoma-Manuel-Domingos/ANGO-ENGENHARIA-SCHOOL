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
                <th class="bg-info text-center" colspan="19">Qudro 5. Pessoal Docente (Diante dos alunos) total
                    com e sem formação pedagógica e Nível Académico dos Docentes.
                    <span class="float-right">({{ $ensino->nome }})</span>    
                </th>
            </tr>
            <tr>
                <th></th>
                <th class="text-center" colspan="18">Nível Académico dos Docentes</th>
            </tr>

            <tr>
                <th></th>
                @foreach ($escolaridades as $escolaridade)
                <th colspan="2">{{ $escolaridade->nome }}</th>
                @endforeach
                <th colspan="2">Total</th>
            </tr>

            <tr>
                <td></td>
                @foreach ($escolaridades as $escolaridade)
                <td>MF</td>
                <td>F</td>
                @endforeach
                <td>MF</td>
                <td>F</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($formacoes as $formacao)
            <tr>
                @php
                $professores_masculinos = 0;
                $professores_femenino = 0;
                @endphp
                <td>{{ $formacao->nome }}</td>
                @foreach ($escolaridades as $escolaridade)

                @php
                $professores = App\Models\web\funcionarios\FuncionariosControto::select(
                DB::raw('SUM(CASE WHEN tb_professores.genero = "Masculino" THEN 1 ELSE 0 END) AS
                professores_masculino'),
                DB::raw('SUM(CASE WHEN tb_professores.genero = "Femenino" THEN 1 ELSE 0 END) AS
                professores_femenino'),
                )
                ->join('tb_professores', 'tb_contratos.funcionarios_id' , '=', 'tb_professores.id')
                ->join('tb_professores_academicos', 'tb_professores.id' , '=',
                'tb_professores_academicos.professor_id')
                ->where('tb_contratos.cargo_geral', 'professor')
                ->where('tb_contratos.level', '4')
                ->whereIn('tb_professores.id', $ids_professores)
                ->where('tb_contratos.shcools_id', $escola->id)
                ->where('tb_professores_academicos.escolaridade_id', $escolaridade->id)
                ->where('tb_professores_academicos.formacao_academica_id', $formacao->id)
                ->first()
                @endphp

                <td>{{ $professores->professores_masculino ?? 0 }}</td>
                <td>{{ $professores->professores_femenino ?? 0 }}</td>

                @php
                $professores_masculinos += $professores->professores_masculino;
                $professores_femenino += $professores->professores_femenino;
                @endphp

                @endforeach
                <td>{{ $professores_masculinos ?? 0 }}</td>
                <td>{{ $professores_femenino ?? 0 }}</td>
            </tr>
            @endforeach
            <tr>
                <td>TOTAL</td>
                @php
                $professores_masculinos_total = 0;
                $professores_femenino_total = 0;
                @endphp
                @foreach ($escolaridades as $escolaridade)
                @php
                    $professores = App\Models\web\funcionarios\FuncionariosControto::select(
                    DB::raw('SUM(CASE WHEN tb_professores.genero = "Masculino" THEN 1 ELSE 0 END) AS professores_masculino'),
                    DB::raw('SUM(CASE WHEN tb_professores.genero = "Femenino" THEN 1 ELSE 0 END) AS professores_femenino'),
                    )
                    ->join('tb_professores', 'tb_contratos.funcionarios_id' , '=', 'tb_professores.id')
                    ->join('tb_professores_academicos', 'tb_professores.id' , '=',
                    'tb_professores_academicos.professor_id')
                    ->where('tb_contratos.cargo_geral', 'professor')
                    ->whereIn('tb_professores.id', $ids_professores)
                    ->where('tb_contratos.level', '4')
                    ->where('tb_contratos.shcools_id', $escola->id)
                    ->where('tb_professores_academicos.escolaridade_id', $escolaridade->id)
                    ->first()
                @endphp

                <td>{{ $professores->professores_masculino ?? 0 }}</td>
                <td>{{ $professores->professores_femenino ?? 0 }}</td>

                @php
                $professores_masculinos_total += $professores->professores_masculino;
                $professores_femenino_total += $professores->professores_femenino;
                @endphp
                @endforeach
                <td>{{ $professores_masculinos_total ?? 0 }}</td>
                <td>{{ $professores_femenino_total ?? 0 }}</td>
            </tr>
        </tbody>
    </table>
    </div>

</body>

</html>