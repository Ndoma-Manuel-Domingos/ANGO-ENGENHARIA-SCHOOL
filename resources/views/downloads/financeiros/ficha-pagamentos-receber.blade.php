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
                <th>Tatal Registro:</th>
                <th>{{ count($pagamentos) }}</th>
                <th>Data Inicio:</th>
                <th {{ $requests['data_inicio'] ?? 'Todas.' }}</th>
                <th>Data Final:</th>
                <th colspan="2">{{ $requests['data_final'] ?? 'Todas.' }}</th>
                <th colspan="2">Serviço:</th>
                <th colspan="2">{{ $servico ? $servico->servico : 'Todos.' }}</th>
            </tr>
            <tr>
                <th style="text-align: left;background-color: #ccc;padding: 5px">Nº</th>
                <th style="text-align: left;background-color: #ccc;padding: 5px">Nº Ficha</th>
                <th style="text-align: left;background-color: #ccc;padding: 5px">Pagamento</th>
                <th style="text-align: left;background-color: #ccc;padding: 5px">Nome</th>
                <th style="text-align: right;background-color: #ccc;padding: 5px">Data</th>
                <th title="Valores" style="text-align: right;background-color: #ccc;padding: 5px">Preço</th>
                <th title="Valores" style="text-align: right;background-color: #ccc;padding: 5px">IVA</th>
                <th title="Valores" style="text-align: right;background-color: #ccc;padding: 5px">Qtd</th>
                <th title="Descontos" style="text-align: right;background-color: #ccc;padding: 5px">Des.</th>
                <th title="Multas" style="text-align: right;background-color: #ccc;padding: 5px">Mult.</th>
                <th style="text-align: right;background-color: #ccc;padding: 5px">Total</th>
            </tr>
        </thead>
        <tbody>
            @php $somaFinal = 0; $somaIVA = 0; $receitas = 0; $dispesas = 0; @endphp
            @foreach ($pagamentos as $key => $item)
            @php
                $somaFinal += $item->total_pagar; // ($item->quantidade * $item->valor) - $item->desconto + $item->multa;
                $somaIVA += $item->valor_iva;
                if ($item->pagamento->caixa_at == "receita") {
                    $receitas += $item->total_pagar;
                }
                if($item->pagamento->caixa_at == "despesa"){
                    $dispesas += $item->total_pagar;
                }
            @endphp
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->pagamento->next_factura }}</td>
                <td>{{ $item->servico->servico ?? "" }}</td>
                <td>{{ $item->pagamento->model($item->pagamento->model, $item->pagamento->estudantes_id) }}</td>
                <td>{{ $item->date_att }}</td>
                <td>{{ number_format($item->valor_incidencia, 2, ',', '.')  }}</td>
                <td>{{ number_format($item->valor_iva, 2, ',', '.')  }}</td>
                <td>{{ number_format($item->quantidade, 2, ',', '.')  }}</td>
                <td>{{ number_format($item->desconto_valor, 2, ',', '.') }}</td>
                <td>{{ number_format($item->multa, 2, ',', '.') }}</td>

                @if ($item->pagamento->caixa_at == "receita")
                    <td style="color: rgb(18, 119, 48);padding: 3px 0;border-bottom: 1px dashed #ccc;text-align: right">{{ number_format( ($item->preco * $item->quantidade) - $item->desconto + $item->multa , 2, ',', '.') }} </td>
                @endif
                @if ($item->pagamento->caixa_at == "despesa")
                    <td style="color: rgb(141, 61, 61);padding: 3px 0;border-bottom: 1px dashed #ccc;text-align: right">{{ number_format( ($item->preco * $item->quantidade) - $item->desconto + $item->multa , 2, ',', '.') }} </td>
                @endif

            </tr>
            @endforeach
        </tbody>
    </table>

    @if ($requests['all'] && $requests['all'] == "todos")
    <footer>
        <table style="">
            <tbody>
                <tr style="background-color: rgb(141, 61, 61); color: #ffffff;">
                    <td style="padding: 5px;text-align: right"> Total de Saídas: {{ number_format($dispesas , 2, ',', '.') }}</td>
                </tr>
                <tr style="background-color: rgb(18, 119, 48); color: #ffffff;">
                    <td style="padding: 5px;text-align: right"> Total de Entradas: {{ number_format($receitas , 2, ',', '.') }}</td>
                </tr>
                <tr style="background-color: rgba(0,0,0,1); color: #ffffff;">
                    <td style="padding: 5px;text-align: right"> Total do IVA Cobrado: {{ number_format($somaIVA , 2, ',', '.') }}</td>
                </tr>
                <tr style="background-color: rgba(0,0,0,1); color: #ffffff;">
                    <td style="padding: 5px;text-align: right"> Diferença de receitas e dispesas: {{ number_format($receitas - $dispesas , 2, ',', '.') }}</td>
                </tr>
                <tr style="background-color: rgba(0,0,0,1); color: #ffffff;">
                    <td style="padding: 5px;text-align: right"> Saldo Final: {{ number_format($somaFinal , 2, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </footer>
    @else
    <footer>
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
    </footer>
    @endif

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
