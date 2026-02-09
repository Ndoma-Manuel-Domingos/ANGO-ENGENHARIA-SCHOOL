@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Formulário Segundo Ciclo Regular</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Segundo Ciclo</a></li>
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
                                    <th colspan="2">14 ou menos Anos</th>
                                    <th colspan="2">15 Anos</th>
                                    <th colspan="2">16 Anos</th>
                                    <th colspan="2">17 Anos</th>
                                    <th colspan="2">18 Anos</th>
                                    <th colspan="2">19 Anos</th>
                                    <th colspan="2">20 Anos</th>
                                    <th colspan="2">21 ou mais Anos</th>
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
                                </tr>
                            </thead>
                            <tbody id="">
                                @php
                                $matriculados_masculino_14_anos_total = 0;
                                $matriculados_masculino_15_anos_total = 0;
                                $matriculados_masculino_16_anos_total = 0;
                                $matriculados_masculino_17_anos_total = 0;
                                $matriculados_masculino_18_anos_total = 0;
                                $matriculados_masculino_19_anos_total = 0;
                                $matriculados_masculino_20_anos_total = 0;
                                $matriculados_masculino_21_anos_total = 0;


                                $matriculados_feminino_14_anos_total = 0;
                                $matriculados_feminino_15_anos_total = 0;
                                $matriculados_feminino_16_anos_total = 0;
                                $matriculados_feminino_17_anos_total = 0;
                                $matriculados_feminino_18_anos_total = 0;
                                $matriculados_feminino_19_anos_total = 0;
                                $matriculados_feminino_20_anos_total = 0;
                                $matriculados_feminino_21_anos_total = 0;

                                @endphp

                                @foreach ($classes as $classe)
                                <tr>
                                    @php
                                    $result = App\Models\web\calendarios\Matricula::select(

                                    DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR,
                                    tb_estudantes.nascimento, CURDATE()) <= 14 THEN 1 ELSE 0 END) AS
                                        matriculados_masculino_14_anos'), DB::raw('SUM(CASE WHEN
                                        tb_estudantes.genero="Femenino" AND TIMESTAMPDIFF(YEAR,
                                        tb_estudantes.nascimento, CURDATE()) <=14 THEN 1 ELSE 0 END) AS
                                        matriculados_feminino_14_anos'), DB::raw('SUM(CASE WHEN
                                        tb_estudantes.genero="Masculino" AND TIMESTAMPDIFF(YEAR,
                                        tb_estudantes.nascimento, CURDATE())=15 THEN 1 ELSE 0 END) AS
                                        matriculados_masculino_15_anos'), DB::raw('SUM(CASE WHEN
                                        tb_estudantes.genero="Femenino" AND TIMESTAMPDIFF(YEAR,
                                        tb_estudantes.nascimento, CURDATE())=15 THEN 1 ELSE 0 END) AS
                                        matriculados_feminino_15_anos'), DB::raw('SUM(CASE WHEN
                                        tb_estudantes.genero="Masculino" AND TIMESTAMPDIFF(YEAR,
                                        tb_estudantes.nascimento, CURDATE())=16 THEN 1 ELSE 0 END) AS
                                        matriculados_masculino_16_anos'), DB::raw('SUM(CASE WHEN
                                        tb_estudantes.genero="Femenino" AND TIMESTAMPDIFF(YEAR,
                                        tb_estudantes.nascimento, CURDATE())=16 THEN 1 ELSE 0 END) AS
                                        matriculados_feminino_16_anos'), DB::raw('SUM(CASE WHEN
                                        tb_estudantes.genero="Masculino" AND TIMESTAMPDIFF(YEAR,
                                        tb_estudantes.nascimento, CURDATE())=17 THEN 1 ELSE 0 END) AS
                                        matriculados_masculino_17_anos'), DB::raw('SUM(CASE WHEN
                                        tb_estudantes.genero="Femenino" AND TIMESTAMPDIFF(YEAR,
                                        tb_estudantes.nascimento, CURDATE())=17 THEN 1 ELSE 0 END) AS
                                        matriculados_feminino_17_anos'), DB::raw('SUM(CASE WHEN
                                        tb_estudantes.genero="Masculino" AND TIMESTAMPDIFF(YEAR,
                                        tb_estudantes.nascimento, CURDATE())=18 THEN 1 ELSE 0 END) AS
                                        matriculados_masculino_18_anos'), DB::raw('SUM(CASE WHEN
                                        tb_estudantes.genero="Femenino" AND TIMESTAMPDIFF(YEAR,
                                        tb_estudantes.nascimento, CURDATE())=18 THEN 1 ELSE 0 END) AS
                                        matriculados_feminino_18_anos'), DB::raw('SUM(CASE WHEN
                                        tb_estudantes.genero="Masculino" AND TIMESTAMPDIFF(YEAR,
                                        tb_estudantes.nascimento, CURDATE())=19 THEN 1 ELSE 0 END) AS
                                        matriculados_masculino_19_anos'), DB::raw('SUM(CASE WHEN
                                        tb_estudantes.genero="Femenino" AND TIMESTAMPDIFF(YEAR,
                                        tb_estudantes.nascimento, CURDATE())=19 THEN 1 ELSE 0 END) AS
                                        matriculados_feminino_19_anos'), DB::raw('SUM(CASE WHEN
                                        tb_estudantes.genero="Masculino" AND TIMESTAMPDIFF(YEAR,
                                        tb_estudantes.nascimento, CURDATE())=20 THEN 1 ELSE 0 END) AS
                                        matriculados_masculino_20_anos'), DB::raw('SUM(CASE WHEN
                                        tb_estudantes.genero="Femenino" AND TIMESTAMPDIFF(YEAR,
                                        tb_estudantes.nascimento, CURDATE())=20 THEN 1 ELSE 0 END) AS
                                        matriculados_feminino_20_anos'), DB::raw('SUM(CASE WHEN
                                        tb_estudantes.genero="Masculino" AND TIMESTAMPDIFF(YEAR,
                                        tb_estudantes.nascimento, CURDATE())>= 21 THEN 1 ELSE 0 END) AS
                                        matriculados_masculino_21_anos'),
                                        DB::raw('SUM(CASE WHEN tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR,
                                        tb_estudantes.nascimento, CURDATE()) >= 21 THEN 1 ELSE 0 END) AS
                                        matriculados_feminino_21_anos'),

                                        )
                                        ->whereBetween(DB::raw('TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento,
                                        CURDATE())'), [13, 30])
                                        ->join('tb_estudantes', 'tb_matriculas.estudantes_id' , '=', 'tb_estudantes.id')
                                        ->where('tb_matriculas.classes_id', $classe->id)
                                        ->where('tb_matriculas.shcools_id', $escola->id)
                                        ->where('tb_matriculas.ano_lectivos_id', $anolectivoactual)
                                        ->first();
                                        @endphp

                                        <td>{{ $classe->classes }}</td>

                                        <td>{{ $result->matriculados_masculino_15_anos ?? 0 }}</td>
                                        <td>{{ $result->matriculados_feminino_15_anos ?? 0 }}</td>

                                        <td>{{ $result->matriculados_masculino_16_anos ?? 0 }}</td>
                                        <td>{{ $result->matriculados_feminino_16_anos ?? 0 }}</td>

                                        <td>{{ $result->matriculados_masculino_17_anos ?? 0 }}</td>
                                        <td>{{ $result->matriculados_feminino_17_anos ?? 0 }}</td>

                                        <td>{{ $result->matriculados_masculino_18_anos ?? 0 }}</td>
                                        <td>{{ $result->matriculados_feminino_18_anos ?? 0 }}</td>

                                        <td>{{ $result->matriculados_masculino_19_anos ?? 0 }}</td>
                                        <td>{{ $result->matriculados_feminino_19_anos ?? 0 }}</td>

                                        <td>{{ $result->matriculados_masculino_20_anos ?? 0 }}</td>
                                        <td>{{ $result->matriculados_feminino_20_anos ?? 0 }}</td>

                                        <td>{{ $result->matriculados_masculino_21_anos ?? 0 }}</td>
                                        <td>{{ $result->matriculados_feminino_21_anos ?? 0 }}</td>

                                        <td>{{ $result->matriculados_masculino_15_anos ?? 0 +
                                            $result->matriculados_masculino_16_anos ?? 0 +
                                            $result->matriculados_masculino_17_anos ?? 0 +
                                            $result->matriculados_masculino_18_anos ?? 0 +
                                            $result->matriculados_masculino_19_anos ?? 0 +
                                            $result->matriculados_masculino_20_anos ?? 0 +
                                            $result->matriculados_masculino_21_anos ?? 0}}
                                        </td>
                                        <td>
                                            {{ $result->matriculados_feminino_15_anos ?? 0 +
                                            $result->matriculados_feminino_15_anos ?? 0 +
                                            $result->matriculados_feminino_16_anos ?? 0 +
                                            $result->matriculados_feminino_17_anos ?? 0 +
                                            $result->matriculados_feminino_18_anos ?? 0 +
                                            $result->matriculados_feminino_19_anos ?? 0 +
                                            $result->matriculados_feminino_20_anos ?? 0 +
                                            $result->matriculados_feminino_21_anos ?? 0 }}
                                        </td>
                                </tr>

                                @php
                                $matriculados_masculino_15_anos_total += $result->matriculados_masculino_15_anos;
                                $matriculados_masculino_16_anos_total += $result->matriculados_masculino_16_anos;
                                $matriculados_masculino_17_anos_total += $result->matriculados_masculino_17_anos;
                                $matriculados_masculino_18_anos_total += $result->matriculados_masculino_18_anos;
                                $matriculados_masculino_19_anos_total += $result->matriculados_masculino_19_anos;
                                $matriculados_masculino_20_anos_total += $result->matriculados_masculino_20_anos;
                                $matriculados_masculino_21_anos_total += $result->matriculados_masculino_21_anos;

                                $matriculados_feminino_15_anos_total += $result->matriculados_feminino_15_anos;
                                $matriculados_feminino_16_anos_total += $result->matriculados_feminino_16_anos;
                                $matriculados_feminino_17_anos_total += $result->matriculados_feminino_17_anos;
                                $matriculados_feminino_18_anos_total += $result->matriculados_feminino_18_anos;
                                $matriculados_feminino_19_anos_total += $result->matriculados_feminino_19_anos;
                                $matriculados_feminino_20_anos_total += $result->matriculados_feminino_20_anos;
                                $matriculados_feminino_21_anos_total += $result->matriculados_feminino_21_anos;
                                @endphp

                                @endforeach
                                <tr>
                                    <td>TOTAL</td>

                                    <td>{{ $matriculados_masculino_15_anos_total ?? 0 }}</td>
                                    <td>{{ $matriculados_feminino_15_anos_total ?? 0 }}</td>

                                    <td>{{ $matriculados_masculino_16_anos_total ?? 0 }}</td>
                                    <td>{{ $matriculados_feminino_16_anos_total ?? 0 }}</td>

                                    <td>{{ $matriculados_masculino_17_anos_total ?? 0 }}</td>
                                    <td>{{ $matriculados_feminino_17_anos_total ?? 0 }}</td>

                                    <td>{{ $matriculados_masculino_18_anos_total ?? 0 }}</td>
                                    <td>{{ $matriculados_feminino_18_anos_total ?? 0 }}</td>

                                    <td>{{ $matriculados_masculino_19_anos_total ?? 0 }}</td>
                                    <td>{{ $matriculados_feminino_19_anos_total ?? 0 }}</td>

                                    <td>{{ $matriculados_masculino_20_anos_total ?? 0 }}</td>
                                    <td>{{ $matriculados_feminino_20_anos_total ?? 0 }}</td>

                                    <td>{{ $matriculados_masculino_21_anos_total ?? 0 }}</td>
                                    <td>{{ $matriculados_feminino_21_anos_total ?? 0 }}</td>

                                    <td>{{ $matriculados_masculino_15_anos_total ?? 0 +
                                        $matriculados_masculino_16_anos_total ?? 0 +
                                        $matriculados_masculino_17_anos_total ?? 0 +
                                        $matriculados_masculino_18_anos_total ?? 0 +
                                        $matriculados_masculino_19_anos_total ?? 0 +
                                        $matriculados_masculino_20_anos_total ?? 0 +
                                        $matriculados_masculino_21_anos_total ?? 0 }}
                                    </td>
                                    <td>{{ $matriculados_feminino_15_anos_total ?? 0 +
                                        $matriculados_feminino_16_anos_total ?? 0 + $matriculados_feminino_17_anos_total
                                        ?? 0 +
                                        $matriculados_feminino_18_anos_total ?? 0 + $matriculados_feminino_19_anos_total
                                        ?? 0 + $matriculados_feminino_20_anos_total ?? 0 +
                                        $matriculados_feminino_21_anos_total ?? 0 }}
                                    </td>
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
                                    <th colspan="2">15 Anos</th>
                                    <th colspan="2">16 Anos</th>
                                    <th colspan="2">17 Anos</th>
                                    <th colspan="2">18 Anos</th>
                                    <th colspan="2">19 Anos</th>
                                    <th colspan="2">20 Anos</th>
                                    <th colspan="2">21 ou mais Anos</th>
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
                                </tr>
                            </thead>
                            <tbody id="">

                                @php
                                $repetentes_masculino_15_anos_total = 0;
                                $repetentes_masculino_16_anos_total = 0;
                                $repetentes_masculino_17_anos_total = 0;
                                $repetentes_masculino_18_anos_total = 0;
                                $repetentes_masculino_19_anos_total = 0;
                                $repetentes_masculino_20_anos_total = 0;
                                $repetentes_masculino_21_anos_total = 0;


                                $repetentes_feminino_15_anos_total = 0;
                                $repetentes_feminino_16_anos_total = 0;
                                $repetentes_feminino_17_anos_total = 0;
                                $repetentes_feminino_18_anos_total = 0;
                                $repetentes_feminino_19_anos_total = 0;
                                $repetentes_feminino_20_anos_total = 0;
                                $repetentes_feminino_21_anos_total = 0;

                                @endphp

                                @foreach ($classes as $classe)
                                <tr>
                                    @php
                                        $result = App\Models\web\calendarios\Matricula::select(
    
                                        DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 15 THEN 1 ELSE 0 END) AS repetintes_masculino_15_anos'),
                                        DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 15 THEN 1 ELSE 0 END) AS repetintes_feminino_15_anos'),
    
                                        DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 16 THEN 1 ELSE 0 END) AS repetintes_masculino_16_anos'),
                                        DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 16 THEN 1 ELSE 0 END) AS repetintes_feminino_16_anos'),
    
                                        DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 17 THEN 1 ELSE 0 END) AS repetintes_masculino_17_anos'),
                                        DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 17 THEN 1 ELSE 0 END) AS repetintes_feminino_17_anos'),
    
                                        DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 18 THEN 1 ELSE 0 END) AS repetintes_masculino_18_anos'),
                                        DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 18 THEN 1 ELSE 0 END) AS repetintes_feminino_18_anos'),
    
                                        DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 19 THEN 1 ELSE 0 END) AS repetintes_masculino_19_anos'),
                                        DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 19 THEN 1 ELSE 0 END) AS repetintes_feminino_19_anos'),
    
                                        DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 20 THEN 1 ELSE 0 END) AS repetintes_masculino_20_anos'),
                                        DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) = 20 THEN 1 ELSE 0 END) AS repetintes_feminino_20_anos'),
    
                                        DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_estudantes.genero = "Masculino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) >= 21 THEN 1 ELSE 0 END) AS repetintes_masculino_21_anos'),
                                        DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "Repitente" AND tb_estudantes.genero = "Femenino" AND TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE()) >= 21 THEN 1 ELSE 0 END) AS repetintes_feminino_21_anos'),
                                        )
                                        ->whereBetween(DB::raw('TIMESTAMPDIFF(YEAR, tb_estudantes.nascimento, CURDATE())'),
                                        [15, 30])
                                        ->join('tb_estudantes', 'tb_matriculas.estudantes_id' , '=', 'tb_estudantes.id')
                                        ->where('tb_matriculas.classes_id', $classe->id)
                                        ->where('tb_matriculas.shcools_id', $escola->id)
                                        ->where('tb_matriculas.ano_lectivos_id', $anolectivoactual)
                                        ->first();
                                    @endphp

                                    <td>{{ $classe->classes }}</td>

                                    <td>{{ $result->repetentes_masculino_15_anos ?? 0 }}</td>
                                    <td>{{ $result->repetentes_feminino_15_anos ?? 0 }}</td>

                                    <td>{{ $result->repetentes_masculino_16_anos ?? 0 }}</td>
                                    <td>{{ $result->repetentes_feminino_16_anos ?? 0 }}</td>

                                    <td>{{ $result->repetentes_masculino_17_anos ?? 0 }}</td>
                                    <td>{{ $result->repetentes_feminino_17_anos ?? 0 }}</td>

                                    <td>{{ $result->repetentes_masculino_18_anos ?? 0 }}</td>
                                    <td>{{ $result->repetentes_feminino_18_anos ?? 0 }}</td>

                                    <td>{{ $result->repetentes_masculino_19_anos ?? 0 }}</td>
                                    <td>{{ $result->repetentes_feminino_19_anos ?? 0 }}</td>

                                    <td>{{ $result->repetentes_masculino_20_anos ?? 0 }}</td>
                                    <td>{{ $result->repetentes_feminino_20_anos ?? 0 }}</td>

                                    <td>{{ $result->repetentes_masculino_21_anos ?? 0 }}</td>
                                    <td>{{ $result->repetentes_feminino_21_anos ?? 0 }}</td>

                                    <td>{{ $result->repetentes_masculino_15_anos ?? 0 +
                                        $result->repetentes_masculino_16_anos ?? 0 +
                                        $result->repetentes_masculino_17_anos ?? 0 +
                                        $result->repetentes_masculino_18_anos ?? 0 +
                                        $result->repetentes_masculino_19_anos ?? 0 +
                                        $result->repetentes_masculino_20_anos ?? 0 +
                                        $result->repetentes_masculino_21_anos ?? 0}}
                                    </td>
                                    <td>
                                        {{ $result->repetentes_feminino_15_anos ?? 0 + $result->repetentes_feminino_15_anos ?? 0 +
                                        $result->repetentes_feminino_16_anos ?? 0 + $result->repetentes_feminino_17_anos ?? 0 + $result->repetentes_feminino_18_anos ?? 0 + $result->repetentes_feminino_19_anos
                                        ?? 0 + $result->repetentes_feminino_20_anos ?? 0 + $result->repetentes_feminino_21_anos ?? 0 }}
                                    </td>
                                </tr>

                                @php
                                $repetentes_masculino_15_anos_total += $result->repetentes_masculino_15_anos;
                                $repetentes_masculino_16_anos_total += $result->repetentes_masculino_16_anos;
                                $repetentes_masculino_17_anos_total += $result->repetentes_masculino_17_anos;
                                $repetentes_masculino_18_anos_total += $result->repetentes_masculino_18_anos;
                                $repetentes_masculino_19_anos_total += $result->repetentes_masculino_19_anos;
                                $repetentes_masculino_20_anos_total += $result->repetentes_masculino_20_anos;
                                $repetentes_masculino_21_anos_total += $result->repetentes_masculino_21_anos;

                                $repetentes_feminino_15_anos_total += $result->repetentes_feminino_15_anos;
                                $repetentes_feminino_16_anos_total += $result->repetentes_feminino_16_anos;
                                $repetentes_feminino_17_anos_total += $result->repetentes_feminino_17_anos;
                                $repetentes_feminino_18_anos_total += $result->repetentes_feminino_18_anos;
                                $repetentes_feminino_19_anos_total += $result->repetentes_feminino_19_anos;
                                $repetentes_feminino_20_anos_total += $result->repetentes_feminino_20_anos;
                                $repetentes_feminino_21_anos_total += $result->repetentes_feminino_21_anos;
                                @endphp

                                @endforeach
                                <tr>
                                    <td>TOTAL</td>

                                    <td>{{ $repetentes_masculino_15_anos_total ?? 0 }}</td>
                                    <td>{{ $repetentes_feminino_15_anos_total ?? 0 }}</td>

                                    <td>{{ $repetentes_masculino_16_anos_total ?? 0 }}</td>
                                    <td>{{ $repetentes_feminino_16_anos_total ?? 0 }}</td>

                                    <td>{{ $repetentes_masculino_17_anos_total ?? 0 }}</td>
                                    <td>{{ $repetentes_feminino_17_anos_total ?? 0 }}</td>

                                    <td>{{ $repetentes_masculino_18_anos_total ?? 0 }}</td>
                                    <td>{{ $repetentes_feminino_18_anos_total ?? 0 }}</td>

                                    <td>{{ $repetentes_masculino_19_anos_total ?? 0 }}</td>
                                    <td>{{ $repetentes_feminino_19_anos_total ?? 0 }}</td>

                                    <td>{{ $repetentes_masculino_20_anos_total ?? 0 }}</td>
                                    <td>{{ $repetentes_feminino_20_anos_total ?? 0 }}</td>

                                    <td>{{ $repetentes_masculino_21_anos_total ?? 0 }}</td>
                                    <td>{{ $repetentes_feminino_21_anos_total ?? 0 }}</td>

                                    <td>{{ $repetentes_masculino_15_anos_total ?? 0 +
                                        $repetentes_masculino_16_anos_total ?? 0 + $repetentes_masculino_17_anos_total
                                        ?? 0 +
                                        $repetentes_masculino_18_anos_total ?? 0 + $repetentes_masculino_19_anos_total
                                        ?? 0 + $repetentes_masculino_20_anos_total ?? 0 +
                                        $repetentes_masculino_21_anos_total ?? 0 }}
                                    </td>
                                    <td>{{ $repetentes_feminino_15_anos_total ?? 0 + $repetentes_feminino_16_anos_total
                                        ?? 0 + $repetentes_feminino_17_anos_total ?? 0 +
                                        $repetentes_feminino_18_anos_total ?? 0 + $repetentes_feminino_19_anos_total ??
                                        0 + $repetentes_feminino_20_anos_total ?? 0 +
                                        $repetentes_feminino_21_anos_total ?? 0 }}
                                    </td>
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
                                    <th colspan="3" class="text-center bg-info">Quadro 7. Salas de laboratórios</th>
                                </tr>
                                <tr>
                                    <th rowspan=""></th>
                                    <th colspan="" class="text-center">Nº Salas</th>
                                    <th colspan="" class="text-center">Tem Equipamentos?</th>
                                </tr>
                            </thead>
                            <tbody id="">
                                <tr>
                                    <td>Fisíca</td>

                                    <td class="text-center">0</td>

                                    <td class="text-center">0</td>

                                </tr>

                                <tr>
                                    <td>Biologia</td>

                                    <td class="text-center">0</td>

                                    <td class="text-center">0</td>
                                </tr>

                                <tr>
                                    <td>Química</td>
                                    <td class="text-center">0</td>
                                    <td class="text-center">0</td>
                                </tr>

                                <tr>
                                    <td>Outros</td>

                                    <td class="text-center">0</td>

                                    <td class="text-center">0</td>
                                </tr>

                                <tr>
                                    <td>TOTAL</td>

                                    <td class="text-center">0</td>

                                    <td class="text-center">0</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <table id="example1" style="width: 100%"
                            class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th colspan="7" class="text-center bg-info">Quadro 8. Pessoal Docente por
                                        Disciplina(Diante dos alunos)</th>
                                </tr>
                                <tr>
                                    <th rowspan="2"></th>
                                    <th colspan="2" class="text-center">C/Formação Pedagógica</th>
                                    <th colspan="2" class="text-center">S/Formação Pedagógica</th>
                                    <th colspan="2" class="text-center">Total</th>
                                </tr>

                                <tr>
                                    <th>MF</th>
                                    <th>F</th>

                                    <th>MF</th>
                                    <th>F</th>

                                    <th>MF</th>
                                    <th>F</th>
                                </tr>
                            </thead>
                            <tbody id="">
                                <tr>
                                    <td>Lingua Portuguêsa</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>
                                </tr>

                                <tr>
                                    <td>Língua Estrageira</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>
                                </tr>

                                <tr>
                                    <td>Matemática</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>
                                </tr>

                                <tr>
                                    <td>Informática</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>
                                </tr>

                                <tr>
                                    <td>Educação de Física</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>
                                </tr>

                                <tr>
                                    <td>Filosofia</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>
                                </tr>

                                <tr>
                                    <td>Física</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>
                                </tr>

                                <tr>
                                    <td>Química</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>
                                </tr>

                                <tr>
                                    <td>Biologia</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>
                                </tr>

                                <tr>
                                    <td>Geologia</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>
                                </tr>

                                <tr>
                                    <td>Geometria Descritiva</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>
                                </tr>

                                <tr>
                                    <td>Sociologia</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>
                                </tr>

                                <tr>
                                    <td>Pscologia</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>
                                </tr>

                                <tr>
                                    <td>Introdução ao Direito</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>
                                </tr>

                                <tr>
                                    <td>Introdução á Economia</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>
                                </tr>

                                <tr>
                                    <td>História</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>
                                </tr>

                                <tr>
                                    <td>Geográfia</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>
                                </tr>

                                <tr>
                                    <td>Desenvolvimento Ecónomico e Social</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>
                                </tr>

                                <tr>
                                    <td>Antropologia</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>
                                </tr>


                                <tr>
                                    <td>Literatura</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>
                                </tr>

                                <tr>
                                    <td>Desenho</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>
                                </tr>

                                <tr>
                                    <td>Teoria Pratica do Design</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>
                                </tr>

                                <tr>
                                    <td>Historia das Artes</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>
                                </tr>

                                <tr>
                                    <td>Tecnicas de Expressão Artistica</td>

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