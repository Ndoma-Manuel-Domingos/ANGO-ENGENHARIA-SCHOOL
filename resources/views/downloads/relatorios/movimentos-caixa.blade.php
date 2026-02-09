<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>{{ $titulo }}</title>
    <style>
        * {
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

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
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

    <table>
        <thead>
            <tr>
                <th colspan="4">OPERADOR: {{ $operador ? $operador->nome : 'TODOS' }}</th>
                <th colspan="2">CAIXA: {{ $caixa ? $caixa->caixa : 'TODOS' }} </th>
                <th colspan="3">DATA INICIO: {{ $data_inicio ?? 'TODOS' }}</th>
                <th colspan="3">DATA FINAL: {{ $data_final ?? 'TODOS' }}</th>
            </tr>
            <tr>
                <th>Cod</th>
                <th>Caixa</th>
                <th>Status</th>
                <th>Total TPA</th>
                <th>Total Cache</th>
                <th>Total Depositado</th>
                <th>Total Transferido</th>
                <th>Total Retirado</th>
                <th>Data Abertura</th>
                <th>Valor Inicial</th>
                <th>Saldo Final</th>
                <th>Operador</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($movimentos as $movimento)
            <tr>
                <td>{{ $movimento->id }}</td>
                <td>{{ $movimento->caixa->conta }} - {{ $movimento->caixa->caixa }}</td>
                <td>{{ $movimento->status }}</td>
                <td>{{ number_format($movimento->valor_tpa , '2', ',', '.') }}</td>
                <td>{{ number_format($movimento->valor_cache , '2', ',', '.') }}</td>
                <td>{{ number_format($movimento->valor_depositado , '2', ',', '.') }}</td>
                <td>{{ number_format($movimento->valor_transferencia , '2', ',', '.') }}</td>
                <td>{{ number_format($movimento->valor_retirado1 + $movimento->valor_retirado2 + $movimento->valor_retirado3 , '2', ',', '.') }}</td>
                <td>{{ $movimento->data_abrir }}</td>
                <td>{{ number_format($movimento->valor_abrir, '2', ',', '.') }}</td>
                <td>{{ number_format((($movimento->valor_cache + $movimento->valor_abrir + $movimento->valor_tpa) - ($movimento->valor_retirado1 - $movimento->valor_retirado2 - $movimento->valor_retirado3)) ,'2', ',', '.') }}</td>
                <td>{{ $movimento->user_abrir->nome }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Rodapé com paginação -->
    <div class="footer">
        <span class="pagenum"></span>
        <script type="text/php">
            if (isset($pdf)) {
      $pdf->page_script(function ($pageNumber, $pageCount, $pdf) {
          $pdf->text(270, 820, "Página $pageNumber de $pageCount", null, 10);
      });
    }
  </script>
    </div>

</body>
</html>
