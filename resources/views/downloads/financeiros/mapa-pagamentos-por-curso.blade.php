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

    <table style="width: 100%" class="table table-bordered">
        <tbody>
            @php
            $total_alunos_curso_geral = 0;
            $total_alunos_com_propinas_pagas_geral = 0;
            $total_alunos_com_propinas_nao_pagas_geral = 0;
            $total_receita_a_arrecadar_geral = 0;
            $total_receita_arrecadadas_geral = 0;
            $total_receita__nao_arrecadadas_geral = 0;
            @endphp

            @foreach ($cursos as $curso)
            <tr>
                <th colspan="8" class="text-uppercase bg-secondary">CURSOS: {{ $curso->curso }} </th>
            </tr>
            <tr>
                <th class="text-uppercase bg-light" style="text-align: center;background-color: #b2b2b2;">Classes</th>
                <th class="text-uppercase bg-light" style="text-align: center;background-color: #b2b2b2;">Valor Proprinas</th>
                <th class="text-uppercase bg-light" style="text-align: center;background-color: #b2b2b2;">Nº de Alunos</th>
                <th class="text-uppercase bg-light" style="text-align: center;background-color: #b2b2b2;">Alunos com propinas pagas</th>
                <th class="text-uppercase bg-light" style="text-align: center;background-color: #b2b2b2;">Alunos com propinas não pagas</th>
                <th class="text-uppercase bg-light" style="text-align: center;background-color: #b2b2b2;">Receitas A Arrecadar</th>
                <th class="text-uppercase bg-light" style="text-align: center;background-color: #b2b2b2;">Receitas Arrecadadas</th>
                <th class="text-uppercase bg-light" style="text-align: center;background-color: #b2b2b2;">Receitas Não Arrecadadas</th>
            </tr>
            @php
            $total_receita_a_arrecadar = 0;
            $total_receita_arrecadadas = 0;
            $total_receita__nao_arrecadadas = 0;
            @endphp
            @foreach ($curso->classes as $classe)
            <tr>
                <td class="text-center" style="text-align: center">{{ $classe->classes }}</td>
                <td class="text-center" style="text-align: center">{{ number_format($classe->valor_propina, 2, ",", ".") }}</td>
                <td class="text-center" style="text-align: center">{{ $classe->total_estudantes }}</td>
                <td class="text-center" style="text-align: center">{{ $classe->total_pago }}</td>
                <td class="text-center" style="text-align: center">{{ $classe->total_nao_pago }}</td>
                <td class="text-center" style="text-align: center">{{ number_format($classe->valor_propina * $classe->total_estudantes, 2, ",", ".") }}</td>
                <td class="text-center" style="text-align: center">{{ number_format($classe->valor_propina * $classe->total_pago, 2, ",", ".") }}</td>
                <td class="text-center" style="text-align: center">{{ number_format($classe->valor_propina * $classe->total_nao_pago, 2, ",", ".") }}</td>
            </tr>
            @php
            $total_receita_a_arrecadar += ($classe->valor_propina * $classe->total_estudantes);
            $total_receita_arrecadadas += ($classe->valor_propina * $classe->total_pago);
            $total_receita__nao_arrecadadas += ($classe->valor_propina * $classe->total_nao_pago);
            @endphp
            @endforeach

            @php
            $total_alunos_curso_geral += $curso->total_geral;
            $total_alunos_com_propinas_pagas_geral += $curso->total_pago;
            $total_alunos_com_propinas_nao_pagas_geral += $curso->total_nao_pago;
            $total_receita_a_arrecadar_geral += $total_receita_a_arrecadar;
            $total_receita_arrecadadas_geral += $total_receita_arrecadadas;
            $total_receita__nao_arrecadadas_geral += $total_receita__nao_arrecadadas;
            @endphp

            <tr>
                <td class="text-center" style="text-align: center"></td>
                <td class="text-center" style="text-align: center"></td>
                <td class="text-center" style="text-align: center">{{ $curso->total_geral }}</td>
                <td class="text-center" style="text-align: center">{{ $curso->total_pago }}</td>
                <td class="text-center" style="text-align: center">{{ $curso->total_nao_pago }}</td>
                <td class="text-center" style="text-align: center">{{ number_format($total_receita_a_arrecadar, 2, ",", ".") }}</td>
                <td class="text-center" style="text-align: center">{{ number_format($total_receita_arrecadadas, 2, ",", ".") }}</td>
                <td class="text-center" style="text-align: center">{{ number_format($total_receita__nao_arrecadadas, 2, ",", ".") }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th class="text-center" style="text-align: center">-</th>
                <th class="text-center" style="text-align: center">-</th>
                <th class="text-center" style="text-align: center">{{ number_format($total_alunos_curso_geral, 2, ',', '.') }}</th>
                <th class="text-center" style="text-align: center">{{ number_format($total_alunos_com_propinas_pagas_geral, 2, ',', '.') }}</th>
                <th class="text-center" style="text-align: center">{{ number_format($total_alunos_com_propinas_nao_pagas_geral, 2, ',', '.') }}</th>
                <th class="text-center" style="text-align: center">{{ number_format($total_receita_a_arrecadar_geral, 2, ',', '.') }}</th>
                <th class="text-center" style="text-align: center">{{ number_format($total_receita_arrecadadas_geral, 2, ',', '.') }}</th>
                <th class="text-center" style="text-align: center">{{ number_format($total_receita__nao_arrecadadas_geral, 2, ',', '.') }}</th>
            </tr>
        </tfoot>
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
