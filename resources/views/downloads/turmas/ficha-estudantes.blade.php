<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <title>{{ $titulo }}</title>
  <style>
    *{
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
  
    @page {
      margin: 2cm 1.5cm;
    }

    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 11px;
      margin: 20px;
      padding: 0;
    }

    .header {
      text-align: center;
      margin-bottom: 20px;
      border-bottom: 1px solid #000;
      padding-bottom: 10px;
    }

    .header img {
      height: 60px;
      margin-bottom: 10px;
    }

    .empresa {
      font-weight: bold;
      font-size: 14px;
    }

    .titulo {
      margin-top: 10px;
      font-size: 16px;
      font-weight: bold;
      text-transform: uppercase;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
      page-break-inside: auto;
    }

    table, th, td {
      border: 1px solid #000;
    }

    th, td {
      padding: 6px;
      text-align: left;
    }

    th {
      background-color: #f2f2f2;
    }

    tr {
      page-break-inside: avoid;
      page-break-after: auto;
    }

    .footer {
      position: fixed;
      bottom: 5px;
      left: 0;
      right: 0;
      text-align: center;
      font-size: 11px;
      color: #777;
    }

    .pagenum:before {
      content: counter(page);
    }
  </style>
</head>
<body>

<div class="header">
  <img src="{{ $logotipo }}" alt="Logotipo"><br>
  <div class="empresa">{{ $escola->nome }}</div>
  <div>{{ $escola->endereco ?? "" }} | NIF: {{ $escola->documento ?? "" }}</div>
  <div class="titulo">{{ $titulo }}</div>
</div>

<div style="margin-top: 0px;border-bottom: 1px solid #000;">
    <p>
        Turma: <strong>{{ $turma->turma }}</strong> |
        classe: <strong>{{ $classe->classes }}</strong> | 
        Sala <strong>{{ $sala->salas }}</strong> |
        Curso <strong>{{ $curso->curso }}</strong> |
        Turno: <strong>{{ $turno->turno }} </strong> |
        Ano Lectivo: <strong>{{ $ano->ano }}</strong>. 
    </p>
</div>

<table>
  <thead>
    <tr>
      <th>#</th>
      <th>Nome</th>
      <th>Nº Processo</th>
      <th>Gênero</th>
      <th>Telefone</th>
      <th>Data de Confirmação</th>
    </tr>
  </thead>
  <tbody>
    @php $numero = 0; @endphp
    @foreach ($estudantes as $index => $estudante)
      @php $numero = $numero + 1; @endphp
      <tr>
        <td>{{ $numero }}</td>
        <td>{{ $estudante->estudante->nome }} {{ $estudante->estudante->sobre_nome }}</td>
        <td>{{ $estudante->estudante->numero_processo }}</td>
        <td>{{ $estudante->estudante->genero }}</td>
        <td>{{ $estudante->estudante->telefone_pai }}</td>
        <td>{{ \Carbon\Carbon::parse($estudante->data_at)->format('d/m/Y') }}</td>
      </tr>
    @endforeach
  </tbody>
</table>

<!-- Rodapé com paginação -->
<div class="footer">
  <span class="pagenum"></span> <script type="text/php">
    if (isset($pdf)) {
      $pdf->page_script(function ($pageNumber, $pageCount, $pdf) {
          $pdf->text(270, 820, "Página $pageNumber de $pageCount", null, 10);
      });
    }
  </script>
</div>

</body>
</html>

