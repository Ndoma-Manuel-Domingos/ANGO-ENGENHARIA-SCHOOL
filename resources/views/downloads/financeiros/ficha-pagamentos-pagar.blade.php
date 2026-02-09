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
                {{-- <th>Tatal Registro:</th>
                <th>{{ count($pagamentos) }}</th> --}}
                {{-- <th>Data Inicio:</th>
                <th {{ $requests['data_inicio'] ?? 'Todas.' }}</th>
                <th>Data Final:</th>
                <th>{{ $requests['data_final'] ?? 'Todas.' }}</th>
                <th>Serviço:</th>
                <th>{{ $servico ? $servico->servico : 'Todos.' }}</th> --}}
            </tr>
            <tr>
                <th>Nº</th>
                <th>Nº Ficha</th>
                <th>Pagamento</th>
                <th>Nome</th>
                <th>Data</th>
                <th title="Valores">Valor</th>
                <th title="Valores">Qtd</th>
                <th title="Descontos">Des.</th>
                <th title="Multas">Mult.</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php $somaFinal = 0; $somaIVA = 0; @endphp
            @foreach ($pagamentos as $key => $item)
            @php
            $somaFinal += $item->valor2; // ($item->quantidade * $item->valor) - $item->desconto + $item->multa;
            $somaIVA += 0;
            @endphp
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->next_factura }}</td>
                <td>{{ $item->servico->servico ?? "" }}</td>
                <td>{{ $item->model($item->model, $item->estudantes_id) }}</td>
                <td>{{ $item->data_at }}</td>
                <td>{{ number_format($item->valor, 2, ',', '.')  }}</td>
                <td>{{ number_format($item->quantidade, 2, ',', '.')  }}</td>
                <td>{{ number_format($item->desconto, 2, ',', '.') }}</td>
                <td>{{ number_format($item->multa, 2, ',', '.') }}</td>
                <td>{{ number_format( ($item->valor2) , 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table style="">
        <tbody>
            <tr style="background-color: rgba(0,0,0,1); color: #ffffff;">
                <td style="padding: 5px;text-align: right"> Total do IVA Cobrado: {{ number_format($somaIVA , 2, ',', '.') }}</td>
            </tr>
            <tr style="background-color: rgba(0,0,0,1); color: #ffffff;">
                <td style="padding: 5px;text-align: right"> Saldo Final: {{ number_format($somaFinal , 2, ',', '.') }}</td>
            </tr>
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
