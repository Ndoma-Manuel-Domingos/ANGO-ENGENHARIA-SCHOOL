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
        <table id="example1" style="width: 100%" class="table table-bordered  ">
            <thead>
                <tr>
                    <th colspan="{{ count($formacoes) * 2 + 3 }}" class="text-center bg-info">Quadro 6. Idade do Pessoal Docente(Diante dos alunos). 
                        <span class="float-right">{{ $ensino->nome }}</span>
                    </th>
                </tr>
                <tr>
                    <th rowspan="2"></th>
                    @foreach ($formacoes as $formacao)
                    <th colspan="2" class="text-center">C/Formação Pedagógica</th>
                    @endforeach
                    <th colspan="2" class="text-center">Total</th>
                </tr>

                <tr>
                    @foreach ($formacoes as $formacao)
                    <th>MF</th>
                    <th>F</th>
                    @endforeach

                    <th>MF</th>
                    <th>F</th>
                </tr>
            </thead>
            <tbody id="">
                <tr>
                    <td>18-19 Anos</td>
                    @php
                    $professores_masculino_18_19 = 0;
                    $professores_femenino_18_19 = 0;
                    @endphp
                    @foreach ($formacoes as $formacao)
                    @php
                    $professores = App\Models\web\funcionarios\FuncionariosControto::select(
                    DB::raw('SUM(CASE WHEN tb_professores.genero = "Masculino" AND TIMESTAMPDIFF(YEAR,
                    tb_professores.nascimento, CURDATE()) BETWEEN 18 AND 19 THEN 1 ELSE 0 END) AS
                    professores_masculino'),
                    DB::raw('SUM(CASE WHEN tb_professores.genero = "Femenino" AND TIMESTAMPDIFF(YEAR,
                    tb_professores.nascimento, CURDATE()) BETWEEN 18 AND 19 THEN 1 ELSE 0 END) AS
                    professores_femenino'),
                    )
                    ->whereBetween(DB::raw('TIMESTAMPDIFF(YEAR, tb_professores.nascimento, CURDATE())'), [18, 19])
                    ->join('tb_professores', 'tb_contratos.funcionarios_id' , '=', 'tb_professores.id')
                    ->join('tb_professores_academicos', 'tb_professores.id' , '=',
                    'tb_professores_academicos.professor_id')
                    ->whereIn('tb_professores.id', $ids_professores)
                    ->where('tb_contratos.cargo_geral', 'professor')
                    ->where('tb_contratos.level', '4')
                    ->where('tb_contratos.shcools_id', $escola->id)
                    ->where('tb_professores_academicos.formacao_academica_id', $formacao->id)
                    ->first()
                    @endphp

                    <td>{{ $professores->professores_masculino ?? 0 }}</td>
                    <td>{{ $professores->professores_femenino ?? 0 }}</td>
                    @php
                    $professores_masculino_18_19 += $professores->professores_masculino ?? 0;
                    $professores_femenino_18_19 += $professores->professores_femenino ?? 0;
                    @endphp
                    @endforeach

                    <td>{{ $professores_masculino_18_19 ?? 0 }}</td>
                    <td>{{ $professores_femenino_18_19 ?? 0 }}</td>
                </tr>

                <tr>
                    <td>20-24 Anos</td>

                    @php
                    $professores_masculino_20_24 = 0;
                    $professores_femenino_20_24 = 0;
                    @endphp
                    @foreach ($formacoes as $formacao)
                    @php
                    $professores = App\Models\web\funcionarios\FuncionariosControto::select(
                    DB::raw('SUM(CASE WHEN tb_professores.genero = "Masculino" AND TIMESTAMPDIFF(YEAR,
                    tb_professores.nascimento, CURDATE()) BETWEEN 20 AND 24 THEN 1 ELSE 0 END) AS
                    professores_masculino'),
                    DB::raw('SUM(CASE WHEN tb_professores.genero = "Femenino" AND TIMESTAMPDIFF(YEAR,
                    tb_professores.nascimento, CURDATE()) BETWEEN 20 AND 24 THEN 1 ELSE 0 END) AS
                    professores_femenino'),
                    )
                    ->whereBetween(DB::raw('TIMESTAMPDIFF(YEAR, tb_professores.nascimento, CURDATE())'), [20, 24])
                    ->join('tb_professores', 'tb_contratos.funcionarios_id' , '=', 'tb_professores.id')
                    ->join('tb_professores_academicos', 'tb_professores.id' , '=',
                    'tb_professores_academicos.professor_id')
                    ->where('tb_contratos.cargo_geral', 'professor')
                    ->whereIn('tb_professores.id', $ids_professores)
                    ->where('tb_contratos.level', '4')
                    ->where('tb_contratos.shcools_id', $escola->id)
                    ->where('tb_professores_academicos.formacao_academica_id', $formacao->id)
                    ->first()
                    @endphp

                    <td>{{ $professores->professores_masculino ?? 0 }}</td>
                    <td>{{ $professores->professores_femenino ?? 0 }}</td>
                    @php
                    $professores_masculino_20_24 += $professores->professores_masculino ?? 0;
                    $professores_femenino_20_24 += $professores->professores_femenino ?? 0;
                    @endphp
                    @endforeach

                    <td>{{ $professores_masculino_20_24 ?? 0 }}</td>
                    <td>{{ $professores_femenino_20_24 ?? 0 }}</td>
                </tr>

                <tr>
                    <td>25-29 Anos</td>

                    @php
                    $professores_masculino_25_29 = 0;
                    $professores_femenino_25_29 = 0;
                    @endphp
                    @foreach ($formacoes as $formacao)
                    @php
                    $professores = App\Models\web\funcionarios\FuncionariosControto::select(
                    DB::raw('SUM(CASE WHEN tb_professores.genero = "Masculino" AND TIMESTAMPDIFF(YEAR,
                    tb_professores.nascimento, CURDATE()) BETWEEN 25 AND 29 THEN 1 ELSE 0 END) AS
                    professores_masculino'),
                    DB::raw('SUM(CASE WHEN tb_professores.genero = "Femenino" AND TIMESTAMPDIFF(YEAR,
                    tb_professores.nascimento, CURDATE()) BETWEEN 25 AND 29 THEN 1 ELSE 0 END) AS
                    professores_femenino'),
                    )
                    ->whereBetween(DB::raw('TIMESTAMPDIFF(YEAR, tb_professores.nascimento, CURDATE())'), [25, 29])
                    ->join('tb_professores', 'tb_contratos.funcionarios_id' , '=', 'tb_professores.id')
                    ->join('tb_professores_academicos', 'tb_professores.id' , '=',
                    'tb_professores_academicos.professor_id')
                    ->where('tb_contratos.cargo_geral', 'professor')
                    ->where('tb_contratos.level', '4')
                    ->whereIn('tb_professores.id', $ids_professores)
                    ->where('tb_contratos.shcools_id', $escola->id)
                    ->where('tb_professores_academicos.formacao_academica_id', $formacao->id)
                    ->first()
                    @endphp

                    <td>{{ $professores->professores_masculino ?? 0 }}</td>
                    <td>{{ $professores->professores_femenino ?? 0 }}</td>
                    @php
                    $professores_masculino_25_29 += $professores->professores_masculino ?? 0;
                    $professores_femenino_25_29 += $professores->professores_femenino ?? 0;
                    @endphp
                    @endforeach

                    <td>{{ $professores_masculino_25_29 ?? 0 }}</td>
                    <td>{{ $professores_femenino_25_29 ?? 0 }}</td>
                </tr>

                <tr>
                    <td>30-34 Anos</td>

                    @php
                    $professores_masculino_30_34 = 0;
                    $professores_femenino_30_34 = 0;
                    @endphp
                    @foreach ($formacoes as $formacao)
                    @php
                    $professores = App\Models\web\funcionarios\FuncionariosControto::select(
                    DB::raw('SUM(CASE WHEN tb_professores.genero = "Masculino" AND TIMESTAMPDIFF(YEAR,
                    tb_professores.nascimento, CURDATE()) BETWEEN 30 AND 34 THEN 1 ELSE 0 END) AS
                    professores_masculino'),
                    DB::raw('SUM(CASE WHEN tb_professores.genero = "Femenino" AND TIMESTAMPDIFF(YEAR,
                    tb_professores.nascimento, CURDATE()) BETWEEN 30 AND 34 THEN 1 ELSE 0 END) AS
                    professores_femenino'),
                    )
                    ->whereBetween(DB::raw('TIMESTAMPDIFF(YEAR, tb_professores.nascimento, CURDATE())'), [30, 34])
                    ->join('tb_professores', 'tb_contratos.funcionarios_id' , '=', 'tb_professores.id')
                    ->join('tb_professores_academicos', 'tb_professores.id' , '=',
                    'tb_professores_academicos.professor_id')
                    ->where('tb_contratos.cargo_geral', 'professor')
                    ->whereIn('tb_professores.id', $ids_professores)
                    ->where('tb_contratos.level', '4')
                    ->where('tb_contratos.shcools_id', $escola->id)
                    ->where('tb_professores_academicos.formacao_academica_id', $formacao->id)
                    ->first()
                    @endphp

                    <td>{{ $professores->professores_masculino ?? 0 }}</td>
                    <td>{{ $professores->professores_femenino ?? 0 }}</td>
                    @php
                    $professores_masculino_30_34 += $professores->professores_masculino ?? 0;
                    $professores_femenino_30_34 += $professores->professores_femenino ?? 0;
                    @endphp
                    @endforeach

                    <td>{{ $professores_masculino_30_34 ?? 0 }}</td>
                    <td>{{ $professores_femenino_30_34 ?? 0 }}</td>
                </tr>

                <tr>
                    <td>35-39 Anos</td>

                    @php
                    $professores_masculino_35_39 = 0;
                    $professores_femenino_35_39 = 0;
                    @endphp
                    @foreach ($formacoes as $formacao)
                    @php
                    $professores = App\Models\web\funcionarios\FuncionariosControto::select(
                    DB::raw('SUM(CASE WHEN tb_professores.genero = "Masculino" AND TIMESTAMPDIFF(YEAR,
                    tb_professores.nascimento, CURDATE()) BETWEEN 35 AND 39 THEN 1 ELSE 0 END) AS
                    professores_masculino'),
                    DB::raw('SUM(CASE WHEN tb_professores.genero = "Femenino" AND TIMESTAMPDIFF(YEAR,
                    tb_professores.nascimento, CURDATE()) BETWEEN 35 AND 39 THEN 1 ELSE 0 END) AS
                    professores_femenino'),
                    )
                    ->whereBetween(DB::raw('TIMESTAMPDIFF(YEAR, tb_professores.nascimento, CURDATE())'), [35, 39])
                    ->join('tb_professores', 'tb_contratos.funcionarios_id' , '=', 'tb_professores.id')
                    ->join('tb_professores_academicos', 'tb_professores.id' , '=',
                    'tb_professores_academicos.professor_id')
                    ->where('tb_contratos.cargo_geral', 'professor')
                    ->whereIn('tb_professores.id', $ids_professores)
                    ->where('tb_contratos.level', '4')
                    ->where('tb_contratos.shcools_id', $escola->id)
                    ->where('tb_professores_academicos.formacao_academica_id', $formacao->id)
                    ->first()
                    @endphp

                    <td>{{ $professores->professores_masculino ?? 0 }}</td>
                    <td>{{ $professores->professores_femenino ?? 0 }}</td>
                    @php
                    $professores_masculino_35_39 += $professores->professores_masculino ?? 0;
                    $professores_femenino_35_39 += $professores->professores_femenino ?? 0;
                    @endphp
                    @endforeach

                    <td>{{ $professores_masculino_35_39 ?? 0 }}</td>
                    <td>{{ $professores_femenino_35_39 ?? 0 }}</td>
                </tr>

                <tr>
                    <td>40 Anos ou mais</td>

                    @php
                    $professores_masculino_40 = 0;
                    $professores_femenino_40 = 0;
                    @endphp
                    @foreach ($formacoes as $formacao)
                    @php
                    $professores = App\Models\web\funcionarios\FuncionariosControto::select(
                    DB::raw('SUM(CASE WHEN tb_professores.genero = "Masculino" AND TIMESTAMPDIFF(YEAR,
                    tb_professores.nascimento, CURDATE()) >= 40 THEN 1 ELSE 0 END) AS professores_masculino'),
                    DB::raw('SUM(CASE WHEN tb_professores.genero = "Femenino" AND TIMESTAMPDIFF(YEAR,
                    tb_professores.nascimento, CURDATE()) >= 40 THEN 1 ELSE 0 END) AS professores_femenino'),
                    )
                    ->whereBetween(DB::raw('TIMESTAMPDIFF(YEAR, tb_professores.nascimento, CURDATE())'), [40, 60])
                    ->join('tb_professores', 'tb_contratos.funcionarios_id' , '=', 'tb_professores.id')
                    ->join('tb_professores_academicos', 'tb_professores.id' , '=',
                    'tb_professores_academicos.professor_id')
                    ->where('tb_contratos.cargo_geral', 'professor')
                    ->whereIn('tb_professores.id', $ids_professores)
                    ->where('tb_contratos.level', '4')
                    ->where('tb_contratos.shcools_id', $escola->id)
                    ->where('tb_professores_academicos.formacao_academica_id', $formacao->id)
                    ->first()
                    @endphp

                    <td>{{ $professores->professores_masculino ?? 0 }}</td>
                    <td>{{ $professores->professores_femenino ?? 0 }}</td>
                    @php
                    $professores_masculino_40 += $professores->professores_masculino ?? 0;
                    $professores_femenino_40 += $professores->professores_femenino ?? 0;
                    @endphp
                    @endforeach

                    <td>{{ $professores_masculino_40 ?? 0 }}</td>
                    <td>{{ $professores_femenino_40 ?? 0 }}</td>
                </tr>

                <tr>
                    <td>TOTAL</td>
                    @php
                    $professores_masculino_geral = 0;
                    $professores_femenino_geral = 0;
                    @endphp
                    @foreach ($formacoes as $formacao)

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
                    ->whereIn('tb_professores.id', $ids_professores)
                    ->where('tb_contratos.level', '4')
                    ->where('tb_contratos.shcools_id', $escola->id)
                    ->where('tb_professores_academicos.formacao_academica_id', $formacao->id)
                    ->first()
                    @endphp

                    <td>{{ $professores->professores_masculino ?? 0 }}</td>
                    <td>{{ $professores->professores_femenino ?? 0 }}</td>
                    @php
                    $professores_masculino_geral += $professores->professores_masculino ?? 0;
                    $professores_femenino_geral += $professores->professores_femenino ?? 0;
                    @endphp

                    @endforeach

                    <td>{{ $professores_masculino_geral ?? 0 }}</td>
                    <td>{{ $professores_femenino_geral ?? 0 }}</td>
                </tr>
            </tbody>
        </table>
    </div>

</body>

</html>