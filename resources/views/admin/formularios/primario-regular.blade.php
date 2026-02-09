@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Formulário Primário Regular</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Primario</a></li>
                    <li class="breadcrumb-item active">Formulário</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="callout callout-info">
                    <h5><i class="fas fa-info"></i> Painel Pedagógico</h5>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- table 01--}}
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <table id="example1" style="width: 100%"
                            class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th colspan="24" class="text-center bg-info">Quadro 1. Total de Alunos Matriculados
                                        por classe e idade <span>(incluindo os repetentes e os alunos com
                                            deficiência)</span></th>
                                </tr>
                                <tr>
                                    <th colspan="24" class="text-center bg-primary">Total alunos por idade</th>
                                </tr>
                                <tr>
                                    <th rowspan="2">Idades/Classes</th>
                                    <th colspan="2">5 Anos</th>
                                    <th colspan="2">6 Anos</th>
                                    <th colspan="2">7 Anos</th>
                                    <th colspan="2">8 Anos</th>
                                    <th colspan="2">9 Anos</th>
                                    <th colspan="2">10 Anos</th>
                                    <th colspan="2">11 Anos</th>
                                    <th colspan="2">12 Anos</th>
                                    <th colspan="2">13 Anos</th>
                                    <th colspan="2">14 a 16 Anos</th>
                                    <th colspan="2">Total</th>
                                </tr>

                                <tr>
                                    <th>MF</th>
                                    <th>F</th>

                                    <th>MF</th>
                                    <th>F</th>

                                    <th>MF</th>
                                    <th>F</th>

                                    <th>MF</th>
                                    <th>F</th>

                                    <th>MF</th>
                                    <th>F</th>

                                    <th>MF</th>
                                    <th>F</th>

                                    <th>MF</th>
                                    <th>F</th>

                                    <th>MF</th>
                                    <th>F</th>

                                    <th>MF</th>
                                    <th>F</th>

                                    <th>MF</th>
                                    <th>F</th>

                                    <th>MF</th>
                                    <th>F</th>
                                </tr>
                            </thead>
                            <tbody id="">

                                @php
                                $matriculados_masculino_5_anos_total = 0;
                                $matriculados_masculino_6_anos_total = 0;
                                $matriculados_masculino_7_anos_total = 0;
                                $matriculados_masculino_8_anos_total = 0;
                                $matriculados_masculino_9_anos_total = 0;
                                $matriculados_masculino_10_anos_total = 0;
                                $matriculados_masculino_11_anos_total = 0;
                                $matriculados_masculino_12_anos_total = 0;
                                $matriculados_masculino_13_anos_total = 0;
                                $matriculados_masculino_14_anos_total = 0;


                                $matriculados_feminino_5_anos_total = 0;
                                $matriculados_feminino_6_anos_total = 0;
                                $matriculados_feminino_7_anos_total = 0;
                                $matriculados_feminino_8_anos_total = 0;
                                $matriculados_feminino_9_anos_total = 0;
                                $matriculados_feminino_10_anos_total = 0;
                                $matriculados_feminino_11_anos_total = 0;
                                $matriculados_feminino_12_anos_total = 0;
                                $matriculados_feminino_13_anos_total = 0;
                                $matriculados_feminino_14_anos_total = 0;

                                @endphp

                                @foreach ($classes as $classe)
                                <tr>
                                    @php
                                    $result = App\Models\web\calendarios\Matricula::select(
                                    DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 5 THEN 1 ELSE 0 END) AS matriculados_masculino_5_anos'),
                                    DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 5 THEN 1 ELSE 0 END) AS matriculados_feminino_5_anos'),

                                    DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 6 THEN 1 ELSE 0 END) AS matriculados_masculino_6_anos'),
                                    DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 6 THEN 1 ELSE 0 END) AS matriculados_feminino_6_anos'),

                                    DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 7 THEN 1 ELSE 0 END) AS matriculados_masculino_7_anos'),
                                    DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 7 THEN 1 ELSE 0 END) AS matriculados_feminino_7_anos'),

                                    DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 8 THEN 1 ELSE 0 END) AS matriculados_masculino_8_anos'),
                                    DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 8 THEN 1 ELSE 0 END) AS matriculados_feminino_8_anos'),

                                    DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 9 THEN 1 ELSE 0 END) AS matriculados_masculino_9_anos'),
                                    DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 9 THEN 1 ELSE 0 END) AS matriculados_feminino_9_anos'),

                                    DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 10 THEN 1 ELSE 0 END) AS matriculados_masculino_10_anos'),
                                    DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 10 THEN 1 ELSE 0 END) AS matriculados_feminino_10_anos'),

                                    DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 11 THEN 1 ELSE 0 END) AS matriculados_masculino_11_anos'),
                                    DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 11 THEN 1 ELSE 0 END) AS matriculados_feminino_11_anos'),

                                    DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 12 THEN 1 ELSE 0 END) AS matriculados_masculino_12_anos'),
                                    DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 12 THEN 1 ELSE 0 END) AS matriculados_feminino_12_anos'),

                                    DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 13 THEN 1 ELSE 0 END) AS matriculados_masculino_13_anos'),
                                    DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 13 THEN 1 ELSE 0 END) AS matriculados_feminino_13_anos'),

                                    DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 14 AND 16 THEN 1 ELSE 0 END) AS matriculados_masculino_14_anos'),
                                    DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 14 AND 16 THEN 1 ELSE 0 END) AS matriculados_feminino_14_anos'),
                                    )
                                    ->whereBetween(DB::raw('TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE())'),
                                    [5, 14])
                                    ->join('tb_estudantes', 'tb_matriculas.estudantes_id' , '=', 'tb_estudantes.id')
                                    ->where('tb_matriculas.classes_id', $classe->id)
                                    ->where('tb_matriculas.shcools_id', $escola->id)
                                    ->where('tb_matriculas.ano_lectivos_id', $anolectivoactual)
                                    ->first();
                                    @endphp

                                    <td>{{ $classe->classes }}</td>

                                    <td>{{ $result->matriculados_masculino_5_anos ?? 0 }}</td>
                                    <td>{{ $result->matriculados_feminino_5_anos ?? 0 }}</td>

                                    <td>{{ $result->matriculados_masculino_6_anos ?? 0 }}</td>
                                    <td>{{ $result->matriculados_feminino_6_anos ?? 0 }}</td>

                                    <td>{{ $result->matriculados_masculino_7_anos ?? 0 }}</td>
                                    <td>{{ $result->matriculados_feminino_7_anos ?? 0 }}</td>

                                    <td>{{ $result->matriculados_masculino_8_anos ?? 0 }}</td>
                                    <td>{{ $result->matriculados_feminino_8_anos ?? 0 }}</td>

                                    <td>{{ $result->matriculados_masculino_9_anos ?? 0 }}</td>
                                    <td>{{ $result->matriculados_feminino_9_anos ?? 0 }}</td>

                                    <td>{{ $result->matriculados_masculino_10_anos ?? 0 }}</td>
                                    <td>{{ $result->matriculados_feminino_10_anos ?? 0 }}</td>

                                    <td>{{ $result->matriculados_masculino_11_anos ?? 0 }}</td>
                                    <td>{{ $result->matriculados_feminino_11_anos ?? 0 }}</td>

                                    <td>{{ $result->matriculados_masculino_12_anos ?? 0 }}</td>
                                    <td>{{ $result->matriculados_feminino_12_anos ?? 0 }}</td>

                                    <td>{{ $result->matriculados_masculino_13_anos ?? 0 }}</td>
                                    <td>{{ $result->matriculados_feminino_13_anos ?? 0 }}</td>

                                    <td>{{ $result->matriculados_masculino_14_anos ?? 0 }}</td>
                                    <td>{{ $result->matriculados_feminino_14_anos ?? 0 }}</td>

                                    <td>{{ $result->matriculados_masculino_5_anos ?? 0 +
                                        $result->matriculados_masculino_6_anos ?? 0 +
                                        $result->matriculados_masculino_7_anos ?? 0 +
                                        $result->matriculados_masculino_8_anos ?? 0 +
                                        $result->matriculados_masculino_9_anos ?? 0 +
                                        $result->matriculados_masculino_10_anos ?? 0 +
                                        $result->matriculados_masculino_11_anos ?? 0 +
                                        $result->matriculados_masculino_12_anos ?? 0 +
                                        $result->matriculados_masculino_13_anos ?? 0 +
                                        $result->matriculados_masculino_14_anos ?? 0 }}</td>
                                    <td>
                                        {{ $result->matriculados_feminino_5_anos ?? 0 +
                                        $result->matriculados_feminino_6_anos ?? 0 +
                                        $result->matriculados_feminino_7_anos ?? 0 +
                                        $result->matriculados_feminino_8_anos ?? 0 +
                                        $result->matriculados_feminino_9_anos ?? 0 +
                                        $result->matriculados_feminino_10_anos ?? 0 +
                                        $result->matriculados_feminino_11_anos ?? 0 +
                                        $result->matriculados_feminino_12_anos ?? 0 +
                                        $result->matriculados_feminino_13_anos ?? 0 +
                                        $result->matriculados_feminino_14_anos ?? 0 }}
                                    </td>
                                </tr>

                                @php
                                $matriculados_masculino_5_anos_total += $result->matriculados_masculino_5_anos;
                                $matriculados_masculino_6_anos_total += $result->matriculados_masculino_6_anos;
                                $matriculados_masculino_7_anos_total += $result->matriculados_masculino_7_anos;
                                $matriculados_masculino_8_anos_total += $result->matriculados_masculino_8_anos;
                                $matriculados_masculino_9_anos_total += $result->matriculados_masculino_9_anos;
                                $matriculados_masculino_10_anos_total += $result->matriculados_masculino_10_anos;
                                $matriculados_masculino_11_anos_total += $result->matriculados_masculino_11_anos;
                                $matriculados_masculino_12_anos_total += $result->matriculados_masculino_12_anos;
                                $matriculados_masculino_13_anos_total += $result->matriculados_masculino_13_anos;
                                $matriculados_masculino_14_anos_total += $result->matriculados_masculino_14_anos;


                                $matriculados_feminino_5_anos_total += $result->matriculados_feminino_5_anos;
                                $matriculados_feminino_6_anos_total += $result->matriculados_feminino_6_anos;
                                $matriculados_feminino_7_anos_total += $result->matriculados_feminino_7_anos;
                                $matriculados_feminino_8_anos_total += $result->matriculados_feminino_8_anos;
                                $matriculados_feminino_9_anos_total += $result->matriculados_feminino_9_anos;
                                $matriculados_feminino_10_anos_total += $result->matriculados_feminino_10_anos;
                                $matriculados_feminino_11_anos_total += $result->matriculados_feminino_11_anos;
                                $matriculados_feminino_12_anos_total += $result->matriculados_feminino_12_anos;
                                $matriculados_feminino_13_anos_total += $result->matriculados_feminino_13_anos;
                                $matriculados_feminino_14_anos_total += $result->matriculados_feminino_14_anos;
                                @endphp

                                @endforeach
                                <tr>
                                    <td>TOTAL</td>

                                    <td>{{ $matriculados_masculino_5_anos_total ?? 0 }}</td>
                                    <td>{{ $matriculados_feminino_5_anos_total ?? 0 }}</td>

                                    <td>{{ $matriculados_masculino_6_anos_total ?? 0 }}</td>
                                    <td>{{ $matriculados_feminino_6_anos_total ?? 0 }}</td>

                                    <td>{{ $matriculados_masculino_7_anos_total ?? 0 }}</td>
                                    <td>{{ $matriculados_feminino_7_anos_total ?? 0 }}</td>

                                    <td>{{ $matriculados_masculino_8_anos_total ?? 0 }}</td>
                                    <td>{{ $matriculados_feminino_8_anos_total ?? 0 }}</td>

                                    <td>{{ $matriculados_masculino_9_anos_total ?? 0 }}</td>
                                    <td>{{ $matriculados_feminino_9_anos_total ?? 0 }}</td>

                                    <td>{{ $matriculados_masculino_10_anos_total ?? 0 }}</td>
                                    <td>{{ $matriculados_feminino_10_anos_total ?? 0 }}</td>

                                    <td>{{ $matriculados_masculino_11_anos_total ?? 0 }}</td>
                                    <td>{{ $matriculados_feminino_11_anos_total ?? 0 }}</td>

                                    <td>{{ $matriculados_masculino_12_anos_total ?? 0 }}</td>
                                    <td>{{ $matriculados_feminino_12_anos_total ?? 0 }}</td>

                                    <td>{{ $matriculados_masculino_13_anos_total ?? 0 }}</td>
                                    <td>{{ $matriculados_feminino_13_anos_total ?? 0 }}</td>

                                    <td>{{ $matriculados_masculino_14_anos_total ?? 0 }}</td>
                                    <td>{{ $matriculados_feminino_14_anos_total ?? 0 }}</td>

                                    <td>{{ $matriculados_masculino_5_anos_total ?? 0 +
                                        $matriculados_masculino_6_anos_total ?? 0 + $matriculados_masculino_7_anos_total
                                        ?? 0 + $matriculados_masculino_8_anos_total ?? 0 +
                                        $matriculados_masculino_9_anos_total ?? 0 +
                                        $matriculados_masculino_10_anos_total ?? 0 +
                                        $matriculados_masculino_11_anos_total ?? 0 +
                                        $matriculados_masculino_12_anos_total ?? 0 +
                                        $matriculados_masculino_13_anos_total ?? 0 +
                                        $matriculados_masculino_14_anos_total ?? 0 }}</td>
                                    <td>{{ $matriculados_feminino_5_anos_total ?? 0 +
                                        $matriculados_feminino_6_anos_total ?? 0 + $matriculados_feminino_7_anos_total
                                        ?? 0 + $matriculados_feminino_8_anos_total ?? 0 +
                                        $matriculados_feminino_9_anos_total ?? 0 + $matriculados_feminino_10_anos_total
                                        ?? 0 + $matriculados_feminino_11_anos_total ?? 0 +
                                        $matriculados_feminino_12_anos_total ?? 0 + $matriculados_feminino_13_anos_total
                                        ?? 0 + $matriculados_feminino_14_anos_total ?? 0 }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- table 02 --}}
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <table id="example1" style="width: 100%"
                            class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th colspan="24" class="text-center bg-info">Quadro 2. Total de Alunos Repetentes
                                        por classe e idade <span>(que frequentam a mesma classes pela segunda ou mais
                                            vezes)</span></th>
                                </tr>
                                <tr>
                                    <th colspan="24" class="text-center bg-primary">Total de alunos por idade</th>
                                </tr>
                                <tr>
                                    <th rowspan="2">Idades/Classes</th>
                                    <th colspan="2">6 Anos</th>
                                    <th colspan="2">7 Anos</th>
                                    <th colspan="2">8 Anos</th>
                                    <th colspan="2">9 Anos</th>
                                    <th colspan="2">10 Anos</th>
                                    <th colspan="2">11 Anos</th>
                                    <th colspan="2">12 Anos</th>
                                    <th colspan="2">13 Anos</th>
                                    <th colspan="2">14 a 16 Anos</th>
                                    <th colspan="2">Total</th>
                                </tr>

                                <tr>
                                    <th>MF</th>
                                    <th>F</th>

                                    <th>MF</th>
                                    <th>F</th>

                                    <th>MF</th>
                                    <th>F</th>

                                    <th>MF</th>
                                    <th>F</th>

                                    <th>MF</th>
                                    <th>F</th>

                                    <th>MF</th>
                                    <th>F</th>

                                    <th>MF</th>
                                    <th>F</th>

                                    <th>MF</th>
                                    <th>F</th>

                                    <th>MF</th>
                                    <th>F</th>

                                    <th>MF</th>
                                    <th>F</th>
                                </tr>
                            </thead>
                            <tbody id="">
                                @php
                                $repetentes_masculino_6_anos_total = 0;
                                $repetentes_masculino_7_anos_total = 0;
                                $repetentes_masculino_8_anos_total = 0;
                                $repetentes_masculino_9_anos_total = 0;
                                $repetentes_masculino_10_anos_total = 0;
                                $repetentes_masculino_11_anos_total = 0;
                                $repetentes_masculino_12_anos_total = 0;
                                $repetentes_masculino_13_anos_total = 0;
                                $repetentes_masculino_14_anos_total = 0;


                                $repetentes_feminino_6_anos_total = 0;
                                $repetentes_feminino_7_anos_total = 0;
                                $repetentes_feminino_8_anos_total = 0;
                                $repetentes_feminino_9_anos_total = 0;
                                $repetentes_feminino_10_anos_total = 0;
                                $repetentes_feminino_11_anos_total = 0;
                                $repetentes_feminino_12_anos_total = 0;
                                $repetentes_feminino_13_anos_total = 0;
                                $repetentes_feminino_14_anos_total = 0;

                                @endphp

                                @foreach ($classes as $classe)
                                <tr>
                                    @php
                                    $result = App\Models\web\calendarios\Matricula::select(

                                    DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 6 THEN 1 ELSE 0 END) AS repetentes_masculino_6_anos'),
                                    DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 6 THEN 1 ELSE 0 END) AS repetentes_feminino_6_anos'),

                                    DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 7 THEN 1 ELSE 0 END) AS repetentes_masculino_7_anos'),
                                    DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 7 THEN 1 ELSE 0 END) AS repetentes_feminino_7_anos'),

                                    DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 8 THEN 1 ELSE 0 END) AS repetentes_masculino_8_anos'),
                                    DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 8 THEN 1 ELSE 0 END) AS repetentes_feminino_8_anos'),

                                    DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 9 THEN 1 ELSE 0 END) AS repetentes_masculino_9_anos'),
                                    DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 9 THEN 1 ELSE 0 END) AS repetentes_feminino_9_anos'),

                                    DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 10 THEN 1 ELSE 0 END) AS repetentes_masculino_10_anos'),
                                    DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 10 THEN 1 ELSE 0 END) AS repetentes_feminino_10_anos'),

                                    DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 11 THEN 1 ELSE 0 END) AS repetentes_masculino_11_anos'),
                                    DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 11 THEN 1 ELSE 0 END) AS repetentes_feminino_11_anos'),

                                    DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 12 THEN 1 ELSE 0 END) AS repetentes_masculino_12_anos'),
                                    DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 12 THEN 1 ELSE 0 END) AS repetentes_feminino_12_anos'),

                                    DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 13 THEN 1 ELSE 0 END) AS repetentes_masculino_13_anos'),
                                    DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 13 THEN 1 ELSE 0 END) AS repetentes_feminino_13_anos'),

                                    DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 14 AND 16 THEN 1 ELSE 0 END) AS repetentes_masculino_14_anos'),
                                    DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) BETWEEN 14 AND 16 THEN 1 ELSE 0 END) AS repetentes_feminino_14_anos'),
                                    )
                                    ->whereBetween(DB::raw('TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE())'),
                                    [6, 14])
                                    ->join('tb_estudantes', 'tb_matriculas.estudantes_id' , '=', 'tb_estudantes.id')
                                    ->where('tb_matriculas.classes_id', $classe->id)
                                    ->where('tb_matriculas.shcools_id', $escola->id)
                                    ->where('tb_matriculas.ano_lectivos_id', $anolectivoactual)
                                    ->first();
                                    @endphp

                                    <td>{{ $classe->classes }}</td>

                                    <td>{{ $result->repetentes_masculino_6_anos ?? 0 }}</td>
                                    <td>{{ $result->repetentes_feminino_6_anos ?? 0 }}</td>

                                    <td>{{ $result->repetentes_masculino_7_anos ?? 0 }}</td>
                                    <td>{{ $result->repetentes_feminino_7_anos ?? 0 }}</td>

                                    <td>{{ $result->repetentes_masculino_8_anos ?? 0 }}</td>
                                    <td>{{ $result->repetentes_feminino_8_anos ?? 0 }}</td>

                                    <td>{{ $result->repetentes_masculino_9_anos ?? 0 }}</td>
                                    <td>{{ $result->repetentes_feminino_9_anos ?? 0 }}</td>

                                    <td>{{ $result->repetentes_masculino_10_anos ?? 0 }}</td>
                                    <td>{{ $result->repetentes_feminino_10_anos ?? 0 }}</td>

                                    <td>{{ $result->repetentes_masculino_11_anos ?? 0 }}</td>
                                    <td>{{ $result->repetentes_feminino_11_anos ?? 0 }}</td>

                                    <td>{{ $result->repetentes_masculino_12_anos ?? 0 }}</td>
                                    <td>{{ $result->repetentes_feminino_12_anos ?? 0 }}</td>

                                    <td>{{ $result->repetentes_masculino_13_anos ?? 0 }}</td>
                                    <td>{{ $result->repetentes_feminino_13_anos ?? 0 }}</td>

                                    <td>{{ $result->repetentes_masculino_14_anos ?? 0 }}</td>
                                    <td>{{ $result->repetentes_feminino_14_anos ?? 0 }}</td>

                                    <td>{{ $result->repetentes_masculino_6_anos ?? 0 +
                                        $result->repetentes_masculino_7_anos ?? 0 + $result->repetentes_masculino_8_anos
                                        ?? 0 +
                                        $result->repetentes_masculino_9_anos ?? 0 +
                                        $result->repetentes_masculino_10_anos ?? 0 +
                                        $result->repetentes_masculino_11_anos ?? 0 +
                                        $result->repetentes_masculino_12_anos ?? 0 +
                                        $result->repetentes_masculino_13_anos ?? 0 +
                                        $result->repetentes_masculino_14_anos ?? 0 }}</td>
                                    <td>
                                        {{ $result->repetentes_feminino_6_anos ?? 0 +
                                        $result->repetentes_feminino_7_anos ?? 0 +
                                        $result->repetentes_feminino_8_anos ?? 0 + $result->repetentes_feminino_9_anos
                                        ?? 0 +
                                        $result->repetentes_feminino_10_anos ?? 0 + $result->repetentes_feminino_11_anos
                                        ?? 0 +
                                        $result->repetentes_feminino_12_anos ?? 0 + $result->repetentes_feminino_13_anos
                                        ?? 0 + $result->repetentes_feminino_14_anos ?? 0 }}
                                    </td>
                                </tr>

                                @php
                                $repetentes_masculino_6_anos_total += $result->repetentes_masculino_6_anos;
                                $repetentes_masculino_7_anos_total += $result->repetentes_masculino_7_anos;
                                $repetentes_masculino_8_anos_total += $result->repetentes_masculino_8_anos;
                                $repetentes_masculino_9_anos_total += $result->repetentes_masculino_9_anos;
                                $repetentes_masculino_10_anos_total += $result->repetentes_masculino_10_anos;
                                $repetentes_masculino_11_anos_total += $result->repetentes_masculino_11_anos;
                                $repetentes_masculino_12_anos_total += $result->repetentes_masculino_12_anos;
                                $repetentes_masculino_13_anos_total += $result->repetentes_masculino_13_anos;
                                $repetentes_masculino_14_anos_total += $result->repetentes_masculino_14_anos;

                                $repetentes_feminino_6_anos_total += $result->repetentes_feminino_6_anos;
                                $repetentes_feminino_7_anos_total += $result->repetentes_feminino_7_anos;
                                $repetentes_feminino_8_anos_total += $result->repetentes_feminino_8_anos;
                                $repetentes_feminino_9_anos_total += $result->repetentes_feminino_9_anos;
                                $repetentes_feminino_10_anos_total += $result->repetentes_feminino_10_anos;
                                $repetentes_feminino_11_anos_total += $result->repetentes_feminino_11_anos;
                                $repetentes_feminino_12_anos_total += $result->repetentes_feminino_12_anos;
                                $repetentes_feminino_13_anos_total += $result->repetentes_feminino_13_anos;
                                $repetentes_feminino_14_anos_total += $result->repetentes_feminino_14_anos;
                                @endphp

                                @endforeach
                                <tr>
                                    <td>TOTAL</td>

                                    <td>{{ $repetentes_masculino_6_anos_total ?? 0 }}</td>
                                    <td>{{ $repetentes_feminino_6_anos_total ?? 0 }}</td>

                                    <td>{{ $repetentes_masculino_7_anos_total ?? 0 }}</td>
                                    <td>{{ $repetentes_feminino_7_anos_total ?? 0 }}</td>

                                    <td>{{ $repetentes_masculino_8_anos_total ?? 0 }}</td>
                                    <td>{{ $repetentes_feminino_8_anos_total ?? 0 }}</td>

                                    <td>{{ $repetentes_masculino_9_anos_total ?? 0 }}</td>
                                    <td>{{ $repetentes_feminino_9_anos_total ?? 0 }}</td>

                                    <td>{{ $repetentes_masculino_10_anos_total ?? 0 }}</td>
                                    <td>{{ $repetentes_feminino_10_anos_total ?? 0 }}</td>

                                    <td>{{ $repetentes_masculino_11_anos_total ?? 0 }}</td>
                                    <td>{{ $repetentes_feminino_11_anos_total ?? 0 }}</td>

                                    <td>{{ $repetentes_masculino_12_anos_total ?? 0 }}</td>
                                    <td>{{ $repetentes_feminino_12_anos_total ?? 0 }}</td>

                                    <td>{{ $repetentes_masculino_13_anos_total ?? 0 }}</td>
                                    <td>{{ $repetentes_feminino_13_anos_total ?? 0 }}</td>

                                    <td>{{ $repetentes_masculino_14_anos_total ?? 0 }}</td>
                                    <td>{{ $repetentes_feminino_14_anos_total ?? 0 }}</td>

                                    <td>{{ $repetentes_masculino_6_anos_total ?? 0 + $repetentes_masculino_7_anos_total
                                        ?? 0 + $repetentes_masculino_8_anos_total ?? 0 +
                                        $repetentes_masculino_9_anos_total ?? 0 + $repetentes_masculino_10_anos_total ??
                                        0 + $repetentes_masculino_11_anos_total ?? 0 +
                                        $repetentes_masculino_12_anos_total ?? 0 + $repetentes_masculino_13_anos_total
                                        ?? 0 + $repetentes_masculino_14_anos_total ?? 0 }}</td>
                                    <td>{{ $repetentes_feminino_6_anos_total ?? 0 + $repetentes_feminino_7_anos_total ??
                                        0 + $repetentes_feminino_8_anos_total ?? 0 +
                                        $repetentes_feminino_9_anos_total ?? 0 + $repetentes_feminino_10_anos_total ?? 0
                                        + $repetentes_feminino_11_anos_total ?? 0 +
                                        $repetentes_feminino_12_anos_total ?? 0 + $repetentes_feminino_13_anos_total ??
                                        0 + $repetentes_feminino_14_anos_total ?? 0 }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- turmas e turnos--}}
            @include('admin.formularios.quadros.turmas-turnos')

            {{-- estudantes deficientes --}}
            @include('admin.formularios.quadros.estudantes-deficienca')

            {{-- professores por formação 03 --}}
            @include('admin.formularios.quadros.professores-formacao')

            {{-- professores pode idade 03 --}}
            @include('admin.formularios.quadros.professores-idade')

            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <table id="example1" style="width: 100%"
                            class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th colspan="15" class="text-center bg-info">Quadro 7. Manueis Escolares recebidos
                                    </th>
                                </tr>
                                <tr>
                                    <th rowspan="2"></th>
                                    @for ($i = 0; $i < 6; $i++) <th colspan="2" class="text-center">{{ $i + 1 }}</th>
                                        @endfor
                                        <th colspan="2" class="text-center">Total</th>
                                </tr>

                                <tr>
                                    @for ($i = 0; $i < 6; $i++) <th>Prof.</th>
                                        <th>Alunos</th>
                                        @endfor

                                        <th>Prof.</th>
                                        <th>Alunos</th>
                                </tr>
                            </thead>
                            <tbody id="">
                                <tr>
                                    <td>Língua Portugues</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>
                                </tr>

                                <tr>
                                    <td>Matématica</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>
                                </tr>

                                <tr>
                                    <td>Ciências</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>
                                </tr>

                                <tr>
                                    <td>Outros</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>
                                </tr>

                                <tr>
                                    <td>TOTAL</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
<!-- /.content -->
@endsection