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
    @if ($meses)
        <tr>
            <th rowspan="2" style="font-size: 10px">Cod</th>
            <th rowspan="2" style="font-size: 10px;width: 50px">Nº Proc.</th>
            <th rowspan="2" style="font-size: 10px;text-align: left;width: 190px">Nome Completo</th>
            <th rowspan="2" style="font-size: 10px">Sexo</th>
            <th colspan="12" style="font-size: 10px">Meses</th>
        </tr>
        <tr>
            @foreach ($meses as $item)
            <th style="font-size: 10px">{{ $item->abreviacao }}.</th>
            @endforeach
        </tr> 
    @endif
  </thead>
  <tbody>
    @php $pago = $isento = $divida = $n_pago = 0; @endphp
    @foreach ($estudantes as $key => $items)
      @php
        $cartao = (new App\Models\web\estudantes\CartaoEstudante)::where('estudantes_id', $items->estudantes_id)
          ->where('servicos_id', $servico->id)
          ->where('ano_lectivos_id', $ano->id)
          ->get();
      @endphp
      <tr>
          <td style="font-size: 10px">{{ $key + 1 }}</td>
          <td style="text-transform: capitalize;font-size: 10px">{{ $items->estudante->numero_processo ?? "" }}</td>
          <td style="text-transform: capitalize;font-size: 10px">{{ $items->estudante->nome ?? "" }} {{ $items->estudante->sobre_nome ?? "" }}</td>
          <td style="text-align: center;font-size: 10px">
          @if ($items->genero == 'Masculino') M @else F @endif
          </td>
          @foreach ($cartao as $cart)
          <td style="font-size: 8px">
              @if ($cart->status == "divida")
                <span style="color: #e79e15;text-transform: uppercase;font-size: 8px;"><strong>DIVIDA</strong></span> 
                <br> Multa: <small>{{ number_format($cart->multa, 2, ',', '.')  }}</small>
                @php $divida += $cart->preco_unitario + $cart->multa; @endphp
              @else
                  @if ($cart->status == "Nao Pago")
                   <span style="color: #700b0b;text-transform: uppercase;font-size: 8px;">N/Pago</strong></span>  
                   <br> Multa: <small>{{ number_format($cart->multa, 2, ',', '.')  }}</small>  
                   @php $n_pago += $cart->preco_unitario + $cart->multa; @endphp
                  @else
                    @if ($cart->status == "Isento")
                    @php $isento += $cart->preco_unitario + $cart->multa; @endphp
                    <span style="color: #646464;text-transform: uppercase;font-size: 8px;"><strong>Isento</strong></span>
                    @else
                    <span style="color: #0aa023;text-transform: uppercase;font-size: 8px;"><strong>Pago</strong></span>
                    @php $pago += $cart->preco_unitario + $cart->multa; @endphp
                    @endif
                  @endif
              @endif
          </td>
          @endforeach    
      </tr>
    @endforeach
  </tbody>
  
  <tfoot>
    <tr>
      <td colspan="4"><strong>Total de meses arrecadados</strong></td>
      <td colspan="12" style="text-align: right">{{ number_format($pago, 2, ',', '.') }}</td>
    </tr>
    <tr>
      <td colspan="4"><strong>Total de meses acumulados em dívidas</strong></td>
      <td colspan="12" style="text-align: right">{{ number_format($divida, 2, ',', '.') }}</td>
    </tr>
    <tr>
      <td colspan="4"><strong>Total de meses não pagos (excluindo dívidas)</strong></td>
      <td colspan="12" style="text-align: right">{{ number_format($n_pago, 2, ',', '.') }}</td>
    </tr>
    <tr>
      <td colspan="4"><strong>Total de meses não pagos (com dívidas acumuladas)</strong></td>
      <td colspan="12" style="text-align: right">{{ number_format($divida + $n_pago, 2, ',', '.') }}</td>
    </tr>
  </tfoot>
  
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

