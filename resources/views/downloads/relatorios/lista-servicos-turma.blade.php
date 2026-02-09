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
      <th>Nº </th>
      <th>Serviço</th>
      <th>Estado</th>
      <th>Dia Inicial Pag.</th>
      <th>Dia Final Pag.</th>
      
      <th>Data Inicial Pag.</th>
      <th>Data Final Pag.</th>
      
      <th>Atraso de:</th>
      <th>Taxa multa 1º Atraso</th>
      
      <th>Atraso de:</th>
      <th>Taxa multa 2º Atraso</th>
      
      <th>Atraso de:</th>
      <th>Taxa multa 3º Atraso</th>
      
      <th>Preço</th>
      <th>Tipo</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($servicos as $key => $item)
      <tr>
        <td>{{ $key + 1 }} </td>
        <td>{{ $item->servico->servico ?? "" }} </td>
        <td>{{ $item->status }} </td>
        <td>dia {{ $item->intervalo_pagamento_inicio }} </td>
        <td>até {{ $item->intervalo_pagamento_final }} </td>
        <td>{{ $item->data_inicio }} </td>
        <td>{{ $item->data_final }} </td>
        
        <td>{{ $item->taxa_multa1_dia }} dia(s) </td>
        <td>{{ $item->taxa_multa1 }} % do preço</td>
        
        <td>{{ $item->taxa_multa2_dia }} dia(s) </td>
        <td>{{ $item->taxa_multa2 }} % do preço</td>
        
        <td>{{ $item->taxa_multa3_dia }} dia(s) </td>
        <td>{{ $item->taxa_multa3 }} % do preço</td>
        
        <td>{{ number_format($item->preco, 2, ',', '.') }} Kz</td>
        <td>{{ $item->pagamento }}</td>
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