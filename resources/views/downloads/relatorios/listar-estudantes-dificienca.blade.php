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
          <th colspan="11" class="text-center bg-info">Quadro 4. Alunos com deficiências. ({{ $ensino->nome }})</th>
        </tr>
        <tr>
          <th rowspan="2"></th>
          <th colspan="2" class="text-center">Visual</th>
          <th colspan="2" class="text-center">Auditivo</th>
          <th colspan="2" class="text-center">Motora</th>
          <th colspan="2" class="text-center">Outras</th>
          <th colspan="2" class="text-center">Total</th>
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

        </tr>
      </thead>
      <tbody id="">

        @php
        $estudante_dificiencia_visual_masculino = 0;
        $estudante_dificiencia_visual_feminino = 0;

        $estudante_dificiencia_auditiva_masculino = 0;
        $estudante_dificiencia_auditiva_feminino = 0;

        $estudante_dificiencia_motora_masculino = 0;
        $estudante_dificiencia_motora_feminino = 0;

        $estudante_dificiencia_outras_masculino = 0;
        $estudante_dificiencia_outras_feminino = 0;

        @endphp

        @foreach ($classes as $classe)
        <tr>

          @php
          $result = App\Models\web\calendarios\Matricula::select(

          // // estudantes com definciencias Visual
          DB::raw('SUM(CASE WHEN tb_estudantes.dificiencia = "Visual" AND tb_estudantes.genero
          = "Masculino" THEN 1 ELSE 0 END) AS estudante_dificiencia_visual_masculino'),
          DB::raw('SUM(CASE WHEN tb_estudantes.dificiencia = "Visual" AND tb_estudantes.genero
          = "Femenino" THEN 1 ELSE 0 END) AS estudante_dificiencia_visual_feminino'),

          // estudantes com definciencias Auditiva
          DB::raw('SUM(CASE WHEN tb_estudantes.dificiencia = "Auditiva" AND
          tb_estudantes.genero = "Masculino" THEN 1 ELSE 0 END) AS
          estudante_dificiencia_auditiva_masculino'),
          DB::raw('SUM(CASE WHEN tb_estudantes.dificiencia = "Auditiva" AND
          tb_estudantes.genero = "Femenino" THEN 1 ELSE 0 END) AS
          estudante_dificiencia_auditiva_feminino'),

          // estudantes com definciencias Motora
          DB::raw('SUM(CASE WHEN tb_estudantes.dificiencia = "Motora" AND tb_estudantes.genero
          = "Masculino" THEN 1 ELSE 0 END) AS estudante_dificiencia_motora_masculino'),
          DB::raw('SUM(CASE WHEN tb_estudantes.dificiencia = "Motora" AND tb_estudantes.genero
          = "Femenino" THEN 1 ELSE 0 END) AS estudante_dificiencia_motora_feminino'),

          // estudantes com definciencias Outras
          DB::raw('SUM(CASE WHEN tb_estudantes.dificiencia = "Outras" AND tb_estudantes.genero
          = "Masculino" THEN 1 ELSE 0 END) AS estudante_dificiencia_outras_masculino'),
          DB::raw('SUM(CASE WHEN tb_estudantes.dificiencia = "Outras" AND tb_estudantes.genero
          = "Femenino" THEN 1 ELSE 0 END) AS estudante_dificiencia_outras_feminino'),

          )
          ->join('tb_estudantes', 'tb_matriculas.estudantes_id' , '=', 'tb_estudantes.id')
          ->where('tb_matriculas.classes_id', $classe->id)
          ->where('tb_matriculas.shcools_id', $escola->id)
          ->where('tb_matriculas.ano_lectivos_id', $anolectivoactual)
          ->first();
          @endphp


          <td>{{ $classe->classes }}</td>

          <td>{{ $result->estudante_dificiencia_visual_masculino ?? 0 }}</td>
          <td>{{ $result->estudante_dificiencia_visual_feminino ?? 0 }}</td>

          @php
          $estudante_dificiencia_visual_masculino +=
          $result->estudante_dificiencia_visual_masculino;
          $estudante_dificiencia_visual_feminino +=
          $result->estudante_dificiencia_visual_feminino;
          @endphp

          <td>{{ $result->estudante_dificiencia_auditiva_masculino ?? 0 }}</td>
          <td>{{ $result->estudante_dificiencia_auditiva_feminino ?? 0 }}</td>

          @php
          $estudante_dificiencia_auditiva_masculino +=
          $result->estudante_dificiencia_auditiva_masculino;
          $estudante_dificiencia_auditiva_feminino +=
          $result->estudante_dificiencia_auditiva_feminino;
          @endphp

          <td>{{ $result->estudante_dificiencia_motora_masculino ?? 0 }}</td>
          <td>{{ $result->estudante_dificiencia_motora_feminino ?? 0 }}</td>

          @php
          $estudante_dificiencia_motora_masculino +=
          $result->estudante_dificiencia_motora_masculino;
          $estudante_dificiencia_motora_feminino +=
          $result->estudante_dificiencia_motora_feminino;
          @endphp

          <td>{{ $result->estudante_dificiencia_outras_masculino ?? 0 }}</td>
          <td>{{ $result->estudante_dificiencia_outras_feminino ?? 0 }}</td>

          @php
          $estudante_dificiencia_outras_masculino +=
          $result->estudante_dificiencia_outras_masculino;
          $estudante_dificiencia_outras_feminino +=
          $result->estudante_dificiencia_outras_feminino;
          @endphp

          <td>{{ $result->estudante_dificiencia_visual_masculino ?? 0 +
            $result->estudante_dificiencia_auditiva_masculino ?? 0 +
            $result->estudante_dificiencia_motora_masculino ?? 0 +
            $result->estudante_dificiencia_outras_masculino ?? 0 }}</td>
          <td>{{ $result->estudante_dificiencia_visual_feminino ?? 0 +
            $result->estudante_dificiencia_auditiva_feminino ?? 0 +
            $result->estudante_dificiencia_motora_feminino ?? 0 +
            $result->estudante_dificiencia_outras_feminino ?? 0 }}</td>
        </tr>
        @endforeach

        <tr>
          <td>Total</td>

          <td>{{ $estudante_dificiencia_visual_masculino ?? 0 }}</td>
          <td>{{ $estudante_dificiencia_visual_feminino ?? 0 }}</td>

          <td>{{ $estudante_dificiencia_auditiva_masculino ?? 0 }}</td>
          <td>{{ $estudante_dificiencia_auditiva_feminino ?? 0 }}</td>

          <td>{{ $estudante_dificiencia_motora_masculino ?? 0 }}</td>
          <td>{{ $estudante_dificiencia_motora_feminino ?? 0 }}</td>

          <td>{{ $estudante_dificiencia_outras_masculino ?? 0 }}</td>
          <td>{{ $estudante_dificiencia_outras_feminino ?? 0 }}</td>

          <td>{{ $estudante_dificiencia_visual_masculino ?? 0 +
            $estudante_dificiencia_auditiva_masculino ?? 0 +
            $estudante_dificiencia_motora_masculino ?? 0 +
            $estudante_dificiencia_outras_masculino ?? 0 }}</td>
          <td>{{ $estudante_dificiencia_visual_feminino ?? 0 +
            $estudante_dificiencia_auditiva_feminino ?? 0 +
            $estudante_dificiencia_motora_feminino ?? 0 +
            $estudante_dificiencia_outras_feminino ?? 0 }}</td>

        </tr>
      </tbody>
    </table>

  </div>

</body>

</html>