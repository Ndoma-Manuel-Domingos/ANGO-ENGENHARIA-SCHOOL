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

    <div style="margin-top: 0px;border-bottom: 1px solid #000;">
        <p>
            Turma: <strong>{{ $turma->turma ?? "TODAS" }}</strong> |
            Serviço: <strong>{{ $servico->servico ?? "TODOS" }}</strong> |
            Ano Lectivo: <strong>{{ $ano_lectivo->ano ?? "TODAS" }}</strong>.
        </p>
    </div>


    <table>
        <thead>
            <tr>
                <th rowspan="2">Entidade</th>
                <th rowspan="2">Serviço</th>
                <th rowspan="2">Curso</th>
                <th rowspan="2">Classe</th>
                <th rowspan="2">Pagamento</th>
                <th rowspan="2">Preço</th>

                <th colspan="2">Data do Pagamento</th>

                <th rowspan="2">Prestações</th>

                <th colspan="2">Dias dos Pagamentos</th>


                <th colspan="3">Taxas das Multas</th>
                <th colspan="3">Dias para aplicação das Multas</th>
            </tr>

            <tr>
                <th>Início</th>
                <th>Final</th>
                <th>Início</th>
                <th>Final</th>

                <th>A</th>
                <th>B</th>
                <th>C</th>
                <th>A</th>
                <th>B</th>
                <th>C</th>
            </tr>

        </thead>

        <tbody>
            @foreach ($servicosTurmas as $item)
            @if ($item->model == "turmas")
            @php
            $turma = (new App\Models\web\turmas\Turma)->where('id', '=', $item->turmas_id)->first();
            @endphp
            @else
            @if ($item->model == "escola")
            @php
            $escola = (new App\Models\Shcool)->where('id', '=', $item->turmas_id)->first();
            @endphp
            @endif
            @endif
            <tr>
                @if ($item->model == "turmas")
                <td style="padding: 3px 0;border-bottom: 1px dashed #ccc;text-align: left">{{ $turma->turma }}</td>
                @else
                @if ($item->model == "escola")
                <td style="padding: 3px 0;border-bottom: 1px dashed #ccc;text-align: left">{{ $escola->nome }} </td>
                @endif
                @endif
                <td style="padding: 3px 0;border-bottom: 1px dashed #ccc;text-align: left">{{ $item->servico->servico ?? "" }}</td>
                <td style="padding: 3px 0;border-bottom: 1px dashed #ccc;text-align: left">{{ $item->turma->curso->curso }}</td>
                <td style="padding: 3px 0;border-bottom: 1px dashed #ccc;text-align: left">{{ $item->turma->classe->classes }}</td>
                <td style="padding: 3px 0;border-bottom: 1px dashed #ccc;text-align: left">{{ $item->pagamento }}</td>
                <td style="padding: 3px 0;border-bottom: 1px dashed #ccc;text-align: right">{{ number_format($item->preco, 2, ',', '.') }}</td>
                <td style="padding: 3px 0;border-bottom: 1px dashed #ccc;text-align: center">{{ $item->data_inicio }}</td>
                <td style="padding: 3px 0;border-bottom: 1px dashed #ccc;text-align: center">{{ $item->data_final }}</td>
                <td style="padding: 3px 0;border-bottom: 1px dashed #ccc;text-align: center">{{ $item->total_vezes }}</td>

                <td style="padding: 3px 0;border-bottom: 1px dashed #ccc;text-align: center">{{ $item->intervalo_pagamento_inicio }}</td>
                <td style="padding: 3px 0;border-bottom: 1px dashed #ccc;text-align: center">{{ $item->intervalo_pagamento_final }}</td>


                <td style="text-align: center">{{ $item->taxa_multa1 }}%</td>
                <td style="text-align: center">{{ $item->taxa_multa2 }}%</td>
                <td style="text-align: center">{{ $item->taxa_multa3 }}%</td>
                <td style="text-align: center">{{ $item->taxa_multa1_dia }}</td>
                <td style="text-align: center">{{ $item->taxa_multa2_dia }}</td>
                <td style="text-align: center">{{ $item->taxa_multa3_dia }}</td>

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
