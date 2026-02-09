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
      font-size: 11px;
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

  <!-- Table row -->
  <div class="row" style="margin-top: 170px">
    <table>
      <thead>
        <tr>
          <th colspan="4" style="text-align: left">TOTAL REGISTROS:</th>
          <th colspan="6" style="text-align: left">{{ count($matriculas) }}</th>
        </tr>

        <tr>
          <th  style="text-align: left" colspan="4">Estudantes:
            @if ($status == 'Admitido')
            <span style="color: green">{{ $status }}s(as)</span>
            @else
            @if ($status == 'Nao Admitido')
            <span style="color: red">{{ $status }}s(as)</span>
            @else
            <span>Todos</span>
            @endif
            @endif
          </th>
          <th style="text-align: left" colspan="3">
            COM MÉDIA: {{ $media > 0 ? $media : 'TODAS' }}
          </th>
          <th style="text-align: left" colspan="3">
            COM IDADE: {{ $idade ?? 'TODAS' }}
          </th>
        </tr>

        <tr>
          <th style="text-align: left" colspan="4">CURSO: {{ $curso ? $curso->curso : 'TODOS' }}</th>
          <th style="text-align: left" colspan="3">CLASSE: {{ $classe ? $classe->classes : 'TODAS' }}</th>
          <th style="text-align: left" colspan="3">TURNO: {{ $turno ? $turno->turno : 'TODOS' }}</th>
        </tr>

        <tr>
          <th style="text-align: left">Nº</th>
          <th style="text-align: left">Nome</th>
          <th style="text-align: left">Sexo</th>
          <th style="text-align: left">Idade</th>
          <th style="text-align: left">Bilhete</th>
          <th style="text-align: left">Classe</th>
          <th style="text-align: left">Curso</th>
          <th style="text-align: left">Turno</th>
          <th style="text-align: left">Média</th>
          <th style="text-align: left">Status</th>
        </tr>
      </thead>

      <tbody class="text-center">
        @if ($matriculas)
        @foreach ($matriculas as $key => $item)
        <tr>
          <td style="text-align: left">{{ $key + 1 }}</td>
          <td style="text-align: left">{{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}</td>
          @if($item->estudante->genero == "Masculino")
          <td style="text-align: left">M</td>
          @else
          <td style="text-align: left">F</td>
          @endif
          <td style="text-align: left">{{ $item->estudante->idade($item->estudante->nascimento) }}</td>
          <td style="text-align: left">{{ $item->estudante->bilheite }}</td>
          <td style="text-align: left">{{ $item->classe->classes }}</td>
          <td style="text-align: left">{{ $item->curso->curso }}</td>
          <td style="text-align: left">{{ $item->turno->turno }}</td>
          <td style="text-align: left">{{ $item->media }}</td>
          @if ($item->status_inscricao == 'Admitido')
          <td style="text-align: left" class="text-warning" style="color: green">Adimitido(a)</td>
          @endif
          @if ($item->status_inscricao == 'Nao Admitido')
          <td style="text-align: left" class="text-danger" style="color: red">Não Adimitido(a)</td>
          @endif

        </tr>
        @endforeach

        @endif

      </tbody>
    </table>
  </div>

</body>

</html>